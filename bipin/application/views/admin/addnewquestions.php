<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <?php
        if (!empty($question)) {
			//print_r($question);
			//print_r($question_answer);
            $description = $question[0]['description'];
            //echo $description;
            $category_id = $question[0]['category_id'];
            $type = $question[0]['type'];
			$isparent = $question[0]['isparent'];
			$has_child = $question[0]['has_child'];
			$parent_id = $question[0]['parent_id'];
			$weighting = $question[0]['weighting'];
			$noofanswer = $question[0]['noofanswer'];
			
        } else {
            $description = "";
            $category_id = "";
            $type = "";
			$isparent = "";
			$has_child = "";
			$parent_id = "";
			$weighting = "";
			$noofanswer = "";
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <div class="content">
                            <div class="container">
                                <!-- Page-Title -->                        
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!--<h1>Setting</h1>-->
                                        <p class="text-muted page-title-alt"></p>
                                    </div>
                                    <?php if ($this->session->flashdata('message_success') != '') { ?>				
                                        <div class="alert alert-success alert-dismissable" style="clear:both;">					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>					<strong><?php echo $this->session->flashdata('message_success'); ?></strong>				</div>
                                    <?php } ?>


                                    <div class="tab-content">

                                        <div id="referral_charges" class="tab-pane fade in active">
                                            <div class="col-sm-8">
                                                <form method="post" action="">
                                                    <h4 class="page-title">Add New Question</h4>
                                                    <label>Category</label>	
                                                    <select class="form-control" name="category_id" required>
                                                        <option>Select</option>
                                                        <?php for ($i = 0; $i < sizeof($category); $i++) {
                                                            ?>
                                                            <option <?php
                                                            if ($category_id == $category[$i]['id']) {
                                                                echo 'selected';
                                                            }
                                                            ?> value="<?php echo $category[$i]['id']; ?>"><?php echo $category[$i]['name']; ?></option>
                                                            <?php }
                                                            ?>
                                                    </select>
                                                    <label>New Question: </label>
                                                    <input type="text" class="form-control" name="description" required value='<?php echo $description; ?>'>
                                                    <label>Is_parent</label>
                                                    <select class="form-control" name="isparent" required id="childdisplay">
                                                        <option>Select</option>
                                                        <option  value="parent" <?php if($isparent == "parent"){echo "selected"; } ?>>Parent</option>
                                                        <option  value="child" <?php if($isparent == "child"){echo "selected"; } ?>>Child</option>
                                                    </select>

                                                    <label id="question_label" style="<?php if($isparent == "parent"){ ?>display: none;<?php } ?>">Please Select Parent Question</label>
                                                    <select class="form-control" name="question" id="question" style="display: none;" >
                                                        <option value="0">Select</option>
                                                        <?php
                                                        for ($j = 0; $j < sizeof($newquestion); $j++) {
                                                            ?>

                                                        
                                                            <option value="<?php echo $newquestion[$j]['que_id']; ?>" <?php if($parent_id == $newquestion[$j]['que_id']){echo "selected"; } ?>>
                                                                <?php echo ucwords($newquestion[$j]['description']); ?>
                                                            </option>

                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <label>Assessment Type: </label>	
                                                    <select class="form-control" name="type" required>
                                                        <option>Select</option>
                                                        <option <?php
                                                        if ($type == "text") {
                                                            echo 'selected';
                                                        }
                                                        ?> value="text">Text</option>
                                                        <option <?php
                                                        if ($type == "voice") {
                                                            echo 'selected';
                                                        }
                                                        ?> value="voice">Voice</option>
                                                    </select>
													<label>Weighting: </label>
                                                    <input type="text" class="form-control" name="qweighting" value='<?php echo $weighting; ?>'>
                                                    <label>No Of Answer: </label>
                                                    <select class="form-control" name="noofanswer" id="noofanswer" required >
                                                        <option>Select</option>
                                                        <option value="1" <?php if($noofanswer == "1"){echo "selected"; } ?>>1</option>
                                                        <option value="2" <?php if($noofanswer == "2"){echo "selected"; } ?>>2</option>
                                                        <option value="3" <?php if($noofanswer == "3"){echo "selected"; } ?>>3</option>
                                                        <option value="4" <?php if($noofanswer == "4"){echo "selected"; } ?>>4</option>
                                                        <option value="5" <?php if($noofanswer == "5"){echo "selected"; } ?>>5</option>
                                                    </select>
                                                    <div class="allanswers">
													<?php if($noofanswer!="" && $noofanswer > 0){ ?>
														<table>
															<thead>
																<tr>
																	<th>Answer</th>
																	<th style="text-align:  center;">Rating</th>
																	<th style="text-align:  center;">Weighting</th>
																</tr>
															</thead>
															<tbody>
															<?php for($i = 0;$i<$noofanswer;$i++){ ?>
																<tr>
																<td> <input class="form-control" type="text" name="answers[]" required="" value="<?php echo $question_answer[$i]['answers']; ?>" style="width:400px;margin-bottom:10px;"> </td>
																<td> <input class="form-control" type="text" name="rating[]" value="<?php echo $question_answer[$i]['rating']; ?>" style="margin-bottom:10px;"> </td>
																<td> <input class="form-control" type="text" name="weighting[]" value="<?php echo $question_answer[$i]['weighting']; ?>" style="margin-bottom:10px;"> </td>
															</tr>
															<?php } ?>
														</tbody>
														</table>
													<?php } ?>
                                                    </div>    

                                                    <input type="submit" class="btn btn-primary center-block" name="btn_submit" value="<?php if(isset($_GET['id'])){echo 'Update';}else{echo "Submit"; }?>" style="margin-top:5px;">

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
<script>
    
    
     $('#childdisplay').change(function ()
    {
        if($(this).val() == "child")
        {
            $("#question").show();
			$("#question_label").show();
        }
		else
		{
			$("#question").hide();
			$("#question_label").hide();
		}
    });

    $('#noofanswer').change(function ()
    {
        var noofanswer = $(this).val();
        var html = "";
        html += "<table>";
        html += "<thead s>";
         html += "<tr>";
        html += "<th>Answer</th>";
        html += "<th style='text-align:  center;'>Rating</th>";
		html += "<th style='text-align:  center;'>Weighting</th>";
         html += "</tr>";
        html += "</thead>";
        html += "<tbody>";
        for (var i = 0; i < noofanswer; i++)
        {
            html += "<tr >";
            //html += "<div>";
            html += "<td> <input class='form-control' type='text' name='answers[]' required style='width:400px;margin-bottom:10px;' /> </td>";
            html += "<td> <input class='form-control' type='text' name='rating[]'  style='margin-bottom:10px;'  /> </td>";
			html += "<td> <input class='form-control' type='text' name='weighting[]'  style='margin-bottom:10px;'  /> </td>";
            //html += "</div>";
            html += "</tr>";
        }
        html += "</tbody>";
        html += "</table>";
        $('.allanswers').html(html);
    });
    

</script>