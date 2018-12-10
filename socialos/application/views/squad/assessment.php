<br>
<div class="row">
    <div class="col-md-6 col-xs-12">
        <h3><?= $this->logged_in_name; ?> Dashboard </h3>
    </div>
    <div class="col-md-3 col-xs-12 text-right">
        <h6 class="text-semibold">SET MONTHLY GOAL: <label class="label label-success"><?= isset($month_goal) ? $month_goal->monthgoal : '00'; ?>%</label> </h6> <a <?= isset($month_goal) ? 'style="pointer-events: none;"' : ''; ?> id="btn_month">Click here</a>
    </div>
    <div class="col-md-3 col-xs-12 text-right">
        <h6 class="text-semibold">SET MONTHLY GOAL: <label class="label label-success"><?= isset($week_goal) ? $week_goal->weekgoal : '00'; ?>%</label> </h6> <a <?= isset($week_goal) ? 'style="pointer-events: none;"' : ''; ?> id="btn_week">Click here</a>
    </div>
</div>
<br>

<!-- Message -->
<?php
if ($this->session->flashdata('message')) {
    ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
<?php } ?>

<?php if ($this->is_paused) { ?>
    <div id="msg" class="callout callout-danger fade in">
        <h5>Your account has been paused.</h5>
    </div>
<?php } ?>

<?php
$active = 0;

