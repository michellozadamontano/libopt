
<h2 class="dentrorow" >Usuarios <?php echo $button ?></h2>
<form action="<?php echo $action; ?>" method="post">
    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
        <label for="varchar">Nombre <?php echo form_error('nombre') ?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" />
        </div>
    </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
        <label for="varchar">Apellidos <?php echo form_error('apellidos') ?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?php echo $apellidos; ?>" />
        </div>

    </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
        <label for="varchar">Telefono <?php echo form_error('telefono') ?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
            <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono; ?>" />
        </div>
    </div>
    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
        <label for="varchar">Correo <?php echo form_error('correo') ?></label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
            <input readonly="readonly" type="text" class="form-control" name="correo" id="correo" placeholder="Correo" value="<?php echo $correo; ?>" />
        </div>
    </div>


    <div class="form-group col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2"

         <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
        <button type="submit" class="btn btn-default"><?php echo $button ?></button> 
        <a href="<?php echo site_url('adminpanel') ?>" class="btn btn-default">Cancel</a>
    </div>
</form>