<div class="row">

    <div class="col-md-12">
        <?php

           // $month = $date['mon'];
            $month = $mes;
            $oldsecond = mktime(0,0,0,$mes,1);
            $mydate = strftime('%d-%m-%Y',$oldsecond);
            $mestext = strftime('%B',$oldsecond); //mes en letra
            $mestext = Modules::run('adminsettings/translate_month',$mestext);
            $yeartext = $year;//strftime('%Y',$oldsecond);

            $day_month = days_in_month($month);
            $month_count = 1; //este es el numero del mes para un dia

            $lunes      = "Lunes";
            $martes     = "Martes";
            $miercoles  = "Miércoles";
            $jueves     = "Jueves";
            $viernes    = "Viernes";
            $sabado     = "Sábado";
            $domingo    = "Domingo";

        //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
        $day = mktime(0,0,0,$month,$month_count,$yeartext);
        $dia = strftime('%A', $day);

        if ($dia=="Tuesday") $day_month+=1;
        if ($dia=="Wednesday") $day_month+=2;
        if ($dia=="Thursday") $day_month+=3;
        if ($dia=="Friday") $day_month+=4;
        if ($dia=="Saturday") $day_month+=5;
        if ($dia=="Sunday") $day_month+=6;

        ?>
        <span>
            <p style="text-align: center">Plan de trabajo de: <?php echo $user->name ?></p>

        </span>
        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3">
            <h2 style="text-align: right;margin-top: 0px;padding-top: 0px;"><?php echo $mestext . ' ' . $yeartext ?></h2>
        </div>
        <div class="col-md-3 col-lg-3 col-sm-3 col-xs-3">
            <?php if(isset($aproved)):?>
             <h3 style="color:red">Este Plan de trabajo ya ha sido aceptado</h3>
            <?php else:?>
                <form action="<?php echo base_url('aprobacionpt/create_action')?>" method="post">
                    <input type="hidden" name="users_id" id="users_id" value="<?php echo $user_id?>">
                    <input type="hidden" name="mes" id="mes" value="<?php echo $month?>">
                    <input type="hidden" name="month" id="month" value="<?php echo $month?>">
                    <input type="hidden" name="year" id="year" value="<?php echo $year?>">
                    <button type="submit" class="btn btn-default"><i class="fa fa-th-large"></i> Aprobar PT</button>
                </form>
            <?php endif?>
        </div>
        <div class="col-md3">
            <a href="#" data-toggle="modal" data-target="#myModal" data-backdrop="static" class="btn btn-default"><i class="fa fa-envelope-square" data-toggle="tooltip" data-placement="top" title="Notificar revisión"></i></a>
        </div>

        <table width="100%" class="mytable">

            <?php
            //  setlocale(LC_ALL, 'nld_nld');

            for($i=1 ; $i<=$day_month;$i++):;?>
                <tr style="height: 100% !important;">
                    <?php
                    $day_number = 1;
                    $show = false;

                    for($k = $i ; $k<= ($i+6);$k++):
                        if($k > $day_month)break;
                        if($day_number == 8) $day_number = 1;

                        ;?>

                        <td style="height: 100% !important;vertical-align: top;width:14% !important;">
                            <?php
                            $second = mktime(0,0,0,$month,$month_count,$yeartext);
                            $date = strftime('%d-%m-%Y',$second);
                            $dia = strftime('%A', $second);

                            if ($dia=="Monday") $dia="Lunes";
                            if ($dia=="Tuesday") $dia="Martes";
                            if ($dia=="Wednesday") $dia="Miércoles";
                            if ($dia=="Thursday") $dia="Jueves";
                            if ($dia=="Friday") $dia="Viernes";
                            if ($dia=="Saturday") $dia="Sábado";
                            if ($dia=="Sunday") $dia="Domingo";?>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="titulopt">
                                        <b>
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
                                        </b>
                                </div>
                            </div>
                        </div>
                                <?php if($show):?>

                                        <div class="bodypt " style="overflow: none !important;">

                                            <?php echo Modules::run('pt/get_plan_subor',$date,$user_id);?>

                                        </div>

                                <?php endif?>

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
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información</h4>
            </div>
            <form action="<?php echo base_url('aprobacionpt/notifica_revision') ?>" method="post">
                <div class="modal-body">
                    Enviar nota al usuario.
                    <textarea name="text_email" id="" cols="70" rows="10"></textarea>
                    <input type="hidden" name="user" value="<?php echo $user_id ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>

                </div>
            </form>
        </div>
    </div>
</div>
