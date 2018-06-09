<h2 style="margin-top:0px">Plan de Actividades</h2>
<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('flujo_informativo/create'), '<i class="fa fa-plus"></i> Adicionar', 'class="btn btn-primary"'); ?>
        <?php echo anchor(site_url('flujo_informativo/actividad_import'), '<i class="fa fa-plus"></i> Adicionar por Import', 'class="btn btn-danger"'); ?>
    </div>

    <div class="col-md-1 text-right">
    </div>
    <div class="col-md-3 text-right">
        <form action="<?php echo site_url('flujo_informativo'); ?>" class="form-inline" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php
                            if ($q <> '') {
                                ?>
                                <a href="<?php echo site_url('flujo_informativo'); ?>" class="btn btn-default"><i
                                        class="fa fa-undo"></i> Resetear</a>
                                <?php
                            }
                            ?>
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                        </span>
            </div>
        </form>
    </div>
</div>
<table class="table table-striped" style="margin-bottom: 10px">
    <tr>
        <th>No</th>
        <th>Actividad</th>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Dirigente</th>
        <th>Categoria</th>
        <th>Acciones</th>
    </tr><?php
    foreach ($flujo_informativo_data as $flujo_informativo) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $flujo_informativo->actividad ?></td>
            <td><?php echo $flujo_informativo->hora_inicio ?></td>
            <td><?php echo $flujo_informativo->hora_fin ?></td>
            <td><?php echo $flujo_informativo->dirigente ?></td>
            <td><?php echo $flujo_informativo->nombre ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('flujo_informativo/read/'.$flujo_informativo->id),'<i class="fa fa-eye fa-2x"></i>', array("title"=>"Leer"));
                echo '  ';
                echo anchor(site_url('flujo_informativo/update/' . $flujo_informativo->id), '<i class="fa fa-pencil-square-o fa-2x"></i>', array("title" => "Editar"));
                echo '  ';
                $atributes = array(
                    "title" => "Eliminar",
                    "onclick" => "javasciprt: return confirm('¿Est&aacute; seguro?')");
                echo anchor(site_url('flujo_informativo/delete/' . $flujo_informativo->id), '<i class="fa fa-trash fa-2x"></i>', $atributes);
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<div class="row">
    <div class="col-md-6">
        <a href="#" class="btn btn-primary">Total de Registros : <?php echo $total_rows ?></a>
    </div>
    <div class="col-md-6 text-right">
        <?php echo $pagination ?>
    </div>
</div>
<?php  $date = getdate(time());?>
<hr>
<div class="row">
    <div class="col-md-12">
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Actividad,Hora,Lugar</th>
                <th><?php echo Modules::run('adminsettings/translate_month',$date['month']) ?></th>
                <th>Dirigente</th>
                <th>Participantes</th>
            </tr>
            <?php
            foreach ($categoria as $cat) {
                ?>
                <tr>
                    <td colspan="5">
                        <?php echo $cat->nombre;
                        ?>
                    </td>
                </tr>
                <?php
                $flujo = Modules::run('flujo_informativo/get_flujo_by_month_year', $date['mon'], $date['year'], $cat->id);
                foreach ($flujo as $item) {
                    ?>
                    <tr>
                        <td>

                        </td>
                        <td>
                            <?php echo $item->actividad ?>
                        </td>
                        <td>
                            <?php
                            $list_fechas = Modules::run('flujo_informativo/get_fecha_flujo', $date['mon'], $date['year'], $item->id);
                            $dia = '';
                            $temp = '';
                            $mostrar = '';
                            foreach($list_fechas as $fecha){
                                $str_date = explode("-", $fecha->fecha, 3);
                                $day = (string)(int)$str_date[2];
                                if($dia == '')
                                {
                                    $dia = $day;
                                    $temp = $day;
                                    continue;
                                }

                                    if($day - $temp == 1){
                                        $temp = $day;
                                    }
                                    else
                                    {
                                        if($temp > $dia)
                                        {
                                            $mostrar.= $dia.'-'.$temp.',';
                                            $dia = $day;
                                            $temp = $day;
                                            continue;
                                        }
                                        if($temp = $dia)
                                        {
                                            $mostrar.= $dia.',';
                                            $dia = $day;
                                            $temp = $day;
                                        }

                                       // $mostrar .= $day.',';

                                    }


                               /* echo $day;
                                echo ",";*/

                            }
                           // echo $mostrar;
                            if($dia != '' && $temp != '')
                            {
                                if($dia == $temp)
                                {
                                    $mostrar.= $dia.',';
                                    echo $mostrar;
                                }
                                else
                                {
                                    $mostrar.= $dia.'-'.$temp.',';
                                    echo $mostrar;
                                }
                            }
                            else
                            {
                                echo $mostrar;
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $item->dirigente ?>
                        </td>
                        <td>
                            <?php
                            $list_group = Modules::run('flujo_informativo/get_participantes_flujo', $item->id);
                            foreach($list_group as $group){
                                echo $group->titulo;
                                echo ",";
                                echo "<br>";
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                <?php
            }
            ?>


        </table>
    </div>
</div>
