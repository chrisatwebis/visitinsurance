<?php
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php
	//Get the age
	$age = $_GET['age'];
	
	//Insurance Amount
	$amount = $_GET['amount'];
	
	$start = $_GET['start'];
	$end = $_GET['end'];
	$start_time = strtotime( $start );
	$end_time = strtotime( $end );
	global $language;
	//var_dump($language);
	//total days
	$days = (($end_time - $start_time)/(60*60*24))+1;
	$days = round($days);
	if(!isset($age) || !isset($amount))
	{
		print t("This is a internal page!");
		
	}else{
		foreach ($rows as $id => $row){print $row; }
	}
?>