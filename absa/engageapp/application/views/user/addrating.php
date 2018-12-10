 <div class="col-sm-9 col-md-9 col-lg-10" style="margin-top:5%;">
	<div class="content">
	   <div class="container">
                  <!-- Page-Title -->                        
			  <div class="row" style="margin-bottom: 30px;">
				 <div class="col-sm-12">
					<h4 class="page-title">Add Assessment</h4>
				 </div>
			  </div>
			   <?php if($this->session->flashdata('message_eror') != '') {?>				
		   <div class="alert alert-danger alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_eror');?></strong>				</div>
		   <?php }?>
			<?php if($this->session->flashdata('message_success') != '') {?>				
		   <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success');?></strong>				</div>
		   <?php }?>
		  <div class="row">
			<div class="col-sm-4 col-md-4 col-lg-4 col-xs-12 text-center">
				<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
					<i class="fa fa-user" style="width:100%;font-size: 55px;"><h1 style="display:inline-block;font-size:70px;font-weight:700;"><?php echo $userdata_get[0]['first_name']; ?></h1></i>
					
				</div>
				
				<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
					<a href="<?php echo base_url()."addrating?id=".$userdata_get[0]['id']."&type=voice"; ?>">
						<button class="btn ownbg center-block" style="color:#fff;width:100%;font-weight:700;">VOICE</button>
						<h4 style="font-weight:700;">CLICK TO ACCESS</h4>
					</a>
				</div>
				
				<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
					<a href="<?php echo base_url()."addrating?id=".$userdata_get[0]['id']."&type=text"; ?>">
						<button class="btn ownbg center-block" style="color:#fff;width:100%;font-weight:700;">TEXT</button>
						<h4 style="font-weight:700;">CLICK TO ACCESS</h4>
					</a>
				</div>
				
				
				
				<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top:20px;background: #ebeff2;padding: 17px;">
					<i class="fa fa-user" style="width:100%;font-size: 55px;"><h1 style="display:inline-block;font-size:70px;font-weight:700;"><?php echo $userdata_get[0]['first_name']; ?></h1></i>
					
					<?php 
					$mainpercentage = 0;
					$count=0;
					if($gettextquestionpercentage!=""){
						$mainpercentage += $gettextquestionpercentage;
						$count++;
					}
					if($getvoicequestionpercentage!=""){
						$mainpercentage += $getvoicequestionpercentage;
						$count++;
					}
					?>
					<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
						<button class="btn center-block" style="color:#fff;width:100%;font-weight:700;background:#77d877">VOICE</br><span><?php echo $getvoicequestionpercentage; ?>%</span></button>
						<h4 style="font-weight:700;">COMPLETED</h4>
						<h4 style="font-weight:700;margin-top: 40px;">YOUR SCORE FOR <?php echo $userdata_get[0]['first_name']; ?> IS: </h4>
					</div>
					
					<div class="col-sm-6 col-md-6 col-lg-6 col-xs-6">
						<button class="btn center-block" style="color:#fff;width:100%;font-weight:700;background:#77d877">TEXT</br><span><?php echo $gettextquestionpercentage; ?>%</span></button>
						<h4 style="font-weight:700;">COMPLETED</h4>
						<h4 style="font-weight: 700;font-size: 50px;margin-top: 40px;"><?php if($count!=0){ echo intval($mainpercentage/$count); }else{ echo 0; }?>%</h4>
					</div>
				
				</div>
				
				<div class="col-sm-12">
					<?php
					if(isset($_GET['sender_receiver']) && $_GET['sender_receiver'] == "same"){
						if(!empty($percentage_gape_message)){ 
							echo '<h3>Messages</h3>
								<div class="col-sm-12">';
							for($i=0;$i<sizeof($percentage_gape_message);$i++){
								?>
								<?php if($percentage_gape_message[$i]['sender_user_id'] == $this->session->user_id){ ?>
								<div class="col-sm-12"  style="text-align:right;width: 70%;background: aliceblue;padding: 10px;margin: 5px;float: right;">
									<?php echo $percentage_gape_message[$i]['message']; ?>
								</div>
								<?php } ?>
								<?php if($percentage_gape_message[$i]['receiver_user_id'] == $this->session->user_id){ ?>
								<div class="col-sm-12" style="text-align:left;width: 70%;background: aliceblue;padding: 10px;margin: 5px;">
									<?php echo $percentage_gape_message[$i]['message']; ?>
								</div>
								<?php } ?>
								<?php
							} 
							echo '</div>';
						}
						?>
						<form method="post" action="<?php echo base_url(); ?>pages/sendmessage" style="margin-top: 10px;border: 1px solid;padding: 10px;clear:both;">
							<textarea name="message" class="form-control" placeholder="Enter Message Here.."></textarea>
							<?php
							foreach($_GET as $key=>$value){
								echo '<input type="hidden" name="'.$key.'" value="'.$value.'">';
							}
							?>
							
							<input type="submit" class="btn btn-primary" value="Submit" style="margin-top: 5px;"/>
						</form>
					<?php } ?>
					
				</div>
				
			</div>
			<?php

			
			
			?>
			<!--<div class="col-sm-1 col-md-1 col-lg-1">
			</div>-->
			<div class="col-sm-8 col-md-8 col-lg-8 col-xs-12">
			<form method="post" action="">
				<h1 style="font-weight:700;font-size:40px;"><?php if($_GET['type']=="text") { echo "TEXT"; } else{ echo "VOICE"; } ?> ASSESSMENT</h1>
				<?php
				if($igivethisman == "no"){ 
					echo '<h3 style="color:red;">You Cannot give the rating beacause rating is already give by other..!';
				}
				$questioncomplete = array();
				if(isset($_GET['type']) && $_GET['type']=="text"){
					$questionlist = $getalltextquestions;
					if(!empty($getalltextquestionscomplete)){
						$questionlist = array();
						$questioncomplete = $getalltextquestionscomplete;
					}
				}elseif(isset($_GET['type']) && $_GET['type']=="voice"){
					$questionlist = $getallvoicequestions;
					if(!empty($getallvoicequestionscomplete)){
						$questionlist = array();
						$questioncomplete = $getallvoicequestionscomplete;
					}
				}else{
					$questionlist = array();
				}
				$heading="";
				$heading2="";
				$heading=3;
				for($i=0;$i<sizeof($questionlist);$i++){
							if($heading!=$questionlist[$i]['category_name']){
								$heading = $questionlist[$i]['category_name'];
								
								$j=1;
								if($i!=0){
									//echo '<tr><td></td><td></td><td class="cat_'.strtolower($category_id).'_main"></td></tr>';
									//echo '</table></div></div>'; //panel body and panel-default end
									?>
									<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top:20px;display: inline-flex;">
										<h4 style="">TextArea for feedback</h3>
										<textarea class="form-control" name="textarea[]"></textarea>
									</div>
									<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top:20px;">
										<h3 style="float:right;">TOTAL FOR <?php echo $questionlist[$i-1]['category_name']; ?>   <span class="<?php echo 'cat_'.strtolower($category_id).'_main'; ?>" style="background:#f17387;padding:10px;">%</span></h3>
									</div>
								</div>
									<?php
								}
								$heading2 = $heading;
								?>
							<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 ownbg" style="margin-top: 10px;margin-bottom: 5px;">
								
								<?php
							}else{
								$heading2="";
							}
							?>
							<input type="hidden" name="category_id[]" value="<?php echo $questionlist[$i]['category_id']; ?>">
							<input type="hidden" name="question_id[]" value="<?php echo $questionlist[$i]['id']; ?>">
							<?php if($heading2 != ""){ ?>
							<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="font-weight:700;font-size:30px;color:#fff;text-align: center;padding-top: 20px;">
								<?php echo $heading2; ?>
							</div>
							<?php } ?>
							<div class="col-sm-10 col-md-10 col-lg-10 col-xs-12" style="margin-top:10px;font-weight:normal;font-size:15px;color:#fff;padding-left: 30px;">
								<?php echo $questionlist[$i]['description']; ?>
							</div>
							<div class="col-sm-2 col-md-2 col-lg-2 col-xs-12" >
								<?php if($igivethisman == "yes"){ ?>
								<select style="margin-top:10px;padding: 0px 5px;height: 30px;" name="rating[]" onchange="ratingchange('<?php echo strtolower($questionlist[$i]['category_id']); ?>');" class="cat_<?php echo strtolower($questionlist[$i]['category_id']); ?> form-control" required>
									<option value="">Select</option>
									<?php for($k=0;$k<=100;$k++){
										echo '<option value="'.$k.'">'.$k.'%</option>';
										$k+=4;
									} ?>
								</select>
								<?php }elseif($igivethisman == "no"){ ?>
									<select style="margin-top:10px;padding: 0px 5px;height: 30px;" name="rating[]" onchange="ratingchange('<?php echo strtolower($questionlist[$i]['category_id']); ?>');" class="cat_<?php echo strtolower($questionlist[$i]['category_id']); ?> form-control" required>
										<option value=""></option>
									</select>
								<?php } ?>
									
							</div>
						<?php
							$category_id = $questionlist[$i]['category_id'];
							if(sizeof($questionlist)-1 == $i){
								//echo '<tr><td></td><td></td><td class="cat_'.strtolower($category_id).'_main"></td></tr>';
								echo '<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top:20px;display: inline-flex;">
										<h4 style="">TextArea for feedback</h4>
										<textarea class="form-control" name="textarea[]"></textarea>
									</div>';
								echo '<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top: 20px;">
										<h3 style="float:right;">TOTAL FOR '.$questionlist[$i-1]['category_name'].'   <span style="background:#f17387;padding:10px;" class="cat_'.strtolower($category_id).'_main">%</span></h3>
									</div>';
								echo '</div>';
								echo '<input type="text" class="form-control" placeholder="Enter File Name" name="file_name" style="margin-top:5px;">';	
								if($igivethisman == "yes"){
									echo '<input type="submit" class="btn btn-primary" style="margin-top:5px;">';
								}
							}
							$j++;
						}
					?>
				</form>	
				<?php 
				$heading="";
				$heading2="";
				$total_percent = 0;
				$j=1;
				//print_R($getalltextareaquestion);
				for($i=0;$i<sizeof($questioncomplete);$i++){
							if($questioncomplete[$i]['rating']!=""){
								if($heading!=$questioncomplete[$i]['category_name']){
									$heading = $questioncomplete[$i]['category_name'];
									
									//$j=1;
									if($i!=0){
										//echo '<tr><td></td><td></td><td class="cat_'.strtolower($category_id).'_main"></td></tr>';
										//echo '</table></div></div>'; //panel body and panel-default end
										$j--;
										?>
										<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top: 20px;display: inline-flex;">
											<h4 style="float:right;color:#fff;">Comments</h4>   <textarea class="<?php echo 'cat_'.strtolower($category_id).'_main'; ?> form-control" style="padding:10px;margin-left: 10px;"><?php if(isset($getalltextareaquestion[$category_id])){ echo $getalltextareaquestion[$category_id]; } ?></textarea>
										</div>
										<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top: 20px;">
											<h3 style="float:right;">TOTAL FOR <?php echo $questioncomplete[$i-1]['category_name']; ?>   <span class="<?php echo 'cat_'.strtolower($category_id).'_main'; ?>" style="background:#f17387;padding:10px;"><?php echo intval($total_percent/$j); ?>%</span></h3>
										</div>
									</div>
										<?php
										$j=1;
										$total_percent=0;
									}
									$heading2 = $heading;
									?>
								<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12 ownbg" style="margin-top: 10px;margin-bottom:5px;">
									
									<?php
								}else{
									$heading2="";
								}
								$total_percent+=$questioncomplete[$i]['rating'];
								?>
								
								<input type="hidden" name="question_id[]" value="<?php echo $questioncomplete[$i]['id']; ?>">
								<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="font-weight:700;font-size:30px;color:#fff;padding-top: 20px;clear:both;">
									<?php echo $heading2; ?>
								</div>
								<div class="col-sm-10 col-md-10 col-lg-10 col-xs-12" style="margin-top:10px;font-weight:normal;font-size:15px;color:#fff;padding-left: 30px;">
									<?php echo $questioncomplete[$i]['description']; ?>
								</div>
								<div class="col-sm-2 col-md-2 col-lg-2 col-xs-12" >
									<select style="margin-top:10px;padding: 0px 5px;height: 30px;" name="rating[]" onchange="ratingchange('<?php echo strtolower($questioncomplete[$i]['category_id']); ?>');" class="cat_<?php echo strtolower($questioncomplete[$i]['category_id']); ?> form-control" required>
										<option value=""><?php echo $questioncomplete[$i]['rating']; ?></option>
										<?php //for($k=0;$k<=100;$k++){
											//echo '<option value="'.$k.'">'.$k.'%</option>';
											//$k+=4;
										//} ?>
									</select>
										
								</div>
							<?php
								$j++;
								$category_id = $questioncomplete[$i]['category_id'];
								if(sizeof($questioncomplete)-1 == $i){
									$j--;
									//echo '<tr><td></td><td></td><td class="cat_'.strtolower($category_id).'_main"></td></tr>';
									if(!isset($getalltextareaquestion[$category_id])){
										$getalltextareaquestion[$category_id]="";
									}
									echo '<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top: 20px;display:inline-flex;">
											<h4 style="float:right;color:#fff;">Comments</h4><textarea class="form-control" style="display:inline-flex;margin-left: 10px;" disabled>'.$getalltextareaquestion[$category_id].'</textarea>
										</div>';
									echo '<div class="col-sm-12 col-md-12 col-lg-12 col-xs-12" style="margin-top: 20px;">
											<h3 style="float:right;">TOTAL FOR '.$questioncomplete[$i-1]['category_name'].'   <span style="background:#f17387;padding:10px;" class="cat_'.strtolower($category_id).'_main">'.intval($total_percent/$j).'%</span></h3>
										</div>';
									echo '</div>';
									//print_r($file_name);
									echo '<input type="text" class="form-control" placeholder="Enter File Name" name="file_name" style="margin-top:5px;" value="'.$file_name.'">';	
								}
							}
						}
					?>
			</div>
				<!--	<div class="col-md-12">
					<form method="post" action="">
					<?php //print_r($questionlist); 
					
						// $heading = "";
						// $j=1;
						// $category_id="";
						// for($i=0;$i<sizeof($questionlist);$i++){
							// if($heading!=$questionlist[$i]['category_name']){
								// $heading = $questionlist[$i]['category_name'];
								// $j=1;
								// if($i!=0){
									// echo '<tr><td></td><td></td><td class="cat_'.strtolower($category_id).'_main"></td></tr>';
									// echo '</table></div></div>'; //panel body and panel-default end
								// }
								?>
								
								<div class="panel panel-default">
								  <div class="panel-heading"><?php echo $questionlist[$i]['category_name']; ?></div>
								  <div class="panel-body">
								  <table class="table bordered">
								  <tr>
									<th>#</th>
									<th>Question</th>
									<th>Rating</th>
								  </tr>
								 <?php
							// }
							?>
							<input type="hidden" name="question_id[]" value="<?php echo $questionlist[$i]['id']; ?>">
							
								<tr>
								<td><?php echo $j; ?></td>
								<td><?php echo $questionlist[$i]['description']; ?></td>
								<td>
									<select name="rating[]" onchange="ratingchange('<?php echo strtolower($questionlist[$i]['category_id']); ?>');" class="cat_<?php echo strtolower($questionlist[$i]['category_id']); ?>">
										<option value="">Select</option>
										<option value="0">0</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="75">75</option>
										<option value="100">100</option>
									</select>
								</td>
								</tr>
							
							 
							<?php
							?>
								
							<?php
							// $category_id = $questionlist[$i]['category_id'];
							// if(sizeof($questionlist)-1 == $i){
								// echo '<tr><td></td><td></td><td class="cat_'.strtolower($category_id).'_main"></td></tr>';
								// echo '</table></div></div>';
							// }
							// $j++;
						// }
					?>
					<input type="submit" class="btn btn-primary">
					</form>
					</div>-->
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
body{
	background: #fff;
}
</style>
<script src="<?= base_url() ?>public/assets/js/jquery.min.js"></script>
<script>
function ratingchange(categoryid)
{
	var i=0;
	var total=0;
	$('.cat_'+categoryid).each(function(){
		if($(this).val()!=""){
			total=parseInt(total)+parseInt($(this).val());
		}
		i++;
	});
	var val =  parseInt(total/i);
	$('.cat_'+categoryid+'_main').html(val+"%");
	//alert(parseInt(total/i));
	
}
</script>