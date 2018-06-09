<div class="row">
    <div class="col-sm-12">
        <form action="<?php echo base_url('Flujo_informativo/get_actividad_import'); ?>" method="post" class="form-inline">

                <div class="form-group">
                    <label for="categoria_id">Categoria <?php echo form_error('categoria_id') ?></label>
                    <select class="form-control chosen-select" name="categoria_id" id="categoria_id" data-rule-required="true" data-msg-required="Inserte un grupo por favor">
                        <?php
                        foreach ($categoria_id as $item) {
                            if (isset($cat_id)) {
                                ?>
                                <option <?php if ($cat_id == $item->id) {
                                    echo 'selected ';
                                } ?> value="<?php echo $item->id ?>"><?php echo $item->nombre ?></option>
                                <?php
                            } else { ?>
                                <option value="<?php echo $item->id ?>"><?php echo $item->nombre ?></option>
                                <?php
                            }

                        }
                        ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Aplicar</button>

        </form>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php
            if(isset($actividades)){?>
                <table class="table table-striped" style="width: 100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <th style="width: 20%;vertical-align:top;border:1px solid black;">Actividad</th>
                        <th style="width: 20%;vertical-align:top;border:1px solid black;">Meses</th>
                        <th style="width: 20%;vertical-align:top;border:1px solid black;">Dirige</th>
                        <th style="width: 20%;vertical-align:top;border:1px solid black;">Participantes</th>
                        <th style="width: 20%;vertical-align:top;border:1px solid black;"></th>
                    </tr>
                    <?php
                        foreach ($actividades as $act){?>
                            <tr>
                                <td style="width: 20%;vertical-align:top;border:1px solid black;"><?php echo $act->actividad?></td>
                                <td style="width: 20%;vertical-align:top;border:1px solid black;">
                                    <table border="1" class="table table-striped" style="width: 100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td colspan="12">
                                                Meses
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">E</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">F</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">M</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">A</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">M</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">J</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">J</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">A</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">S</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">O</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">N</th>
                                            <th style="width: 8%;vertical-align:top;border:1px solid black;">D</th>
                                        </tr>
                                        <tr>
                                            <?php $fechas = Modules::run('Flujo_informativo/get_fecha_actividad_import',$act->id);
                                            foreach ($fechas as $fec){?>
                                                  <td><?php echo $fec->dias?></td>

                                            <?php
                                            }
                                            ?>
                                        </tr>

                                    </table>
                                </td>
                                <td style="width: 20%;vertical-align:top;border:1px solid black;"><?php echo $act->dirige?></td>
                                <td style="width: 20%;vertical-align:top;border:1px solid black;"><?php echo $act->participantes?></td>
                                <td style="width: 20%;vertical-align:top;border:1px solid black;">
                                    <?php
                                        if($act->agregada == 1)
                                        {
                                            echo '<p style="color: red">Agregada!!</p>';
                                        }
                                        else
                                        {?>
                                            <a href="<?php echo base_url('Flujo_informativo/form_insert_plan/'.$act->id.'/'.$cat_id)?>" class="btn btn-primary">Agregar</a>
                                        <?php
                                        }
                                    ?>

                                </td>
                            </tr>

                        <?php
                        }
                    ?>
                </table>

            <?php
            }
        ?>
    </div>
</div>