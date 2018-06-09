<h2 style="margin-top:0px">Aprobacionpt <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="int">Users Id <?php echo form_error('users_id') ?></label>
            <input type="text" class="form-control" name="users_id" id="users_id" placeholder="Users Id" value="<?php echo $users_id; ?>" />
        </div>
	    <div class="form-group">
            <label for="date">Fecha <?php echo form_error('fecha') ?></label>
            <input type="text" class="form-control" name="fecha" id="fecha" placeholder="Fecha" value="<?php echo $fecha; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('aprobacionpt') ?>" class="btn btn-default">Cancelar</a>
	</form>