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
        <div class="col-lg-12">

            <!-- Default tabs -->
            <div class="well block">
                <div class="tabbable">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#default-tab1" data-toggle="tab">Dashboard</a></li>
                        <li class=""><a href="#default-tab2" data-toggle="tab">Assessments</a></li>
                        <li class=""><a href="#default-tab3" data-toggle="tab">Squad Sessions</a></li>
                        <li><a href="#default-tab4" data-toggle="tab">My Performance</a></li>
                    </ul>

                    <div class="tab-content with-padding">

                        <!-- Tab1 -->
                        <div class="tab-pane fade active in" id="default-tab1">
                            <div class="row">
                                <div class="col-md-9 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">

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
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="squadsession-boxs">
                                                        <h4>Success and Failures to look out for:</h4>
                                                        <div class="squadsession-box">
                                                            <div class="top">
                                                                <div class="performers-boxs">
                                                                    <h4></h4>
                                                                    <div class="performers-boxs">
                                                                        <div class="performers-box">
                                                                            <div class="gold">
                                                                                <img src="<?= base_url('assets/images/images/gold.png'); ?>">
                                                                            </div>
                                                                            <span>Authentication</span>
                                                                        </div>
                                                                        <div class="performers-box">
                                                                            <div class="gold">
                                                                                <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                                                            </div>
                                                                            <span>Call Rating</span>
                                                                        </div>
                                                                        <div class="performers-box">
                                                                            <div class="gold">
                                                                                <img src="<?= base_url('assets/images/images/boom.png'); ?>">
                                                                            </div>
                                                                            <span>View Full List of Trends</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
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

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <?php foreach ($squad_list as $s) { ?>
                                            <div class="col-md-2 col-xs-12">
                                                <div class="weekly-box">
                                                    <div class="progress-box">
                                                        <div class="col-md-12 text-semibold">
                                                            <?= $s->squad_name; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }
                                        ?>
                                    </div>
                                </div>

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

                            </div>
                        </div>

                        <!-- Tab2 -->
                        <div class="tab-pane fade" id="default-tab2">
                            Assessment Tab
                        </div>

                        <!-- Tab3 -->
                        <div class="tab-pane fade" id="default-tab3">
                            Session Tab
                        </div>

                        <!-- Tab4 -->
                        <div class="tab-pane fade" id="default-tab4">
                            Performance Tab
                        </div>

                    </div>
                </div>
            </div>
            <!-- /default tabs -->

        </div>
    </div>

</section>
<br>

<script type="text/javascript">
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