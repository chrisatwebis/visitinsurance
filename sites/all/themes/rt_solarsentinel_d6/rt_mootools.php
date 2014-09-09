
<script type="text/javascript" src="<?php echo base_path() . path_to_theme(); ?>/js/mootools.js"></script>

<?php if(theme_get_setting(m_type) ) :?>

	<script type="text/javascript" src="<?php echo base_path() . path_to_theme(); ?>/js/rokmoomenu.js"></script>
	<script type="text/javascript" src="<?php echo base_path() . path_to_theme(); ?>/js/mootools.bgiframe.js"></script>

<?php endif; ?>


<?php if($enable_ie6warn=="true") : ?>
	<script type="text/javascript" src="<?php echo base_path() . path_to_theme(); ?>/js/rokie6warn.js"></script>
<?php endif; ?>


<script type="text/javascript" src="<?php echo base_path() . path_to_theme(); ?>/js/rokfonts-1.9.js"></script>

<script type="text/javascript">
	window.addEvent('domready', function() {
		var modules = ['side-mod','module','moduletable','component-header']
		var header = ["h3", "h1"];
		RokBuildSpans(modules, header);
	});
</script>

