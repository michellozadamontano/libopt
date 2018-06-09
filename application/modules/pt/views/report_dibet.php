<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plan de Trabajo</title>
    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/style.css'); ?>">
    <style>
        table, td, th {
            border: 1px solid black;

            font-stretch: condensed;
            border-collapse: collapse;
        / / font-size: 16 px;
            table-layout: fixed;
            margin: 0px;
            padding: 0px;
        }

        div.header {
            top: 0cm;
            left: 0cm;
            border-bottom-width: 1px;
        / / height: 3 cm;
        }
    </style>
</head>
<?php
$date = getdate(time());
$month = $mespt;//$date['mon'];
$anno = $yearpt;//$date['year'];
$day_month = days_in_month($month);
$month_count = 1; //este es el numero del mes para un dia
//  setlocale(LC_ALL, 'es_MX.UTF-8');
$lunes = "Lunes";
$martes = "Martes";
$miercoles = "Miércoles";
$jueves = "Jueves";
$viernes = "Viernes";
$sabado = "Sábado";
$domingo = "Domingo";
$usuario = $this->session->userdata('usuario');
$jefe = Modules::run('users/get_user', $usuario->parent_id);
$principal_task = Modules::run('tareasprincipales/get_prinipal_task', $usuario->id, $month, $anno);

//esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
$day = mktime(0, 0, 0, $month, $month_count, $anno);
$dia = strftime('%A', $day);

if ($dia == "Tuesday") $day_month += 1;
if ($dia == "Wednesday") $day_month += 2;
if ($dia == "Thursday") $day_month += 3;
if ($dia == "Friday") $day_month += 4;
if ($dia == "Saturday") $day_month += 5;
if ($dia == "Sunday") $day_month += 6;

$year = strftime('%Y', mktime(0, 0, 0, $month));
$mes = strftime('%B', mktime(0, 0, 0, $month));

if ($mes == "January") $mes = "Enero";
if ($mes == "February") $mes = "Febrero";
if ($mes == "March") $mes = "Marzo";
if ($mes == "April") $mes = "Abril";
if ($mes == "May") $mes = "Mayo";
if ($mes == "June") $mes = "Junio";
if ($mes == "July") $mes = "Julio";
if ($mes == "August") $mes = "Agosto";
if ($mes == "September") $mes = "Setiembre";
if ($mes == "October") $mes = "Octubre";
if ($mes == "November") $mes = "Noviembre";
if ($mes == "December") $mes = "Diciembre";; ?>
<body>
<div class="header">
    <div style="text-align: left;">
        <p> Aprobado por: __________________</p>

        <p><?php if ($jefe != null) echo utf8_decode($jefe->cargo); ?></p>

        <p><?php if ($jefe != null) echo $jefe->name; ?></p>

    </div>
</div>
<div style="text-align: center;">
    <span>PLAN DE TRABAJO INDIVIDUAL MES:<?php echo $mes . ' ' . $anno ?></span><br>
    <span> DE: <b><u><?php echo utf8_decode($usuario->cargo); ?></u></b></span><br>
    <span><b><u>Tareas Principales</u></b></span>

    <div>
        <?php
        $numb = 1;
        foreach ($principal_task as $task): ?>
            <span style="text-align: justify"><?php echo $numb . '. ' . utf8_decode($task->tarea) ?></span><br>
            <?php $numb++; ?>
        <?php endforeach ?>
    </div>
</div>
<br />
<div class="row">
    <?php echo $table_pt; ?>
</div>



<div class="header">
    <div style="text-align: right;">
        <p> __________________</p>

        <p><?php echo utf8_decode($usuario->cargo); ?></p>
        <?php echo $usuario->name; ?>

    </div>
</div>
</body>
</html>


