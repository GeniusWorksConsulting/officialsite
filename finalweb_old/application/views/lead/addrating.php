<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3><?= $row->name; ?> Assessment</h3>
    </div>
</div>
<!-- /page header -->

<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('lead/index') ?>">Home</a></li>
        <li>Add Rating</li>
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
        echo form_open('lead/addrating');
        ?>
        <input type="hidden" name="sender_id" value="<?= $this->logged_in_id; ?>">
        <input type="hidden" name="receiver_id" value="<?= $receiver_data->id; ?>">
        <input type="hidden" name="squad_group" value="<?= $receiver_data->squad_group; ?>">
        <input type="hidden" name="assessment" value="<?= $row->assessment; ?>">

        <?php
        foreach ($categories as $cat) {
            $questions = get_questions_helper($cat->cat_id);
            ?>
            <div class="bg-success with-padding block-inner">
                <?= $cat->name; ?>
            </div> 

            <?php
            foreach ($questions as $que) {
                $answers = get_answers_helper($que->question_id);
                ?>
                <div class="form-group">
                    <label><?= $que->description ?></label>
                    <input type="hidden" name="count[]" value="<?= $que->count; ?>">
                    <input type="hidden" name="has_child[]" value="<?= $que->has_child; ?>">
                    <input type="hidden" name="question_id[]" value="<?= $que->question_id; ?>">
                    <select class="form-control <?= ($que->has_child == 1) ? 'has_child' : ''; ?>" name="answer[]" id="<?= $que->question_id; ?>">
                        <option value="">Select</option>
                        <?php foreach ($answers as $ans) { ?>
                            <option id="<?= $ans->answer_id; ?>" value="<?= $ans->weighting; ?>"><?= $ans->answer; ?></option>
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

    <div class="col-md-4">

        <div class="row">
            <div class="col-md-12">
                <div class="block">
                    <div class="bg-primary realtime-stats">
                        <div class="row">
                            <div class="col-md-3">
                                <i style="font-size: 30px;" class="icon-user3"></i>
                            </div>
                            <div class="col-md-9">
                                <h3><?= $receiver_data->first_name . ' ' . $receiver_data->last_name; ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="section-details text-center container-fluid">
                        <div class="row">
                            <?php foreach ($type as $t) { ?>
                                <div class="col-xs-6">
                                    <a onclick="assessment(<?= $receiver_data->id; ?>, <?= $t->type_id; ?>);" class="btn btn-default btn-block btn-lg"><?= $t->type; ?></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center text-success">
                <h4>Your score for <?= $receiver_data->first_name . ' ' . $receiver_data->last_name; ?></h4>
                <hr>
            </div>
            <?php foreach ($type as $t) { ?>
                <div class="col-md-6">
                    <!-- Task -->
                    <div class="block task task-normal">
                        <div class="row with-padding">
                            <div class="col-sm-12">
                                <button class="btn btn-info btn-lg btn-block">
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