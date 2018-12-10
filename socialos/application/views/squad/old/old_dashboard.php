<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3><?= $this->logged_in_name; ?> Dashboard 
    </div>
</div>
<!-- /page header -->

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
<?php } ?>

<div class="row">
    <?php if (isset($isPaused)) { ?>
        <div class="col-md-2">
            <?php
            echo form_open('squad/unpauseAccount');
            ?>
            <button type="submit" name="btnunPause" class="btn btn-success btn-xs">UnPause Account</button>
            <?php echo form_close(); ?>
        </div>
    <?php } ?>

    <div class="col-md-10">
        <?php if (isset($isPaused)) { ?>
            <div class="callout callout-danger fade in">
                <h5>Your account has been paused.</h5>
            </div>
        <?php } ?>
    </div>
</div>

<div <?php
if (isset($isPaused)) {
    echo 'style="pointer-events: none;"';
}
?>>
    <div class="row">
        <!--col-md-9-->
        <div class="col-md-9">
            <!--Panel-->
            <div class="panel panel-default">
                <!--Panel Body-->
                <div class="panel-body">

                    <!--Row 1-->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 style="text-decoration: underline" class="text-semibold"><?= $squad_group; ?></h6>
                            <p>You Have Completed <span id="userCompleted" class="text-semibold">0</span> out of <span class="text-semibold"><?= sizeof($members); ?></span> Assessments for This Week</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php
                            echo form_open('squad/pauseAccount');
                            ?>

                            <?php if (!isset($isPaused)) { ?>
                                <button type="submit" name="btnPause" onclick="return confirm('Are you sure you want to pause account?');" class="btn btn-info btn-xs">Pause Account</button>
                            <?php } ?>
                            <!--<button type="button" class="btn btn-default btn-xs" data-toggle="modal" role="button" href="#DiscussionQueue">Queue for Discussion</button>-->
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                    <!--Row 1 END-->

                    <!--Row 2-->
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="info-blocks">

                                <?php
                                foreach ($members as $m1) {
                                    $noCount = 0;
                                    $selfCount = get_countassessment_helper(array('sender_id' => $m1->id, 'receiver_id' => $m1->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                    ?>
                                    <li class="bg-default sminfo">
                                        <div class="top-info">
                                            <span class="limitCharacter" href="#"><?= $m1->first_name . ' ' . $m1->last_name; ?></span>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed <?= ($m1->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m1->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">
                                        <a id="btnSelf<?= $m1->id; ?>" class="bottom-info <?= ($selfCount == sizeof($type)) ? 'bg-success' : 'bg-white'; ?> margin-top">SELF</a>
                                        <div class="progress block-inner">
                                            <div id="percentage<?= $m1->id; ?>" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                <span>PEER</span>
                                            </div>
                                        </div>
                                        <span class="text-semibold text-danger"><?= ($m1->is_paused == 1) ? 'Leave' : '&nbsp;'; ?></span>
                                    </li>

                                    <?php
                                    $disableAccount = 0;
                                    foreach ($members as $user1) {
                                        $isDone2 = 0;
                                        if ($user1->is_paused == 0) {
                                            foreach ($type as $t2) {
                                                ?>
                                                <?php
                                                $is_rating2 = get_israting_helper(array('w.sender_id' => $m1->id, 't.type_id' => $t2->type_id, 'w.receiver_id' => $user1->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));

                                                if ($is_rating2) {
                                                    $isDone2++;
                                                }
                                            }

                                            if ($isDone2 == sizeof($type)) {
                                                $noCount++;
                                                if ($m1->id == $user1->id) {
                                                    $noCount--;
                                                }
                                            }
                                        } else {
                                            $disableAccount++;
                                        }
                                    }

                                    $division = sizeof($members) - ($disableAccount + 1);
                                    if ($division > 0) {
                                        $percentage = ($noCount * 100) / $division;
                                    } else {
                                        $percentage = 0;
                                    }
                                    ?>
                                    <script type="text/javascript">
                                        $('#percentage<?= $m1->id; ?>').css({width: <?= $percentage; ?> + '%'});
                                    </script>
                                    <?php
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                    <!--Row 2 END-->

                    <!--Row 3-->
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="info-blocks">

                                <?php
                                $completedAss = 0;
                                $selfCompleted = 0;
                                foreach ($members as $m) {
                                    $count = get_countassessment_helper(array('sender_id' => $m->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                    if ($count == sizeof($members) * sizeof($type)) {
                                        $completedAss++;
                                    }
                                    ?>
                                    <li class="bg-default" <?= ($m->is_paused == 1) ? 'style="pointer-events: none;"' : ''; ?>>
                                        <div class="top-info">
                                            <span class="limitCharacter" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                            <p class="text-success text-semibold" id="<?= $m->id; ?>">00.00 %</p>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed margin-bottom <?= ($m->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">
                                        <?php
                                        $isDone = 0;
                                        $avg = 0;
                                        foreach ($type as $t) {
                                            ?>
                                            <?php
                                            $is_rating = get_israting_helper(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
                                            ?>
                                            <a id="<?= $t->type_id . $m->id ?>" onclick="assessment(<?= $m->id; ?>, <?= $t->type_id; ?>);" class="bottom-info <?= ($is_rating) ? 'bg-success' : 'bg-primary' ?>"><?= ($is_rating) ? $is_rating->rating : $t->type; ?></a>
                                            <?php if ($is_rating) { ?>
                                                <script type="text/javascript">
                                                    $('#<?= $t->type_id . $m->id; ?>').removeAttr('onclick');
                                                </script>
                                                <?php
                                                $isDone++;
                                                $avg = $avg + $is_rating->rating;
                                            }
                                        }

                                        if ($isDone == sizeof($type)) {
                                            $selfCompleted++;
                                            ?>
                                            <script type="text/javascript">
                                                $('#<?= $m->id; ?>').html('<?= number_format((float) $avg / 2, 2, '.', ''); ?> %');
                                            </script>
                                        <?php }
                                        ?>
                                    </li>
                                    <?php
                                }
                                ?>
                                <script type="text/javascript">
                                    $('#userCompleted').html('<?= $selfCompleted; ?>');
                                </script>

                            </ul>
                        </div>
                    </div>
                    <!--Row 3 END-->

                    <!--Row 4-->
                    <div class="row">
                        <div class="col-md-12">
                            <h6 style="text-decoration: underline" class="text-semibold">YOU HAVE BEEN ASSESSED BY:</h6>
                            <ul class="info-blocks">

                                <?php
                                foreach ($members as $m) {
                                    $checkPaused = get_ispaused_helper(array('user_id' => $m->id, 'status' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                    ?>
                                    <li class="bg-default">
                                        <div class="top-info">
                                            <span class="limitCharacter" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed margin-bottom <?= ($m->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">

                                        <?php
                                        foreach ($type as $t) {
                                            ?>
                                            <?php
                                            $is_given = get_israting_helper(array('w.sender_id' => $m->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $this->logged_in_id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
                                            ?>
                                            <a class="bottom-info <?= ($is_given) ? 'bg-success' : 'bg-primary' ?>"><?= $t->type; ?></a>
                                            <?php
                                        }
                                        ?>
                                    </li>
                                    <?php
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                    <!--Row 4 END-->

                </div>
                <!--Panel Body-->
            </div>
            <!--Panel-->
        </div>
        <!--col-md-8 End-->

        <!--col-md-3-->
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <!-- Task -->
                    <div class="block task task-normal">
                        <div class="row with-padding">
                            <div class="col-sm-8">
                                <div class="task-description">
                                    <a  class="text-uppercase">Squad's Customer Love</a>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="task-info">
                                    <span><span class="label label-success"><?= number_format((float) $squadGoal, 2, '.', ''); ?>%</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="pull-left">
                                <span><i class="icon-users"></i> CUSTOMER LOVE</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /task -->

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-success text-uppercase">my goal for <?= date('F'); ?></h6>
                    <!-- Task -->
                    <div class="block task task-normal">
                        <div class="row with-padding">
                            <div class="col-sm-12">
                                <button class="btn btn-success btn-lg btn-block">
                                    <?= isset($month_goal) ? $month_goal->monthgoal : '00'; ?>%
                                </button>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="">
                                <button id="btnMonth" class="btn btn-default btn-block<?= isset($month_goal) ? ' disabled' : ''; ?>"> CLICK HERE</button>
                            </div>
                        </div>
                    </div>
                    <!-- /task -->
                </div>

                <div class="col-md-6">
                    <h6 class="text-info">MY WEEKLY GOAL</h6>

                    <!-- Task -->
                    <div class="block task task-high">
                        <div class="row with-padding">
                            <div class="col-sm-12">
                                <button class="btn btn-info btn-lg btn-block">
                                    <?= isset($week_goal) ? $week_goal->weekgoal : '00'; ?>%
                                </button>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="">
                                <button id="btnWeek" class="btn btn-default btn-block<?= isset($week_goal) ? ' disabled' : ''; ?>"> CLICK HERE</button>
                            </div>
                        </div>
                    </div>
                    <!-- /task -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!-- Task -->
                    <div class="block task task-normal">
                        <div class="row with-padding">
                            <div class="col-sm-8">
                                <div class="task-description">
                                    <a>MY CUSTOMER LOVE</a>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="task-info">
                                    <span><span class="label label-success"><?= number_format((float) $achieving, 2, '.', ''); ?>%</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="pull-left">
                                <span><i class="icon-users"></i> CUSTOMER LOVE</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /task -->

        </div>
        <!--col-md-3 END-->
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="block task task-info">

                <div class="row with-padding">
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a>MY SQUAD </a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h4>FOR THIS WEEK</h4>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <i style="font-size: 30px;" class="icon-user"></i>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5><?= number_format((float) $squadGoal, 2, '.', ''); ?> % Customer Love</h5>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a><?= $completedAss; ?> OUT OF <?= sizeof($members); ?></a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5 class="text-success">HAVE COMPLETED THEIR SQUAD ASSESSMENT</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="block task task-normal">

                <div class="row with-padding">
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a>TOP SQUAD - <?= isset($first) ? $first->squad_name : ''; ?></a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h4>FOR THIS WEEK</h4>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <i style="font-size: 30px;" class="icon-user"></i>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5><?= isset($first) ? $first->rating : '00'; ?> % Customer Love</h5>
                        </div>
                    </div>
                    <?php
                    $topSize = 0;
                    $topCompleted = 0;
                    if (isset($first)) {
                        $topMembers = get_squad_helper($first->squad_group);
                        $topSize = sizeof($topMembers);

                        foreach ($topMembers as $tm) {
                            $count = get_countassessment_helper(array('sender_id' => $tm->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                            if ($count == sizeof($topMembers) * sizeof($type)) {
                                $topCompleted++;
                            }
                        }
                    }
                    ?>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a><?= $topCompleted; ?> OUT OF <?= $topSize; ?></a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5 class="text-success">HAVE COMPLETED THEIR SQUAD ASSESSMENT</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="block task task-high">

                <div class="row with-padding">
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a>BOTTOM SQUAD - <?= isset($last) ? $last->squad_name : ''; ?></a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h4>FOR THIS WEEK</h4>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <i style="font-size: 30px;" class="icon-user"></i>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5><?= isset($last) ? $last->rating : '00'; ?> % Customer Love</h5>
                        </div>
                    </div>
                    <?php
                    $bottomSize = 0;
                    $bottomCompleted = 0;
                    if (isset($last)) {
                        $bottomMembers = get_squad_helper($last->squad_group);
                        $bottomSize = sizeof($bottomMembers);

                        foreach ($bottomMembers as $bm) {
                            $count1 = get_countassessment_helper(array('sender_id' => $bm->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                            if ($count1 == sizeof($bottomMembers) * sizeof($type)) {
                                $bottomCompleted++;
                            }
                        }
                    }
                    ?>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a><?= $bottomCompleted; ?> OUT OF <?= $bottomSize; ?></a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5 class="text-success">HAVE COMPLETED THEIR SQUAD ASSESSMENT</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="block task task-normal">

                <div class="row with-padding">
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a>TOP SQUAD MEMBER</a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h4><?= isset($topMember) ? $topMember->first_name : ''; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <i style="font-size: 30px;" class="icon-user"></i>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5><?= isset($topMember) ? $topMember->rating : '00'; ?> % Customer Love</h5>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5 class="text-success">FOR THIS WEEK</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-6">
            <div class="block task task-high">

                <div class="row with-padding">
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <a>BOTTOM SQUAD MEMBER</a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h4><?= isset($bottomMember) ? $bottomMember->first_name : ''; ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <i style="font-size: 30px;" class="icon-user"></i>
                    </div>
                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5><?= isset($bottomMember) ? $bottomMember->rating : '00'; ?> % Customer Love</h5>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="task-description text-center">
                            <h5 class="text-success">FOR THIS WEEK</h5>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Small modal -->
<div id="small_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Assessment</h4>
            </div>

            <div class="modal-body with-padding div_assessment">

            </div>
        </div>
    </div>
</div>
<!-- /small modal -->

<!-- Week Goal -->
<div id="form_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Set Your Weekly Goal</h4>
            </div>

            <!-- Form inside modal -->
            <?php
            $attributes = array('id' => 'weekForm', 'name' => 'weekForm', 'class' => '');
            echo form_open('squad/index', $attributes);
            ?>

            <div class="modal-body with-padding">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" name="weekgoal" placeholder="00" class="form-control allow_number">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="btnWeek" class="btn btn-primary btn-xs">Submit</button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- Week Goal End -->

<!-- Queue for Discussion -->
<div id="DiscussionQueue" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Queue for Discussion</h4>
            </div>

            <!-- Form inside modal -->
            <?php
            echo form_open('squad/index', array('id' => 'discForm', 'name' => 'discForm', 'class' => ''));
            ?>

            <div class="modal-body with-padding">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 col-xs-12">
                            <input type="text" name="score" placeholder="Your Score" class="form-control allow_number">
                        </div>
                    </div>
                </div>
            </div>

            <?php echo form_close(); ?>                                           
        </div>
    </div>
</div>
<!-- Month Goal End -->
