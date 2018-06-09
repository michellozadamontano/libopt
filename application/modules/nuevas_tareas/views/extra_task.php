<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="" style="color: #4f0a0f; font-weight: bold">
    <?php
        foreach ($query as $row) {?>
            <p><i class="fa fa-calendar  hidden-print" style="color: red" data-toggle="tooltip" data-placement="top" title="Tarea Extra"></i><?php echo $row->hora_ini.'-'.$row->hora_fin." ".$row->nuevatarea;?> </p>
        <?php
        }
    ?>
</div>
