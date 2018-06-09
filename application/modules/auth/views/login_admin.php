<?php echo $headpage; ?>
<?php echo $javascripts; ?>

<body class="login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="<?php echo site_url(); ?>">
            <img src="<?php echo site_url('images/libopt.png'); ?>" alt="" /> </a>
    </div>
    <div class="content" style="height: 260px;">
        <form class="login-form" action="<?php echo site_url(); ?>auth/login_action" method="post">
            <h3 class="form-title">Login</h3>
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Correo</label>
                <div class="input-icon">
                    <i class="fa fa-user"></i>
                    <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Correo" name="correo" />
                </div>
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Password</label>

                <div class="input-icon">
                    <i class="fa fa-lock"></i>
                    <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" />
                </div>
            </div>
            <div class="row">
                <button type="reset" class="btn white pull-right"> Reset </button>
                <button type="submit" class="btn green pull-right"> Iniciar sesi&oacute;n </button>
            </div>
        </form>
    </div>
<div class="container">
    <?php echo form_error('correo') ?><br/>
    <?php echo form_error('password') ?>
</div>

</body>
