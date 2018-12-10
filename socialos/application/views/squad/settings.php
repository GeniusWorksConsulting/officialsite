<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('squad/index') ?>">Home</a></li>
        <li>Profile Setting</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-2">
        <!-- Profile links -->

        <div class="block">
            <div class="thumbnail">
                <div class="thumb">
                    <img alt="Profile" class="img-thumbnail" src="<?= base_url('profile/' . $user->profile) ?>" onerror="this.src='<?= base_url('assets/images/preview.jpg') ?>';">
                </div>

                <div class="caption text-center">
                    <h6><?= $this->logged_in_name; ?> <small><?= $this->groups; ?></small></h6>
                </div>
            </div>
        </div>

        <!-- /profile links -->
    </div>

    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-body">

                <h1>Profile</h1>
                <!-- Message -->
                <?php if ($this->session->flashdata('message')) { ?>
                    <div id="msg" class="callout callout-success fade in">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <p><?php echo $this->session->flashdata('message'); ?></p>
                    </div>
                <?php } ?>
                <hr>

                <?php
                $attributes = array('id' => 'profileForm', 'name' => 'profileForm', 'class' => '');
                echo form_open_multipart('squad/settings', $attributes);
                ?>
                <input type="hidden" name="filename" value="<?= $user->profile; ?>">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label><?php echo lang('create_user_fname_label', 'first_name'); ?></label>
                            <input type="text" name="first_name" value="<?= $user->first_name; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label><?php echo lang('create_user_lname_label', 'last_name'); ?></label>
                            <input type="text" name="last_name" value="<?= $user->last_name; ?>" class="form-control">
                        </div> 
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Profile Picture</label>
                            <input class="form-control" name="profile" type="file">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="submit" name="btnProfile" class="btn btn-success btn-xs">Save</button>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-body">

                <h1><?php echo lang('change_password_heading'); ?></h1>
                <div id="infoMessage" class="text-danger"><?php echo $message; ?></div>
                <hr>

                <?php echo form_open("squad/settings"); ?>
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