<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index'); ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/usergroups'); ?>">User Groups</a></li>
        <li>Edit Group</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-4 col-md-offset-4 col-xs-12">

        <div class="panel panel-default">
            <div class="panel-body">

                <h1 class="text-info">Edit Group</h1>
                <p>Please enter the group information below.</p>

                <div id="infoMessage" class="text-danger"><?php echo $message; ?></div>
                <hr>

                <?php echo form_open(current_url()); ?>
                <?php
                $readonly = ($this->config->item('admin_group', 'ion_auth') === $group->name || 'admin' == $group->name) ? 'readonly' : '';
                ?>
                <p>
                    <strong>Group Name: </strong> <br />
                    <input type="text" name="group_name" class="form-control" placeholder="Group Name" value="<?= $this->form_validation->set_value('group_name', $group->name); ?>" <?= $readonly; ?>>
                </p>
                <br>
                <p>
                    <strong>Description: </strong> <br />
                    <input type="text" name="group_description" class="form-control" placeholder="Description" value="<?= $this->form_validation->set_value('group_description', $group->description); ?>">
                </p>

                <p>
                    <button class="btn btn-primary btn-xs" type="submit" name="submit">Save Group</button>
                </p>
                <?php echo form_close(); ?>

            </div>
        </div>

    </div>
</div>