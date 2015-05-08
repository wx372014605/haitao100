<?php 
$from = get_string('from');
$to = get_string('to');
if($from=='' or $to==''){
	exit("");
}
$exchange_rate =  get_exchange_rate($from,$to);
if(!$exchange_rate){
	exit("");
}else{
	echo $exchange_rate;
}
?>