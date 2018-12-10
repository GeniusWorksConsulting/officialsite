<div class="left side-menu col-sm-3 col-md-3 col-lg-2" style="background:black;">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li class="text-muted menu-title">Navigation</li>
                
				
				
				<?php if($_SESSION['role'] == 1 || $_SESSION['role']==49){ ?>
				<li class="has_sub">
                    <a href="<?= base_url() ?>admin/dashboard" class="waves-effect <?php if($active_page == "dashboard") { echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> Dashboard </span> </a>
                   
                </li>
				
				<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect opensub <?php if($active_page == "category") { echo "active"; } ?>" data-id="users"><i class="fa fa-user" aria-hidden="true"></i> <span> Category </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled" id="users">
                        <li><a href="<?= base_url() ?>admin/addcategory">Add Category</a></li>
                        <li><a href="<?= base_url() ?>admin/viewcategory">View Category</a></li>
                    </ul>
                </li>
				
				<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect opensub <?php if($active_page == "questions") { echo "active"; } ?>" data-id="questions"><i class="fa fa-user" aria-hidden="true"></i> <span> Questions </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled" id="questions">
                        <li><a href="<?= base_url() ?>admin/addquestions">Add Questions</a></li>
                        <li><a href="<?= base_url() ?>admin/addnewquestions">Add New Questions</a></li>
                        <li><a href="<?= base_url() ?>admin/viewquestions">View Questions</a></li>
                        <li><a href="<?= base_url() ?>admin/viewnewquestions">View New Questions</a></li>
                    </ul>
                </li>
				
				<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect opensub <?php if($active_page == "user") { echo "active"; } ?>" data-id="user"><i class="fa fa-user" aria-hidden="true"></i> <span> Tribe Member </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled" id="user">
                        <li><a href="<?= base_url() ?>admin/adduser">Add Tribe Member</a></li>
                        <li><a href="<?= base_url() ?>admin/viewuser">View Tribe Member</a></li>
                    </ul>
                </li>
				<?php } ?>
				<?php if($_SESSION['role'] == 1){ ?>
				
				<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect opensub <?php if($active_page == "qamember") { echo "active"; } ?>" data-id="qamember"><i class="fa fa-user" aria-hidden="true"></i> <span> QA Member </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled" id="qamember">
                      <!--  <li><a href="<?= base_url() ?>admin/adduser">Add Tribe Member</a></li>-->
                        <li><a href="<?= base_url() ?>admin/viewqamember">View QA Member</a></li>
                    </ul>
                </li>
				<li class="has_sub">
                    <a href="<?= base_url() ?>admin/paused_account" class="waves-effect <?php if($active_page == "paused_account") { echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> Paused Account </span> </a>
                   
                </li>
				<li class="has_sub">
                    <a href="<?= base_url() ?>admin/view_previous" class="waves-effect <?php if($active_page == "viewprevious") { echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> View Previous </span> </a>
                </li>
				
				<?php } ?>
				<?php if($_SESSION['role'] == 2){ ?>
				<li class="has_sub">
                    <a href="<?= base_url() ?>newdashboard" class="waves-effect <?php if($active_page == "dashboard") { echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> Dashboard </span> </a>
                   
                </li>
				<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect opensub <?php if($active_page == "changepassword") { echo "active"; } ?>" data-id="changepassword"><i class="fa fa-user" aria-hidden="true"></i> <span> Tribe Member </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled" id="changepassword">
                        <li><a href="<?= base_url() ?>changepassword">Change Password</a></li>
                        <li><a href="<?= base_url() ?>changepassword">Change Profile</a></li>
                    </ul>
                </li>
				<li class="has_sub">
                    <a href="<?= base_url() ?>pages/view_previous" class="waves-effect <?php if($active_page == "viewprevious") { echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> View Previous </span> </a>
                   
                </li>
				<!--
				<li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect opensub <?php if($active_page == "rating") { echo "active"; } ?>" data-id="rating"><i class="fa fa-user" aria-hidden="true"></i> <span> Rating </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled" id="rating">
                        <li><a href="<?= base_url() ?>addrating">Add Rating</a></li>
                        <li><a href="<?= base_url() ?>viewrating">View Rating</a></li>
                    </ul>
                </li>-->
				<?php } ?>
				<?php if($_SESSION['role'] == 49){ ?>
				<li class="has_sub">
                    <a href="<?= base_url() ?>qamember/view_previous" class="waves-effect <?php if($active_page == "viewprevious") { echo "active"; } ?>"><i class="fa fa-dashboard"></i> <span> View Previous </span> </a>
                   
                </li>
				<?php } ?>
				<!--
				 <li class="has_sub">
					
					 <a href="javascript:void(0);" class="waves-effect opensub <?php if($active_page == "setting") { echo "active"; } ?>" data-id="setting"><i class="fa fa-user" aria-hidden="true"></i> <span> Settings </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled" id="setting">
                        <li><a href="<?= base_url() ?>admin/kuber_coin_rate">Altera Coin Rate</a></li>
                        <li><a href="<?= base_url() ?>admin/available_total_kuber_coin">Available Total Altera Coin</a></li>
						<li><a href="<?= base_url() ?>admin/referral_charges">Referral Charges</a></li>
						<li><a href="<?= base_url() ?>admin/change_password">Change Password</a></li>
						<li><a href="<?= base_url() ?>admin/change_login_link">Change Login Link</a></li>
                    </ul>
                   
                </li>
				
				
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-table" aria-hidden="true"></i> <span> Master </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="product.php">Products</a></li>
                        <li><a href="portlocation.php">Port Locations</a></li>
                        <li><a href="transporter.php">Transporter</a></li>
						<li><a href="vehicle.php">Vehicle</a></li>
						<li><a href="location.php">Location</a></li>
						<li><a href="consignee.php">Consignee</a></li>
						<li><a href="consignor.php">Consignor</a></li>
						<li><a href="vessel.php">Vessel</a></li>
						
						<li><a href="agent.php">Agent</a></li>
						<li><a href="service_code.php">Service Code</a></li>
                    </ul>
                </li>
				
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-pencil-alt"></i>  <span> Consignment </span> <span class="menu-arrow"></span> </a>
                    <ul class="list-unstyled">
                        <li><a href="lrdetail.php">Consignment Note</a></li>
                        <li><a href="invoice.php">Invoice</a></li>
                        
                    </ul>
                </li>
               <li class="has_sub">
                    <a href="revenue.php" class="waves-effect"><i class="ti-pencil-alt"></i>  <span> Revenue Amount </span></a>
                    
                </li>
				
				
                <li class="has_sub">
                    <a href="lrdetail.php" class="waves-effect"><i class="ti-pencil-alt"></i>  <span> LR </span></a>
                    
                </li>
               
				
                <li class="has_sub">
                    <a href="invoice.php" class="waves-effect"><i class="ti-pencil-alt"></i>  <span> Invoice </span></a>
                    
                </li>
              --->
                </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<style>
#sidebar-menu ul ul a,#sidebar-menu li,#sidebar-menu > ul > li > a{
	color:white !important;
}
.navbar-default {
    background-color: black !important;
}
@media (max-width: 767px){
.sidebar-inner {
    /*overflow:scroll !important;*/
}
}
.nav-pills li.active a:hover {
    background-color: black !important;
}
.nav-pills li.active a {
    background-color: black !important;
}
.nav-pills li a:hover {
    background-color: darkgray !important;
}
.nav-pills li a {
    background-color: darkgray !important;
}
body.fixed-left .side-menu.left {
   /* position: absolute !important;*/
   position:unset;
}
#wrapper {
    height: auto !important;
}
</style>