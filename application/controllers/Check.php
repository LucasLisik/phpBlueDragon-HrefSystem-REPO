<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_time_limit(0);
error_reporting(E_ALL & ~E_WARNING & ~E_PARSE & ~E_NOTICE);

class Check extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('System_model');
        //$this->output->enable_profiler(true);
        
        $this->lang->load('system', $this->config->item('language'));
    }
    
    private function CheckCanCheck()
    {
        if($this->System_model->CheckLicenseExists() == 'yes')
        {
            $SystemCanAdd = true;
        }
        else
        {
            $ResultDB = $this->System_model->GetHowManyProject();
            
            $HowManyProjects = 0;
            
            foreach($ResultDB->result() as $row)
            {
                $HowManyProjects = $row->HowMany;   
            }
            
            if($HowManyProjects < 2)
            {
                $SystemCanAdd = true;
            }   
            else
            {
                $SystemCanAdd = false;
            }     
            
        }
        
        return $SystemCanAdd;
    }
    
    public function hand($ProjectId)
    {
        if($this->CheckCanCheck())
        {
            $ResultDB = $this->System_model->GetOneLinkFromProject($ProjectId);
            
            foreach($ResultDB->result() as $row)
            {
                $DataFromLink = $this->CheckOutLinkStatus($row->link_id);
                
                if($DataFromLink['comm'] = 'y')
                {
                    echo '<div class="alert alert-success">'.$this->lang->line('a0858').''.$row->link_url.''.$this->lang->line('a0856').'</div>';
                }
                else
                {
                    echo '<div class="alert alert-danger">'.$this->lang->line('a0858').''.$row->link_url.''.$this->lang->line('a0857').'</div>';
                }
                
                
                $this->System_model->UpdateLinkHandStatus($row->link_id);
            }
        }
    }
    
    public function cron()
    {
        if($this->CheckCanCheck())
        {
            $ResultDB = $this->System_model->GetSystemConfig();
    
            foreach($ResultDB->result() as $row)
            {
            	$ConfigTable[$row->config_name] = $row->config_value;
            }
            
            for($i=0;$i<$ConfigTable['cron'];$i++)
            {
                $LinkId = $this->System_model->GetOneLinkFromProjectCron();
                
                echo $LinkId.'<br />';
                
                if($LinkId)
                {
                    echo $this->CheckOutLinkStatus($LinkId);
                    $this->System_model->UpdateLinkCronStatus($LinkId);
                    
                    $ResultDB = $this->System_model->GetLinkProjectData($LinkId);
                
                    foreach($ResultDB->result() as $row)
                    {
                        $ProjectId = $row->link_project_id;
                    }
                    
                    $ResultDB = $this->System_model->CheckEmptyLink($ProjectId);
                    
                    foreach($ResultDB->result() as $row)
                    {
                        $LinkHowMany = $row->HowMany;
                    }
        
                    if($LinkHowMany == 0)
                    {
                        $this->report($ProjectId);
                    }
                }
            }
        }
    }
    
    private function CheckOutLinkStatus($LinkId)
    {
        //
        // Wybranie linku
        //
        $ResultDB4 = $this->System_model->GetLinkData($LinkId);
                
        foreach($ResultDB4->result() as $row4)
        {
            $LinkHref = $row4->link_url;
            $LinkText = $row4->link_text;
            $LinkProject = $row4->link_project_id;
            
            //print_r($row4);
        }
        
        //
        // Wybranie projektu
        //
        $ResultDB4 = $this->System_model->ProjectGetById($LinkProject);
                
        foreach($ResultDB4->result() as $row4)
        {
            $ProjectHref = $row4->project_href;
            
            //print_r($row4);
        }
        
        
        //
        // Pobieranie strony
        //
        $LinkHref = htmlspecialchars_decode($LinkHref);
        $LinkHref = trim($LinkHref);
        
        
        $CurlId = curl_init($LinkHref);
        curl_setopt($CurlId, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($CurlId, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.googlebot.com/bot.html)');
        curl_setopt($CurlId, CURLOPT_CONNECTTIMEOUT, 15); 
        curl_setopt($CurlId, CURLOPT_TIMEOUT, 40);
        
        $CurlResponse = curl_exec($CurlId);
        $InfoHeaders = curl_getinfo($CurlId);
        if(curl_errno($CurlId)) 
        {
             $DataFromLink['comm'] = 'n';
             //echo 'Błąd';
        }
        else
        {
            $DataFromLink['comm'] = 'y';
            //echo 'OK';
        }
        curl_close($CurlId);
        
        
        $CORE_HeaderStatus = null;
        
        //
        // Nagłówek
        //
        $CORE_HeaderStatus = $InfoHeaders['http_code'];

        // 
        // Przekierowanie
        //
        if($CORE_HeaderStatus == 301 OR $CORE_HeaderStatus == 302)
        {
            $UrlHeaderStatus = $CORE_HeaderStatus;
            
            if($InfoHeaders['redirect_url'])
            {
                $UrlToCheck2 = htmlspecialchars_decode($InfoHeaders['redirect_url']);
                $UrlToCheck2 = trim($UrlToCheck2);
                $CurlId = curl_init($UrlToCheck2);
                curl_setopt($CurlId, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($CurlId, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.googlebot.com/bot.html)');
                curl_setopt($CurlId, CURLOPT_CONNECTTIMEOUT, 20); 
                curl_setopt($CurlId, CURLOPT_TIMEOUT, 40); // 10 sekund
                $CurlResponse = curl_exec($CurlId);
                $InfoHeaders = curl_getinfo($CurlId);
                if(curl_errno($CurlId)){$DataFromLink['comm'] = 'n';}else{$DataFromLink['comm'] = 'y';}
                curl_close($CurlId);
                
                //
                // Nagłówek
                //
                $CORE_HeaderStatus = $InfoHeaders['http_code'];
            }
        }
        
        
        //
        // Sprawdzenie pliku robots.txt
        //
        $IsAviable = $this->robots_allowed($LinkHref);
        
        if($IsAviable)
        {
            $CORE_RobotAviable = 'y';
        }
        else
        {
            $CORE_RobotAviable = 'n';
        }
        
        //
        // Sprawdzenie meta pod względem czy można przeszukiwać stronę
        //
            $DOM = new DOMDocument;
            $DOM->strictErrorChecking = false;
            libxml_use_internal_errors(false);
            $DOM->loadHTML($CurlResponse);
            //echo $CurlResponse;
            $CORE_MetaAviable = null;
            
            foreach ($DOM->getElementsByTagName('meta') as $Node) 
            {
                $Value = $Node->getAttribute('name');
                if($Value == 'robots')
                {
                    $CORE_MetaAviable = $Node->getAttribute('content');
                }
            }
            
            if($CORE_MetaAviable == null)
            {
                $CORE_MetaAviable = 'n';
            }
            
            
            
            //
            // Sprawdzanie linków wychodzących
            //
            $CORE_LinksOut = 0;
                
            foreach ($DOM->getElementsByTagName('a') as $Node)
            {              
                $GetLink = $Node->getAttribute('href');
                
                //echo $GetLink;  
                          
                $PozitionString = strpos($GetLink, 'http://');
                
                if($PozitionString !== false) 
                {
                    $ResultURL = parse_url($LinkHref);
                    $ResultURL['host'] = str_replace("www.", "", strtolower($ResultURL['host']));
                    
                    $PozitionString2 = strpos($GetLink, $ResultURL['host']);
                    
                    if($PozitionString2 === false)
                    {
                        $CORE_LinksOut++;
                    }
                    
                    
                }
            }
            
            
            $LinkText = html_entity_decode($LinkText);
            
            if($LinkText == ''){$LinkText = '*';}
            
            $UrlInOnPage = null;
            $UrlHasNoFollow = null;
            
            $IsStared = explode('*', $ProjectHref);
            
            $UrlIsStared = false;
            $ProjectHref2 = '';
            
            if(count($IsStared) > 1)
            {
                //echo 'jest5';
                $UrlIsStared = true;
                
                $ProjectUrlSearch = preg_quote($ProjectHref, '/');
                $ProjectUrlSearch = str_replace('\*', '[.]*', $ProjectUrlSearch);
            }
            else
            {
                // bez gwiazdki
                $UrlIsStared = false;
                //echo 'jest6';
                $ProjectUrlSearch = $ProjectHref;
            }
            
            $ValidationSuccess = false;
            $ValidationSuccessText = false;
            
            //
            // Sprawdź łącze
            //
            
            foreach ($DOM->getElementsByTagName('a') as $Node)
            {              
                $ValueLink = $Node->getAttribute('href');
                $ValueLinkNoFollow = $Node->getAttribute('rel');
                
                $DataFromLink['data'] .= $ProjectUrlSearch.'-'.$ValueLink.'<br />';
                
                $RegExUrl = "/".$ProjectUrlSearch."/";
                
                if($UrlIsStared)
                {
                    if(preg_match($RegExUrl, $ValueLink))
                    {
                        $ValidationSuccess = true;
                        //echo 'jest3';
                    }
                    
                    //if(preg_match($RegExUrl, 'http://'.$ValueLink))
                    //{
                        //$ValidationSuccess = true;
                    //}
                }
                else
                {
                    //echo $ValueLink.'<br />';
                    //echo $ProjectUrlSearch.'<br /><br />';
                    
                    if($ValueLink == $ProjectUrlSearch)
                    {
                        $ValidationSuccess = true;
                    }
                }
                
                if($ValidationSuccess == true)
                {
                    if($ValueLinkNoFollow == 'nofollow')
                    {
                        $CORE_UrlHasNoFollow = 'y';
                    }
                }
            }
            
            //
            // Sprawdź napis
            //
            
            $TextIsStared = false;
            
            $IsStaredText = explode('*', $LinkText);
            
            if(count($IsStaredText) > 1)
            {
                $TextIsStared = true;
                
                $ProjectTextSearch = preg_quote($LinkText, '/');
                $ProjectTextSearch = str_replace('\*', '[.]*', $ProjectTextSearch);   
                
                $RegExText = "/".$ProjectTextSearch."/";
                
                //echo $RegExText;
                if(preg_match($RegExText, $CurlResponse)) 
                {
                    $ValidationSuccessText = true;
                    //echo 'Jest2';
                }
                
                //echo 'IsStarees';
            }
            else
            {
                $TextIsStared = false;
    
                $ProjectTextSearch = preg_quote($LinkText, '/');
                //$ProjectTextSearch = str_replace('\*', '[.]*', $ProjectTextSearch);   
                
                if(preg_match("/".$ProjectTextSearch."/i", $CurlResponse)) 
                {
                    $ValidationSuccessText = true;
                }
            }
            
            //$DataFromLink['data'] .= '<br />RegUrl: '.$RegExUrl;
            //$DataFromLink['data'] .= '<br />RegText: '.$RegExText;
        //}
        
        //if($ValidationSuccess){echo 'Jest url';}
        //if($ValidationSuccessText){echo 'Jest link';}
        if($ValidationSuccess && $ValidationSuccessText)
        {
            $CORE_UrlInOnPage = 'y';
        }
        
        if($CORE_RobotAviable == "")
        {
            $CORE_RobotAviable = 'n';
        }
        
        if($CORE_HeaderStatus == "")
        {
            //$CORE_HeaderStatus = 'Brak nagłówka';
            $CORE_HeaderStatus = 'n';
        }
        
        if($CORE_MetaAviable == "")
        {
            //$CORE_MetaAviable = 'Brak znacznika';
            $CORE_MetaAviable = 'n';
        }
        
        if($CORE_UrlInOnPage == "")
        {
            $CORE_UrlInOnPage = 'n';
        }
        
        if($CORE_UrlHasNoFollow == "")
        {
            $CORE_UrlHasNoFollow = 'n';
        }
        
        // Aktualizacja danych na temat łącza w bazie danych
        $this->System_model->UpdateLink($LinkId,$CORE_RobotAviable,$CORE_MetaAviable,$CORE_UrlInOnPage,$CORE_UrlHasNoFollow,$CORE_HeaderStatus,$CORE_LinksOut);
        
        //file_put_contents('text.txt', 'aaaaa', FILE_APPEND); 
        
        //return $DataFromLink;
        
        
        return $DataFromLink;
    }
    
    private function robots_allowed($url, $useragent=false)
    {
        // parse url to retrieve host and path
        $parsed = parse_url($url);
        
        $agents = array(preg_quote('*'));
        if($useragent) $agents[] = preg_quote($useragent, '/');
        $agents = implode('|', $agents);
        
        // location of robots.txt file, only pay attention to it if the server says it exists
        if(function_exists('curl_init')) 
        {
            $handle = curl_init("http://{$parsed['host']}/robots.txt");
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if($httpCode == 200) 
            {
                $robotstxt = explode("\n", $response);
            } 
            else 
            {
                $robotstxt = false;
            }
            curl_close($handle);
        } 
        else 
        {
            $robotstxt = @file("http://{$parsed['host']}/robots.txt");
        }
        
        // if there isn't a robots, then we're allowed in
        if(empty($robotstxt)) return true;
        
        $rules = array();
        $ruleApplies = false;
        foreach($robotstxt as $line) 
        {
            // skip blank lines
            if(!$line = trim($line)) continue;
            
            // following rules only apply if User-agent matches $useragent or '*'
            if(preg_match('/^\s*User-agent: (.*)/i', $line, $match)) 
            {
                $ruleApplies = preg_match("/($agents)/i", $match[1]);
                continue;
            }
            if($ruleApplies) 
            {
                list($type, $rule) = explode(':', $line, 2);
                $type = trim(strtolower($type));
                // add rules that apply to array for testing
                $rules[] = array(
                'type' => $type,
                'match' => preg_quote(trim($rule), '/'),
                );
            }
        }
        
        $isAllowed = true;
        $currentStrength = 0;
        foreach($rules as $rule) 
        {
        // check if page hits on a rule
            if(preg_match("/^{$rule['match']}/", $parsed['path'])) 
            {
                // prefer longer (more specific) rules and Allow trumps Disallow if rules same length
                $strength = strlen($rule['match']);
                if($currentStrength < $strength)
                {
                    $currentStrength = $strength;
                    $isAllowed = ($rule['type'] == 'allow') ? true : false;
                } 
                elseif($currentStrength == $strength && $rule['type'] == 'allow') 
                {
                    $currentStrength = $strength;
                    $isAllowed = true;
                }
            }
        }
        
        return $isAllowed;
    }
    
    public function report($ProjectId)
    {
        $this->System_model->ProjectMakeReport($ProjectId);
        
        echo '<div class="alert alert-info">'.$this->lang->line('a0859').'</div>';
    }
    
    /*
    $IsSomethingToCheck = $this->System_model->CheckCountProject();
        
        if($IsSomethingToCheck)
        {
            
        }
        */
}

?>