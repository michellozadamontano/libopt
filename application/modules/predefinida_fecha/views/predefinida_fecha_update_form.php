<h2 style="margin-top:0px"><?php echo $button ?>Tareas predefinidas por dias del mes </h2>
<div class="panel panel-default">
    <div class="panel-heading">
        Tarea predefinida
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="updPreFecha">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
                    <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"
                           value="<?php echo $tarea; ?>" data-rule-required="true"
                           data-msg-required="Inserte Tarea"/>
                </div>
                <div class="form-group">
                    <label for="int">Los dias <?php echo form_error('dia') ?></label>

                    <select name="dia" id="dia" class="form-control" data-rule-required="true"
                            data-msg-required="Seleccione dia">
                        <?php for ($i = 1; $i < 31; $i++): ?>
                            <?php if (isset($dia)): ?>
                                <option <?php if ($dia == $i) {
                                    echo 'selected ';
                                } ?> value="<?php echo $i ?>"><?php echo $i ?> de cada mes
                                </option>
                            <?php else: ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?> de cada mes</option>
                            <?php endif ?>

                        <?php endfor ?>
                    </select>

                </div>
                <div class="form-group">
                    <div class="radio-list">
                        <label class="radio-inline">Hora <?php echo form_error('hora') ?>
                            <?php if (($hora != null) && ($hora != 'T/D')): ?>
                                <input type="text" class="form-control timepicker" name="hora" id="hora"
                                       value="<?php echo $hora; ?>" data-rule-required="true"
                                       data-msg-required="Inserte una hora fin">
                            <?php else: ?>
                                <input type="text" class="form-control timepicker" name="hora" id="hora" value="08:00"
                                       data-rule-required="true"
                                       data-msg-required="Inserte una hora fin">
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
                        <label class="radio-inline">
                            <?php if ($hora == 'T/D'): ?>
                                <input type="checkbox" name="td" checked id="td"> Todo el dia
                            <?php else: ?>
                                <input type="checkbox" name="td" id="td"> Todo el dia
                            <?php endif ?>
                        </label>
                    </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('predefinida_fecha') ?>" class="btn btn-default">Cancelar</a>
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
                        <select multiple="multiple" class="chosen-select form-control" id="my_multi_select2"
                                name="my_multi_select[]">
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
                                    <a href="<?php echo site_url('predefinida_fecha/delete_predef_subor/'.$id.'/'.$sub->id) ?>" class="btn btn-danger"><i class="fa fa-close"></i></a>
                                </td>

                            </tr>
                        <?php endforeach ?>
                    </table>
                <?php endif ?>
            </div>
        </form>
    </div>
</div>
