<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/permissions') ?>">Permissions</a></li>
        <li class="active">Edit</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12">
        <?php if ($message) { ?>
            <div id="msg" class="callout callout-danger fade in text-semibold">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <h1 class="text-center">Edit Permission</h1>
        <div class="panel panel-default">
            <!--<div class="panel-heading"><h6 class="panel-title">Invoice</h6></div>-->
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'perm', 'name' => 'perm', 'class' => 'form-bordered');
                echo form_open('', $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Key:</label>
                            <input type="text" name="perm_key" id="perm_key" class="form-control" value="<?= $this->form_validation->set_value('perm_key', $permission->perm_key); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('perm_key'); ?></span>
                        </div>
                        <div class="col-sm-6">
                            <label>Name:</label>
                            <input type="text" name="perm_name" id="perm_name" class="form-control" value="<?= $this->form_validation->set_value('perm_name', $permission->perm_name); ?>">
                            <span class="text-danger text-semibold"><?php echo form_error('perm_name'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" name="Save" class="">
                                Save  
                            </button>
                            <?php echo form_submit('cancel', 'Cancel'); ?>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
