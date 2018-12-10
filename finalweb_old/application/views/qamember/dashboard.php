<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3>Dashboard <small>Welcome <?= $this->logged_in_name; ?>. 12 hours since last visit</small></h3>
    </div>

    <div class="range">
        <ul class="statistics">
            <li style="cursor: default">
                <div class="statistics-info">
                    <a title="Date" class="bg-success btn btn-xs"><?= date('jS', strtotime(date('Y-m-d H:i:s'))); ?></a>
                    <strong><?= date('F', strtotime(date('Y-m-d H:i:s'))); ?> </strong>
                </div>
                <div class="progress progress-micro">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
                <span><?= date('l', strtotime(date('Y-m-d H:i:s'))); ?> - <?= date('Y', strtotime(date('Y-m-d H:i:s'))); ?></span>
            </li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Breadcrumbs line -->
<!--<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('qamember/index') ?>">Home</a></li>
    </ul>
</div>-->
<!-- /breadcrumbs line -->

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-9">
        <?php
        foreach ($squadList as $squad) {
            $members = get_squad_helper($squad->squad_group);
            ?>

            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    $isClaim = get_isclaim_helper(array('user_id' => $this->logged_in_id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year, 'squad_group' => $squad->squad_group));
                                    ?>
                                    <a onclick="return confirm('Want to Claim this Squad?');" id="btnClaim" href="<?= site_url('qamember/claimSquad/' . $squad->squad_group); ?>" class="btn <?= ($isClaim) ? 'btn-success' : 'btn-primary'; ?> btn-xs">Claim This Squad</a>
                                    <hr>
                                    <h6 class="text-semibold"><?= get_squadgroup_helper(array('squad_group' => $squad->squad_group)); ?> - <?= ($isClaim) ? $this->logged_in_name : ''; ?></h6>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="info-blocks">

                                            <?php
                                            foreach ($members as $m1) {
                                                $noCount = 0;

                                                $checkPaused1 = get_ispaused_helper(array('user_id' => $m1->id, 'status' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                                if ($checkPaused1) {
                                                    $selfCount = get_countassessment_helper(array('sender_id' => $m1->id, 'receiver_id' => $m1->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                                    ?>
                                                    <li class="bg-default sminfo">
                                                        <div class="top-info">
                                                            <span class="limitCharacter" href="#"><?= $m1->first_name . ' ' . $m1->last_name; ?></span>
                                                        </div>
                                                        <img alt="Profile" class="media-object thumbnail-boxed" src="<?= base_url('profile/' . $m1->profile); ?>" onerror="this.src='../assets/images/user.png';">
                                                        <a id="btnSelf<?= $m1->id; ?>" class="bottom-info <?= ($selfCount == sizeof($type)) ? 'bg-success' : 'bg-white'; ?> margin-top">SELF</a>
                                                        <div class="progress block-inner">
                                                            <div id="percentage<?= $m1->id; ?>" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                                <span>PEER</span>
                                                            </div>
                                                        </div>
                                                    </li>

                                                    <?php
                                                    $disableAccount = 0;
                                                    foreach ($members as $user1) {
                                                        $isDone2 = 0;
                                                        $cPause = get_ispaused_helper(array('user_id' => $user1->id, 'status' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                                        if ($cPause) {
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

                                                    $exMebmer = $disableAccount + 1;
                                                    $percentage = ($noCount * 100) / (sizeof($members) - $exMebmer);
                                                    ?>
                                                    <script type="text/javascript">
                                                        $('#percentage<?= $m1->id; ?>').css({width: <?= $percentage; ?> + '%'});
                                                    </script>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </ul>

                                        <ul class="info-blocks">

                                            <?php foreach ($members as $m) { ?>
                                                <li class="bg-default">
                                                    <div class="top-info">
                                                        <span class="limitCharacter" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                                        <p class="text-success text-semibold" id="<?= $m->id; ?>">00.00 %</p>
                                                    </div>
                                                    <img alt="Profile" class="media-object thumbnail-boxed margin-bottom" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='../assets/images/user.png';">

                                                    <?php
                                                    $isDone = 0;
                                                    foreach ($type as $t) {
                                                        $is_rating = get_israting_helper(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
                                                        ?>
                                                        <a id="<?= $t->type_id . $m->id; ?>" <?= ($isClaim) ? 'onclick="qa_assessment(' . $m->id . ', ' . $t->type_id . ')"' : ''; ?> class="bottom-info <?= ($is_rating) ? 'bg-success' : 'bg-primary' ?>"><?= ($is_rating) ? $is_rating->rating : $t->type; ?></a>
                                                        <?php
                                                        if ($is_rating) {
                                                            $isDone++;
                                                        }
                                                    }

                                                    if ($isDone == sizeof($type)) {
                                                        ?>

                                                        <?php
                                                        $avg = 0;
                                                        foreach ($type as $t1) {
                                                            $is_rating1 = get_israting_helper(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $t1->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
                                                            ?>
                                                            <script type="text/javascript">
                                                                $('#<?= $t1->type_id . $m->id; ?>').html('<?= $is_rating1->rating; ?>');
                                                                $('#<?= $t1->type_id . $m->id; ?>').removeAttr('onclick');
                                                                $('#<?= $t1->type_id . $m->id; ?>').removeClass('bg-success');
                                                                $('#<?= $t1->type_id . $m->id; ?>').addClass('bg-info');
                                                            </script>
                                                            <?php
                                                            $avg = $avg + $is_rating1->rating;
                                                        }
                                                        ?>
                                                        <script type="text/javascript">
                                                            $('#<?= $m->id; ?>').html('<?= number_format((float) $avg / 2, 2, '.', ''); ?> %');
                                                        </script>
                                                    <?php }
                                                    ?>
                                                </li>
                                            <?php }
                                            ?>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <?php }
        ?>
    </div>

    <div class="col-md-3">
        <?php
        foreach ($squadList as $squad) {
            $members = get_squad_helper($squad->squad_group);
            ?>
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
        <?php }
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

<!-- Choose Squad -->
<div id="squadModal1" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Form inside modal -->
            <?php
            echo form_open('qamember/index', array('id' => 'squadForm', 'name' => 'squadForm', 'class' => ''));
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

<!-- Choose Squad End -->
<script type="text/javascript">
//    jQuery(function ($) {
//        $("#btnClaim").click(function () {
//
//            $.ajax({
//                type: "post",
//                url: siteurl + 'qamember/getSquadlist',
//                dataType: "json",
//                success: function (response) {
//                    console.log(JSON.stringify(response));
//                    if (response['status'] === 0) {
//                        var data = response['response'];
//                        $.each(data, function (key, item) {
//                            $('#' + item.squad_group).attr('checked', true);
//                        });
//                    }
//                }
//            });
//
//            $('#squadModal1').modal({
//                backdrop: 'static',
//                keyboard: false
//            });
//        });
//    });
</script>