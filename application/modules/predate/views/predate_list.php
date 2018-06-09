<div class="row">
    <div class="col-md-12">
        <div class="panel default>
            <div class="panel-heading">
                <h2 style="margin-top:0px">Actividades anuales</h2>
            </div>
            <div class="panel-body">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-8">
                        <?php echo anchor(site_url('predate/create'), 'Crear', 'class="btn btn-primary"'); ?>
                    </div>
                </div>
                <table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
                    <tr>
                        <th>No</th>
                        <th>Fecha</th>
                        <th>Hora Inicio</th>
                        <th>Hora Fin</th>
                        <th>Actividad</th>
                        <th></th>
                    </tr><?php
                    foreach ($predate_data as $predate) {
                        ?>
                        <tr>
                            <td width="80px"><?php echo ++$start ?></td>
                            <td><?php echo Modules::run('adminsettings/fechaesp',$predate->fecha)  ?></td>
                            <td><?php echo $predate->hora_inicio ?></td>
                            <td><?php echo $predate->hora_fin ?></td>
                            <td><?php echo $predate->actividad ?></td>
                            <td style="text-align:center" width="200px">
                                <?php
                                echo anchor(site_url('predate/update/' . $predate->id), '<i class="fa fa-refresh fa-2x"></i>',['data-toggle'=>"tooltip",'data-placement'=>"top",'title'=>"Actualizar"]);
                                echo ' | ';
                                echo anchor(site_url('predate/delete/' . $predate->id), '<i class="fa fa-trash-o fa-2x" data-toggle="tooltip" data-placement="top" title="Eliminar"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
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
            </div>
        </div>
    </div>
</div>