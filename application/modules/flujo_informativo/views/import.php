<html>
<head>
    <title>Importar excel a mysql</title>
</head>
<body>

<?php echo form_open_multipart('flujo_informativo/to_mysql');?>

<input type="file" name="excel" size="20" />

<br /><br />
<input type="submit" value="Importar"  class="btn btn-primary"/>

<?php echo form_close() ?>

</body>
</html>