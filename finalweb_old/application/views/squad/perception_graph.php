<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('squad/index') ?>">Home</a></li>
        <li>Perception Gap</li>
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
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-body">
                <div id="chartContainer" style="height: 470px; width: 100%;"></div>
            </div>
        </div>

    </div>
</div>

<?php
$dataPoints = array(
    array("y" => 25, "label" => "Sunday"),
    array("y" => 15, "label" => "Monday"),
    array("y" => 25, "label" => "Tuesday"),
    array("y" => 5, "label" => "Wednesday"),
    array("y" => 10, "label" => "Thursday"),
    array("y" => 0, "label" => "Friday"),
    array("y" => 20, "label" => "Saturday")
);

//var_dump($dataPoints);
//var_dump($currentWeek);
?>

<script type="text/javascript">
    window.onload = function () {

        var chart = new CanvasJS.Chart("chartContainer", {
            backgroundColor: "#F5DEB3",
            animationEnabled: true,
            theme: "light1",
            title: {
                text: "Perception Graph"
            },
            axisY: {
                lineColor: "red",
                gridColor: "orange",
                title: "Perception Gap",
                minimum: -50,
                maximum: 50
            },
            data: [{
                    type: "line",
                    dataPoints: <?php echo json_encode(array_reverse($currentWeek), JSON_NUMERIC_CHECK); ?>
                }]
        });
        chart.render();
    }
</script>