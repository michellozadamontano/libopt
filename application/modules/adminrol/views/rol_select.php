<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<select name="rol_id" id="rol_id" class="select2">
    <optgroup label="Rol">
        <option value="">[Seleccione Rol]</option>
    <?php
        foreach($rol as $row):?>
            <option <?php if ($rol_id == $row->id){ echo  'selected ';}?> value="<?php echo $row->id ?>"><?php echo $row->rol ?></option>
        <?php endforeach?>

</select>