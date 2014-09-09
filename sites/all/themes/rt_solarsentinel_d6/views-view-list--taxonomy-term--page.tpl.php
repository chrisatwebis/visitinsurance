<?php
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<script>
	
	$(document).ready(function(){
		$(".views-field-title a").each(
			function(index)
			{	
				$(this).addClass("arrowdown");
				$(this).parents(".views-row").find(".views-field-body").css("display","none");
				$(this).click(
					function(e){
						e.preventDefault();
						if($(this).hasClass("arrowdown")){
							
							$(this).parents(".view-content").find(".views-field-title a").removeClass("arrowup").addClass("arrowdown");
							
							
							$(this).parents(".view-content").find(".views-field-body").slideUp();
							
							$(this).removeClass("arrowdown");
							$(this).addClass("arrowup");
							$(this).parents(".views-row").find(".views-field-body").slideDown();
						}else{
							$(this).removeClass("arrowup");
							
							$(this).addClass("arrowdown");
							$(this).parents(".view-content").find(".views-field-body").slideUp();
						}
						
					}
				);
			}
		);
	});

</script>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php foreach ($rows as $id => $row): ?>
  <div class="<?php print $classes[$id]; ?>">
    <?php print $row; ?>
  </div>
<?php endforeach; ?>