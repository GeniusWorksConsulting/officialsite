<?php
$uri_seg = $this->uri->segment(2);
?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-content">

        <!-- User dropdown -->
        <div class="user-menu dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= base_url('assets/images/user.png'); ?>" alt="user">
                <div class="user-info">
                    <?= $this->logged_in_name; ?> <span>Super Admin</span>
                </div>
            </a>
        </div>
        <!-- /user dropdown -->

        <ul class="navigation">
            <li class="<?php if ($uri_seg == "index" or $uri_seg == "") echo "active"; ?>">
                <a href="<?= site_url('superadmin/index'); ?>">
                    <span>Dashboard</span><i class="icon-home2"></i> 
                </a>
            </li>
            <li class="<?php if ($uri_seg == "admin" || $uri_seg == "addadmin") echo "active"; ?>">
                <a href="<?= site_url('superadmin/sliders'); ?>">
                    <span>Platform</span><i class="icon-user"></i>
                </a>
            </li>
        </ul>

    </div>
</div>
<!-- /sidebar -->