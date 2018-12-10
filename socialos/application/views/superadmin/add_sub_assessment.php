<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/sub_assessment') ?>">Sub Assessment</a></li>
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

        <h1 class="text-center">Add/Edit Sub Assessment</h1>
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                $attributes = array('id' => 'assessment', 'name' => 'assessment', 'class' => 'form-bordered');
                echo form_open('superadmin/add_sub_assessment', $attributes);
                ?>

                <input type="hidden" name="sub_ass_id" value="<?= isset($row) ? $row->sub_ass_id : ''; ?>">
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
                            <label>Assessment:</label>
                            <select class="form-control" name="assessment_id" id="assessment_id">
                                <option value="">-- Select --</option>
                                <?php foreach ($assessment as $a) { ?>
                                    <option value="<?= $a->assessment_id; ?>"><?= $a->name; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('assessment_id'); ?></span>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control" value="<?= isset($row) ? $row->name : $this->form_validation->set_value('name'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('name'); ?></span>
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
        $('#assessment_id').val('<?= $row->assessment_id; ?>');
    </script>
<?php } ?>