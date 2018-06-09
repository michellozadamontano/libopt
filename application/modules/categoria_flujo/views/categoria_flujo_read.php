<div class="panel panel-default">
  <div class="panel-heading">Datos de categoria_flujo</div>
  <div class="panel-body"><table class="table">
	    <tr><td>Nombre</td><td><?php echo $nombre; ?></td></tr>
	</table>
	 <?php $atributes = array(
    "title"=>"Eliminar",
    'class' => 'iconoaction',
    "onclick" =>"javasciprt: return confirm('¿Est&aacute; seguro?')"); ?>
	 <div class="row">
	 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	    <a href="<?php echo site_url('categoria_flujo/update/'.$id) ?>" class="btn btn-primary"><i class="fa fa-refresh"></i> Editar</a>
	    <a href="<?php echo site_url('categoria_flujo/delete/'.$id) ?>" title="Eliminar" class="btn btn-primary red" onclick="javasciprt: return confirm('¿Est&aacute; seguro?')"><i class="fa fa-trash"></i> Eliminar</a>
	    <a href="<?php echo site_url('categoria_flujo') ?>" class="btn btn-default"><i class="fa fa-close"></i> Cancel</a>
	 </div>
	 </div></div></div>