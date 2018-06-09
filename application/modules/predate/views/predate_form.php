<h2 style="margin-top:0px"><?php echo $button ?> tareas anuales</h2>
<div class="panel panel-default">
    <div class="panel-heading">
        Tarea Predefinida
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="dateform">
            <div class="col-md-6">


                <div class="form-group">
                    <label for="date">Fecha <?php echo form_error('fecha') ?></label>
                    <input type="text" class="form-control date-width" name="fecha" id="fecha" placeholder="Fecha"
                           value="<?php echo $fecha; ?>" data-rule-required="true"
                           data-msg-required="Inserte una fecha"/>
                </div>
                <div class="radio-list">
                    <?php if ($hora_inicio != 'T/D'): ?>
                    <label class="radio-inline">Hora Inicial
                        <input type="text" class="form-control timepicker " name="hora_inicio" id="hora"
                               value="<?php echo $hora_inicio; ?>" data-rule-required="true"
                               data-msg-required="Inserte una hora inicio">
                    </label>
                    <label class="radio-inline">Hora Final
                        <input type="text" class="form-control timepicker " name="hora_fin" id="horaf"
                               value="<?php echo $hora_fin; ?>" data-rule-required="true"
                               data-msg-required="Inserte una hora final">
                    </label>
                    <?php else: ?>
                        <label class="radio-inline">Hora Inicial
                            <input type="text" class="form-control timepicker " name="hora_inicio" id="hora"
                                   value="08:00" data-rule-required="true"
                                   data-msg-required="Inserte una hora inicio">
                        </label>
                        <label class="radio-inline">Hora Final
                            <input type="text" class="form-control timepicker " name="hora_fin" id="horaf"
                                   value="08:30" data-rule-required="true"
                                   data-msg-required="Inserte una hora final">
                        </label>
                    <?php endif ?>

                    <label class="radio-inline">
                        <?php if ($hora_inicio == 'T/D'): ?>
                            <input type="checkbox" name="td" checked id="td"> T/D
                        <?php else: ?>
                            <input type="checkbox" name="td" id="td"> T/D
                        <?php endif ?>
                    </label>
                </div>
                <div class="form-group">
                    <label for="varchar">Actividad <?php echo form_error('actividad') ?></label>
                    <input type="text" class="form-control" name="actividad" id="actividad" placeholder="Actividad"
                           value="<?php echo $actividad; ?>" data-rule-required="true"
                           data-msg-required="Inserte una actividad"/>
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('predate') ?>" class="btn btn-default">Cancelar</a>
            </div>
            <div class="col-md-6">
                <?php if (isset($directivo)): ?>
                    <h3>Añadir tareas a subordinados</h3>
                    <?php echo Modules::run('users/subordinado_select', $user->id) ?>
                <?php endif ?>
                <?php if (isset($subo_list)): ?>
                    <h3>Subordinados sin esta tarea</h3>
                    <span style="margin-left: 20px">
                        <button id="select-all"><i class="fa fa-check-square"></i> seleccionar todos</button>
                        <button id="deselect-all"><i class="fa fa-undo"></i> deseleccionar todos</button>
                    </span>
                    <br><br>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select multiple="multiple" class="multi-select" id="my_multi_select" name="my_multi_select[]">
                            <optgroup label="Subordinados">
                                <?php
                                foreach ($subo_list as $value) {
                                    echo '<option   value="' . $value->id . '">' . $value->name . '</option>';
                                }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                <?php endif ?>
            </div>
            <div class="col-md-12">
                <?php if (isset($subo_with_task)): ?>
                    <h3>Subordinados con esta tarea</h3>
                    <ul>
                        <?php foreach($subo_with_task as $sub): ?>
                        <li>
                            <?php echo $sub->name?>
                        </li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
            </div>
        </form>
    </div>
</div>
