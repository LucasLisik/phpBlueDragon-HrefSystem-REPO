<?php

echo '<ol class="breadcrumb">
<li><a href="'.base_url().'">'.$this->lang->line('a1050').'</a></li>
<li class="active">'.$this->lang->line('a1059').'</li>
</ol>';

echo '<h1>'.$Title.'</h1>';

echo $Content.'<br /><br />';

if($UploadError['error'] != "")
{
    echo '<div class="alert alert-danger">'.$UploadError['error'].'</div>';
}

if($FileIsEmpty)
{
    echo '<div class="alert alert-danger">'.$this->lang->line('a1060').'</div>';
}

if($ImportFileSuccess)
{
    echo '<div class="alert alert-success">'.$this->lang->line('a1061').'</div>';
}

$ResultDB = $this->System_model->GetProjectList();
    
$OptionsList = null;

if($ResultDB != null)
{
    foreach($ResultDB->result() as $row)
    {
        $OptionsList[$row->project_id] = $row->project_name;
    }
}

if(!$IsFielImport)
{
    echo '<div class="row">
      <div class="col-md-2"></div>
      <div class="col-md-8">';
      
    echo form_open_multipart('import');
    
    echo '<strong>'.$this->lang->line('a1062').'</strong><br />
        <div class="input-group">
            <span class="input-group-btn">
            
            <span class="btn btn-default btn-file">
            '.$this->lang->line('a1063').'&hellip; <input type="file" name="importfile" id="importfile">
            </span>
            </span>
            <input type="text" class="form-control" readonly>
            
        </div>
    
  
                
            ';
            ?>
            <script>
            $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });
            
            $(document).ready( function() {
                $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
                
                    var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;
                    
                    if( input.length ) {
                    input.val(log);
                    } else {
                    if( log ) alert(log);
                    }
                
                });
            });
            </script>
            <?php
    echo form_error('importfile','<div class="alert alert-danger">', '</div>');
    
    echo '<br /><strong>'.$this->lang->line('a1064').'</strong><br />'.form_dropdown('page_id', $OptionsList, $Fpage_id, 'class="form-control"');
    echo form_error('page_id','<div class="alert alert-danger">', '</div>');
    
    
    echo form_hidden('addlink','yes');
    echo '<br /><br />'.form_submit(array('name' => 'zaloguj', 'value' => ''.$this->lang->line('a1065').'', 'class' => 'btn btn-info btn-block'));
    echo form_close();
    
        echo '</div>
      <div class="col-md-2"></div>
    </div>';

}
else
{
    echo $Body;
}

?>