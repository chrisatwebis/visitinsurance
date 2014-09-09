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
    [field_2500_deductible_value] == ??: $1000 Deductible (field_2500_deductible)
    [field_3000_deductible_value] == ??: $3000 Deductible (field_3000_deductible)
    [field_5000_deductible_value] == ??: $1000 Deductible (field_5000_deductible)
    [field_10000_deductible_value] == ??: $3000 Deductible (field_10000_deductible)	
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
	//NID
	$nid = $fields["nid"]->raw;
  $node = node_load($nid);
  
	//Insurance Amount
	$amount = trim($_GET['amount'],"$");
	
	$start = $_GET['start'];
	$end = $_GET['end'];
	$start_time = strtotime( $start );
	$end_time = strtotime( $end );
	
	//Get the age
  $birthday = $_GET['birthday'];
  $age = site_helper_get_which_age_by_node($node, $birthday, $start);
	
	//total days
	$days = (($end_time - $start_time)/(60*60*24))+1;
	$days = round($days);
	$field_100_ded_max_discount_value = $fields["field_100_ded_max_discount_value"]->raw;
	$field_500_ded_max_discount_value = $fields["field_500_ded_max_discount_value"]->raw;
	$field_1000_ded_max_discount_value = $fields["field_1000_ded_max_discount_value"]->raw;
	
	/**RSV PRICE  START
  ** before discount and other condition.
  ****/
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
	
	
	
	/**RSNSV PRICE  START
  ** before discount and other condition.
  ****/
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
		"75"=>$fields["field_75_deductible_value"]->raw,    
		"100"=>$fields["field_100_deductible_value"]->raw,
		"250"=>$fields["field_250_deductible_value"]->raw,
		"500"=>$fields["field_500_deductible_value"]->raw,
		"1000"=>$fields["field_1000_deductible_value"]->raw,
		"2500"=>$fields["field_2500_deductible_value"]->raw,
		"5000"=>$fields["field_5000_deductible_value"]->raw,
		"10000"=>$fields["field_10000_deductible_value"]->raw,
	);
	if($amount >= 100000 && $days >= 365)
	{
		$ded_arr["3000"] = $fields["field_3000_deductible_value"]->raw;
	}
	
	//This is only for TIC Visitor to Canada Emergency & Hospital.
	//Changed required by John on June 7 2012.
	//Detailed requirement: there is no limit for 3000 dedutible. Neither the age limite nor the insurance amount limit or insurance duration days limit.
	if($nid == 87)
	{
		$ded_arr["3000"] = $fields["field_3000_deductible_value"]->raw;
	}
	
	//This is only for TIC JF optimum.
	//Changed required by John on June 7 2012.
	//Detailed requirement: there is only one limit for 3000 dedutible. The insurance amount needs to be $100,000 and up.Neither the age limit nor insurance duration days limit.
	if($nid == 66)
	{
    //３０００和１０００的垫底费只适用于１０万和１５万保额一年期的计划的所有年龄段
		if($amount >= 100000  && $days >= 365)
		{
			$ded_arr["1000"] = $fields["field_1000_deductible_value"]->raw;
      $ded_arr["3000"] = $fields["field_3000_deductible_value"]->raw;
		}else{
      unset($ded_arr["1000"]);
      unset($ded_arr["3000"]);
    }
	}
	
	//this is only for ETFS.
	if($nid == 83)
	{
		$ded_arr["3000"] = $fields["field_3000_deductible_value"]->raw;
	}
	
  //Travel Guard-Gold Visitors to  Canada Emergency Medical Plan : 2万5、5万的有50垫底费，而15万的是零垫底费，我已经设置成为50垫底费，
  //但15万的保额需要单独调会0垫底费；另外，60-84岁最多只能够买183天，也需要设置一下。
  //
	if($nid == 1581)
	{
    if($amount == 150000){
      unset($ded_arr["50"]);
    }else{
      unset($ded_arr["0"]);
    }
    
    if($age >= 60 && $age <= 84 && $days > 183){
      unset($ded_arr["0"]);
      unset($ded_arr["50"]);
    }
	}
  
	$new_ded_arr = array();
	foreach($ded_arr as $k=>$v)
	{
		if(isset($v))
		{
			$v = 1+floatval($v);
			$new_ded_arr[$k] = $v;
		}
	}
	
	$rowspan = (count($new_ded_arr));
	$rowspan_flag = true;
	//when age is small than 86, print discount price
	//Used in TIC
	$field_discount_age_limit_value = isset($fields["field_discount_age_limit_value"]->raw)? $fields["field_discount_age_limit_value"]->raw : 9999;
	
	//If this insurance has "surcharge to premium" set up. Used in TU insurance.
	if(isset($price_rsnsv) && !isset($price) && $fields["field_surcharge_value"]->raw)
	{
		//Surcharge portion is not appliable to "dedutiable discount". Hence we seperate this portion. Used in TU.
		$tu_price_rsnsv_surcharge = $price_rsnsv * ($fields["field_surcharge_value"]->raw);
		$total_tu_price_rsnsv_surcharge = $tu_price_rsnsv_surcharge * $days;
		if($age < 80){
			$price = $price_rsnsv;
			$total_price = $price * $days;
		}
		
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
			//If it is the price without stable pre-existing medical coverage, we dont add the surcharge.
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
					//Total_price = original price before discount.
					//final_total_price = original price applies with discount rate.
					$tem = $total_price+$total_tu_price_rsnsv_surcharge - $fields["field_".$key."_ded_max_discount_value"]->raw;
					$final_total_price = $final_total_price > $tem ? $final_total_price : $tem;					
				}
				if(!empty($price_rsnsv))
				{
					//If the price isn't null, total price won't be null either.
					$tem = $total_price_rsnsv - $fields["field_".$key."_ded_max_discount_value"]->raw;
					$final_total_price_rsnsv = $final_total_price_rsnsv > $tem ? $final_total_price_rsnsv : $tem;
				}
			}
	?>
		<tr>
		<?php
			if($rowspan_flag)
			{
				$rowspan_flag = false;
		?>
			<td rowspan="<?php print $rowspan;?>" class="insurance_company">
				<div align="center" >
					<?php print $fields["title"]->content;?><br>
					<?php 
						//Building quoting query.
						$query = "";
						foreach($_GET as $q => $q_value)
						{
							if($q == "q") continue;
							$query .= $q."=".$q_value."&";
						}
						$query .= "insurance_product=".$nid;
					?>
					<span class="buy_online_button"><a target="_blank" href="<?php print url("node/166",array("query" => $query));?>"><?php print t("Buy Online");?></a></span>
					
				</div>
			</td>
		<?php				
			}
			?>
			<td height="30" class="deduction">
				$<?php print $key;?>
			</td>
			<td class="price_per_day">
				<div class="price_item rsv">
					<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
						<?php print t("With stable pre-existing medical condition coverage:");?>
					</span>
					<span class="value">
						<?php 
							if(!empty($price)){print round($final_price,2);}else{print $none_str;};
						?>
					</span>
				</div>
				<div class="price_item rsnsv">
					<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
						<?php print t("Without stable pre-existing medical condition coverage:");?>
					</span>
					<span class="value">
						<?php 
							if(!empty($price_rsnsv)){print round($final_price_rsnsv,2);}else{print $none_str;};
						?>
					</span>
				</div>
			</td>
			<td class="price_total">
				<div class="price_item rsv">
					<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
						<?php print t("With stable pre-existing medical condition coverage:");?>
					</span>
					<span class="value">
						<?php 
							if(!empty($total_price)){print round($final_total_price,2);}else{print $none_str;};
						?>
					</span>
				</div>
				<div class="price_item rsnsv">
					<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
						<?php print t("Without stable pre-existing medical condition coverage:");?>
					</span>
					<span class="value">
						<?php 
							if(!empty($total_price_rsnsv)){print round($final_total_price_rsnsv,2);}else{print $none_str;};
						?>
					</span>
				</div>
			</td>
		</tr>
		<?php
		}
	?>

    
    
