<!-- Header Start -->

<html>
    

<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?= $header_title ?></title>
		<link rel="icon" href="<?= base_url() ?>public/assets/images/favicon.ico" type="image/gif" sizes="16x16">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  
		
		<link href="<?= base_url() ?>/public/assets/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>public/assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
		<link href="<?= base_url() ?>/public/assets/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" />
     
	  <!--Bootstrap Table-->
	 <link href="<?= base_url() ?>/public/assets/plugins/bootstrap-table/css/bootstrap-table.min.css" rel="stylesheet" type="text/css" />
	 
	 <!--Form Wizard-->
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/public/assets/plugins/jquery.steps/css/jquery.steps.css" />

        

        <link href="<?= base_url() ?>public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>public/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>public/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>public/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>public/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>public/assets/css/responsive.css" rel="stylesheet" type="text/css" />
		
		<!--Morris Chart CSS -->
		<link rel="stylesheet" href="<?= base_url() ?>/public/assets/plugins/morris/morris.css">
		<link rel="stylesheet" href="<?= base_url() ?>/public/assets/plugins/magnific-popup/css/magnific-popup.css" />
        <link rel="stylesheet" href="<?= base_url() ?>/public/assets/plugins/jquery-datatables-editable/datatables.css" />
		
		 <!-- Sweet Alert -->
        <link href="<?= base_url() ?>/public/assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
		
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>	
 

    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="<?= base_url() ?>admin/dashboard" class="logo"><span style="text-transform: none;font-size: 15px;"></span></a>
                        <!-- Image Logo here -->
                        <!--<a href="index.html" class="logo">-->
                            <!--<i class="icon-c-logo"> <img src="/public/assets/images/logo_sm.png" height="42"/> </i>-->
                            <!--<span><img src="public/assets/images/logo_light.png" height="20"/></span>-->
                        <!--</a>-->
                    </div>
                </div>

                <!-- Button mobile view to collapse sidebar menu -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">
                        <div class="">
                            <div class="pull-left">
                                <button class="button-menu-mobile open-left waves-effect waves-light">
                                    <i class="md md-menu"></i>
                                </button>
                                <span class="clearfix"></span>
                            </div>

                            

                          


                            <ul class="nav navbar-nav navbar-right pull-right">
                               
                               
                               
                                <li class="dropdown top-menu-item-xs">
                                    <a href="#" class="dropdown-toggle profile waves-effect waves-light" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-user" aria-hidden="true" style="font-size: 50px;margin-top: 5px;"></i>
										<span style="font-size: 20px;"><?= $this->session->user_name?></span>
									</a>
                                    <ul class="dropdown-menu">
                                       
                                        
                                        <li><a href="<?= base_url()?>admin/logout"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </div>
            </div>