<!doctype html>
<html>
<head>
    <title>Plan de Trabajo</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
    <style>
        .word-table {
           /* border:1px solid black !important;*/
            border-collapse: collapse !important;
            width: 100%;
        }
        .word-table tr th, .word-table tr td{
            border:1px solid black !important;
           /* padding: 5px 10px;*/
            margin: 0!important;
        }
        .word-data{
            border: 0px!important;
        }
    </style>
</head>
<body>
<h2>Plan de trabajo</h2>
        <?php
        $date = getdate(time());
        $month = $date['mon'];
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
        $usuario = $this->session->userdata('usuario');

        //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
        $day = mktime(0,0,0,$month,$month_count);
        $dia = strftime('%A', $day);

        if ($dia=="Tuesday") $day_month+=1;
        if ($dia=="Wednesday") $day_month+=2;
        if ($dia=="Thursday") $day_month+=3;
        if ($dia=="Friday") $day_month+=4;
        if ($dia=="Saturday") $day_month+=5;
        if ($dia=="Sunday") $day_month+=6;

        $year =  strftime('%Y',mktime(0,0,0,$month));
        $mes=strftime('%B',mktime(0,0,0,$month));

        if ($mes=="January") $mes="Enero";
        if ($mes=="February") $mes="Febrero";
        if ($mes=="March") $mes="Marzo";
        if ($mes=="April") $mes="Abril";
        if ($mes=="May") $mes="Mayo";
        if ($mes=="June") $mes="Junio";
        if ($mes=="July") $mes="Julio";
        if ($mes=="August") $mes="Agosto";
        if ($mes=="September") $mes="Setiembre";
        if ($mes=="October") $mes="Octubre";
        if ($mes=="November") $mes="Noviembre";
        if ($mes=="December") $mes="Diciembre";
        ;?>
<div class="header">
    <div style="text-align: left;">
        <p> Aprobado por: __________________</p>
        <?php //echo;?>
    </div>
</div>
<div style="text-align: center;">
    <p>PLAN DE TRABAJO INDIVIDUAL MES:<?php echo $mes.' '.$year ?></p>
    <p> DE: <?php echo utf8_decode($usuario->cargo);?></p>
    <br>
    <p>Tareas Principales</p>

</div>
        <table class="word-table">

            <?php

            for($i=1 ; $i<=$day_month;$i++):;?>
                <tr>
                    <?php
                    $day_number = 1;
                    $show = false;

                    for($k = $i ; $k<= ($i+6);$k++):
                        if($k > $day_month)break;
                        if($day_number == 8) $day_number = 1;

                        ;?>

                        <td>
                            <?php
                            $second = mktime(0,0,0,$month,$month_count);
                            $date = strftime('%d/%m/%Y',$second);
                            $dia = strftime('%A', $second);

                            if ($dia=="Monday") $dia="Lunes";
                            if ($dia=="Tuesday") $dia="Martes";
                            if ($dia=="Wednesday") $dia="Miércoles";
                            if ($dia=="Thursday") $dia="Jueves";
                            if ($dia=="Friday") $dia="Viernes";
                            if ($dia=="Saturday") $dia="Sábado";
                            if ($dia=="Sunday") $dia="Domingo";?>

                            <table class="word-data">
                               <tr>
                                   <th>
                                       <?php
                                       if(($day_number == 1)&&($dia == $lunes)){
                                           echo "Lunes";
                                           echo ' '.$month_count;
                                           $month_count++;
                                           $show = true;
                                       }
                                       if(($day_number == 2)&& ($dia == $martes)){
                                           echo "Martes";
                                           echo ' '.$month_count;
                                           $month_count++;
                                           $show = true;

                                       }
                                       if(($day_number == 3)&&($dia == $miercoles)){
                                           echo utf8_decode("Miércoles");
                                           echo ' '.$month_count;
                                           $month_count++;
                                           $show = true;

                                       }
                                       if(($day_number == 4)&&($dia == $jueves)){
                                           echo "Jueves";
                                           echo ' '.$month_count;
                                           $month_count++;
                                           $show = true;
                                       }
                                       if(($day_number == 5)&&($dia == $viernes)){
                                           echo "Viernes";
                                           echo ' '.$month_count;
                                           $month_count++;
                                           $show = true;
                                       }
                                       if(($day_number == 6)&&($dia == $sabado)){
                                           echo utf8_decode("Sábado");
                                           echo ' '.$month_count;
                                           $month_count++;
                                           $show = true;
                                       }
                                       if(($day_number == 7)&&($dia == $domingo)){
                                           echo "Domingo";
                                           echo ' '.$month_count;
                                           $month_count++;
                                           $show = true;
                                       }
                                       $day_number ++;
                                       ?>
                                   </th>
                               </tr>
                                <tr>
                                    <td>
                                        <?php if($show):?>
                                            <?php echo utf8_decode(Modules::run('pt/get_plan',$date)) ;?>
                                        <?php endif?>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    <?php endfor;?>
                    <?php
                    if($month_count == $day_month)break;
                    $i = $k-1;
                    ?>
                </tr>
            <?php endfor;?>
        </table>
<div class="header">
    <div style="text-align: right;">
        <p> __________________</p>
        <p><?php echo utf8_decode($usuario->cargo);?></p>
        <?php echo $usuario->name;?>

    </div>
</div>





</body>
</html>