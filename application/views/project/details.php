<?php

echo '<ol class="breadcrumb">
<li><a href="'.base_url().'">'.$this->lang->line('a1006').'</a></li>
<li><a href="'.base_url().'">'.$this->lang->line('a1007').'</a></li>
<li class="active">'.$ProjectName.'</li>
</ol>';

echo '<h1>'.$Title.': '.$ProjectName.'</h1>';

echo $Content.'<br /><br />';

$TableHTTPCom[100] = '100 Continue';
$TableHTTPCom[101] = '101 Switching Protocols';
$TableHTTPCom[102] = '102 Processing (WebDAV)';
$TableHTTPCom[200] = '200 OK';
$TableHTTPCom[201] = '201 Created';
$TableHTTPCom[202] = '202 Accepted';
$TableHTTPCom[203] = '203 Non-Authoritative Information';
$TableHTTPCom[204] = '204 No Content';
$TableHTTPCom[205] = '205 Reset Content';
$TableHTTPCom[206] = '206 Partial Content';
$TableHTTPCom[207] = '207 Multi-Status (WebDAV)';
$TableHTTPCom[208] = '208 Already Reported (WebDAV)';
$TableHTTPCom[226] = '226 IM Used';
$TableHTTPCom[300] = '300 Multiple Choices';
$TableHTTPCom[301] = '301 Moved Permanently';
$TableHTTPCom[302] = '302 Found';
$TableHTTPCom[303] = '303 See Other';
$TableHTTPCom[304] = '304 Not Modified';
$TableHTTPCom[305] = '305 Use Proxy';
$TableHTTPCom[306] = '306 (Unused)';
$TableHTTPCom[307] = '307 Temporary Redirect';
$TableHTTPCom[308] = '308 Permanent Redirect (experiemental)';
$TableHTTPCom[400] = '400 Bad Request';
$TableHTTPCom[401] = '401 Unauthorized';
$TableHTTPCom[402] = '402 Payment Required';
$TableHTTPCom[403] = '403 Forbidden';
$TableHTTPCom[404] = '404 Not Found';
$TableHTTPCom[405] = '405 Method Not Allowed';
$TableHTTPCom[406] = '406 Not Acceptable';
$TableHTTPCom[407] = '407 Proxy Authentication Required';
$TableHTTPCom[408] = '408 Request Timeout';
$TableHTTPCom[409] = '409 Conflict';
$TableHTTPCom[410] = '410 Gone';
$TableHTTPCom[411] = '411 Length Required';
$TableHTTPCom[412] = '412 Precondition Failed';
$TableHTTPCom[413] = '413 Request Entity Too Large';
$TableHTTPCom[414] = '414 Request-URI Too Long';
$TableHTTPCom[415] = '415 Unsupported Media Type';
$TableHTTPCom[416] = '416 Requested Range Not Satisfiable';
$TableHTTPCom[417] = '417 Expectation Failed';
$TableHTTPCom[418] = '418 I\'m a teapot (RFC 2324)';
$TableHTTPCom[420] = '420 Enhance Your Calm (Twitter)';
$TableHTTPCom[422] = '422 Unprocessable Entity (WebDAV)';
$TableHTTPCom[423] = '423 Locked (WebDAV)';
$TableHTTPCom[424] = '424 Failed Dependency (WebDAV)';
$TableHTTPCom[425] = '425 Reserved for WebDAV';
$TableHTTPCom[426] = '426 Upgrade Required';
$TableHTTPCom[428] = '428 Precondition Required';
$TableHTTPCom[429] = '429 Too Many Requests';
$TableHTTPCom[431] = '431 Request Header Fields Too Large';
$TableHTTPCom[444] = '444 No Response (Nginx)';
$TableHTTPCom[450] = '450 Blocked by Windows Parental Controls (Microsoft)';
$TableHTTPCom[499] = '499 Client Closed Request (Nginx)';
$TableHTTPCom[500] = '500 Internal Server Error';
$TableHTTPCom[501] = '501 Not Implemented';
$TableHTTPCom[502] = '502 Bad Gateway';
$TableHTTPCom[503] = '503 Service Unavailable';
$TableHTTPCom[504] = '504 Gateway Timeout';
$TableHTTPCom[505] = '505 HTTP Version Not Supported';
$TableHTTPCom[506] = '506 Variant Also Negotiates (Experimental)';
$TableHTTPCom[507] = '507 Insufficient Storage (WebDAV)';
$TableHTTPCom[508] = '508 Loop Detected (WebDAV)';
$TableHTTPCom[509] = '509 Bandwidth Limit Exceeded (Apache)';
$TableHTTPCom[510] = '510 Not Extended';
$TableHTTPCom[511] = '511 Network Authentication Required';
$TableHTTPCom[598] = '598 Network read timeout error';
$TableHTTPCom[599] = '599 Network connect timeout error';

