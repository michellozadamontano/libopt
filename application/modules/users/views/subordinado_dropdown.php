<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<select name="users_id" id="users_id" class="select2">
    <optgroup label="Usuarios ">
        <option value="">[selecione usuario]</option>
        <?php if(isset($jefe_supremo)): ?>
            <option value="<?php echo $jefe_supremo->id ?>"><?php echo $jefe_supremo->name ?></option>
        <?php endif ?>
        <?php foreach($users as $row): ?>
            <?php if(isset($user_id)): ?>
                <option <?php if ($user_id == $row->id){ echo  'selected ';}?> value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
            <?php else:?>
                <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
            <?php endif?>
        <?php endforeach?>
</select>