<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('users/index') ?>">Dashboard</a></li>
        <li class="active">Add Squad</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<?php if ($message) { ?>
    <div id="msg" class="callout callout-danger fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $message; ?>
    </div>
<?php } ?>


<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-xs-12">
        <h1>Squad Member</h1>
        <p><?php echo lang('create_user_subheading'); ?></p>

        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'formSquad', 'name' => 'formSquad', 'class' => 'form-bordered');
                echo form_open('users/addSquad', $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label><?php echo lang('create_user_fname_label', 'first_name'); ?></label>
                            <?php echo form_input($first_name); ?>
                        </div>
                        <div class="col-sm-3">
                            <label><?php echo lang('create_user_lname_label', 'last_name'); ?></label>
                            <?php echo form_input($last_name); ?>
                        </div>

                        <div class="col-sm-6">
                            <label><?php echo lang('create_user_email_label', 'email'); ?></label>
                            <?php echo form_input($email); ?>
                        </div>
                        <?php
                        if ($identity_column !== 'email') {
                            echo '<div class="col-sm-1">';
                            echo '<p>';
                            echo lang('create_user_identity_label', 'identity');
                            echo '<br />';
                            echo form_error('identity');
                            echo form_input($identity);
                            echo '</p>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <label><?php echo lang('create_user_phone_label', 'phone'); ?></label>
                            <?php echo form_input($phone); ?>
                        </div>
                        <div class="col-sm-4">
                            <label><?php echo lang('create_user_password_label', 'password'); ?></label>
                            <?php echo form_input($password); ?>
                        </div>
                        <div class="col-sm-4">
                            <label><?php echo lang('create_user_password_confirm_label', 'password_confirm'); ?></label>
                            <?php echo form_input($password_confirm); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Squad</label>
                            <select class="form-control" name="squad_group" id="squad_group">
                                <option value="">Select</option>
                                <?php foreach ($squad_group as $squad) { ?>
                                    <option value="<?= $squad->squad_group; ?>"><?= $squad->squad_name; ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info btn-sm">
                                Create  
                            </button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