if($ProjectDeleted)
{
    echo '<div class="alert alert-warning">'.$this->lang->line('a1008').'</div>';
}
    
$ResultDB = $this->System_model->GetSystemConfig();

foreach($ResultDB->result() as $row)
{
	$ConfigTable[$row->config_name] = $row->config_value;
}

$SortColumn = $ConfigTable['column2'];
$SortOrder = $ConfigTable['order2'];

$AddDataSort = false;

if($this->input->get('column2') != "")
{
    $SortColumn = $this->input->get('column2');
    $AddDataSort = true;
}

if($this->input->get('order2') != "")
{
    $SortOrder = $this->input->get('order2');
    $AddDataSort = true;
}

if($AddDataSort)
{
    $this->System_model->UpdateSort2($SortColumn,$SortOrder);
}

$ResultDB = $this->System_model->GetProjectLinksSort($ProjectId,$SortColumn,$SortOrder);

if($SortColumn == 'link_url'){ if($SortOrder == 'asc'){ $LinkUrlASC = ' style="font-weight: bold; " '; } else { $LinkUrlDESC = ' style="font-weight: bold; " '; } } 
if($SortColumn == 'link_text'){ if($SortOrder == 'asc'){ $LinkTextASC = ' style="font-weight: bold; " '; } else { $LinkTextDESC = ' style="font-weight: bold; " '; } }
if($SortColumn == 'link_exists'){ if($SortOrder == 'asc'){ $LinkExistASC = ' style="font-weight: bold; " '; } else { $LinkExistDESC = ' style="font-weight: bold; " '; } }
if($SortColumn == 'link_robots'){ if($SortOrder == 'asc'){ $LinkRobotsASC = ' style="font-weight: bold; " '; } else { $LinkRobotsDESC = ' style="font-weight: bold; " '; } }
if($SortColumn == 'link_nofollow'){ if($SortOrder == 'asc'){ $LinkNoFollowASC = ' style="font-weight: bold; " '; } else { $LinkNoFollowDESC = ' style="font-weight: bold; " '; } }
if($SortColumn == 'link_meta'){ if($SortOrder == 'asc'){ $LinkMetaASC = ' style="font-weight: bold; " '; } else { $LinkMetaDESC = ' style="font-weight: bold; " '; } }
if($SortColumn == 'link_http'){ if($SortOrder == 'asc'){ $LinkHttpASC = ' style="font-weight: bold; " '; } else { $LinkHttpDESC = ' style="font-weight: bold; " '; } }
if($SortColumn == 'link_howmany'){ if($SortOrder == 'asc'){ $LinkHowManyASC = ' style="font-weight: bold; " '; } else { $LinkHowManyDESC = ' style="font-weight: bold; " '; } }
    
echo '<a href="'.base_url('link-add/'.$ProjectId).'" class="btn btn-info">'.$this->lang->line('a1009').'</a> ';
echo '<a href="'.base_url('check-link/'.$ProjectId).'" class="btn btn-success">'.$this->lang->line('a1010').'</a>';

echo form_open('details/'.$ProjectId);

    echo '<div style="padding: 10px;">';
    echo '<span style="cursor: pointer; cursor: hand;" OnClick="CheckAll(\'DivFields\');" class="btn btn-default btn-xs">'.$this->lang->line('a1011').'</span>&nbsp;';
    echo '<span style="cursor: pointer; cursor: hand;" OnClick="UncheckAll(\'DivFields\');" class="btn btn-default btn-xs">'.$this->lang->line('a1012').'</span>&nbsp;';
    echo form_hidden(array('action' => 'deleteallchecked'));
    echo form_submit(array('name' => 'submitbutton', 'value' => ''.$this->lang->line('a1013').'', 'class' => 'btn btn-danger btn-xs'));;
    echo '</div>';

echo '<div id="DivFields" name="DivFields">';

