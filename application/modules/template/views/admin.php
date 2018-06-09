<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Dashboard ZunPT</title>

    <!-- Bootstrap core CSS -->

    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url();?>css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url();?>">ZunPt</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><i class="fa fa-user"></i><?php echo $this->user->get_name(); ?></a></li>
                <li><a href="#"></a></li>
                <li><a href="#"></a></li>
                <li><?php echo anchor('login/logout','Logout');?></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active">Administracion</a></li>
                <li><?php echo anchor('actividades/manage','Actividades') ?></li>
                <li><?php echo anchor('grupo/manage','Grupo') ?></li>
                <li><?php echo anchor('cargos/manage','Cargos') ?></li>
                <li><?php echo anchor('users/manage','Crear Usuario') ?></li>
                <li><a href="<?php echo base_url('login/logout'); ?>">Salir</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Area de Administración</h1>
            <div class="table-responsive">
                <?php
                echo $this->load->view($module.'/'.$view_file);
                ?>
           </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
    <script src="<?= base_url();?>js/jquery-1.11.0.js"></script>
    <script src="<?= base_url();?>js/bootstrap.min.js"></script>
<script src="<?= base_url();?>/js/docs.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?= base_url();?>/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
