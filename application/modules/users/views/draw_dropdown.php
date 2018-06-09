<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<select name="parent_id" id="parent_id" class="select2">
    <optgroup label="Usuarios ">
        <option value="">[No se subordina]</option>
        <?php foreach($users as $row): ?>
            <?php if(isset($parent_id)): ?>
                <option <?php if ($parent_id == $row->id){ echo  'selected ';}?> value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
            <?php else:?>
                <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
            <?php endif?>
        <?php endforeach?>
</select>