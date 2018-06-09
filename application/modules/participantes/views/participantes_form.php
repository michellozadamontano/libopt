<h2 style="margin-top:0px">Participantes <?php echo $button ?></h2>
<div class="panel panel-default">
    <div class="panel-heading">Datos de participantes</div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post">
            <div class="form-group">
                <label for="int">Usuario <?php echo form_error('users_id') ?></label>

                <select class="form-control chosen-select" name="users_id" id="users_id">
                    <?php
                    foreach($users_id as $item){?>
                        <option value="<?php echo $item->id?>"><?php echo $item->name?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="int">Grupo Participantes <?php echo form_error('grupo_participantes_id') ?></label>

                <select class="form-control chosen-select" name="grupo_participantes_id" id="grupo_participantes_id">
                    <?php
                    foreach($grupo_participantes_id as $item){?>
                        <option value="<?php echo $item->id?>"><?php echo $item->titulo?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <button type="submit" class="btn btn-primary" value="<?php echo $button ?>"
                    name="btnsubmit"><?php if ($button == 'Create' || $button == 'Adicionar') { ?><i
                    class="fa fa-plus"></i> <?php echo $button;
                } else { ?><i class="fa fa-refresh"></i> <?php echo $button;
                } ?>  </button>
            <button type="submit" class="btn btn-primary" value="Guardar y Continuar" name="btnsubmit"><i
                    class="fa fa-paste"></i> Guardar y Continuar
            </button>
            <a href="<?php echo site_url('participantes') ?>" class="btn btn-default"><i class="fa fa-close"></i>
                Cancelar</a>
        </form>
    </div>
</div>