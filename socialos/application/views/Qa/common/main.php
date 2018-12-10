<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="icon" href="<?= base_url('assets/images/user.png') ?>" type="image/png" sizes="16x16">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <title><?= CV_PROJECT_NAME; ?></title>

        <link href="<?= base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/londinium-theme.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/styles.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/dashboard.css'); ?>" rel="stylesheet" type="text/css">

        <link href="<?= base_url('assets/css/daterangepicker.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-ui.min.js'); ?>"></script>
    </head>

    <body class="sidebar-narrow">
        <!--<body>-->
        <input type="hidden" id="siteurl" value="<?= site_url(); ?>">

        <div id="dv_loader" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;">
            <p style="position: absolute; color: White; top: 40%; left: 45%;" class="loader"></p>
        </div>

        <!-- Navbar -->
        <div class="navbar navbar-inverse" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"></a>
                <a class="sidebar-toggle"><i class="icon-paragraph-justify2"></i></a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons">
                    <span class="sr-only">Toggle navbar</span>
                    <i class="icon-grid3"></i>
                </button>
                <button type="button" class="navbar-toggle offcanvas">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="icon-paragraph-justify2"></i>
                </button>
            </div>

            <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
                <li>
                    <li><a href="<?= site_url($this->group_name . '/setting') ?>"><i class="icon-settings"></i></a></li>
                </li>
                <li><a href="<?php echo site_url('auth/logout'); ?>"><i class="icon-exit"></i> Logout</a></li>
            </ul>
        </div>
        <!-- /navbar -->

        <!-- Page container -->
        <div class="page-container">
            <?php $this->load->view($this->group_name . '/common/sidebar'); ?>

            <!-- Page content -->
            <div class="page-content">

                <?php
                if (isset($content) && $content) {
                    $this->load->view($content);
                }
                ?>

                <!-- Footer -->
                <div class="footer clearfix">
                    <div class="pull-left">COPYRIGHT © ALL RIGHTS RESERVED. </a></div>
                </div>
                <!-- /footer -->

            </div>
            <!-- /page content -->
        </div>

        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/select2.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/inputmask.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/autosize.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/inputlimit.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/listbox.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/multiselect.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/validate.min.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/fancybox.min.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/moment.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/bootstrap-datetimepicker.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/jgrowl.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/daterangepicker.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/application.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/backend.js'); ?>"></script>
        <!-- /page container -->

    </body>
</html>