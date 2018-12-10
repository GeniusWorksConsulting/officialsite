<script src="https://feedeposit.com/public/frontend/js/jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>public/line.js" type="text/javascript"></script>
<div class="col-md-12">
	<h3 style="text-align:center;">Perception Gap Graph</h3>
	<p style="text-align:center;">This tracks your ability to reflect on your behaviour and how you can learn from it. The ideal score is 0. </p>
</div>
<div class="col-md-12 maindiv" style="width:100%;position: relative;">
	
</div>

<div class="myhover_show" style="display :none;">
	<div class="" style="background-color:#eee;">
		<div class="col-sm-1">
			<span style="padding: 9px 15px;">QA</span>
			<span style="padding: 9px 15px;">MY</span>
			<span style="padding: 9px 15px;">DIF</span>
		</div>
		<div class="col-sm-1">
			<span style="padding: 50px;">-</span>
			<span>=</span>
		</div>
		
		<div class="col-sm-1">
			<span class="qa_rating" style="padding-left:27px"></span>
			<span class="my_rating" style="padding-left:35px"></span>
			<span class="total_rating" style="padding-left:46px"></span>
		</div>
		
	</div>
</div>
<script>
$(document).ready(function (){
	$('.myhover').hover(function (){
		var this_top = $(this).attr("data-top");
		var this_left = $(this).attr("data-left");
		 //alert(this_top);
		// alert(this_left);
		// $('.myhover_show').toggle();
		$('.myhover_show').show();
		$('.myhover_show').css("top",this_top+"px");
		$('.myhover_show').css("left",this_left+"px");
		$('.myhover_show').css("position","absolute");
		var span_qa_rating = $(this).attr("data-qarating");
		var span_my_rating = $(this).attr("data-myrating");
		var span_total_rating = $(this).attr("data-totalrating");
		console.log(span_qa_rating+"~~"+span_my_rating+"~~"+span_total_rating);
		$('.qa_rating').html(span_qa_rating);
		$('.my_rating').html(span_my_rating);
		$('.total_rating').html(span_total_rating);
		
	});
	$('.myhover').mouseout(function (){
		$('.myhover_show').hide();
	});
});
// if 8 = j = 10;
// if 12 = j = 
var full_height = 400;
var no_of_part = 8;
var gap_part = full_height / no_of_part;


var j=10;
for(i=0;i<=no_of_part;i++){
	var mytop = i * gap_part;
	//console.log(mytop);
	
	var y = ((no_of_part /2)*j) - (i * j) ;
	$(".maindiv").append("<span style='position:absolute;left:0px;top:"+mytop+"px;width: 100%;'>"+y+"</span>")
	//console.log(y);
}
var week_qa_rating = [];
var week_my_rating = [];
var week = [];

<?php
$first_key_ = key($qarating);
for($i=$first_key_;$i<sizeof($qarating)+$first_key_;$i++){
	$first_key = key($qarating[$i]);
	for($j=$first_key;$j<sizeof($qarating[$i])+$first_key;$j++){
		if(isset($qarating[$i][$j])){ 
			$first_key_k = key($qarating[$i][$j]);
			for($k=$first_key_k;$k<sizeof($qarating[$i][$j])+$first_key_k;$k++){
				$qa_rating = $qarating[$i][$j][$k];
				$myarting = $sender_receiver_same[$i][$j][$k];
				//echo "qarating=".$qa_rating."   myrating=".$myarting."</br>";
				if($qa_rating==0 || $myarting==0){
				?>
					week.push("0");
					week_my_rating.push("0");
					week_qa_rating.push("0");
				<?php
				}else{
				?>
					week.push("<?php echo ($qa_rating - $myarting); ?>");
					week_my_rating.push("<?php echo ($myarting); ?>");
					week_qa_rating.push("<?php echo ($qa_rating); ?>");
				<?php
				}
			}
		}else{
			for($k=1;$k<=sizeof($qarating[$i]["0".$j]);$k++){
				$qa_rating = $qarating[$i]["0".$j][$k];
				$myarting = $sender_receiver_same[$i]["0".$j][$k];
				//echo "qarating=".$qa_rating."   myrating=".$myarting."</br>";
				if($qa_rating==0 || $myarting==0){
				?>
					week.push("0");
					week_my_rating.push("0");
					week_qa_rating.push("0");
				<?php
				}else{
				?>
					week.push("<?php echo ($qa_rating - $myarting); ?>");
					week_my_rating.push("<?php echo ($myarting); ?>");
					week_qa_rating.push("<?php echo ($qa_rating); ?>");
				<?php
				}
			}
		}
		?>
		week.push("30");
		week_my_rating.push("0");
		week_qa_rating.push("0");
		<?php
	}
}
 ?>
