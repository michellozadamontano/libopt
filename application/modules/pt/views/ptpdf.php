<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div style="padding: 5px;border-bottom: 1px solid black;width: 100% !important;background-color: #cccccc;">
    <?php
    $DIASSEMANA = array(
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Mi&eacute;rcoles',
        'Thursday' =>'Jueves',
        'Friday'=> 'Viernes',
        'Saturday'=> 'S&aacute;bado',
        'Sunday' => 'Domingo'
    );
    $diasemana = date("l",strtotime($a.'/'.$m.'/'.$d));
    echo $DIASSEMANA[$diasemana]. " ".$d;
    ?>
</div>
<div style="padding:5px 5px 40px 5px;border:0px solid black;">
    <?php foreach($query as $row):;?>
        <?php if($row->hora!='T/D') :?>
            <p><?php echo $row->hora.'-'.$row->hora_fin." ".$row->actividad;?></p>
        <?php else :?>
            <p><?php echo $row->hora." ".$row->actividad;?></p>
        <?php endif?>
    <?php endforeach;?>

</div>