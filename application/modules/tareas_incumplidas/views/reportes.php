<h2 style="margin: 0px;">Modelos por periodos</h2>
<div class="panel panel-default">
    <div class="panel-heading">
        Planes de trabajo
    </div>
    <div class="panel-body">
        <form action="<?php echo base_url('pt/report') ?>" method="post" target="_blank">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="">Planes aprobados hasta la fecha <?php echo form_error('planes') ?></label>
                <select name="planes" id="planes" class="form-control select2">
                    <option value=""></option>
                    <?php foreach ($planes_proved as $plan): ?>
                        <?php $mes = Modules::run('adminsettings/translate_month_number', $plan->month); ?>
                        <option value="<?php echo $plan->id ?>"><?php echo $mes . '-' . $plan->year ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <?php echo form_submit('submit', 'Modelo1', ['class' => 'btn btn-primary']); ?>
                <?php echo form_submit('submit', 'Modelo2', ['class' => 'btn btn-danger']); ?>

            </div>
        </form>
    </div>
</div>