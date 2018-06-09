<h2 style="margin-top:0px">Predefinida según días</h2>
<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('predefinidas_dias_fecha/create'), 'Crear', 'class="btn btn-primary"'); ?>
    </div>

    <div class="col-md-1 text-right">
    </div>
    <div class="col-md-3 text-right">
        <form action="<?php echo site_url('predefinidas_dias_fecha'); ?>" class="form-inline" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                <span class="input-group-btn">
                            <?php
                            if ($q <> '')
                            {
                                ?>
                                <a href="<?php echo site_url('predefinidas_dias_fecha'); ?>" class="btn btn-default">Reset</a>
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
        <th>Cuando</th>
        <th>D&iacute;a</th>
        <th>Hora</th>
        <th>Acción</th>
    </tr><?php
    foreach ($predefinidas_dias_fecha_data as $predefinidas_dias_fecha) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $predefinidas_dias_fecha->tarea ?></td>
            <td><?php echo $predefinidas_dias_fecha->cuando ?></td>
            <td><?php echo $predefinidas_dias_fecha->dia ?></td>
            <?php if ($predefinidas_dias_fecha->hora == 'T/D'): ?>
                <td><?php echo $predefinidas_dias_fecha->hora ?></td>
            <?php else : ?>
                <td><?php echo $predefinidas_dias_fecha->hora ?>
                    -<?php echo $predefinidas_dias_fecha->hora_fin ?></td>
            <?php endif ?>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('predefinidas_dias_fecha/update/' . $predefinidas_dias_fecha->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                echo ' ';
                echo anchor(site_url('predefinidas_dias_fecha/delete/' . $predefinidas_dias_fecha->id), '<i class="fa fa-trash-o fa-2x" data-toggle="tooltip" data-placement="top" title="Eliminar"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
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
