<?php

echo '<ol class="breadcrumb">
<li><a href="'.base_url().'">'.$this->lang->line('a0981').'</a></li>
<li><a href="'.base_url().'">'.$this->lang->line('a0982').'</a></li>
<li><a href="'.base_url('details/'.$ProjectId).'">'.$ProjectName.'</a></li>
<li class="active">'.$this->lang->line('a0996').'</li>
</ol>';

echo '<h1>'.$Title.'</h1>';

echo $Content.'<br /><br />';

if($IsAdded)
{
    echo '<div class="alert alert-success">'.$this->lang->line('a0997').'</div>';
}

echo form_open('link-add/'.$ProjectId);

echo '<strong>'.$this->lang->line('a0998').'</strong> 
<div class="alert alert-warning alert-dismissable">'.$this->lang->line('a0999').'</div>
 '.form_input(array('name' => 'link_url', 'id' => 'link_url', 'value' => $Vlink_url, 'class' => 'form-control')).'<br />';
echo form_error('link_url','<div class="alert alert-danger">','</div>');

echo '<strong>'.$this->lang->line('a1000').'</strong> 
<div class="alert alert-warning alert-dismissable">'.$this->lang->line('a1001').'</div>
 '.form_input(array('name' => 'link_text', 'id' => 'link_text', 'value' => $Vlink_text, 'class' => 'form-control')).'<br />';
echo form_error('link_text','<div class="alert alert-danger">','</div>');

echo ''.form_hidden('formlogin','yes');
echo form_submit(array('name' => 'buttonstart', 'value' => ''.$this->lang->line('a1002').'', 'class' => 'btn btn-info btn-block'));
echo form_close();

?>