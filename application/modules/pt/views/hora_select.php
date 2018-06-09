<select name="hora" id="hora" class="select2">
    <optgroup label="Selecciona hora ">
        <option value=" "></option>
        <?php foreach($horas as $row): ?>
            <?php if(isset($hora)): ?>
                <option <?php if ($hora == $row){ echo  'selected ';}?> value="<?php echo $row ?>"><?php echo $row ?></option>
            <?php else:?>
                <option value="<?php echo $row ?>"><?php echo $row ?></option>
            <?php endif?>
        <?php endforeach?>
</select>