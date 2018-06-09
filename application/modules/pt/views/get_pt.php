<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div style="color: red" id="error"></div>
<div class="col-md-5">
    <?php
    echo validation_errors('<p style = "color:red; ">', '</p>');
    echo form_open($form_location,['id'=>'form_report']);
    echo form_label('Aprobado por','username');
    echo Modules::run('users/_draw_dropdown');
    echo form_label('Tareas Principales','principal_task');
    echo form_textarea('principal_task','Tareas Principales',['class'=>'form-control','id'=>'task']);
    echo '<br>';

    echo form_submit('submit','Submit',['class'=>'btn btn-primary']);
    echo form_close();
    ;?>
</div>
