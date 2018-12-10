<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('superadmin/index') ?>">Dashboard</a></li>
        <li><a href="<?= site_url('superadmin/schedule') ?>">Schedule</a></li>
        <li class="active">Add/Edit</li>
    </ul>
</div>
<!-- /breadcrumbs line -->

<div class="row">
    <div class="col-md-8 col-md-offset-2 col-xs-12">
        <?php if ($this->session->flashdata('message')) { ?>
            <div id="msg" class="callout callout-danger fade in text-semibold">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <?= $this->session->flashdata('message'); ?>
            </div>
        <?php } ?>

        <h1 class="text-center">Add/Edit Schedule</h1>
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                $attributes = array('id' => 'schedule', 'name' => 'schedule', 'class' => 'form-bordered');
                echo form_open('', $attributes);
                ?>

                <input type="hidden" name="schedule_id" value="<?= isset($row) ? $row->schedule_id : ''; ?>">

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Select Admin:</label>
                            <select class="form-control" name="admin_id" id="admin_id">
                                <option value="">-- Select --</option>
                                <?php foreach ($users as $u) { ?>
                                    <option value="<?= $u->id; ?>"><?= $u->first_name . ' ' . $u->last_name; ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('admin_id'); ?></span>
                        </div>

                        <div class="col-md-4">
                            <label>SQUAD GROUP:</label>
                            <select class="form-control" name="squad_id" id="squad_id">
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('squad_id'); ?></span>
                        </div>

                        <div class="col-md-4">
                            <label>SQUAD Member:</label>
                            <select class="form-control" name="user_id" id="user_id">
                            </select>
                            <span class="text-danger text-semibold"><?php echo form_error('user_id'); ?></span>
                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <div class="row" style="overflow:hidden;">
                        <div class="col-md-6">
                            <div id="datetimepicker12"></div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Schedule Date:</label>
                                    <input type="text" readonly class="form-control input-sm" id="schedule_date" name="schedule_date" value="<?= isset($row) ? $row->schedule_date : $this->form_validation->set_value('schedule_date'); ?>">
                                    <span class="text-danger text-semibold"><?php echo form_error('schedule_date'); ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>From Time *</label>
                                    <select class="form-control" name="from_time" id="from_time">
                                        <option value="">Select</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="12:30 PM">12:30 PM</option>
                                        <option value="1:00 PM">1:00 PM</option>
                                        <option value="1:30 PM">1:30 PM</option>
                                        <option value="2:00 PM">2:00 PM</option>
                                        <option value="2:30 PM">2:30 PM</option>
                                        <option value="3:00 PM">3:00 PM</option>
                                        <option value="3:30 PM">3:30 PM</option>
                                        <option value="4:00 PM">4:00 PM</option>
                                        <option value="4:30 PM">4:30 PM</option>
                                        <option value="5:00 PM">5:00 PM</option>
                                        <option value="5:30 PM">5:30 PM</option>
                                        <option value="6:00 PM">6:00 PM</option>
                                        <option value="6:30 PM">6:30 PM</option>
                                        <option value="7:00 PM">7:00 PM</option>
                                        <option value="7:30 PM">7:30 PM</option>
                                        <option value="8:00 PM">8:00 PM</option>
                                        <option value="8:30 PM">8:30 PM</option>
                                        <option value="9:00 PM">9:00 PM</option>
                                        <option value="9:30 PM">9:30 PM</option>
                                        <option value="10:00 PM">10:00 PM</option>
                                        <option value="10:30 PM">10:30 PM</option>
                                        <option value="11:00 PM">11:00 PM</option>
                                        <option value="11:30 PM">11:30 PM</option>
                                        <option value="5:00 AM">5:00 AM</option>
                                        <option value="5:30 AM">5:30 AM</option>
                                        <option value="6:00 AM">6:00 AM</option>
                                        <option value="6:30 AM">6:30 AM</option>
                                        <option value="7:00 AM">7:00 AM</option>
                                        <option value="7:30 AM">7:30 AM</option>
                                        <option value="8:00 AM">8:00 AM</option>
                                        <option value="8:30 AM">8:30 AM</option>
                                        <option value="9:00 AM">9:00 AM</option>
                                        <option value="9:30 AM">9:30 AM</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="10:30 AM">10:30 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="11:30 AM">11:30 AM</option>
                                    </select>

                                    <span class="text-danger text-semibold"><?php echo form_error('from_time'); ?></span>
                                </div>

                                <div class="col-md-6">
                                    <label>To Time *</label>
                                    <select class="form-control" name="to_time" id="to_time">
                                        <option value="">Select</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="12:30 PM">12:30 PM</option>
                                        <option value="1:00 PM">1:00 PM</option>
                                        <option value="1:30 PM">1:30 PM</option>
                                        <option value="2:00 PM">2:00 PM</option>
                                        <option value="2:30 PM">2:30 PM</option>
                                        <option value="3:00 PM">3:00 PM</option>
                                        <option value="3:30 PM">3:30 PM</option>
                                        <option value="4:00 PM">4:00 PM</option>
                                        <option value="4:30 PM">4:30 PM</option>
                                        <option value="5:00 PM">5:00 PM</option>
                                        <option value="5:30 PM">5:30 PM</option>
                                        <option value="6:00 PM">6:00 PM</option>
                                        <option value="6:30 PM">6:30 PM</option>
                                        <option value="7:00 PM">7:00 PM</option>
                                        <option value="7:30 PM">7:30 PM</option>
                                        <option value="8:00 PM">8:00 PM</option>
                                        <option value="8:30 PM">8:30 PM</option>
                                        <option value="9:00 PM">9:00 PM</option>
                                        <option value="9:30 PM">9:30 PM</option>
                                        <option value="10:00 PM">10:00 PM</option>
                                        <option value="10:30 PM">10:30 PM</option>
                                        <option value="11:00 PM">11:00 PM</option>
                                        <option value="11:30 PM">11:30 PM</option>
                                        <option value="5:00 AM">5:00 AM</option>
                                        <option value="5:30 AM">5:30 AM</option>
                                        <option value="6:00 AM">6:00 AM</option>
                                        <option value="6:30 AM">6:30 AM</option>
                                        <option value="7:00 AM">7:00 AM</option>
                                        <option value="7:30 AM">7:30 AM</option>
                                        <option value="8:00 AM">8:00 AM</option>
                                        <option value="8:30 AM">8:30 AM</option>
                                        <option value="9:00 AM">9:00 AM</option>
                                        <option value="9:30 AM">9:30 AM</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="10:30 AM">10:30 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="11:30 AM">11:30 AM</option>
                                    </select>

                                    <span class="text-danger text-semibold"><?php echo form_error('to_time'); ?></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <script type="text/javascript">
                    $(function () {
                        $('#datetimepicker12').datepicker({
                            dateFormat: 'yy-mm-dd',
                            inline: true,
                            sideBySide: true,
                            onSelect: function (dateText, inst) {
                                $('#schedule_date').val(dateText);
                            }
                        }).datepicker("setDate", '<?= isset($row) ? $row->schedule_date : date('Y-m-d'); ?>');
                    });
                </script>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button onclick="this.disabled = true;
                                    this.value = 'Sending...';
                                    this.form.submit();" type="submit" class="btn btn-primary btn-xs">
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
        $('#admin_id').val('<?= $row->admin_id; ?>');
        var admin_id = '<?= $row->admin_id; ?>';

        if (admin_id) {
            $.ajax({
                type: "post",
                url: '<?= site_url('ajaxrequest/getsquads'); ?>',
                data: {'admin_id': admin_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="user_id"]').empty();
                        $('select[name="squad_id"]').empty();
                        $('select[name="squad_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="squad_id"]').append('<option value="' + value.squad_id + '">' + value.squad_name + '</option>');
                        });

                        $('select[name="squad_id"]').val('<?= $row->squad_id; ?>');
                        $('select[name="squad_id"]').trigger("change");
                    } else {
                        $('select[name="user_id"]').empty();
                        $('select[name="squad_id"]').empty();
                        alert(json['message']);
                    }
                },
                complete: function () {
                    $("#dv_loader").hide();
                }
            });
        }

        $('#from_time').val('<?= $row->from_time; ?>');
        $('#to_time').val('<?= $row->to_time; ?>');
    </script>
