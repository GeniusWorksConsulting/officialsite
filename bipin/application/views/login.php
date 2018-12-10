<div class="page-heading">

	<div class="container" style="text-align:center;">

		<h1>Login</h1>

	</div>

</div>



<div class="container body-content">

	<div id="dvc-resale-calculator">

	
		<div class="row">

		
		<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
			<div class="col-sm-12">
			
			 <?php if($this->session->flashdata('inactive_error') != '') {?>

        <div class="alert alert-warning alert-dismissable">

            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>

            <strong><?php echo $this->session->flashdata('inactive_error');?></strong>

        </div>

    <?php }?>



    <?php if($this->session->flashdata('incorrect_info') != '') {?>

        <div class="alert alert-danger alert-dismissable">

            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>

            <strong><?php echo $this->session->flashdata('incorrect_info');?></strong>

        </div>

    <?php }?>
				<form action="<?=base_url() ?>login" method="post">
			    			

			    			<div class="form-group">
			    				<input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email Address">
			    			</div>

			    			
							<div class="form-group">
								<input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password">
							</div>
			    				
			    			<a href="<?php echo base_url()."forgot"; ?>" style="float:right;">Forgot Password?</a>
			    			<input type="submit" value="Submit" class="btn btn-info btn-block">
			    		
			    		</form>
			</div>
		</div>
		</div>

	</div>

</div>
<style>
label{
	margin-top:5px;
}
</style>
