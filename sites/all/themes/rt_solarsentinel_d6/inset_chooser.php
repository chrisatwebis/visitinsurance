
<script type="text/javascript">
	window.addEvent('domready', function() {
		var select = document.id('variation_chooser'), preview = document.id('variation_preview'), next = document.id('variation_chooser_next'), prev = document.id('variation_chooser_prev');
		if (select && preview && prev && next) {
			select.addEvent('change', function(e) {
				new Event(e).stop();
				selectImage(select.selectedIndex);
			});
			prev.addEvent('click', function() {
				var index = select.selectedIndex;
				if (index - 1 < 0) index = select.options.length - 1;
				else index -= 1;
				select.selectedIndex = index;
				selectImage(index);
			});
			next.addEvent('click', function() {
				var index = select.selectedIndex;
				if (index + 1 >= select.options.length) index = 0;
				else index += 1;
				select.selectedIndex = index;
				selectImage(index);
			});
			
			var asset;
			var selectImage = function(index) {
				preview.setStyle('background', 'url(<?php echo path_to_theme(); ?>/images/loading.gif) center center no-repeat');
				asset = new Asset.image('<?php echo path_to_theme(); ?>/files/stories/styles/ss_' + select.options[index].value + '.jpg', {
					onload: function() { 
						if (index == select.selectedIndex) preview.setStyle('background-image', 'url(' + this.src + ')');
					}
				});
			};
			
			selectImage(select.selectedIndex);
		};
	});


</script>

<?php
	if( isset( $_COOKIE['ss_preset_style'] ) )
		$this_preset_style = $_COOKIE['ss_preset_style']; 
	else
		$this_preset_style = theme_get_setting('preset_style');
?>

<div style="width: 210px;margin: 0 auto;">
	<img src="<?php echo path_to_theme(); ?>/images/blank.png" name="preview" id="variation_preview" border="0" width="210" height="150" alt="Mixxmag" />

	<form action="<?php echo base_path(); ?>" name="chooserform" method="get" class="variation-chooser">

	<div class="controls">
		
		<img class="control-prev" id="variation_chooser_prev" title="Previous" alt="prev" src="<?php echo path_to_theme(); ?>/images/blank.png" style="background-image: url('<?php echo path_to_theme(); ?>/images/showcase-controls.png');" />
		<select name="tstyle" id="variation_chooser" class="button" style="float: left;">
			<option value="style1"<?php if($this_preset_style == "style1"): ?> selected="selected"<?php endif; ?>>Style 1</option>
			<option value="style2"<?php if($this_preset_style == "style2"): ?> selected="selected"<?php endif; ?>>Style 2</option>
			<option value="style3"<?php if($this_preset_style == "style3"): ?> selected="selected"<?php endif; ?>>Style 3</option>
			<option value="style4"<?php if($this_preset_style == "style4"): ?> selected="selected"<?php endif; ?>>Style 4</option>
			<option value="style5"<?php if($this_preset_style == "style5"): ?> selected="selected"<?php endif; ?>>Style 5</option>
			<option value="style6"<?php if($this_preset_style == "style6"): ?> selected="selected"<?php endif; ?>>Style 6</option>
			<option value="style7"<?php if($this_preset_style == "style7"): ?> selected="selected"<?php endif; ?>>Style 7</option>
			<option value="style8"<?php if($this_preset_style == "style8"): ?> selected="selected"<?php endif; ?>>Style 8</option>
			<option value="style9"<?php if($this_preset_style == "style9"): ?> selected="selected"<?php endif; ?>>Style 9</option>
			<option value="style10"<?php if($this_preset_style == "style10"): ?> selected="selected"<?php endif; ?>>Style 10</option>
		</select>
		<img class="control-next" id="variation_chooser_next" title="Next" alt="next" src="<?php echo path_to_theme(); ?>/images/blank.png" style="background-image: url('<?php echo path_to_theme(); ?>/images/showcase-controls.png');"/>
	</div>
	<input class="button" type="submit" value="Select" />
	</form>
</div>
	