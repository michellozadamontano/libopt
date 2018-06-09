<h2 style="margin-top:0px"><?php echo $button ?> Tareas Predefinidas por d&iacute;as de la semana</h2>
<div class="panel panel-default">
    <div class="panel-heading">
Tarea predefinida
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="predefinida_form">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
                    <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"
                           value="<?php echo $tarea; ?>" data-rule-required="true"
                           data-msg-required="Inserte una tarea"/>
                </div>
                <div class="form-group">
                    <label for="varchar">D&iacute;a <?php echo form_error('dia') ?></label>
                    <select name="dia" id="dia" class="select2" data-rule-required="true"
                            data-msg-required="Este campo es requerido">
                        <option value="<?php echo $dia; ?>"><?php echo $dia; ?></option>
                        <optgroup label="D&iacute;as de la semana">
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="T/D">Todos los dias</option>
                        </optgroup>

                    </select>
                </div>
                <div class="form-group">

                    <div class="form-group">
                        <div class="radio-list">
                            <label class="radio-inline">Hora Inicio <?php echo form_error('hora') ?>
                                <?php if ($hora != null): ?>
                                    <input type="text" class="form-control timepicker" name="hora" id="hora"
                                           value="<?php echo $hora ?>" data-rule-required="true"
                                           data-msg-required="Inserte una hora inicio">
                                <?php else: ?>
                                    <input type="text" class="form-control timepicker" name="hora" id="hora"
                                           value="08:00" data-rule-required="true"
                                           data-msg-required="Inserte una hora inicio">
                                <?php endif ?>
                            </label>
                            <label class="radio-inline">Hora Fin<?php echo form_error('hora_fin') ?>
                                <?php if ($hora_fin != null): ?>
                                    <input type="text" class="form-control timepicker" name="horaf" id="horaf"
                                           value="<?php echo $hora_fin ?>" data-rule-required="true"
                                           data-msg-required="Inserte una hora fin">
                                <?php else: ?>
                                    <input type="text" class="form-control timepicker" name="horaf" id="horaf"
                                           value="08:30" data-rule-required="true"
                                           data-msg-required="Inserte una hora de fin">
                                <?php endif ?>
                            </label>
                            <label class="radio-inline">
                                <input type="checkbox" name="td" id="td"> Todo el día
                            </label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary">Aceptar</button>
                <a href="<?php echo site_url('predefinidas') ?>" class="btn btn-default">Cancelar</a>
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