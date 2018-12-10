<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
<?php } ?>
<br>

<section class="dashboard-main">
    <!--<div class="container">-->
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-xs-12">
            <div class="whatneed-main">
                <h3>Learning and Reflection</h3>
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
                            <?php foreach ($newsfeed as $news) {
                                ?>
                                <div class="content">
                                    <?php if ($g->id == 3) { ?>
                                        <p class="more <?= ($news->sender_id == $this->logged_in_id) ? 'red-ligt' : 'red'; ?>"><strong><?= get_firstname_helper(array('id' => $news->sender_id)); ?>: </strong><?= $news->message; ?> </p>
                                    <?php } else { ?>
                                        <span><?= $news->title; ?></span>
                                        <?= ($news->sub_title != NULL) ? '<h5>' . $news->sub_title . '</h5>' : ''; ?>
                                        <p class="more"><?= $news->message; ?> </p>
                                    <?php } ?>
                                </div>
                            <?php }
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
<script type="text/javascript">
    $(document).ready(function () {
        // Configure/customize these variables.
        var showChar = 100;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Show more...";
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