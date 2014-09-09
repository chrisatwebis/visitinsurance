
<?php if($class != ""): ?>
	<div class="<?php echo $class; ?>">
<?php else: ?>
	<div class="">
<?php endif; ?>
	
	<div class="side-mod">
		
		<?php if ($block->subject) : ?>
			<div class="component-header"><h1 class="componentheading"><?php print $block->subject; ?></h1></div>

		<?php endif; ?>
		
		<div class="module">
			<?php print $block->content; ?>
		</div>

	</div>

</div>
