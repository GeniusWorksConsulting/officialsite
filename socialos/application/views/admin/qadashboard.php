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
        <li><a href="<?= site_url('admin/welcome') ?>">Home</a></li>
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
    foreach ($users as $u) {
        $squadList = get_qasquad_helper(array('user_id' => $u->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
        ?>
        <div class="col-md-6">
            <div class="callout callout-success fade in">
                <h5><?= $u->first_name; ?></h5>
            </div>

            <?php if (empty($squadList)) { ?>
                <div class = "panel panel-default">
                    <div class = "panel-body">
                        <p class="text-danger"><?= $u->first_name; ?> has not claimed any squad for assessments.</p>
                    </div>
                </div>    
            <?php } ?>

            <?php
            foreach ($squadList as $s) {
                $members = get_squad_helper($s->squad_group);
                ?>
                <div class = "panel panel-default">
                    <div class = "panel-body">
                        <div class="col-md-4">
                            <u><h6 class="text-semibold"><?= get_squadgroup_helper(array('squad_group' => $s->squad_group)); ?></h6></u>
                        </div>
                        <div class="col-md-8 text-right">
                            <?php
                            foreach ($previous as $pre) {
                                if ($currentWeek->week != $pre->week || $currentWeek->month != $pre->month || $currentWeek->year != $pre->year) {
                                    ?>
                                    <a href="<?= site_url('admin/viewQAassess?week=' . $pre->week . '&month=' . $pre->month . '&year=' . $pre->year . '&user_id=' . $u->id . '') ?>" title="View" class="btn btn-info btn-sm btn-icon">
                                        <?= date("F", mktime(0, 0, 0, $pre->month, 1, 2011)) . '-Week ' . $pre->week ?>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class = "info-blocks">
                                    <?php
                                    foreach ($members as $m) {
                                        ?>
                                        <li class="bg-default">
                                            <div class="top-info">
                                                <span class="limitCharacter" href="#"><?= $m->first_name; ?></span>
                                                <p class="text-success text-semibold" id="<?= $m->id; ?>">00.00 %</p>
                                            </div>
                                            <img alt="Profile" class="media-object thumbnail-boxed" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='../assets/images/user.png';">

                                            <?php
                                            $isDone = 0;
                                            $avg = 0;
                                            foreach ($type as $t) {
                                                $is_rating = get_israting_helper(array('w.sender_id' => $u->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
                                                ?>
                                                <a id="<?= $t->type_id . $m->id; ?>" class="bottom-info <?= ($is_rating) ? 'bg-success' : 'bg-primary' ?>"><?= ($is_rating) ? $is_rating->rating : $t->type; ?></a>
                                                <?php
                                                if ($is_rating) {
                                                    $isDone++;
                                                    $avg = $avg + $is_rating->rating;
                                                }
                                            }

                                            if ($isDone == sizeof($type)) {
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
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
        </div>

        <script type="text/javascript">
            $('#user<?= $u->id; ?>').html('<?= $noCompleted; ?>');
        </script>
    <?php }
    ?>
</div>