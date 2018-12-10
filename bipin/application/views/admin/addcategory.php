<div class="content-page">
    <!-- Start content -->
    <div class="content">
<?php

	if(!empty($category)){

		$name = $category[0]['name'];
		$weighting = $category[0]['weighting'];
		$type = $category[0]['type'];
	}else{
		$name = "";
		$weighting = "";
		$type = "";
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
                        <h1>Add Category</h1>
                        <p class="text-muted page-title-alt"></p>
                     </div>
					 <?php if($this->session->flashdata('message_success') != '') {?>				
               <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success');?></strong>				</div>
               <?php }?>
					

					<div class="tab-content">
					  
					  <div id="referral_charges" class="tab-pane fade in active">
					  <div class="col-sm-8">
						<form method="post" action="">
							<h4 class="page-title">Add Category</h4>
								<div class="form-group">
									<label>Assessment Type</label>
									<select class="form-control" name="type" >
										<option value="text" <?php if($type=="text"){echo 'selected';} ?>>Text</option>
										<option value="inbound" <?php if($type=="inbound"){echo 'selected';} ?>>Voice(InBound)</option>
										<option value="outbound" <?php if($type=="outbound"){echo 'selected';} ?>>Voice(OutBound)</option>
									</select>
								</div>
								<div class="form-group">
									<label>Name of Category</label>
									<input type="text" class="form-control" name="name" required value="<?php echo $name; ?>">
								</div>
								<div class="form-group">
									<label>Weighting</label>
									<input type="text" class="form-control" name="weighting" required value="<?php echo $weighting; ?>">
								</div>
							
							
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