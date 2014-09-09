

<?php 
	$rotator_count = 6;
	$rotator_delay = 7000;
	$rotator_duration = 800;
	$rotator_autoplay = true;
	$rotator_show_controls = true;
	$rotator_auto_hide_controls = true;
	$rotator_controls = true;
	$rotator_preview_length = 300;
?>

<?php
    $sql = "SELECT node_revisions.title, node_revisions.body, node_revisions.nid, node_revisions.timestamp, node.promote, node.nid FROM node_revisions INNER JOIN node ON node_revisions.nid = node.nid WHERE node.promote = 1 ORDER BY node_revisions.timestamp DESC LIMIT $rotator_count";
	$result = db_query($sql);
?>
<!--
<script type="text/javascript">
    RokStoriesImage.push('/rt/joomla/solarsentinel/images/stories/rokstories/demo3.jpg');
    RokStoriesImage.push('/rt/joomla/solarsentinel/images/stories/rokstories/demo5.jpg');
    RokStoriesImage.push('/rt/joomla/solarsentinel/images/stories/rokstories/demo4.jpg');
    RokStoriesImage.push('/rt/joomla/solarsentinel/images/stories/rokstories/demo1.jpg');

window.addEvent('domready', function() {
	new RokStories('.feature-block', {
		'startElement': 0,
		'thumbsOpacity': 0.3,
		'mousetype': 'mouseenter',
		'autorun': 1,
		'delay': 15000,
		'startWidth': 410	});
});
</script>

-->
<div class="feature-block">
	<div class="image-container">
		<div class="image-full"><img src="files/stories/rokstories/demo3.jpg"></div>
		<div class="image-small">
		    <img src="files/stories/rokstories/demo3_thumb.jpg" class="feature-sub" alt="image" />
		    <img src="files/stories/rokstories/demo5_thumb.jpg" class="feature-sub" alt="image" />
		    <img src="files/stories/rokstories/demo4_thumb.jpg" class="feature-sub" alt="image" />
			<img src="files/stories/rokstories/demo1_thumb.jpg" class="feature-sub" alt="image" />
		</div>
	</div>
	<div class="description">
		<span class="feature-title">Somali pirate in court</span>
		<span class="feature-desc">
			A Somali teenager captured by the US during the rescue of an American sea captain from pirates has appeared in a federal court in New York. The judge briefly closed the trial to the public until he had clarified whether Abde Wale Abdul Kadhir Muse was a juvenile as his mother claimed.
		</span>
		
		<div class="clr"></div>
		
		<div class="readon-wrap1">
			<div class="readon1-l"></div>
				<a href="" class="readon-main"><span class="readon1-m"><span class="readon1-r">Read the Full Story</span></span></a>
			</div>
			<div class="clr"></div>
		</div>

</div>



<!--
<div id="news-rotator">
	<?php while ($anode = db_fetch_object($result)) : ?>
	  		
	  	<?php
	  		$final_text = "";
	  		$final_text = strip_tags($anode->body);
	  		$final_text = substr($final_text, 0, strpos($final_text, ' ', $rotator_preview_length)) . "..." ;
	  	?>	
	  	
	  	<div class="story-block">
			<div class="divider">
            	<div class="image">
                	 <a href="?q=node/<?php print $anode->nid; ?>" title="<?php print $anode->title; ?>"><img src="files/images/stories/rotator<?php print $anode->nid; ?>.jpg" alt="<?php print $anode->title; ?>" /></a>
                </div>
            	<div class="story">
                	<div class="padding">
                    	 <h1><a href="?q=node/<?php print $anode->nid; ?>"><?php print $anode->title; ?></a></h1>
                     	<p>
							<?php print $final_text; ?>
						</p>
                	</div>
            	</div>
			</div>
        </div>
	  	
	  	
	<?php endwhile; ?> 				   
			   
			       
</div>
-->