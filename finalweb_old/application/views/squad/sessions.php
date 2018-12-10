<style type="text/css">
    #clockdiv{
        font-family: sans-serif;
        color: #fff;
        display: inline-block;
        font-weight: 100;
        text-align: center;
        font-size: 30px;
    }

    #clockdiv > div{
        padding: 10px;
        border-radius: 3px;
        background: #00BF96;
        display: inline-block;
    }

    #clockdiv div > span{
        padding: 15px;
        border-radius: 3px;
        background: #00816A;
        display: inline-block;
    }

    .smalltext{
        padding-top: 5px;
        font-size: 16px;
    }
</style>
<br>
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li><a href="<?= site_url('squad/index') ?>">Home</a></li>
        <li>Squad Session</li>
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
    <div class="col-sm-4 col-sm-offset-4 col-xs-12">

        <div class="form-group">
            <?php if ($session) { ?>
                <button type="button" id="btn_stop" class="btn btn-success btn-block btn-lg"><i class="icon-pause"></i> Stop Session</button>
            <?php } else {
                ?>
                <button type="button" id="btn_start" class="btn btn-primary btn-block btn-lg"><i class="icon-play2"></i> Start Session</button>
            <?php }
            ?>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-xs-12 text-center">

        <div id="clockdiv">
            <div>
                <span class="hours">00</span>
                <div class="smalltext">Hours</div>
            </div>
            <div>
                <span class="minutes">00</span>
                <div class="smalltext">Minutes</div>
            </div>
            <div>
                <span class="seconds">00</span>
                <div class="smalltext">Seconds</div>
            </div>
        </div>

    </div>
</div>
<br>

<script type="text/javascript">
    var myVar, h, m, s;

    $('#btn_start').click(function () {
        $.ajax({
            type: "post",
            url: '<?= site_url('squad/start_session'); ?>',
            dataType: "json",
            beforeSend: function () {
                $("#dv_loader").show();
            },
            success: function (response) {
                console.log(response);
                if (response['status'] === 0) {
                    window.location = '<?= site_url('squad/session'); ?>';
                } else {
                    alert('Error');
                }
            },
            error: function (exception) {
                console.log(exception);
            },
            complete: function () {
                $("#dv_loader").hide();
            }
        });
    });



    function myTimer() {
        if (s === 60) {
            s = 1;
            m++;
        }

        if (m === 60) {
            m = 1;
            h++;
        }
        var clock = document.getElementById('clockdiv');
        var hoursSpan = clock.querySelector('.hours');
        var minutesSpan = clock.querySelector('.minutes');
        var secondsSpan = clock.querySelector('.seconds');

        hoursSpan.innerHTML = ('0' + h).slice(-2);
        minutesSpan.innerHTML = ('0' + m).slice(-2);
        secondsSpan.innerHTML = ('0' + s).slice(-2);
        s++;
    }
</script>

<?php
if ($session) {
    $start = strtotime($session->start_time);
    $end = strtotime(date('Y-m-d H:i:s'));
    $diff = $end - $start;

    $hours = floor($diff / (60 * 60));
    $minutes = $diff - $hours * (60 * 60);
    $seconds = $diff % 60;
    ?>
    <script type="text/javascript">
        $('#timer').append('<div class="form-group"><input type="text" id="txtTime" readonly class="form-control text-center text-semibold"></div>');

        h = '<?= $hours; ?>';
        m = '<?= floor($minutes / 60); ?>';
        s = '<?= floor($seconds); ?>';

        myVar = setInterval(function () {
            myTimer();
        }, 1000);

        $('#btn_stop').click(function () {
            $.ajax({
                type: "post",
                url: '<?= site_url('squad/stop_session'); ?>',
                dataType: "json",
                beforeSend: function () {
                    $("#dv_loader").show();
                },
                success: function (response) {
                    console.log(response);
                    if (response['status'] === 0) {
                        window.location = '<?= site_url('squad/session'); ?>';
                    } else {
                        alert('Error');
                    }
                },
                error: function (exception) {
                    console.log(exception);
                },
                complete: function () {
                    $("#dv_loader").hide();
                }
            });
        });
    </script>
<?php } ?>