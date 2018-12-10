<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Manage Groups</li>
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

<h1>Manage Groups</h1>

<div class="row">
    <div class="col-md-6 col-xs-12">

        <!-- Default table inside panel -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="15%">Key</th>
                    <th width="10%" class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($groups as $group) :
                    if ($this->config->item('admin_group', 'ion_auth') !== $group->name) {
                        ?>
                        <tr>
                            <td><?php echo $group->description; ?></td>
                            <td class="text-right">
                                <a href="<?= site_url('superadmin/group_permissions/' . $group->id); ?>">Manage Permissions</a>
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