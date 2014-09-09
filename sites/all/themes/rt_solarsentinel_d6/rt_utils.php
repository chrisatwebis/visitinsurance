<?php


// set left column width
if((theme_get_setting(splitmenu_col) == "leftcol") or $left) {
	$current_leftcolumn_width = theme_get_setting('leftcolumn_width');
}
else {
	$current_leftcolumn_width = 0;
}
// set right column width
if((theme_get_setting(splitmenu_col) == "rightcol") or $right) {
	$current_rightcolumn_width = theme_get_setting('rightcolumn_width');
}
else {
	$current_rightcolumn_width = 0;
}
// set right insetwidth
if($inset2 AND arg(0) != "admin") {
	$current_right_inset_width = theme_get_setting('rightinset_width');
}
else {
	$current_right_inset_width = 0;
}
// set left insetwidth
if($inset AND arg(0) != "admin") {
	$current_left_inset_width = theme_get_setting('leftinset_width');
}
else {
	$current_left_inset_width = 0;
}
// set template width
$current_template_width = 'margin: 0 auto; width: ' . theme_get_setting('template_width');
$leftbanner_width=0;
$rightbanner_width=0;
$moduleslider_height=0;

$col_mode = "s-c-s";
if ($current_leftcolumn_width==0 and $current_rightcolumn_width>0) $col_mode = "x-c-s";
if ($current_leftcolumn_width>0 and $current_rightcolumn_width==0) $col_mode = "s-c-x";
if ($current_leftcolumn_width==0 and $current_rightcolumn_width==0) $col_mode = "x-c-x";

?>