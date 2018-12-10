<div class="row">
    <div class="col-md-12">

        <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
            <ul class="info-blocks">
                <?php
                foreach ($members as $m) {
                    ?>
                    <li class="bg-default" <?= ($m->is_paused == 1) ? 'style="pointer-events: none;"' : ''; ?>>
                        <a href="<?= site_url('users/user_details/' . $m->id); ?>">
                            <div class="top-info">
                                <span class="limitCharacter" href="#"><?= $m->first_name . ' ' . $m->last_name; ?></span>
                                <p class="text-success text-semibold" id="<?= $m->id; ?>">00.00 %</p>
                            </div>
                        </a>
                        <img alt="Profile" class="media-object thumbnail-boxed margin-bottom <?= ($m->is_paused == 1) ? 'border-red' : ''; ?>" src="<?= base_url('profile/' . $m->profile); ?>" onerror="this.src='<?= base_url('assets/images/user.png'); ?>';">

                        <?php
                        $isDone = 0;
                        foreach ($type as $t) {
                            $is_rating = get_israting_helper(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $t->type_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                            ?>
                            <a id="<?= $t->type_id . $m->id; ?>" <?= ($isClaim) ? 'onclick="qa_assessment(' . $m->id . ', ' . $t->type_id . ')"' : ''; ?> class="bottom-info <?= ($is_rating) ? 'bg-success' : 'bg-primary' ?>"><?= ($is_rating) ? $is_rating->rating : $t->type; ?></a>
                            <?php
                            if ($is_rating) {
                                $isDone++;
                                ?>
                                <script type="text/javascript">
                                    $('#<?= $t->type_id . $m->id; ?>').removeAttr('onclick');
                                    $('#<?= $t->type_id . $m->id; ?>').removeClass('bg-success');
                                    $('#<?= $t->type_id . $m->id; ?>').addClass('bg-info');
                                    $('#<?= $t->type_id . $m->id; ?>').html('<?= $is_rating->rating; ?>');
                                </script>
                                <?php
                            }
                        }

                        if ($isDone == sizeof($type)) {
                            $rating = get_checkassessment_helper(array('w.sender_id' => $this->logged_in_id, 'w.receiver_id' => $m->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                            ?>
                            <script type="text/javascript">
                                $('#<?= $m->id; ?>').html('<?= $rating->rating; ?> %');
                            </script>
                        <?php }
                        ?>
                    </li>
                <?php } ?>
            </ul>
            <?php
        }
        ?>
        
    </div>
</div>