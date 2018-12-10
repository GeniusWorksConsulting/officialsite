<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3>Dashboard <small>Welcome <?= $this->logged_in_name; ?>. 12 hours since last visit</small></h3>
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

<!--QA Member View Start-->
<?php if ($this->ion_auth->in_group(array('qamember', 'lead'))) { ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            foreach ($squadList as $squad) {
                $condition = array('u.squad_group' => $squad->squad_group, 'u.active' => 1);
                $members = get_activeusers_heper($condition, $week->week, $week->month, $week->year);
                $isClaim = get_isclaim_helper(array('user_id' => $this->logged_in_id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year, 'squad_group' => $squad->squad_group));

                $active = 0;
                foreach ($members as $a) {
                    if ($a->is_paused == 1) {
                        $active++;
                    }
                }
                ?>

                <div class="row">
                    <div class="col-md-12">

                        <div class="panel panel-default">
                            <div class="panel-body">

                                <!--Row 1-->
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
                                            <div class="col-md-12 col-xs-12">
                                                <span class="text-semibold"><?= get_squadgroup_helper(array('squad_group' => $squad->squad_group)); ?> <?= ($isClaim) ? '- ' . $this->logged_in_name : ''; ?></span> &nbsp;&nbsp;
                                                <a id="btnClaim" onclick="return confirm('Want to Claim this Squad?');" href="<?= site_url('users/claimSquad/' . $squad->squad_group); ?>" class="label <?= ($isClaim) ? 'label-success' : 'label-primary'; ?>">Claim This Squad</a>
                                            </div>
                                        <?php } ?>

                                        <?php
                                        if ($this->ion_auth->in_group(array('lead'))) {
                                            $isClaim = NULL;
                                            ?>
                                            <div class="col-md-12 col-xs-12">
                                                <span class="text-semibold"><?= get_squadgroup_helper(array('squad_group' => $squad->squad_group)); ?></span>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
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
                                                    <?php if ($this->ion_auth->in_group(array('lead'))) { ?>
                                                        <a href="<?= site_url('users/compliment/' . $u->id); ?>" class="bottom-info bg-info">COMPLIMENT</a>
                                                    <?php } ?> 
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

                                <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
                                    <!--Row 2-->
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <ul class="info-blocks" <?= (!$isClaim) ? 'style="pointer-events: none;"' : ''; ?>>

                                                <?php
                                                foreach ($members as $m) {
                                                    ?>
                                                    <li class="bg-default" <?= ($m->is_paused == 1) ? 'style="pointer-events: none;"' : ''; ?>>
                                                        <!--<a href="<?= site_url('users/user_details/' . $m->id); ?>">-->
                                                        <div class="top-info">
                                                            <span class="limitCharacter10" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                                            <p class="text-success text-semibold" id="average<?= $m->id; ?>">00.00 %</p>
                                                        </div>
                                                        <!--</a>-->
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
                                                                    //var_dump($is_rating);
                                                                }
                                                                ?>
                                                                <a id="btn<?= $t->type_id . $m->id ?>" <?php echo ($is_rating) ? 'onclick="return confirm(\'Are you sure to redo assessment.\')" href="' . site_url('users/addrating/' . $m->id . '/' . $is_rating->assessment) . '"' : 'onclick="qa_assessment(' . $m->id . ', ' . $t->type_id . ')"' ?> class="bottom-info <?= ($is_rating) ? 'bg-success' : 'bg-primary' ?>"><?= ($is_rating) ? $is_rating->rating . ' Redo' : $t->type; ?></a>
                                                                <?php
                                                            }

                                                            if ($one_ass) {
                                                                $is_one = sizeof($one_ass);
                                                                if ($one_ass->type_id == $t->type_id) {
                                                                    ?>
                                                                    <a href="<?= site_url('users/rollback/' . $m->id . '/' . $t->type_id); ?>" id="no_btn<?= $t->type_id . $m->id ?>" class="bottom-info bg-default confirmation"><?= $t->type; ?></a>
                                                                <?php } else {
                                                                    ?>
                                                                    <a class="bottom-info bg-default"><?= '&nbsp;'; ?></a>
                                                                <?php }
                                                                ?>
                                                            <?php } else {
                                                                ?>
                                                                <a id="no_btn<?= $t->type_id . $m->id ?>" href="<?= site_url('users/one_assessment/' . $m->id . '/' . $t->type_id); ?>" class="bottom-info bg-warning confirmation"><?= 'No ' . $t->type; ?></a>
                                                                <?php
                                                            }
                                                        }

                                                        if ($isDone == (sizeof($type) - $is_one)) {
                                                            ?>
                                                            <script type="text/javascript">
                                                                $('#average<?= $m->id; ?>').html('<?= number_format((float) $user_average / (sizeof($type) - $is_one), 2, '.', ''); ?> %');
                                                            </script>
                                                        <?php } ?>
                                                    </li>
                                                <?php } ?>

                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!--Row 2 END-->

                            </div>
                        </div>

                    </div>

                    <!--<div class="col-md-3">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="block task task-normal">
                                    <div class="row with-padding">
                                        <div class="col-sm-12">
                                            <div class="task-description text-center">
                                                <a><?= get_squadgroup_helper(array('squad_group' => $squad->squad_group)); ?></a>
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
                                                <h5>00 % Customer Love</h5>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="task-description text-center">
                                                <a>0 OUT OF <?= sizeof($members); ?></a>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="task-description text-center">
                                                <h5 class="text-success">Have Completed Their Squad Assessment</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>-->

                </div>
                <?php
                //break;
            }
            ?>
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
<?php } ?>
<!--QA Member View End-->

<!-- Comment Box -->
<?php
if ($this->ion_auth->in_group(array('qamember'))) {
    if ($user_id != NULL) {
        ?>
        <div id="comment_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <a type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                        <h4 class="modal-title">Comment Box</h4>
                    </div>

                    <?php
                    echo form_open('users/submit_comment');
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
        <?php
    }
}
?>

<!-- Choose Squad -->
<div id="squadModal1" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Form inside modal -->
            <?php
            echo form_open('users/index', array('id' => 'squadForm', 'name' => 'squadForm', 'class' => ''));
            ?>

            <div class="modal-body with-padding boxshadow">
                <div class="block-inner text-info">
                    <h6 class="heading-hr">
                        Claim a specific squad...
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </h6>

                </div>

                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <?php foreach ($squad_group as $s) { ?>

                                <div class="col-sm-4">

                                    <div class="block-inner">
                                        <label class="checkbox-inline">
                                            <input id="<?= $s->squad_group; ?>" name="squad_group[]" value="<?= $s->squad_group; ?>" type="checkbox"><?= $s->squad_name; ?>
                                        </label>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>

                    </div>
                </div>

                <!-- Message -->
                <?php if (validation_errors()) { ?>
                    <p><?php echo validation_errors(); ?></p>
                <?php } ?>

                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <button type="submit" name="btnChoose" class="btn btn-success btn-xs">
                                    Submit  
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<!-- Choose Squad End -->