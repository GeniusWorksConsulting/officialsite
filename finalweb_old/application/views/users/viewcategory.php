<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('users/index') ?>">Dashboard</a></li>
        <li class="active">View Category</li>
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

<?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
    <div class="row">
        <div class="col-sm-12 text-right">  
            <a class="btn btn-success btn-sm" href="<?= site_url('users/addcategory') ?>"><i class="icon-plus-circle"></i> ADD</a>
        </div>
    </div>
    <br>
<?php } ?>

<div class="panel panel-default">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th>Category</th>
                <th class="text-center">Weighting</th>
                <th class="text-center">Assessment</th>
                <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
                    <th class="text-right">Action</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list as $i) { ?>
                <tr>
                    <td class="text-center"><?= $i->cat_id; ?></td>
                    <td><?= $i->name; ?></td>
                    <td class="text-center"><?= $i->weighting; ?></td>
                    <td class="text-center"><?= $i->type; ?></td>
                    <?php if ($this->ion_auth->in_group(array('qamember'))) { ?>
                        <td class="text-right">
                            <a title="Edit" class="" href="<?= site_url('users/addcategory/' . $i->cat_id) ?>"><i class="icon-pencil3"></i></a>&nbsp;|
                            <a title="Delete" onclick="return confirm('Want to delete?');" class="text-danger" href="<?= site_url('users/deletecategory/' . $i->cat_id) ?>"><i class="icon-remove2"></i></a>
                        </td>
                    <?php } ?>
                </tr>
            <?php }
            ?>

            <?php
            if (!$list) {
                echo '<tr><td class="text-danger text-center" colspan="8">No record found...</tr>';
            }
            ?>
        </tbody>
    </table>
</div>