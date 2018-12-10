<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Create User</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12">
        <?php if ($message) { ?>
            <div id="msg" class="callout callout-danger fade in text-semibold">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <h1 class="text-center">Create User</h1>
        <p class="text-center">Please enter the user information below.</p>
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                $attributes = array('id' => 'userform', 'name' => 'userform', 'class' => 'form-bordered');
                echo form_open('superadmin/create_user', $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>First Name:</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $this->form_validation->set_value('first_name'); ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Last Name:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $this->form_validation->set_value('last_name'); ?>">
                        </div>

                        <div class="col-sm-4">
                            <label>Email:</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= $this->form_validation->set_value('email'); ?>">
                        </div>

                        <div class="col-sm-2">
                            <label>Phone:</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?= $this->form_validation->set_value('phone'); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>User Group:</label>
                            <select class="form-control" name="group_id" id="group_id">
                                <option value="">-- Select --</option>
                                <?php
                                foreach ($this->users as $group) {
                                    if ($this->config->item('admin_group', 'ion_auth') !== $group->name && 'admin' !== $group->name) {
                                        ?>
                                        <option class="text-first-letter" value="<?= $group->id; ?>"><?= $group->name; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Select Admin:</label>
                            <select class="form-control" name="admin_id" id="admin_id">
                                <option value="">-- Select --</option>
                                <?php foreach ($users as $u) { ?>
                                    <option value="<?= $u->id; ?>"><?= $u->first_name . ' ' . $u->last_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Password:</label>
                            <input type="password" name="password" id="password" class="form-control" value="<?= $this->form_validation->set_value('password'); ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Confirm Password:</label>
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control" value="<?= $this->form_validation->set_value('password_confirm'); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4" id="squad_group">

                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-xs">
                                Submit  
                            </button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>

        </div>
    </div>
</div>