<?php  $date = getdate(time());
    $mes = Modules::run('adminsettings/translate_month_number',$month)
?>


<span>________________________</span><br>
<span><?php echo  $presidente->nombre_cargo;?></span><br>
<span><?php echo  $presidente->name;?></span><br>
<br><br><br>
<div style="text-align: center;">
    <span>PLAN DE TRABAJO PARA EL MES DE <?php echo $mes?> DEL <?php echo $date['year']?></span>
</div>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered" style="width: 100%;font-size: 12pt" border="1">
            <tr style="background-color: #00A5E3">
                <th>No</th>
                <th>Actividad,Hora,Lugar</th>
                <th><?php echo Modules::run('adminsettings/translate_month_number',$month) ?></th>
                <th>Dirigente</th>
                <th>Participantes</th>
            </tr>
            <?php
            foreach ($categoria as $cat) {
                $count = 0;
                ?>
                <tr>
                    <td colspan="5" style="background-color: yellow">
                        <?php echo $cat->nombre;
                        ?>
                    </td>
                </tr>
                <?php
                $flujo = Modules::run('flujo_informativo/get_flujo_by_month_year', $month, $date['year'], $cat->id);
                foreach ($flujo as $item) {
                    ?>
                    <tr>
                        <td>
                            <?php echo ++$count ?>
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
                               // echo $day;
                               // echo ",";
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
                                    }

                            }
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