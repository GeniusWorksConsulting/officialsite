<!-- Header Start -->
<html>
<head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

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
	
	<!--Morris Chart CSS -->
	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/plugins/morris/morris.css">
	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/plugins/magnific-popup/css/magnific-popup.css" />
	<link rel="stylesheet" href="<?= base_url() ?>/public/assets/plugins/jquery-datatables-editable/datatables.css" />

	<!-- Sweet Alert -->
	<link href="<?= base_url() ?>/public/assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">


	<link href="<?= base_url() ?>public/assets/css/pages.css" rel="stylesheet" type="text/css" />
	<link href="<?= base_url() ?>public/assets/css/responsive.css" rel="stylesheet" type="text/css" />
	
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>	


</head>
<body class="fixed-left">
	<!-- Begin page -->
	<div id="wrapper">

		<!-- Top Bar Start -->
		<header class="navbar navbar-inverse">
			<div class="container-fluid"> 
				<div class="navbar-header">
					<button class="button-menu-mobile">
						<i class="md md-menu"></i>
					</button>					
					<a href="<?= base_url() ?>admin/dashboard" class="navbar-brand"><span></span></a>
				</div>

				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
								<i class="fa fa-user"></i>
								<span><?= $this->session->user_name ?></span>
								<i class="caret"></i>
						</a>
						<ul class="dropdown-menu">
								<li><a href="<?= base_url() ?>admin/logout"><i class="ti-power-off m-r-10 text-danger"></i> Logout</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</header>