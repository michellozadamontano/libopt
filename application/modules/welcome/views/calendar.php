<input type="hidden" id="url" value="<?php echo base_url('pt/get_all')?>">
<div id="calendar"></div>
<div class="modal fade bs-modal-lg"  tabindex="-1" role="dialog" data-backdrop="static" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tareas Diarias</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php
                    $attributes = array('id' => 'myform');
                    echo form_open(base_url('pt/create_plan_action'),$attributes);?>
                    <div class="col-md-6">
                        <p id="date_task"></p>
                        <div class="form-group">
                            <?php
                            $options = ['08:00'=>'08:00',
                                '9:00'=>'9:00',
                                '10:00'=>'10:00',
                                '11:00'=>'11:00',
                                '12:00'=>'12:00',
                                '13:00'=>'13:00',
                                '14:00'=>'14:00',
                                '15:00'=>'15:00',
                                '16:00'=>'16:00',
                                'T/D'=>'T/D'];
                            echo form_dropdown('hora',$options,'8:00',['class'=>'form-control','id'=>'hora'])
                            ;?>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="dir_activity" value="<?php echo base_url('actividades/activity_by_group');?>">
                            <input type="hidden" name="fecha" value="<?php //echo $date ?>">
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

                    </div>
                    <div class="col-md-6">
                        <?php if(isset($directivo)):?>
                            <h3>Añadir tareas a subordinados</h3>
                            <div class="col-md-6">
                                <button id="select-all"><i class="fa fa-check-square"></i> sleccionar todos</button>
                            </div>
                            <div class="col-md-6">
                                <button id="deselect-all">deseleccionar todos</button>
                            </div>


                            <?php echo Modules::run('users/subordinado_select',$user->id)?>
                        <?php endif?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <a href="<?php echo site_url('welcome') ?>" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-primary" id="btn_save">Aceptar</button>
            </div>
            <?php echo form_close();?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->