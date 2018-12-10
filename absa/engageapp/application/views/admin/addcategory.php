<div class="content-page">
    <!-- Start content -->
    <div class="content">
<?php

	if(!empty($category)){

		$name = $category[0]['name'];
	}else{
		$name = "";
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
							
								
									<label>Name of Category</label>
									<input type="text" class="form-control" name="name" required value="<?php echo $name; ?>">
														
							
							
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