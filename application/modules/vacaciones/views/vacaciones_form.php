<h3 style="margin-top:0px"><?php echo $button ?> Vacaciones </h3>
<div class="panel panel-default">
    <div class="panel-heading">
        Periodo
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" id="vacaciones">

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="date">Desde <?php echo form_error('desde') ?></label>
                <input type="text" class="form-control" name="desde" id="desde" placeholder="Desde"
                       data-rule-required="true" data-msg-required="Inserte una fecha por favor"
                       value="<?php echo $desde; ?>"/>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <label for="date">Hasta <?php echo form_error('hasta') ?></label>
                <input type="text" class="form-control" name="hasta" id="hasta" placeholder="Hasta"
                       data-rule-required="true" data-msg-required="Inserte una fecha por favor"
                       value="<?php echo $hasta; ?>"/>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('vacaciones') ?>" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>
</div>
