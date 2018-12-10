<?php
$query_string = "";
$style = "";

if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
    $query_string = "&week=" . $_GET['week'] . "&month=" . $_GET['month'] . "&year=" . $_GET['year'];
    $style = "display:none;";
}
?>
<div class="col-sm-9 col-md-9 col-lg-10" style="margin-top:5%;">
    <?php
    if ($this->session->flashdata('message_eror') != '') {
        ?>				
        <div class="alert alert-danger alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">Ã—</a>					<strong><?php echo $this->session->flashdata('message_eror'); ?></strong>				</div>
    <?php }
    ?>
    <?php
    if ($this->session->flashdata('message_success') != '') {
        ?>				
        <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">Ã—</a>					<strong><?php echo $this->session->flashdata('message_success'); ?></strong>				</div>
    <?php }
    ?>
    <div class="content">
        <div class="container">
            <!-- Page-Title -->                        
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">vb starter dashboard</h4>
                    <p class="text-muted page-title-alt"></p>
                    <?php
                    if (isset($paused_account[0])) {
                        if ($paused_account[0]['message'] == "") {
                            echo '<h3 style="color:red;">Your Account is Paused</h3>
										<form method="post" action="' . base_url() . 'pages/resume_account">
											<input type="text" class="form-control" name="message" style="width:50%;" placeholder="Send Message To Admin. If Your Account is Paused By Mistake.">
											<input type="hidden" name="id" value="' . $paused_account[0]['id'] . '">
											<input type="submit" class="btn btn-primary" value="Submit">
										</form>';
                        } else {
                            echo '<h3 style="color:red;">Your Account is Paused. You Send Message Already To adin.!</h3>';
                        }
                    } else {

                        // print_r($check_account_paused_in_previous_month);
                        // echo sizeof($check_account_paused_in_previous_month);

                        if (sizeof($check_account_paused_in_previous_month) == 0) {
                            echo '<a href="' . base_url() . 'pause_account" class="btn btn-primary">Pause account</a>';
                        }
                    }
                    ?>
                    <h3 class="marginbottomzero"  style="font-weight:700;"><?php echo $this->session->first_name; ?>'s Dashboard</h3>
                    <h5 class="margintopzero" style="font-weight:700;"><?php echo $this->session->first_name; ?> has completed <span><?php echo $mycompleted['complete_member']; ?></span> out of <span><?php echo $mycompleted['total_member']; ?></span> assessment for this week</h5>
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="/*margin-bottom: 50px;*/">

                        <?php
                        if ($sender_receiver_same != 0) {
                            if (abs($sender_receiver_same - $qarating) > 5) {
                                ?>
                                </br>
                                <button class="btn btn-danger checkpercentage" data-id="1"><?php echo $qarating; ?>%</button>
                                <a class="checkpercentage_toggle1" style="display:none;" href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $this->session->user_id; ?>&sender_receiver=same&type=voice&sender_id=<?php echo $this->session->user_id; ?>&receiver_id=<?php echo $qa_id; ?><?php echo $query_string; ?>">
                                    <button class="voicecomplete btn" style="background: #1a53ff;width: 10%;">Voice</button>
                                </a>
                                <a class="checkpercentage_toggle1" style="display:none;" href="<?php echo base_url(); ?>addrating?id=<?php echo $this->session->user_id; ?>&sender_receiver=same&type=text&sender_id=<?php echo $this->session->user_id; ?>&receiver_id=<?php echo $qa_id; ?><?php echo $query_string; ?>"><button class="textcomplete btn" style="background: #1a53ff;width: 10%;">Text</button></a>
                                <?php
                            } else {
                                echo '<h3 style="color:#1a53ff;margin: 0px;">' . $qarating;
                                if ($qarating != "") {
                                    echo "%";
                                }

                                echo '</h3>';
                            }
                        }
                        ?>
                    </div>
                    <div class="col-sm-8 col-md-8 col-lg-8 col-xs-12" style="/*margin-bottom: 50px;*/">
                        <div class="text-center col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-bottom: 50px;">
                            <?php
                            for ($i = 0; $i < sizeof($groupmember); $i++) {
                                ?>
                                <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                    <h5 style="font-weight:700;"><?php echo $groupmember[$i]['first_name']; ?></h5>
                                    <?php
                                    if (!empty($groupmember[$i]['text_rating']) && !empty($groupmember[$i]['voice_rating'])) {
                                        ?>
                                        <i class="fa fa-user" style="width:100%;font-size: 90px;color: darkseagreen;"></i>
                                        <?php
                                    } else {
                                        ?>
                                        <i class="fa fa-user" style="width:100%;font-size:90px;color: 2f2f2f;"></i>
                                    <?php }
                                    ?>
                                    <?php
                                    if (!empty($groupmember[$i]['text_rating'])) {
                                        ?>
                                        <a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember[$i]['id']; ?>&type=text<?php echo $query_string; ?>"	><button class="textcomplete btn" style="background: #00a900;">Text</button></a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember[$i]['id']; ?>&type=text" style="<?php echo $style; ?>"	><button class="textpending btn">Text</button></a>
                                    <?php }
                                    ?>
                                    <?php
                                    if (!empty($groupmember[$i]['voice_rating'])) {
                                        ?>
                                        <a href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $groupmember[$i]['id']; ?>&type=voice<?php echo $query_string; ?>"	><button class="voicecomplete btn" style="background: #00a900;">Voice</button></a>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $groupmember[$i]['id']; ?>&type=voice" style="<?php echo $style; ?>"	><button class="voicepending btn">Voice</button></a>
                                    <?php }
                                    ?>

                                </div>
                            <?php }
                            ?>
                        </div>

                        <h3 class="marginbottomzero" style="clear:both; font-weight:700;"><?php echo $this->session->first_name; ?> HAS BEEN ASSESSED BY:</h3>
                        <h5 class="margintopzero" style="font-weight:700;">YOUR TRIBE CRED IS CURRENTLY AT <?php echo $current_im_acieving; ?>%</h5>

                        <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 ">
                            <?php
                            for ($i = 0; $i < sizeof($groupmember); $i++) {
                                ?>
                                <div class="textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                    <h5 style="font-weight:700;"><?php echo $groupmember[$i]['first_name']; ?></h5>
                                    <h3><?php
                                        echo $groupmember[$i]['assessed_percentage'];
                                        if ($groupmember[$i]['assessed_percentage'] != "NA") {
                                            echo "%";
                                        }
                                        ?></h3>
                                    <?php
                                    if (!empty($groupmember[$i]['text_assessed_rating']) && !empty($groupmember[$i]['voice_assessed_rating'])) {
                                        ?>
                                        <i class="fa fa-user" style="width:100%;font-size: 90px;color: darkseagreen;"></i>
                                        <?php
                                    } else {
                                        ?>
                                        <i class="fa fa-user" style="width:100%;font-size:90px;color: 2f2f2f;"></i>
                                    <?php }
                                    ?>
                                    <?php
                                    if (!empty($groupmember[$i]['text_assessed_rating'])) {
                                        ?>
                                        <a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember[$i]['id']; ?>&type=text&oww=yes<?php echo $query_string; ?>"	><button class="textcomplete btn" style="background: #00a900;">Text</button></a>
                                        <?php
                                    } else {
                                        ?>
                                        <button class="textpending btn" style="<?php echo $style; ?>">Text</button>
                                    <?php }
                                    ?>
                                    <?php
                                    if (!empty($groupmember[$i]['voice_assessed_rating'])) {
                                        ?>
                                        <a href="<?php echo base_url(); ?>optionvoicerating?id=<?php echo $groupmember[$i]['id']; ?>&type=voice&oww=yes<?php echo $query_string; ?>"	><button class="voicecomplete btn" style="background: #00a900;">Voice</button></a>
                                        <?php
                                    } else {
                                        ?>
                                        <button class="voicepending btn" style="<?php echo $style; ?>">Voice</button>
                                    <?php }
                                    ?>

                                </div>
                            <?php }
                            ?>

                            <!--Sanjay's Change -->
                        </div>
                        <!--Sanjay's Change -->
                        <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12" ></div>


                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4 col-xs-12" style="float:right;">
                        <h4 style="font-size: 14px;color:#000;font-weight: 700;">Squad goal for <?php echo date("F"); ?> :</h4>

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
                                <?php
                                if (empty($check_month_goal_entry)) {
                                    ?>
                                    <a type="button" class="" data-toggle="modal" data-target="#setmonthlygoal" style="vertical-align: middle;display: table-cell;width:100%;font-size: 14px;color: #fff;cursor: pointer;">Click Here to Set<br/>your Monthly Goal</a>
                                    <?php
                                } else {
                                    ?>
                                    <span>
                                        <h1 style="color:#fff;font-weight:900;margin-bottom:0px;font-size: 60px;"><?php echo $check_month_goal_entry->monthly_goal; ?>%</h1>
                                        <h4 style="color:#fff;font-weight:900;margin-top:0px;">customer love</h4>
                                    </span>
                                <?php }
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12" style="padding-right: 0px;">
                            <h4 style="font-size: 14px;color:#000;font-weight: 700;">MY WEEEKLY GOAL:</h4>

                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center ownbg" style="padding:21px 0px;height:130px;display: table;">
                                <?php
                                if (empty($check_week_goal_entry)) {
                                    ?>
                                    <a type="button" class="" data-toggle="modal" data-target="#setweeklygoal" style="vertical-align: middle;display: table-cell;width:100%;font-size: 14px;color: #fff;cursor: pointer;">Click Here to Set<br/>your Weekly Goal</a>
                                    <?php
                                } else {
                                    ?>
                                    <span>
                                        <h1 style="color:#fff;font-weight:900;margin-bottom:0px;font-size: 60px;"><?php echo $check_week_goal_entry->weekly_goal; ?>%</h1>
                                        <h4 style="color:#fff;font-weight:900;margin-top:0px;">customer love</h4>
                                    </span>
                                <?php }
                                ?>

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
                    </div>
                    <!--		<div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                            <h5 style="font-weight:700;">rhines</h5>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox col-sm-1 col-md-1 col-lg-1 col-xs-12">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                            --->

                    <!--		<div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">thomas</h5>
                                            <h3>50%</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;color: darkseagreen;"></i>
                                            <button class="textcomplete btn" style="background: #f51034;">Text Complete</button>
                                            <button class="voicecomplete btn" style="background: #f51034;">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">rhines</h5>
                                            <h3>30%</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <h3>N/A</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <h3>N/A</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <h3>N/A</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <h3>N/A</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <h3>N/A</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    
                                    <div class="text-center textvoicebox">
                                            <h5 style="font-weight:700;">cindy</h5>
                                            <h3>N/A</h3>
                                            <i class="fa fa-user" style="width:100%;font-size: 50px;"></i>
                                            <button class="textpending btn">Text Complete</button>
                                            <button class="voicepending btn">Voice Complete</button>
                                    </div>
                                    --->

                    <!--			<div class="col-sm-3 col-md-3 col-lg-3 col-xs-12" style="clear:both;margin-top: 15px;">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#77d877;clear:both; margin-right:5px;margin-left:5px;">
                            <h1 style="color: #fff;font-weight: 600;margin-bottom: 0px;">Week 1</h1>
                            <h5 style="margin-top: 0px;color: #fff;font-weight: 900;">Customer Love</h5>
                            <h1 style="line-height: 100%;color: #fff;font-weight: 900;font-size: 60px;">35%</h1>
                    </div>
            </div>
            
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12" style="margin-top: 15px;">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center ownbg" style=" margin-right:5px;margin-left:5px;">
                            <h1 style="color: #fff;font-weight: 600;margin-bottom: 0px;">Week 2</h1>
                            <h5 style="margin-top: 0px;color: #fff;font-weight: 900;">Customer Love</h5>
                            <h1 style="line-height: 100%;color: #fff;font-weight: 900;font-size: 60px;">65%</h1>
                    </div>
            </div>
            
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12" style="margin-top: 15px;">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#f17387; margin-right:5px;margin-left:5px;">
                            <h1 style="color: #fff;font-weight: 600;margin-bottom: 0px;">Week 3</h1>
                            <h5 style="margin-top: 0px;color: #fff;font-weight: 900;">Customer Love</h5>
                            <h1 style="line-height: 100%;color: #fff;font-weight: 900;font-size: 60px;">45%</h1>
                    </div>
            </div>
            
            <div class="col-sm-3 col-md-3 col-lg-3 col-xs-12" style="margin-top: 15px;">
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#f17387; margin-right:5px;margin-left:5px;">
                            <h1 style="color: #fff;font-weight: 600;margin-bottom: 0px;">Week 4</h1>
                            <h5 style="margin-top: 0px;color: #fff;font-weight: 900;">Customer Love</h5>
                            <h1 style="line-height: 100%;color: #fff;font-weight: 900;font-size: 60px;">45%</h1>
                    </div>
            </div>
