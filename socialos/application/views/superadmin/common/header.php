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

            <li class="<?php if ($uri_seg == "admin" || $uri_seg == "addadmin" || $uri_seg == "editadmin") echo "active"; ?>">
                <a href="<?= site_url('superadmin/admin'); ?>">
                    <span>Admin (Platform)</span><i class="icon-user"></i>
                </a>
            </li>

            <li title="user management">
                <a href="#"><span>User Management</span> <i class="icon-arrow-down2"></i></a>
                <ul>
                    <li class="<?php if ($uri_seg == "usergroups" or $uri_seg == "edit_group" || $uri_seg == "create_group") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/usergroups'); ?>">
                            <span>USER TYPE</span>
                        </a>
                    </li>
                    <li class="<?php if ($uri_seg == "create_user" or $uri_seg == "edit_user") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/create_user'); ?>">
                            <span>Create User</span>
                        </a>
                    </li>
                    <li class="<?php if ($uri_seg == "managesquad") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/managesquad'); ?>">
                            <span>Squad Manage</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li title="Permission Management">
                <a href="#"><span>Permission</span> <i class="icon-arrow-down2"></i></a>
                <ul>
                    <li class="<?php if ($uri_seg == "permissions" || $uri_seg == "add_permission" || $uri_seg == "update_permission" || $uri_seg == "delete_permission") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/permissions'); ?>">
                            <span>Manage Permission's</span>
                        </a>
                    </li>
                    <li class="<?php if ($uri_seg == "groups" or $uri_seg == "group_permissions") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/groups'); ?>">
                            <span>Groups</span>
                        </a>
                    </li>
                    <li class="<?php if ($uri_seg == "user_list" or $uri_seg == "manage_user" || $uri_seg == "user_permissions") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/user_list'); ?>">
                            <span>Manage User's</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li title="Users">
                <a href="#"><span>Users</span> <i class="icon-users2"></i></a>
                <ul>
                    <?php
                    foreach ($this->users as $user) {
                        if ($this->config->item('admin_group', 'ion_auth') !== $user->name && 'admin' !== $user->name) {
                            ?>
                            <li class="<?php if ($this->uri->segment(3) == $user->name) echo "active"; ?>">
                                <a href="<?= site_url('superadmin/users/' . $user->name); ?>">
                                    <span class="text-first-letter"><?= $user->name; ?></span>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </li>

            <li title="Master Module">
                <a href="#"><span>Master Module</span> <i class="icon-arrow-down2"></i></a>
                <ul>
                    <li class="<?php if ($uri_seg == "squadgroup" || $uri_seg == "addsgroup") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/squadgroup'); ?>">
                            <span>Squad Group</span>
                        </a>
                    </li>

                    <li class="<?php if ($uri_seg == "schedule" || $uri_seg == "addschedule") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/schedule'); ?>">
                            <span>Schedule</span>
                        </a>
                    </li>

                    <li class="<?php if ($uri_seg == "getweeks" || $uri_seg == "addweek" || $uri_seg == "searchweek") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/getweeks'); ?>">
                            <span>Timelines</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li title="Levels">
                <a href="#"><span>Levels</span> <i class="icon-arrow-down2"></i></a>
                <ul>
                    <li class="<?php if ($uri_seg == "levels" || $uri_seg == "createlevel") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/levels'); ?>">
                            <span>Create Levels</span>
                        </a>
                    </li>

                    <li class="<?php if ($uri_seg == "sub_levels" || $uri_seg == "create_sub_level") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/sub_levels'); ?>">
                            <span>Sub Levels</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li title="Session Management">
                <a href="#"><span>Session Management</span> <i class="icon-arrow-down2"></i></a>
                <ul>
                    <li class="<?php if ($uri_seg == "session") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/session'); ?>">
                            <span>Session</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li title="Assessment">
                <a href="#"><span>Assessment</span> <i class="icon-arrow-down2"></i></a>
                <ul>
                    <li class="<?php if ($uri_seg == "sub_assessment" || $uri_seg == "add_sub_assessment") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/sub_assessment'); ?>">
                            <span>Sub Assessment</span>
                        </a>
                    </li>

                    <li class="<?php if ($uri_seg == "category" || $uri_seg == "add_category") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/category'); ?>">
                            <span>Category</span>
                        </a>
                    </li>

                    <li class="<?php if ($uri_seg == "question" || $uri_seg == "add_que") echo "active"; ?>">
                        <a href="<?= site_url('superadmin/question'); ?>">
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