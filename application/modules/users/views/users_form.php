<h2 style="margin-top:0px"><?php echo $button ?> Usuarios </h2>
<?php if(isset($error)): ?>
    <p style="color: red"><?php echo $error?></p>
<?php endif?>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nombre <?php echo form_error('name') ?></label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user"></i>
                </span>
                <input type="text" class="form-control" name="name" id="name" placeholder="Nombre" value="<?php echo $name; ?>" />

            </div>

        </div>
	    <div class="form-group">
            <label for="varchar">Email <?php echo form_error('email') ?></label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-envelope"></i>
                </span>
                <input type="text" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $email; ?>" />
            </div>

        </div>
	    <div class="form-group">
            <label for="varchar">Cargo <?php echo form_error('cargo') ?></label>
            <?php echo Modules::run('cargos/cargos_select')?>
        </div>
	    <div class="form-group">
            <label for="varchar">Password <?php echo form_error('password') ?></label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user-secret"></i>
                </span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>" />

            </div>

        </div>
	    <div class="form-group">
            <label for="int">Rol <?php echo form_error('rol_id') ?></label>
            <?php echo Modules::run('adminrol/rol_select')?>
        </div>
        <div class="form-group">
            <label for="int">Se subordina a:</label>
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="fa fa-user-times"></i>
                </span>
                <?php echo Modules::run('users/user_select') ?>
            </div>
            <span style="color: red"> Si el usuario no se subordina a nadie lo dejas en blanco</span>
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('users') ?>" class="btn btn-default">Cancelar</a>
	</form>