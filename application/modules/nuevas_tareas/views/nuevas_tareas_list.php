<h2 style="margin-top:0px">Tareas Extras</h2>

<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('nuevas_tareas/create'), 'Crear', 'class="btn btn-primary"'); ?>
    </div>

</div>
<table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
    <tr>
        <th>No</th>
        <th>Nueva tarea</th>
        <th>Qui&eacute;n Origin&oacute;</th>
        <th>Causas</th>
        <th>Acciones</th>
    </tr><?php
    foreach ($nuevas_tareas_data as $nuevas_tareas) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $nuevas_tareas->nuevatarea ?></td>
            <td><?php echo $nuevas_tareas->quien_origino ?></td>
            <td><?php echo $nuevas_tareas->causas ?></td>

            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('nuevas_tareas/update/' . $nuevas_tareas->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                echo ' ';
                echo anchor(site_url('nuevas_tareas/delete/' . $nuevas_tareas->id), '<i class="fa fa-trash-o fa-2x" data-toggle="tooltip" data-placement="top" title="Eliminar"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
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
