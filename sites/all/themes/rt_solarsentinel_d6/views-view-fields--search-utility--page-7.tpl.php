<?php
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 <?php foreach ($fields as $id => $field): ?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>

  <?php print $field->wrapper_prefix; ?>
    <?php print $field->label_html; ?>
    <?php print $field->content; ?>
  <?php print $field->wrapper_suffix; ?>
<?php endforeach; ?>
 
 * @ingroup views_templates
    [title] == ??: ??
    [field_0_deductible_value] == ??: $0 Deductible (field_0_deductible)
    [field_50_deductible_value] == ??: $50 Deductible (field_50_deductible)
    [field_100_deductible_value] == ??: $100 Deductible (field_100_deductible)
    [field_250_deductible_value] == ??: $250 Deductible (field_250_deductible)
    [field_500_deductible_value] == ??: $500 Deductible (field_500_deductible)
    [field_1000_deductible_value] == ??: $1000 Deductible (field_1000_deductible)
    [field_3000_deductible_value] == ??: $3000 Deductible (field_3000_deductible)
    [field_over_85_deductible_value] == ??: Over 85 dedutible (field_over_85_deductible)
    [field_100_ded_max_discount_value] == ??: $100 Deductible Max Discount (field_100_ded_max_discount)
    [field_500_ded_max_discount_value] == ??: $500 Deductible Max Discount (field_500_ded_max_discount)
    [field_1000_ded_max_discount_value] == ??: $1000 Deductible Max Discount (field_1000_ded_max_discount)
    [field_discount_age_limit_value] == ??: Disacount age limit (field_discount_age_limit)
    [field_surcharge_value] == ??: Surcharge to premium (field_surcharge)
    [field_rate_schedule_value] == ??: Rate Schedule With Stable Pre-exisiting Medical Condition Coverage (field_rate_schedule)
    [field_rate_schedule_no_spmcc_value] == ??: Rate Schedule Without Stable Pre-exisiting Medical Condition Coverage (field_rate_schedule_no_spmcc)




 
 $_GET:
 array
  'q' => string 'visitor-travel-insurance' (length=24)
  'age' => string '0' (length=1)
  'amount' => string '$25000' (length=6)
  'start' => string '01/25/2012' (length=10)
  'end' => string '01/25/2012' (length=10)
  'sid' => string '18' (length=2)
 */
?>

