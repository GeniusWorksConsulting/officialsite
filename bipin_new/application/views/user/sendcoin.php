<div class="container">	<div class="row">		<form method="post" action="">			<div class="col-sm-12">					<div class="col-sm-4"></div>									<div class="col-sm-4 col-sm-offest-4" style="margin-top:5%;">						<?php if($this->session->flashdata('message_error') != '') {?>				<div class="alert alert-danger alert-dismissable">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_error');?></strong>				</div>			<?php }?>						<?php if($this->session->flashdata('message_success') != '') {?>				<div class="alert alert-success alert-dismissable">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success');?></strong>				</div>			<?php }?>			<h2>Send Coin to User</h2>						<label>User Name: </label>			<input type="text" class="form-control" name="username">						<label>Mobile Number: </label>			<input type="text" class="form-control" name="mobile" >						<label>Coin: </label>			</br>			<input type="text" class="form-control" name="coin" id="coin">						<input type="submit" class="btn btn-default btn-primary center-block" value="Submit" style="margin-top:5px;">						</div>		</div>			</form>		</div></div><style>label{	margin-top:5px;}</style><script>jQuery("#coin").on("keyup", function(){    var valid = /^\d{0,4}(\.\d{0,2})?$/.test(this.value),        val = this.value;        if(!valid){        console.log("Invalid input!");        this.value = val.substring(0, val.length - 1);    }});</script>