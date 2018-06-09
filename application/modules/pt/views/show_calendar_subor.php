<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="thumbnail">
            <p>Seleccione el d&iacute;a de la tarea que va a asignar a sus subordinados.</p>

            <form action="<?php echo base_url('pt/tarea_subor') ;?>" method="post" id="calendar-form">
                <input type="text" id="fecha_task_subor" name="fecha" data-rule-required ="true" data-msg-required ="Inserte una fecha por favor" >
                <button type="submit" class="btn btn-success">Aceptar</button>
            </form>
        </div>



    </div>
</div>