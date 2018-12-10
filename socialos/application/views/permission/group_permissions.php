<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/groups') ?>">Manage Groups</a></li>
        <li class="active">Manage Permission</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12">
        <?php echo form_open(); ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Permission</th>
                    <th>Allow</th>
                    <th>Deny</th>
                    <th>Ignore</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($permissions) : ?>
                    <?php foreach ($permissions as $k => $v) : ?>
                        <tr>
                            <td><?php echo $v['name']; ?></td>
                            <td><?php echo form_radio("perm_{$v['id']}", '1', set_radio("perm_{$v['id']}", '1', ( array_key_exists($v['key'], $group_permissions) && $group_permissions[$v['key']]['value'] === TRUE ) ? TRUE : FALSE)); ?></td>
                            <td><?php echo form_radio("perm_{$v['id']}", '0', set_radio("perm_{$v['id']}", '0', ( array_key_exists($v['key'], $group_permissions) && $group_permissions[$v['key']]['value'] != TRUE ) ? TRUE : FALSE)); ?></td>
                            <td><?php echo form_radio("perm_{$v['id']}", 'X', set_radio("perm_{$v['id']}", 'X', (!array_key_exists($v['key'], $group_permissions) ) ? TRUE : FALSE)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">There are currently no permissions to manage, please add some permissions</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <hr>
        <p>
            <?php echo form_submit('save', 'Save'); ?>
            <?php echo form_submit('cancel', 'Cancel'); ?>
        </p>

        <?php echo form_close(); ?>
    </div>
</div>