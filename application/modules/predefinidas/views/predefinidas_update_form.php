<h2 style="margin-top:0px"><?php echo $button ?> Tareas Predefinidas por días de la semana</h2>
<div class="panel panel-default">
    <div class="panel-heading">
        Tarea Predefinida
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="updPre">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
                    <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"
                           value="<?php echo $tarea; ?>" data-rule-required="true"
                           data-msg-required="Inserte tarea por favor"/>
                </div>
                <div class="form-group">
                    <label for="varchar">D&iacute;a <?php echo form_error('dia') ?></label>
                    <select name="dia" id="dia" class="select2">
                        <option value="<?php echo $dia; ?>"><?php echo $dia; ?></option>
                        <optgroup label="D&iacute;as de la semana">
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <!--<option value="T/D">Todos los dias</option>-->
                        </optgroup>

                    </select>
                </div>
                <div class="form-group">

                        <div class="radio-list">

                            <?php if (($hora != null) && ($hora != 'T/D')): ?>
                                <label class="radio-inline">Hora Inicio <?php echo form_error('hora') ?>
                                    <input type="text" class="form-control timepicker" name="hora" id="hora"
                                           value="<?php echo $hora ?>">
                                </label>
                                <label class="radio-inline">Hora Fin<?php echo form_error('hora_fin') ?>
                                    <input type="text" class="form-control timepicker" name="horaf" id="horaf"
                                           value="<?php echo $hora_fin ?>" data-rule-required="true"
                                           data-msg-required="Inserte una hora fin">
                                </label>

                            <?php else: ?>
                                <label class="radio-inline">Hora Inicio <?php echo form_error('hora') ?>
                                    <input type="text" class="form-control timepicker" name="hora" id="hora"
                                           value="08:00">
                                </label>
                                <label class="radio-inline">Hora Fin<?php echo form_error('hora_fin') ?>
                                    <input type="text" class="form-control timepicker" name="horaf" id="horaf"
                                           value="08:00" data-rule-required="true"
                                           data-msg-required="Inserte una hora de fin">
                                </label>
                            <?php endif ?>

                            <label class="radio-inline">
                                <?php if ($hora == 'T/D'): ?>
                                    <input type="checkbox" name="td" checked id="td"> T/D
                                <?php else: ?>
                                    <input type="checkbox" name="td" id="td"> T/D
                                <?php endif ?>
                            </label>
                        </div>

                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('predefinidas') ?>" class="btn btn-default">Cancelar</a>
                <br />
                <br />
            </div>
            <div class="col-md-6">
                <?php if (isset($subo_list)): ?>
                    <h3>Subordinados sin esta tarea</h3>
                    <!--<span style="margin-left: 20px">
                        <button id="select-all"><i class="fa fa-check-square"></i>   seleccionar todos</button>
                        <button id="deselect-all"><i class="fa fa-undo"></i> deseleccionar todos</button>
                    </span>-->
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select multiple="multiple" class="chosen-select form-control" id="my_multi_select2" name="my_multi_select[]">
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
            <div class="col-md-6">
                <?php if (isset($subo_with_task)): ?>
                    <h3>Subordinados con esta tarea</h3>

                <?php endif ?> <table class="table table-striped">
                    <tr>
                        <th>Nombre</th>
                        <th></th>
                    </tr>
                    <?php foreach($subo_with_task as $sub): ?>
                        <tr>
                            <td>
                                <?php echo $sub->name?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('predefinidas/delete_predef_subor/'.$id.'/'.$sub->id) ?>" class="btn btn-danger"><i class="fa fa-close"></i></a>
                            </td>

                        </tr>
                    <?php endforeach ?>
                </table>
            </div>
        </form>
    </div>
</div>
