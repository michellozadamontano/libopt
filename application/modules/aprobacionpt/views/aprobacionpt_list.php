<h2 style="margin-top:0px">Aprobacionpt List</h2>
        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-8">
                <?php echo anchor(site_url('aprobacionpt/create'),'Create', 'class="btn btn-primary"'); ?>
            </div>
            
            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('aprobacionpt'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php 
                                if ($q <> '')
                                {
                                    ?>
                                    <a href="<?php echo site_url('aprobacionpt'); ?>" class="btn btn-default">Reset</a>
                                    <?php
                                }
                            ?>
                          <button class="btn btn-primary" type="submit">Search</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
		<th>Users Id</th>
		<th>Fecha</th>
		<th>Action</th>
            </tr><?php
            foreach ($aprobacionpt_data as $aprobacionpt)
            {
                ?>
                <tr>
			<td width="80px"><?php echo ++$start ?></td>
			<td><?php echo $aprobacionpt->users_id ?></td>
			<td><?php echo $aprobacionpt->fecha ?></td>
			<td style="text-align:center" width="200px">
				<?php 
				echo anchor(site_url('aprobacionpt/read/'.$aprobacionpt->id),'Read'); 
				echo ' | '; 
				echo anchor(site_url('aprobacionpt/update/'.$aprobacionpt->id),'Update'); 
				echo ' | '; 
				echo anchor(site_url('aprobacionpt/delete/'.$aprobacionpt->id),'Delete','onclick="javasciprt: return confirm(\'Are You Sure ?\')"'); 
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