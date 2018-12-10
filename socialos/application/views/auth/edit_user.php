<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('desktop/index') ?>">Dashboard</a></li>
        <li class="active">Edit QA</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<?php if ($message) { ?>
    <div class="alert alert-danger fade in block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $message; ?>
    </div>
<?php } ?>

<h1>Edit QA</h1>
<p><?php echo lang('edit_user_subheading'); ?></p>

<div class="row">
    <div class="col-sm-8">
        <div class="panel panel-default">
            <!--<div class="panel-heading"><h6 class="panel-title">Invoice</h6></div>-->
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'invoice_form', 'name' => 'invoice_form', 'class' => 'form-bordered');
                echo form_open(uri_string(), $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <label><?php echo lang('edit_user_fname_label', 'first_name'); ?></label>
                            <?php echo form_input($first_name); ?>
                        </div>
                        <div class="col-sm-4">
                            <label><?php echo lang('edit_user_lname_label', 'last_name'); ?></label>
                            <?php echo form_input($last_name); ?>
                        </div>
                        <div class="col-sm-4">
                            <label><?php echo lang('edit_user_phone_label', 'phone'); ?></label>
                            <?php echo form_input($phone); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-sm-6">
                            <label><?php echo lang('edit_user_password_label', 'password'); ?></label>
                            <?php echo form_input($password); ?>
                        </div>
                        <div class="col-sm-6">
                            <label><?php echo lang('edit_user_password_confirm_label', 'password_confirm'); ?></label>
                            <?php echo form_input($password_confirm); ?>
                        </div>
                        
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info btn-sm">
                                Update  
                            </button>
                        </div>
                    </div>
                </div>
                <?php echo form_hidden('id', $user->id); ?>
                <?php echo form_hidden($csrf); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


