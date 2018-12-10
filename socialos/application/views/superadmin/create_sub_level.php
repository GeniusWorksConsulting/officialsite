<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/sub_levels') ?>">Sub Levels</a></li>
        <li class="active">Create/Edit Level</li>
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

        <h1 class="text-center">Create/Edit Sub Levels</h1>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'sublevel', 'name' => 'sublevel', 'class' => 'form-bordered');
                echo form_open('', $attributes);
                ?>

                <input type="hidden" name="sub_level_id" value="<?= isset($row) ? $row->sub_level_id : ''; ?>">
                <div class="form-group">
                    <div class="row">

                        <div class="col-md-6">
                            <label>Select Admin:</label>
                            <select class="form-control" name="user_id" id="user_id">
                                <option value="">-- Select --</option>
                                <?php foreach ($users as $u) { ?>
                                    <option value="<?= $u->id; ?>"><?= $u->first_name . ' ' . $u->last_name; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('user_id'); ?></span>
                        </div>

                        <div class="col-md-6">
                            <label>Levels:</label>
                            <select class="form-control" name="level_id" id="level_id">
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('level_id'); ?></span>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Sub Level Name:</label>
                            <input type="text" name="sub_level_name" class="form-control" value="<?= isset($row) ? $row->sub_level_name : $this->form_validation->set_value('sub_level_name'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('sub_level_name'); ?></span>
                        </div>

                        <div class="col-md-3">
                            <label>Percentage:</label>
                            <input type="number" name="value" class="form-control" value="<?= isset($row) ? $row->value : $this->form_validation->set_value('value'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('value'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-10">
                            <button onclick="this.disabled = true;this.value = 'Sending, Please wait...';this.form.submit();" type="submit" class="btn btn-primary btn-xs">
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
                url: '<?= site_url('ajaxrequest/getlevels'); ?>',
                data: {'user_id': user_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="level_id"]').empty();
                        $('select[name="level_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="level_id"]').append('<option value="' + value.level_id + '">' + value.level_name + '</option>');
                        });

                        $('select[name="level_id"]').val('<?= $row->level_id; ?>');
                    } else {
                        $('select[name="level_id"]').empty();
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
                url: '<?= site_url('ajaxrequest/getlevels'); ?>',
                data: {'user_id': user_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="level_id"]').empty();
                        $('select[name="level_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="level_id"]').append('<option value="' + value.level_id + '">' + value.level_name + '</option>');
                        });

                    } else {
                        $('select[name="level_id"]').empty();
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