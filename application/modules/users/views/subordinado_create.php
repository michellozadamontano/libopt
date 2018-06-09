<div class="row" style="margin-bottom: 10px">
    <form action="<?php echo $action; ?>" method="post">
        <select multiple="multiple" class="multi-select" id="my_multi_select" name="my_multi_select[]">
            <optgroup label="Subordinados">
                <?php
                foreach ($users as $value) {
                    echo '<option   value="' . $value->id . '">' . $value->name . '</option>';
                }
                ?>
            </optgroup>
        </select>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </form>

</div>
