<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('scheduler/index') ?>">Dashboard</a></li>
        <li class="active">Add Schedule</li>
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
                $attributes = array('id' => 'scheduleFrom', 'name' => 'scheduleFrom', 'class' => '');
                echo form_open_multipart('scheduler/addSchedule', $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Select SQUAD *</label>
                            <select name="squad_group" id="squad_group" class="form-control">
                                <option value="">Select</option>
                                <?php foreach ($list as $row) { ?>
                                    <option value="<?= $row->squad_group; ?>"><?= $row->squad_name; ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('squad_group'); ?></span>
                        </div>

                        <div class="col-sm-6">
                            <label>SQUAD Member *</label>
                            <select name="user_id" id="user_id" class="form-control">
                            </select>
                            <span class="text-danger"><?php echo form_error('user_id'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <label>Date *</label>
                            <input type="text" name="date" class="datepicker form-control">
                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                        </div>

                        <div class="col-sm-4">
                            <label>From Time *</label>
                            <!--<div class='input-group date datetimepicker3'>
                                <input name="from_time" type='text' class="form-control" />
                                <span class="input-group-addon">
                                    <span class="icon-watch"></span>
                                </span>
                            </div>-->
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
                            <span class="text-danger"><?php echo form_error('from_time'); ?></span>
                        </div>

                        <div class="col-sm-4">
                            <label>To Time *</label>
                            <!--<div class='input-group date datetimepicker3'>
                                <input name="to_time" type='text' class="form-control" />
                                <span class="input-group-addon">
                                    <span class="icon-watch"></span>
                                </span>
                            </div>-->
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
                            <span class="text-danger"><?php echo form_error('to_time'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button name="submit" type="submit" class="btn btn-info" tabindex="13">ADD</button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(function ($) {
        $('#squad_group').on('change', function (e) {
            var squad_group = this.value;
            if (squad_group) {
                $.ajax({
                    type: "post",
                    url: '<?= site_url('scheduler/getmembers'); ?>',
                    data: {'squad_group': squad_group},
                    dataType: "json",
                    beforeSend: function () {
                        $("#dv_loader").show();
                    },
                    success: function (json) {
                        console.log(json);
                        $('select[name="user_id"]').empty();
                        $('select[name="user_id"]').append('<option value="">Select...</option>');
                        $.each(json, function (s, value) {
                            $('select[name="user_id"]').append('<option value="' + value.id + '">' + value.first_name + '</option>');
                        });
                    },
                    complete: function () {
                        $("#dv_loader").hide();
                    }
                });
            }
        });
    });
</script>