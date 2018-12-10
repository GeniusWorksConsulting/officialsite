<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/squadgroup') ?>">Squad Groups</a></li>
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

        <h1 class="text-center">Add/Edit SQUAD Group</h1>
        <div class="panel panel-default">
            <!--<div class="panel-heading"><h6 class="panel-title">Invoice</h6></div>-->
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'squadgroup', 'name' => 'squadgroup', 'class' => 'form-bordered');
                echo form_open('', $attributes);
                ?>

                <input type="hidden" name="squad_id" value="<?= isset($row) ? $row->squad_id : ''; ?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Squad Name:</label>
                            <input type="text" name="squad_name" class="form-control" value="<?= isset($row) ? $row->squad_name : $this->form_validation->set_value('squad_name'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('squad_name'); ?></span>
                        </div>

                        <div class="col-md-6">
                            <label>Site:</label>
                            <input type="text" name="site" class="form-control" value="<?= isset($row) ? $row->site : $this->form_validation->set_value('site'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('site'); ?></span>
                        </div>
                    </div>
                </div>

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
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button onclick="this.disabled = true;this.value = 'Sending...';this.form.submit();" type="submit" class="btn btn-primary btn-xs">
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
    </script>
<?php } ?>