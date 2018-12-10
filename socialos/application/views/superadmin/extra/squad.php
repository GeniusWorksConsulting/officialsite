<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Squad Users</li>
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
    <div class="col-md-10 col-xs-12">
        <?php
        $attributes = array("class" => "form-horizontal", "id" => "search", "name" => "search");
        echo form_open('superadmin/searchsquad', $attributes);
        ?>
        <div class="form-group">
            <div class="col-md-3">
                <input type="text" name="search" placeholder="Seach Name, Email, Admin" class="form-control input-sm">
            </div>

            <div class="col-md-2">
                <input id="btn_search" name="btn_search" type="submit" class="btn btn-primary btn-xs" value="Search" />&nbsp;
                <a class="btn btn-danger btn-xs" href="<?= site_url('superadmin/squads') ?>">Cancel</a>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>

    <div class="col-md-2 text-right">
        <a class="btn btn-primary btn-xs" href="<?= site_url('superadmin/addsquad') ?>"><i class="icon-plus-circle"></i> Add Member</a>
    </div>
</div>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th>User Name</th>
            <th>Email</th>
            <th class="text-center">Admin</th>
            <th class="text-center">Status</th>
            <th class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        //$i = 1;
        foreach ($users as $user):
            ?>
            <tr>
                <td><?= $user->id; ?></td>
                <td><?= $user->first_name; ?></td>
                <td><?= $user->email; ?></td>
                <td class="text-center">
                    <label class="label label-info"><?= get_adminname_helper($user->admin_id); ?></label>
                </td>
                <td class="text-center">
                    <?php echo ($user->active) ? anchor("superadmin/deactivate/" . $user->id, 'Active', array('class' => 'text-success text-semibold')) : anchor("superadmin/activate/" . $user->id, 'Inactive', array('class' => 'text-danger text-semibold')); ?>
                </td>
                <td class="text-right">
                    <a title="edit" href="<?= site_url('superadmin/editsquad/' . $user->id) ?>"><i class="icon-pencil3"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php
        if ($users == NULL) {
            echo '<tr><td class="text-center text-danger text-semibold" colspan="6">No data found.</td></tr>';
        }
        ?>

    </tbody>
</table>

<?php if (isset($pagination) && $pagination != NULL) { ?>
    <div class="well text-right hidden-print">
        <div class="well text-right">
            <?= $pagination; ?>
        </div>
    </div>
<?php } ?>
<br>