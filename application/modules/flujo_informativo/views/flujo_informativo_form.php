<h2 style="margin-top:0px"><?php echo $button ?> Plan de Actividades</h2>
<div class="panel panel-default">
    <div class="panel-heading">Datos de flujo_informativo</div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="flujo_form">
            <input type="hidden" id="fechas" value="<?php echo $fechas; ?>">
            <div class="form-group">
                <label for="varchar">Actividad <?php echo form_error('actividad') ?></label>
                <input type="text" class="form-control" name="actividad" id="actividad" placeholder="Actividad"
                       value="<?php echo $actividad; ?>" data-rule-required="true"
                       data-msg-required="Inserte Actividad"/>
            </div>
            <div class="form-group">
                <label for="varchar">Fecha</label>
                <input type="text" class="form-control" name="fecha_flujo" id="fecha_flujo" placeholder="Fecha"
                       value="" />
            </div>
            <div class="form-group form-md-checkboxes">
                <label>Fecha por rango <em style="color: red;">Si marca el check box el rango de fecha tendr&aacute; prioridad</em></label>
                <div class="md-checkbox-inline">
                    <div class="md-checkbox">
                        <input type="checkbox" id="checkbox6" name="flujo_rango" class="md-check">
                        <label for="checkbox6">
                            <span></span>
                            <span class="check"></span>
                            <span class="box"></span>Fecha por rango? </label>
                    </div>
                </div>
            </div>
            <div class="row" id="row_rango">
                <div class="col-sm-6">
                    <div class="col-sm-6">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" name="flujo_desde" id="flujo_desde" value="">
                            <label for="form_control_1">Desde</label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" name="flujo_hasta" id="flujo_hasta" value="">
                            <label for="form_control_1">Hasta</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="varchar">Hora Inicio <?php echo form_error('hora_inicio') ?></label>
                <input type="text" class="form-control" name="hora_inicio" id="hora_inicio" placeholder="Hora Inicio"
                       value="<?php echo $hora_inicio; ?>" data-rule-required="true"
                       data-msg-required="Inserte hora inicial"/>
            </div>
            <div class="form-group">
                <label for="varchar">Hora Fin <?php echo form_error('hora_fin') ?></label>
                <input type="text" class="form-control" name="hora_fin" id="hora_fin" placeholder="Hora Fin"
                       value="<?php echo $hora_fin; ?>" data-rule-required="true"
                       data-msg-required="Inserte hora final"/>
            </div>
            <div class="form-group">
                <label for="varchar">Dirigente <?php echo form_error('dirigente') ?></label>
                <input type="text" class="form-control" name="dirigente" id="dirigente" placeholder="Dirigente"
                       value="<?php echo $dirigente; ?>" data-rule-required="true"
                       data-msg-required="Inserte quien dirige"/>
            </div>
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
            <div class="form-group">
                <input type="hidden" id="grupoFlujo" value="<?php echo $grupoFlujo ?>">
                <label for="grupo_participantes">Grupo Participantes </label>
                <select class="form-control chosen-select" multiple name="grupo_participantes[]"
                        id="grupo_participantes" data-rule-required="true" data-msg-required="Inserte grupo">
                    <?php
                        foreach ($grupo_participantes as $item) {
                          ?>
                            <option value="<?php echo $item->id ?>"><?php echo $item->titulo ?></option>
                        <?php
                       }?>

                </select>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <button type="submit" class="btn btn-primary" value="<?php echo $button ?>"
                    name="btnsubmit"><?php if ($button == 'Create' || $button == 'Adicionar') { ?><i
                    class="fa fa-plus"></i> <?php echo $button;
                } else { ?><i class="fa fa-refresh"></i> <?php echo $button;
                } ?>  </button>
            <button type="submit" class="btn btn-primary" value="
             y Continuar" name="btnsubmit"><i
                    class="fa fa-paste"></i> Guardar y Continuar
            </button>
            <a href="<?php echo site_url('flujo_informativo') ?>" class="btn btn-default"><i class="fa fa-close"></i>
                Cancelar</a>
        </form>
    </div>
</div>