echo '<div class="row RowColor3">
    <div class="col-md-3">'.$this->lang->line('a1014').' <a href="'.base_url('details/'.$ProjectId.'?column2=link_url&order2=asc').'" '.$LinkUrlASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column2=link_url&order2=desc').'" '.$LinkUrlDESC.'>&darr;</a> Tekst <a href="'.base_url('details/'.$ProjectId.'?column2=link_text&order2=asc').'" '.$LinkTextASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column2=link_text&order2=desc').'" '.$LinkTextDESC.'>&darr;</a></div>
    <div class="col-md-1" style="text-align: center;">'.$this->lang->line('a1015').'<br /><a href="'.base_url('details/'.$ProjectId.'?column2=link_exists&order2=asc').'" '.$LinkExistASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column=2link_exists&order2=desc').'" '.$LinkExistDESC.'>&darr;</a></div>
    <div class="col-md-1" style="text-align: center;">'.$this->lang->line('a1016').'<br /><a href="'.base_url('details/'.$ProjectId.'?column2=link_robots&order2=asc').'" '.$LinkRobotsASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column2=link_robots&order2=desc').'" '.$LinkRobotsDESC.'>&darr;</a></div>
    <div class="col-md-1" style="text-align: center;">'.$this->lang->line('a1017').'<br /><a href="'.base_url('details/'.$ProjectId.'?column2=link_nofollow&order2=asc').'" '.$LinkNoFollowASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column2=link_nofollow&order2=desc').'" '.$LinkNoFollowDESC.'>&darr;</a></div>
    <div class="col-md-2" style="text-align: center;">'.$this->lang->line('a1018').'<br /><a href="'.base_url('details/'.$ProjectId.'?column2=link_meta&order2=asc').'" '.$LinkMetaASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column2=link_meta&order2=desc').'" '.$LinkMetaDESC.'>&darr;</a></div>
    <div class="col-md-2" style="text-align: center;">'.$this->lang->line('a1019').'<br /><a href="'.base_url('details/'.$ProjectId.'?column2=link_http&order2=asc').'" '.$LinkHttpASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column2=link_http&order2=desc').'" '.$LinkHttpDESC.'>&darr;</a></div>
    <div class="col-md-1" style="text-align: center;">'.$this->lang->line('a1020').'<br /><a href="'.base_url('details/'.$ProjectId.'?column2=link_howmany&order2=asc').'" '.$LinkHowManyASC.'>&uarr;</a> <a href="'.base_url('details/'.$ProjectId.'?column2=link_howmany&order2=desc').'" '.$LinkHowManyDESC.'>&darr;</a></div>
    <div class="col-md-1" style="text-align: right;">'.$this->lang->line('a1021').'</div>
    </div>';
   
$Color = 0;
 
