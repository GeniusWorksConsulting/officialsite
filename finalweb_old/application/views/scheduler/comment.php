<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('scheduler/index') ?>">Dashboard</a></li>
        <li class="active">Reflection Comment</li>
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
        <h4 class="text-center">Reflection Comment</h4>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'commentForm', 'name' => 'commentForm', 'class' => '');
                echo form_open_multipart(uri_string(), $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Title *</label>
                            <input type="hidden" name="title" value="Scheduling">
                            <input type="text" readonly name="sub_title" value="Reflections on the week" class="form-control">
                            <span class="text-danger"><?php echo form_error('sub_title'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Comment *</label>
                            <textarea tabindex="1" type="text" name="message" class="form-control"></textarea>
                            <span class="text-danger"><?php echo form_error('message'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button name="submit" type="submit" class="btn btn-info btn-xs" tabindex="2">Submit</button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>