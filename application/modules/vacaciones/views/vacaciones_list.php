<h2 style="margin-top:0px">Vacaciones</h2>

<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('vacaciones/create'), 'Crear', 'class="btn btn-primary"'); ?>
    </div>

</div>
<table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
    <tr>
        <th>No</th>
        <th>Desde</th>
        <th>Hasta</th>
        <th>Acción</th>
    </tr><?php
    foreach ($vacaciones_data as $vacaciones) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo Modules::run('adminsettings/fecha_vacaciones', $vacaciones->desde) ?></td>
            <td><?php echo Modules::run('adminsettings/fecha_vacaciones', $vacaciones->hasta) ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('vacaciones/update/' . $vacaciones->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                echo ' ';
                echo anchor(site_url('vacaciones/delete/' . $vacaciones->id), '<i class="fa fa-trash-o fa-2x" data-toggle="tooltip" data-placement="top" title="Eliminar"></i>', 'onclick="javasciprt: return confirm(\'Estas seguro ?\')"');
                ?>
            </td>
        </tr>
    <?php
    }
    ?>
</table>
<div class="row">
    <div class="col-md-6">
        <a href="#" class="btn btn-primary">Total: <?php echo $total_rows ?></a>
    </div>
    <div class="col-md-6 text-right">
        <?php echo $pagination ?>
    </div>
</div>
