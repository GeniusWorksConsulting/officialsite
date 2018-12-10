<?php
function bitly($url) {
	
$bitlyid = get_option_tree ('bitly', '');
$bitlyapi = get_option_tree ('bitly-api', '');
$content = file_get_contents("http://api.bit.ly/v3/shorten?login=".$bitlyid."&apiKey=".$bitlyapi."&longUrl=".$url."&format=xml");
$element = new SimpleXmlElement($content);
$bitly = $element->data->url;
if($bitly){
echo $bitly;}
else{
	
echo $url;
}
}
?>