<?php } ?>

<script type="text/javascript">
    $('#admin_id').on('change', function () {
        var admin_id = this.value;

        if (admin_id) {
            $.ajax({
                type: "post",
                url: '<?= site_url('ajaxrequest/getsquads'); ?>',
                data: {'admin_id': admin_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="user_id"]').empty();
                        $('select[name="squad_id"]').empty();
                        $('select[name="squad_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="squad_id"]').append('<option value="' + value.squad_id + '">' + value.squad_name + '</option>');
                        });
                    } else {
                        $('select[name="user_id"]').empty();
                        $('select[name="squad_id"]').empty();
                        alert(json['message']);
                    }
                },
                complete: function () {
                    $("#dv_loader").hide();
                }
            });
        }
    });

    $('body').delegate('#squad_id', 'change', function () {
        var squad_id = this.value;

        if (squad_id) {
            $.ajax({
                type: "post",
                url: '<?= site_url('ajaxrequest/getsquadusers'); ?>',
                data: {'squad_id': squad_id},
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (json) {
                    console.log(json);
                    if (json['status']) {
                        $('select[name="user_id"]').empty();
                        $('select[name="user_id"]').append('<option value="">Select...</option>');
                        $.each(json['data'], function (s, value) {
                            $('select[name="user_id"]').append('<option value="' + value.user_id + '">' + value.user_name + '</option>');
                        });

                        '<?php if (isset($row)) { ?>';
                            $('select[name="user_id"]').val('<?= $row->user_id; ?>');
                            '<?php } ?>';
                    } else {
                        $('select[name="user_id"]').empty();
                        alert(json['message']);
                    }
                },
                complete: function () {
                    $("#dv_loader").hide();
                }
            });
        }
    });
</script>