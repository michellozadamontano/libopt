<?php 

$string = "<div class=\"panel panel-default\">
  <div class=\"panel-heading\">Datos de $table_name</div>
  <div class=\"panel-body\">";


$string .= "<table class=\"table\">";
foreach ($non_pk as $row) {
    $string .= "\n\t    <tr><td>".label($row["column_name"])."</td><td><?php echo $".$row["column_name"]."; ?></td></tr>";
}

$string .= "\n\t</table>";


$string .= "\n\t <?php \$atributes = array(
    \"title\"=>\"Eliminar\",
    'class' => 'iconoaction',
    \"onclick\" =>\"javasciprt: return confirm('¿Est&aacute; seguro?')\"); ?>";


$string .= "\n\t <div class=\"row\">";
    $string .= "\n\t <div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">";
        $string .= "\n\t    <a href=\"<?php echo site_url('".$c_url."/update/'.\$id) ?>\" class=\"btn btn-primary\"><i class=\"fa fa-refresh\"></i> Editar</a>";
        $string .= "\n\t    <a href=\"<?php echo site_url('".$c_url."/delete/'.\$id) ?>\" title=\"Eliminar\" class=\"btn btn-primary red\" onclick=\"javasciprt: return confirm('¿Est&aacute; seguro?')\"><i class=\"fa fa-trash\"></i> Eliminar</a>";
        $string .= "\n\t    <a href=\"<?php echo site_url('".$c_url."') ?>\" class=\"btn btn-default\"><i class=\"fa fa-close\"></i> Cancel</a>";
    $string .= "\n\t </div>";
$string .= "\n\t </div>";

$string .= "</div></div>";

$hasil_view_read = createFile($string, $target."views/" . $v_read_file);

?>