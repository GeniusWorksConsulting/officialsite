<script src="<?= base_url('assets/js/circle-progressbar.js'); ?>"></script>
<br>
<?php
$customer_love = 0;
$t_love = 0;

$active = 0;
foreach ($members as $a) {
    if ($a->is_paused == 1) {
        $active++;
    }
}

$minarray = array('rating' => 100, 'name' => '', 'profile' => '');
$maxarray = array('rating' => 0, 'name' => '', 'profile' => '');
$firsttribe = array('rating' => 0, 'squad_name' => '');
$lasttribe = array('rating' => 100, 'squad_name' => '');

foreach ($tribes as $t) {
    $tribe_love = 0;
    $user_tribe = FALSE;

    $condition = array('u.squad_group' => $t->squad_group, 'u.active' => 1);
    $users = get_activeusers_heper($condition, $week->week, $week->month, $week->year);
    //var_dump($t);

    if (count($users) > 0) {
        foreach ($users as $u) {
            $peer_score = calculate_love($u->id, $members, $type, $week);
            $qa_score = calculate_qa_love($u, $type, $week);
            $self_score = calculate_self_love($u->id, $type, $week);

            $peer_score = (($peer_score * 80) / 100) / 2;
            $qa_score = $qa_score / 2;
            $self_score = (($self_score * 20) / 100) / 2;

            $final_love = $peer_score + $self_score + $qa_score;
            //var_dump($u);
            //var_dump($final_love);

            if ($minarray['rating'] > $final_love) {
                $minarray['rating'] = $final_love;
                $minarray['profile'] = $u->profile;
                $minarray['name'] = $u->first_name . ' ' . $u->last_name;
            }

            if ($maxarray['rating'] < $final_love) {
                $maxarray['rating'] = $final_love;
                $maxarray['profile'] = $u->profile;
                $maxarray['name'] = $u->first_name . ' ' . $u->last_name;
            }

            $tribe_love = $tribe_love + $final_love;

            if ($u->id == $this->logged_in_id) {
                $customer_love = $final_love;
                $user_tribe = TRUE;
            }
        }

        $tribe_average = $tribe_love / (count($users) - $active);
        if ($user_tribe) {
            $t_love = $tribe_average;
        }

        //var_dump($tribe_average);

        if ($lasttribe['rating'] > $tribe_average) {
            $lasttribe['rating'] = $tribe_average;
            $lasttribe['squad_name'] = $t->squad_name;
        }

        if ($firsttribe['rating'] < $tribe_average) {
            $firsttribe['rating'] = $tribe_average;
            $firsttribe['squad_name'] = $t->squad_name;
        }
    }
}

//var_dump($tribe_love / count($members));
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

//var_dump($minarray);
//var_dump($maxarray);
?>

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
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

