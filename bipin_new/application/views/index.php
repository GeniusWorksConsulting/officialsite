<!--<div id="container">
    <h1></h1>



    <div id="body">
        <a href="<?php echo base_url(); ?>login" style="text-decoration: none;">
            <div style="width: 250px;background: aliceblue;padding: 20px;text-align: center;margin: auto;cursor: pointer;">
                <h3 style="color: dodgerblue;">Login</h3>
            </div>
        </a>

        <a href="<?php echo base_url(); ?>register" style="text-decoration: none;">
            <div style="width: 250px;background: aliceblue;padding: 20px;text-align: center;margin: auto;cursor: pointer;margin-top: 25px;">
                <h3 style="color: dodgerblue;">Register</h3>
            </div>
        </a>

    </div>


</div>

</body>
</html>-->

<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <link rel='stylesheet' href="<?= base_url('public/assets/css/login_style.css'); ?>">
    </head>
    <body>
        <div class="wrapper">
            <?php echo form_open('', array('class' => 'form-signin')); ?>
            <br><br>
            <div class="form-group">
                <a href="<?php echo base_url(); ?>login" class="btn btn-block btn-primary"><h2>LOGIN</h2></a>
            </div>

            <div class="form-group">
                <a href="<?php echo base_url(); ?>register" class="btn btn-block btn-success"><h2>REGISTER</h2></a>
            </div>
            <?php echo form_close(); ?>
        </div>
    </body>
</html>


