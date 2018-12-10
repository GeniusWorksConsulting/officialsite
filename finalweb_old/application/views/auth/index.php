<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('desktop/index') ?>">Dashboard</a></li>
        <li class="active">Create User</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<h1><?php echo lang('index_heading'); ?></h1>
<p><?php echo lang('index_subheading'); ?></p>

<?php if ($message) { ?>
    <div class="alert alert-danger fade in block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?php echo $message; ?>
    </div>
<?php } ?>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th><?php echo lang('index_groups_th'); ?></th>
            <th><?php echo lang('index_status_th'); ?></th>
            <th><?php echo lang('index_action_th'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user->first_name, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user->last_name, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($user->phone, ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                    <?php foreach ($user->groups as $group): ?>
                        <?php echo $group->name; ?><br />
                    <?php endforeach ?>
                </td>
                <td><?php echo ($user->active) ? anchor("auth/deactivate/" . $user->id, lang('index_active_link')) : anchor("auth/activate/" . $user->id, lang('index_inactive_link')); ?></td>
                <td><a title="edit" href="<?= site_url('auth/edit_user/' . $user->id) ?>"><i class="icon-pencil3"></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>