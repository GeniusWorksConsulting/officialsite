<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Home</a></li>
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
        foreach ($squadList as $s) {
            $members = get_squad_helper($s->squad_group);
            ?>
            <div class = "panel panel-default">
                <div class = "panel-body">
                    <div class="col-md-12">
                        <u><h6 class="text-semibold"><?= get_squadgroup_helper(array('squad_group' => $s->squad_group)); ?></h6></u>
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
                                            $is_rating = get_israting_helper(array('w.sender_id' => $currentWeek['user_id'], 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $currentWeek['week'], 'w.month' => $currentWeek['month'], 'w.year' => $currentWeek['year']));
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

    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tr>
                        <th>QA Member</th>
                        <td><?= get_firstname_helper(array('id' => $currentWeek['user_id'])); ?></td>
                    </tr>

                    <tr>
                        <th>Assessment</th>
                        <td><?= date("F", mktime(0, 0, 0, $currentWeek['month'], 1, 2011)) . ' ' . $currentWeek['year'] . ' (' . 'Week ' . $currentWeek['week'] . ')'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>