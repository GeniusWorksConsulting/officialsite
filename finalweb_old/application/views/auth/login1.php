<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="icon" href="<?= base_url('assets/images/app_icon.png') ?>" type="image/png" sizes="16x16">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <title><?php echo CV_PROJECT_NAME; ?> | <?php echo $title; ?></title>

        <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url() ?>assets/css/londinium-theme.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url() ?>assets/css/styles.css" rel="stylesheet" type="text/css">
        <link href="<?= base_url() ?>assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    </head>

    <body class="full-width">
        <!-- Login wrapper -->
        <div class="login-wrapper">
            <?php echo form_open('auth/login'); ?>
            <?php if (isset($message)) { ?>
                <div class="callout callout-danger fade in">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <?= $message; ?>
                </div>
            <?php } ?>

            <div class="popup-header">
                <span class="text-semibold"><?php echo CV_PROJECT_NAME; ?></span>
            </div>
            <div class="well">
                <div class="form-group has-feedback">
                    <label>Username</label>
                    <?php echo form_input($identity); ?>
                    <i class="icon-users form-control-feedback"></i>
                </div>

                <div class="form-group has-feedback">
                    <label>Password</label>
                    <?php echo form_input($password); ?>
                    <i class="icon-lock form-control-feedback"></i>
                </div>

                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="row form-actions">
                    <div class="col-xs-6">
                        <button type="submit" class="btn btn-warning"><i class="icon-menu2"></i> Sign in</button>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="checkbox checkbox-success">
                            <label>
                                <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                                Remember me
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div> 
        <!-- /login wrapper -->

        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>

        <script type="text/javascript" src="<?= base_url() ?>assets/js/plugins/interface/moment.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/plugins/interface/jgrowl.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/plugins/interface/datatables.min.js"></script>

        <script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>assets/js/application.js"></script>

    </body>
</html>