<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Squad Group</li>
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
        <a class="btn btn-success btn-sm" href="<?= site_url('superadmin/addsgroup'); ?>"><i class="icon-plus-circle"></i> Create</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="10%">No.</th>
            <th>Squad Name</th>
            <th class="text-center">Admin</th>
            <th width="10%" class="text-center">Site</th>
            <th width="10%" class="text-center">Created</th>
            <th width="10%" class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($list as $l):
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $l->squad_name; ?></td>
                <td class="text-center">
                    <label class="label label-success"><?= $l->user_name; ?></label>
                </td>
                <td class="text-center"><?= $l->site; ?></td>
                <td class="text-center"><?= $l->created; ?></td>
                <td class="text-right">
                    <a title="edit" href="<?= site_url('superadmin/addsgroup/' . $l->squad_id); ?>"><i class="icon-pencil3"></i></a> | 
                    <a title="remove" onclick="return confirm('Are You Sure?');" href="<?= site_url('superadmin/delete_sgroup/' . $l->squad_id); ?>" class="text-danger"><i class="icon-cancel"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>