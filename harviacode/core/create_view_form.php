<?php 

$string = "<h2 style=\"margin-top:0px\">".ucfirst($table_name)." <?php echo \$button ?></h2>";

$string .= "<div class=\"panel panel-default\">
  <div class=\"panel-heading\">Datos de ".$table_name."</div>
  <div class=\"panel-body\">";


$string .= "<form action=\"<?php echo \$action; ?>\" method=\"post\">";
foreach ($non_pk as $row) {
    if ($row["data_type"] == 'text')
    {
    $string .= "\n\t    <div class=\"form-group\">
            <label for=\"".$row["column_name"]."\">".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></label>
            <textarea class=\"form-control\" rows=\"3\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\"><?php echo $".$row["column_name"]."; ?></textarea>
        </div>";
    } else
    {
    $string .= "\n\t    <div class=\"form-group\">
            <label for=\"".$row["data_type"]."\">".label($row["column_name"])." <?php echo form_error('".$row["column_name"]."') ?></label>
            <input type=\"text\" class=\"form-control\" name=\"".$row["column_name"]."\" id=\"".$row["column_name"]."\" placeholder=\"".label($row["column_name"])."\" value=\"<?php echo $".$row["column_name"]."; ?>\" />
        </div>";
    }
}
$string .= "\n\t    <input type=\"hidden\" name=\"".$pk."\" value=\"<?php echo $".$pk."; ?>\" /> ";



$string .= "\n\t    <button type=\"submit\" class=\"btn btn-primary\" value=\"<?php echo \$button ?>\" name=\"btnsubmit\"><?php if(\$button == 'Create' || \$button == 'Adicionar' ){ ?><i class=\"fa fa-plus\"></i> <?php echo \$button; }else{ ?><i class=\"fa fa-refresh\"></i> <?php echo \$button;} ?>  </button>";
$string .= "\n\t    <button type=\"submit\" class=\"btn btn-primary\" value=\"Guardar y Continuar\" name=\"btnsubmit\"><i class=\"fa fa-paste\"></i> Guardar y Continuar</button>";


$string .= "\n\t    <a href=\"<?php echo site_url('".$c_url."') ?>\" class=\"btn btn-default\"><i class=\"fa fa-close\"></i> Cancelar</a>";
$string .= "\n\t</form>";


$string .= "\n\t</div></div>";


$hasil_view_form = createFile($string, $target."views/" . $v_form_file);

?>