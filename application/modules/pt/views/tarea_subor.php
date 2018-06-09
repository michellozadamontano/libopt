<div class="row">
    <?php if(isset($flash)):?>
        <div class="alert-success"><?php echo '<p style="color: red">'.$flash.'</p>' ?></div>
    <?php endif?>
    <br><br>
    <?php $fechaesp = Modules::run('adminsettings/fecha_esp',$date) ?>
    <?php //echo $date

    ?>
    <?php echo $fechaesp ?>
    <span>
        <a href="<?php echo base_url('pt/tarea_subor/'.$d_ant) ?>" data-toggle="tooltip" data-placement="top" title="D&iacute;a Anterior" class="btn btn-danger"><i class="fa fa-arrow-left"></i></a>
        <a href="<?php echo base_url('pt/tarea_subor/'.$d_prox) ?>" data-toggle="tooltip" data-placement="top" title="Pr&oacute;ximo D&iacute;a" class="btn btn-danger"><i class="fa fa-arrow-right"></i></a>

    </span>
    <br><br>

    <?php
    echo validation_errors();
    $attributes = array('id' => 'myform');
    echo form_open(base_url('pt/tarea_subor_action'),$attributes);?>
    <div class="col-md-6">
        <p id="date_task"></p>
        <p>Selecione hora:   <b>Nota:</b><span style="color: red">T/D tiene prioridad sobre el resto de las horas</span> </p>
        <div class="radio-list">
            <label class="radio-inline">Hora Inicial
                <input type="text" class="form-control timepicker " name="hora" id="hora" value="08:00" data-rule-required ="true" data-msg-required ="Inserte una hora inicio">
            </label>
            <label class="radio-inline">Hora Final
                <input type="text" class="form-control timepicker " name="horaf" id="horaf" value="08:30" data-rule-required ="true" data-msg-required ="Inserte una hora final" >
            </label>
            <label class="radio-inline">
                <input type="checkbox" name="td" id="td"> T/D
            </label>
        </div>
        <br>
        <div class="form-group">
            <input type="hidden" id="dir_activity" value="<?php echo base_url('actividades/activity_by_group');?>">
            <input type="hidden" name="fecha" value="<?php echo $date ?>">
            <label for="">Seleccione grupo</label>
            <?php echo Modules::run('grupo/grupo_select');?>
        </div>
        <div class="form-group">
            <select name="activity" id="activity" class="form-control">
                <option value="">Selecciona la actividad</option>
            </select>
        </div>
        <span style="color: red">Si la tarea no está en la lista dejela en blanco y escriba la nueva aquí: <i class="fa fa-hand-o-down fa-lg"></i></span>
        <div class="form-group">
            <?php echo form_textarea('extra_task','',['class'=>'form-control','id'=>'extra_task','placeholder'=>'Tarea Extra']);?>
        </div>
        <a href="<?php echo site_url('pt/show_calendar_subor') ?>" class="btn btn-default">Cancelar</a>
        <button type="submit" class="btn btn-primary" id="btn_save">Aceptar</button>
    </div>
    <div class="col-md-6">
        <?php
        $user = $this->session->userdata('usuario');
        ?>

        <?php if((isset($directivo)) || $user->rol_id == 5):?>
            <h3>Añadir tareas a subordinados</h3>
            <?php echo Modules::run('users/subordinado_select',$user->id)?>
        <?php endif?>
    </div>
    <input type="hidden" name="month" value="<?php echo $month ?>">
    <input type="hidden" name="year" value="<?php echo $year ?>">
    <?php echo form_close();?>

</div>