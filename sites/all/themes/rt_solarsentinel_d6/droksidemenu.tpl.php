<?php
/**
 * @file roksidemenu.tpl.php
 * Default theme implementation for roksidemenu.
 *
 * Available variables:
 * - $links: Array of primary links available to for the menu.
 *
 * @see template_preprocess_roksidemenu()
 * @see theme_roksidemenu()
 */
?>

<?php 
	if(variable_get('clean_url', 0)) {
		$preURL = "";
	}
	else {
		$preURL = "?q=";
	}
?>

<?php
	$title = variable_get("roksidemenu_title", 'Main Menu');
?>

<ul class="menu">


	<?php foreach ($links as $index => $link) : ?>		
		
		<?php		
			
			$href = $link['link']['href'] == "<front>" ? base_path() : base_path() . $preURL . drupal_get_path_alias($link['link']['href']);
			
			if($link['below']) {
				$parent = "parent ";
			}
			else {
				$parent = "";
			}
		?>
			
			<?php if ($link['link']['in_active_trail']) : ?>
				<li id="current" class="<?php echo $parent; ?>active item">
				
			<?php else : ?>
				<li class="<?php echo $parent; ?>item">
				
			<?php endif; ?>
		
	
				
		<a href="<?php echo $href; ?>" class="topdaddy"><span><?php echo $link['link']['title']; ?></span></a>
		
		<!--Secondary Nav  for Active Trail-->
				
		<?php if ($link['link']['in_active_trail'] AND $link['below']) : ?>
							
			<ul>
										
				<?php foreach ($link['below'] as $index2 => $link) : ?>
			 		  	
					  <?php if ($link['link']['in_active_trail']) : ?>
							<li id="current" class="active item">
					  <?php else: ?>
					  		<li class="item">	
					  <?php endif; ?>
					  
					  <?php
					  	$href = $link['link']['href'] == "<front>" ? base_path() : base_path() . $preURL . drupal_get_path_alias($link['link']['href']);
						
					  ?>		
					  
						<a href="<?php echo $href; ?>" class="item"><span><?php echo $link['link']['title']; ?></span></a>
				  		
				  	</li>
				
			   <?php endforeach; ?>
		    
		    </ul>
				
		    	
		<?php endif; ?>
				
		</li>
			
			
	<?php endforeach; ?> <!--end loop-->
			
			
</ul>	
			