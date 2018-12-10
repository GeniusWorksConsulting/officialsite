<?php
$query_string = "";
$style = "";
if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
    $query_string = "&week=" . $_GET['week'] . "&month=" . $_GET['month'] . "&year=" . $_GET['year'];
    $style = "display:none;";
}
?>
<div class="col-sm-9 col-md-9 col-lg-10" style="margin-top:5%;">
    <?php if ($this->session->flashdata('message_eror') != '') { ?>				
        <div class="alert alert-danger alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_eror'); ?></strong>				</div>
    <?php } ?>
    <?php if ($this->session->flashdata('message_success') != '') { ?>				
        <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success'); ?></strong>				</div>
    <?php } ?>
    <div class="content">
        <div class="container">
            <!-- Page-Title -->                        
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">vb starter dashboard</h4>
                    <p class="text-muted page-title-alt"></p>

                    <h3 class="marginbottomzero"  style="font-weight:700;"><?php echo $this->session->first_name; ?>'s Dashboard</h3>


                    <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12" style="/*margin-bottom: 50px;*/">
                        <h5 class="" style="font-weight:700;">squad 1</h5>
                        <div class="text-center col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-bottom: 50px;">
                            <?php for ($i = 0; $i < sizeof($groupmember); $i++) { ?>
                                <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                    <h5 style="font-weight:700;"><?php echo $groupmember[$i]['first_name']; ?></h5>
                                    <h3><?php
                                        echo $groupmember[$i]['percentage'];
                                        if ($groupmember[$i]['percentage'] != "NA") {
                                            echo "%";
                                        }
                                        ?></h3>
                                    <?php if (empty($groupmember[$i]['text_rating']) || empty($groupmember[$i]['voice_rating'])) { ?>
                                        <i class="fa fa-user" style="width:100%;font-size: 90px;color: 2f2f2f;"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-user" style="width:100%;font-size: 90px;color: darkseagreen;"></i>
                                    <?php } ?>
                                    <?php if (!empty($groupmember[$i]['text_rating'])) { ?>
                                        <a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember[$i]['id']; ?>&type=text<?php echo $query_string; ?>"><button class="textcomplete btn" style="background: #1a53ff;">Text</button></a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember[$i]['id']; ?>&type=text" style="<?php echo $style; ?>"><button class="textpending btn">Text</button></a>
                                    <?php } ?>
                                    <?php if (!empty($groupmember[$i]['voice_rating'])) { ?>
                                        <a href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $groupmember[$i]['id']; ?>&type=voice<?php echo $query_string; ?>"><button class="voicecomplete btn" style="background: #1a53ff;">Voice</button></a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $groupmember[$i]['id']; ?>&type=voice" style="<?php echo $style; ?>"><button class="voicepending btn">Voice</button></a>
                                    <?php } ?>
                                    <?php
                                    if (isset($groupmember[$i]['user_percentage'])) {
                                        $diff = abs($groupmember[$i]['user_percentage'] - $groupmember[$i]['percentage']);
                                        if ($diff > 5) {
                                            ?>
                                            <button class="btn btn-danger checkpercentage" data-id="<?php echo $groupmember[$i]['id']; ?>"><?php echo $diff; ?>%</button>
                                            <a class="checkpercentage_toggle<?php echo $groupmember[$i]['id']; ?>" style="display:none;" href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember[$i]['id']; ?>&sender_receiver=same&type=voice&sender_id=<?php echo $this->session->user_id; ?>&receiver_id=<?php echo $groupmember[$i]['id']; ?><?php echo $query_string; ?>">
                                                <button class="voicecomplete btn" style="background: #1a53ff;">Voice</button>
                                            </a>
                                            <a class="checkpercentage_toggle<?php echo $groupmember[$i]['id']; ?>" style="display:none;" href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember[$i]['id']; ?>&sender_receiver=same&type=text&sender_id=<?php echo $this->session->user_id; ?>&receiver_id=<?php echo $groupmember[$i]['id']; ?><?php echo $query_string; ?>"><button class="textcomplete btn" style="background: #1a53ff;">Text</button></a>
                                            <?php
                                        } else {
                                            ?>
                                            <button class="btn btn-primary checkpercentage"><?php echo $diff; ?>%</button>
                                            <?php
                                        }
                                    }
                                    ?>

                                </div>
<?php } ?>
                        </div>

