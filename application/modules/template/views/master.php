<!DOCTYPE html>
<html lang="en">
   <head>
    <title><?php echo Modules::run('site_settings/get_site_name'); ?></title>

    <meta charset="utf-8" />

    <meta name="author" content="Ing. Michel Lozada Montano y MSc. Dibet Garcia Gonzalez" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css');?>">
    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('jquery-ui/themes/base/jquery-ui.css');?>">
    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/style.css');?>">
       <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/hover-min.css');?>">

    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/font-awesome.min.css');?>">    

</head>
<body>  
    
   <div class="container">
       <div id="header">
           <div id="logo">
               <img src="<?php echo base_url();?>images/zunpt.jpg" alt=""><br>

           </div>

       </div>  
    <nav class="navbar navbar-default">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url();?>">Zunpt</a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <li class="active"><a href="<?php echo base_url();?>">Home <span class="sr-only">(current)</span></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Acciones <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('pt/show_view_plan');?>">Atualizar Plan</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo base_url('pt/report');?>">Reporte</a></li>
            </ul>
              <ul class=" nav navbar-nav navbar-right">
                  <li><a href="#"><?php echo $this->user->get_name(); ?></a></li>
                  <li><a href="#"></a></li>
                  <li><a href="<?php echo base_url('login/logout'); ?>">Salir</a></li>
              </ul>
          </div><!-- /.navbar-collapse -->

        </div><!-- /.container-fluid -->
     </nav>
    </div> 
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                 <?php
                    echo $this->load->view($module.'/'.$view_file);
                 ?>

          </div>
        </div>
    </div>
    <script src="<?= base_url();?>js/jquery-1.11.0.js"></script>
    <script src="<?= base_url();?>js/bootstrap.min.js"></script>
    <script src="<?= base_url();?>jquery-ui/jquery-ui.js"></script>
   <script src="<?= base_url();?>jquery-ui/jquery.ui.datepicker-es.js"></script>
   <script src="<?= base_url();?>js/zunpt.js"></script>
   <script>
       var base_url="<?php echo base_url();?>";
       var current_url="<?php echo current_url();?>";

   </script>
</body>
</html>