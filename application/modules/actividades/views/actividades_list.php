<h2 style="margin-top:0px">Actividades</h2>
<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('actividades/create'), 'Crear', 'class="btn btn-primary"'); ?>
    </div>

    <div class="col-md-1 text-right">
    </div>
    <div class="col-md-3 text-right">
        <form action="<?php echo site_url('actividades'); ?>" class="form-inline" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php
                            if ($q <> '') {
                                ?>
                                <a href="<?php echo site_url('actividades'); ?>" class="btn btn-default">Reset</a>
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
        <th>Grupo</th>
        <th>Nombre Actividad</th>
        <th>Acciones</th>
    </tr><?php
    foreach ($actividades_data as $actividades) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $actividades->nombre_grupo ?></td>
            <td><?php echo $actividades->nombre_actividad ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('actividades/update/' . $actividades->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                echo '  ';
                echo anchor(site_url('actividades/delete/' . $actividades->id), '<i class="fa fa-trash-o fa-2x"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
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