<div class="container">
    <div class="row">
        <form method="post" action="">
            <div class="col-sm-12">
                <div class="col-sm-4"></div>
                <div class="col-sm-4 col-sm-offest-4" style="margin-top:5%;">
                    <?php if ($this->session->flashdata('message') != '') { ?>				
                        <div class="alert alert-danger alert-dismissable">					
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					
                            <strong><?php echo $this->session->flashdata('message'); ?></strong>
                        </div>
                    <?php } ?>						
                    <?php if ($this->session->flashdata('message_success') != '') { ?>				
                        <div class="alert alert-success alert-dismissable">					
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					
                            <strong><?php echo $this->session->flashdata('message_success'); ?></strong>				
                        </div>
                    <?php } ?>			

                    <h2>Send Coin to User</h2>
                    <label>First Name: </label>			
                    <input type="text" class="form-control" value="<?= $userdetails[0]->first_name ?>" disabled>						
                    <label>Last Name: </label>			
                    <input type="text" class="form-control" value="<?= $userdetails[0]->last_name ?>" disabled>						
                    <label>Coin: </label>			
                    <br>			
                    <input type="text" class="form-control" name="coin">						
                    <input type="submit" class="btn btn-default btn-primary center-block" value="Submit" style="margin-top:5px;">						
                </div>
            </div>
        </form>
    </div>
</div>
<style>label{	margin-top:5px;}</style>
