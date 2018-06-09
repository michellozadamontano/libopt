<h2 style="margin-top:0px"><?php echo $button ?>Tareas predefinidas por dias del mes </h2>
        <div class="panel panel-default">
            <div class="panel-heading">
                Tarea predefinida
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" id="PreFecha">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="varchar">Tarea <?php echo form_error('tarea') ?></label>
                            <input type="text" class="form-control" name="tarea" id="tarea" placeholder="Tarea"
                                   value="<?php echo $tarea; ?>" data-rule-required="true" data-msg-required="Inserte Tarea"/>
                        </div>
                        <div class="form-group">
                            <label for="int">Los dias <?php echo form_error('dia') ?></label>

                            <select name="dia" id="dia" class="form-control" data-rule-required="true" data-msg-required="Seleccione dia">
                                <?php for ($i = 1; $i < 31; $i++): ?>
                                    <?php if (isset($dia)): ?>
                                        <option <?php if ($dia == $i) {
                                            echo 'selected ';
                                        } ?> value="<?php echo $i ?>"><?php echo $i ?> de cada mes
                                        </option>
                                    <?php else: ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?> de cada mes</option>
                                    <?php endif ?>

                                <?php endfor ?>
                            </select>

                        </div>
                        <div class="form-group">

                            <div class="radio-list">
                                <label class="radio-inline"> Hora <?php echo form_error('hora') ?>
                                    <?php if ($hora != null): ?>
                                        <input type="text" class="form-control" name="hora" id="hora"
                                               value="<?php echo $hora; ?>" data-rule-required="true" data-msg-required="Inserte Hora Inicio">
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="hora" id="hora" value="08:00" data-msg-required="Inserte Hora Inicio">
                                    <?php endif ?>
                                </label>
                                <label class="radio-inline">Hora Fin<?php echo form_error('hora_fin') ?>
                                    <?php if ($hora_fin != null): ?>
                                        <input type="text" class="form-control" name="horaf" id="horaf"
                                               value="<?php echo $hora_fin ?>" data-rule-required="true"
                                               data-msg-required="Inserte una hora fin">
                                    <?php else: ?>
                                        <input type="text" class="form-control" name="horaf" id="horaf" value="08:30"
                                               data-rule-required="true" data-msg-required="Inserte una hora de fin">
                                    <?php endif ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="checkbox" name="td" id="td"> T/D
                                </label>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                        <a href="<?php echo site_url('predefinida_fecha') ?>" class="btn btn-default">Cancel</a>
                    <br />
                    <br />
                    </div>
                    <div class="col-md-6">
                        <?php if (isset($directivo)): ?>
                            <h3>Añadir tareas a subordinados</h3>
                            <?php echo Modules::run('users/subordinado_select', $user->id) ?>
                        <?php endif ?>
                    </div>
                </form>
            </div>
        </div>
