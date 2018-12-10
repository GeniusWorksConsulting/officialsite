<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">User Groups</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-6 text-right">
        <a class="btn btn-success btn-xs" href="<?= site_url('superadmin/create_group') ?>"><i class="icon-plus-circle"></i> Create</a>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-6">
        <!-- Message -->
        <?php if ($this->session->flashdata('message')) { ?>
            <div id="msg" class="callout callout-success fade in text-semibold">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $this->session->flashdata('message'); ?>
            </div>
        <?php } ?>

        <!-- Default table inside panel -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>USER TYPE</th>
                    <th>Description</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($groups as $row):
                    if ($this->config->item('admin_group', 'ion_auth') !== $row->name) {
                        ?>
                        <tr>
                            <td><?= $row->id; ?></td>
                            <td><?= $row->name; ?></td>
                            <td><?= $row->description; ?></td>
                            <td class="text-right">
                                <a title="edit" href="<?= site_url('superadmin/edit_group/' . $row->id) ?>"><i class="icon-pencil3"></i></a>
                            </td>
                        </tr>
                        <?php
                    }
                endforeach;
                ?>
            </tbody>
        </table>

    </div>
</div>
<br>