<!--<h3 class="marginbottomzero" style="clear:both; font-weight:700;"><?php echo $this->session->first_name; ?> HAS BEEN ASSESSED BY:</h3>-->
                        <h5 class="margintopzero" style="font-weight:700;">squad 2</h5>

                        <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 ">
<?php for ($i = 0; $i < sizeof($groupmember2); $i++) { ?>
                                <div class="textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                    <h5 style="font-weight:700;"><?php echo $groupmember2[$i]['first_name']; ?></h5>
                                    <h3><?php
                                        echo $groupmember2[$i]['percentage'];
                                        if ($groupmember2[$i]['percentage'] != "NA") {
                                            echo "%";
                                        }
                                        ?></h3>
                                    <?php if (empty($groupmember2[$i]['text_rating']) || empty($groupmember2[$i]['voice_rating'])) { ?>
                                        <i class="fa fa-user" style="width:100%;font-size: 90px;color: 2f2f2f;"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-user" style="width:100%;font-size: 90px;color: darkseagreen;"></i>
                                    <?php } ?>
                                    <?php if (!empty($groupmember2[$i]['text_rating'])) { ?>
                                        <a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember2[$i]['id']; ?>&type=text<?php echo $query_string; ?>"><button class="textcomplete btn" style="background: #1a53ff;">Text</button></a>
                                    <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember2[$i]['id']; ?>&type=text" style="<?php echo $style; ?>"><button class="textpending btn">Text</button></a>
                                    <?php } ?>
                                    <?php if (!empty($groupmember2[$i]['voice_rating'])) { ?>
                                        <a href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $groupmember2[$i]['id']; ?>&type=voice<?php echo $query_string; ?>"><button class="voicecomplete btn" style="background: #1a53ff;">Voice</button></a>
                                <?php } else { ?>
                                        <a href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $groupmember2[$i]['id']; ?>&type=voice"><button class="voicepending btn" style="<?php echo $style; ?>">Voice</button></a>
    <?php } ?>

                                </div>
<?php } ?>

                            <!--Sanjay's Change -->
                        </div>
                        <!--Sanjay's Change -->
                        <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12" ></div>


                    </div>
<?php if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
    
} else {
    ?>
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12 text-center" style="margin-top:15px;padding: 0px;margin-bottom: 20px;">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="padding: 0px;">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#aef5ae;">
                                    <h1 style="color: #464545;font-size: 30px;font-weight: 900;">Squad 1 tribe</h1>
                                    <h3 style="color: #464545;font-size: 20px;font-weight: 900;">FOR this week</h3>
                                    <div class="col-sm-5 col-md-5 col-lg-5 col-xs-5">
                                        <i class="fa fa-users" style="float:right;font-size:60px;"></i>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
                                        <span style="float:left;">
                                            <h1 class="owncolor" style="font-weight: 900;margin-bottom: 0px;line-height: 100%;font-size: 30px;margin-top:5px;"><?php echo $mytribe['percentage']; ?>%</h1>
                                            <h4  class="owncolor" style="font-weight: 900;font-size: 20px;margin-top: 0px;">customer love</h4>
                                        </span>
                                    </div>
                                    <h4 style="clear:both;padding-top:10px;color: #36d636;font-weight: 900;"><?php echo $mytribe['complete_member']; ?> out of <?php echo $mytribe['total_member']; ?></h4>
                                    <h3 style="color: #464545;font-size: 20px;font-weight: 900;">HAVE COMPLETED</br> THEIR SQUAD ASSESSMENTS</h3>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="padding-right:0px;padding: 0px;margin-top: 10px;">
                                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#aef5ae;">
                                    <h1 style="color: #464545;font-size: 30px;font-weight: 900;">Squad 2 tribe</h1>
                                    <h3 style="color: #464545;font-size: 20px;font-weight: 900;">FOR this week</h3>
                                    <div class="col-sm-5 col-md-5 col-lg-5 col-xs-5">
                                        <i class="fa fa-users" style="float:right;font-size:60px;"></i>
                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
                                        <span style="float:left;">
                                            <h1 class="owncolor" style="font-weight: 900;margin-bottom: 0px;line-height: 100%;font-size: 30px;margin-top:5px;"><?php echo $othertribe['percentage']; ?>%</h1>
                                            <h4 class="owncolor" style="font-weight: 900;font-size: 20px;margin-top: 0px;">customer love</h4>
                                        </span>
                                    </div>
                                    <h4 style="clear:both;padding-top:10px;color: #36d636;font-weight: 900;"><?php echo $othertribe['complete_member']; ?> out of <?php echo $othertribe['total_member']; ?></h4>
                                    <h3 style="color: #464545;font-size: 20px;font-weight: 900;">HAVE COMPLETED</br> THEIR SQUAD ASSESSMENTS</h3>
                                </div>
                            </div>
                        </div>
<?php } ?>
                    <!--	<div class="col-sm-4 col-md-4 col-lg-4 col-xs-12" style="float:right;">
                                                    <h4 style="font-size: 14px;color:#000;font-weight: 700;">Squad goal for <?php echo date("F"); ?> : Monthly goal</h4>
                                            
                                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 ownbg" style="padding:21px 0px 21px 21px;height:130px;">
                                                            <div class="col-sm-5 col-md-5 col-lg-5 col-xs-5">
                                                                    <i class="fa fa-users" style="font-size:90px;color:#fff;"></i>
                                                            </div>
                                                            <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
                                                                    <span>
                                                                            <h1 style="color:#fff;font-weight:900;margin-bottom:0px;font-size: 45px;text-align:center;"><?php echo $squal_monthly_goal; ?>%</h1>
                                                                            <h4 style="color:#fff;font-weight:900;margin-top:0px;text-align:center;">customer love</h4>
                                                                    </span>
                                                            </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12" style="padding-left:0px;">
                                                            <h4 style="font-size: 13px;color:#000;font-weight: 700;">MY GOAL FOR <?php echo date("F"); ?>:</h4>
                                            
                                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center ownbg" style="padding:21px 0px;height:130px;display: table;">
