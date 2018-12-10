<!-- Page header -->
<div class="page-header">
    <div class="page-title">
        <h3>Dashboard <small>Welcome <?= $this->logged_in_name; ?>. 12 hours since last visit</small></h3>
    </div>

    <div class="range">
        <ul class="statistics">
            <li style="cursor: default">
                <div class="statistics-info">
                    <a title="Date" class="bg-success btn btn-xs"><?= date('jS', strtotime(date('Y-m-d H:i:s'))); ?></a>
                    <strong><?= date('F', strtotime(date('Y-m-d H:i:s'))); ?> </strong>
                </div>
                <div class="progress progress-micro">
                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                        <span class="sr-only">60% Complete</span>
                    </div>
                </div>
                <span><?= date('l', strtotime(date('Y-m-d H:i:s'))); ?> - <?= date('Y', strtotime(date('Y-m-d H:i:s'))); ?></span>
            </li>
        </ul>
    </div>
</div>
<!-- /page header -->

<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('qamember/index') ?>">Home</a></li>
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