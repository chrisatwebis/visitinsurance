<?php
/**
 * @file drokheadlines.tpl.php
 * Default theme implementation for drokheadlines.
 *
 * Available variables:
 * - $links: Array of primary links available to for the menu.
 *
 * @see template_preprocess_drokheadlines()
 * @see theme_drokheadlines()
 */
?>
<?php
	$prefix = $GLOBALS['db_prefix'];
	$limitnum = variable_get("headlines_rotatorCount", 5);
	$preview_length = variable_get("headlines_preview_length", 50);
	$flashCat = variable_get("headlines_category",'');
	$img_path = variable_get("headlines_img_path", '');

	$sql = "SELECT 
				".$prefix."node_revisions.*, ".$prefix."term_node.* 
			FROM 
				".$prefix."node_revisions, ".$prefix."term_node 
			WHERE  
				".$prefix."node_revisions.nid = ".$prefix."term_node.nid AND ".$prefix."term_node.tid = $flashCat 
			ORDER BY 
				".$prefix."node_revisions.timestamp DESC 
			LIMIT $limitnum";
	
	$result = db_query($sql);
	global $theme_key;			
?>

<?php 
	if(variable_get('clean_url', 0)) {
		$preURL = "";
	}
	else {
		$preURL = "?q=";
	}
?>
			
<div class="roknewspager-wrapper">
	<ul class="roknewspager">
			
		<?php while ($anode = db_fetch_object($result)) : ?>
			<?php
			  	$final_text = "";
			  	$final_text = strip_tags($anode->body);
	
				if(strlen($final_text) > $preview_length) {
			  		$final_text = substr($final_text, 0, strpos($final_text, ' ', $preview_length)) . "..." ;
			  	}
			?>

			<li>
				<div class="roknewspager-div">
					<a href="<?php echo $preURL; ?>node/<?php echo $anode->nid; ?>" title="<?php echo $anode->title; ?>" class="roknewspager-title"><?php echo $anode->title; ?></a><?php echo $final_text; ?>
				</div>
			</li>
					
			
		<?php endwhile; ?>
			
		
	</ul>
</div>
