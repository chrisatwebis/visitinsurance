<?php
/**
 * @file droknewsflash.tpl.php
 * Default theme implementation for roknewsflash.
 *
 * Available variables:
 * - $links: Array of primary links available to for the menu.
 *
 * @see template_preprocess_droknewsflash()
 * @see theme_droknewsflash()
 */
?>
<?php
	$prefix = $GLOBALS['db_prefix'];
	$limitnum = variable_get("droknewsflash_rotatorCount", 5);
	$delay = variable_get("droknewsflash_delay", 7000);
	$duration = variable_get("droknewsflash_duration", 600);
	$controls = variable_get("droknewsflash_controls", 'true');
	$preview_length = variable_get("droknewsflash_preview_length", 50);
	$flashCat = variable_get("droknewsflash_category",'');
	$showContent = variable_get("droknewsflash_content", '');
	$textLabel = variable_get("droknewsflash_label", '');
		
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
?>

<?php 
	if(variable_get('clean_url', 0)) {
		$preURL = "";
	}
	else {
		$preURL = "?q=";
	}
?>			

<script type="text/javascript" src="sites/all/modules/droknewsflash/roknewsflash.js"></script>

<div id="newsflash-bar" class="png">

	<script type="text/javascript">
		window.addEvent('domready', function() {
			var x = new RokNewsFlash('newsflash', {
					controls: <?php echo $controls; ?>,
					delay: <?php echo $delay; ?>,
					duration: <?php echo $duration; ?>
				});
			});
	</script>

	<div id="newsflash" class="roknewsflash png">
		<span class="flashing png"><?php echo $textLabel; ?></span>
			<ul>

			
			<?php while ($anode = db_fetch_object($result)) : ?>
				
				<?php
				  	$final_text = "";
				  	if($showContent == "body") {
				  		$final_text = "";
					  	$final_text = $anode->body;
					  	
					  	$final_text = preg_replace("/<?php[^>]+\?>/i", "", $final_text);
					  	$final_text = str_replace("<?", "", $final_text);
					  	$final_text = strip_tags($final_text);
				  		
				  		//$final_text = strip_tags($anode->body);
				  	}
				  	else {
				  		$final_text = strip_tags($anode->title);
				  	}
				  	
					if(strlen($final_text) > $preview_length) {
				  		$final_text = substr($final_text, 0, strpos($final_text, ' ', $preview_length)) . "..." ;
				  	}
			  	
			  	?>
			
				<li><a href="<?php echo $preURL; ?>node/<?php echo $anode->nid; ?>"><?php echo $final_text; ?></a></li>
				
			
			<?php endwhile; ?>
			
	
			</ul>
	</div>
</div>
