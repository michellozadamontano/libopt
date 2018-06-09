<select name="list_task" id="list_task" class="form-control">
    <?php foreach($list_date as $list):?>
        <?php $fechaesp = Modules::run('adminsettings/fechaesp',$list->fecha) ?>
        <option value=""></option>
        <optgroup label='<?php echo $fechaesp?>'>
            <?php $tareas = Modules::run('pt/listar_tareas',$list->fecha);
            foreach($tareas as $task):?>
                <option <?php if ($pt_id == $task->id){ echo  'selected ';}?> value="<?php echo $task->id?>"><?php echo $task->hora.' '.$task->actividad?></option>
            <?php endforeach?>
        </optgroup>
    <?php endforeach?>
</select>