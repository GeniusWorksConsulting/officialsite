<?php
$uri_seg_val = $this->uri->segment(2);
?>

<div class="sidebar">
    <div class="sidebar-content">

        <!-- User dropdown -->
        <div class="user-menu dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">
                <?php if (isset($this->profile)) { ?>
                    <img alt="Profile" class="img-circle" src="<?= base_url('profile/' . $this->profile) ?>" onerror="this.src='<?= base_url('assets/images/user.png') ?>';">
                <?php } else { ?>
                    <img src="<?= base_url('assets/images/user.png'); ?>" alt="">
                <?php } ?>
                <div class="user-info">
                    <?= $this->logged_in_name; ?> <span><?= $this->groups; ?></span>
                </div>
            </a>
        </div>
        <!-- /user dropdown -->

        <?php if ($this->ion_auth->is_admin()) { ?>
            <!-- Main navigation -->
            <ul class="navigation">
                <li title="Dashboard" class="<?php if ($uri_seg_val == "" or $uri_seg_val == "index") echo "active"; ?>">
                    <a href="<?= site_url('admin/index') ?>"><span>Dashboard</span><i class="icon-home2"></i> </a>
                </li>

                <li title="QA Dashboard" class="<?php if ($uri_seg_val == "qadashboard" or $uri_seg_val == "viewQAassess") echo "active"; ?>">
                    <a href="<?= site_url('admin/qadashboard') ?>"><span>QA Dashboard</span><i class="icon-user4"></i> </a>
                </li>

                <li title="Category">
                    <a href="#"><span>Category</span> <i class="icon-list"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewcategory") echo "active"; ?>">
                            <a href="<?= site_url('admin/viewcategory') ?>">View Category</a>
                        </li>
                        <li class="<?php if ($uri_seg_val == "addcategory" or $uri_seg_val == "savecategory") echo "active"; ?>">
                            <a href="<?= site_url('admin/addcategory') ?>">Add Category</a>
                        </li>
                    </ul>
                </li>

                <li title="Questions">
                    <a href="#"><span>Questions</span> <i class="icon-question3"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewquestion") echo "active"; ?>">
                            <a href="<?= site_url('admin/viewquestion') ?>">View Questions</a>
                        </li>
                        <li class="<?php if ($uri_seg_val == "addquestion" or $uri_seg_val == "saveQuestion") echo "active"; ?>">
                            <a href="<?= site_url('admin/addquestion') ?>">Add Question</a>
                        </li>
                    </ul>
                </li>

                <li title="QA Member">
                    <a href="#"><span>QA Member</span> <i class="icon-user"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewQA") echo "active"; ?>">
                            <a href="<?= site_url('admin/viewQA') ?>">Manage</a>
                        </li>
                        <li class="<?php if ($uri_seg_val == "addQA" or $uri_seg_val == "editQA") echo "active"; ?>">
                            <a href="<?= site_url('admin/addQA') ?>">Add QA Member</a>
                        </li>
                    </ul>
                </li>

                <li title="Squad Member">
                    <a href="#"><span>Squad Member</span> <i class="icon-users2"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewSquad") echo "active"; ?>">
                            <a href="<?= site_url('admin/viewSquad') ?>">Manage Squad</a>
                        </li>
                        <li class="<?php if ($uri_seg_val == "addSquad" or $uri_seg_val == "editSquad") echo "active"; ?>">
                            <a href="<?= site_url('admin/addSquad') ?>">Add Squad</a>
                        </li>
                        <li class="<?php if ($uri_seg_val == "squadnumber" or $uri_seg_val == "saveSquadnumber") echo "active"; ?>">
                            <a href="<?= site_url('admin/squadnumber') ?>">Squad Number</a>
                        </li>
                    </ul>
                </li>

                <li title="Lead Member">
                    <a href="#"><span>Lead</span> <i class="icon-leaf"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewLead") echo "active"; ?>">
                            <a href="<?= site_url('admin/viewLead') ?>">Manage Lead</a>
                        </li>
                        <li class="<?php if ($uri_seg_val == "addLead" or $uri_seg_val == "editLead") echo "active"; ?>">
                            <a href="<?= site_url('admin/addLead') ?>">New Lead</a>
                        </li>
                    </ul>
                </li>

                <li title="Scheduler">
                    <a href="#"><span>Scheduler</span> <i class="icon-calendar"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewScheduler") echo "active"; ?>">
                            <a href="<?= site_url('admin/viewScheduler'); ?>">Manage Schedulers</a>
                        </li>
                        <li class="<?php if ($uri_seg_val == "addScheduler" or $uri_seg_val == "editScheduler") echo "active"; ?>">
                            <a href="<?= site_url('admin/addScheduler') ?>">New Scheduler</a>
                        </li>
                    </ul>
                </li>

                <li title="View Previous" class="<?php if ($uri_seg_val == "viewPrevious" or $uri_seg_val == "viewAssessment") echo "active"; ?>">
                    <a href="<?= site_url('admin/viewPrevious') ?>"><span>View Previous</span><i class="icon-previous"></i> </a>
                </li>
            </ul>
            <!-- /main navigation -->
        <?php } ?>

        <?php if ($this->ion_auth->in_group(array('qamember', 'lead'))) { ?>
            <!-- Main navigation -->
            <ul class="navigation">
                <li title="Dasbboard" class="<?php if ($uri_seg_val == "" or $uri_seg_val == "index" or $uri_seg_val == "user_details") echo "active"; ?>">
                    <a href="<?= site_url('users/index') ?>"><span>Dashboard</span><i class="icon-home2"></i> </a>
                </li>

                <li title="Category">
                    <a href="#"><span>Category</span> <i class="icon-list"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewcategory") echo "active"; ?>">
                            <a href="<?= site_url('users/viewcategory') ?>">View Category</a>
                        </li>
                        <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
                            <li class="<?php if ($uri_seg_val == "addcategory" or $uri_seg_val == "savecategory") echo "active"; ?>">
                                <a href="<?= site_url('users/addcategory') ?>">Add Category</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li title="Questions">
                    <a href="#"><span>Questions</span> <i class="icon-question3"></i></a>
                    <ul>
                        <li class="<?php if ($uri_seg_val == "viewquestion") echo "active"; ?>">
                            <a href="<?= site_url('users/viewquestion') ?>">View Questions</a>
                        </li>
                        <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
                            <li class="<?php if ($uri_seg_val == "addquestion" or $uri_seg_val == "saveQuestion") echo "active"; ?>">
                                <a href="<?= site_url('users/addquestion') ?>">Add Question</a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
                    <li title="Squad Member">
                        <a href="#"><span>Squad Member</span> <i class="icon-users2"></i></a>
                        <ul>
                            <li class="<?php if ($uri_seg_val == "viewSquad" or $uri_seg_val == "deactivate") echo "active"; ?>">
                                <a href="<?= site_url('users/viewSquad') ?>">Manage Squad</a>
                            </li>
                            <li class="<?php if ($uri_seg_val == "addSquad" or $uri_seg_val == "editSquad") echo "active"; ?>">
                                <a href="<?= site_url('users/addSquad') ?>">Add Squad</a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <li title="View Previous" class="<?php if ($uri_seg_val == "viewPrevious" or $uri_seg_val == "viewAssessment") echo "active"; ?>">
                    <a href="<?= site_url('users/viewPrevious') ?>"><span>Previous Assessment</span><i class="icon-previous"></i> </a>
                </li>

                <li title="Reports" class="<?php if ($uri_seg_val == "reports") echo "active"; ?>">
                    <a href="<?= site_url('users/reports') ?>"><span>Reports</span><i class="icon-calendar"></i> </a>
                </li>
            </ul>
            <!-- /main navigation -->
        <?php } ?>

        <?php if ($this->ion_auth->in_group('members')) { ?>
            <!-- Main navigation -->
            <ul class="navigation">
                <li title="Dasbboard" class="<?php if ($uri_seg_val == "" or $uri_seg_val == "index" or $uri_seg_val == "addrating") echo "active"; ?>">
                    <a href="<?= site_url('squad/index') ?>"><span>Dashboard</span><i class="icon-home2"></i> </a>
                </li>

                <li title="Perception Graph" class="<?php if ($uri_seg_val == "perception_graph") echo "active"; ?>">
                    <a href="<?= site_url('squad/perception_graph') ?>"><span>Perception Graph</span><i class="icon-pie3"></i> </a>
                </li>

                <li title="Profile Page" class="<?php if ($uri_seg_val == "profile") echo "active"; ?>">
                    <a href="<?= site_url('squad/profile') ?>"><span>Change Profile</span><i class="icon-profile"></i> </a>
                </li>
                <li title="View Previous" class="<?php if ($uri_seg_val == "viewPrevious" or $uri_seg_val == "viewAssessment") echo "active"; ?>">
                    <a href="<?= site_url('squad/viewPrevious') ?>"><span>View Previous</span><i class="icon-previous"></i> </a>
                </li>
                <li title="View Previous" class="<?php if ($uri_seg_val == "sessions") echo "active"; ?>">
                    <a href="<?= site_url('squad/sessions') ?>"><span>Squad Sessions</span><i class="icon-menu"></i> </a>
                </li>
            </ul>
            <!-- /main navigation -->
        <?php } ?>

        <?php if ($this->ion_auth->in_group('scheduler')) { ?>
            <!-- Main navigation -->
            <ul class="navigation">
                <li title="Dasbboard" class="<?php if ($uri_seg_val == "" or $uri_seg_val == "index" or $uri_seg_val == "addSchedule") echo "active"; ?>">
                    <a href="<?= site_url('scheduler/index') ?>"><span>Dashboard</span><i class="icon-home2"></i> </a>
                </li>

                <li title="Comment" class="<?php if ($uri_seg_val == "comment") echo "active"; ?>">
                    <a href="<?= site_url('scheduler/comment') ?>"><span>Reflection Comment</span><i class="icon-envelop"></i> </a>
                </li>
            </ul>
            <!-- /main navigation -->
        <?php } ?>

    </div>
</div>