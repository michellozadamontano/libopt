<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if(isset($flash)):?>
<div class=" col-md-12" id="opinion_div">
    <div class="alert alert-success" role="alert">
        <p><?php  echo $flash;?> </p>
    </div>
</div>
<?php endif;?>


<div class="col-md-4">
    <div id="date_plan"></div>
    <input type="hidden" id="url" value="<?php echo base_url('pt/list_plan_update')?>">
</div>
<div class="col-md-8">
<div id="view">
    <?php
        if(isset($query))
        {?>
        <table class="table">
        <?php foreach($query->result() as $row): ;?>
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
    <?php
        }
    ?>
</div>
</div>
