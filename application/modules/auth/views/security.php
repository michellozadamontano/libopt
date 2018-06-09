<div class="row">
    <div class="col-md-12">
        <div class="col-md-6 col-md-offset-3">
            <form action="<?php echo base_url('auth/insertcod') ?>" method="post">
                <div class="form-group">
                    <h2> Este sistema no ha sido registrado por favor inserte el c&oacute;digo de validaci&oacute;n para este servidor</h2>
                    <label for=""><?php echo $cadena?></label>
                </div>
                <div class="form-group">
                    <input type="text" style="width:650px" id="codigo" name="codigo" placeholder="Introduza el c&oacute;digo">
                </div>
                <button type="submit" class="btn btn-danger"><i class="fa fa-barcode"> Insertar</i></button>
            </form>
        </div>
    </div>
</div>
