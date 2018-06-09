<h2 style="margin-top:0px">Flujo_informativo List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-8">
                <?php echo anchor(site_url('flujo_informativo/create'),'<i class="fa fa-plus"></i> Adicionar', 'class="btn btn-primary"'); ?>
            </div>
            
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('flujo_informativo'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('flujo_informativo'); ?>" class="btn btn-default"><i class="fa fa-undo"></i> Resetear</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Actividad</th>
		<th>Hora Inicio</th>
		<th>Hora Fin</th>
		<th>Dirigente</th>
		<th>Categoria Id</th>
		<th>Acciones</th>
            </tr><?php
            foreach ($flujo_informativo_data as $flujo_informativo)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $flujo_informativo->actividad ?></td>
			<td><?php echo $flujo_informativo->hora_inicio ?></td>
			<td><?php echo $flujo_informativo->hora_fin ?></td>
			<td><?php echo $flujo_informativo->dirigente ?></td>
			<td><?php echo $flujo_informativo->categoria_id ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('flujo_informativo/read/'.$flujo_informativo->id),'<i class="fa fa-eye fa-2x"></i>', array("title"=>"Leer")); 
				echo ' | '; 
				echo anchor(site_url('flujo_informativo/duplicar/'.$flujo_informativo->id),'<i class="fa fa-files-o fa-2x"></i>', array("title"=>"Duplicar"); 
				echo ' | '; 
				echo anchor(site_url('flujo_informativo/update/'.$flujo_informativo->id),'<i class="fa fa-pencil-square-o fa-2x"></i>', array("title"=>"Editar")); 
				echo ' | '; $atributes = array(
        "title"=>"Eliminar",        
        "onclick" =>"javasciprt: return confirm('¿Est&aacute; seguro?')");
				echo anchor(site_url('flujo_informativo/delete/'.$flujo_informativo->id),'<i class="fa fa-trash fa-2x"></i>',$atributes ); 
				?>
			</td>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total de Registros : <?php echo $total_rows ?></a>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
