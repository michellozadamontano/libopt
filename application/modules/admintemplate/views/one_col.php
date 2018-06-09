<?php echo $headpage; ?>

<body class="page-header-fixed page-sidebar-closed-hide-logo 
      page-content-white"

    <?php
    echo $headermenu;
    ?>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <?php echo $aside;?>
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content">
                <!-- BEGIN PAGE HEADER-->
                <!-- BEGIN THEME PANEL -->
             
                <!-- END THEME PANEL -->
                <!-- BEGIN PAGE BAR -->
                <div class="page-bar">
               
                </div>
                <!-- END PAGE BAR -->
                
                </br>
                
                <?php echo $vista;?>

                
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT -->
     <?php
     echo $quicksidebar;
     ?>
    </div>
    <!-- END CONTAINER -->










    <?php
    echo $footer;
    echo $javascript;
    ?>


</body>
</html>