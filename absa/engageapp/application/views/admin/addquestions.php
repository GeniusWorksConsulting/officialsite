<div class="content-page">
    <!-- Start content -->
    <div class="content">
<?php

if(!empty($question)){
	$description = $question[0]['description'];
	//echo $description;
	$category_id = $question[0]['category_id'];
	$type = $question[0]['type'];
}else{
	$description = "";
	$category_id = "";	
	$type="";
}

?>
<div class="container">
   <div class="row">
      <div class="col=sm=12">
         <div class="col-sm-12">
            <div class="content">
               <div class="container">
                  <!-- Page-Title -->                        
                  <div class="row">
                     <div class="col-sm-12">
                        <h1>Setting</h1>
                        <p class="text-muted page-title-alt"></p>
                     </div>
					 <?php if($this->session->flashdata('message_success') != '') {?>				
               <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success');?></strong>				</div>
               <?php }?>
					

					<div class="tab-content">
					  
					  <div id="referral_charges" class="tab-pane fade in active">
					  <div class="col-sm-8">
						<form method="post" action="">
							<h4 class="page-title">Add Question</h4>
							
								
									<label>Question: </label>
									<input type="text" class="form-control" name="description" required value='<?php echo $description; ?>'>
									<label>Category</label>	
									<select class="form-control" name="category_id" required>
											<option>Select</option>
										<?php for($i=0;$i<sizeof($category);$i++){
											?>
											<option <?php if($category_id == $category[$i]['id']){ echo 'selected'; }?> value="<?php echo $category[$i]['id']; ?>"><?php echo $category[$i]['name']; ?></option>
											<?php
										} ?>
									</select>
									<label>Type: </label>	
									<select class="form-control" name="type" required>
											<option>Select</option>
											<option <?php if($type == "text"){ echo 'selected'; }?> value="text">Text</option>
											<option <?php if($type == "voice"){ echo 'selected'; }?> value="voice">Voice</option>
									</select>
							
							
							<input type="submit" class="btn btn-primary center-block" value="Submit" style="margin-top:5px;">
							
						</form>
						</div>
						
					  </div>
					</div>
					 
                  </div>
                                
               </div>
               <!-- container -->                
            </div>
         </div>
      </div>
   </div>
</div>
   </div>
</div>