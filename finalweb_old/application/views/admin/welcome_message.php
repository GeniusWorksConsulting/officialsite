<style>
    .popover{
        max-width: 100%;
        background: #FFF;
        border: solid 1px #999;
    }
</style>

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

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <h6 class="text-success"><span id="totalCompleted">0</span>/<span id="total"></span> Tribe members have completed their squad assessments. </h6>
    </div>
</div>

<div class="row">
    <?php
    $total = 0;
    $totalCompleted = 0;
    foreach ($squadList as $s) {
        $members = get_squad_helper($s->squad_group);
        $total = $total + sizeof($members);
        $selfCompleted = 0;
        $peerCompleted = 0;
        //$squadAVG = get_averagerating_helper(array('squad_group' => $s->squad_group, 'is_squad' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
        ?>
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left"><?= get_squadgroup_helper(array('squad_group' => $s->squad_group)); ?>: Completed <span class="text-semibold" id="comPer<?= $s->squad_group; ?>">0</span>%. <span class="text-semibold" id="self<?= $s->squad_group; ?>">0</span>/<span class="text-semibold"><?= sizeof($members); ?></span> Completed Self Assessment and <span class="text-semibold" id="peer<?= $s->squad_group; ?>">0</span>/<span class="text-semibold"><?= sizeof($members); ?></span> Completed Peer Assessments. <!--COMPLETED <span class="text-semibold text-success" id="squad<?= $s->squad_group; ?>">0</span> OUT OF <span class="text-semibold text-success"><?= sizeof($members); ?></span> ASSESSMENT FOR THIS WEEK--></h4>
                    <div class="dropdown pull-right">
                        <a href="#" class="dropdown-toggle btn btn-link btn-icon" data-toggle="dropdown">
                            <i class="icon-flickr"></i> 
                        </a>
                        <ul class="dropdown-menu icons-right dropdown-menu-right">
                            <?php
                            foreach ($previous as $pre) {
                                if ($currentWeek->week != $pre->week || $currentWeek->month != $pre->month || $currentWeek->year != $pre->year) {
                                    $date = get_enddate_helper(array('week' => $pre->week, 'month' => $pre->month, 'year' => $pre->year));
                                    ?>
                                    <li>
                                        <a class="text-uppercase" href="<?= site_url('admin/viewAssessment?week=' . $pre->week . '&month=' . $pre->month . '&year=' . $pre->year . '&squad=' . $s->squad_group . '') ?>">
                                            <?= date("F", mktime(0, 0, 0, $pre->month, 1, 2011)) . '-Week ' . $pre->week . ' ' . date('d', strtotime($date->from_date)) . ' To ' . date('d', strtotime($date->to_date)); ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <!--<div class="row">
                        <div class="col-md-12">
                            <p> Completed <span class="text-semibold" id="comPer<?= $s->squad_group; ?>">0</span>%. <span class="text-semibold" id="self<?= $s->squad_group; ?>">0</span>/<span class="text-semibold"><?= sizeof($members); ?></span> Completed Self and <span class="text-semibold" id="peer<?= $s->squad_group; ?>">0</span>/<span class="text-semibold"><?= sizeof($members); ?></span> Completed Peer for the Squads.</p>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="info-blocks">

                                <?php
                                $completedAss = 0;
                                foreach ($members as $m) {
                                    $isSelf = 0;
                                    $doneAssessment = 0;
                                    $averageAssessment = 0;
                                    $checkPaused = get_ispaused_helper(array('user_id' => $m->id, 'status' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                    $count = get_countassessment_helper(array('sender_id' => $m->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                    if ($count > 0) {
                                        $totalAssessment = sizeof($members) * sizeof($type);
                                        if ($count == $totalAssessment) {
                                            $completedAss++;
                                        }
                                    }
                                    ?>
                                    <li id="<?= $m->id; ?>" class="bg-default sminfo" data-toggle="popover" data-container="body" data-trigger="click" data-html="true">
                                        <div class="top-info">
                                            <span class="limitCharacter12" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                            <p class="text-center text-success" id="avPer<?= $m->id; ?>">00 %</p>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed <?= (!$checkPaused) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">
                                        <a id="btnSelf<?= $m->id; ?>" class="bottom-info bg-white margin-top">SELF</a>
                                        <div class="progress block-inner">
                                            <div id="percentage<?= $m->id; ?>" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                <span>PEER</span>
                                            </div>
                                        </div>
                                    </li>
                                    <div class="hide" id="hover<?= $m->id; ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <u><h6><?= $m->first_name; ?></h6></u>

                                                <ul class="info-blocks">
                                                    <?php
                                                    foreach ($members as $user) {
                                                        ?>
                                                        <li class="bg-default sminfo">
                                                            <div class="top-info">
                                                                <span class="limitCharacter" href="#"><?= $user->first_name; ?></span>
                                                                <p id="as<?= $s->squad_group . $m->id . $user->id ?>" class="text-center text-danger"> 00.00 %</p>
                                                            </div> 
                                                        </li>
                                                        <?php
                                                        $isDone = 0;
                                                        $avg = 0;
                                                        foreach ($type as $t) {
                                                            ?>
                                                            <?php
                                                            $is_rating = get_israting_helper(array('w.sender_id' => $m->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $user->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));

                                                            if ($is_rating) {
                                                                $isDone++;
                                                                $avg = $avg + $is_rating->rating;
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($isDone == sizeof($type)) {
                                                            $doneAssessment++;
                                                            if ($m->id == $user->id) {
                                                                $isSelf = 1;
                                                                $doneAssessment--;
                                                                $selfCompleted++;
                                                                ?>
                                                                <script type="text/javascript">
                                                                    $('#btnSelf<?= $m->id; ?>').removeClass('bg-white');
                                                                    $('#btnSelf<?= $m->id; ?>').addClass('bg-success');
                                                                </script>
                                                            <?php }
                                                            ?>
                                                            <script type="text/javascript">
                                                                $('#as<?= $s->squad_group . $m->id . $user->id ?>').html('<?= number_format((float) $avg / sizeof($type), 2, '.', '') ?> %');
                                                                $('#as<?= $s->squad_group . $m->id . $user->id ?>').removeClass('text-danger');
                                                                $('#as<?= $s->squad_group . $m->id . $user->id ?>').addClass('text-success');
                                                            </script>
                                                        <?php }
                                                        ?>
                                                    <?php } ?>
                                                </ul>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <u><h6><?= $m->first_name; ?> has been assessed by:</h6></u>

                                                <ul class="info-blocks">
                                                    <?php
                                                    foreach ($members as $ass) {
                                                        ?>
                                                        <li class="bg-default sminfo">
                                                            <div class="top-info">
                                                                <span class="limitCharacter" href="#"><?= $ass->first_name; ?></span>
                                                                <p id="assessBy<?= $s->squad_group . $m->id . $ass->id ?>" class="text-center text-danger"> 00.00 %</p>
                                                            </div>
                                                        </li>
                                                        <?php
                                                        $isDone1 = 0;
                                                        $avg1 = 0;
                                                        foreach ($type as $t) {
                                                            ?>
                                                            <?php
                                                            $is_rating1 = get_israting_helper(array('w.sender_id' => $ass->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));

                                                            if ($is_rating1) {
                                                                $isDone1++;
                                                                $avg1 = $avg1 + $is_rating1->rating;
                                                            }
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($isDone1 == sizeof($type)) {
                                                            $averageAssessment = $averageAssessment + number_format((float) $avg1 / sizeof($type), 2, '.', '');
                                                            ?>
                                                            <script type="text/javascript">
                                                                $('#assessBy<?= $s->squad_group . $m->id . $ass->id ?>').html('<?= number_format((float) $avg1 / sizeof($type), 2, '.', '') ?> %');
                                                                $('#assessBy<?= $s->squad_group . $m->id . $ass->id ?>').removeClass('text-danger');
                                                                $('#assessBy<?= $s->squad_group . $m->id . $ass->id ?>').addClass('text-success');
                                                            </script>
                                                        <?php }
                                                        ?>
                                                    <?php } ?>
                                                </ul>

                                            </div>
                                        </div>

                                    </div>

                                    <?php
                                    $division = sizeof($members) - 1;
                                    if ($division > 0) {
                                        $percentage = ($doneAssessment * 100) / $division;
                                        if ($percentage >= 99.99) {
                                            $peerCompleted++;
                                            if ($isSelf == 1) {
                                                $totalCompleted++;
                                            }
                                        }
                                    } else {
                                        $percentage = 0;
                                    }
                                    ?>
                                    <script type="text/javascript">
                                        $('#percentage<?= $m->id; ?>').css({width: <?= $percentage; ?> + '%'});
                                        $('#avPer<?= $m->id; ?>').html('<?= number_format((float) $averageAssessment / sizeof($members), 2, '.', '') . ' %'; ?>');
                                    </script>
                                <?php }
                                ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script type="text/javascript">
            $('#squad<?= $s->squad_group; ?>').html('<?= $completedAss; ?>');
            $('#self<?= $s->squad_group; ?>').html('<?= $selfCompleted; ?>');
            $('#peer<?= $s->squad_group; ?>').html('<?= $peerCompleted; ?>');
            $('#comPer<?= $s->squad_group; ?>').html('<?= number_format((float) $completedAss * 100 / sizeof($members), 2, '.', ''); ?>');
        </script>
        <?php
    }
    ?>
    <script type="text/javascript">
        $('#total').html('<?= $total; ?>');
        $('#totalCompleted').html('<?= $totalCompleted; ?>');
    </script>
</div>

<script>
    jQuery(function ($) {
        $("[data-toggle=popover]").each(function (i, obj) {
            $(this).popover({
                html: true,
                placement: 'auto left',
                content: function () {
                    var id = $(this).attr('id');
                    return $('#hover' + id).html();
                }
            });
        });
        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    });
</script>