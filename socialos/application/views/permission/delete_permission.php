<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/permissions') ?>">Permissions</a></li>
        <li class="active">Delete</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-sm-6 col-sm-offset-3 col-xs-12">
        <h1>Delete Permission</h1>
        <div id="infoMessage"><?php echo $message; ?></div>
        <div class="panel panel-default">
            <div class="panel-body">
                <p>Are you sure you want to delete this permission</p>

                <?php echo form_open(); ?>

                <p>
                    <?php echo form_submit('delete', 'Delete'); ?>
                    <?php echo form_submit('cancel', 'Cancel'); ?>
                </p>

                <?php echo form_close(); ?>
                
            </div>
        </div>
    </div>
</div>