--->
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
                                    <h1 style="line-height: 100%;color: #fff;font-weight: 900;font-size: 60px;"><?php echo $allweekdetails[$i]['details']; ?>%</h1>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>


                    <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="margin-top:15px;    margin-bottom: 200px;">
                        <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12 text-center">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#aef5ae;">
                                <h1 style="color: #464545;font-size: 60px;font-weight: 900;">MY tribe</h1>
                                <h3 style="color: #464545;font-size: 35px;font-weight: 900;">FOR this week</h3>
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
                                    <i class="fa fa-users" style="float:right;font-size:90px;"></i>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
                                    <span style="float:left;">
                                        <h1 class="owncolor" style="font-weight: 900;margin-bottom: 0px;line-height: 100%;font-size: 60px;"><?php echo $mytribe['percentage']; ?>%</h1>
                                        <h4  class="owncolor" style="font-weight: 900;font-size: 20px;margin-top: 0px;">customer love</h4>
                                    </span>
                                </div>
                                <h3 style="clear:both;padding-top:10px;color: #36d636;font-weight: 900;"><?php echo $mytribe['complete_member']; ?> out of <?php echo $mytribe['total_member']; ?></h3>
                                <h3 style="color: #464545;font-size: 30px;font-weight: 900;">HAVE COMPLETED</br> THEIR SQUAD ASSESSMENTS</h3>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6 col-lg-6 col-xs-12 text-center" style="padding-right:0px;">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#aef5ae;">
                                <h1 style="color: #464545;font-size: 50px;font-weight: 900;">other tribe</h1>
                                <h3 style="color: #464545;font-size: 35px;font-weight: 900;">FOR this week</h3>
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
                                    <i class="fa fa-users" style="float:right;font-size:90px;"></i>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
                                    <span style="float:left;">
                                        <h1 class="owncolor" style="font-weight: 900;margin-bottom: 0px;line-height: 100%;font-size: 60px;"><?php echo $othertribe['percentage']; ?>%</h1>
                                        <h4 class="owncolor" style="font-weight: 900;font-size: 20px;margin-top: 0px;">customer love</h4>
                                    </span>
                                </div>
                                <h3 style="clear:both;padding-top:10px;color: #36d636;font-weight: 900;"><?php echo $othertribe['complete_member']; ?> out of <?php echo $othertribe['total_member']; ?></h3>
                                <h3 style="color: #464545;font-size: 30px;font-weight: 900;">HAVE COMPLETED</br> THEIR SQUAD ASSESSMENTS</h3>
                            </div>
                        </div>
                    </div>
                    <!-- End Partition --> 
                </div>

                <!-- New Graph -->
                <script src="<?php echo base_url(); ?>public/line.js" type="text/javascript"></script>
                <div class="col-md-12">
                    <h3 style="text-align:center;">Perception Gap Graph</h3>
                    <p style="text-align:center;">This tracks your ability to reflect on your behaviour and how you can learn from it. The ideal score is 0. </p>
                </div>
                <div class="col-md-12 maindiv" style="width:100%;height:500px;position: relative;">

                </div>
                <script>

                    // if 8 = j = 10;
                    // if 12 = j = 

                    var full_height = 400;
                    var no_of_part = 8;
                    var gap_part = full_height / no_of_part;

                    var j = 10;
                    for (i = 0; i <= no_of_part; i++) {
                        var mytop = i * gap_part;

                        // console.log(mytop);


                        var y = ((no_of_part / 2) * j) - (i * j);
                        $(".maindiv").append("<span style='position:absolute;left:0px;top:" + mytop + "px;width: 100%;'>" + y + "</span>")

                        // console.log(y);

                    }
                    var week = [

<?php
$first_key_ = key($qarating_graph);

for ($i = $first_key_; $i < sizeof($qarating_graph) + $first_key_; $i++) {
    $first_key = key($qarating_graph[$i]);
    for ($j = $first_key; $j < sizeof($qarating_graph[$i]) + $first_key; $j++) {
        if (isset($qarating_graph[$i][$j])) {
            $first_key_k = key($qarating_graph[$i][$j]);
            for ($k = $first_key_k; $k < sizeof($qarating_graph[$i][$j]) + $first_key_k; $k++) {
                $qa_rating = $qarating_graph[$i][$j][$k];
                $myarting = $sender_receiver_same_graph[$i][$j][$k];

                // echo "qarating_graph=".$qa_rating."   myrating=".$myarting."</br>";

                if ($qa_rating == 0 || $myarting == 0) {
                    echo '"0",';
                } else {
                    echo '"' . ($qa_rating - $myarting) . '",';
                }
            }
        } else {
            for ($k = 1; $k <= sizeof($qarating_graph[$i]["0" . $j]); $k++) {
                $qa_rating = $qarating_graph[$i]["0" . $j][$k];
                $myarting = $sender_receiver_same_graph[$i]["0" . $j][$k];

                // echo "qarating_graph=".$qa_rating."   myrating=".$myarting."</br>";

                if ($qa_rating == 0 || $myarting == 0) {
                    echo '"0",';
                } else {
                    echo '"' . ($qa_rating - $myarting) . '",';
                }
            }
        }

        echo '"' . (30) . '",';
    }
}
?>
                    ];

                    var week_name = [
<?php
$first_key_ = key($qarating_graph);

for ($i = $first_key_; $i < sizeof($qarating_graph) + $first_key_; $i++) {
    $first_key = key($qarating_graph[$i]);
    for ($j = $first_key; $j < sizeof($qarating_graph[$i]) + $first_key; $j++) {
        if (isset($qarating_graph[$i][$j])) {
            $first_key_k = key($qarating_graph[$i][$j]);
            for ($k = $first_key_k; $k < sizeof($qarating_graph[$i][$j]) + $first_key_k; $k++) {
                $qa_rating = $qarating_graph[$i][$j][$k];
                $myarting = $sender_receiver_same_graph[$i][$j][$k];

                // echo "qarating_graph=".$qa_rating."   myrating=".$myarting."</br>";

                echo '"' . $k . '",';

                // echo '"'.($qa_rating - $myarting).'",';
            }
        } else {
            for ($k = 1; $k <= sizeof($qarating_graph[$i]["0" . $j]); $k++) {
                $qa_rating = $qarating_graph[$i]["0" . $j][$k];
                $myarting = $sender_receiver_same_graph[$i]["0" . $j][$k];

                // echo "qarating_graph=".$qa_rating."   myrating=".$myarting."</br>";
                // echo '"'.($qa_rating - $myarting).'",';

                echo '"' . $k . '",';
            }
        }

        echo '"' . (30) . '",';
    }
}
?>
                    ];

                    var month_name = [
<?php

function month_name($j) {
    if ($j == "1" || $j == "01") {
        echo '"January",';
    }

    if ($j == "2" || $j == "02") {
        echo '"February",';
    }

    if ($j == "3" || $j == "03") {
        echo '"March",';
    }

    if ($j == "4" || $j == "04") {
        echo '"April",';
    }

    if ($j == "5" || $j == "05") {
        echo '"May",';
    }

    if ($j == "6" || $j == "06") {
        echo '"June",';
    }

    if ($j == "7" || $j == "07") {
        echo '"July",';
    }

    if ($j == "8" || $j == "08") {
        echo '"August",';
    }

    if ($j == "9" || $j == "09") {
        echo '"September",';
    }

    if ($j == "10") {
        echo '"October",';
    }

    if ($j == "11") {
        echo '"November",';
    }

    if ($j == "12") {
        echo '"December",';
    }
}

$first_key_ = key($qarating_graph);

for ($i = $first_key_; $i < sizeof($qarating_graph) + $first_key_; $i++) {
    $first_key = key($qarating_graph[$i]);
    for ($j = $first_key; $j < sizeof($qarating_graph[$i]) + $first_key; $j++) {
        if (isset($qarating_graph[$i][$j])) {
            $first_key_k = key($qarating_graph[$i][$j]);
            for ($k = $first_key_k; $k < sizeof($qarating_graph[$i][$j]) + $first_key_k; $k++) {
                $qa_rating = $qarating_graph[$i][$j][$k];
                $myarting = $sender_receiver_same_graph[$i][$j][$k];

                // echo "qarating_graph=".$qa_rating."   myrating=".$myarting."</br>";

                month_name($j);

                // echo '"'.($qa_rating - $myarting).'",';
            }
        } else {
            for ($k = 1; $k <= sizeof($qarating_graph[$i]["0" . $j]); $k++) {
                $qa_rating = $qarating_graph[$i]["0" . $j][$k];
                $myarting = $sender_receiver_same_graph[$i]["0" . $j][$k];

                // echo "qarating_graph=".$qa_rating."   myrating=".$myarting."</br>";
                // echo '"'.($qa_rating - $myarting).'",';

                month_name($j);
            }
        }

        month_name($j);
    }
}
?>
                    ];
                    console.log(month_name);
                    var main = (no_of_part / 2) * 10;
                    var myold_dot = 0;
                    var myold_left = 0;
                    var a = 0;
                    var month_last = 0;

                    for (i = 0; i < week.length; i++) {

                        // if(week[i]==50){
                        // //
                        // continue;
                        // }else{

                        if (week[i] != 30) {
                            a++;
                            var dot = ((main - (week[i])) * j) / 2;
                            var myleft = (i * 100) + 10;
                            console.log(dot + " " + myleft);
                            $(".maindiv").append("<span style='position:absolute;left:0px;top:" + dot + "px;left:" + myleft + "px;height:20px;width:20px;border-radius:100%;background:black;'></span>")
                            $(".maindiv").append("<span style='position:absolute;left:0px;top:20px;left:" + myleft + "px;'>Week " + week_name[i] + "</span>")
                            if (i != 0 && i != week.length) {
                                $('.maindiv').line(myold_dot, myold_left, myleft + 5, dot + 5, {color: "#000", stroke: 5, zindex: 1001}, function () {});

                                console.log("old_left = " + myold_dot + " myold_top = " + myold_left);
                                console.log("old_left = " + myleft + " myold_top = " + dot);
                            }

                            myold_dot = myleft + 5;
                            myold_left = dot + 5;
                        } else {
                            $('.maindiv').line((i * 100) + 20, 10, (i * 100) + 20, 400, {color: "#d43030", stroke: 5, zindex: 1001}, function () {});

                            // $(".maindiv").append("<span style='position:absolute;left:0px;top:0px;left:"+(month_left)+"px;'>January</span>");
                            // alert(month_left+"   "+a);
                            // a=0;

                            var new_left = ((i - month_last) / 2) * 100 + (month_last * 100);
                            month_last = i;
                            a = 0;
                            $(".maindiv").append("<span style='position:absolute;left:0px;top:0px;left:" + (new_left) + "px;'>" + month_name[i] + "</span>");
                        }
                    }
                </script>
                <!-- New Graph End-->

            </div>           
        </div>
    </div>
</div>

<!-- All Model -->
<?php
if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
    
} else {
    ?>
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
                        <?php
                        if (!empty($currentweek)) {
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
<?php }
?>
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
    @media only screen and (min-width: 1200px)
    .col-lg-2 {
        width: 14.666667%;
    }
    @media only screen and (min-width: 1200px)
    .col-lg-10 {
        width: 85.333333%;
    }
</style>