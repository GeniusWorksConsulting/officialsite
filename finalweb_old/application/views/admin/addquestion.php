<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('admin/viewquestion') ?>">View Questions</a></li>
        <li class="active">Add Question</li>
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

<!-- Message -->
<?php if (form_error('parent_id')) { ?>
    <div id="msg" class="callout callout-danger fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo form_error('parent_id'); ?></p>
    </div>
<?php } ?>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'questionForm', 'name' => 'questionForm', 'class' => 'form-bordered');
                echo form_open_multipart('admin/saveQuestion', $attributes);
                ?>

                <?= isset($row) ? '<input type="hidden" name="question_id" value="' . $row->question_id . '">' : ''; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Category *</label>
                            <select class="form-control" id="cat_id" name="cat_id" autofocus tabindex="1">
                                <option value="">Select</option>
                                <?php foreach ($list as $c) { ?>
                                    <option value="<?= $c->cat_id; ?>"><?= $c->name; ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('cat_id'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Question *</label>
                            <input type="text" name="description" value="<?= isset($row) ? $row->description : ''; ?>" class="form-control" tabindex="2">
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Evaluation Criteria</label>
                            <textarea name="evaluation" class="form-control" tabindex="3"><?= isset($row) ? $row->evaluation : ''; ?></textarea>
                            <span class="text-danger"><?php echo form_error('evaluation'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Is Parent *</label>
                            <select class="form-control" id="is_parent" name="is_parent" tabindex="4">
                                <option value="0">Parent</option>
                                <option value="1">Child</option>
                            </select>
                            <?php echo form_error('is_parent'); ?>
                        </div>

                        <div class="col-sm-3">
                            <label>Has Child *</label>
                            <select class="form-control" id="has_child" name="has_child" tabindex="5">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <span class="text-danger"><?php echo form_error('has_child'); ?></span>
                        </div>

                        <div class="col-sm-3">
                            <label>Weighting</label>
                            <input type="text" name="weight" value="<?= isset($row) ? $row->weight : ''; ?>" class="form-control allow_number" tabindex="6">
                            <span class="text-danger"><?php echo form_error('weight'); ?></span>
                        </div>
                        <div class="col-sm-3">
                            <label>No of Answer *</label>
                            <select class="form-control no_answer" id="no_answer" name="no_answer" tabindex="7">
                                <option value="">Select</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <span class="text-danger"><?php echo form_error('no_answer'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="isChild"></div>
                <div class="isCount"></div>
                <div class="allanswers"></div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button name="btn-save" type="submit"  class="btn btn-info" tabindex="10"><?= isset($row) ? 'UPDATE' : 'SUBMIT'; ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php if (isset($row)) { ?>
    <script type="text/javascript">
        $('#cat_id').val('<?= $row->cat_id; ?>');
    </script>
<?php } ?>