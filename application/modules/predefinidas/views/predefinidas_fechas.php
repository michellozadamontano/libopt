<div class="portlet box">

<h2 style="margin-top:0px"> Tareas Predefinidas por fechas</h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
            <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"  />
        </div>
	    <div class="form-group">
            <label for="varchar">D&iacute;a <?php echo form_error('dia') ?></label>
            <span>
                <select name="dia" id="dia">
                    <?php for($i=1;$i<31;$i++): ?>
                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor ?>
                </select>
                de cada mes
            </span>
        </div>
	    <div class="form-group">
            <label for="varchar">Hora <?php echo form_error('hora') ?></label>
            <div class="form-group">
                <div class="radio-list">
                    <label class="radio-inline">
                        <input type="time" class="form-control timerpicker" name="hora" id="hora" value="08:00">
                    </label>
                    <label class="radio-inline">
                        <input type="checkbox" name="td" id="td"> T/D
                    </label>
                </div>
            </div>
        </div>
	    <button type="submit" class="btn btn-primary">Crear</button>
	    <a href="<?php echo site_url('predefinidas') ?>" class="btn btn-default">Cancel</a>
	</form>
</div>