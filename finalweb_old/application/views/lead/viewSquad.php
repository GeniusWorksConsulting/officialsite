<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('lead/index') ?>">Dashboard</a></li>
        <li class="active">View Squad Member</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
<?php } ?>

<div class="row">
    <div class="col-sm-12 text-right">  
        <a class="btn btn-success btn-sm" href="<?= site_url('lead/addSquad') ?>"><i class="icon-plus-circle"></i> ADD SQUAD</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Member</th>
            <th class="text-center">Squad Group</th>
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
                        <?php echo '<span style="text-transform: uppercase">' . $group->name . '</span>' ?>
                    <?php endforeach ?>
                </td>
                <td class="text-center"><?= get_squadgroup_helper(array('squad_group' => $user->squad_group)); ?></td>
                <td><?php echo ($user->active) ? anchor("lead/deactivate/" . $user->id, lang('index_active_link'), array('class' => 'text-success text-semibold')) : anchor("lead/activate/" . $user->id, lang('index_inactive_link'), array('class' => 'text-danger text-semibold')); ?></td>
                <td><a title="edit" href="<?= site_url('lead/editSquad/' . $user->id) ?>"><i class="icon-pencil3"></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php if ($pagination) {
    ?>
    <div class="well text-right">
        <?php echo $pagination; ?>
    </div>
<?php } ?>
<br>