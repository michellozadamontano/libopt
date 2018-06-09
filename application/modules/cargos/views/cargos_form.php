<h2 style="margin-top:0px"><?php echo $button ?> Cargos </h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nombre Cargo <?php echo form_error('nombre_cargo') ?></label>
            <input type="text" class="form-control" name="nombre_cargo" id="nombre_cargo" placeholder="Nombre Cargo" value="<?php echo $nombre_cargo; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('cargos') ?>" class="btn btn-default">Cancelar</a>
	</form>