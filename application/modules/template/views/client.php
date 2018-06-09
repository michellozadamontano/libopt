<!DOCTYPE html>
<html lang="en">
   <head>
    <title><?php echo $title; ?></title>

    <meta charset="utf-8" />

    <meta name="author" content="Ing. Michel Lozada Montano y MSc. Dibet Garcia Gonzalez" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/bootstrap.min.css');?>">
    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/style.css');?>">

    <link media="all" type="text/css" rel="stylesheet" href="<?php echo base_url('css/font-awesome.min.css');?>">    

</head>
<body>  
    
   <div class="container">
       <div id="header">
           <div id="logo">
               <img src="<?php echo base_url();?>images/logo.png" alt=""><br>
               designer jewelery
           </div>

       </div>  
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Dashboard</a>
          </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Users <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('login/register'); ?>">Register</a></li>
                        <li><a href="<?php echo site_url('login/logout')?>">Log out</a></li>
                    </ul>
                </li>
            </ul>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

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
</body>
</html>