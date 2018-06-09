<!-- MAIN CONTENT SECTION  _____________________________________________-->
<section id="content" role="main">
    <div class="wrapper">

        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 col-lg-offset-4 col-md-offset-4 col-sm-offset-2">
                <form action="<?php echo $action; ?>" method="post">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $button ?></h3>
                        </div>
                        <div class="panel-body">


                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="varchar">Correo <?php echo form_error('correo') ?></label>

                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control" name="correo" id="correo"
                                           placeholder="Correo" value="<?php echo $correo; ?>"/>
                                </div>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label for="varchar">Password <?php echo form_error('password') ?></label>

                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" id="password"
                                           placeholder="Password" value="<?php echo $password; ?>"/>
                                </div>
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="id" value="<?php //echo $id; ?>"/>
                                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                                <a href="<?php echo site_url('') ?>" class="btn btn-default">Cancelar</a>
                            </div>

                        </div>
                        </div>
                </form>

            </div>
        </div>


    </div>
</section>