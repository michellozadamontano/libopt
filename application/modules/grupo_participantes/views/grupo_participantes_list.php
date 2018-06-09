<h2 style="margin-top:0px">Grupo Participantes</h2>
<div class="row" style="margin-bottom: 10px">
    <div class="col-md-8">
        <?php echo anchor(site_url('grupo_participantes/create'), '<i class="fa fa-plus"></i> Adicionar', 'class="btn btn-primary"'); ?>
    </div>

    <div class="col-md-1 text-right">
    </div>
    <div class="col-md-3 text-right">
        <form action="<?php echo site_url('grupo_participantes'); ?>" class="form-inline" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php
                            if ($q <> '') {
                                ?>
                                <a href="<?php echo site_url('grupo_participantes'); ?>" class="btn btn-default"><i
                                        class="fa fa-undo"></i> Resetear</a>
                                <?php
                            }
                            ?>
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Buscar</button>
                        </span>
            </div>
        </form>
    </div>
</div>
<table class="table table-striped" style="margin-bottom: 10px">
    <tr>
        <th>No</th>
        <th>Titulo</th>
        <th>Acciones</th>
    </tr><?php
    foreach ($grupo_participantes_data as $grupo_participantes) {
        ?>
        <tr>
            <td width="80px"><?php echo ++$start ?></td>
            <td><?php echo $grupo_participantes->titulo ?></td>
            <td style="text-align:center" width="200px">
                <?php
                echo anchor(site_url('grupo_participantes/update/' . $grupo_participantes->id), '<i class="fa fa-pencil-square-o"></i>', array("title" => "Editar", 'class' => 'iconoaction'));
                echo ' ';
                $atributes = array(
                    "title" => "Eliminar",
                    'class' => 'iconoaction',
                    "onclick" => "javasciprt: return confirm('¿Est&aacute; seguro?')");
                echo anchor(site_url('grupo_participantes/delete/' . $grupo_participantes->id), '<i class="fa fa-trash"></i>', $atributes);
                ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<div class="row">
    <div class="col-md-6">
        <a href="#" class="btn btn-primary">Total de Registros : <?php echo $total_rows ?></a>
    </div>
    <div class="col-md-6 text-right">
        <?php echo $pagination ?>
    </div>
</div>