foreach($ResultDB->result() as $row)
{
    if($row->link_exists == '')
    {
        $Exists = '-';
        $Robots = '-';
        $NoFollow = '-';
        $Meta = '-';
        $HttpHeader = '-';
        $HowMany = '-';
    }
    else
    {
        if($row->link_exists == 'y'){$Exists = '<span class="btn btn-info btn-xs">'.$this->lang->line('a1022').'</span>';}else{$Exists = '<span class="btn btn-danger btn-xs">'.$this->lang->line('a1023').'</span>';}
        if($row->link_robots == 'y'){$Robots = '<span class="btn btn-info btn-xs">'.$this->lang->line('a1022').'</span>';}else{$Robots = '<span class="btn btn-danger btn-xs">'.$this->lang->line('a1023').'</span>';}
        if($row->link_nofollow == 'y'){$NoFollow = '<span class="btn btn-danger btn-xs">'.$this->lang->line('a1022').'</span>';}else{$NoFollow = '<span class="btn btn-info btn-xs">'.$this->lang->line('a1023').'</span>';}
        if($row->link_meta == 'n'){$Meta = '<span class="btn btn-info btn-xs">'.$this->lang->line('a1024').'</span>';}else{
            if (strpos($row->link_meta, 'noindex') !== false) {$HasBad = true;}
            if (strpos($row->link_meta, 'nofollow') !== false) {$HasBad = true;}
            if($HasBad)
            {
                $Meta = '<span class="btn btn-danger btn-xs">'.$row->link_meta.'</span>';
            }
            else
            {
                $Meta = '<span class="btn btn-info btn-xs">'.$row->link_meta.'</span>';
            }
            }
        
        if($TableHTTPCom[$row->link_http] != "")
        {
            if($row->link_http == '200')
            {
                $HttpHeader = '<span class="btn btn-info btn-xs">'.$TableHTTPCom[$row->link_http].'</span>';
            }
            else
            {
                $HttpHeader = '<span class="btn btn-danger btn-xs">'.$TableHTTPCom[$row->link_http].'</span>';
            }
        }
        elseif($row->link_http == 'n')
        {
            $HttpHeader = '<span class="btn btn-danger btn-xs">'.$this->lang->line('a1025').'</span>';
        }
        else
        {
            $HttpHeader = '<span class="btn btn-danger btn-xs">'.$row->link_http.'</span>';
        }
        
        if($row->link_howmany == 0)
        {
            $HowMany = '<span class="btn btn-info btn-xs">'.$row->link_howmany.'</span>';
        }
        else
        {
            $HowMany = '<span class="btn btn-success btn-xs">'.$row->link_howmany.'</span>';
        }
    }
    
    if($Color == 0)
    {
        echo '<div class="row RowColor1">';
        $Color = 1;
    }
    else
    {
        echo '<div class="row RowColor2">';
        $Color = 0;
    }
    
    echo '
    <div class="col-md-3">'.form_checkbox('deletelinkis[]', $row->link_id).' '.$row->link_url.'<br /><span style="font-size: 12px; font-style: italic;">'.$row->link_text.'</span></div>
    <div class="col-md-1" style="text-align: center;">'.$Exists.'</div>
    <div class="col-md-1" style="text-align: center;">'.$Robots.'</div>
    <div class="col-md-1" style="text-align: center;">'.$NoFollow.'</div>
    <div class="col-md-2" style="text-align: center;">'.$Meta.'</div>
    <div class="col-md-2" style="text-align: center;">'.$HttpHeader.'</div>
    <div class="col-md-1" style="text-align: center;">'.$HowMany.'</div>
    <div class="col-md-1" style="text-align: right;">
    <a href="'.base_url('link-edit/'.$row->link_id).'" title="'.$this->lang->line('a1027').'" class="btn btn-info btn-xs">'.$this->lang->line('a1026').'</a>
    <a href="JavaScript:DeteleInfo(\''.base_url().'link-delete/'.$ProjectId.'/'.$row->link_id.'\',\''.$this->lang->line('a1028').'\');" title="'.$this->lang->line('a1029').'" class="btn btn-danger btn-xs">'.$this->lang->line('a1030').'</a></div>
    </div>';
}

echo '</div>';

/*
echo form_submit(array('name' => 'submitbutton', 'value' => 'Usuń zaznaczone linki'));;
echo form_close();
echo '<span style="cursor: pointer; cursor: hand;" OnClick="CheckAll(\'DivFields\');">Zaznacz wszystkie</span> ';
echo '<span style="cursor: pointer; cursor: hand;" OnClick="UncheckAll(\'DivFields\');">Odznacz wszystkie</span>';
*/

echo '<div style="padding: 10px;">';
echo '<span style="cursor: pointer; cursor: hand;" OnClick="CheckAll(\'DivFields\');" class="btn btn-default btn-xs">'.$this->lang->line('a1031').'</span>&nbsp;';
echo '<span style="cursor: pointer; cursor: hand;" OnClick="UncheckAll(\'DivFields\');" class="btn btn-default btn-xs">'.$this->lang->line('a1032').'</span>&nbsp;';
echo form_submit(array('name' => 'submitbutton', 'value' => ''.$this->lang->line('a1033').'', 'class' => 'btn btn-danger btn-xs'));;
echo '</div>';

$ResultDB = $this->System_model->ProjectSelectArchive($ProjectId);

$IsArchive = false;

$CountFirst = 0;

foreach($ResultDB->result() as $row)
{
    $IsArchive = true;
    
    if($CountFirst == 0)
    {
        echo '<h2>'.$this->lang->line('a1034').'</h2>';
    }
    
    $CountFirst = 1;
    
    echo '<a href="'.base_url('view-archive-report/'.$row->archive_id).'" class="btn btn-info btn-xs">'.$row->archive_date.'</a> ';
}

if($IsArchive == true)
{
    echo ' <a href="'.base_url('view-archive/'.$ProjectId).'" class="btn btn-default btn-xs">'.$this->lang->line('a1035').'</a>';
}

?>
<script>
function CheckAll(DivNameId)
{
    $('#' + DivNameId + ' :checkbox').each(function ()
    {
        $(this).prop('checked', true);
    });
}

function UncheckAll(DivNameId)
{
    $('#' + DivNameId + ' :checkbox').each(function ()
    {
        $(this).prop('checked', false);
    });
}

</script>