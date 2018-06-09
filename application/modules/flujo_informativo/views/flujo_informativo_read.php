<div class="panel panel-default">
    <div class="panel-heading">Datos de flujo_informativo</div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <td>Actividad</td>
                <td><?php echo $actividad; ?></td>
            </tr>
            <tr>
                <td>Hora Inicio</td>
                <td><?php echo $hora_inicio; ?></td>
            </tr>
            <tr>
                <td>Hora Fin</td>
                <td><?php echo $hora_fin; ?></td>
            </tr>
            <tr>
                <td>Dirigente</td>
                <td><?php echo $dirigente; ?></td>
            </tr>
            <tr>
                <td>Categoria</td>
                <td><?php echo $categoria_id; ?></td>
            </tr>
            <tr>
                <td>Fecha</td>
                <td>
                    <?php foreach($fechas as $fecha){
                        echo Modules::run('adminsettings/fecha_formato_esp',$fecha->fecha) ;
                        echo', ';
                    } ?>
                </td>
            </tr>
            <tr>
                <td>Participantes</td>
                <td>
                    <?php  foreach($participantes as $participante)
                    {
                        echo $participante->titulo;
                        echo '<br>';
                        echo ',';
                    }
                    ?>
                </td>
            </tr>
        </table>
        <?php $atributes = array(
            "title" => "Eliminar",
            'class' => 'iconoaction',
            "onclick" => "javasciprt: return confirm('¿Est&aacute; seguro?')"); ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a href="<?php echo site_url('flujo_informativo/update/' . $id) ?>" class="btn btn-primary"><i
                        class="fa fa-refresh"></i> Editar</a>
                <a href="<?php echo site_url('flujo_informativo/delete/' . $id) ?>" title="Eliminar"
                   class="btn btn-primary red" onclick="javasciprt: return confirm('¿Est&aacute; seguro?')"><i
                        class="fa fa-trash"></i> Eliminar</a>
                <a href="<?php echo site_url('flujo_informativo') ?>" class="btn btn-default"><i
                        class="fa fa-close"></i> Cancel</a>
            </div>
        </div>
    </div>
</div>