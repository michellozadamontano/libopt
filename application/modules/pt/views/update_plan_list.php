<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Tareas para este d&iacute;a</h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped">
            <tr>
                <th>Hora</th>
                <th>Tarea</th>
                <th></th>
            </tr><?php
            $month = $this->session->userdata('mes');
            $year = $this->session->userdata('ano');
            foreach ($tareas as $task) {
                ?>
                <tr>
                    <td><?php echo $task->hora.'-'.$task->hora_fin ?></td>
                    <?php if($task->tarea_superior):?>
                        <td><?php echo $task->actividad ?> <i class="fa fa-flag  hidden-print" style="color: red" data-toggle="tooltip" data-placement="top" title="Tarea de Orden Superior (<?php echo Modules::run('Users/get_user',$task->parent_id)->name?>)"></i></td>
                    <?php else :?>
                        <td><?php echo $task->actividad ?></td>
                    <?php endif?>

                    <td style="text-align:center" width="200px">
                        <?php
                        echo anchor(site_url('pt/update_plan/' . $task->id), '<i class="fa fa-2x fa-refresh"></i>');
                        echo ' ';
                        echo anchor(site_url('pt/delete_plan/' . $task->id.'/'.$date), '<i class="fa fa-2x fa-trash-o"></i>', 'onclick="javasciprt: return confirm(\'Estas seguro ?\')"');
                        ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
        <a href="<?php echo site_url('welcome/index/'.$month.'/'.$year) ?>" class="btn btn-success"><i class="fa fa-arrow-left">Regresar</i></a>
    </div>
</div>