<?php
	
	$none_str = "n/a";
	//Get the age
	$age = $_GET['age'];
	//Insurance Amount
	$amount = trim($_GET['amount'],"$");
	
	$start = $_GET['start'];
	$end = $_GET['end'];
	$start_time = strtotime( $start );
	$end_time = strtotime( $end );
	
	//NID
	$nid = $fields["nid"]->raw;
	
	$node_id = $_GET['nid'];
	
	if($nid != $node_id){
		return;
	}
	
	//Dedutible
	$dedutible = $_GET['dedutible'];
	$with_pre_existing = $_GET['with-pre-existing'];
	
	//total days
	$days = (($end_time - $start_time)/(60*60*24))+1;
	$days = round($days);
	$field_100_ded_max_discount_value = $fields["field_100_ded_max_discount_value"]->raw;
	$field_500_ded_max_discount_value = $fields["field_500_ded_max_discount_value"]->raw;
	$field_1000_ded_max_discount_value = $fields["field_1000_ded_max_discount_value"]->raw;
	
	/**RSV PRICE  START****/
	$rsv = unserialize($fields["field_rate_schedule_value"]->raw);	
	$new_rsv = array();
	
	
	foreach($rsv as $key => $val)
	{
		//rebuild the arrary from 1 dimension array to 2 dimension array.
		$key_arr = explode("_",$key);
		$new_rsv[$key_arr[1]][$key_arr[2]] = $val;
	}
	//row is the horizontal count. @check TIC- JF table.
	$row = "";
	foreach($new_rsv as $key => $val)
	{
		$age_ranges_str = $val[0];
		//"0-25"
		if(strpos($age_ranges_str,"-") !== false)
		{
			$age_arr = explode("-",$age_ranges_str);
			$age_arr[0] = intval($age_arr[0]);
			$age_arr[1] = intval($age_arr[1]);
			if( $age_arr[0] <= $age && $age <= $age_arr[1] )
			{
				$row = $key;
				break;
			}
		}
	}
	//Column is the vertical count. @check TIC- JF table.
	$column = "";
	foreach($new_rsv[0] as $key => $val)
	{
		if($val == $amount)
		{
			$column = $key;
			break;
		}
	}

	//Get the price here.
	if(!empty($row) && !empty($column))
	{
		//If price is found in the table, calculate it.
		$price = $new_rsv[$row][$column];
		if($price == $none_str)
		{
			$price = null;
			$total_price = null;
		}else{
			$total_price = $price * $days;
		}
		
	}else
	{//If the price couldn't be found in the table.
		$price = null;
		$total_price = null;
	}
	/**RSV PRICE  END****/
	
	
	
	/**RSNSV PRICE  START****/
	$rsnsv = unserialize($fields["field_rate_schedule_no_spmcc_value"]->raw);
	if(!empty($rsnsv))
	{
		$new_rsnsv = array();
		foreach($rsnsv as $key => $val)
		{
			//rebuild the arrary from 1 dimension array to 2 dimension array.
			$key_arr = explode("_",$key);
			$new_rsnsv[$key_arr[1]][$key_arr[2]] = $val;
		}
		//row is the horizontal count.
		$row_rsnsv = "";
		foreach($new_rsnsv as $key => $val)
		{
			$age_ranges_str = $val[0];
			//"0-25"
			if(strpos($age_ranges_str,"-") !== false)
			{
				$age_arr = explode("-",$age_ranges_str);
				$age_arr[0] = intval($age_arr[0]);
				$age_arr[1] = intval($age_arr[1]);
				if( $age_arr[0] <= $age && $age <= $age_arr[1] )
				{
					$row_rsnsv = $key;
					break;
				}
			}
		}
		//Column is the vertical count.
		$column_rsnsv = "";
		foreach($new_rsnsv[0] as $key => $val)
		{
			if($val == $amount)
			{
				$column_rsnsv = $key;
			}
		}
	}
	
	
	
	
	//Get the price here.
	if(!empty($row_rsnsv) && !empty($column_rsnsv))
	{
		//If price is found in the table, calculate it.
		$price_rsnsv = $new_rsnsv[$row_rsnsv][$column_rsnsv];
		if($price_rsnsv == $none_str)
		{
			$price_rsnsv = null;
			$total_price_rsnsv = null;
		}else{
			$total_price_rsnsv = $price_rsnsv * $days;
		}
	}else
	{//If the price couldn't be found in the table.
		$price_rsnsv = null;
		$total_price_rsnsv = null;
	}
	
	if(!isset($price_rsnsv) && !isset($price))
	{
		return;
	}
	
	
	/**RSNSV PRICE  END****/
	
	
	//initialize the array.
	$ded_arr = array(
		"0"=>$fields["field_0_deductible_value"]->raw,
		"50"=>$fields["field_50_deductible_value"]->raw,
		"100"=>$fields["field_100_deductible_value"]->raw,
		"250"=>$fields["field_250_deductible_value"]->raw,
		"500"=>$fields["field_500_deductible_value"]->raw,
		"1000"=>$fields["field_1000_deductible_value"]->raw,
		
	);
	if($amount >= 100000 && $days >= 365)
	{
		$ded_arr["3000"] = $fields["field_3000_deductible_value"]->raw;
	}
	
	$new_ded_arr = array();
	foreach($ded_arr as $k=>$v)
	{
		if( isset($v) && ($dedutible == $k) )
		{
			$v = 1+floatval($v);
			$new_ded_arr[$k] = $v;
		}
	}
	
	$rowspan = (count($new_ded_arr));
	$rowspan_flag = true;
	//when age is small than 86, print discount price
	$field_discount_age_limit_value = isset($fields["field_discount_age_limit_value"]->raw)? $fields["field_discount_age_limit_value"]->raw : 9999;
	
	//If this insurance has "surcharge to premium" set up. Used in TU insurance.
	if(isset($price_rsnsv) && !isset($price) && $fields["field_surcharge_value"]->raw)
	{
		//Surcharge portion is not appliable to "dedutiable discount". Hence we seperate this portion. Used in TU.
		$tu_price_rsnsv_surcharge = $price_rsnsv * ($fields["field_surcharge_value"]->raw);
		$total_tu_price_rsnsv_surcharge = $tu_price_rsnsv_surcharge * $days;
		
		$price = $price_rsnsv;
		$total_price = $price * $days;
	}
	
	if($age < $field_discount_age_limit_value){
		foreach($new_ded_arr as $key => $value){
		
			
			
			if(!empty($price))
			{
				$final_price = $price*$value;
				$final_total_price = $total_price*$value;
			}
			
			//If this is TU, and the surcharge portion is not appliable to "dedutiable discount". 
			if( !empty($tu_price_rsnsv_surcharge) && $fields["field_surcharge_value"]->raw)
			{
				$final_price += $tu_price_rsnsv_surcharge;
				$final_total_price += $total_tu_price_rsnsv_surcharge;
			}
			
			if(!empty($price_rsnsv))
			{
				$final_price_rsnsv = $price_rsnsv*$value;
				$final_total_price_rsnsv = $total_price_rsnsv*$value;
			}			
			
			if(!empty($fields["field_".$key."_ded_max_discount_value"]->raw))
			{
				if(!empty($price))
				{
					//If the price isn't null, total price won't be null either.
					$tem = $total_price - $fields["field_".$key."_ded_max_discount_value"]->raw;
					$final_total_price = $final_total_price > $tem ? $final_total_price : $tem;
				}
				if(!empty($price_rsnsv))
				{
					//If the price isn't null, total price won't be null either.
					$tem = $total_price_rsnsv - $fields["field_".$key."_ded_max_discount_value"]->raw;
					$final_total_price_rsnsv = $final_total_price_rsnsv > $tem ? $final_total_price_rsnsv : $tem;
				}
			}

			if($with_pre_existing == "yes")
			{
				if(!empty($total_price)){print round($final_total_price,2);}else{print $none_str;};
			}else{
				if(!empty($total_price_rsnsv)){print round($final_total_price_rsnsv,2);}else{print $none_str;};
			}

		}
	}else{
	//When age over 85, print original price. And there is no other deductible.
	
		$rowspan = 1;
		if(!empty($fields["field_over_85_deductible_value"]->raw)){

			if($with_pre_existing == "yes")
			{
				if(!empty($total_price)){print round($total_price,2);}else{print $none_str;};
			}else{
				if(!empty($total_price_rsnsv)){print round($total_price_rsnsv,2);}else{print $none_str;};
			}
		}
	}
?>
