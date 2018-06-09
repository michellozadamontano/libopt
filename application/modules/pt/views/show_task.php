<div class="row">
    <div class="col-md-6">
        <form action="<?php echo site_url('pt/listar_fechas')?>" method="post">
            <div class="col-md-3">
                <select name="month_task" id="month_task" class="select2">
                    <?php if(isset($mes)): ?>
                        <option <?php if ($mes == '01'){ echo  'selected ';}?> value="01">Enero</option>
                        <option <?php if ($mes == '02'){ echo  'selected ';}?> value="02">Febrero</option>
                        <option <?php if ($mes == '03'){ echo  'selected ';}?> value="03">Marzo</option>
                        <option <?php if ($mes == '04'){ echo  'selected ';}?> value="04">Abril</option>
                        <option <?php if ($mes == '05'){ echo  'selected ';}?> value="05">Mayo</option>
                        <option <?php if ($mes == '06'){ echo  'selected ';}?> value="06">Junio</option>
                        <option <?php if ($mes == '07'){ echo  'selected ';}?> value="07">Julio</option>
                        <option <?php if ($mes == '08'){ echo  'selected ';}?> value="08">Agosto</option>
                        <option <?php if ($mes == '09'){ echo  'selected ';}?> value="09">Septiembre</option>
                        <option <?php if ($mes == '10'){ echo  'selected ';}?> value="10">Obtubre</option>
                        <option <?php if ($mes == '11'){ echo  'selected ';}?> value="11">Noviembre</option>
                        <option <?php if ($mes == '12'){ echo  'selected ';}?> value="12">Diciembre</option>
                    <?php else:?>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Obtubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                    <?php endif?>
                </select>

            </div>
            <div class="col-md-3">
                <select name="year_task" id="year_task" class="select2">
                    <?php foreach($years as $year): ?>
                        <option value="<?php echo $year->fecha?>"><?php echo $year->fecha?></option>
                    <?php  endforeach?>
                </select>
            </div>
            <button type="submit" class="btn btn-circle"><i class="fa fa-list"></i> Listar</button>
        </form>
    </div>
    <?php if(isset($list_date)):?>
    <div class="col-md-6">
        <select name="list_select[]" id="list_select" multiple="multiple">
        <?php foreach($list_date as $list):?>
            <?php $fechaesp = Modules::run('adminsettings/fechaesp',$list->fecha) ?>
            <optgroup label='<?php echo $fechaesp?>'>
                <?php $tareas = Modules::run('pt/listar_tareas',$list->fecha);
                    foreach($tareas as $task):?>
                        <option value="<?php echo $task->id?>"><?php echo $task->actividad?></option>
                    <?php endforeach?>
            </optgroup>
           
        <?php endforeach?>
        </select>
    </div>
    <?php endif?>
</div>
