<h2 style="margin-top:0px"><?php echo $button ?> Tareas predefinidas según días del mes </h2>

<div class="panel panel-default">
    <div class="panel-heading">
        Tarea predefinida
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="preDiaFecha">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
                    <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"
                           value="<?php echo $tarea; ?>" data-rule-required="true"
                           data-msg-required="Inserte una tarea"/>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Todos los <?php echo form_error('cuando') ?></label>
                            <select name="cuando" id="cuando" class="select2" data-rule-required="true"
                                    data-msg-required="Campo requerido">
                                <option value="<?php echo $cuando; ?>" selected><?php echo $cuando; ?></option>
                                <option value="1">1ros</option>
                                <option value="2">2dos</option>
                                <option value="3">3ros</option>
                                <option value="4">4tos</option>
                            </select>
                            <label for="varchar" class="radio-inline"><?php echo form_error('dia') ?></label>
                        </div>
                        <div class="col-md-6">
                            <label>de cada </label>
                            <select name="dia" id="dia" class="select2" data-rule-required="true"
                                    data-msg-required="Campo requerido">
                                <option value="<?php echo $dia; ?>" selected><?php echo $dia; ?></option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miércoles">Miércoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sábado">Sábado</option>
                                <option value="Domingo">Domingo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="radio-list">
                        <label class="radio-inline"> Hora Inicio <?php echo form_error('hora') ?>
                            <?php if (($hora != null) && ($hora != 'T/D')): ?>
                                <input type="text" class="form-control timepicker" name="hora" id="hora"
                                       value="<?php echo $hora; ?>" data-rule-required="true"
                                       data-msg-required="Inserte una hora Inicio">
                            <?php else: ?>
                                <input type="text" class="form-control timepicker" name="hora" id="hora" value="08:00">
                            <?php endif ?>
                        </label>
                        <label class="radio-inline">Hora Fin<?php echo form_error('hora_fin') ?>
                            <?php if ($hora_fin != null): ?>
                                <input type="text" class="form-control timepicker" name="horaf" id="horaf"
                                       value="<?php echo $hora_fin ?>" data-rule-required="true"
                                       data-msg-required="Inserte una hora fin">
                            <?php else: ?>
                                <input type="text" class="form-control timepicker" name="horaf" id="horaf" value="08:30"
                                       data-rule-required="true" data-msg-required="Inserte una hora de fin">
                            <?php endif ?>
                        </label>
                        <label class="radio-inline">Todo el dia
                            <input type="checkbox" name="td" id="td">
                        </label>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary">Aceptar</button>
                <a href="<?php echo site_url('predefinidas_dias_fecha') ?>" class="btn btn-default">Cancelar</a>
                <br />
                <br />
            </div>
            <div class="col-md-6">
                <?php if (isset($directivo)): ?>
                    <h3>Añadir tareas a subordinados</h3>
                    <?php echo Modules::run('users/subordinado_select', $user->id) ?>
                <?php endif ?>
            </div>
        </form>
    </div>
</div>
