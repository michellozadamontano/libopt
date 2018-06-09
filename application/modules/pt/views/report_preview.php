<style>
    @page {
        margin-top: 1.5cm;
        margin-bottom: 1.5cm;
        margin-left: 2cm;
        margin-right: 2cm;
       // footer: html_letterfooter2;
       // background-color: pink;
    }
</style>
<?php

$usuario = $this->session->userdata('usuario');
$jefe = Modules::run('users/get_user', $usuario->parent_id);
if ($jefe == null) {
    $jefe = Modules::run('users/get_by_rol_id', 4);
}
$month = $mespt;
$anno = $yearpt;

$principal_task = Modules::run('tareasprincipales/get_prinipal_task', $usuario->id, $month, $anno);
?>



<div style="text-align: center;">
    <?php
    $MESES = array(
        '1' => "Enero",
        '2' => "Febrero",
        '3' => "Marzo",
        '4' => "Abril",
        '5' => "Mayo",
        '6' => "Junio",
        '7' => "Julio",
        '8' => "Agosto",
        '9' => "Septiembre",
        '10' => "Octubre",
        '11' => "Noviembre",
        '12' => "Diciembre",
    );
    ?>
    <span>PLAN DE TRABAJO INDIVIDUAL MES: <?= $MESES[$month]. ' '. $anno ?></span><br>
    <span> DE: <b><?php echo $usuario->nombre_cargo; ?>    </b></span><br>
    <span><b>Tareas Principales</b></span>

    <div>
        <?php
        $numb = 1;
        foreach ($principal_task as $task): ?>
            <span><?php echo $numb . '. ' . $task->tarea ?></span><br>
            <?php $numb++; ?>
        <?php endforeach ?>
    </div>
</div>
<br />
<?php
$totaldiasmes = $this->calendar->get_total_days($month, $anno);
$datos = array();
for ($i = 1; $i <= $totaldiasmes; $i++) {
    $info = Modules::run('pt/get_planpdf_v2', $i, $month, $anno);
    if ($info != '-1') {
        $datos[$i] = $info;
    }
}

echo $this->calendar->generate($anno, $month, $datos);
?>