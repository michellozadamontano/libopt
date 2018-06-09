<div class="row">
    <div class="col-sm-6">
        <form action="<?php echo base_url('Flujo_informativo/form_insert_plan_action'); ?>" method="post">
            <div class="form-group">
                <label for="varchar">Hora Inicio </label>
                <input type="text" class="form-control" name="hora_inicio" id="hora_inicio" placeholder="Hora Inicio"
                       value="" data-rule-required="true"
                       data-msg-required="Inserte hora inicial"/>
            </div>
            <div class="form-group">
                <label for="varchar">Hora Fin </label>
                <input type="text" class="form-control" name="hora_fin" id="hora_fin" placeholder="Hora Fin"
                       value="" data-rule-required="true"
                       data-msg-required="Inserte hora final"/>
            </div>
            <div class="form-group">
                <label for="subcategory">Subcategoria </label>
                <select name="subcategory" id="subcategory" class="form-control chosen-select"></select>
            </div>
            <div class="form-group">

                <label for="grupo_participantes">Grupo Participantes </label>
                <select class="form-control chosen-select" multiple name="grupo_participantes[]"
                        id="grupo_participantes" data-rule-required="true" data-msg-required="Inserte grupo">
                    <?php
                    foreach ($grupo_participantes as $item) {
                        ?>
                        <option value="<?php echo $item->id ?>"><?php echo $item->titulo ?></option>
                        <?php
                    }?>

                </select>
            </div>
            <input type="hidden" name="categId" value="<?php echo $categId?>">
            <input type="hidden" name="importId" value="<?php echo $importId?>">
            <button type="submit" class="btn btn-primary">Insertar</button>
        </form>
    </div>
</div>