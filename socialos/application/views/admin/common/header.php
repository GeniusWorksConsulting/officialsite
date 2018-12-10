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
                    <?= $this->logged_in_name; ?> <span>Admin</span>
                </div>
            </a>
        </div>
        <!-- /user dropdown -->

        <ul class="navigation">
            <li class="<?php if ($uri_seg == "index" or $uri_seg == "") echo "active"; ?>">
                <a href="<?= site_url('admin/index'); ?>">
                    <span>Dashboard</span><i class="icon-home2"></i> 
                </a>
            </li>

            <li title="Users">
                <a href="#"><span>Users</span> <i class="icon-users2"></i></a>
                <ul>
                    <?php
                    //var_dump($this->user_acl);
                    foreach ($this->user_acl as $acl) {
                        if ($acl['value']) {
                            ?>
                            <li class="<?php if ($this->uri->segment(3) == $acl['key']) echo "active"; ?>">
                                <a href="<?= site_url('admin/users/' . $acl['key']); ?>">
                                    <span class="text-first-letter"><?= $acl['key']; ?></span>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
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
                        <a href="<?= site_url($this->group_name . 'admin/question'); ?>">
                            <span>Questions</span>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>

    </div>
</div>
<!-- /sidebar -->