<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <?php
        if (!empty($user)) {
            $first_name = $user[0]['first_name'];
            $last_name = $user[0]['last_name'];
            $email = $user[0]['email'];
            $phoneno = $user[0]['phoneno'];
            $group_name = $user[0]['group_name'];
            $password = $user[0]['password'];
        } else {
            $first_name = "";
            $last_name = "";
            $email = "";
            $phoneno = "";
            $group_name = "";
            $password = "";
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
                                    <?php if ($this->session->flashdata('message_success') != '') { ?>				
                                        <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">�</a>					<strong><?php echo $this->session->flashdata('message_success'); ?></strong>				</div>
                                    <?php } ?>


                                    <div class="tab-content">

                                        <div id="referral_charges" class="tab-pane fade in active">
                                            <div class="col-sm-8">
                                                <form method="post" action="">
                                                    <h4 class="page-title">Add User</h4>


                                                    <label>First Name: </label>
                                                    <input type="text" class="form-control" name="first_name" required value="<?php echo $first_name; ?>">

                                                    <label>Last Name: </label>
                                                    <input type="text" class="form-control" name="last_name" required value="<?php echo $last_name; ?>">

                                                    <label>Email: </label>
                                                    <input type="email" class="form-control" name="email" required value="<?php echo $email; ?>">

                                                    <label>Password: </label>
                                                    <input type="text" class="form-control" name="password" required value="<?php echo $password; ?>">

                                                    <label>Phone Number: </label>
                                                    <input type="number" class="form-control" name="phoneno" value="<?php echo $phoneno; ?>">

                                                    <label>Squad Name</label>	
                                                    <select class="form-control" name="group_name" required>
                                                        <option>Select</option>
                                                        <option <?php
                                                        if ($group_name == "1") {
                                                            echo 'selected';
                                                        }
                                                        ?> value="1">1</option>
                                                        <option <?php
                                                        if ($group_name == "2") {
                                                            echo 'selected';
                                                        }
                                                        ?> value="2">2</option>
<!--                                                        <option <?php
                                                        if ($group_name == "3") {
                                                            echo 'selected';
                                                        }
                                                        ?> value="3">3</option>
                                                        <option <?php
                                                        if ($group_name == "4") {
                                                            echo 'selected';
                                                        }
                                                        ?> value="4">4</option>
                                                        <option <?php
                                                        if ($group_name == "5") {
                                                            echo 'selected';
                                                        }
                                                        ?> value="5">5</option>-->
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