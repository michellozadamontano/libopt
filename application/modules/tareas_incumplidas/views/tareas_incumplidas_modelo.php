<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reporte Cumplimiento</title>
    <style>
        table, td, th {
            border: 1px solid black;
            padding: 5px;
            font-stretch: condensed;
            border-collapse: collapse;
            font-size: 14px;

        }

        div.header {
            top: 0cm;
            left: 0cm;
            border-bottom-width: 1px;
            height: 3cm;
        }
    </style>
</head>
<body>
<div style="text-align: center">
    <p><b>RESUMEN DEL CUMPLIMIENTO DEL PLAN INDIVIDUAL DEL MES DE <?php echo $mes . ' ' . $year ?></b></p>

    <p><?= '<b>'.$user->nombre_cargo . "</b>: " . $user->name ?></p>
</div>
<br>
<table style="width: 100%; border-collapse: collapse">
    <tr>
        <td rowspan="2" style="text-align: center;">
            <b>TOTAL DE TAREAS PLANIFICAS</b>
        </td>
        <td colspan="3" style="text-align: center;">
            <b>DE ELLAS</b>
        </td>
        <td rowspan="2" style="text-align: center;">
            <b>NUEVAS TAREAS(EXTRAPLANES)</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: center">
            <b>CUMPLIDAS</b>
        </td>
        <td style="text-align: center">
            <b>INCUMPLIDAS</b>
        </td>
        <td style="text-align: center;">
            <b>SUSPENDIDAS O POSPUESTAS</b>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <?php echo $planificadas ?>
        </td>
        <td style="text-align: center;">
            <?php echo $cumplidas ?>
        </td>
        <td style="text-align: center;">
            <?php echo $t_incumplidas ?>
        </td>
        <td style="text-align: center;">
            <?php echo $t_suspendidas ?>
        </td>
        <td style="text-align: center;">
            <?php echo $t_tareasnuevas ?>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="height: 20px"></td>
    </tr>
    <tr>
        <td colspan="3">
            <b>OBSERVACIONES DEL CUMPLIMIENTO</b>
        </td>
        <td style="text-align: center">
            <b>QUIEN LAS ORIGINO</b>
        </td>
        <td style="text-align: center">
            <b>CAUSAS</p></b>
        </td>
    </tr>

    <tr>
        <td colspan="3">&nbsp;</td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3">
            <b>TAREAS INCUMPLIDAS</b>
        </td>
        <td style="text-align: center">

        </td>
        <td style="text-align: center">

        </td>
    </tr>
    <?php
    if (count($incumplidas) == 0) {
        ?>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
        <?php
    }
    ?>
    <?php foreach ($incumplidas as $row): ?>
        <tr>
            <td colspan="3">
                <?php echo $row->actividad ?>
            </td>
            <td>
                <?php echo $row->quien_origino ?>
            </td>
            <td>
                <?php echo $row->causas ?>
            </td>
        </tr>
    <?php endforeach ?>
    <tr>
        <td colspan="3">
            <b>SUSPENDIDAS O POSPUESTAS</b>
        </td>
        <td style="text-align: center">

        </td>
        <td style="text-align: center">

        </td>
    </tr>

    <?php
    if (count($suspendidas) == 0) {
        ?>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
        <?php
    }
    ?>


    <?php foreach ($suspendidas as $row): ?>
        <tr>
            <td colspan="3">
                <?php echo $row->actividad ?>
            </td>
            <td>
                <?php echo $row->quien_origino ?>
            </td>
            <td>
                <?php echo $row->causas ?>
            </td>
        </tr>
    <?php endforeach ?>

    <tr>
        <td colspan="3">
            <b>NUEVAS TAREAS (EXTRA PLANES)</b>
        </td>
        <td style="text-align: center">

        </td>
        <td style="text-align: center">

        </td>
    </tr>
    <?php
    if (count($tareasnuevas) == 0) {
        ?>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td></td>
            <td></td>
        </tr>
        <?php
    }
    ?>
    <?php foreach ($tareasnuevas as $row): ?>
        <tr>
            <td colspan="3">
                <?php echo $row->nuevatarea ?>
            </td>
            <td>
                <?php echo $row->quien_origino ?>
            </td>
            <td>
                <?php echo $row->causas ?>
            </td>
        </tr>
    <?php endforeach ?>
    <tr>
        <td colspan="5" style="height: 20px"><b>ANALISIS CUALITATIVO</b></td>
    </tr>
    <tr>
        <td style="height: 50px;text-align: justify" colspan="5">
            <?php echo $analisis ?>
        </td>
    </tr>
</table>

</body>
</html>