<section class="dashboard-main">
    <!--<div class="container">-->
    <div class="row">
        <div class="col-md-9 col-xs-12">
            <div class="current-boxs">
                <h4>CURRENT SCORES</h4>
                <div class="current-box active">
                    <span class="icon-love"></span>
                    <h6>Your average is <span><?= round($customer_love, 2); ?>%</span></h6>
                </div>
                <div class="current-box">
                    <span class="icon-cred"></span>
                    <h6>Your average is <span><?= round($t_love, 2); ?>%</span></h6>
                </div>
                <div class="current-box">
                    <span class="icon-mucle"></span>
                    <h6>Your average is <span>00%</span></h6>
                </div>
                <div class="weeks"><img src="<?= base_url('assets/images/images/arrow.png'); ?>"> You are here.To achieve <span>STARTER 02,</span> YOU NEED TO HAVE AN AVERAGE OF <span>70% CUSTOMER LOVE</span> over the next 6 weeks. Click here to to get a full picture of how to progress the fastest.</div>
            </div>

            <div class="starter-list">
                <ul>
                    <li><a href="#">STARTER 01</a></li>
                    <li><a href="#">STARTER 02</a></li>
                    <li><a href="#">INTERMEDIATE 01</a></li>
                    <li><a href="#">INTERMEDIATE 02</a></li>
                    <li><a href="#">ADVANCED 01</a></li>
                    <li><a href="#">ADVANCED 02</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-3 col-xs-12">
                    <div class="weekly-box">
                        <h4>You're scheduled to assess on:</h4>
                        <div class="progress-box">
                            <?php
                            if (sizeof($scheduled) > 0) {
                                foreach ($scheduled as $row) {
                                    ?>
                                    <div class="col-md-6 col-xs-12 text-orange text-smaller text-semibold">
                                        <?= date('l dS', strtotime($row->date)) . '<br>' . $row->from_time . ' - ' . $row->to_time; ?>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<span class="text-orange text-semibold">Not scheduled yet.</span>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="weekly-box">
                        <h4>My Weekly Progress:</h4>
                        <div class="progress-box">
                            <div class="circle" id="circle-a">
                                <div class="circle second-circle" id="circle-b">
                                    <div class="circle second-circle" id="circle-c">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12 margin-top">
                                <div class="block-inner text-smaller">
                                    <label class="checkbox-inline">
                                        <button class="btn btn-icon btn-sm btn-danger"></button> SELF
                                    </label>
                                    <label class="checkbox-inline">
                                        <button class="btn btn-icon btn-sm btn-info"></button> PEER
                                    </label>
                                    <label class="checkbox-inline">
                                        <button class="btn btn-icon btn-sm btn-orange"></button> CLD
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script type="text/javascript">
                        /**
                         *Exampe from https://kottenator.github.io/jquery-circle-progress/
                         */
                        var progressBarOptions = {
                            startAngle: -1.55,
                            lineCap: 'round',
                            fill: {
                                color: '#f26a6a'
                            }
                        };
                        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function (event, progress, stepValue) {
                            $(this).find('strong').text(String(stepValue.toFixed(2)).substr(1));
                        });
                    </script>

                    <div class="weekly-box">
                        <h4>Squad Progress:</h4>
                        <div class="progress-box">
                            <?php
                            foreach ($members as $u) {
                                $noCount = 0;
                                $percentage = 0;
                                $peer = 0;
                                ?>

                                <div class="squad-box">
                                    <span><?= $u->first_name; ?></span>
                                    <div class="circle circle-d<?= $u->id ?>">
                                        <div class="circle circle-e<?= $u->id ?> squad-second-circle">
                                            <div class="circle circle-f<?= $u->id ?> squad-second-circle">
                                            </div>
                                        </div>
                                    </div>						
                                </div>

                                <?php
                                foreach ($members as $m) {
                                    $one_ass = check_oneassessment(array('sender_id' => $u->id, 'user_id' => $m->id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));

                                    $isDone = 0;
                                    foreach ($type as $t) {
                                        if ($one_ass) {
                                            if ($one_ass->type_id != $t->type_id) {
                                                $is_rating = get_israting_helper(array('w.sender_id' => $u->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                                if ($is_rating) {
                                                    $isDone = sizeof($type);
                                                    $noCount = $noCount + sizeof($type);
                                                    if ($u->id == $m->id) {
                                                        $noCount = $noCount - sizeof($type);
                                                    }
                                                }
                                            }
                                        } else {
                                            $is_rating = get_israting_helper(array('w.sender_id' => $u->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                            if ($is_rating) {
                                                $isDone++;
                                                $noCount++;
                                                if ($u->id == $m->id) {
                                                    $noCount--;
                                                }
                                            }
                                        }
                                    }

                                    if ($this->logged_in_id == $u->id && $u->id == $m->id) {
                                        if ($isDone > 0) {
                                            $selfPercentage = ($isDone / sizeof($type)) * 100;
                                        }
                                    }

                                    if ($u->id == $m->id) {
                                        if ($isDone > 0) {
                                            $percentage = ($isDone / sizeof($type)) * 100;
                                        }
                                    }
                                }

                                if ($this->logged_in_id == $u->id) {
                                    if ($noCount > 0) {
                                        $total = sizeof($members) * sizeof($type);
                                        $minus = sizeof($type) + $active;
                                        $peerPercentage = ($noCount / ($total - $minus)) * 100;
                                    }
                                }

                                //$peer = ($noCount / (sizeof($members) - 1) ) * 100;

                                if ($noCount > 0) {
                                    $total = sizeof($members) * sizeof($type);
                                    $minus = sizeof($type) + $active;
                                    $peer = ($noCount / ($total - $minus)) * 100;
                                }
                                ?>

                                <script type="text/javascript">
                                    $('.circle-d<?= $u->id ?>').circleProgress({
                                        size: 100,
                                        lineCap: 'round',
                                        startAngle: -1.55,
                                        value: '<?= isset($percentage) ? $percentage / 100 : '0.0'; ?>',
                                        thickness: 5,
                                        fill: {
                                            color: '#f26a6a'
                                        }
                                    });
                                    $('.circle-e<?= $u->id ?>').circleProgress({
                                        size: 80,
                                        lineCap: 'round',
                                        startAngle: -1.55,
                                        value: '<?= isset($peer) ? $peer / 100 : '0.0'; ?>',
                                        thickness: 5,
                                        fill: {
                                            color: '#50ABC2'
                                        }
                                    });
                                    $('.circle-f<?= $u->id ?>').circleProgress({
                                        size: 60,
                                        lineCap: 'round',
                                        startAngle: -1.55,
                                        value: 0.0,
                                        thickness: 5,
                                        fill: {
                                            color: 'orange'
                                        }
                                    });
                                </script>

                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-9 col-xs-12">

                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="performers-boxs">
                                <h4>Top & Bottom Performers:</h4>
                                <div class="performers-box">
                                    <div class="img">
                                        <img alt="Top Performer" class="img-media" src="<?= base_url('profile/' . $maxarray['profile']); ?>" onerror="this.src='<?= base_url('assets/images/preview.jpg'); ?>';">
                                    </div>
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/gold.png'); ?>">
                                    </div>
                                    <span><?= $maxarray['name']; ?></span>

                                    <span class="perfo-text pull-right"><?= round($maxarray['rating'], 2) ?>%</span>
                                    <span class="icon pull-right">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                </div>
                                <div class="performers-box">
                                    <div class="img">
                                        <img alt="Bottom Performer" class="img-media" src="<?= base_url('profile/' . $minarray['profile']); ?>" onerror="this.src='<?= base_url('assets/images/preview.jpg'); ?>';">
                                    </div>
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                    </div>
                                    <span><?= $minarray['name']; ?></span>

                                    <span class="perfo-text pull-right"><?= round($minarray['rating'], 2) ?>%</span>
                                    <span class="icon pull-right">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                </div>
                            </div>
                            <div class="performers-boxs">
                                <h4>Top & Bottom Squad:</h4>
                                <div class="performers-box">
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/gold.png'); ?>">
                                    </div>
                                    <span class="sqauds-text"><?= $firsttribe['squad_name']; ?></span>

                                    <span class="perfo-text pull-right"><?= round($firsttribe['rating'], 2); ?>%</span>
                                    <span class="icon pull-right">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                </div>
                                <div class="performers-box">
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                    </div>
                                    <span class="sqauds-text"><?= $lasttribe['squad_name']; ?></span>

                                    <span class="perfo-text pull-right"><?= round($lasttribe['rating'], 2); ?>%</span>
                                    <span class="icon pull-right">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                </div>
                            </div>
                            <div class="says-box">
                                <div class="img">
                                    <img src="<?= base_url('assets/images/images/gril.png'); ?>">
                                </div>
                                <h5>Sensei Says:</h5>
                                <p>
                                    Remember to take time this week to reflect on the way you show up to the customer when you are tired or worn out...
                                </p> 
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="squadsession-boxs">
                                <h4>Prep for Squad Session:</h4>
                                <div class="squadsession-box">
                                    <div class="top">
                                        <h5>Problems with card</h5>
                                        <span class="start">5/8 <img src="<?= base_url('assets/images/images/start-icon.png'); ?>"></span>
                                        <div class="img">
                                            <img src="<?= base_url('assets/images/images/performers-img.png'); ?>">
                                        </div>
                                    </div>
                                    <div class="content">
                                        I’ve noticed a lot of problems with the way our customers...
                                    </div>
                                </div>
                                <div class="squadsession-box">
                                    <div class="top">
                                        <h5>Problems with card</h5>
                                        <span class="start">5/8 <img src="<?= base_url('assets/images/images/start-icon.png'); ?>"></span>
                                        <div class="img">
                                            <img src="<?= base_url('assets/images/images/performers-img.png'); ?>">
                                        </div>
                                    </div>
                                    <div class="content">
                                        I’ve noticed a lot of problems with the way our customers...
                                    </div>
                                </div>
                                <div class="additem">
                                    <a href="#">+ Add item to discuss</a>
                                </div>
                            </div>
                            <div class="buckets-boxs">
                                <h4>Top 4 buckets from other Squad this week:</h4>
                                <div class="buckets-box">
                                    <div class="itmes-box">
                                        <img src="<?= base_url('assets/images/images/dol-icon.png'); ?>">
                                        <span>SLA times</span>
                                    </div>
                                    <div class="itmes-box">
                                        <img src="<?= base_url('assets/images/images/dol-icon.png'); ?>">
                                        <span>Stop card</span>
                                    </div>
                                    <div class="itmes-box">
                                        <img src="<?= base_url('assets/images/images/dol-icon.png'); ?>">
                                        <span>Air Con</span>
                                    </div>
                                    <div class="itmes-box">
                                        <img src="<?= base_url('assets/images/images/dol-icon.png'); ?>">
                                        <span>SBSS</span>
                                    </div>
                                    <div class="other-box">
                                        <p>Other buckets being named include: xxx, zzz, bbb...</p>
                                        <a href="#">Add bucket <img src="<?= base_url('assets/images/images/dol-icon2.png'); ?>"></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <div class="eacteach-boxs">
                                <h3>Each One Teach One:</h3>
                                <h5>This week, what I need to learn or work on is:</h5>
                                <div class="eacteach-box">
                                    <h6>I need to learn to be more considerate</h6>
                                    <p>This week, I am going to work on being more considerate to my fellow tribe members when planning my breaks.</p>
                                    <span class="name-text">My mentor is: <span class="name">Bongile Malefe</span></span>
                                </div>
                                <h4>What I am teaching is:</h4>
                                <div class="eacteach-box">
                                    <h6>I need to learn to be more considerate</h6>
                                    <p>The ability to toggle between screens and get it right.</p>
                                    <span class="name-text">My mentor is: <span class="name">Bongile Malefe</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <div id="chartContainer1" style="height: 325px; width: 100%;background-image:url('<?= base_url('assets/images/Graph1.png'); ?>');"></div>
                        </div>
                        <div class="col-xs-12 margin-top">
                            <div id="chartContainer" style="height: 325px; width: 100%;background-image:url('<?= base_url('assets/images/Graph.png'); ?>');"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-xs-12">
            <div class="whatneed-main">
                <h3>What I need to know <span>My feed</span></h3>
                <?php
                foreach ($groups as $g) {
                    $newsfeed = $this->backend->messages(array('group_id' => $g->id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year), $this->logged_in_id);
                    if ($newsfeed) {
                        ?>
                        <div class="whatneed-box">
                            <div class="top">
                                <h4><?= $g->description; ?></h4>
                                <img src="<?= base_url('assets/images/images/performers-img.png'); ?>">
                            </div>
                            <?php
                            foreach ($newsfeed as $news) {
                                if ($news->sender_id != $this->logged_in_id) {
                                    ?>
                                    <div class="content">
                                        <?php if ($g->id == 3) { ?>
                                            <p class="more red"><strong><?= get_firstname_helper(array('id' => $news->sender_id)); ?>: </strong><?= $news->message; ?> </p>
                                        <?php } else { ?>
                                            <span><?= $news->title; ?></span>
                                            <?= ($news->sub_title != NULL) ? '<h5>' . $news->sub_title . '</h5>' : ''; ?>
                                            <p class="more"><?= $news->message; ?> </p>
                                        <?php } ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>

            </div>
        </div>

    </div>
    <!--</div>-->
</section>
<br>

<?php
$dataPoints4 = array(
    array("y" => 6, "label" => "Apple"),
    array("y" => 4, "label" => "Mango"),
    array("y" => 5, "label" => "Orange"),
    array("y" => 7, "label" => "Banana"),
    array("y" => 4, "label" => "Pineapple"),
    array("y" => 6, "label" => "Pears"),
    array("y" => 7, "label" => "Grapes"),
    array("y" => 5, "label" => "Lychee"),
    array("y" => 4, "label" => "Jackfruit")
);
?>

<script type="text/javascript">
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            backgroundColor: 'transparent',
            animationEnabled: true,
            legend: {
                dockInsidePlotArea: true,
                verticalAlign: "top",
                horizontalAlign: "center",
                fontSize: 12
            },
            title: {
                text: "Perception Gap = 00.0%",
                fontSize: 14,
                fontFamily: "tahoma",
                horizontalAlign: "right",
                padding: 10,
                fontWeight: "normal"
            },
            axisY: {
                lineDashType: "dash",
                gridColor: "transparent",
                minimum: -20,
                interval: 20
            },
            axisX: {
                labelBackgroundColor: "#6aa2cd",
                labelFontFamily: "tahoma",
                labelFontColor: "white",
                labelFontSize: 14,
                padding: 10,
                labelFontWeight: "Normal",
                lineDashType: "dash"
            },
            data: [
                {
                    type: "line",
                    lineColor: "red",
                    showInLegend: true,
                    legendText: "Result",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "red",
                    axisXIndex: 0,
                    markerColor: "red",
                    dataPoints: <?php echo json_encode(array_reverse($datapoints), JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "line",
                    lineColor: "#445874",
                    showInLegend: true,
                    legendText: "Goal",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "#445874",
                    axisXIndex: 1, //defaults to 0
                    markerColor: "#445874",
                    dataPoints: <?php echo json_encode(array_reverse($datapoints2), JSON_NUMERIC_CHECK); ?>
                }]
        });
        chart.render();
        var chart1 = new CanvasJS.Chart("chartContainer1", {
            backgroundColor: 'transparent',
            animationEnabled: true,
            theme: "light1",
            legend: {
                dockInsidePlotArea: true,
                verticalAlign: "top",
                horizontalAlign: "center",
                fontSize: 12
            },
            title: {
                text: "Customer Love Average = 00.0%",
                fontSize: 14,
                fontFamily: "tahoma",
                horizontalAlign: "right",
                margin: 10,
                padding: 10,
                fontWeight: "normal"
            },
            axisY: {
                lineDashType: "dash",
                gridColor: "transparent",
                minimum: -20,
                interval: 20
            },
            axisX: {
                labelBackgroundColor: "#c3c4c5",
                labelFontColor: "white",
                labelFontSize: 14,
                labelFontWeight: "bold",
                lineDashType: "dash",
            },
            data: [
                {
                    type: "line",
                    lineColor: "red",
                    showInLegend: true,
                    legendText: "Result",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "red",
                    axisXIndex: 0,
                    markerColor: "red",
                    dataPoints: <?php echo json_encode(array_reverse($datapoints3), JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "line",
                    lineColor: "#445874",
                    showInLegend: true,
                    legendText: "Goal",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "#445874",
                    axisXIndex: 1, //defaults to 0
                    markerColor: "#445874",
                    dataPoints: <?php echo json_encode(array_reverse($datapoints2), JSON_NUMERIC_CHECK); ?>
                }]
        });
        chart1.render();
        $('#circle-a').circleProgress({
            size: 150,
            value: '<?= isset($selfPercentage) ? $selfPercentage / 100 : '0'; ?>',
            lineCap: 'round',
            thickness: 8,
            fill: {
                color: '#f26a6a'
            }
        });
        $('#circle-b').circleProgress({
            size: 120,
            value: '<?= isset($peerPercentage) ? $peerPercentage / 100 : '0'; ?>',
            thickness: 8,
            fill: {
                color: '#50ABC2'
            }
        });
        $('#circle-c').circleProgress({
            size: 90,
            value: 0.0,
            thickness: 8,
            fill: {
                color: 'orange'
            }
        });

        $("#chartContainer1 a").remove();
        $("#chartContainer a").remove();
    };

    $(document).ready(function () {

        // Configure/customize these variables.
        var showChar = 50; // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more >";
        var lesstext = "Show less";
        $('.more').each(function () {
            var content = $(this).html();
            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);
                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="<?= site_url('squad/learning'); ?>" class="">' + moretext + '</a></span>';
                $(this).html(html);
            }

        });
        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    });
</script>