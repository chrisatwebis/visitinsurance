<?php
/**
 * @file Utility form, conversion and rendering functions for image processes
 */

/**
 * Given two imageapi objects with dimensions, and some positioning values,
 * calculate a new x,y for the layer to be placed at.
 * 
 * This is a different approach to imagecache_actions_keyword_filter() - more
 * like css.
 * 
 * The $style is an array, and expected to have 'top,bottom, left,right'
 * attributes set. These values may be positive, negative, or in %.
 * 
 * % is calculated relative to the base image dimensions.
 * Using % requires that the layer is positioned CENTERED on that location, so
 * some offsets are added to it. 'right-25%' is not lined up with a margin 25%
 * in, it's centered at a point 25% in - which is therefore identical with
 * left+75%
 * 
 * @return a keyed array of absolute x,y co-ordinates to place the layer at.
 */
function imagecache_actions_calculate_relative_position($base, $layer, $style) {
  // both images should now have size info available.

  if (isset($style['bottom'])) {
    $ypos = imagecache_actions_calculate_offset('bottom', $style['bottom'], $base->info['height'], $layer->info['height']);
  }
  if (isset($style['top'])) {
    $ypos = imagecache_actions_calculate_offset('top', $style['top'], $base->info['height'], $layer->info['height']);
  }
  if (isset($style['right'])) {
    $xpos = imagecache_actions_calculate_offset('right', $style['right'], $base->info['width'], $layer->info['width']);
  }
  if (isset($style['left'])) {
    $xpos = imagecache_actions_calculate_offset('left', $style['left'], $base->info['width'], $layer->info['width']);
  }
  if (! isset($ypos)) {
    // assume center
    $ypos = ($base->info['height']/2) - ($layer->info['height']/2);
  }
  if (! isset($xpos)) {
    // assume center
    $xpos = ($base->info['width']/2) - ($layer->info['width']/2);
  }
  return array('x' => $xpos, 'y' => $ypos);
}


/**
 * Positive numbers are IN from the edge, negative offsets are OUT.
 * 
 * $keyword, $value, $base_size, $layer_size
 * eg 
 * left,20 200, 100 = 20
 * right,20 200, 100 = 80 (object 100 wide placed 20 px from the right = x=80)
 * 
 * top,50%, 200, 100 = 50 (Object is centered when using %)
 * top,20%, 200, 100 = -10 
 * bottom,-20, 200, 100 = 220
 * right, -25%, 200, 100 = 200 (this ends up just offscreen)
 * 
 * 
 * Also, the value can be a string, eg "bottom-100", or "center+25%"
 */
function imagecache_actions_calculate_offset($keyword, $value, $base_size, $layer_size) {
  $offset = 0; // used to account for dimensions of the placed object
  $direction = 1;
  $base = 0;
  if ($keyword == 'right' || $keyword == 'bottom') {
    $direction = -1;
    $offset = -1 * $layer_size;
    $base = $base_size;
  }
  if ($keyword == 'middle' || $keyword == 'center') {
    $base = $base_size/2;
    $offset = -1 * ($layer_size / 2);
  }
  
  // Keywords may be used to stand in for numeric values
  switch ($value) {
    case 'left' :
    case 'top' :
      $value = 0;
    break;
    case 'middle' :
    case 'center' :
      $value = $base_size/2;
    break;
    case 'bottom' :
    case 'right' :
      $value = $base_size;
  }
  
  // Handle keyword-number cases
  // top+50% or bottom-100px
  if (preg_match('/^(.+)([\+\-])(\d+)([^\d]*)$/', $value, $results)) {
    list($match, $value_key, $value_mod, $mod_value, $mod_unit) = $results;
    if ($mod_unit == '%') {
      $mod_value = $mod_value / 100 * $base_size;
      $mod_unit = 'px';
    }
    $mod_direction = ($value_mod == '-') ? -1 : +1;
    switch ($value_key) {
      case 'left' :
      case 'top' :
        $mod_base = 0;
      break;
      case 'middle' :
      case 'center' :
        $mod_base = $base_size/2;
      break;
      case 'bottom' :
      case 'right' :
        $mod_base = $base_size;
    }
    $modified_value = $mod_base + ($mod_direction * $mod_value);    
    return $modified_value;    
  }
  #dpm(get_defined_vars());

  // handle % values
  if (substr($value, strlen($value)-1, 1) == '%') {
    $value = intval($value / 100 * $base_size);
    $offset = -1 * ($layer_size / 2);
  }
  $value = $base + ($direction * $value);

  #dpm(__FUNCTION__ . " Placing an object $layer_size big on a range of $base_size at a position of $value , $offset");  
  #dpm(get_defined_vars());

  // Add any extra offset to position the item
  return $value + $offset;
}


/**
 * Decode an HTML hex-code into an array of R, G, and B values.
 * accepts these formats: (case insensitive) #ffffff, ffffff, #fff, fff
 *
 * The imageapi version doesn't key the results
 * 
 * I think it's better if we do.
 * 
 * @ingroup utility
 */
function imagecache_actions_hex2rgba($hex) {
  $unkeyed = imageapi_hex2rgba($hex);
  $keyed = array_combine(array('red', 'green', 'blue', 'alpha'), $unkeyed);
  // bad alpha produces solid black.
  if ($keyed['alpha'] > 127) {
    watchdog('imageapi_text', 'Bad alpha value in hex color %hex. Alpha channel cannot be greater than #7F (127)', array('%hex' => $hex), WATCHDOG_NOTICE);
  }
  return $keyed;
}

/**
 * Accept a keyword (center, top, left, etc) and return it as an offset in pixels.
 * Called on either the x or y values.
 * 
 * May  be something like "20", "center", "left+20", "bottom+10". + values are
 * in from the sides, so bottom+10 is 10 UP from the bottom. 
 * 
 * "center+50" is also OK.
 * 
 * "30%" will place the CENTER of the object at 30% across. to get a 30% margin,
 * use "left+30%"
 * 
 * @param $value 
 *   string or int value. 
 * @param $current_size
 *   int size in pixels of the range this item is to be placed in
 * @param $object_size
 *   int size in pixels of the object to be placed
 * 
 * 
 */
function imagecache_actions_keyword_filter($value, $base_size, $layer_size) {
  // See above for the patterns this matches
  if (! preg_match('/([a-z]*)([\+\-]?)(\d*)([^\d]*)/', $value, $results) ) {
    trigger_error("imagecache_actions had difficulty parsing the string '$value' when calculating position. Please check the syntax.", E_USER_WARNING);
  }
  list($match, $keyword, $plusminus, $value, $unit) = $results;

  #dpm(__FUNCTION__ . " Placing an object $layer_size big on a range of $base_size at a position of $value");  
  #dpm(get_defined_vars());

  return imagecache_actions_calculate_offset($keyword, $plusminus.$value.$unit, $base_size, $layer_size);
}

/**
 * imagecache is conservative with its inclusion of inc files, but sometimes I
 * need to use them - eg crop. This function finds and includes it if needed.
 */
function imagecache_include_standard_actions() {
  $cropaction = imagecache_action_definition('imagecache_crop');
  include_once($cropaction['file']);
}

