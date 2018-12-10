<div class="container">	
    <div class="row">
        <div class="col-sm-12">	
            <div class="col-sm-2"></div>
            <div class="col-sm-10" style="margin-top:5%;">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#pending">Pending User</a>
                    </li>			  
                    <li><a data-toggle="tab" href="#active">Active User</a></li>
                </ul>
                <div class="tab-content">
                    <div id="pending" class="tab-pane fade in active">
                        <h3>Pending User</h3>
                        <?php print_r($pendinguser); ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Firstname</th>
                                        <th>Lastname</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Reg. Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < sizeof($pendinguser); $i++) { ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= $pendinguser[$i]->first_name ?></td>
                                            <td><?= $pendinguser[$i]->last_name ?></td>
                                            <td><?= $pendinguser[$i]->user_name ?></td>
                                            <td><?= $pendinguser[$i]->email ?></td>
                                            <td><?= $pendinguser[$i]->mobile ?></td>
                                            <td><?= date("d-m-Y", strtotime($pendinguser[$i]->createdat)) ?></td>
                                            <td><a href="<?= base_url() . "admin/sendcoin/" . $pendinguser[$i]->user_id ?>">Send Coin</a></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="active" class="tab-pane fade">
                        <h3>Active User</h>
                            <?php print_r($activeuser); ?>
                            <div class="table-responsive"></div>
                            <table class="table">
                                <thead>	
                                    <tr>
                                        <th>#</th>
                                        <th>Firstname</th>
                                        <th>Lastname</th><th>User Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Available Coins</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < sizeof($activeuser); $i++) { ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= $activeuser[$i]->first_name ?></td>
                                            <td><?= $activeuser[$i]->last_name ?></td>
                                            <td><?= $activeuser[$i]->user_name ?></td>
                                            <td><?= $activeuser[$i]->email ?></td>
                                            <td><?= $activeuser[$i]->mobile ?></td>
                                            <td><?= $activeuser[$i]->available_coins ?></td>
                                            <td></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>