<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Dashboard</a></li>
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
        <a class="btn btn-success btn-sm" href="<?= site_url('admin/addSquad') ?>"><i class="icon-plus-circle"></i> ADD SQUAD</a>
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

                <td class="text-center"><?= get_squadgroup_helper(array('squad_group' => $user->squad_group)); ?></td>
                <td>
                    <?php
                    if ($user->active) {
                        $isPaused = get_ispaused_helper(array('user_id' => $user->id, 'status' => 0, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
                        echo ($isPaused) ? '<a href="' . site_url('admin/deactivate/' . $user->id) . '" class="text-success">Active</a>' : '<span class="text-orange">Paused</span>';
                        ?>
                        <?php
                    } else {
                        echo '<a href="' . site_url('admin/activate/' . $user->id) . '" class="text-danger">Inactive</a>';
                    }
                    ?>
                </td>
                <td><a title="edit" href="<?= site_url('admin/editSquad/' . $user->id) ?>"><i class="icon-pencil3"></a></td>
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