<?php
	}else{
	//When age over 85, print original price. And there is no other deductible.
	
	$rowspan = 1;
	if(!empty($fields["field_over_85_deductible_value"]->raw)){
		
		//This is sepecially for 21 CENTERY, IF more insurance products are using this, we are going to load node instead of using view fields.\
		//In that case, this will be a lot of work.
		if($nid == 78){
			$centry_21 = node_load($nid);
			$ded_500 = $centry_21->field_500_deductible[0]["value"];
			$ded_1000 = $centry_21->field_1000_deductible[0]["value"];
			$ded_5000 = $centry_21->field_5000_deductible[0]["value"];
			$ded_10000 = $centry_21->field_10000_deductible[0]["value"];
			$arr_21 = array(
				'$500' => 1+$ded_500,
				'$1000' => 1+$ded_1000,
				'$5000' => 1+$ded_5000,
				'$10000' => 1+$ded_10000,
			);
			$rowspan_21 = count($arr_21);
			$rowspan_flag = true;
			foreach($arr_21 as $key => $val)
			{
			?>
				<tr>
					
					
				<?php
					if($rowspan_flag)
					{
						$rowspan_flag = false;
				?>
					<td rowspan="<?php print $rowspan_21;?>" class="insurance_company">
						<div align="center" >
							<?php print $fields["title"]->content;?><br>
							<?php 
								//Building quoting query.
								$query = "";
								foreach($_GET as $q => $q_value)
								{
									if($q == "q") continue;
									$query .= $q."=".$q_value."&";
								}
								$query .= "insurance_product=".$nid;
							?>
							<span class="buy_online_button"><a target="_blank" href="<?php print url("node/166",array("query" => $query));?>"><?php print t("Buy Online");?></a></span>
							
						</div>
					</td>
				<?php				
					}
					?>

					<td height="30" class="deduction">
						<?php print $key;?>
					</td>
					<td class="price_per_day">
						<div class="price_item rsv">
							<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
								<?php print t("With stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($price)){print round($price*$val,2);}else{print $none_str;};
								?>
							</span>
						</div>
						<div class="price_item rsnsv">
							<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
								<?php print t("Without stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($price_rsnsv)){print round($price_rsnsv*$val,2);}else{print $none_str;};
								?>
							</span>
						</div>
					</td>
					<td class="price_total">
						<div class="price_item rsv">
							<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
								<?php print t("With stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
							<?php 
								if(!empty($total_price)){print round($total_price*$val,2);}else{print $none_str;};
							?>
							</span>
						</div>
						<div class="price_item rsnsv">
							<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
								<?php print t("Without stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($total_price_rsnsv)){print round($total_price_rsnsv*$val,2);}else{print $none_str;};
								?>
							</span>
						</div>
					</td>
				</tr>
			<?php	
			}
		}else{
			if($nid == 66)
			{
				
				$ticjf = node_load($nid);
				//$ded_500 = $ticjf->field_500_deductible[0]["value"];
				//$ded_3000 = $ticjf->field_3000_deductible[0]["value"];
				$arr_ticjf = array(
//					'$500' => 1+$ded_500,
				);
        $over_85_deductible = !empty($ticjf->field_over_85_deductible[0]["value"]) ? $ticjf->field_over_85_deductible[0]["value"] : 0;
        foreach($ded_arr as $key => $val){
          
          if($key == $over_85_deductible){
            $arr_ticjf["$".$key] = 1;
           }
          
          if($key > $over_85_deductible){
            if(!empty($val)){
              $arr_ticjf["$".$key] = 1+$val;
            }
          }
        }
        
				$rowspan_21 = count($arr_ticjf);
				$rowspan_flag = true;
				foreach($arr_ticjf as $key => $val)
				{
          
				?>
				<tr>
					
					
				<?php
					if($rowspan_flag)
					{
						$rowspan_flag = false;
				?>
					<td rowspan="<?php print $rowspan_21;?>" class="insurance_company">
						<div align="center" >
							<?php print $fields["title"]->content;?><br>
							<?php 
								//Building quoting query.
								$query = "";
								foreach($_GET as $q => $q_value)
								{
									if($q == "q") continue;
									$query .= $q."=".$q_value."&";
								}
								$query .= "insurance_product=".$nid;
							?>
							<span class="buy_online_button"><a target="_blank" href="<?php print url("node/166",array("query" => $query));?>"><?php print t("Buy Online");?></a></span>
							
						</div>
					</td>
				<?php				
					}
					?>

					<td height="30" class="deduction">
						<?php print $key;?>
					</td>
					<td class="price_per_day">
						<div class="price_item rsv">
							<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
								<?php print t("With stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($price)){print round($price*$val,2);}else{print $none_str;};
								?>
							</span>
						</div>
						<div class="price_item rsnsv">
							<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
								<?php print t("Without stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($price_rsnsv)){print round($price_rsnsv*$val,2);}else{print $none_str;};
								?>
							</span>
						</div>
					</td>
					<td class="price_total">
						<div class="price_item rsv">
							<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
								<?php print t("With stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
							<?php 
								if(!empty($total_price)){print round($total_price*$val,2);}else{print $none_str;};
							?>
							</span>
						</div>
						<div class="price_item rsnsv">
							<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
								<?php print t("Without stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($total_price_rsnsv)){print round($total_price_rsnsv*$val,2);}else{print $none_str;};
								?>
							</span>
						</div>
					</td>
				</tr>
				<?php	
				}
			}else{
			//Normal insurance
			?>
				<tr>
					<td rowspan="<?php print $rowspan;?>" class="insurance_company">
						<div align="center" >
							<?php print $fields["title"]->content;?><br>
							<?php 
								//Building quoting query.
								$query = "";
								foreach($_GET as $q => $q_value)
								{
									if($q == "q") continue;
									$query .= $q."=".$q_value."&";
								}
								$query .= "insurance_product=".$nid;
							?>
							<span class="buy_online_button"><a target="_blank" href="<?php print url("node/166",array("query" => $query));?>"><?php print t("Buy Online");?></a></span>
						</div>
					</td>
					<td height="30" class="deduction">
						<?php print $fields["field_over_85_deductible_value"]->content;?>
					</td>
					<td class="price_per_day">
						<div class="price_item rsv">
							<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
								<?php print t("With stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($price)){print round($price,2);}else{print $none_str;};
								?>
							</span>
						</div>
						<div class="price_item rsnsv">
							<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
								<?php print t("Without stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($price_rsnsv)){print round($price_rsnsv,2);}else{print $none_str;};
								?>
							</span>
						</div>
					</td>
					<td class="price_total">
						<div class="price_item rsv">
							<span class="label" title="<?php print t("With stable pre-existing medical condition coverage:");?>">
								<?php print t("With stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
							<?php 
								if(!empty($total_price)){print round($total_price,2);}else{print $none_str;};
							?>
							</span>
						</div>
						<div class="price_item rsnsv">
							<span class="label" title="<?php print t("Without stable pre-existing medical condition coverage:");?>">
								<?php print t("Without stable pre-existing medical condition coverage:");?>
							</span>
							<span class="value">
								<?php 
									if(!empty($total_price_rsnsv)){print round($total_price_rsnsv,2);}else{print $none_str;};
								?>
							</span>
						</div>
					</td>
				</tr>
			<?php
			}
			 
		}
	}
}?>
