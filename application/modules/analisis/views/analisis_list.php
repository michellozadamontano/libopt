<h2 style="margin-top:0px">An&aacute;lisis por meses</h2>
<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('analisis/create'), 'Crear', 'class="btn btn-primary"'); ?>
    </div>

</div>
<table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
    <tr>
        <th>No</th>
        <th>Mes</th>
        <th>Año</th>
        <th>Analisis</th>
        <th>Acciones</th>
    </tr><?php
    foreach ($analisis_data as $analisis) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $analisis->mes ?></td>
            <td><?php echo $analisis->ano ?></td>
            <td><?php echo $analisis->analisis ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('analisis/update/' . $analisis->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                echo ' | ';
                echo anchor(site_url('analisis/delete/' . $analisis->id), '<i class="fa fa-trash-o fa-2x"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
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