<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index'); ?>">Dashboard</a></li>
        <li>Change Password</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-6 col-md-offset-3 col-xs-12">

        <div class="panel panel-default">
            <div class="panel-body">

                <h1 class="text-info"><?php echo lang('change_password_heading'); ?></h1>
                <div id="infoMessage" class="text-danger"><?php echo $message; ?></div>
                <hr>

                <?php echo form_open(); ?>
                <p>
                    <?php echo lang('change_password_old_password_label', 'old_password'); ?> <br />
                    <?php echo form_input($old_password); ?>
                </p>

                <p>
                    <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length); ?></label> <br />
                    <?php echo form_input($new_password); ?>
                </p>

                <p>
                    <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm'); ?> <br />
                    <?php echo form_input($new_password_confirm); ?>
                </p>

                <?php echo form_input($user_id); ?>
                <p><?php echo form_submit('submit', lang('change_password_submit_btn')); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
</div>