<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Manage Users</li>
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

<h1>Manage Users</h1>

<div class="row">
    <div class="col-md-12 col-xs-12">

        <!-- Default table inside panel -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th class="text-center">User Group</th>
                    <th width="10%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td class="text-semibold"><?php echo $user->email; ?></td>
                        <td class="text-center text-semibold text-uppercase"><?= $this->ion_auth->get_users_groups($user->id)->row()->name; ?></td>
                        <td class="text-right">
                            <a href="<?= site_url('superadmin/manage_user/' . $user->id); ?>">Manage User</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
<br>