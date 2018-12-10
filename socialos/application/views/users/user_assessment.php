<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('users/index') ?>">Home</a></li>
        <li><a href="<?= site_url('users/viewscore') ?>">View Score</a></li>
        <li>Scores</li>
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
    <div class="col-md-12 text-center">
        <label class="label label-success"><?= $user->first_name . ' ' . $user->last_name . ' - ' . get_squadgroup_helper(array('squad_group' => $user->squad_group)); ?></label>

        <div class="panel panel-default">
            <div class="panel-body text-center">

                <ul class="info-blocks">
                    <?php
                    $total = 0;

                    $active = 0;
                    foreach ($members as $a) {
                        if ($a->is_paused == 1) {
                            $active++;
                        }
                    }

                    foreach ($members as $m) {
                        ?>
                        <li class="bg-default sminfo">
                            <div class="top-info">
                                <span class="limitCharacter10" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                <p class="text-success text-semibold" id="average<?= $m->id; ?>">00.00 %</p>
                            </div>
                            <img alt="Profile" class="media-object thumbnail-boxed margin-bottom <?= ($m->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">
                            <?php
                            $one_ass = check_oneassessment(array('sender_id' => $m->id, 'user_id' => $user->id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));

                            $is_one = 0;
                            $isDone = 0;
                            $user_average = 0;
                            foreach ($type as $t) {
                                if ($one_ass) {
                                    $is_one = sizeof($one_ass);
                                }

                                $is_rating = get_israting_helper(array('w.sender_id' => $m->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $user->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                if ($is_rating) {
                                    $isDone++;
                                    $user_average = $user_average + $is_rating->rating;
                                }
                                ?>
                                <a class="bottom-info bg-white margin-top"><?= ($one_ass && $one_ass->type_id == $t->type_id) ? 'No ' : ''; ?><?= ($is_rating) ? $is_rating->rating : $t->type; ?></a>
                                <?php
                            }

                            if ($isDone == (sizeof($type) - $is_one)) {
                                $total = $total + ($user_average / (sizeof($type) - $is_one));
                                ?>
                                <script type="text/javascript">
                                    $('#average<?= $m->id; ?>').html('<?= number_format((float) $user_average / (sizeof($type) - $is_one), 2, '.', ''); ?> %');
                                </script>
                            <?php } ?>
                        </li>
                    <?php } ?>
                </ul>

                <!--<label class="label label-info"><?= 'Average Score: ' . $total / (count($members) - $active); ?></label>-->
                <!--<button class="btn btn-block disabled">Perception Gap: <?= ($qarating) ? round($qarating->rating - $total / sizeof($users), 2) : '0'; ?></button>-->
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <a class="btn btn-primary btn-xs" href="<?= site_url('users/viewscore'); ?>">Back</a>
    </div>
</div>
<br>
