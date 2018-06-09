<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table class="table">
    <?php foreach($query as $row): ;?>
    <tr>
        <td>
            <?php echo $row->hora.' '.$row->actividad;?>
        </td>
        <td>
            <a href="<?php echo base_url('pt/delete_plan/'.$row->id);?>" class="btn btn-danger"><i class="fa fa-times"></i></a>
        </td>
    </tr>
    <?php endforeach;?>
</table>