console.log("week = "+week);
console.log("week_my_rating = "+week_my_rating);
console.log("week_qa_rating = "+week_qa_rating);
 
 var week_name = [
 <?php
$first_key_ = key($qarating);
for($i=$first_key_;$i<sizeof($qarating)+$first_key_;$i++){
	$first_key = key($qarating[$i]);
	for($j=$first_key;$j<sizeof($qarating[$i])+$first_key;$j++){
		if(isset($qarating[$i][$j])){ 
			$first_key_k = key($qarating[$i][$j]);
			for($k=$first_key_k;$k<sizeof($qarating[$i][$j])+$first_key_k;$k++){
				$qa_rating = $qarating[$i][$j][$k];
				$myarting = $sender_receiver_same[$i][$j][$k];
				//echo "qarating=".$qa_rating."   myrating=".$myarting."</br>";
				echo '"'.$k.'",';
				//echo '"'.($qa_rating - $myarting).'",';
			}
		}else{
			for($k=1;$k<=sizeof($qarating[$i]["0".$j]);$k++){
				$qa_rating = $qarating[$i]["0".$j][$k];
				$myarting = $sender_receiver_same[$i]["0".$j][$k];
				//echo "qarating=".$qa_rating."   myrating=".$myarting."</br>";
				//echo '"'.($qa_rating - $myarting).'",';
				echo '"'.$k.'",';
			}
		}
		
		echo '"'.(30).'",';
	}
}
 ?>
 ];
 
 var month_name = [
 <?php
 function month_name($j){
	 if($j=="1" || $j=="01"){
			echo '"January",';
		}
		if($j=="2" || $j=="02"){
			echo '"February",';
		}
		if($j=="3" || $j=="03"){
			echo '"March",';
		}
		if($j=="4" || $j=="04"){
			echo '"April",';
		}
		if($j=="5" || $j=="05"){
			echo '"May",';
		}
		if($j=="6" || $j=="06"){
			echo '"June",';
		}
		if($j=="7" || $j=="07"){
			echo '"July",';
		}
		if($j=="8" || $j=="08"){
			echo '"August",';
		}
		if($j=="9" || $j=="09"){
			echo '"September",';
		}
		if($j=="10"){
			echo '"October",';
		}
		if($j=="11"){
			echo '"November",';
		}
		if($j=="12"){
			echo '"December",';
		}
 }
$first_key_ = key($qarating);
for($i=$first_key_;$i<sizeof($qarating)+$first_key_;$i++){
	$first_key = key($qarating[$i]);
	for($j=$first_key;$j<sizeof($qarating[$i])+$first_key;$j++){
		if(isset($qarating[$i][$j])){ 
			$first_key_k = key($qarating[$i][$j]);
			for($k=$first_key_k;$k<sizeof($qarating[$i][$j])+$first_key_k;$k++){
				$qa_rating = $qarating[$i][$j][$k];
				$myarting = $sender_receiver_same[$i][$j][$k];
				//echo "qarating=".$qa_rating."   myrating=".$myarting."</br>";
				month_name($j);
				//echo '"'.($qa_rating - $myarting).'",';
			}
		}else{
			for($k=1;$k<=sizeof($qarating[$i]["0".$j]);$k++){
				$qa_rating = $qarating[$i]["0".$j][$k];
				$myarting = $sender_receiver_same[$i]["0".$j][$k];
				//echo "qarating=".$qa_rating."   myrating=".$myarting."</br>";
				//echo '"'.($qa_rating - $myarting).'",';
				month_name($j);
			}
		}
		month_name($j);
		
		
	}
}
 ?>
 ];
 console.log(month_name);
 var main = (no_of_part / 2) * 10;
var myold_dot = 0;
var myold_left = 0;
var a=0;
var month_last = 0;

for(i=0;i<week.length;i++){
	// if(week[i]==50){
		// //
		// continue;
	// }else{
	if(week[i]!=30){
	a++;	
	var dot = ((main - (week[i])) * j) / 2;
	var myleft = (i*100) + 10;
	console.log(dot+" "+myleft);
	if(dot > 200)
	{
		$(".maindiv").append("<span class='myhover' data-qarating='"+week_qa_rating[i]+"' data-myrating='"+week_my_rating[i]+"' data-totalrating='"+(week_qa_rating[i]-week_my_rating[i])+"' data-top='"+(dot+5)+"' data-left='"+(myleft-30)+"' style='position:absolute;left:0px;top:"+dot+"px;left:"+myleft+"px;height:20px;width:20px;border-radius:100%;background:red;'></span>");
	}
	else
	{
		$(".maindiv").append("<span class='myhover' data-qarating='"+week_qa_rating[i]+"' data-myrating='"+week_my_rating[i]+"' data-totalrating='"+(week_qa_rating[i]-week_my_rating[i])+"'  data-top='"+(dot+5)+"' data-left='"+(myleft-30)+"'  style='position:absolute;left:0px;top:"+dot+"px;left:"+myleft+"px;height:20px;width:20px;border-radius:100%;background:black;'></span>");
	}
	
	$(".maindiv").append("<span style='position:absolute;left:0px;top:20px;left:"+myleft+"px;'>Week "+week_name[i]+"</span>");
	if(i!=0 && i!= week.length){
		$('.maindiv').line(myold_dot, myold_left, myleft+5, dot+5, {color:"#000", stroke:5, zindex:1001}, function(){});
		
		console.log("old_left = "+myold_dot+" myold_top = "+myold_left);
		console.log("old_left = "+myleft+" myold_top = "+dot);
	}
	
		myold_dot = myleft+5;
		myold_left = dot +5;
	}else{
		$('.maindiv').line((i*100)+20, 10, (i*100)+20, 500, {color:"#d43030", stroke:5, zindex:1001}, function(){});
		// $(".maindiv").append("<span style='position:absolute;left:0px;top:0px;left:"+(month_left)+"px;'>January</span>");
		// alert(month_left+"   "+a);
		// a=0;
		var new_left = ((i-month_last) / 2) *100 + (month_last * 100);
		month_last = i;
		a=0;
		$(".maindiv").append("<span style='position:absolute;left:0px;top:0px;left:"+(new_left)+"px;'>"+month_name[i]+"</span>");
	}
}
</script>
