
<div class="block first">

<?php if($class != ""): ?>
	<div class="<?php echo $class; ?>">
<?php else: ?>
	<div class="">
<?php endif; ?>
	
	<div class="moduletable">
		
		<?php if ($block->subject) : ?>
			<h3 class="main-modules"><span><?php print $block->subject; ?></h3>
		<?php endif; ?>
		
		<?php print $block->content; ?>

	</div>

</div>

</div>
