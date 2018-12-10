<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li class="active">Squad Group</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<!-- Message -->
<?php if ($this->session->flashdata('message')) { ?>
    <div id="msg" class="callout callout-success fade in text-semibold">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <?= $this->session->flashdata('message'); ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-10 col-xs-12">
        <?php
        $attributes = array("class" => "form-horizontal", "id" => "search", "name" => "search");
        echo form_open('superadmin/searchschedule', $attributes);
        ?>
        <div class="form-group">
            <div class="col-md-3">
                <input type="text" name="search" placeholder="Seach Username, Date, Time" class="form-control input-sm">
            </div>

            <div class="col-md-1">
                <input id="btn_search" name="btn_search" type="submit" class="btn btn-primary btn-xs" value="Search" />
            </div>
            <div class="col-md-1">
                <a class="btn btn-danger btn-xs" href="<?= site_url('superadmin/schedule'); ?>">Cancel</a>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>

    <div class="col-md-2 text-right">
        <a class="btn btn-success btn-sm" href="<?= site_url('superadmin/addschedule'); ?>"><i class="icon-plus-circle"></i> Add Schedule</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th width="5%">No.</th>
            <th>User Name</th>
            <th width="10%" class="text-center">Schedule Date</th>
            <th width="20%" class="text-center">Time</th>
            <th class="text-center">Admin</th>
            <th width="10%" class="text-center">Week</th>
            <th width="10%" class="text-center">Month</th>
            <th width="10%" class="text-center">Year</th>
            <th class="text-right">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($list as $l):
            ?>
            <tr>
                <td><?= $i++; ?></td>
                <td><?= $l->user_name; ?></td>
                <td class="text-center"><?= $l->schedule_date; ?></td>
                <td class="text-center"><?= $l->from_time . ' - ' . $l->to_time; ?></td>
                <td class="text-center">
                    <label class="label label-success"><?= get_adminname_helper($l->admin_id); ?></label>
                </td>
                <td class="text-center"><?= $l->week; ?></td>
                <td class="text-center"><?= $l->month; ?></td>
                <td class="text-center"><?= $l->year; ?></td>
                <td class="text-right">
                    <a title="edit" href="<?= site_url('superadmin/addschedule/' . $l->schedule_id); ?>"><i class="icon-pencil3"></i></a> | 
                    <a title="remove" onclick="return confirm('Are You Sure?');" href="<?= site_url('superadmin/delete_schedule/' . $l->schedule_id); ?>" class="text-danger"><i class="icon-cancel"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (isset($pagination) && $pagination != NULL) { ?>
    <div class="well text-right hidden-print">
        <div class="well text-right">
            <?= $pagination; ?>
        </div>
    </div>
<?php } ?>
<br>