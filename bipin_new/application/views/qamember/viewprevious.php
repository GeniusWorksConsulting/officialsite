
<div class="col-sm-9 col-md-9 col-lg-10" style="margin-top:5%;">
    <?php if ($this->session->flashdata('message_eror') != '') { ?>				
        <div class="alert alert-danger alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">�</a>					<strong><?php echo $this->session->flashdata('message_eror'); ?></strong>				</div>
    <?php } ?>
    <?php if ($this->session->flashdata('message_success') != '') { ?>				
        <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">�</a>					<strong><?php echo $this->session->flashdata('message_success'); ?></strong>				</div>
    <?php }
    ?>
    <div class="content">
        <div class="container">
            <!-- Page-Title -->                        
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">vb starter View Previous</h4>
                    <p class="text-muted page-title-alt"></p>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Index</th>
                                <th>Week</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < sizeof($data); $i++) {
                                if ($data[$i]['id'] > 3) {
                                    ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td><?php echo $data[$i]['week']; ?></td>
                                        <td><?php echo $data[$i]['month']; ?></td>
                                        <td><?php echo $data[$i]['year']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url(); ?>qamember/dashboard?week=<?php echo $data[$i]['week']; ?>&month=<?php echo $data[$i]['month']; ?>&year=<?php echo $data[$i]['year']; ?>" class="btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                        </tbody>
                    </table>		

                    <!-- End Partition --> 
                </div>

            </div>           
        </div>
    </div>
</div>

<!-- All Model -->

<style>
    .ownbg{
        background: #f51034;
    }
    .owncolor{
        color: #f51034;
    }
    h1,h5,h2,h3,h4{
        text-transform:uppercase;
        color:#000;
    }
    .textvoicebox{
        width:12%;
        display:inline-block;
        /*width: 9%;*/
        padding: 0px;
        text-align: center;

    }
    .marginbottomzero{
        margin-bottom:0px;
    }
    .margintopzero{
        margin-top:0px;
    }
    .marginleftzero{
        margin-left:0px;
    }
    .marginrightzero{
        margin-right:0px;
    }
    .textcomplete{
        background:red;
        width:100%;
        padding-left: 6px;
        padding-right: 6px;
        color: #fff;
        font-weight: bold;
        font-size: 10px;
    }
    .voicecomplete{
        background:red;
        width:100%;
        color: #fff;
        padding-left: 6px;
        padding-right: 6px;
        margin-top: 5px;
        font-weight: bold;
        font-size: 10px;
    }
    .textpending{
        background:#c1baba;
        width:100%;
        padding-left: 6px;
        padding-right: 6px;
        color: #fff;
        font-weight: bold;
        font-size: 10px;
    }
    .voicepending{
        background:#c1baba;
        width:100%;
        color: #fff;
        padding-left: 6px;
        padding-right: 6px;
        margin-top: 5px;
        font-weight: bold;
        font-size: 10px;
    }
    @media only screen and (max-width: 768px) {
        /* For mobile phones: */
        .textvoicebox {
            width: 50%;
        }
    }
    @media only screen and (max-width: 500px) {
        .textvoicebox {
            width: 100%;
        }
    }
    @media only screen and (min-width: 768px){
        .textvoicebox {
            width: 25%;
        }
    }
    @media only screen and (min-width: 1100px){
        .textvoicebox {
            width: 12%;
            /*width: 9%;*/
            padding: 0px;
        }
    }
    body{
        background:#fff;
    }

    /*Sanjay*/
    @media only screen and (min-width: 1200px) {
        .col-lg-2 {
            width: 14.666667%;
        }
    }
    @media only screen and (min-width: 1200px) {
        .col-lg-10 {
            width: 85.333333%;
        }
    }
</style>

