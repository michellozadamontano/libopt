<div class="row">
    <div class="col-md-12">
        <?php
            $date = getdate(time());
            //$month = $date['mon'];
            $month = $mes;
            $day_month = days_in_month($month);
            $month_count = 1; //este es el numero del mes para un dia
          //  setlocale(LC_ALL, 'es_MX.UTF-8');
            $lunes      = "Lunes";
            $martes     = "Martes";
            $miercoles  = "Miércoles";
            $jueves     = "Jueves";
            $viernes    = "Viernes";
            $sabado     = "Sábado";
            $domingo    = "Domingo";

        //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
        $day = mktime(0,0,0,$month,$month_count,$year);
        $dia = strftime('%A', $day);

        if ($dia=="Tuesday") $day_month+=1;
        if ($dia=="Wednesday") $day_month+=2;
        if ($dia=="Thursday") $day_month+=3;
        if ($dia=="Friday") $day_month+=4;
        if ($dia=="Saturday") $day_month+=5;
        if ($dia=="Sunday") $day_month+=6;
        ;?>
        <table width="100%" class="mytable">

            <?php
            //  setlocale(LC_ALL, 'nld_nld');

            for($i=1 ; $i<=$day_month;$i++):;?>
                <tr>
                    <?php
                    $day_number = 1;
                    $show = false;

                    for($k = $i ; $k<= ($i+6);$k++):
                        if($k > $day_month)break;
                        if($day_number == 8) $day_number = 1;

                        ;?>

                        <td width="80px">
                            <?php
                            $second = mktime(0,0,0,$month,$month_count,$year);
                            $date = strftime('%d/%m/%Y',$second);
                            $dia = strftime('%A', $second);

                            if ($dia=="Monday") $dia="Lunes";
                            if ($dia=="Tuesday") $dia="Martes";
                            if ($dia=="Wednesday") $dia="Miércoles";
                            if ($dia=="Thursday") $dia="Jueves";
                            if ($dia=="Friday") $dia="Viernes";
                            if ($dia=="Saturday") $dia="Sábado";
                            if ($dia=="Sunday") $dia="Domingo";?>

                            <div class="panel panel-info  ">
                                <div class="panel-heading">
                                    <?php
                                        if($day_number == 1){
                                            echo "Lunes";
                                            if($dia == $lunes){
                                                echo ' '.$month_count;
                                                $month_count++;
                                                $show = true;
                                            }
                                        }
                                        if($day_number == 2){
                                            echo "Martes";
                                            if($dia == $martes){
                                                echo ' '.$month_count;
                                                $month_count++;
                                                $show = true;
                                            }
                                        }
                                        if($day_number == 3){
                                            echo "Miércoles";
                                            if($dia == $miercoles){
                                                echo ' '.$month_count;
                                                $month_count++;
                                                $show = true;
                                            }
                                        }
                                        if($day_number == 4){
                                            echo "Jueves";
                                            if($dia == $jueves) {
                                                echo ' '.$month_count;
                                                $month_count++;
                                                $show = true;
                                            }
                                        }
                                        if($day_number == 5){
                                            echo "Viernes";
                                            if($dia == $viernes) {
                                                echo ' '.$month_count;
                                                $month_count++;
                                                $show = true;
                                            }
                                        }
                                        if($day_number == 6){
                                            echo "Sábado";
                                            if($dia == $sabado) {
                                                echo ' '.$month_count;
                                                $month_count++;
                                                $show = true;
                                            }
                                        }
                                        if($day_number == 7){
                                            echo "Domingo";
                                            if($dia == $domingo) {
                                                echo ' '.$month_count;
                                                $month_count++;
                                                $show = true;
                                            }
                                        }
                                    $day_number ++;
                                    ?>
                                </div>
                                <?php if($show):?>
                                    <a  class="effects" href="<?php echo $date ;?>">
                                        <div class="panel-body hvr-wobble-bottom" style="height: 100px;width: 100%;overflow: scroll;position: relative" >

                                            <?php
                                                echo Modules::run('predefinidas/get_activity_by_user',$user->id,$dia,$date);
                                            ;?>

                                        </div></a>
                                <?php endif?>
                                </div>

                        </td>
                    <?php endfor;?>
                    <?php
                    if($month_count == $day_month)break;
                    $i = $k-1;
                    ?>
                </tr>
            <?php endfor;?>
        </table>
    </div>

    <!--<div class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Tareas Diarias</h4>
                </div>
                <div class="modal-body">
                    <?php
                    $attributes = array('id' => 'myform');
                    echo form_open(base_url('pt/insert_plan'),$attributes);?>
                    <p id="date_task"></p>
                    <div class="form-group">
                        <?php
                            $options = ['08:00'=>'08:00',
                                '9:00'=>'9:00',
                                '10:00'=>'10:00',
                                '11:00'=>'11:00',
                                '12:00'=>'12:00',
                                '13:00'=>'13:00',
                                '14:00'=>'14:00',
                                '15:00'=>'15:00',
                                '16:00'=>'16:00',
                                'T/D'=>'T/D'];
                            echo form_dropdown('hora',$options,'8:00',['class'=>'form-control','id'=>'hora'])
                        ;?>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="dir_activity" value="<?php echo base_url('actividades/activity_by_group');?>">
                        <?php echo Modules::run('grupo/grupo_select');?>
                    </div>
                    <div class="form-group">
                        <select name="activity" id="activity" class="form-control">
                            <option value="">Selecciona el Grupo</option>
                        </select>
                    </div>
                    <span style="color: red">Si la tarea no está en la lista dejela en blanco y escriba la nueva aquí: <i class="fa fa-hand-o-down fa-lg"></i></span>
                    <div class="form-group">
                        <?php echo form_textarea('extra_task','',['class'=>'form-control','id'=>'extra_task','placeholder'=>'Tarea Extra']);?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btn_save">Aceptar</button>
                </div>
                <?php echo form_close();?>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>--><!-- /.modal -->
</div>
