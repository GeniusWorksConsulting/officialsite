<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('users/index') ?>">Dashboard</a></li>
        <li class="active">Compliment or Complaint</li>
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
    <div class="col-sm-6 col-sm-offset-3 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'userForm', 'name' => 'userForm', 'class' => '');
                echo form_open_multipart('users/send_compliment', $attributes);
                ?>

                <input type="hidden" name="user_id" value="<?= $this->uri->segment(3); ?>" >

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Options *</label>
                            <select name="title" class="form-control">
                                <option value="">Select</option>
                                <option value="Compliment">Compliment</option>
                                <option value="Complaint">Complaint</option>
                            </select>
                            <?php echo form_error('title'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Users *</label>
                            <select name="sub_title" class="form-control">
                                <option value="">Select</option>
                                <option value="lead">Lead Compliment</option>
                                <option value="customer">Customer Compliment</option>
                                <option value="lead">Lead Complaint</option>
                                <option value="customer">Customer Complaint</option>
                            </select>
                            <?php echo form_error('sub_title'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Message *</label>
                            <textarea name="message" class="form-control" rows="3" tabindex="1"></textarea>
                            <?php echo form_error('message'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button name="btn-save" type="submit"  class="btn btn-info" tabindex="13"><?= isset($row) ? 'UPDATE' : 'SUBMIT'; ?></button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>