<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo CV_PROJECT_NAME; ?> | <?php echo $title; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="<?= base_url('assets/login/images/icons/favicon.ico') ?>"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/font-awesome.min.css') ?>">

        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/util.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/main.css') ?>">
    </head>
    <body>

        <div class="limiter">
            <div class="container-login100">
                <div class="wrap-login100">
                    <?php echo form_open('auth/login', array('class' => 'login100-form', 'autocomplete' => 'off')); ?>
                    <span class="login100-form-title p-b-26">
                        User Login
                    </span>
                    <?php if (isset($message)) { ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <?= $message; ?>
                        </div>
                    <?php } ?>

                    <div class="wrap-input100">
                        <?php echo form_input($identity); ?>
                    </div>

                    <div class="wrap-input100">
                        <span class="btn-show-pass">
                            <i class="fa fa-eye"></i>
                        </span>
                        <?php echo form_input($password); ?>
                    </div>

                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <div class="col-12">
                        <div class="checkbox checkbox-success">
                            <label>
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>&nbsp;
                                Remember me
                            </label>
                        </div>


                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button type="submit" class="login100-form-btn">
                                Login
                            </button>
                        </div>
                    </div>

                    <div class="text-center p-t-15">
                        <a class="txt2 text-info" <a href="forgot_password">
                                <?php echo lang('login_forgot_password'); ?>
                            </a>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!--===============================================================================================-->
        <script src="<?= base_url('assets/login/js/jquery-3.2.1.min.js'); ?>"></script>
        <script src="<?= base_url('assets/login/js/bootstrap.min.js'); ?>"></script>
        <script src="<?= base_url('assets/login/js/main.js'); ?>"></script>
    </body>
</html>