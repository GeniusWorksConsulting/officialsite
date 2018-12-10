<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Assessment</li>
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
        <a class="btn btn-success btn-sm" href="<?= site_url('superadmin/add_sub_assessment') ?>"><i class="icon-plus-circle"></i> Add</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="10%">No.</th>
            <th>Sub Assessment</th>
            <th width="10%" class="text-center">Assessment</th>
            <th width="10%" class="text-center">Admin Name</th>
            <th width="10%" class="text-center">Date</th>
            <th width="10%" class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($list as $s):
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $s->name; ?></td>
                <td class="text-center"><?= $s->ass_name; ?></td>
                <td class="text-center">
                    <label class="label label-success"><?= $s->user_name; ?></label>
                </td>
                <td class="text-center"><?= $s->created; ?></td>
                <td class="text-right">
                    <a title="edit" href="<?= site_url('superadmin/add_sub_assessment/' . $s->sub_ass_id); ?>"><i class="icon-pencil3"></i></a> | 
                    <a title="remove" onclick="return confirm('Are you sure?');" href="<?= site_url('superadmin/delete_sub_assessment/' . $s->sub_ass_id); ?>" class="text-danger"><i class="icon-cancel"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>