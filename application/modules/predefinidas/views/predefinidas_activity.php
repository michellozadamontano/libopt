<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<div>
    <?php foreach($actividades as $row):;?>
        <?php if($dia == $row->dia):?>
            <p><?php echo $row->hora.' '.$row->tarea;?></p>
        <?php endif?>
        <?php if($row->dia == 'T/D'):?>
            <p><?php echo $row->hora.' '.$row->tarea;?></p>
        <?php endif?>
    <?php endforeach;?>
</div>
