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

<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Home</a></li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
<?php } ?>

<div class="row">
    <?php
    foreach ($squadList as $s) {
        $members = get_squad_helper($s->squad_group);
        //$squadAVG = get_averagerating_helper(array('squad_group' => $s->squad_group, 'is_squad' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
        ?>
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="col-md-4">
                        <u><?= get_squadgroup_helper(array('squad_group' => $s->squad_group)); ?>: </u>
                    </div>
                    <div class="col-md-8 text-right">
                        <?php
                        foreach ($previous as $pre) {
                            if ($currentWeek->week != $pre->week || $currentWeek->month != $pre->month || $currentWeek->year != $pre->year) {
                                ?>
                                <a href="<?= site_url('admin/viewAssessment?week=' . $pre->week . '&month=' . $pre->month . '&year=' . $pre->year . '&squad=' . $s->squad_group . '') ?>" title="View" class="btn btn-info btn-sm btn-icon">
                                    <?= date("F", mktime(0, 0, 0, $pre->month, 1, 2011)) . '-Week ' . $pre->week ?>
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-12">
                        <!--<u><?= get_squadgroup_helper(array('squad_group' => $s->squad_group)); ?>: </u> COMPLETED <span class="text-semibold text-success" id="squad<?= $s->squad_group; ?>">0</span> OUT OF <span class="text-semibold text-success"><?= sizeof($members); ?></span> ASSESSMENT FOR THIS WEEK <span style="float: right"><?= isset($squadAVG) ? number_format((float) $squadAVG, 2, '.', '') : '00' ?>%</span>-->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="info-blocks">

                                <?php
                                $completedAss = 0;
                                foreach ($members as $m) {
                                    $count = get_countassessment_helper(array('sender_id' => $m->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                    if ($count == sizeof($members) * sizeof($type)) {
                                        $completedAss++;
                                    }
                                    $avRating = get_averagerating_helper(array('receiver_id' => $m->id, 'is_squad' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                                    ?>
                                    <li id="<?= $m->id; ?>" class="bg-default sminfo" data-toggle="popover" data-container="body" data-trigger="click" data-html="true">
                                        <div class="top-info">
                                            <span class="limitCharacter" href="#"><?= $m->first_name; ?></span>
                                            <p class="text-center text-success" id=""><?= isset($avRating) ? number_format((float) $avRating, 2, '.', '') : '00' ?> %</p>
                                        </div>
                                        <img alt="Profile" class="media-object thumbnail-boxed" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='../assets/images/user.png';">
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
                                                <u><h6><?= $m->first_name; ?> HAVE BEEN ASSESSED BY:</h6></u>

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

                                <?php } ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script type="text/javascript">
            $('#squad<?= $s->squad_group; ?>').html('<?= $completedAss; ?>');
        </script>
        <?php
    }
    ?>
</div>

<script>
    jQuery(function ($) {
        $("[data-toggle=popover]").each(function (i, obj) {

            $(this).popover({
                html: true,
                placement: 'auto right',
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