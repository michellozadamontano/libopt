<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<div style="overflow: none !important;">
    <?php foreach($query as $row):;?>
        <?php if($row->tarea_superior):?>
            <?php if($row->hora!='T/D') :?>
                <?php $superior = Modules::run('Users/get_user',$row->parent_id);?>
                <p><?php echo $row->hora.'-'.$row->hora_fin." ".$row->actividad;?> <i class="fa fa-certificate  hidden-print" style="color: red" data-toggle="tooltip" data-placement="top" title="Tarea de Orden Superior (<?php echo $superior->name?>)"></i></p>
                <?php else :?>
                <p><?php echo $row->hora." ".$row->actividad;?> <i class="fa fa-certificate  hidden-print" style="color: red" data-toggle="tooltip" data-placement="top" title="Tarea de Orden Superior (<?php //echo Modules::run('Users/get_user',$row->parent_id)->name?>)"></i></p>
            <?php endif?>
        <?php else :?>
            <?php if($row->hora!='T/D') :?>
                <p><?php echo $row->hora.'-'.$row->hora_fin." ".$row->actividad;?></p>
            <?php else :?>
                <p><?php echo $row->hora." ".$row->actividad;?></p>
            <?php endif?>
        <?php endif?>
    <?php endforeach;?>
</div>
