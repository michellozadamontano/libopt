<h2 style="margin-top:0px"><?php echo $button ?> Tareas predefinidas seg&uacute;n d&iacute;as del mes </h2>

<div class="panel panel-default">
    <div class="panel-heading">
        Tarea predefinida
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="updPreDiaFecha">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
                    <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"
                           value="<?php echo $tarea; ?>"/>
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
                    <label for="varchar">Hora </label>

                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                            <label class=""> Hora Inicio <?php echo form_error('hora') ?></label>
                                <?php if (($hora != null) && ($hora != 'T/D')): ?>
                                    <input type="text" class="form-control" name="hora" id="hora"
                                           value="<?php echo $hora; ?>" data-rule-required="true"
                                           data-msg-required="Inserte una hora Inicio">
                                <?php else: ?>
                                    <input type="text" class="form-control" name="hora" id="hora" value="08:00">
                                <?php endif ?>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                            <label class="">Hora Fin<?php echo form_error('hora_fin') ?></label>
                                <?php if ($hora_fin != null): ?>
                                    <input type="text" class="form-control" name="horaf" id="horaf"
                                           value="<?php echo $hora_fin ?>" data-rule-required="true"
                                           data-msg-required="Inserte una hora fin">
                                <?php else: ?>
                                    <input type="text" class="form-control timepicker" name="horaf" id="horaf" value="08:30"
                                           data-rule-required="true" data-msg-required="Inserte una hora de fin">
                                <?php endif ?>

                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                            <label class="">
                                <?php if ($hora == 'T/D'): ?>
                                    <input type="checkbox" name="td" checked id="td"> Todo el dia
                                <?php else: ?>
                                    <input type="checkbox" name="td" id="td"> Todo el dia
                                <?php endif ?>
                            </label>
                        </div>
                    </div>


                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('predefinidas_dias_fecha') ?>" class="btn btn-default">Cancelar</a>
                <br/>
                <br/>
            </div>
            <div class="col-md-6">
                <?php if (isset($subo_list)): ?>
                    <h3>Subordinados sin esta tarea</h3>
                    <!--<span style="margin-left: 20px">
                        <button id="select-all"><i class="fa fa-check-square"></i> seleccionar todos</button>
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
                    <table class="table table-striped">
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
                                    <a href="<?php echo site_url('predefinidas_dias_fecha/delete_predef_subor/'.$id.'/'.$sub->id) ?>" class="btn btn-danger"><i class="fa fa-close"></i></a>
                                </td>

                            </tr>
                        <?php endforeach ?>
                    </table>
                <?php endif ?>
            </div>
        </form>
    </div>
</div>