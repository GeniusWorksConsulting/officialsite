<div class="left side-menu" style="display:none;">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li class="text-muted menu-title">Navigation</li>
                <li class="has_sub sanjay">
                    <a href="newdashboard" class="waves-effect <?php
                    if ($active_page == "dashboard") {
                        echo "active";
                    }
                    ?>"><i class="ti-home"></i> <span> Dashboard </span> </a>

                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect opensub <?php
                    if ($active_page == "profile") {
                        echo "active";
                    }
                    ?>" data-id="profile">
                        <i class="fa fa-user" aria-hidden="true"></i> 
                        <span> Profile </span> 
                        <span class="menu-arrow"></span> 
                    </a>
                    <ul class="list-unstyled" id="profile">
                        <li><a href="<?= base_url() ?>changepassword">Change Password</a></li>
                        <li><a href="<?= base_url() ?>changeprofile">Change Profile</a></li>
                    </ul>

                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ti-home"></i> <span> Signup </span> </a>

                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ti-home"></i> <span> Self Wallet </span> </a>

                </li>

                <li class="has_sub">
                    <a href="<?= base_url() . "kmwallet" ?>" class="waves-effect"><i class="ti-home"></i> <span> KM Wallet </span> </a>

                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ti-home"></i> <span> My Network </span> </a>

                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ti-home"></i> <span> Incentive </span> </a>

                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ti-home"></i> <span> Mobile Topup </span> </a>

                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ti-home"></i> <span> Income Statement </span> </a>

                </li>

                <li class="has_sub">
                    <a href="#" class="waves-effect"><i class="ti-home"></i> <span> Support </span> </a>
                </li>

                <li class="has_sub">
                    <a href="<?= base_url() . "sendcoin" ?>" class="waves-effect"><i class="ti-home"></i> <span> Send Coin </span> </a>
                </li>



                <!--
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