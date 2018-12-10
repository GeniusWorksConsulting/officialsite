<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script src="<?= base_url('assets/js/circle-progressbar.js'); ?>"></script>
<br>
<?php
$customer_love = 0;
$tribe_love = 0;
$active = 0;

foreach ($members as $a) {
    if ($a->is_paused == 1) {
        $active++;
    }
}

foreach ($members as $u) {
    $total_rating = 0;
    $ratings = get_getallassessment_helper(array('w.receiver_id' => $u->id, 'w.is_squad' => 0, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
    //var_dump($ratings);
    if (sizeof($ratings) > 0) {

        foreach ($ratings as $row) {
            $total_rating = $total_rating + $row->rating;
        }

        if ($u->id == $this->logged_in_id) {
            $customer_love = $total_rating / (sizeof($members) - $active);
        }
    }

    $tribe_love = $tribe_love + ($total_rating / (sizeof($members) - $active));
}

$minarray = array('rating' => 100, 'name' => '', 'profile' => '');
$maxarray = array('rating' => 0, 'name' => '', 'profile' => '');
$firsttribe = array('rating' => 0, 'squad_name' => '');
$lasttribe = array('rating' => 100, 'squad_name' => '');

foreach ($tribes as $t) {
    $tribe_total = 0;

    $condition = array('u.squad_group' => $t->squad_group, 'u.active' => 1);
    $users = get_activeusers_heper($condition, $week->week, $week->month, $week->year);

    if (sizeof($users) > 0) {
        foreach ($users as $u) {

            $total = 0;
            $ratings = get_getallassessment_helper(array('w.receiver_id' => $u->id, 'w.is_squad' => 0, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
            //var_dump($ratings);
            if (sizeof($ratings) > 0) {
                foreach ($ratings as $row) {
                    $total = $total + $row->rating;
                }
            }

            $rating = $total / (sizeof($users) - $active);
            $tribe_total = $tribe_total + $rating;

            if ($minarray['rating'] > $rating) {
                $minarray['rating'] = $rating;
                $minarray['profile'] = $u->profile;
                $minarray['name'] = $u->first_name . ' ' . $u->last_name;
            }

            if ($maxarray['rating'] < $rating) {
                $maxarray['rating'] = $rating;
                $maxarray['profile'] = $u->profile;
                $maxarray['name'] = $u->first_name . ' ' . $u->last_name;
            }
        }

        $tribe_average = $tribe_total / (sizeof($users) - $active);

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
                    <h6>Youraverage is <span><?= round($customer_love, 2); ?>%</span></h6>
                </div>
                <div class="current-box">
                    <span class="icon-cred"></span>
                    <h6>Youraverage is <span><?= round($tribe_love / (sizeof($members) - $active), 2); ?>%</span></h6>
                </div>
                <div class="current-box">
                    <span class="icon-mucle"></span>
                    <h6>Youraverage is <span>00%</span></h6>
                </div>
                <div class="weeks"><img src="<?= base_url('assets/images/images/arrow.png'); ?>"> You are here.To achieve <span>STARTER 02,</span> YOU NEED TO HAVE AN AVERGARE OF <span>70% CUSTOMER LOVE</span> over the next 6 weeks. Click here to to get a full picture of how to progress the fastest.</div>
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
                                echo '<span class="text-orange text-semibold">No scheduled found.</span>';
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
                                    $isDone = 0;

                                    foreach ($type as $t) {
                                        $is_rating = get_israting_helper(array('w.sender_id' => $u->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                                        if ($is_rating) {
                                            $isDone++;
                                        }
                                    }

                                    if ($this->logged_in_id == $u->id && $u->id == $m->id) {
                                        if ($isDone > 0) {
                                            $selfPercentage = ($isDone / sizeof($type)) * 100;
                                        }
                                    }

                                    if ($isDone == sizeof($type)) {
                                        $noCount++;
                                        if ($u->id && $u->id == $m->id) {
                                            $noCount--;
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
                                        $peerPercentage = ($noCount / (sizeof($members) - 1) ) * 100;
                                    }
                                }

                                $peer = ($noCount / (sizeof($members) - 1) ) * 100;

                                foreach ($qa_list as $qa) {
                                    
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
                                    <span class="icon">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                    <span class="perfo-text"><?= round($maxarray['rating'], 2) ?>%</span>
                                </div>
                                <div class="performers-box">
                                    <div class="img">
                                        <img alt="Bottom Performer" class="img-media" src="<?= base_url('profile/' . $minarray['profile']); ?>" onerror="this.src='<?= base_url('assets/images/preview.jpg'); ?>';">
                                    </div>
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                    </div>
                                    <span><?= $minarray['name']; ?></span>
                                    <span class="icon">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                    <span class="perfo-text"><?= round($minarray['rating'], 2) ?>%</span>
                                </div>
                            </div>
                            <div class="performers-boxs">
                                <h4>Top & Bottom Sqauds:</h4>
                                <div class="performers-box">
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/gold.png'); ?>">
                                    </div>
                                    <span class="sqauds-text"><?= $firsttribe['squad_name']; ?></span>
                                    <span class="icon">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                    <span class="perfo-text"><?= round($firsttribe['rating'], 2); ?>%</span>
                                </div>
                                <div class="performers-box">
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                    </div>
                                    <span class="sqauds-text"><?= $lasttribe['squad_name']; ?></span>
                                    <span class="icon">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                    <span class="perfo-text"><?= round($lasttribe['rating'], 2); ?>%</span>
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
                                <h4>Top 4 buckets from other Sqauds this week:</h4>
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
                            <div id="chartContainer1" style="height: 275px; width: 100%;"></div>
                        </div>
                        <div class="col-xs-12 margin-top">
                            <div id="chartContainer" style="height: 275px; width: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-xs-12">
            <div class="whatneed-main">
                <h3>What I need to know <span>My feed</span></h3>
                <?php if ($newsfeed) { ?>
                    <?php foreach ($newsfeed as $news) { ?>
                        <div class="whatneed-box">
                            <div class="top">
                                <h4><?= $news->title; ?></h4>
                                <img src="<?= base_url('assets/images/images/performers-img.png'); ?>">
                            </div>
                            <div class="content">
                                <h5><?= $news->sub_title; ?></h5>
                                <!--<h5><?= date('d-m-Y h:i A', strtotime($news->created)); ?>:</h5>-->
                                <p class="more"><?= $news->message; ?> </p>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="whatneed-box">
                        <div class="top">
                            <h4>No Found</h4>
                        </div>
                        <div class="content">
                            <h5><?= date('d-m-Y h:i A'); ?>:</h5>
                            <p>No feed found for you.</p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
    <!--</div>-->
</section>
<br>

<script type="text/javascript">
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer", {
            backgroundColor: '#B0E2FF',
            animationEnabled: true,
            theme: "light1",
            legend: {
                dockInsidePlotArea: true,
                verticalAlign: "top",
                horizontalAlign: "right",
                fontSize: 14
            },
            title: {
                text: "Perception Gap"
            },
            axisY: {
                lineColor: "red",
                title: "Perception Gap",
                minimum: -100,
                interval: 20,
                maximum: 100
            },
            axisX: {
                gridDashType: "dot",
                gridThickness: 2
            },
            data: [
                {
                    type: "line",
                    showInLegend: true,
                    lineColor: "red",
                    legendText: "Result",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "red",
                    axisXIndex: 0, //defaults to 0
                    markerColor: "red",
                    dataPoints: <?php echo json_encode(array_reverse($datapoints), JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "line",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "blue",
                    legendText: "Goal",
                    lineColor: "blue",
                    axisXIndex: 1, //defaults to 0
                    showInLegend: true,
                    markerColor: "blue",
                    dataPoints: <?php echo json_encode(array_reverse($datapoints2), JSON_NUMERIC_CHECK); ?>
                }]
        });
        chart.render();

        var chart1 = new CanvasJS.Chart("chartContainer1", {
            backgroundColor: 'rgba(90, 84, 85, 0.1)',
            animationEnabled: true,
            theme: "light1",
            legend: {
                dockInsidePlotArea: true,
                verticalAlign: "top",
                horizontalAlign: "right",
                fontSize: 12
            },
            title: {
                text: "Customer Love Average"
            },
            axisY: {
                lineColor: "red",
                title: "Customer Love",
                minimum: -100,
                interval: 20,
                maximum: 100
            },
            axisX: {
                gridDashType: "dot",
                gridThickness: 2
            },
            data: [
                {
                    type: "line",
                    showInLegend: true,
                    lineColor: "red",
                    legendText: "Result",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "red",
                    axisXIndex: 0, //defaults to 0
                    markerColor: "red",
                    dataPoints: <?php echo json_encode(array_reverse($datapoints3), JSON_NUMERIC_CHECK); ?>
                },
                {
                    type: "line",
                    legendMarkerType: "square",
                    legendMarkerBorderThickness: 5,
                    legendMarkerBorderColor: "blue",
                    legendText: "Goal",
                    lineColor: "blue",
                    axisXIndex: 1, //defaults to 0
                    showInLegend: true,
                    markerColor: "blue",
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
    }

    $(document).ready(function () {
        // Configure/customize these variables.
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more >";
        var lesstext = "Show less";


        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';

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