foreach ($members as $a) {
    if ($a->is_paused == 1) {
        $active++;
    }
}
?>
<div <?php
if ($this->is_paused) {
    echo 'style="pointer-events: none;"';
}
?>>
    <div class="row">
        <!--col-md-12-->
        <div class="col-md-12">
            <!--Panel-->
            <div class="panel panel-default">
                <!--Panel Body-->
                <div class="panel-body">

                    <!--Row 1-->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h6 style="text-decoration: underline" class="text-semibold">
                                You Have Completed <span id="userdone" class="text-semibold">0</span> out of <span class="text-semibold"><?= sizeof($members); ?></span> Assessments for This Week
                            </h6>
                        </div>
                    </div>
                    <!--Row 1 END-->

                    <!--Row 2-->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <ul class="info-blocks">

                                <?php
                                foreach ($members as $u) {
                                    $nocount = 0;
                                    $self = 0;
                                    $peer = 0;
                                    ?>

                                    <li class="bg-default sminfo">
                                        <div class="top-info">
                                            <span class="limitCharacter10" href="#"><?= $u->first_name . ' ' . $u->last_name; ?></span>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed <?= ($u->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $u->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">

                                        <div class="progress progress-striped active block-inner margin-top">
                                            <div id="self<?= $u->id; ?>" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                <span>SELF</span>
                                            </div>
                                        </div>
                                        <div class="progress progress-striped active block-inner margin-top">
                                            <div id="peer<?= $u->id; ?>" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                <span>PEER</span>
                                            </div>
                                        </div>
                                        <span class="text-semibold text-danger"><?= ($u->is_paused == 1) ? 'Leave' : '&nbsp;'; ?></span>
                                    </li>

                                    <?php
                                    foreach ($members as $m) {
                                        $one_ass = check_oneassessment(array('sender_id' => $u->id, 'user_id' => $m->id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));
                                        //var_dump($one_ass);

                                        $isDone = 0;
                                        foreach ($type as $t) {
                                            if ($one_ass) {
                                                if ($one_ass->type_id != $t->type_id) {
                                                    $is_rating = get_israting_helper(array('w.sender_id' => $u->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                                    if ($is_rating) {
                                                        $isDone = sizeof($type);
                                                        $nocount = $nocount + sizeof($type);
                                                        if ($u->id == $m->id) {
                                                            $nocount = $nocount - sizeof($type);
                                                        }
                                                    }
                                                }
                                            } else {
                                                $is_rating = get_israting_helper(array('w.sender_id' => $u->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                                if ($is_rating) {
                                                    $isDone++;
                                                    $nocount++;
                                                    if ($u->id == $m->id) {
                                                        $nocount--;
                                                    }
                                                }
                                            }
                                        }

                                        if ($u->id == $m->id) {
                                            if ($isDone > 0) {
                                                $self = ($isDone / sizeof($type)) * 100;
                                            }
                                        }
                                    }

                                    if ($nocount > 0) {
                                        $total = sizeof($members) * sizeof($type);
                                        $minus = sizeof($type) + $active;
                                        $peer = ($nocount / ($total - $minus)) * 100;
                                    }
                                    ?>
                                    <script type="text/javascript">
                                        $('#self<?= $u->id; ?>').css({width: <?= $self; ?> + '%'});
                                        $('#peer<?= $u->id; ?>').css({width: <?= $peer; ?> + '%'});
                                    </script>
                                <?php }
                                ?>
                            </ul>

                        </div>
                    </div>
                    <!--Row 1 END-->

                    <!--Row 2-->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <ul class="info-blocks">

                                <?php
                                $completed = 0;
                                foreach ($members as $m) {
                                    ?>
                                    <li class="bg-default" <?= ($m->is_paused == 1) ? 'style="pointer-events: none;"' : ''; ?>>
                                        <div class="top-info">
                                            <span class="limitCharacter10" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                            <p class="text-success text-semibold" id="average<?= $m->id; ?>">00.00 %</p>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed margin-bottom <?= ($m->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">
                                        <?php
                                        $one_ass = check_oneassessment(array('sender_id' => $this->logged_in_id, 'user_id' => $m->id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));

                                        $is_one = 0;
                                        $isDone = 0;
                                        $user_average = 0;
                                        foreach ($type as $t) {
                                            if ($one_ass && $one_ass->type_id == $t->type_id) {
                                                ?>
                                                <a id="btn<?= $t->type_id . $m->id ?>" class="bottom-info bg-primary"><?= 'No ' . $t->type; ?></a>
                                                <?php
                                            } else {
                                                $is_rating = get_israting_helper(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                                if ($is_rating) {
                                                    $isDone++;
                                                    $user_average = $user_average + $is_rating->rating;
                                                }
                                                ?>
                                                <a id="btn<?= $t->type_id . $m->id ?>" <?= ($is_rating) ? '' : 'onclick="assessment(' . $m->id . ', ' . $t->type_id . ')"' ?> class="bottom-info <?= ($is_rating) ? 'bg-success' : 'bg-primary' ?>"><?= ($is_rating) ? $is_rating->rating : $t->type; ?></a>
                                                <?php
                                            }

                                            if ($one_ass) {
                                                $is_one = sizeof($one_ass);
                                                if ($one_ass->type_id == $t->type_id) {
                                                    ?>
                                                    <a href="<?= site_url('squad/rollback/' . $m->id . '/' . $t->type_id); ?>" id="no_btn<?= $t->type_id . $m->id ?>" class="bottom-info bg-default confirmation"><?= $t->type; ?></a>
                                                <?php } else {
                                                    ?>
                                                    <a class="bottom-info bg-default"><?= '&nbsp;'; ?></a>
                                                <?php }
                                                ?>
                                            <?php } else {
                                                ?>
                                                <a id="no_btn<?= $t->type_id . $m->id ?>" href="<?= site_url('squad/one_assessment/' . $m->id . '/' . $t->type_id); ?>" class="bottom-info bg-warning confirmation"><?= 'No ' . $t->type; ?></a>
                                                <?php
                                            }
                                        }

                                        if ($isDone == (sizeof($type) - $is_one)) {
                                            $completed++;
                                            ?>
                                            <script type="text/javascript">
                                                $('#average<?= $m->id; ?>').html('<?= number_format((float) $user_average / (sizeof($type) - $is_one), 2, '.', ''); ?> %');
                                            </script>
                                        <?php } ?>
                                    </li>
                                <?php } ?>

                                <script type="text/javascript">
                                    $('#userdone').html('<?= $completed; ?>');
                                </script>

                            </ul>
                        </div>
                    </div>
                    <!--Row 2 END-->

                    <hr>
                    <!--Row 3-->
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <span class="label label-warning">YOU HAVE BEEN ASSESSED BY:</span>
                            <ul class="info-blocks">
                                <?php
                                foreach ($members as $m) {
                                    $one_ass = check_oneassessment(array('sender_id' => $m->id, 'user_id' => $this->logged_in_id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));
                                    ?>
                                    <li class="bg-default">
                                        <div class="top-info">
                                            <span class="limitCharacter10" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed margin-bottom <?= ($m->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">

                                        <?php
                                        foreach ($type as $t) {
                                            if ($one_ass && $one_ass->type_id == $t->type_id) {
                                                ?>
                                                <a class="bottom-info bg-success"><?= 'No ' . $t->type; ?></a>  
                                                <?php
                                            } else {
                                                $is_given = get_israting_helper(array('w.sender_id' => $m->id, 'w.receiver_id' => $this->logged_in_id, 't.type_id' => $t->type_id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                                ?>
                                                <a class="bottom-info <?= ($is_given) ? 'bg-success' : 'bg-primary' ?>"><?= $t->type; ?></a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!--Row 3 END-->

                </div>
                <!--Panel Body-->
            </div>
            <!--Panel-->
        </div>
        <!--col-md-12 End-->
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

<!-- Comment Box -->
<?php if ($user_id != NULL) { ?>
    <div id="comment_modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <a type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                    <h4 class="modal-title">Comment Box</h4>
                </div>

                <?php
                echo form_open('squad/submit_comment');
                ?>

                <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                <input type="hidden" name="title" value="Comment">
                <div class="modal-body with-padding">
                    <div class="block-inner text-danger">
                        <h6 class="heading-hr">Write your comment here. </h6>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea required placeholder="Comment" name="message" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-xs">Submit</button>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(function ($) {
            $('#comment_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });
    </script>
<?php } ?>

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
            echo form_open('squad/assessment', $attributes);
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

<!-- Month Goal -->
<div id="month_goal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Set Your Month Goal</h4>
            </div>

            <!-- Form inside modal -->
            <?php
            echo form_open('squad/assessment', array('id' => 'monthForm', 'name' => 'monthForm', 'class' => ''));
            ?>

            <div class="modal-body with-padding">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="text" name="monthgoal" placeholder="00" class="form-control allow_number">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="btnMonth" class="btn btn-primary btn-xs">Submit</button>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- Month Goal End -->

<script type="text/javascript">
    jQuery(function ($) {
        $("#btn_week").click(function () {
            $('#form_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $("#btn_month").click(function () {
            $('#month_goal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        $("form[name='weekForm']").validate({
            // Specify validation rules
            rules: {
                weekgoal: {
                    required: true,
                    number: true
                }
            },
            messages: {
                weekgoal: {
                    required: 'Please enter your goal.',
                    number: 'Please enter number only.'
                }
            },
            // Specify validation error messages
            errorClass: "text-danger",
            errorElement: "span",
            submitHandler: function (form) {
                form.submit();
            }
        });

        $("form[name='monthForm']").validate({
            // Specify validation rules
            rules: {
                monthgoal: {
                    required: true,
                    number: true
                }
            },
            messages: {
                monthgoal: {
                    required: 'Please enter your goal.',
                    number: 'Please enter number only.'
                }
            },
            // Specify validation error messages
            errorClass: "text-danger",
            errorElement: "span",
            submitHandler: function (form) {
                form.submit();
            }
        });

        $('.confirmation').on('click', function () {
            return confirm('Are you sure?');
        });
    });
</script>