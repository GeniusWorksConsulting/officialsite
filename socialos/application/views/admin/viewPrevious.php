<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Dashboard</a></li>
        <li class="active">View Previous</li>
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

<div class="panel panel-default">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="5%" class="text-center">No.</th>
                <th class="text-center">Week</th>
                <th class="text-center">Month</th>
                <th class="text-center">Year</th>
                <th class="text-right">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            foreach ($list as $i) {
                ?>
                <tr>
                    <?php
                    $attributes = array('method' => 'GET');
                    echo form_open('admin/viewAssessment', $attributes);
                    ?>
                    <td class="text-center"><?= $count; ?></td>
                    <td class="text-center">
                        <?= $i->week; ?>
                        <input type="hidden" name="week" value="<?= $i->week; ?>">
                    </td>
                    <td class="text-center">
                        <?= $i->month; ?> <span class="text-uppercase">(<?= date("F", mktime(0, 0, 0, $i->month, 1, 2011)) ?>)</span>
                        <input type="hidden" name="month" value="<?= $i->month; ?>">
                    </td>
                    <td class="text-center">
                        <?= $i->year; ?>
                        <input type="hidden" name="year" value="<?= $i->year; ?>">
                        <input type="hidden" name="squad">
                    </td>
                    <td class="text-right">
                        <button type="submit" title="View" class="btn btn-success btn-sm btn-icon" href="<?= site_url('qamember/addcategory/') ?>"><i class="icon-eye2"></i></button>
                    </td>
                    <?php echo form_close(); ?>
                </tr>
                <?php
                $count++;
            }
            ?>

            <?php
            if (!$list) {
                echo '<tr><td class="text-danger text-center" colspan="8">No record found...</tr>';
            }
            ?>
        </tbody>
    </table>
</div>
