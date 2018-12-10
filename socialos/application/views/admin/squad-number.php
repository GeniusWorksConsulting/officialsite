<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('admin/index') ?>">Dashboard</a></li>
        <li>Squad-Number</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-xs-12">
        <!-- Callout -->
        <?php if ($this->session->flashdata('message')) { ?>
            <div id="message" class="callout callout-success fade in">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <p><?php echo $this->session->flashdata('message'); ?></p>
            </div>
        <?php } ?>

        <?php
        $attributes = array('id' => 'sNumber', 'name' => 'sNumber', 'class' => 'form-horizontal');
        echo form_open('admin/saveSquadnumber', $attributes);
        ?>

        <div class="panel panel-default">
            <div class="panel-body">
                <input type="hidden" name="squad_group" value="<?= isset($row) ? $row->squad_group : ''; ?>" >
                <div class="form-group">
                    <label class="col-sm-2 control-label">SQUAD Name:</label>
                    <div class="col-sm-3">
                        <input type="text" name="squad_name" class="form-control" value="<?= isset($row) ? $row->squad_name : ''; ?>">
                        <div class="text-danger"><?php echo form_error('squad_name'); ?></div>
                    </div>

                    <label class="col-sm-2 control-label">Site Name:</label>
                    <div class="col-sm-3">
                        <input type="text" name="site" class="form-control" value="<?= isset($row) ? $row->site : ''; ?>">
                        <div class="text-danger"><?php echo form_error('site'); ?></div>
                    </div>

                    <div class="col-sm-2">
                        <input type="submit" value="Submit" class="btn btn-primary btn-xs">
                    </div>
                </div>
            </div>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
<!-- /right labels -->

<div class="panel panel-default">
    <div class="datatable">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Name</th>
                    <th>Site</th>
                    <th width="5%">Status</th>
                    <th width="10%"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $s) { ?>
                    <tr>
                        <td><?= $s->squad_group ?></td>
                        <td><?= $s->squad_name ?></td>
                        <td><?= $s->site ?></td>
                        <td class="text-center">
                            <?php
                            if ($s->status == 0) {
                                echo 'Active';
                            }
                            ?>
                        </td>
                        <td class="text-right">
                            <a title="Edit" href="<?= site_url('admin/squadnumber/' . $s->squad_group . ''); ?>" class="btn btn-icon btn-default btn-xs"><i class="icon-pencil3"></i></a>
                        </td>
                    </tr>
                <?php }
                ?>
            </tbody>
        </table>
    </div>
</div>
<!-- /striped datatable inside panel -->