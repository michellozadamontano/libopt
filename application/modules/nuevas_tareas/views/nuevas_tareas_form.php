<h2 style="margin-top:0px"><?php echo $button ?> Nuevas tareas </h2>

<div class="panel panel-default">
    <div class="panel-heading">
        Tarea
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post">
            <div class="form-group  col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="varchar">Nueva tarea <?php echo form_error('nuevatarea') ?></label>
                <input type="text" class="form-control" name="nuevatarea" id="nuevatarea" placeholder="Nuevatarea"
                       value="<?php echo $nuevatarea; ?>"/>
            </div>
            <div class="form-group  col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="varchar">Qui&eacute;n Origin&oacute; <?php echo form_error('quien_origino') ?></label>
                <input type="text" class="form-control" name="quien_origino" id="quien_origino"
                       placeholder="Qui&eacute;n Origin&oacute;" value="<?php echo $quien_origino; ?>"/>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="date">Fecha <?php echo form_error('fecha') ?></label>
                <input type="text" class="form-control date-width" name="fecha" id="fecha" placeholder="Fecha"
                       value="<?php echo $fecha; ?>" data-rule-required="true"
                       data-msg-required="Inserte una fecha"/>
            </div>
            <div class=" form-group radio-list col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label class="radio-inline">Hora Inicial
                    <input type="text" class="form-control timepicker " name="hora" id="hora" value="<?php echo $hora_ini; ?>" data-rule-required ="true" data-msg-required ="Inserte una hora inicio">
                </label>
                <label class="radio-inline">Hora Final
                    <input type="text" class="form-control timepicker " name="horaf" id="horaf" value="<?php echo $hora_fin; ?>" data-rule-required ="true" data-msg-required ="Inserte una hora final" >
                </label>
            </div>
            <div class="form-group  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="varchar">Causas <?php echo form_error('causas') ?></label>
                <input type="text" class="form-control" name="causas" id="causas" placeholder="Causas"
                       value="<?php echo $causas; ?>"/>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('nuevas_tareas') ?>" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>
</div>