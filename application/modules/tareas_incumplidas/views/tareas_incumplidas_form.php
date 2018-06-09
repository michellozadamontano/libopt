<h2 style="margin-top:0px">OBSERVACIONES DEL CUMPLIMIENTO </h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <input type="hidden" class="form-control" name="users_id" id="users_id" value="<?php echo $users_id; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Seleccione Tarea <?php echo form_error('pt_id') ?></label>
            <?php echo Modules::run('pt/list_task') ?>
        </div>
	    <div class="form-group">
            <label for="varchar">Qui&eacute;n Origin&oacute; <?php echo form_error('quien_origino') ?></label>
            <input type="text" class="form-control" name="quien_origino" id="quien_origino" placeholder="Qui&eacute;n Origin&oacute;" value="<?php echo $quien_origino; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Causas <?php echo form_error('causas') ?></label>
            <textarea name="causas" class="form-control" id="causas" cols="30" rows="10"><?php echo $causas; ?></textarea>

        </div>
	    <div class="form-group">
            <label>Incumplidas o Suspendidas</label>
            <div class="radio-list">
                <label class="radio-inline">
                    <input type="radio" name="optionsRadios" id="incumplida" value="option1" checked> Incumplida </label>
                <label class="radio-inline">
                    <input type="radio" name="optionsRadios" id="suspendida" value="option2"> Suspendida </label>

            </div>
        </div>

	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('tareas_incumplidas') ?>" class="btn btn-default">Cancelar</a>
	</form>