<?php
/**
 * @file optional special text effect
 * 
 * Note that the style attribute:
 * text-effect:embedded-underline;
 * uses -, but the filename convention and the function name use _
 * 
 */

/**
 * The 'embededed_underline' action takes the first and last letter 
 * of a word or phrase, shrinks the text in between and aligns it 
 * to the top.
 * 
 * @TODO - Kerning sucks. I don't think it will be possible to automate without
 * a LOT of magic.
 *
 *
 * This func takes the same arguments as imageapi_image_create_text()
 * ... and effectively does the same thing. In its own way.
 * 
 * @return an image api object containing the actual drawn text.
 */
function imageapi_text_embedded_underline(&$image, $text, $fontfile, $style) {
  $first_text = substr($text, 0, 1);
  $last_text = substr($text, strlen($text)-1, 1);
  $middle_text = substr($text, 1, strlen($text)-2);
  // What we do is create three lumps of text, 
  // align them together into one image,
  // then position that new image on the source
  unset($style['text-effect']); // avoid looping! this func calls its parent to do most of the normal stuff

  // Rotation and padding needs to be handled ourselves. 
  $local_style = array();
  $dont_pass_styles = array('rotate', 'padding-left', 'padding-right', 'padding-top', 'padding-bottom', 'background-color');
  foreach($dont_pass_styles as $restricted_style) {
    $local_style = @$style[$restricted_style];
    unset($style[$restricted_style]);
  }

  // Create three placeholder gd image resources
  $textimage = (object) array('toolkit' => $image->toolkit, );
  $first_image = clone $textimage;
  $last_image = clone $textimage;
  $middle_image = clone $textimage;
  
  imageapi_image_create_text($first_image, $first_text, $fontfile, $style);
  imageapi_image_create_text($last_image, $last_text, $fontfile, $style);
  $modified_style = $style;
  $modified_style['font-size'] = $modified_style['font-size'] * 0.75;
  imageapi_image_create_text($middle_image, $middle_text, $fontfile, $modified_style);

  
  // Now glue these three images into one
  $new_width = $first_image->info['width'] + $middle_image->info['width'] + $last_image->info['width'];
  $new_height = max($first_image->info['height'], $middle_image->info['height'], $last_image->info['height']);

  // For better kerning, MAY need to remove a few pixels between the mis-sized letters. 
# debug kerning
#$pink = imagecolorallocate($last_image->resource, 255, 255, 180);
#imagerectangle($last_image->resource, 0, 0, $last_image->info['width']-1, $last_image->info['height']-1, $pink);
#$pink = imagecolorallocate($middle_image->resource, 255, 255, 180);
#imagerectangle($middle_image->resource, 0, 0, $middle_image->info['width']-1, $middle_image->info['height']-1, $pink);
  $kerning = $style['font-size'] * 0.1;
  // .. No, this is a per-font problem. Most are OK, but Times may look bad unless done by hand!
  $kerning = 0; // what to do?
  
  $composite = new_transparent_gd_image($new_width, $new_height);
  imagecopy($composite, $first_image->resource, 0, 0, 0, 0, $first_image->info['width'], $first_image->info['height']);
  imagecopy($composite, $middle_image->resource, $first_image->info['width'] - $kerning, 0, 0, 0, $middle_image->info['width'], $middle_image->info['height']);
  imagecopy($composite, $last_image->resource, $first_image->info['width'] + $middle_image->info['width'] - ($kerning * 2), 0, 0, 0, $last_image->info['width'], $last_image->info['height']);

  // Now we have the munted text.

  // Put in an underline!
  $RGB = imagecache_actions_hex2rgba($style['fill']);
  $RGB['alpha'] = 127 - (127 * ( isset($style['text-shadow-opacity']) ? $style['text-shadow-opacity'] : $style['opacity']));
  $color = imagecolorallocatealpha($composite, $RGB['red'], $RGB['green'], $RGB['blue'], $RGB['alpha']);
  $rect = array(
    'x' => $first_image->info['width'] + ($style['font-size'] * .15),
    'x2' => $first_image->info['width'] + $middle_image->info['width'] - ($style['font-size'] * .15),
    'y' => $style['font-size'] * 0.85,
    'y2' => $style['font-size'] * 0.90,
  );
  imagefilledrectangle($composite, $rect['x'], $rect['y'], $rect['x2'], $rect['y2'], $color);
    
  // About now we are able to apply padding and background
  // TODO
  # $local_style

  // Finally overlay it on the base, with positioning.
  // Put it into an imageapi-type object
  $image->resource = $composite;
  $image->info['width'] = $new_width;
  $image->info['height'] = $new_height;

  // Defining this just stops the other image funcs getting confused.
  $image->info['mime_type'] = 'image/png';

  return $image;
}
