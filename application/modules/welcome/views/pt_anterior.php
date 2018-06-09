<div class="row">
    <div class="col-md-12">
        <form action="<?php echo base_url('welcome/post_pt_anterior') ?>" method="post">
            <div class="col-md-6 col-md-offset-3">
                <label for="">Planes de meses anteriores</label>
                <br>
                <select name="month" id="month" class="form-control selected">
                    <label for="">Mes</label>
                    <?php foreach($month_list as $key=>$value):?>
                        <option <?php if ($key == $month){ echo  'selected ';}?> value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php endforeach?>
                </select>
                <br>
                <label for="">Año</label>
                <select name="year" id="year" class="form-control selected">
                    <option value="<?php echo $current_year ?>" selected><?php echo $current_year ?></option>
                    <option value="<?php echo $last_year ?>"><?php echo $last_year ?></option>
                    <option value="<?php echo $next_year ?>"><?php echo $next_year ?></option>
                </select>
                <br>
                <p>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-calendar-check-o"> Ir a Plan Anterior</i></button>
                </p>
            </div>
        </form>
    </div>
</div>