<ch2 style="margin-top:0px"><?php echo $button ?> Actividades </ch2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">Grupo <?php echo form_error('grupo_id') ?></label>
            <?php echo Modules::run('grupo/grupo_select') ?>
        </div>
	    <div class="form-group">
            <label for="varchar">Nombre Actividad <?php echo form_error('nombre_actividad') ?></label>
            <input type="text" class="form-control" name="nombre_actividad" id="nombre_actividad" placeholder="Nombre Actividad" value="<?php echo $nombre_actividad; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('actividades') ?>" class="btn btn-default">Cancelar</a>
	</form>