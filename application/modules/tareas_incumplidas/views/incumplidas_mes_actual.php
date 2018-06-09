<h2 style="margin-top:0px">Observaciones del cumplimiento </h2>

<div class="panel panel-default">
    <div class="panel-heading">
        Sobre la tarea
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post">
            <div class="form-group">
                <input type="hidden" class="form-control" name="users_id" id="users_id"
                       value="<?php echo $users_id; ?>"/>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <input type="hidden" name="pt_id" value="<?php echo $pt_id ?>">
                <label for="varchar">Tarea</label>
                <input type="text" class="form-control" disabled
                       value="<?php echo $obj_actividad->hora . ' ' . $obj_actividad->actividad ?>">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="varchar">Qui&eacute;n Origin&oacute; <?php echo form_error('quien_origino') ?></label>
                <input type="text" class="form-control" name="quien_origino" id="quien_origino"
                       placeholder="Qui&eacute;n Origin&oacute;" value="<?php echo $quien_origino; ?>"/>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="varchar">Causas <?php echo form_error('causas') ?></label>
                <textarea name="causas" class="form-control" id="causas" cols="30"
                          rows="10"><?php echo $causas; ?></textarea>

            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Incumplidas o Suspendidas</label>

                <div class="radio-list">
                    <label class="radio-inline">
                        <input type="radio" name="optionsRadios" id="incumplida" value="option1" checked> Incumplida
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="optionsRadios" id="suspendida" value="option2"> Suspendida </label>

                </div>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('pt/get_dias_vencidos') ?>" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>
</div>