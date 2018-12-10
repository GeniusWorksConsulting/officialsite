<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Edit Admin</li>
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

        <h1 class="text-center">Edit Admin</h1>

        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'adminform', 'name' => 'adminform', 'class' => 'form-bordered');
                echo form_open(uri_string(), $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-3">
                            <label>First Name:</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="<?= $this->form_validation->set_value('first_name', $user->first_name); ?>">
                        </div>
                        <div class="col-sm-3">
                            <label>Last Name:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="<?= $this->form_validation->set_value('last_name', $user->last_name); ?>">
                        </div>

                        <div class="col-sm-4">
                            <label>Email:</label>
                            <input type="text" name="email" id="email" class="form-control" value="<?= $this->form_validation->set_value('email', $user->email); ?>">
                        </div>

                        <div class="col-sm-2">
                            <label>Phone:</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?= $this->form_validation->set_value('phone', $user->phone); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Company Name:</label>
                            <input type="text" name="company" id="company" class="form-control" value="<?= $this->form_validation->set_value('company', $user->company); ?>">
                        </div>
                        <div class="col-sm-4">
                            <label>Password: (if changing password)</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <label>Confirm Password: (if changing)</label>
                            <input type="password" name="password_confirm" id="password_confirm" class="form-control">
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
                
                <?php echo form_hidden('id', $user->id); ?>
                <?php echo form_hidden($csrf); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>


