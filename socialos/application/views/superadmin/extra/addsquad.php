<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/squads') ?>">Squad Users</a></li>
        <li class="active">Add New</li>
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

        <h1 class="text-center">Squad User</h1>
        <div class="panel panel-default">
            <!--<div class="panel-heading"><h6 class="panel-title">Invoice</h6></div>-->
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'squadform', 'name' => 'squadform', 'class' => 'form-bordered');
                echo form_open('superadmin/addsquad', $attributes);
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
                        <div class="col-sm-4">
                            <label>Select Admin:</label>
                            <select class="form-control" name="admin_id" id="admin_id">
                                <option value="">-- Select --</option>
                                <?php foreach ($users as $u) { ?>
                                    <option value="<?= $u->id; ?>"><?= $u->first_name . ' ' . $u->last_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Password:</label>
                            <input type="password" name="password" id="password" class="form-control" value="<?= $this->form_validation->set_value('password'); ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Confirm Password:</label>
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control" value="<?= $this->form_validation->set_value('password_confirm'); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-sm">
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
