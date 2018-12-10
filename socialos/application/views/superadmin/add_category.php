<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url($this->group_name . '/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url($this->group_name . '/category') ?>">Category</a></li>
        <li class="active">Add/Edit</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-6 col-md-offset-3 col-xs-12">
        <?php if ($this->session->flashdata('message')) { ?>
            <div id="msg" class="callout callout-danger fade in text-semibold">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $this->session->flashdata('message'); ?>
            </div>
        <?php } ?>

        <h1 class="text-center">Add/Edit Category</h1>
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                $attributes = array('id' => 'category', 'name' => 'category', 'class' => 'form-bordered');
                echo form_open($this->group_name . '/add_category', $attributes);
                ?>

                <input type="hidden" name="cat_id" value="<?= isset($row) ? $row->cat_id : ''; ?>">
                <?php if ($this->ion_auth->in_group('superadmin')) { ?>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6">
                                <label>Select Admin: *</label>
                                <select class="form-control" name="user_id" id="user_id">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($users as $u) { ?>
                                        <option value="<?= $u->id; ?>"><?= $u->first_name . ' ' . $u->last_name; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger text-semibold"><?php echo form_error('user_id'); ?></span>
                            </div>

                            <div class="col-md-6">
                                <label>Sub Assessment: *</label>
                                <select class="form-control" name="sub_ass_id" id="sub_ass_id">
                                </select>
                                <span class="text-danger text-semibold"><?php echo form_error('sub_ass_id'); ?></span>
                            </div>

                        </div>
                    </div>
                <?php } ?>

                <?php if (!$this->ion_auth->in_group('superadmin')) { ?>
                    <div class="form-group">
                        <div class="row">

                            <div class="col-md-6">
                                <label>Sub Assessment: *</label>
                                <select class="form-control" name="sub_ass_id" id="sub_ass_id">
                                    <option value="">-- Select --</option>
                                    <?php foreach ($assessments as $a) { ?>
                                        <option value="<?= $a->sub_ass_id; ?>"><?= $a->name; ?></option>
                                    <?php } ?>
                                </select>
                                <span class="text-danger text-semibold"><?php echo form_error('sub_ass_id'); ?></span>
                            </div>

                        </div>
                    </div>
                <?php } ?>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Category Name: *</label>
                            <input type="text" name="cat_name" class="form-control" value="<?= isset($row) ? $row->cat_name : $this->form_validation->set_value('cat_name'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('cat_name'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Weighting: </label>
                            <input type="text" name="weighting" class="form-control" value="<?= isset($row) ? $row->weighting : $this->form_validation->set_value('weighting'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('weighting'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <button onclick="this.disabled = true;this.value = 'Sending, Please wait...';this.form.submit();" type="submit" class="btn btn-primary btn-sm">
                                Submit  
                            </button>
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
        $('#user_id').val('<?= $row->user_id; ?>');
        var user_id = '<?= $row->user_id; ?>';

        if (user_id) {
            $.ajax({
                type: "post",
                url: '<?= site_url('ajaxrequest/getsub_assessment'); ?>',
                data: {'user_id': user_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="sub_ass_id"]').empty();
                        $('select[name="sub_ass_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="sub_ass_id"]').append('<option value="' + value.sub_ass_id + '">' + value.name + '</option>');
                        });

                        $('select[name="sub_ass_id"]').val('<?= $row->sub_ass_id; ?>');
                    } else {
                        $('select[name="sub_ass_id"]').empty();
                        alert(json['message']);
                    }
                },
                complete: function () {
                    $("#dv_loader").hide();
                }
            });
        }
    </script>
<?php } ?>

<script type="text/javascript">
    $('#user_id').on('change', function () {
        var user_id = this.value;

        if (user_id) {
            $.ajax({
                type: "post",
                url: '<?= site_url('ajaxrequest/getsub_assessment'); ?>',
                data: {'user_id': user_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="sub_ass_id"]').empty();
                        $('select[name="sub_ass_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="sub_ass_id"]').append('<option value="' + value.sub_ass_id + '">' + value.name + '</option>');
                        });
                    } else {
                        $('select[name="sub_ass_id"]').empty();
                        alert(json['message']);
                    }
                },
                complete: function () {
                    $("#dv_loader").hide();
                }
            });
        }
    });
</script>