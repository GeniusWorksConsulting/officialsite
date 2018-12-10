<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url($this->group_name . '/index') ?>">Dashboard</a></li>
        <li class="active">Category</li>
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
        <a class="btn btn-success btn-sm" href="<?= site_url($this->group_name . '/add_category') ?>"><i class="icon-plus-circle"></i> Add</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="10%">No.</th>
            <th>Cat Name</th>
            <th class="text-center">Sub Assessment</th>
            <th class="text-center">Assessment</th>
            <?php if ($this->ion_auth->in_group('superadmin')) { ?>
                <th class="text-center">Admin</th>
            <?php } ?>
            <th class="text-center">Weighting</th>
            <th width="10%" class="text-center">Date</th>
            <th width="10%" class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($list as $c):
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $c->cat_name; ?></td>
                <td class="text-center"><?= $c->name; ?></td>
                <td class="text-center"><?= $c->ass_name; ?></td>
                <?php if ($this->ion_auth->in_group('superadmin')) { ?>
                    <td class="text-center">
                        <label class="label label-success"><?= $c->user_name; ?></label>
                    </td>
                <?php } ?>
                <td class="text-center"><?= $c->weighting; ?></td>
                <td class="text-center"><?= $c->created; ?></td>
                <td class="text-right">
                    <a title="edit" href="<?= site_url($this->group_name . '/add_category/' . $c->cat_id); ?>"><i class="icon-pencil3"></i></a> | 
                    <a title="remove" onclick="return confirm('Are you sure?');" href="<?= site_url($this->group_name . '/delete_cat/' . $c->cat_id); ?>" class="text-danger"><i class="icon-cancel"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>