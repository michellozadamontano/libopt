<div class="row">
    <div class="col-md-12">

        <h2 style="margin-top:0px">Tareas Predefinidas por días</h2>

        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-8">
                <?php echo anchor(site_url('predefinidas/create'), 'Crear', 'class="btn btn-primary"'); ?>
            </div>

            <div class="col-md-1 text-right">
            </div>
            <div class="col-md-3 text-right">
                <form action="<?php echo site_url('predefinidas'); ?>" class="form-inline" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q" value="<?php echo $q; ?>">
                        <span class="input-group-btn">
                            <?php
                            if ($q <> '')
                            {
                                ?>
                                <a href="<?php echo site_url('predefinidas'); ?>" class="btn btn-default">Reset</a>
                                <?php
                            }
                            ?>
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </span>
                    </div>
                </form>
            </div>

        </div>
        <table class="table table-striped table-condensed table-responsive table-hover" style="margin-bottom: 10px">
            <tr>
                <th>No</th>
                <th>Tarea</th>
                <th>D&iacute;a</th>
                <th>Hora Inicio-Hora Fin</th>
                <th>Acciones</th>
            </tr><?php
            foreach ($predefinidas_data as $predefinidas) {
                ?>
                <tr>
                    <td width="80px"><?php echo ++$start ?></td>
                    <td><?php echo $predefinidas->tarea ?></td>
                    <td><?php echo $predefinidas->dia ?></td>
                    <?php if ($predefinidas->hora == 'T/D'): ?>
                        <td><?php echo $predefinidas->hora ?></td>
                    <?php else : ?>
                        <td><?php echo $predefinidas->hora ?>-<?php echo $predefinidas->hora_fin ?></td>
                    <?php endif ?>
                    <td style="text-align:center" width="200px">
                        <?php
                        echo anchor(site_url('predefinidas/update/' . $predefinidas->id), '<i class="fa fa-refresh fa-2x"></i>', ['data-toggle' => "tooltip", 'data-placement' => "top", 'title' => "Actualizar"]);
                        echo ' ';
                        echo anchor(site_url('predefinidas/delete/' . $predefinidas->id), '<i class="fa fa-trash-o fa-2x"></i>', 'onclick="javasciprt: return confirm(\'Estas Seguro ?\')"');
                        ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col-md-6">
                <a href="#" class="btn btn-primary">Total : <?php echo $total_rows ?></a>
            </div>
            <div class="col-md-6 text-right">
                <?php echo $pagination ?>
            </div>
        </div>
    </div>
</div>