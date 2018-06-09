<h2 style="margin-top:0px">Lista de tareas incumplidas</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-8">
                <?php echo anchor(site_url('tareas_incumplidas/create'),'Nueva', 'class="btn btn-primary"'); ?>
            </div>
            
            <div class="col-md-1 text-right">
            </div>
        </div>
        <table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
                <th>Tarea</th>
                <th>Quien Origino</th>
                <th>Causas</th>
                <th>Incumplida</th>
                <th>Suspendida</th>
                <th>Acciones</th>
            </tr><?php
            foreach ($tareas_incumplidas_data as $tareas_incumplidas)
            {
                $incumplida = "";
                $suspendida = "";
                if($tareas_incumplidas->incumplida == 1)
                {
                    $incumplida = 'X';
                }
                if($tareas_incumplidas->suspendida == 1)
                {
                    $suspendida = 'X';
                }
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $tareas_incumplidas->hora.' '.$tareas_incumplidas->actividad ?></td>
			<td><?php echo $tareas_incumplidas->quien_origino ?></td>
			<td><?php echo $tareas_incumplidas->causas ?></td>
			<td><?php echo $incumplida ?></td>
			<td><?php echo $suspendida ?></td>
			<td style="text-align:center" width="200px">
				<?php
				echo anchor(site_url('tareas_incumplidas/update/'.$tareas_incumplidas->id),'Actualizar');
				echo ' | '; 
				echo anchor(site_url('tareas_incumplidas/delete/'.$tareas_incumplidas->id),'Eliminar','onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
				?>
			</td>
		</tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total Record : <?php echo $total_rows ?></a>
	    </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </body>
</html>