<h2 style="margin-top:0px">Tareas_incumplidas Read</h2>
        <table class="table">
	    <tr><td>Users Id</td><td><?php echo $users_id; ?></td></tr>
	    <tr><td>Pt Id</td><td><?php echo $pt_id; ?></td></tr>
	    <tr><td>Quien Origino</td><td><?php echo $quien_origino; ?></td></tr>
	    <tr><td>Causas</td><td><?php echo $causas; ?></td></tr>
	    <tr><td>Incumplida</td><td><?php echo $incumplida; ?></td></tr>
	    <tr><td>Suspendida</td><td><?php echo $suspendida; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo site_url('tareas_incumplidas') ?>" class="btn btn-default">Cancel</a></td></tr>
	</table>