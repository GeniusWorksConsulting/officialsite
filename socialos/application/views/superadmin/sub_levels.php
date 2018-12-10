<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Levels</li>
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
        <a class="btn btn-success btn-sm" href="<?= site_url('superadmin/create_sub_level') ?>"><i class="icon-plus-circle"></i> Create</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="10%">No.</th>
            <th>Sub Level Name</th>
            <th>Main Level</th>
            <th class="text-center">Admin</th>
            <th width="10%" class="text-center">Percentage (%)</th>
            <th width="10%" class="text-center">Date</th>
            <th width="10%" class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($levels as $l):
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $l->sub_level_name; ?></td>
                <td><?= get_levelname_helper($l->level_id) ?></td>
                <td class="text-center">
                    <label class="label label-success"><?= get_adminname_helper($l->user_id); ?></label>
                </td>
                <td class="text-center"><?= $l->value; ?></td>
                <td class="text-center"><?= $l->created; ?></td>
                <td class="text-right">
                    <a title="edit" href="<?= site_url('superadmin/create_sub_level/' . $l->sub_level_id); ?>"><i class="icon-pencil3"></i></a> | 
                    <a title="remove" onclick="return confirm('Are you sure?');" href="<?= site_url('superadmin/delete_sub_level/' . $l->sub_level_id); ?>" class="text-danger"><i class="icon-cancel"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>