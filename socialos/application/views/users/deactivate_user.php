<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('desktop/index') ?>">Dashboard</a></li>
        <li class="active">Deactivate</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<h1><?php echo lang('deactivate_heading'); ?></h1>
<p><?php echo sprintf(lang('deactivate_subheading'), $user->username); ?></p>

<?php echo form_open("users/deactivate/" . $user->id); ?>

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

<p><button type="submit" class="btn btn-info btn-sm">
        Submit  
    </button></p>

<?php echo form_close(); ?>