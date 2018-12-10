<script src="<?= base_url('assets/js/circle-progressbar.js'); ?>"></script>
<br>

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
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
                    <h6>Your average is <span>00.00%</span></h6>
                </div>
                <div class="current-box">
                    <span class="icon-cred"></span>
                    <h6>Your average is <span>00.00%</span></h6>
                </div>
                <div class="current-box">
                    <span class="icon-mucle"></span>
                    <h6>Your average is <span>00.00%</span></h6>
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
                            <div class="col-md-12 col-xs-12 text-orange text-smaller text-semibold">
                                29-10-2018 12:00 PM
                            </div>
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

                    <div class="weekly-box">
                        <h4>Squad Progress:</h4>
                        <div class="progress-box">

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
                                        <img alt="Top Performer" class="img-media" src="" onerror="this.src='<?= base_url('assets/images/preview.jpg'); ?>';">
                                    </div>
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/gold.png'); ?>">
                                    </div>
                                    <span>Chirag Kevadiya</span>

                                    <span class="perfo-text pull-right">00.00%</span>
                                    <span class="icon pull-right">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                </div>
                                <div class="performers-box">
                                    <div class="img">
                                        <img alt="Bottom Performer" class="img-media" src="" onerror="this.src='<?= base_url('assets/images/preview.jpg'); ?>';">
                                    </div>
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                    </div>
                                    <span>Bhavin Kevadiya</span>

                                    <span class="perfo-text pull-right">00.00%</span>
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
                                    <span class="sqauds-text">Genius Works</span>

                                    <span class="perfo-text pull-right">00.00%</span>
                                    <span class="icon pull-right">
                                        <img src="<?= base_url('assets/images/images/hart-icon.png'); ?>">
                                    </span>
                                </div>
                                <div class="performers-box">
                                    <div class="gold">
                                        <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                    </div>
                                    <span class="sqauds-text">SQUAD 5</span>

                                    <span class="perfo-text pull-right">00.00%</span>
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

        <!--col-md-3-->
        <div class="col-md-3 col-xs-12">
            
            <div class="whatneed-main">
                <h3>What I need to know <span>My feed</span></h3>

                <div class="whatneed-box">
                    <div class="top">
                        <h4>Description</h4>
                        <img src="<?= base_url('assets/images/images/performers-img.png'); ?>">
                    </div>
                    
                    <div class="content">
                        <p class="more red"><strong>Chirag Kevadiya</strong>: Comment here. </p>
                        <span>Title</span>
                        <h5>Sub Title</h5>
                        <p class="more">Comment here.</p>
                    </div>
                </div>
            </div>
            
        </div>
        <!--col-md-3-->

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