<?php if (empty($check_month_goal_entry)) {
    ?>
                                                                            <a type="button" class="" data-toggle="modal" data-target="#setmonthlygoal" style="vertical-align: middle;display: table-cell;width:100%;font-size: 14px;color: #fff;cursor: pointer;">Click Here to Set<br/>your Monthly Goal</a>
                    <?php } else {
                        ?>
                                                                                    <span>
                                                                                            <h1 style="color:#fff;font-weight:900;margin-bottom:0px;font-size: 60px;"><?php echo $check_month_goal_entry->monthly_goal; ?>%</h1>
                                                                                            <h4 style="color:#fff;font-weight:900;margin-top:0px;">customer love</h4>
                                                                                    </span>
<?php } ?>
                                                            </div>
                                                    </div>
                                    <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12" style="padding-right: 0px;">
                                            <h4 style="font-size: 14px;color:#000;font-weight: 700;">MY WEEEKLY GOAL:</h4>
                                            
                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center ownbg" style="padding:21px 0px;height:130px;display: table;">
<?php if (empty($check_week_goal_entry)) {
    ?>
                                                                    <a type="button" class="" data-toggle="modal" data-target="#setweeklygoal" style="vertical-align: middle;display: table-cell;width:100%;font-size: 14px;color: #fff;cursor: pointer;">Click Here to Set<br/>your Weekly Goal</a>
                    <?php } else {
                        ?>
                                                                    <span>
                                                                            <h1 style="color:#fff;font-weight:900;margin-bottom:0px;font-size: 60px;"><?php echo $check_week_goal_entry->weekly_goal; ?>%</h1>
                                                                            <h4 style="color:#fff;font-weight:900;margin-top:0px;">customer love</h4>
                                                                    </span>
<?php } ?>
                                                    
                                            </div>
                                    </div>
                                    
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="padding:0px;">
                                    <h4 style="font-size: 14px;font-weight: 700;">THIS WEEK, CURRENTLY I AM ACHIEVING:</h4>
                                    
                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center ownbg" style="padding:21px 0px;height:130px;">
                                            
                                                    <span>
                                                            <h1 style="color:#fff;font-weight:900;margin-bottom:0px;font-size: 45px;text-align:center;"><?php echo $current_im_acieving; ?>%</h1>
                                                            <h4 style="color:#fff;font-weight:900;margin-top:0px;text-align:center;">customer love</h4>
                                                    </span>
                                            
                                    </div>
                            </div>	
                                    </div>-->
                    <!--
                                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="clear:both;">
                    <?php
                    $col = 3;
                    if (sizeof($allweekdetails) == 4) {
                        $col = 3;
                    }
                    if (sizeof($allweekdetails) == 3) {
                        $col = 4;
                    }
                    if (sizeof($allweekdetails) == 5) {
                        $col = 2;
                    }
                    for ($i = 0; $i < sizeof($allweekdetails); $i++) {
                        ?>
    <?php
    if ($i == sizeof($allweekdetails) - 1) {
        $padding_style = 'padding-right:0px;';
    } else {
        $padding_style = '';
    }
    ?>
                                                            <div class="col-sm-<?php echo $col; ?> col-md-<?php echo $col; ?> col-lg-<?php echo $col; ?> col-xs-12" style="margin-top: 15px;<?php echo $padding_style; ?>">
                                                                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#77d877;clear:both; ">
                                                                            <h1 style="color: #fff;font-weight: 600;margin-bottom: 0px;">Week <?php echo $allweekdetails[$i]['week']; ?></h1>
                                                                            <h5 style="margin-top: 0px;color: #fff;font-weight: 900;">Customer Love</h5>
                                                                            <h1 style="line-height: 100%;color: #fff;font-weight: 900;font-size: 60px;"><?php echo $allweekdetails[$i]['details']['percentage']; ?>%</h1>
                                                                    </div>
                                                            </div>
<?php } ?>
                                            </div>
                    -->


                    <!-- End Partition --> 
                </div>

            </div>           
        </div>
    </div>
