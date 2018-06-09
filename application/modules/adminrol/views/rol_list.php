<h2 style="margin-top:0px">Rol</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-8">
                <?php echo anchor(site_url('adminrol/create'),'Crear', 'class="btn btn-primary"'); ?>
            </div>
            
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('adminrol'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('adminrol'); ?>" class="btn btn-default">Resetear</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Buscar</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Rol</th>
		<th>Action</th>
            </tr><?php
            foreach ($adminrol_data as $adminrol)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $adminrol->rol ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('adminrol/read/'.$adminrol->id),'Leer');
				echo ' | '; 
				echo anchor(site_url('adminrol/update/'.$adminrol->id),'Actualizar');
				echo ' | '; 
				echo anchor(site_url('adminrol/delete/'.$adminrol->id),'Eliminar','onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
				?>
			</td>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total : <?php echo $total_rows ?></a>

	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>