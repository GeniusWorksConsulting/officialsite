<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>Login Form</title>
        <link rel='stylesheet' href="<?= base_url('public/assets/css/login_style.css'); ?>">
    </head>
    <body>

        <div class="wrapper">
            <?php echo form_open('login', array('class' => 'form-signin', 'autocomplete' => 'off')); ?>
            <h3 class="header text-center">Login Form</h3>

            <?php
            if ($this->session->flashdata('incorrect_info') != '') {
                ?>
                <div class="alert alert-danger fade in alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo $this->session->flashdata('incorrect_info'); ?>
                </div>
            <?php }
            ?>

            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" autofocus tabindex="1">
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" tabindex="2">
            </div>

            <div class="form-group text-right">
                <a href="forgot_password" tabindex="4">Forgot your password?</a>
            </div>

            <?php echo form_submit('submit', 'Login', array('class' => 'btn btn-primary btn-block', 'tabindex' => '3')); ?>
            <?php echo form_close(); ?>
        </div>
    </body>
</html>


