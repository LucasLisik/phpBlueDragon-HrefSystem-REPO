<?php

echo '<ol class="breadcrumb">
<li><a href="'.base_url().'">'.$this->lang->line('a1050').'</a></li>
<li><a href="'.base_url().'">'.$this->lang->line('a1051').'</a></li>
<li><a href="'.base_url('details/'.$ProjectId).'">'.$ProjectName.'</a></li>
<li class="active">'.$this->lang->line('a1058').'</li>
</ol>';


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

echo '<h1>'.$Title.'</h1>';

echo $Content.'<br /><br />';

echo '<div id="ResultFromQuery"></div>';

echo '<div id="WaitAMoment" style="">
<img src="'.base_url('library/ajax-loader.gif').'" width="16" height="16" />
</div>';

if($SystemCanAdd)
{                
?>
<script>
$("#WaitAMoment").hide();

function LinkCheck()
{
    $("#WaitAMoment").show();
    
    $.post("<?php echo base_url(); ?>check/hand/<?php echo $ProjectId; ?>", function(data) 
    {
      $("#ResultFromQuery").append(data);
      
      if(data != "")
      {
        LinkCheck();
      }
      else
      {
        GenerateReport();
      }
    });
}

LinkCheck();

function GenerateReport()
{
    $.post("<?php echo base_url(); ?>check/report/<?php echo $ProjectId; ?>", function(data) 
    {
      $("#ResultFromQuery").append(data);
    });
    
    $("#WaitAMoment").hide();
}
</script>
<?php
}
else
{
    echo '<div class="alert alert-danger">'.$this->lang->line('a1071').'</div>';
}
?>
