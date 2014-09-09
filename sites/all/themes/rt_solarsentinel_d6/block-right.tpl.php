
<?php if($class != ""): ?>
	<div class="<?php echo $class; ?>">
<?php else: ?>
	<div class="">
<?php endif; ?>
	
	<div class="side-mod">
		
		<?php if ($block->subject) : ?>
			<div class="module-header"><div class="module-header2"><h3 class="module-title"><?php print $block->subject; ?></h3></div></div>
		<?php endif; ?>
		
		<div class="module">
			<?php print $block->content; ?>
		</div>

	</div>

</div>
