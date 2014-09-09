<?php
/**
 * @file drokintroscroller.tpl.php
 * Default theme implementation for drokstories.
 *
 */
?>

<?php
	$prefix = $GLOBALS['db_prefix'];
	$limitnum = variable_get("drokintroscroller_count", 10);
	$storyCat = variable_get("drokintroscroller_category",'');
	$preview_length = variable_get("drokintroscroller_preview", 100);
	$show_title = variable_get("drokintroscroller_showtitle", 0);
	$link_title = variable_get("drokintroscroller_linktitle", 0);
	$show_readmore = variable_get("drokintroscroller_showreadmore", 0);
	$readmore_text = variable_get("drokintroscroller_readmoretext", 'Read More');

	$sql = "SELECT 
			".$prefix."node_revisions.*, ".$prefix."term_node.* 
		FROM 
			".$prefix."node_revisions, ".$prefix."term_node 
		WHERE  
			".$prefix."node_revisions.nid = ".$prefix."term_node.nid AND ".$prefix."term_node.tid = $storyCat 
		ORDER BY 
			".$prefix."node_revisions.timestamp DESC 
		LIMIT 
			$limitnum";
	
	global $theme_key;
	
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

<script type="text/javascript" src="./sites/all/modules/drokintroscroller/rokintroscroller-mt1.2.js"></script>

<script type="text/javascript">
	window.addEvent('domready', function() {
		var rnu = new RokIntroScroller('rokintroscroller', {
			'arrows': {
				'effect': true,
				'opacity': 0.85
			},
			'scroll': {
				'duration': 800,
				'itemsPerClick': 7,
				'transition': Fx.Transitions.Quad.easeOut
			}
		});
	});
	
	

</script>
			
<div class="scroller-bottom">
	<div class="scroller-bottom1">
		<div class="scroller-bottom2">
			<div class="scroller-top">
				<div class="scroller-top1">
					<div class="scroller-top2">
						<!-- Content START -->
							<div id="rokintroscroller" class="">
								
						        <?php while ($anode = db_fetch_object($result)) : ?>
							        <?php
								  		$final_text = "";
									  	$final_text = $anode->body;
									  	
									  	$final_text = preg_replace("/<?php[^>]+\?>/i", "", $final_text);
									  	$final_text = str_replace("<?", "", $final_text);
									  	$final_text = strip_tags($final_text);
									  	
								  		if(strlen($final_text) > $preview_length) {
									  		$final_text = substr($final_text, 0, strpos($final_text, ' ', $preview_length)) . "..." ;
									  	}
							  		
							  		?>
							        
							        <div class="scroller-item">
										<?php if ($show_title == 1) :?>
											<?php if ($link_title == 1) :?>
											<h2><a href="<?php echo $preURL; ?>node/<?php echo $anode->nid; ?>"><?php echo $anode->title; ?></a></h2>
											<?php else: ?>
											<h2><?php echo $anode->title; ?></h2>
											<?php endif; ?>
										<?php endif; ?>
								
							            <?php echo $final_text; ?>
							
							            <?php if ($show_readmore == 1) :?>
							            <a href="<?php echo $preURL; ?>node/<?php echo $anode->nid; ?>" class="readon3"><?php echo $readmore_text; ?></a> 
							            <?php endif; ?>
							       
							        </div>
						    	<?php endwhile; ?>
							<!-- Content END -->		


							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>		
		
		
		
		
		
		
		