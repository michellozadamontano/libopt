 <?php if (isset($flash)): ?>
    <div class="alert-success"><?php echo $flash ?></div>
<?php endif ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Insertar tarea <i class="fa fa-angle-double-right"></i>
            <?php $fechaesp = Modules::run('adminsettings/fecha_esp', $date) ?>
            <?php echo $fechaesp ?>
            <span>
        <a href="<?php echo base_url('pt/create_plan/' . $d_ant) ?>" data-toggle="tooltip" data-placement="top"
           title="D&iacute;a anterior" class="btn btn-danger"><i class="fa fa-arrow-left"></i></a>
        <a href="<?php echo base_url('pt/create_plan/' . $d_prox) ?>" data-toggle="tooltip" data-placement="top"
           title="Pr&oacute;ximo d&iacute;a" class="btn btn-danger"><i class="fa fa-arrow-right"></i></a>
    </span></h3>
    </div>
    <div class="panel-body">
        <br/>
        <?php
        echo validation_errors();
        $attributes = array('id' => 'myform');
        echo form_open(base_url('pt/create_plan_action'), $attributes);?>
        <div class="col-md-2 col-xs-12 col-lg-2 col-sm-12">
            <h5><strong>Tareas para este d&iacute;a</strong></h5>
            <?php echo Modules::run('pt/get_plan', $date); ?>
        </div>
        <div class="col-md-5 col-xs-12 col-lg-5 col-sm-12">
            <div class="row">
                <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
                    <p id="date_task"></p>

                    <p>Selecione hora: <b>Nota:</b><span
                            style="color: red">T/D tiene prioridad sobre el resto de las horas y la hora es formato 24h</span>
                    </p>

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
                    <input  id="range_date" name="range_date" data-toggle="tooltip" data-placement="top" title="Rango de d&iacute;a para esta tarea">
                </div>
                <div class="form-group col-md-12 col-xs-12 col-lg-12 col-sm-12">
                    <input type="hidden" id="dir_activity"
                           value="<?php echo base_url('actividades/activity_by_group'); ?>">
                    <input type="hidden" name="fecha" value="<?php echo $date ?>">
                    <label for="">Seleccione grupo</label>
                    <?php echo Modules::run('grupo/grupo_select'); ?>
                </div>
                <div class="form-group  col-md-12 col-xs-12 col-lg-12 col-sm-12">
                    <select name="activity" id="activity" class="form-control">
                        <option value="">Selecciona la actividad</option>
                    </select>
                </div>

                <div class="col-md-12 col-xs-12 col-lg-12 col-sm-12">

                    <span
                        style="color: red">Si la tarea no está en la lista d&eacute;jela en blanco y escriba la nueva aquí: <i
                            class="fa fa-hand-o-down fa-lg"></i></span>
                    <?php echo form_textarea('extra_task', '', ['rows' => 5, 'class' => 'form-control', 'id' => 'extra_task', 'placeholder' => 'Tarea Extra']); ?>
                </div>

            </div>
        </div>
        <div class="form-group  col-lg-5 col-md-5 col-sm-6 col-xs-12">
            <?php
            $user = $this->session->userdata('usuario');
            if (isset($directivo) || $user->rol_id == 5) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Añadir tareas a subordinados</h3>
                    </div>
                    <div class="panel-body">
                        <?php echo Modules::run('users/subordinado_select', $user->id) ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>


        <div style="margin-top: 1em;" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button type="submit" class="btn btn-primary" id="btn_save">Aceptar</button>
            <a href="<?php echo site_url('welcome/index/' . $month . '/' . $year) ?>"
               class="btn btn-default">Cerrar</a>
        </div>
    </div>
    <br/>

    <input type="hidden" name="day" id="day" value="<?php echo $day ?>">
    <input type="hidden" name="month" id="month" value="<?php echo $month ?>">
    <input type="hidden" name="year" id="year" value="<?php echo $year ?>">
    <?php echo form_close(); ?>
</div>

