
<h2 class="dentrorow"> <?php echo $button ?> Usuarios </h2>
<form action="<?php echo $action; ?>" method="post">

    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
        <label for="varchar">Contraseña anterior <?php echo form_error('oldpassword') ?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-unlock"></i></span>
            <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="Old Password" value="" />
        </div>
    </div>



    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
        <label for="varchar">Nueva contraseña <?php echo form_error('newpassword') ?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
            <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="New Password" value="" />
        </div>
    </div>



    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
        <label for="varchar">Confirmar contraseña <?php echo form_error('confirmpassword') ?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-check"></i></span>
            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" value="" />
        </div>
    </div>

    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">



        <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
        <button type="submit" class="btn btn-default"><?php echo $button ?></button> 
        <a href="<?php echo site_url('welcome') ?>" class="btn btn-default">Cancelar</a>
    </div>
</form>