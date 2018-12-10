
         <div class="col-sm-9 col-md-9 col-lg-10" style="margin-top:5%;">
            <div class="content">
               <div class="container">
                  <!-- Page-Title -->                        
                  <div class="row">
                     <div class="col-sm-12">
                        <h4 class="page-title">Change Password</h4>
                     </div>
                  </div>
				   <?php if($this->session->flashdata('message_eror') != '') {?>				
               <div class="alert alert-danger alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_eror');?></strong>				</div>
               <?php }?>
			    <?php if($this->session->flashdata('message_success') != '') {?>				
               <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success');?></strong>				</div>
               <?php }?>
                  <div class="row">
					<div class="col-md-5">
						<form method="post" action="">
							 <label>Enter Old Password</label>
							 <input type="text" class="form-control" required name="old_password">
							 
							 <label>Enter New Password</label>
							 <input type="text" class="form-control" required name="new_password">
							 
							 <label>Enter Confirm New Password</label>
							 <input type="text" class="form-control" required name="confirm_password">
							 
							 <input type="submit" class="btn btn-primary center-block" style="margin-top:5px;">
						</form> 
					</div>
                  </div>    
               </div>           
            </div>
         </div>