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
        $members = get_squad_helper($u->squad_group);
        ?>
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="callout callout-success fade in">
                        <h5><?= $u->first_name; ?> Dashboard - <?= get_squadgroup_helper(array('squad_group' => $u->squad_group)); ?></h5>
                        <p>I HAVE COMPLETED <span class="text-semibold" id="user<?= $u->id; ?>">0</span> OUT OF <?= sizeof($members); ?> ASSESSMENT FOR THIS WEEK</p>
                    </div>
                    <ul class="info-blocks">

                        <?php
                        $noCompleted = 0;
                        foreach ($members as $m) {
                            ?>
                            <li id="<?= $m->id; ?>" class="bg-default sminfo" data-toggle="popover" data-container="body" data-trigger="hover" data-html="true">
                                <div class="top-info">
                                    <span class="limitCharacter" href="#"><?= $m->first_name; ?></span>
                                    <p id="asmt<?= $u->id . $m->id; ?>" class="text-center text-success" id="">00.00 %</p>
                                </div>
                                <img alt="Profile" class="media-object thumbnail-boxed" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='../assets/images/user.png';">
                                <?php
                                $isDone = 0;
                                $avg = 0;
                                foreach ($type as $t) {
                                    ?>
                                    <?php
                                    $is_rating = get_israting_helper(array('w.sender_id' => $u->id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));

                                    if ($is_rating) {
                                        $isDone++;
                                        $avg = $avg + $is_rating->rating;
                                    }
                                }
                                ?>

                                <?php
                                if ($isDone == sizeof($type)) {
                                    $noCompleted++;
                                    ?>
                                    <script type="text/javascript">
                                        $('#asmt<?= $u->id . $m->id; ?>').html('<?= $avg / sizeof($type); ?> %');
                                    </script>
                                <?php }
                                ?>

                            </li>
                            <div class="hide" id="hover<?= $m->id; ?>">
                                <?= $m->first_name; ?>
                            </div>
                        <?php } ?>

                    </ul>
                </div>
            </div>

        </div>

        <script type="text/javascript">
            $('#user<?= $u->id; ?>').html('<?= $noCompleted; ?>');
        </script>
        <?php
        break;
    }
    ?>
</div>

<script>
    jQuery(function ($) {
        $("[data-toggle=popover]").each(function (i, obj) {

            $(this).popover({
                html: true,
                content: function () {
                    var id = $(this).attr('id');
                    return $('#hover' + id).html();
                }
            });

        });
    });
</script>
