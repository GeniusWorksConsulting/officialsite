<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Dashboard</a></li>
        <li class="active">Deactivate</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-sm-6 col-sm-offset-3 col-xs-12">
        <h1><?php echo lang('deactivate_heading'); ?></h1>
        <div class="panel panel-default">
            <div class="panel-body">
                <p>Are you sure you want to deactivate the user <span class="text-danger text-semibold"><?= $user->username; ?></span></p>

                <?php echo form_open("superadmin/deactivate/" . $user->id); ?>

                <p>
                    <label class="radio-inline">
                        <input type="radio" name="confirm" value="yes" checked="checked" />
                        <?php echo lang('deactivate_confirm_y_label', 'confirm'); ?>
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="confirm" value="no" />
                        <?php echo lang('deactivate_confirm_n_label', 'confirm'); ?>
                    </label>
                </p>

                <?php echo form_hidden($csrf); ?>
                <?php echo form_hidden(array('id' => $user->id)); ?>
                <?php echo form_hidden(array('redirect' => $redirect)); ?>
                <hr>
                <p>
                    <button type="submit" class="btn btn-info btn-xs">
                        Submit  
                    </button>
                </p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
