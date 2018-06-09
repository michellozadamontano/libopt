    <select name="grupo_id" id="grupo_id" class="select2">
        <optgroup label="Grupos ">
            <option value=" ">[selccione grupo]</option>
            <?php foreach($grupos as $row): ?>
                <?php if(isset($grupo_id)): ?>
                    <option <?php if ($grupo_id == $row->id){ echo  'selected ';}?> value="<?php echo $row->id ?>"><?php echo $row->nombre_grupo ?></option>
                <?php else:?>
                    <option value="<?php echo $row->id ?>"><?php echo $row->nombre_grupo ?></option>
                <?php endif?>
            <?php endforeach?>
    </select>
