<?php
echo ' Sanjay'; 

$con = mysqli_connect("localhost","geniuswo_wrdp2","DZj[2mthEsLU","geniuswo_wrdp2"); 


$sql = "select * from wp_users";

$results = mysqli_query($con, $sql);
while($row = mysqli_fetch_assoc($results))
{
	print_r($row);
}
//echo $sql;
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error(); 
  }
  
  ?>

