<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('users/index') ?>">Home</a></li>
        <li>Reports</li>
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

<?php
$start_date = '2018-07-01';
$end_Date = '2018-08-08';

$startTime = strtotime($start_date);
$endTime = strtotime($end_Date);

$weeks = array();
$date = new DateTime();
$i = 0;
while ($startTime < $endTime) {
    $weeks[$i]['week'] = date('W', $startTime);
    $weeks[$i]['year'] = date('Y', $startTime);
    $date->setISODate($weeks[$i]['year'], $weeks[$i]['week']);
    $weeks[$i]['Monday'] = $date->format('Y-m-d');
    $weeks[$i]['Sunday'] = date('Y-m-d', strtotime($weeks[$i]['Monday'] . "+6 days"));
    $startTime += strtotime('+1 week', 0);
    $i++;
}
//var_dump($weeks);
?>

<div class="row">
    <div class="col-sm-4 col-sm-offset-4 col-xs-12">
        <h4 class="text-center">ASSESSMENTS REPORTS</h4>

        <div class="panel panel-default">
            <div class="panel-body">
                <?php
                $attributes = array('id' => 'reportForm', 'name' => 'reportForm', 'class' => '');
                echo form_open('', $attributes);
                ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Date Range</label>
                            <input type="text" name="daterange" class="form-control daterange" value="" />
                            <input type="hidden" id="startdate" name="startdate" />
                            <input type="hidden" id="enddate" name="enddate" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Select Squads</label>
                            <select class="form-control" name="squad_group" id="squad_group">
                                <option value="">Select</option>
                                <?php foreach ($squad_group as $squad) { ?>
                                    <option value="<?= $squad->squad_group; ?>"><?= $squad->squad_name; ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info btn-sm">
                                Get Report  
                            </button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-9" id="reportDiv">

    </div>

    <div class="col-sm-3" id="weekDiv">

    </div>
</div>

<script type="text/javascript">
    $(function ($) {
        $("form[name='reportForm']").validate({
            focusInvalid: false,
            rules: {
                daterange: "required",
                squad_group: "required"
            },
            messages: {
                daterange: "Please select date range.",
                squad_group: "This field is required."
            },
            errorClass: "text-danger",
            errorElement: "span",
            submitHandler: function (form) {
                $.ajax({
                    type: "POST",
                    url: '<?= site_url('users/getreports'); ?>',
                    data: $('form').serialize(),
                    dataType: "html",
                    beforeSend: function () {
                        $("#dv_loader").show();
                    },
                    success: function (response) {
                        console.log(response);
                        var jsonData = JSON.parse(response);
                        var length = jsonData.weeks.length;

                        if (length > 0) {
                            $('#reportDiv').html('<div class="panel panel-default"><table class="table table-bordered" id="squad_table"></table></div>');
                            $('#squad_table').DataTable({
                                //Assigning data to table
                                "processing": true,
                                "paging": false,
                                "bFilter": false,
                                "ordering": false,
                                "data": jsonData.users,
                                "bSort": false,
                                "bInfo": false,
                                "columnDefs": [
                                    {"title": "Consultant name", "targets": 0},
                                    {"title": "Email/Webchat", "targets": 1},
                                    {"title": "Voice", "targets": 2},
                                    {"title": "Overall Score", "targets": 3}
                                ],
                                "columns": [
                                    {"data": 'first_name',
                                        "render": function (data, type, row) {
                                            return row.first_name + ' ' + row.last_name;
                                        }
                                    },
                                    {"data": 'text',
                                        "render": function (data, type, row) {
                                            return (row.text / length).toFixed(2);
                                        }
                                    },
                                    {"data": 'voice',
                                        "render": function (data, type, row) {
                                            return (row.voice / length).toFixed(2);
                                        }
                                    },
                                    {"data": 'text',
                                        "render": function (data, type, row) {
                                            return (((row.voice / length) + (row.text / length)) / 2).toFixed(2);
                                        }
                                    }
                                ]
                            });

                            $('#weekDiv').html('<div class="panel panel-default"><h4 class="text-center">Week List</h4><table class="table table-bordered" id="week_table"></table></div>');
                            $('#week_table').DataTable({
                                //Assigning data to table
                                "processing": true,
                                "paging": false,
                                "bFilter": false,
                                "ordering": false,
                                "data": jsonData.weeks,
                                "bSort": false,
                                "bInfo": false,
                                "columnDefs": [
                                    {"title": "Start Date", "targets": 0},
                                    {"title": "End Date", "targets": 1}
                                ],
                                "columns": [
                                    {"data": 'from_date'},
                                    {"data": 'to_date'}
                                ]
                            });
                        }

                        $(':input', '#reportForm')
                                .not(':button, :submit, :reset, :hidden')
                                .val('')
                                .removeAttr('checked')
                                .removeAttr('selected');
                        return false;
                    },
                    complete: function () {
                        $("#dv_loader").hide();
                    }
                });
            }
        });

    });
</script>