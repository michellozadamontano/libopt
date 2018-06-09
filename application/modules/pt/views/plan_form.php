<div class="panel panel-default">
    <div class="panel-heading">


        <h3 class="panel-title"><?php echo $button ?> Tarea <i class="fa fa-angle-double-right"></i>
            <?php echo $fecha_texto;
            ?></h3>
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="updPlan">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                <div class="form-group ">

                    <?php if ($hora == 'T/D'): ?>
                        <div class="row">
                            <div class="radio-list">
                                <label class="radio-inline">Hora Inicial
                                    <input type="text" class="form-control timepicker " name="hora" id="hora" value="08:00">
                                </label>
                                <label class="radio-inline">Hora Final
                                    <input type="text" class="form-control timepicker " name="horaf" id="horaf" value="08:00">
                                </label>
                                <label class="radio-inline">
                                    <input type="checkbox" checked name="td" id="td"> T/D
                                </label>
                                <label class="radio-inline">Fecha
                                    <input type="text" name="fecha" id="datepicker" value="<?php echo $fecha ?>">
                                </label>
                            </div>
                        <?php ; ?>
                    <?php else: ?>
                        <div class="radio-list">
                            <label class="radio-inline">Hora Inicial
                                <input type="text" class="form-control timepicker " name="hora" id="hora" value="<?php echo $hora ?>" data-rule-required ="true" data-msg-required ="Inserte una hora inicio">
                            </label>
                            <label class="radio-inline">Hora Final
                                <input type="text" class="form-control timepicker " name="horaf" id="horaf" value="<?php echo $hora_fin ?>" data-rule-required ="true" data-msg-required ="Inserte una hora final" >
                            </label>
                            <label class="radio-inline">
                                <input type="checkbox" name="td" id="td"> T/D
                            </label>
                            <label class="radio-inline">Fecha de cambio
                                <?php
                                    if(isset($directivo))
                                    {?>
                                        <input type="hidden" class="form-control"  name="fecha"  id="datepicker" value="<?php echo $fecha ?>">
                                    <?php
                                    }
                                    else
                                    {?>
                                        <input type="text" class="form-control" name="fecha"  id="datepicker" value="<?php echo $fecha ?>">
                                    <?php
                                    }
                                ?>

                            </label>
                        </div>
                    <?php endif ?>
                </div>
                <div class="form-group">
                    <label for="varchar">Actividad <?php echo form_error('actividad') ?></label>
                    <?php
                    if(isset($directivo))
                    {?>
                        <input type="text" readonly class="form-control" id="actividad" name="actividad"
                               value="<?php echo $actividad ?>">
                    <?php
                    }
                    else
                    {?>
                        <input type="text" class="form-control" id="actividad" name="actividad"
                               value="<?php echo $actividad ?>">
                        <?php
                    }
                    ?>

                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('welcome') ?>" class="btn btn-default">Cerrar</a>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <?php if (isset($subo_list)): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Subordinados sin esta tarea</h3>
                    </div>
                    <div class="panel-body">

                        <!--<div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
                            <button id="select-all"><i class="fa fa-check-square"></i> seleccionar todos</button>
                            <button id="deselect-all"><i class="fa fa-undo"></i> deseleccionar todos</button>
                        </div>
                        <br/>
                        <br/>-->

                        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select multiple="multiple" class="chosen-select form-control" id="my_multi_select3"
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
                </div>
            </div>
                <div class="col-md-6">
                    <?php if (isset($subo_with_task)): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Subordinados con esta tarea </h3>
                            </div>
                            <div class="panel-body">
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
                                                <a href="<?php echo site_url('pt/elimina_usuario_tarea/'.$id.'/'.$sub->id) ?>" class="btn btn-danger"><i class="fa fa-close"></i></a>
                                            </td>

                                        </tr>
                                    <?php endforeach ?>
                                </table>
                            </div>
                        </div>


                    <?php endif ?>
                </div>


        </form>

    </div>
</div>

