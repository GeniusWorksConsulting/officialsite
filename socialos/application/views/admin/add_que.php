<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/question') ?>">Questions</a></li>
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
    <div class="col-sm-10 col-sm-offset-1 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'add_que', 'name' => 'add_que', 'class' => 'form-bordered');
                echo form_open_multipart('superadmin/add_que', $attributes);
                ?>

                <input type="hidden" name="question_id" value="<?= isset($row) ? $row->question_id : ''; ?>">
                <div class="form-group">
                    <div class="row">

                        <div class="col-md-3">
                            <label>Select Admin: *</label>
                            <select class="form-control" name="user_id" id="user_id" tabindex="1">
                                <option value="">-- Select --</option>
                                <?php foreach ($users as $u) { ?>
                                    <option value="<?= $u->id; ?>"><?= $u->first_name . ' ' . $u->last_name; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('user_id'); ?></span>
                        </div>

                        <div class="col-md-9">
                            <label>Category: *</label>
                            <select class="form-control" name="cat_id" id="cat_id" tabindex="2">
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('cat_id'); ?></span>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Question *</label>
                            <input type="text" name="description" value="<?= isset($row) ? $row->description : ''; ?>" class="form-control" tabindex="3">
                            <span class="text-danger"><?php echo form_error('description'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Evaluation Criteria</label>
                            <textarea name="evaluation" class="form-control" tabindex="4"><?= isset($row) ? $row->evaluation : ''; ?></textarea>
                            <span class="text-danger"><?php echo form_error('evaluation'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Is Parent *</label>
                            <select class="form-control" id="is_parent" name="is_parent" tabindex="5">
                                <option value="0">Parent</option>
                                <option value="1">Child</option>
                            </select>
                            <?php echo form_error('is_parent'); ?>
                        </div>

                        <div class="col-sm-3">
                            <label>Has Child *</label>
                            <select class="form-control" id="has_child" name="has_child" tabindex="6">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <span class="text-danger"><?php echo form_error('has_child'); ?></span>
                        </div>

                        <div class="col-sm-3">
                            <label>Weight</label>
                            <input type="text" name="weight" value="<?= isset($row) ? $row->weight : ''; ?>" class="form-control allow_number" tabindex="7">
                            <span class="text-danger"><?php echo form_error('weight'); ?></span>
                        </div>
                        <div class="col-sm-3">
                            <label>No of Answer *</label>
                            <select class="form-control no_answer" id="no_answer" name="no_answer" tabindex="8">
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
                            <button name="btn-save" type="submit"  class="btn btn-sm btn-primary"><?= isset($row) ? 'UPDATE' : 'SUBMIT'; ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#user_id').on('change', function () {
        var user_id = this.value;

        if (user_id) {
            $.ajax({
                type: "post",
                url: '<?= site_url('ajaxrequest/get_category'); ?>',
                data: {'user_id': user_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="cat_id"]').empty();
                        $('select[name="cat_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="cat_id"]').append('<option value="' + value.cat_id + '">' + value.cat_name + '</option>');
                        });
                    } else {
                        $('select[name="cat_id"]').empty();
                        alert(json['message']);
                    }
                },
                complete: function () {
                    $("#dv_loader").hide();
                }
            });
        }
    });

    $('.no_answer').on('change', function () {
        var number = this.value;
        if (number) {
            $('.allanswers').html('');
            var table = '<table class="table table-hover"><thead><tr><th width="50%">Answer</th><th width="10%">Rating</th><th width="10%">Weighting</th><th width="15%">Is Zero</th><th width="15%">Section</th></tr></thead><tbody class="detail"></tbody><table>';
            $('.allanswers').append(table);

            for (var i = 0; i < number; i++) {
                var tr = '<tr>' +
                        '<td><input type="text" class="form-control input-sm" name="answer[]" required></td>' +
                        '<td><input type="text" class="form-control input-sm allow_number" name="rating[]" required></td>' +
                        '<td><input type="text" class="form-control input-sm allow_number" name="weighting[]" required></td>' +
                        '<td><select class="form-control" name="is_zero[]"><option value="0">No</option><option value="1">Yes</option></select></td>' +
                        '<td><select class="form-control" name="section_zero[]"><option value="0">No</option><option value="1">Yes</option></select></td>' +
                        '</tr>';

                $('.detail').append(tr);
            }
        } else {
            $('.allanswers').html('');
        }
    });

    $('#is_parent').on('change', function () {
        var number = this.value;
        if (number === '1') {
            $.ajax({
                type: "POST",
                url: '<?= site_url('ajaxrequest/get_question'); ?>',
                dataType: "html",
                success: function (response) {
                    $('.isChild').html('');
                    console.log(response);
                    $('.isChild').html(response);
                }
            });
        } else {
            $('.isChild').html('');
        }
    });
    
    $('#has_child').on('change', function () {
        var number = this.value;
        if (number === '1') {
            var select = '<div class="form-group"><div class="row"><div class="col-sm-3"><label>Count Weight </label><div class="block-inner"><label class="radio-inline"><input name="count" type="radio"> Yes</label><label class="radio-inline"><input name="count" checked="checked" type="radio"> No</label></div></div></div></div>';
            $('.isCount').html(select);
        } else {
            $('.isCount').html('');
        }
    });
</script>