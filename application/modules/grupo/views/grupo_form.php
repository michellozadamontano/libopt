<h2 style="margin-top:0px"><?php echo $button ?> Grupo</h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nombre Grupo <?php echo form_error('nombre_grupo') ?></label>
            <input type="text" class="form-control" name="nombre_grupo" id="nombre_grupo" placeholder="Nombre Grupo" value="<?php echo $nombre_grupo; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('grupo') ?>" class="btn btn-default">Cancelar</a>
	</form>