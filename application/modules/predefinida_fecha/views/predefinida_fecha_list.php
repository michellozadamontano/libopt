<h2 style="margin-top:0px">Predefinida por fecha</h2>

<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('predefinida_fecha/create'), 'Crear', 'class="btn btn-primary"'); ?>
    </div>

    <div class="col-md-1 text-right">
    </div>
    <div class="col-md-3 text-right">
        <form action="<?php echo site_url('predefinida_fecha'); ?>" class="form-inline" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                <span class="input-group-btn">
                            <?php
                            if ($q <> '')
                            {
                                ?>
                                <a href="<?php echo site_url('predefinida_fecha'); ?>" class="btn btn-default">Reset</a>
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
        <th>Tarea</th>
        <th>D&iacute;a</th>
        <th>Hora</th>
        <th>Acción</th>
    </tr><?php
    foreach ($predefinida_fecha_data as $predefinida_fecha) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $predefinida_fecha->tarea ?></td>
            <td><?php echo $predefinida_fecha->dia ?></td>
            <?php if ($predefinida_fecha->hora == 'T/D'): ?>
                <td><?php echo $predefinida_fecha->hora ?></td>
            <?php else : ?>
                <td><?php echo $predefinida_fecha->hora ?>-<?php echo $predefinida_fecha->hora_fin ?></td>
            <?php endif ?>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('predefinida_fecha/update/' . $predefinida_fecha->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                echo ' ';
                echo anchor(site_url('predefinida_fecha/delete/' . $predefinida_fecha->id), '<i class="fa fa-trash-o fa-2x" data-toggle="tooltip" data-placement="top" title="Eliminar"></i>', 'onclick="javasciprt: return confirm(\'Estas seguro ?\')"');
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

