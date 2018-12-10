<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('admin/viewcategory') ?>">View Category</a></li>
        <li class="active">Add Category</li>
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
                $attributes = array('id' => 'categoryForm', 'name' => 'categoryForm', 'class' => 'form-bordered');
                echo form_open_multipart('admin/savecategory', $attributes);
                ?>

                <?= isset($row) ? '<input type="hidden" name="cat_id" value="' . $row->cat_id . '">' : ''; ?>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-10">
                            <label>Assessment *</label>
                            <select class="form-control" id="assessment" name="assessment" autofocus tabindex="1">
                                <option value="">Select</option>
                                <?php foreach ($list as $a) { ?>
                                    <option value="<?= $a->assessment; ?>"><?= $a->name; ?></option>
                                <?php }
                                ?>
                            </select>
                            <?php echo form_error('assessment'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-10">
                            <label>Name *</label>
                            <input type="text" name="name" value="<?= isset($row) ? $row->name : ''; ?>" class="form-control" tabindex="2">
                            <?php echo form_error('name'); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-5">
                            <label>Weighting *</label>
                            <input type="text" name="weighting" value="<?= isset($row) ? $row->weighting : ''; ?>" class="form-control" tabindex="3">
                            <?php echo form_error('weighting'); ?>
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

<?php if (isset($row)) { ?>
    <script type="text/javascript">
        $('#assessment').val('<?= $row->assessment; ?>');
    </script>
<?php } ?>