<h2 style="margin-top:0px">Listado de Cargos</h2>
<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('cargos/create'), 'Crear', 'class="btn btn-primary"'); ?>
    </div>

    <div class="col-md-1 text-right">
    </div>
    <div class="col-md-3 text-right">
        <form action="<?php echo site_url('cargos'); ?>" class="form-inline" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php
                            if ($q <> '') {
                                ?>
                                <a href="<?php echo site_url('cargos'); ?>" class="btn btn-default">Resetear</a>
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
        <th>Nombre Cargo</th>
        <th>Action</th>
    </tr><?php
    foreach ($cargos_data as $cargos) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $cargos->nombre_cargo ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('cargos/update/' . $cargos->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                echo ' ';
                echo anchor(site_url('cargos/delete/' . $cargos->id), '<i class="fa fa-trash-o fa-2x"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
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
</body>
</html>