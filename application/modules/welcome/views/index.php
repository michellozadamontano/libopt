<div class="row">

    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-6 hidden-print">
        <a href="#" data-toggle="modal" data-target="#myModal" data-backdrop="static" class="btn dark"><i
                class="fa fa-refresh"></i> Refrescar tareas predefinidas</a>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 hidden-print">
        <?php
        if (isset($soldado)):?>
            <a href="<?php echo base_url('welcome/notification/' . $mes . '/' . $year) ?>" class="btn btn-primary"
               data-toggle="tooltip" data-placement="top" title="Notificar al jefe para que apruebe el plan de trabajo"><i
                    class="icon-envelope"></i></a>
        <?php endif ?>
        <a href="<?php echo base_url('pt/report_preview/' . $mes . '/' . $year) ?>" class="btn btn-success"
           data-toggle="tooltip" data-placement="top" title="Vista previa del plan" target="_blank"><i
                    class="fa fa-file-pdf-o"></i></a>
        <!--<button class="btn btn-primary" onclick="window.print();">Imprimir</button>-->
    </div>
    <div class="col-md-5 col-lg-5 col-sm-5 col-xs-6 hidden-print">
        <?php
        //  setlocale(LC_ALL, 'es_MX.UTF-8');

        $oldsecond = mktime(0, 0, 0, $mes, 1, $year);
        $oldmonth = ($mes == 1) ? 12 : $mes - 1;
        $nextmonth = ($mes == 12) ? 1 : $mes + 1;
        $mestext = strftime('%B', $oldsecond); //mes en letra
        $mestext = Modules::run('adminsettings/translate_month', $mestext);
        $yeartext = $year; //strftime('%Y', $oldsecond);
        $nextyear = ($mes == 12) ? $year + 1 : $year;
        $backyear = ($mes == 1) ? $year - 1 : $year;
        ?>

        <h2 style="text-align: right;margin-top: 0px;padding-top: 0px;">
            <a href="<?php echo base_url('welcome/index/' . $oldmonth . '/' . $backyear) ?>" data-toggle="tooltip"
               data-placement="top"
               title="Mes Anterior" class="btn btn-danger"><i class="fa fa-arrow-left"></i></a>
            <?php echo $mestext . ' ' . $yeartext ?>
            <a href="<?php echo base_url('welcome/index/' . $nextmonth . '/' . $nextyear) ?>" data-toggle="tooltip"
               data-placement="top"
               title="Mes proximo" class="btn btn-danger"><i class="fa fa-arrow-right"></i></a>
        </h2>

    </div>
    <?php

    // $month = $date['mon'];
    $month = $mes;
    $this->session->set_userdata('mes', $month);
    $day_month = days_in_month($month);
    $month_count = 1; //este es el numero del mes para un dia

    $lunes = "Lunes";
    $martes = "Martes";
    $miercoles = "Miércoles";
    $jueves = "Jueves";
    $viernes = "Viernes";
    $sabado = "Sábado";
    $domingo = "Domingo";

    //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
    $day = mktime(0, 0, 0, $month, $month_count, $yeartext);
    $dia = strftime('%A', $day);

    if ($dia == "Tuesday") $day_month += 1;
    if ($dia == "Wednesday") $day_month += 2;
    if ($dia == "Thursday") $day_month += 3;
    if ($dia == "Friday") $day_month += 4;
    if ($dia == "Saturday") $day_month += 5;
    if ($dia == "Sunday") $day_month += 6;
    $visited = Modules::run('pt/check_month', $month, $yeartext);
    $predefinidas = Modules::run('predefinidas/has_task');
    $predefinidas_fecha = Modules::run('predefinida_fecha/has_task');
    $predefinidas_dias_fecha = Modules::run('predefinidas_dias_fecha/has_task'); ?>
    <?php //if ((!$visited) && ($predefinidas)||(!$visited) && ($predefinidas_fecha)||(!$visited) && ($predefinidas_dias_fecha)):
    if ((!$visited) && ($predefinidas)||(!$visited) && ($predefinidas_fecha)||(!$visited) && ($predefinidas_dias_fecha)):?>

        <?php Modules::run('welcome/iniciaPlan', $mes); ?>

    <?php else: ?>
        <?php
        // if(!$visited)Modules::run('welcome/insert_datos_jefe', $mes);
        ?>
        <table width="100%" class="mytable">

            <?php
            //  setlocale(LC_ALL, 'nld_nld');

            for ($i = 1; $i <= $day_month; $i++):; ?>
                <tr style="height: 100% !important;">
                    <?php
                    $day_number = 1;
                    $show = false;

                    for ($k = $i; $k <= ($i + 6); $k++):
                        if ($k > $day_month) break;
                        if ($day_number == 8) $day_number = 1;; ?>

                        <td style="height: 100% !important;vertical-align: top;width:14% !important;">
                            <?php
                            $second = mktime(0, 0, 0, $month, $month_count, $yeartext);
                            $date = strftime('%d-%m-%Y', $second);
                            $dia = strftime('%A', $second);

                            if ($dia == "Monday") $dia = "Lunes";
                            if ($dia == "Tuesday") $dia = "Martes";
                            if ($dia == "Wednesday") $dia = "Miércoles";
                            if ($dia == "Thursday") $dia = "Jueves";
                            if ($dia == "Friday") $dia = "Viernes";
                            if ($dia == "Saturday") $dia = "Sábado";
                            if ($dia == "Sunday") $dia = "Domingo"; ?>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="titulopt">

                                        <b>
                                            <?php
                                            if ($day_number == 1) {
                                                echo "Lunes";
                                                if ($dia == $lunes) {
                                                    echo ' ' . $month_count;
                                                    $month_count++;
                                                    $show = true;
                                                }
                                            }
                                            if ($day_number == 2) {
                                                echo "Martes";
                                                if ($dia == $martes) {
                                                    echo ' ' . $month_count;
                                                    $month_count++;
                                                    $show = true;
                                                }
                                            }
                                            if ($day_number == 3) {
                                                echo "Miércoles";
                                                if ($dia == $miercoles) {
                                                    echo ' ' . $month_count;
                                                    $month_count++;
                                                    $show = true;
                                                }
                                            }
                                            if ($day_number == 4) {
                                                echo "Jueves";
                                                if ($dia == $jueves) {
                                                    echo ' ' . $month_count;
                                                    $month_count++;
                                                    $show = true;
                                                }
                                            }
                                            if ($day_number == 5) {
                                                echo "Viernes";
                                                if ($dia == $viernes) {
                                                    echo ' ' . $month_count;
                                                    $month_count++;
                                                    $show = true;
                                                }
                                            }
                                            if ($day_number == 6) {
                                                echo "Sábado";
                                                if ($dia == $sabado) {
                                                    echo ' ' . $month_count;
                                                    $month_count++;
                                                    $show = true;
                                                }
                                            }
                                            if ($day_number == 7) {
                                                echo "Domingo";
                                                if ($dia == $domingo) {
                                                    echo ' ' . $month_count;
                                                    $month_count++;
                                                    $show = true;
                                                }
                                            }
                                            $day_number++;
                                            ?>
                                        </b>
                                        <?php if ($show): ?>
                                            <a href="<?php echo base_url('pt/create_plan/' . $date) ?>"
                                               data-toggle="tooltip" data-placement="top" title="Insertar Tarea"
                                               class=" btn-icon-only blue btn-md hidden-print"><i
                                                    class="fa fa-plus"></i></a>

                                            <a href="<?php echo base_url('pt/update_plan_list/' . $date) ?>"
                                               data-toggle="tooltip" data-placement="top" title="Actualizar Tarea"
                                               class=" btn-icon-only  btn-md  hidden-print"><i
                                                    class="fa fa-refresh"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($show): ?>

                                <div class="bodypt " style="overflow: none !important;">

                                    <?php echo Modules::run('pt/get_plan', $date); ?>
                                    <br>
                                    <?php echo Modules::run('nuevas_tareas/get_plan', $date); ?>
                                </div>


                            <?php endif ?>


                        </td>
                    <?php endfor; ?>
                    <?php
                    if ($month_count == $day_month) break;
                    $i = $k - 1;
                    ?>
                </tr>
            <?php endfor; ?>
        </table>
    <?php endif ?>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Información</h4>
            </div>
            <div class="modal-body">
                <p>Esto eliminará los datos del plan de trabajo y recargará de nuevo las tareas predefinidas, está usted seguro?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a href="<?php echo base_url('welcome/refresh_plan/'.$mes) ?>"  class="btn btn-primary">Confirmar</a>
            </div>
        </div>
    </div>
</div>