<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Admin Users</li>
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
        <a class="btn btn-success btn-sm" href="<?= site_url('superadmin/addadmin') ?>"><i class="icon-plus-circle"></i> New</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>First Name</th>
            <th>Email</th>
            <th>Company Name</th>
            <th class="text-center">Status</th>
            <th class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($users as $user):
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $user->first_name; ?></td>
                <td><?= $user->email; ?></td>
                <td><?= $user->company; ?></td>
                <td class="text-center">
                    <?php echo ($user->active) ? anchor("superadmin/deactivate/" . $user->id, 'Active', array('class' => 'text-success text-semibold')) : anchor("superadmin/activate/" . $user->id, 'Inactive', array('class' => 'text-danger text-semibold')); ?>
                </td>
                <td class="text-right">
                    <a title="edit" href="<?= site_url('superadmin/editadmin/' . $user->id) ?>"><i class="icon-pencil3"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>