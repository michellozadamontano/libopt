<h2 style="margin-top:0px">Flujo_informativo <?php echo $button ?></h2><div class="panel panel-default">
  <div class="panel-heading">Datos de flujo_informativo</div>
  <div class="panel-body"><form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Actividad <?php echo form_error('actividad') ?></label>
            <input type="text" class="form-control" name="actividad" id="actividad" placeholder="Actividad" value="<?php echo $actividad; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Hora Inicio <?php echo form_error('hora_inicio') ?></label>
            <input type="text" class="form-control" name="hora_inicio" id="hora_inicio" placeholder="Hora Inicio" value="<?php echo $hora_inicio; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Hora Fin <?php echo form_error('hora_fin') ?></label>
            <input type="text" class="form-control" name="hora_fin" id="hora_fin" placeholder="Hora Fin" value="<?php echo $hora_fin; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Dirigente <?php echo form_error('dirigente') ?></label>
            <input type="text" class="form-control" name="dirigente" id="dirigente" placeholder="Dirigente" value="<?php echo $dirigente; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">Categoria Id <?php echo form_error('categoria_id') ?></label>
            <input type="text" class="form-control" name="categoria_id" id="categoria_id" placeholder="Categoria Id" value="<?php echo $categoria_id; ?>" />
        </div>
	    <input type="hidden" name="id" value="<?php echo $id; ?>" /> 
	    <button type="submit" class="btn btn-primary" value="<?php echo $button ?>" name="btnsubmit"><?php if($button == 'Create' || $button == 'Adicionar' ){ ?><i class="fa fa-plus"></i> <?php echo $button; }else{ ?><i class="fa fa-refresh"></i> <?php echo $button;} ?>  </button>
	    <button type="submit" class="btn btn-primary" value="Guardar y Continuar" name="btnsubmit"><i class="fa fa-paste"></i> Guardar y Continuar</button>
	    <a href="<?php echo site_url('flujo_informativo') ?>" class="btn btn-default"><i class="fa fa-close"></i> Cancelar</a>
	</form>
	</div></div>