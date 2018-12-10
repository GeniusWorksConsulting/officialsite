<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/user_list') ?>">User's</a></li>
        <li class="active">Manage</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12">

        <h1 class="text-center">Manage User <strong><?php echo $user->first_name; ?> <?php echo $user->last_name; ?></strong></h1>
        <div class="panel panel-default">
            <div class="panel-body">

                <h4>Users Groups</h4>
                <ul>
                    <?php foreach ($user_groups as $ug) : ?>
                        <li class="text-semibold text-success"><?php echo $ug->description; ?></li>
                    <?php endforeach; ?>
                </ul>

                <hr>

                <h6>Users Permissions (<a href="<?= site_url('superadmin/user_permissions/' . $user->id); ?>">Manage User Permissions</a>)</h6>
                <table class="table">
                    <?php foreach ($user_acl as $acl) : ?>
                        <tr>
                            <td><?php echo $acl['name']; ?></td>
                            <td>(<?php if ($this->ion_auth_acl->has_permission($acl['key'], $user_acl)) : ?><span class="text-semibold text-success">Allow</span><?php else: ?><span class="text-semibold text-danger">Deny</span><?php endif; ?><?php if ($acl['inherited']) : ?> <strong>Inherited</strong><?php endif; ?>)</td>
                        </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>
    </div>
</div>