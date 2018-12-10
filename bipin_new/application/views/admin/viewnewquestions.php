<style>
    #mg-multisidetabs .list-group-item:first-child {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    #mg-multisidetabs .list-group-item:last-child {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    #mg-multisidetabs .list-group{
        margin-bottom:0;
    }
    .slide-container{
        overflow:hidden;
    }
    #mg-multisidetabs .list-sub{
        display:none;
    }
    #mg-multisidetabs .panel{
        margin-bottom:0;
    }
    #mg-multisidetabs .panel-body{
        padding:1px 2px;
    }
    .mg-icon{
        font-size:10px;
        line-height: 20px;
    }
</style>
<div class="content-page">
    <!-- Start content -->
    <div class="content">

        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <div class="content">
                            <div class="container">
                                <!-- Page-Title -->                        
                                <div class="row">
                                    <div class="col-sm-12">
										<a href="<?php echo base_url();?>admin/viewnewquestions?type=text" class="btn btn-primary">Text</a>
										<a href="<?php echo base_url();?>admin/viewnewquestions?type=inbound" class="btn btn-primary">InBound</a>
										<a href="<?php echo base_url();?>admin/viewnewquestions?type=outbound" class="btn btn-primary">OutBound</a>
                                        <h4 class="page-title">Question List</h4>
                                        <p class="text-muted page-title-alt"></p>
                                    </div>
                                </div>
                                <form method="POST">
                                <?php if ($this->session->flashdata('message_success') != '') { ?>				
                                    <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success'); ?></strong>				</div>
                                <?php } ?>
                                <?php
                                for ($i = 0; $i < sizeof($getallcatageory); $i++) {
                                    ?>
                                    <h3><?php echo $getallcatageory[$i]['name']; ?></h3>
                                    <div>
                                        <?php
                                        $CI = & get_instance();
                                        $CI->load->model('Admin_model');
                                        $question = $CI->Admin_model->GetAllNewQuestions($getallcatageory[$i]['id']);
                                        //print_r($question);
                                        for ($j = 0; $j < sizeof($question); $j++) {
                                            $answer = $CI->Admin_model->GetAllNewAnswer($question[$j]['que_id']); 
                                             //print_r($answer);
                                            if ($question[$j]['isparent'] == "parent") {
                                                if ($question[$j]['has_child'] == 1) {
                                                    $has_child_class = "has_child";
                                                } else {
                                                    $has_child_class = "";
                                                }
                                                
                                                ?>
                                                <div >
                                                    <h4><?php echo '<b>' . ($j + 1) . '</b> '; ?><?php echo $question[$j]['description'] ?>&nbsp;<a class="btn btn-warning" href="<?php echo base_url();?>admin/addnewquestions?id=<?php echo $question[$j]['que_id']; ?>"><i class="fa fa-pencil"></i> EDIT</a></h4>
                                                    <input type="hidden" name="question_ids[]" value="<?php echo $question[$j]['que_id']; ?>">
                                                    <input type="hidden" name="weighting_ids[]" value="<?php echo $question[$j]['que_id']; ?>">
                                                    <?php //print_r($answer); ?>

                                                    <select name="ans_values[]" id="parent_<?php echo $question[$j]['que_id']; ?>" class="parent <?php echo $has_child_class; ?> form-control" data-parent="<?php echo $question[$j]['que_id'] ?>" name="answer" >
                                                        <option>Select</option>
                                                        <?php
                                                       
                                                        for ($k = 0; $k < sizeof($answer); $k++) {
                                                            ?>
                                                            <option value="<?php echo $answer[$k]['rating']; ?>">
                                                                <?php echo ucwords($answer[$k]['answers']); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                            } else {
                                                ?>  
                                                <div style="display: none;" class="childof_<?php echo $question[$j]['parent_id']; ?>">
                                                    <h4><?php echo $question[$j]['description'] ?></h4>
                                                    <input type="hidden" name="question_ids[]" value="<?php echo $question[$j]['que_id']; ?>">
                                                    <input type="hidden" name="weighting_ids[]" value="<?php echo $question[$j]['que_id']; ?>">
                                                    <select name="ans_values[]"  id="parent_<?php echo $question[$j]['que_id']; ?>" class="parent <?php echo $has_child_class; ?> form-control" data-parent="<?php echo $question[$j]['que_id'] ?>" name="answer" >
                                                        <option value="">Select</option>
                                                        <?php
                                                        for ($k = 0; $k < sizeof($answer); $k++) {
                                                            ?>
                                                            <option value="<?php echo $answer[$k]['rating']; ?>">
                                                                <?php echo ucwords($answer[$k]['answers']); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php } ?>
                                    <input type="submit" value="Submit">
                                </form>
                            </div>
                            <!-- container -->                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
 $('.parent').change(function(){
     var parent_id = $(this).data('parent');
     var text = $('#parent_'+parent_id+" option:selected").text();
     text = $.trim(text);
    if($(this).hasClass('has_child')){
        if(text == "Yes"){
            
            $('.childof_'+parent_id).show();
        }
        else
        {
            $('.childof_'+parent_id).hide();
        }
    } 
 });
    
    
</script>    