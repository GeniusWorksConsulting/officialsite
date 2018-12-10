<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3>View Score <small>Welcome <?= $this->logged_in_name; ?></small></h3>
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
<div class="row">
    <div class="col-md-12">
        <?php
        foreach ($squadList as $squad) {
            $condition = array('u.squad_group' => $squad->squad_group, 'u.active' => 1);
            $members = get_activeusers_heper($condition, $week->week, $week->month, $week->year);
            ?>

            <div class="row">
                <div class="col-md-12">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <!--Row 1-->
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <ul class="info-blocks">

                                        <?php
                                        foreach ($members as $m) {
                                            $peer_score = calculate_love($m->id, $members, $type, $week);
                                            $qa_score = calculate_qa_love($m, $type, $week);
                                            $self_score = calculate_self_love($m->id, $type, $week);

                                            $peer_score = (($peer_score * 80) / 100) / 2;
                                            $qa_score = $qa_score / 2;
                                            $self_score = (($self_score * 20) / 100) / 2;

                                            $final_love = $peer_score + $self_score + $qa_score;
                                            ?>

                                            <li class="bg-default" <?= ($m->is_paused == 1) ? 'style="pointer-events: none;"' : ''; ?>>

                                                <div class="top-info">
                                                    <span class="limitCharacter10" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                                    <p class="text-success text-semibold" id="average<?= $m->id; ?>"><?= round($final_love, 2); ?> %</p>
                                                </div>

                                                <img alt="Profile" class="media-object thumbnail-boxed margin-bottom <?= ($m->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">
                                                <?php foreach ($type as $t) { ?>
                                                    <a id="btn<?= $t->type_id . $m->id ?>" href="<?= site_url('users/user_details/' . $m->id); ?>" class="bottom-info bg-primary"><?= $t->type; ?></a>
                                                <?php } ?>
                                            </li>

                                        <?php } ?>

                                    </ul>
                                </div>
                            </div>
                            <!--Row 1 END-->

                        </div>
                    </div>

                </div>

            </div>
            <?php
            //break;
        }
        ?>
    </div>
</div>

<?php

function calculate_love($receiver_id, $members, $type, $week) {
    $user_love = 0;
    $paused = 1;

    foreach ($members as $u) {
        // if not paused
        if ($u->is_paused == 0) {
            if ($u->id == $receiver_id) {
                continue;
            }
            $ratings = get_assessment_helper(array('w.receiver_id' => $receiver_id, 'w.sender_id' => $u->id, 'w.is_squad' => 0, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
            $user_love = $user_love + sum_rating($ratings, $receiver_id, $u->id, $type, $week);
        }

        // if paused
        else {
            $paused++;
        }
    }

    return $user_love / (count($members) - $paused);
}

function sum_rating($ratings, $receiver_id, $sender_id, $type, $week) {
    if (sizeof($ratings) > 0) {
        $t_rating = 0;

        foreach ($ratings as $row) {
            $t_rating = $t_rating + $row->rating;
        }

        $one_ass = check_oneassessment(array('sender_id' => $sender_id, 'user_id' => $receiver_id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));
        if ($one_ass) {
            return $t_rating / (count($type) - count($one_ass));
        } else {
            return $t_rating / count($type);
        }
    }

    return 0;
}

function calculate_qa_love($user, $type, $week) {
    $qa_love = 0;
    $ci = & get_instance();
    $qa_list = $ci->backend->get_table('qa_squad', array('squad_group' => $user->squad_group, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));

    foreach ($qa_list as $qa) {
        $ratings = get_assessment_helper(array('w.receiver_id' => $user->id, 'w.sender_id' => $qa->user_id, 'w.is_squad' => 1, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
        $qa_love = $qa_love + sum_rating($ratings, $user->id, $qa->user_id, $type, $week);
    }

    if ($qa_love > 0) {
        return $qa_love / count($qa_list);
    }

    return 0;
}

function calculate_self_love($user_id, $type, $week) {
    $ratings = get_assessment_helper(array('w.receiver_id' => $user_id, 'w.sender_id' => $user_id, 'w.is_squad' => 0, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
    $self_love = sum_rating($ratings, $user_id, $user_id, $type, $week);

    return $self_love;
}
?>