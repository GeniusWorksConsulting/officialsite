<div class="page-heading">

	<div class="container" style="text-align:center;">

		<h1>Registration</h1>

	</div>

</div>



<div class="container body-content">

	<div id="dvc-resale-calculator">

	
		<div class="row">

		<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
			<div class="col-sm-12">
			
			<div class="form_error" style="color: red;">
				<?php echo validation_errors(); ?>
			</div>
			<div class="form_error" style="color: green;">
				<?php if(isset($_POST['message'])) { echo $_POST['message']; } ?>
			</div>
				<form action="#" method="post">
			    			<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			                <input type="text" name="first_name" id="first_name" class="form-control input-sm floatlabel" placeholder="First Name" value="<?php if(isset($_POST['first_name'])) { echo $_POST['first_name']; } ?>">
			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    						<input type="text" name="last_name" id="last_name" class="form-control input-sm" placeholder="Last Name" value="<?php if(isset($_POST['last_name'])) { echo $_POST['last_name']; } ?>">
			    					</div>
			    				</div>
			    			</div>
							
							<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			                <input type="text" name="user_name" id="user_name" class="form-control input-sm floatlabel" placeholder="User Name" value="<?php if(isset($_POST['user_name'])) { echo $_POST['user_name']; } ?>">
			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    						<input type="text" name="mobile" id="mobile" class="form-control input-sm" placeholder="Mobile Number" value="<?php if(isset($_POST['mobile'])) { echo $_POST['mobile']; } ?>">
			    					</div>
			    				</div>
			    			</div>

			    			<div class="form-group">
			    				<input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email Address" value="<?php if(isset($_POST['email'])) { echo $_POST['email']; } ?>">
			    			</div>

			    			<div class="row">
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    						<input type="password" name="password" id="password" class="form-control input-sm" placeholder="Password">
			    					</div>
			    				</div>
			    				<div class="col-xs-6 col-sm-6 col-md-6">
			    					<div class="form-group">
			    						<input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-sm" placeholder="Confirm Password">
			    					</div>
			    				</div>
			    			</div>
			    			
			    			<input type="submit" value="Register" class="btn btn-info btn-block">
			    		
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
