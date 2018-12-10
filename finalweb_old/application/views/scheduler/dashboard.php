<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('scheduler/index') ?>">Dashboard</a></li>
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
        <a class="btn btn-success btn-sm" href="<?= site_url('scheduler/addSchedule') ?>"><i class="icon-plus-circle"></i> Add Schedule</a>
    </div>
</div>
<br>

<!-- Default table inside panel -->
<table class="table table-bordered">
    <thead>
        <tr>
            <!--<th>Id</th>-->
            <th>Name</th>
            <th class="text-center">Squad Name</th>
            <th class="text-center">Scheduled Date</th>
            <th>Scheduled Time</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($list as $row): ?>
            <tr>
                <!--<td><?= $row->id ?></td>-->
                <td><?= $row->user_name; ?></td>
                <td class="text-center"><?= $row->squad_name; ?></td>
                <td class="text-center"><?= $row->date; ?></td>
                <td><?= $row->from_time . ' - '. $row->to_time; ?></td>
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