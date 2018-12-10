<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/getweeks') ?>">Timelines</a></li>
        <li class="active">Add/Edit</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-6 col-md-offset-3 col-xs-12">
        <?php if ($this->session->flashdata('message')) { ?>
            <div id="msg" class="callout callout-danger fade in text-semibold">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $this->session->flashdata('message'); ?>
            </div>
        <?php } ?>

        <h1 class="text-center">Add/Edit Week</h1>
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                $attributes = array('id' => 'addweek', 'name' => 'addweek', 'class' => 'form-bordered');
                echo form_open('', $attributes);
                ?>

                <input type="hidden" name="id" value="<?= isset($row) ? $row->id : ''; ?>">
                <div class="form-group">
                    <div class="row">

                        <div class="col-md-4">
                            <label>Week Number:</label>
                            <select class="form-control" name="week" id="week">
                                <option value="">-- Select --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <span class="text-danger"><?php echo form_error('week'); ?></span>
                        </div>

                        <div class="col-md-4">
                            <label>Month:</label>
                            <select class="form-control" name="month" id="month">
                                <option value="">-- Select --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                            <span class="text-danger"><?php echo form_error('month'); ?></span>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Week Start:</label>
                            <input type="text" name="from_date" readonly class="datepicker form-control" value="<?= isset($row) ? $row->from_date : $this->form_validation->set_value('from_date'); ?>">
                            <span class="text-danger"><?php echo form_error('from_date'); ?></span>
                        </div>

                        <div class="col-md-6">
                            <label>Week Start:</label>
                            <input type="text" name="to_date" readonly class="datepicker form-control" value="<?= isset($row) ? $row->to_date : $this->form_validation->set_value('to_date'); ?>">
                            <span class="text-danger"><?php echo form_error('to_date'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">

                        <div class="col-md-6">
                            <label>Select Admin:</label>
                            <select class="form-control" name="user_id" id="user_id">
                                <option value="">-- Select --</option>
                                <?php foreach ($users as $u) { ?>
                                    <option value="<?= $u->id; ?>"><?= $u->first_name . ' ' . $u->last_name; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('user_id'); ?></span>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button onclick="this.disabled = true;this.value = 'Sending...';this.form.submit();" type="submit" class="btn btn-primary btn-xs">
                                Submit  
                            </button>
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
        $('#week').val('<?= $row->week; ?>');
        $('#month').val('<?= $row->month; ?>');
        $('#user_id').val('<?= $row->user_id; ?>');
    </script>
<?php } ?>