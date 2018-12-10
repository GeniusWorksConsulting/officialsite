<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Permissions</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in text-semibold">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?= $this->session->flashdata('message'); ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-sm-12 text-right">
        <a class="btn btn-success btn-sm" href="<?= site_url('superadmin/add_permission') ?>"><i class="icon-plus-circle"></i> New</a>
    </div>
</div>
<br>

<!--<h1>Manage Permissions</h1>-->

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="15%">Key</th>
            <th>Name</th>
            <th width="10%" class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($permissions as $permission) : ?>
            <tr>
                <td><?php echo $permission['key']; ?></td>
                <td><?php echo $permission['name']; ?></td>
                <td>
                    <a href="<?= site_url('superadmin/update_permission/' . $permission['id']); ?>">Edit</a> | 
                    <a href="<?= site_url('superadmin/delete_permission/' . $permission['id']); ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>