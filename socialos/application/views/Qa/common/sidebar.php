<?php
$uri_seg = $this->uri->segment(2);
?>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-content">

        <!-- User dropdown -->
        <div class="user-menu dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= base_url('profile/' . $this->profile); ?>" alt="user" onerror="this.src='<?= base_url('assets/images/user.png') ?>';">
                <div class="user-info">
                    <?= $this->logged_in_name; ?> <span>Qa</span>
                </div>
            </a>
        </div>
        <!-- /user dropdown -->

        <ul class="navigation">
            <li class="<?php if ($uri_seg == "index") echo "active"; ?>">
                <a href="<?= site_url('Qa/index'); ?>">
                    <span>Dashboard</span><i class="icon-home2"></i>
                </a>
            </li>
            
            <li title="Assessment">
                <a href="#"><span>Assessment</span> <i class="icon-arrow-down2"></i></a>
                <ul>
                    <li class="<?php if ($uri_seg == "category" || $uri_seg == "add_category") echo "active"; ?>">
                        <a href="<?= site_url($this->group_name . '/category'); ?>">
                            <span>Category</span>
                        </a>
                    </li>

                    <li class="<?php if ($uri_seg == "question" || $uri_seg == "add_que") echo "active"; ?>">
                        <a href="<?= site_url($this->group_name . '/question'); ?>">
                            <span>Questions</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!--<li class="<?php if ($uri_seg == "squads" || $uri_seg == "addsquad" || $uri_seg == "editsquad" || $uri_seg == "searchsquad") echo "active"; ?>">
                <a href="<?= site_url('superadmin/squads'); ?>">
                    <span>Squad Users</span><i class="icon-tree3"></i>
                </a>
            </li>
            <li class="<?php if ($uri_seg == "lead" || $uri_seg == "addlead" || $uri_seg == "editlead" || $uri_seg == "searchlead") echo "active"; ?>">
                <a href="<?= site_url('superadmin/lead'); ?>">
                    <span>Lead</span><i class="icon-users2"></i>
                </a>
            </li>-->
        </ul>

    </div>
</div>
<!-- /sidebar -->