<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/levels') ?>">Levels</a></li>
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

        <h1 class="text-center">Create/Edit Levels</h1>
        <div class="panel panel-default">
            <!--<div class="panel-heading"><h6 class="panel-title">Invoice</h6></div>-->
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'levelform', 'name' => 'levelform', 'class' => 'form-bordered');
                echo form_open('superadmin/createlevel', $attributes);
                ?>

                <input type="hidden" name="level_id" value="<?= isset($row) ? $row->level_id : ''; ?>">
                <div class="form-group">
                    <div class="row">

                        <div class="col-md-4 col-md-offset-1">
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
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <label>Level Name:</label>
                            <input type="text" name="level_name" class="form-control" value="<?= isset($row) ? $row->level_name : $this->form_validation->set_value('level_name'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('level_name'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <label>Percentage:</label>
                            <input type="number" name="value" class="form-control" value="<?= isset($row) ? $row->value : $this->form_validation->set_value('value'); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('value'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-10 col-md-offset-1">
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