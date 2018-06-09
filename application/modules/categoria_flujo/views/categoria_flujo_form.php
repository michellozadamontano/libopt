<h2 style="margin-top:0px">Categoria_flujo <?php echo $button ?></h2><div class="panel panel-default">
  <div class="panel-heading">Datos de categoria_flujo</div>
  <div class="panel-body"><form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Nombre <?php echo form_error('nombre') ?></label>
            <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary" value="<?php echo $button ?>" name="btnsubmit"><?php if($button == 'Create' || $button == 'Adicionar' ){ ?><i class="fa fa-plus"></i> <?php echo $button; }else{ ?><i class="fa fa-refresh"></i> <?php echo $button;} ?>  </button>
	    <button type="submit" class="btn btn-primary" value="Guardar y Continuar" name="btnsubmit"><i class="fa fa-paste"></i> Guardar y Continuar</button>
	    <a href="<?php echo site_url('categoria_flujo') ?>" class="btn btn-default"><i class="fa fa-close"></i> Cancelar</a>
	</form>
	</div></div>