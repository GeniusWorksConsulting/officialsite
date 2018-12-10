<?php
$query_string="";
$style="";
if(isset($_GET['week']) && $_GET['week']!="" && isset($_GET['month']) && $_GET['month']!="" && isset($_GET['year']) && $_GET['year']!=""){
	$query_string = "&week=".$_GET['week']."&month=".$_GET['month']."&year=".$_GET['year'];
	$style="display:none;";
}
?>
         <div class="col-sm-9 col-md-9 col-lg-10" style="margin-top:5%;">
		   <?php if($this->session->flashdata('message_eror') != '') {?>				
		   <div class="alert alert-danger alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_eror');?></strong>				</div>
		   <?php }?>
			<?php if($this->session->flashdata('message_success') != '') {?>				
		   <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success');?></strong>				</div>
		   <?php }?>
            <div class="content">
               <div class="container">
                  <!-- Page-Title -->                        
                  <div class="row">
                     <div class="col-sm-12">
                        <h4 class="page-title">vb starter dashboard</h4>
                        <p class="text-muted page-title-alt"></p>
						
						<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12" style="margin-bottom: 50px;">
							<?php
							$textvoicebox = "textvoicebox";
							if(sizeof($groupmember1) == 7){ $textvoicebox = "textvoicebox2"; }
							for($i=0;$i<sizeof($groupmember1);$i++){ ?>
                    
								<h3 class="marginbottomzero"  style="font-weight:700;clear:both;"><?php echo $groupmember1[$i]['first_name']; ?>'s Dashboard</h3>
								
								<h5 class="margintopzero" style="font-weight:700;clear:both;">I have completed <span><?php echo $groupmember1[$i]['mycompleted']; ?></span> out of <span><?php echo $groupmember1[$i]['totalmember']; ?></span> assessment for this week</h5>
								
								<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="padding:0px;">
								
									<div class="text-center col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-bottom: 50px;padding:0px;">
									
									<?php for($j=0;$j<sizeof($groupmember1);$j++){ ?>
									
										<div class="text-center <?php echo $textvoicebox; ?> col-sm-1 col-md-1 col-lg-1 col-xs-12">
										
											<h5 style="font-weight:700;font-size:13px;"><?php echo $groupmember1[$i][$j]['first_name']; ?></h5>
											
											<h3><?php echo $groupmember1[$i][$j]['assessed_percentage']; if($groupmember1[$i][$j]['assessed_percentage']!="NA") { echo "%"; } ?></h3>
											<?php  if($groupmember1[$i][$j]['completed']!="") { ?>
												<i class="fa fa-user" style="width:100%;font-size: 90px;color: #aef5ae;;"></i>
											<?php }else{ ?>
												<i class="fa fa-user" style="width:100%;font-size: 90px;"></i>
											<?php } ?>
											<h3 style="color:#1a53ff;margin: 0px;">&nbsp;<?php echo $groupmember1[$i][$j]['qarating']; if($groupmember1[$i][$j]['qarating']!="") { echo "%"; } ?></h3>
											<h5 style="color:#1a53ff;margin: 0px;">&nbsp;<?php if($groupmember1[$i][$j]['qarating']!="") { echo "audited"; } ?></h5>
											<?php if(!empty($groupmember1[$i][$j]['text_rating'])){ ?>
											
											<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember1[$j]['id']; ?>&type=text&admin=yes&send_id=<?php echo $groupmember1[$i]['id']; ?><?php echo $query_string; ?>"	><button class="textcomplete btn" style="background: #f51034;">Text</button></a> 
											
											<?php }else{ ?>
											
										<!--	<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember1[$j]['id']; ?>&type=text"	><button class="textpending btn">Text</button></a> -->
											
											<?php } ?>
											
											<?php if(!empty($groupmember1[$i][$j]['voice_rating'])){ ?>
											
												<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember1[$j]['id']; ?>&type=voice&admin=yes&send_id=<?php echo $groupmember1[$i]['id']; ?><?php echo $query_string; ?>"	><button class="voicecomplete btn" style="background: #f51034;">Voice</button></a> 
												
											<?php }else{ ?>
											
											<!--	<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember1[$j]['id']; ?>&type=voice"><button class="voicepending btn">Voice</button></a>-->
												
											<?php } ?>
											
										</div>
									<?php } ?>
									</div>
										
								</div>
								
							<?php }  ?>
							
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12" style="margin-bottom: 50px;">
							<?php 
							$textvoicebox = "textvoicebox";
							if(sizeof($groupmember2) == 7){ $textvoicebox = "textvoicebox2"; }
							for($i=0;$i<sizeof($groupmember2);$i++){ ?>
                    
								<h3 class="marginbottomzero"  style="font-weight:700;clear:both;"><?php echo $groupmember2[$i]['first_name']; ?>'s Dashboard</h3>
								
								<h5 class="margintopzero" style="font-weight:700;clear:both;">I have completed <span><?php echo $groupmember2[$i]['mycompleted']; ?></span> out of <span><?php echo $groupmember2[$i]['totalmember']; ?></span> assessment for this week</h5>
								
								<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="padding:0px;">
								
									<div class="text-center col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-bottom: 50px;padding:0px;">
									
									<?php for($j=0;$j<sizeof($groupmember2);$j++){ ?>
									
										<div class="text-center <?php echo $textvoicebox; ?> col-sm-1 col-md-1 col-lg-1 col-xs-12">
										
											<h5 style="font-weight:700;font-size:13px;"><?php echo $groupmember2[$i][$j]['first_name']; ?></h5>
											
											<h3><?php echo $groupmember2[$i][$j]['assessed_percentage']; if($groupmember2[$i][$j]['assessed_percentage']!="NA") { echo "%"; } ?></h3>
											
											<?php  if($groupmember2[$i][$j]['completed']!="") { ?>
												<i class="fa fa-user" style="width:100%;font-size: 90px;color: #aef5ae;;"></i>
											<?php }else{ ?>
												<i class="fa fa-user" style="width:100%;font-size: 90px;"></i>
											<?php } ?>
											<h3 style="color:#1a53ff;margin: 0px;">&nbsp;<?php echo $groupmember2[$i][$j]['qarating']; if($groupmember2[$i][$j]['qarating']!="") { echo "%"; } ?></h3>
											<h5 style="color:#1a53ff;margin: 0px;">&nbsp;<?php if($groupmember2[$i][$j]['qarating']!="") { echo "audited"; } ?></h5>
											
											<?php if(!empty($groupmember2[$i][$j]['text_rating'])){ ?>
											
											<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember2[$j]['id']; ?>&type=text&admin=yes&send_id=<?php echo $groupmember2[$i]['id']; ?><?php echo $query_string; ?>"	><button class="textcomplete btn" style="background: #f51034;">Text</button></a>
											
											<?php }else{ ?>
											
										<!--	<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember2[$j]['id']; ?>&type=text"	><button class="textpending btn">Text</button></a>-->
											
											<?php } ?>
											
											<?php if(!empty($groupmember2[$i][$j]['voice_rating'])){ ?>
											
												<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember2[$j]['id']; ?>&type=voice&admin=yes&send_id=<?php echo $groupmember2[$i]['id']; ?><?php echo $query_string; ?>"	><button class="voicecomplete btn" style="background: #f51034;">Voice</button></a>
												
											<?php }else{ ?>
											
											<!--	<a href="<?php echo base_url(); ?>addrating?id=<?php echo $groupmember2[$j]['id']; ?>&type=voice"><button class="voicepending btn">Voice</button></a>-->
												
											<?php } ?>
											
										</div>
									<?php }   ?>
									</div>
										
								</div>
								
							<?php }  ?>
						</div>	
						<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="margin-top:15px;    margin-bottom: 200px;">
									<div class="col-sm-6 col-md-6 col-lg-6 col-xs-12 text-center">
										<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 text-center" style="background:#aef5ae;">
										<h1 style="color: #464545;font-size: 60px;font-weight: 900;">squad 1 tribe</h1>
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
										<h1 style="color: #464545;font-size: 50px;font-weight: 900;">squad 2 tribe</h1>
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
	margin-top:5px;
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
.textvoicebox{
	width:12%;
	display:inline-block;
	/*width: 9%;*/
    padding: 0px;
	text-align: center;
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
        width: 12.5%;
		/*width: 9%;*/
    padding: 0px;
    }
}
body{
	background:#fff;
}
.textvoicebox2{
	width:14%;
	display:inline-block;
	/*width: 9%;*/
    padding: 0px;
	text-align: center;
}
@media only screen and (max-width: 768px) {
    /* For mobile phones: */
    .textvoicebox2 {
        width: 50%;
    }
}
@media only screen and (max-width: 500px) {
    .textvoicebox2 {
        width: 100%;
    }
}
@media only screen and (min-width: 768px){
	.textvoicebox2 {
        width: 25%;
    }
}
@media only screen and (min-width: 1100px){
	.textvoicebox2 {
        width: 14%;
		/*width: 9%;*/
    padding: 0px;
    }
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

