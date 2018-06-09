<h2 style="margin-top:0px"><?php echo $button ?> An&aacute;lisis </h2>

<div class="panel panel-default">
    <div class="panel-heading">
        An&aacute;lisis
    </div>
    <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <label for="analisis">Mes de Análisis </label>
                <select name="mes" id="mes" <?php echo $mes; ?> class="form-control chosen-select">
                    <?php
                        foreach ($meses as $item => $value){
                            if(isset($mes)&& $item == $mes)
                            {?>
                                <option value="<?php echo $item?>" selected><?php echo $value?></option>
                                <?php
                            }else{?>
                                <option value="<?php echo $item?>"><?php echo $value?></option>
                                <?php
                            }
                            ?>


                        <?php
                        }
                    ?>
                </select>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <label for="analisis">An&aacute;lisis cualitativo <?php echo form_error('analisis') ?></label>
                <textarea class="form-control" rows="3" name="analisis" id="analisis"
                          placeholder="An&aacute;lisis cualitativo"><?php echo $analisis; ?></textarea>
            </div>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
                <a href="<?php echo site_url('analisis') ?>" class="btn btn-default">Cancelar</a>
            </div>
        </form>
    </div>
</div>