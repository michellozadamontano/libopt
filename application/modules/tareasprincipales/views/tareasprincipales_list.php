<div class="row">
    <?php
    $oldsecond = mktime(0, 0, 0, $mes, 1);
    $oldmonth = ($mes == 1) ? 12 : $mes - 1;
    $nextmonth = ($mes == 12) ? 1 : $mes + 1;
    $mestext = strftime('%B', $oldsecond); //mes en letra
    $mestext = Modules::run('adminsettings/translate_month', $mestext);
    $yeartext = $year;//strftime('%Y', $oldsecond);
    $nextyear = ($mes == 12) ? $year + 1 : $year;
    $backyear = ($mes == 1) ? $year - 1 : $year;
    ?>

    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-6">
        <h3><?php echo anchor(site_url('tareasprincipales/create/' . $mes), 'Crear', 'class="btn btn-primary"'); ?> Tareas principales del mes </h3>
    </div>
    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-6">
        <h2 style="text-align: right;margin-top: 0px;padding-top: 0px;">
            <a href="<?php echo base_url('tareasprincipales/index/' . $oldmonth . '/' . $backyear) ?>"
               data-toggle="tooltip"
               data-placement="top"
               title="Mes Anterior" class="btn btn-danger"><i class="fa fa-arrow-left"></i></a>

            <?php echo $mestext . ' ' . $yeartext ?>

            <a href="<?php echo base_url('tareasprincipales/index/' . $nextmonth . '/' . $nextyear) ?>"
               data-toggle="tooltip"
               data-placement="top"
               title="Mes proximo" class="btn btn-danger"><i class="fa fa-arrow-right"></i></a>
        </h2>

    </div>
</div>

<table class="table table-striped">
    <tr>
        <th>No</th>
        <th>Tarea</th>
        <th>Acciones</th>
    </tr><?php
    foreach ($tareasprincipales_data as $tareasprincipales) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $tareasprincipales->tarea ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('tareasprincipales/update/' . $tareasprincipales->id), '<i class="fa fa-refresh fa-2x"></i>');
                echo ' ';
                echo anchor(site_url('tareasprincipales/delete/' . $tareasprincipales->id), '<i class="fa fa-trash-o fa-2x"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
                ?>
            </td>
        </tr>
    <?php
    }
    ?>

</table>

    <a href="#" class="btn btn-primary">Total: <?php echo $total_rows ?></a>

    <?php echo $pagination ?>
