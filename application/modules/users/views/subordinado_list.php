<h2 style="margin-top:0px">Mis subordinados</h2>
        <table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Cargo</th>
            </tr><?php
            foreach ($users_data as $users)
            {
                ?>
                <tr>
			<td><?php echo $users->name ?></td>
			<td><?php echo $users->email ?></td>
			<td><?php echo $users->nombre_cargo ?></td>
		</tr>
                <?php
            }
            ?>
        </table>
    </body>
</html>