</div>

<!-- All Model -->
<div id="setweeklygoal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Set Your Weekly Goal</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="number" class="form-control" name="weeklygoal">
                    <?php if (!empty($currentweek)) {
                        ?>
                        <input type="hidden" name="month" value="<?php echo $currentweek->month; ?>">
                        <input type="hidden" name="week" value="<?php echo $currentweek->week; ?>">
                        <input type="hidden" name="year" value="<?php echo $currentweek->year; ?>">
    <?php
}
?>
                    <input type="submit" value="Submit" class="btn btn-primary" style="margin-top: 10px;" name="weeklygoalbutton">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<div id="setmonthlygoal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Set Your Monthly Goal</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    <input type="number" class="form-control" name="monthlygoal">
                    <input type="hidden" name="month" value="<?php echo date("m"); ?>">
                    <input type="hidden" name="year" value="<?php echo date("Y"); ?>">

                    <input type="submit" value="Submit" class="btn btn-primary" style="margin-top: 10px;" name="monthlygoalbutton">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<style>
    .ownbg{
        background: #f51034;
    }
    .owncolor{
        color: #f51034;
    }
    h1,h5,h2,h3,h4{
        text-transform:uppercase;
        color:#000;
    }
    .textvoicebox{
        width:12%;
        display:inline-block;
        /*width: 9%;*/
        padding: 0px;
        text-align: center;

    }
    .marginbottomzero{
        margin-bottom:0px;
    }
    .margintopzero{
        margin-top:0px;
    }
    .marginleftzero{
        margin-left:0px;
    }
    .marginrightzero{
        margin-right:0px;
    }
    .textcomplete{
        background:red;
        width:100%;
        padding-left: 6px;
        padding-right: 6px;
        color: #fff;
        font-weight: bold;
        font-size: 10px;
    }
    .voicecomplete{
        background:red;
        width:100%;
        color: #fff;
        padding-left: 6px;
        padding-right: 6px;
        margin-top: 5px;
        font-weight: bold;
        font-size: 10px;
    }
    .textpending{
        background:#c1baba;
        width:100%;
        padding-left: 6px;
        padding-right: 6px;
        color: #fff;
        font-weight: bold;
        font-size: 10px;
    }
    .voicepending{
        background:#c1baba;
        width:100%;
        color: #fff;
        padding-left: 6px;
        padding-right: 6px;
        margin-top: 5px;
        font-weight: bold;
        font-size: 10px;
    }
    @media only screen and (max-width: 768px) {
        /* For mobile phones: */
        .textvoicebox {
            width: 50%;
        }
    }
    @media only screen and (max-width: 500px) {
        .textvoicebox {
            width: 100%;
        }
    }
    @media only screen and (min-width: 768px){
        .textvoicebox {
            width: 25%;
        }
    }
    @media only screen and (min-width: 1100px){
        .textvoicebox {
            width: 12%;
            /*width: 9%;*/
            padding: 0px;
        }
    }
    body{
        background:#fff;
    }

    /*Sanjay*/
    @media only screen and (min-width: 1200px){ 
        .col-lg-2 {
            width: 14.666667%;
        }
    }
    @media only screen and (min-width: 1200px) {
        .col-lg-10 {
            width: 85.333333%;
        }
    }
</style>

