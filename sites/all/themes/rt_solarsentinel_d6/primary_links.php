
<?php if (is_array($primary_links)) : ?>
  <ul class="menutop">
    <?php foreach ($primary_links as $link => $data): ?>
      <li class="png">
      	<?php 
      		$href = $data['href'] == "<front>" ? base_path() : base_path() . "?q=" . drupal_get_path_alias($data['href']);
      		print "<a class='topdaddy link' href='" . $href . "'><span>" . $data['title'] . "</span></a>";       
      	?>
       
       <?php if ($data['has_children']) : ?>
       
       	<div class="drop-wrap columns-2"><div class="drop1 png"></div>
            <ul class="png columns-2">
            
					
            		<li class="png">
                	<a class="link" href=""><span>asdfasdfds</span></a>
                    </li>
                
               
            <?php foreach ($secondary_links as $link): ?>
            	
					
            		<li class="item png">
                	<?php          
                    	$href = $link['href'] == "<front>" ? base_path() : base_path() . "?q=" . drupal_get_path_alias($link['href']);
                   		print "<a class='link' href='" . $href . "'><span>" . $link['title'] . "</span></a>";              
                    ?>
                    </li>
                
              
            <?php endforeach; ?>
            
            </ul>
        
         </div>
         
   		<?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>