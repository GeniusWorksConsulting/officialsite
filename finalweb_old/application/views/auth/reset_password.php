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
                    <?php echo form_open('auth/reset_password/' . $code, array('class' => 'login100-form', 'autocomplete' => 'off')); ?>
                    <span class="login100-form-title p-b-26">
                        <?php echo lang('reset_password_heading'); ?>
                    </span>

                    <?php if (isset($message)) { ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <?= $message; ?>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <!--<label for="identity">
                        <?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length); ?>
                        </label>-->

                        <div class="wrap-input100">
                            <?php echo form_input($new_password); ?>
                        </div>

                        <!--<label for="identity">
                        <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm'); ?>
                        </label>-->
                        <div class="wrap-input100">
                            <?php echo form_input($new_password_confirm); ?>
                        </div>
                    </div>

                    <?php echo form_input($user_id); ?>
                    <?php echo form_hidden($csrf); ?>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button type="submit" class="login100-form-btn">
                                Submit
                            </button>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>

        <!--===============================================================================================-->
        <script src="<?= base_url('assets/login/js/jquery-3.2.1.min.js'); ?>"></script>
        <script src="<?= base_url('assets/login/js/bootstrap.min.js'); ?>"></script>
        <script src="<?= base_url('assets/login/js/main.js'); ?>"></script>
    </body>
</html>