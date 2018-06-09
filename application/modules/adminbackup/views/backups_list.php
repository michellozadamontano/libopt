<h2 style="margin-top:0px">Resguardos de la Base de Datos</h2>
<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('adminbackup/backup'), '<i class="fa fa-plus"></i> Resguardar', 'class="btn btn-primary"'); ?>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
        <tr>
            <th>No</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>

        <?php
        $start = 0;
        if (isset($files)) {
            foreach ($files as $item) {
                ?>
                <tr>
                    <td width="80px"><?php echo ++$start ?></td>
                    <td><?php echo $item ?></td>
                    <td style="text-align:center" width="200px">
                        <?php
                        echo anchor(site_url('adminbackup/download/' . $item), '<i class="fa fa-download"></i>', array("title" => "Descargar", 'class' => 'iconoaction'));
                        echo ' | ';
                        echo anchor(site_url('adminbackup/restore/' . $item), '<i class="fa fa-arrow-up"></i>', array("title" => "Restaurar", 'class' => 'iconoaction'));
                        echo ' | ';
                        $atributes = array(
                            "title" => "Eliminar",
                            'class' => 'iconoaction',
                            "onclick" => "javasciprt: return confirm('¿Est&aacute; seguro?')");
                        echo anchor(site_url('adminbackup/delete/' . $item), '<i class="fa fa-trash"></i>', $atributes);
                        ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
    </table>
</div>
