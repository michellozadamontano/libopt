<h2 style="margin-top:0px"><?php echo $button ?> Rol </h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Rol <?php echo form_error('rol') ?></label>
            <input type="text" class="form-control" name="rol" id="rol" placeholder="Rol" value="<?php echo $rol; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('adminrol') ?>" class="btn btn-default">Cancelar</a>
	</form>