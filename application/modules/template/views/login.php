<!DOCTYPE html>
<html lang="en">
   <head>
    <title><?php echo Modules::run('site_settings/get_site_name'); ?></title>

    <meta charset="utf-8" />

    <meta name="author" content="Ing. Michel Lozada Montano" />

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
               <img src="<?php echo base_url();?>images/zunpt.jpg" alt=""><br>

           </div>

       </div>
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