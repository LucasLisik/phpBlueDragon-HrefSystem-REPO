<?php

echo '<ol class="breadcrumb">
<li><a href="'.base_url().'">'.$this->lang->line('a1050').'</a></li>
<li><a href="'.base_url().'">'.$this->lang->line('a1051').'</a></li>
<li><a href="'.base_url('details/'.$ProjectId).'">'.$ProjectName.'</a></li>
<li class="active">'.$this->lang->line('a1052').'</li>
</ol>';

echo '<h1>'.$Title.': '.$ProjectName.'</h1>';

echo $Content.'<br /><br />';

$ResultDB = $this->System_model->ProjectSelectAllArchive($ProjectId);

foreach($ResultDB->result() as $row)
{    
    echo '<a href="'.base_url('view-archive-report/'.$row->archive_id).'" class="btn btn-info btn-xs" style="margin-bottom: 5px;">'.$row->archive_date.'</a> ';
}

?>