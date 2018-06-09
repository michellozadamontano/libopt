<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<select name="cargo" id="cargo" class="form-control chosen-select">

    <?php
        foreach($cargos as $row):?>
            <option <?php if ($row->id == $cargo){ echo  'selected ';}?> value="<?php echo $row->id ?>"><?php echo $row->nombre_cargo ?></option>
        <?php endforeach?>

</select>