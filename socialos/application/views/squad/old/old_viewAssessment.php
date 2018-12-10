<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('squad/index') ?>">Home</a></li>
        <li><a href="<?= site_url('squad/viewPrevious') ?>">View Previous</a></li>
        <li>View Assessment</li>
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
    <div class="col-md-8">

        <?php
        $members = get_squad_helper($user->squad_group);
        ?>
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h6 class="text-semibold"><?= get_squadgroup_helper(array('squad_group' => $user->squad_group)) . ' - ' . $this->logged_in_name; ?></h6>
                            </div>
                            
                            <ul class="info-blocks">
                                <?php
                                foreach ($members as $m1) {
                                    $noCount = 0;

                                    $checkPaused1 = get_ispaused_helper(array('user_id' => $m1->id, 'status' => 0, 'week' => $currentWeek['week'], 'month' => $currentWeek['month'], 'year' => $currentWeek['year']));
                                    if ($checkPaused1) {
                                        $selfCount = get_countassessment_helper(array('sender_id' => $m1->id, 'receiver_id' => $m1->id, 'week' => $currentWeek['week'], 'month' => $currentWeek['month'], 'year' => $currentWeek['year']));
                                        ?>
                                        <li class="bg-default sminfo">
                                            <div class="top-info">
                                                <span class="limitCharacter" href="#"><?= $m1->first_name . ' ' . $m1->last_name; ?></span>
                                            </div>
                                            <img alt="Profile" class="media-object thumbnail-boxed" src="<?= base_url('profile/' . $m1->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">
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
                                            $cPause = get_ispaused_helper(array('user_id' => $user1->id, 'status' => 0, 'week' => $currentWeek['week'], 'month' => $currentWeek['month'], 'year' => $currentWeek['year']));
                                            if ($cPause) {
                                                foreach ($type as $t2) {
                                                    ?>
                                                    <?php
                                                    $is_rating2 = get_israting_helper(array('w.sender_id' => $m1->id, 't.type_id' => $t2->type_id, 'w.receiver_id' => $user1->id, 'w.week' => $currentWeek['week'], 'w.month' => $currentWeek['month'], 'w.year' => $currentWeek['year']));

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
                                        <img alt="Profile" class="media-object thumbnail-boxed margin-bottom" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">

                                        <?php
                                        $isDone = 0;
                                        $avg = 0;
                                        foreach ($type as $t) {
                                            $is_rating = get_israting_helper(array('w.sender_id' => $user->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek['week'], 'w.month' => $currentWeek['month'], 'w.year' => $currentWeek['year']));
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
                                                $('#<?= $m->id; ?>').html('<?= number_format((float) $avg / sizeof($type), 2, '.', ''); ?> %');
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