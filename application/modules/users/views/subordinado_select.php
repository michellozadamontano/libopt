<div class="col-lg-12  col-md-12 col-sm-12 col-xs-12">
<!--<button id="select-all"><i class="fa fa-check-square"></i> seleccionar todos</button>
<button id="deselect-all"><i class="fa fa-undo"></i> deseleccionar todos</button>-->
</div>
<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <select multiple="multiple" class="chosen-select form-control" id="my_multi_select2" name="my_multi_select[]">
        <optgroup label="Subordinados">
            <?php
            foreach ($users as $value) {
                echo '<option   value="' . $value->id . '">' . $value->name . '</option>';
            }
            ?>
        </optgroup>
    </select>
</div>