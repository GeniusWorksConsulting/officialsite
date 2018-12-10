<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3><?= $row->name; ?> Assessment</h3>
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

<div class="row">
    <div id="mainDiv" class="col-md-9">
        <?php if ($is_given) { ?>
            <script type="text/javascript">
                $('#mainDiv').css('pointer-events', 'none');
            </script>
            <!-- Message -->
            <div class="callout callout-danger fade in">
                <p>You already given rating to <?= $receiver_data->first_name . ' ' . $receiver_data->last_name; ?></p>
            </div>
        <?php } ?>

        <?php
        echo form_open('squad/addrating');
        ?>
        <input type="hidden" name="sender_id" value="<?= $this->logged_in_id; ?>">
        <input type="hidden" name="receiver_id" value="<?= $receiver_data->id; ?>">
        <input type="hidden" name="assessment" value="<?= $row->assessment; ?>">

        <?php
        foreach ($categories as $cat) {
            $questions = get_questions_helper($cat->cat_id);
            ?>
            <input type="hidden" name="cat_id[]" value="<?= $cat->cat_id; ?>">
            <div class="bg-success with-padding block-inner">
                <?= $cat->name; ?>
            </div> 
            <br>
            <?php
            foreach ($questions as $que) {
                $answers = get_answers_helper($que->question_id);
                ?>
                <div class="form-group">
                    <label class="h6"><?= $que->description; ?></label>
                    <p class="text-orange"><?= $que->evaluation; ?></p>
                    <input type="hidden" name="count[]" value="<?= $que->count; ?>">
                    <input type="hidden" name="has_child[]" value="<?= $que->has_child; ?>">
                    <input type="hidden" name="question_id[]" value="<?= $que->question_id; ?>">
                    <select class="form-control <?= ($que->has_child == 1) ? 'has_child' : ''; ?>" name="answer[]" id="<?= $que->question_id; ?>">
                        <option value="">Select</option>
                        <?php foreach ($answers as $ans) { ?>
                            <option id="<?= $ans->answer_id; ?>" value="<?= $ans->answer_id; ?>"><?= $ans->answer; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?= ($que->has_child == 1) ? '<div id="nextQuestion' . $que->question_id . '"></div>' : ''; ?>
            <?php }
            ?>
        <?php } ?>

        <div class="form-group text-right">
            <button class="btn btn-info btn-sm" name="btnSubmit" type="submit">Submit</button>
        </div>
        <?php echo form_close(); ?>
    </div>

    <div class="col-md-3">

        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="bg-primary realtime-stats">
                        <div class="row">
                            <div class="col-md-12  text-center">
                                <h6><?= $receiver_data->first_name . ' ' . $receiver_data->last_name; ?></h6>
                            </div>
                        </div>
                    </div>

                    <div class="section-details text-center container-fluid">
                        <div class="row">
                            <?php
                            foreach ($type as $t) {
                                ?>
                                <div class="col-xs-6">
                                    <a <?= ($t->rating == 0) ? 'onclick="assessment(' . $receiver_data->id . ',' . $t->type_id . ')"' : ''; ?> class="btn btn-block btn-sm <?= ($t->rating == 0) ? 'btn-default' : 'btn-success disabled'; ?>"><?= $t->type; ?></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center text-success">
                <h6>Your score for <?= $receiver_data->first_name . ' ' . $receiver_data->last_name; ?></h6>
                <hr>
            </div>
            <?php foreach ($type as $t) { ?>
                <div class="col-md-6">
                    <!-- Task -->
                    <div class="block task task-normal">
                        <div class="row with-padding">
                            <div class="col-sm-12">
                                <button class="btn btn-sm disabled btn-block <?= ($t->rating == 0) ? 'btn-default' : 'btn-success'; ?>">
                                    <?= $t->rating; ?>%
                                </button>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <div class="">
                                <button class="btn btn-default btn-block disabled"> <?= $t->type; ?></button>
                            </div>
                        </div>
                    </div>
                    <!-- /task -->
                </div>
            <?php } ?>
        </div>

    </div>
</div>


<!-- Small modal -->
<div id="small_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Assessment</h4>
            </div>

            <div class="modal-body with-padding div_assessment">

            </div>
        </div>
    </div>
</div>
<!-- /small modal -->