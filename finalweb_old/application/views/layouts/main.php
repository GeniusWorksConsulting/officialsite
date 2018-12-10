<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="icon" href="<?= base_url('assets/images/user.png') ?>" type="image/png" sizes="16x16">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
        <title><?php echo CV_PROJECT_NAME; ?> | <?php echo $title; ?></title>

        <meta name="description" content="Stepup Technosys is Web Design, Website Development, Mobile App Development Company at Surat, Gujarat, India.">
        <meta name="author" content="Stepup Technosys">
        <meta name="keywords" itemprop="keywords" content="Web Design Company In Surat, Website Development Company In Surat, Stepup Technosys, Mobile App Development Company Surat, Software Service Provider Company, Web Designer Company, Web Development Company." >

        <link href="<?= base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/londinium-theme.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/styles.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/icons.css'); ?>" rel="stylesheet" type="text/css">

        <link href="<?= base_url('assets/css/daterangepicker.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?= base_url('assets/css/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery-ui.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery.bootstrap.newsbox.min.js'); ?>"></script>
        <style type="text/css">
            .loader {
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid blue;
                border-right: 16px solid green;
                border-bottom: 16px solid red;
                width: 80px;
                height: 80px;
                -webkit-animation: spin 2s linear infinite;
                animation: spin 2s linear infinite;
            }

            @-webkit-keyframes spin 
            {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin 
            {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
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
                <li class="user dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <?php if (isset($this->profile)) { ?>
                            <img alt="Profile" class="img-circle" src="<?= base_url('profile/' . $this->profile) ?>" onerror="this.src='<?= base_url('assets/images/user.png') ?>';">
                        <?php } else { ?>
                            <img src="<?= base_url('assets/images/user.png'); ?>" alt="">
                        <?php } ?>
                        <span><?= $this->logged_in_name; ?></span>
                        <i class="caret"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right icons-right" style="display: none;">
                        <?php if ($this->ion_auth->is_admin()) { ?>
                            <li><a href="<?= site_url('admin/change_password') ?>"><i class="icon-lock"></i> Change Password</a></li>
                        <?php } else if ($this->ion_auth->in_group(array('qamember', 'lead'))) { ?>
                            <li><a href="<?= site_url('users/change_password') ?>"><i class="icon-lock"></i> Change Password</a></li>
                        <?php } ?>
                        <li><a href="<?php echo site_url('auth/logout'); ?>"><i class="icon-exit"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /navbar -->

        <div class="navbar navbar-inverse hidden" role="navigation" style="position: relative; z-index: 99;">
            <div class="navbar-header">
                <a class="sidebar-toggle hidden"><i class="icon-paragraph-justify2"></i></a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu-left">
                    <span class="sr-only">Toggle navbar</span>
                    <i class="icon-grid3"></i>
                </button>

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu-right">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="icon-paragraph-justify2"></i>
                </button>
            </div>

            <ul class="nav navbar-nav collapse" id="navbar-menu-left">
                <li class="active"><a><i class="icon-home2"></i></a></li>
                <li class="active"><a><i class="icon-meter"></i></a></li>
                <li class="active"><a><i class="icon-headphones"></i></a></li>
                <li class="active"><a><i class="icon-calendar"></i></a></li>
                <li class="active"><a><i class="icon-heart3"></i></a></li>
                <li class="active"><a><i class="icon-trophy-star"></i></a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right collapse" id="navbar-menu-right">
                <li title="Settings"><a href="#"><i class="icon-sun2"></i></a></li>
                <li title="Logout"><a href="#"><i class="icon-exit"></i></a></li>
            </ul>
        </div>

        <div class="navbar navbar-default hidden" role="navigation" style="position: relative; z-index: 99;">
            <div class="navbar-header">
                <a class="navbar-brand" href="#"><?php echo CV_PROJECT_NAME; ?></a>
                <a class="sidebar-toggle hidden"><i class="icon-paragraph-justify2"></i></a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu-left">
                    <span class="sr-only">Toggle navbar</span>
                    <i class="icon-grid3"></i>
                </button>

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu-right">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="icon-paragraph-justify2"></i>
                </button>
            </div>

            <ul class="nav navbar-nav collapse" id="navbar-menu-left">
                <li class="active"><a href="#">Dashboard</a></li>
                <li class=""><a href="#">Assessments</a></li>
                <li class=""><a href="#">Learning and Reflection</a></li>
                <li class=""><a href="#">Squad Session</a></li>
                <li class=""><a href="#">My To Do’s</a></li>
                <li class=""><a href="#">Achievements</a></li>
                <!--<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                    <ul class="dropdown-menu icons-right dropdown-menu-right">
                        <li><a href="#"><i class="icon-cogs"></i> This is</a></li>
                        <li><a href="#"><i class="icon-grid3"></i> Dropdown</a></li>
                        <li><a href="#"><i class="icon-spinner7"></i> With right</a></li>
                        <li><a href="#"><i class="icon-link"></i> Aligned icons</a></li>
                    </ul>
                </li>-->
            </ul>

            <ul class="nav navbar-nav navbar-right collapse" id="navbar-menu-right">
                <li><a href="#"><i class="icon-pause pull-right"></i> Pause Account</a></li>
                <!--<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-paragraph-justify2"></i> Dropdown <b class="caret"></b></a>
                    <ul class="dropdown-menu icons-right dropdown-menu-right">
                        <li><a href="#"><i class="icon-cogs"></i> This is</a></li>
                        <li><a href="#"><i class="icon-grid3"></i> Dropdown</a></li>
                        <li><a href="#"><i class="icon-spinner7"></i> With right</a></li>
                        <li><a href="#"><i class="icon-link"></i> Aligned icons</a></li>
                    </ul>
                </li>-->
            </ul>
        </div>

        <!-- Page container -->
        <div class="page-container">
            <!-- Sidebar -->
            <?php $this->load->view('layouts/header'); ?>

            <!-- Page content -->
            <div class="page-content">

                <?php
                if (isset($body) && $body) {
                    $this->load->view($body);
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

        <script type="text/javascript" src="<?= base_url('assets/js/jquery.tabledit.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/select2.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/inputmask.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/autosize.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/inputlimit.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/listbox.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/multiselect.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/forms/validate.min.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/fancybox.min.js'); ?>"></script>
        <!--<script type="text/javascript" src="<?= base_url() ?>assets/js/plugins/interface/.js"></script>-->
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/moment.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/bootstrap-datetimepicker.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/dataTables.bootstrap4.min.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/jgrowl.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/plugins/interface/daterangepicker.js'); ?>"></script>

        <script type="text/javascript" src="<?= base_url('assets/js/application.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/bootstrap.min.js'); ?>"></script>
        <script type="text/javascript" src="<?= base_url('assets/js/backend.js'); ?>"></script>
        <!-- /page container -->

        <script type="text/javascript">
        </script>

    </body>
</html>