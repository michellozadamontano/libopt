<?php
$oldsecond = mktime(0, 0, 0, $mes, 1);
$mestext = strftime('%B', $oldsecond); //mes en letra
$mestext = Modules::run('adminsettings/translate_month', $mestext);?>

<h2 style="margin-top:0px"><?php echo $button ?> tarea principal para el mes de <?php echo $mestext; ?> </h2>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            Datos de la tarea principal
        </h3>
    </div>
    <div class="panel-body">


        <form action="<?php echo $action; ?>" method="post">
            <div class="form-group ">
                <input type="hidden" name="users_id" id="users_id" value="<?php echo $users_id; ?>"/>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
                <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"
                       value="<?php echo $tarea; ?>"/>
            </div>
            <div class="form-group ">
                <input type="hidden" name="mes" id="mes" value="<?php echo $mes; ?>"/>
            </div>
            <div class="form-group">
                <input type="hidden" name="ano" id="ano" value="<?php echo $ano; ?>"/>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
            <a href="<?php echo site_url('tareasprincipales/index/' . $mes) ?>" class="btn btn-default">Cerrar</a>
            </div>
        </form>
    </div>
</div>