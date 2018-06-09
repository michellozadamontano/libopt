<div class="row">
    <?php // aqui voy a definir variables globales
     $pt_id = 0; //este es el id del plan de trabajo.
    ?>
    <div class="col-md-6">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-tasks font-green"></i>
                    <span class="caption-subject font-green sbold uppercase">Tareas hasta la fecha</span>
                </div>
            </div>
            <div class="portlet-body ">
                <div class="dd" id="nestable_list_1">
                    <ol class="dd-list">
                        <?php foreach ($fechas as $f):?>
                            <li class="dd-item" data-id="2">
                                <?php $fechaesp = Modules::run('adminsettings/fechaesp',$f->fecha) ?>
                                <div class="dd-handle"> <?php echo $fechaesp ?> </div>
                                <?php $actividades = Modules::run('pt/listar_tareas',$f->fecha);
                                foreach($actividades as $act):?>

                                    <ol class="dd-list">
                                        <li class="dd-item" data-id="3">
                                            <div class="dd">
                                               <table>
                                                   <tr>
                                                       <td width="500px">
                                                           <?php echo $act->hora.' '.'-'.' '.$act->actividad ?>
                                                       </td>
                                                       <td><a href="<?php echo base_url('tareas_incumplidas/mes_actual_incumplida/'.$act->id)?>" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Incumplidas / Suspendidas"><i class="fa fa-gg-circle"></i>Inc/Susp</a></td>
                                                   </tr>
                                               </table>
                                            </div>
                                        </li>
                                    </ol>
                                <?php endforeach?>
                            </li>
                        <?php endforeach?>
                    </ol>
                </div>
            </div>
        </div>
    </div>

</div>