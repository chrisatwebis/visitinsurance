<?php

// This information has been pulled out to make the template more readible.
//
// This data goes between the <head></head> tags of the template
$theme_path = path_to_theme();
?>

<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
<link href="<?php echo base_path() . path_to_theme(); ?>/css/template.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_path() . path_to_theme(); ?>/css/modules.css" type="text/css" />

<link href="<?php echo base_path() . path_to_theme(); ?>/css/<?php echo "header-" . $ss_header_style; ?>.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_path() . path_to_theme(); ?>/css/<?php echo "body-" . $ss_body_style; ?>.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_path() . path_to_theme(); ?>/css/<?php echo "footer-" . $ss_footer_style; ?>.css" rel="stylesheet" type="text/css" />


<?php if($ss_menu_type == "moomenu" or $ss_menu_type == "suckerfish") : ?>
    <link href="<?php echo base_path() . path_to_theme(); ?>/css/rokmoomenu.css" rel="stylesheet" type="text/css" />
<?php endif; ?>



<style type="text/css">
	div.wrapper { <?php echo $current_template_width; ?>px; padding:0;}
	#inset-block-left { width:<?php echo $current_left_inset_width; ?>px;padding:0;}
	#inset-block-right { width:<?php echo $current_right_inset_width; ?>px;padding:0;}
	#maincontent-block { margin-right:<?php echo $current_right_inset_width; ?>px;margin-left:<?php echo $current_left_inset_width; ?>px;}
	a, .contentheading, .side-mod h3 span, .grey .side-mod a, .componentheading span, .roktabs-links li.active {color: <?php echo theme_get_setting(primary_color); ?>;}
</style>


<script type="text/javascript">
window.templatePath = '<?php echo base_path() . path_to_theme() ?>';
</script>


<!-- If JS_COMPAT IS OFF AND NOT IN THE DRUPAL ADMIN, USE MOOTOOLS JS SCRIPTS -->
<?php if(theme_get_setting(js_compatibility) == 0 AND arg(0) != "admin" AND arg(1) != "add" AND arg(2) != "edit" AND arg(0) != "user") : ?>
	<?php include $theme_path . "/rt_mootools.php"; ?>
<?php endif; ?>




<?php if($ss_menu_type == "suckerfish" or $ss_menu_type == "splitmenu") : ?>
<script type="text/javascript" src="<?php echo base_path() . path_to_theme(); ?>/js/ie_suckerfish.js"></script>
<?php endif; ?>
