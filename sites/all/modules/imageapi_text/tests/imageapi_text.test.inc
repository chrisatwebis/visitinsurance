<?php
// $ID:  $
/**
 * @file
 *   Test page for Visual tests of imageapi_text effects and features
 * 
 * Created on May 23, 2010
 *
 * @author 'dman' Dan Morrison http://coders.co.nz/
 */

/**
 * Test page callback
 */
function imageapi_text_test_page() {
  $tests = imageapi_text_test_styles();
  $sample_text = 'Sample 123 XYZ & %$#@';
  $font_path = drupal_get_path('module', 'imageapi_text') . '/fonts';
  $default_fontfile = $font_path .'/liberation-fonts-1.04/LiberationSans-Bold.ttf';
  $rows = array();
  foreach ($tests as $test_name => $test_style) {
    $text = (isset($test_style->text)) ? $test_style->text : $sample_text;
    $test_style->fontfile = (isset($test_style->fontfile)) ? $test_style->fontfile : $default_fontfile;
    $rows[] = array(
      'header' => TRUE,
      'data' => array(
        array( 
          'data' => $test_name,
          'colspan' => 2,
        ),
      ),
    );
    $rows[] = array(
      'setting' => basename($test_style->fontfile) . '<pre>'. print_r($test_style->style, 1) . '</pre>',
      'sample' => array(
        'data' => theme('imageapi_text', $text, $test_style),
        'class' => 'imageapi-text-sample',
      ),
    );
  }
  drupal_add_css(drupal_get_path('module', 'imageapi_text') .'/imageapi_text.admin.css');
  return theme('table', array(), $rows, array('class' => 'imageapi-text-test'));
  
} 

function imageapi_text_test_styles() {
  $font_path = drupal_get_path('module', 'imageapi_text') . '/fonts';
  $default_fontfile = $font_path .'/liberation-fonts-1.04/LiberationSans-Bold.ttf';
  return array(
    'default' => (object)array(
      'style' => '',
      'fontfile' => '',
    ),
    'basic' => (object)array(
      'style' => '
font-size:22px;
fill:#dd0000;
',
    ),
    'stroke' => (object)array(
      'style' => '
font-size:22px;
fill:#EEEEEE;
stroke:#FF0000;
stroke-width:3px;
',
    ),
    'shadow' => (object)array(
      'style' => '
font-size:22px;
fill:#ff0000;
text-shadow: #999999 2px 2px 0;
',
    ),
    'rotate' => (object)array(
      'style' => '
font-size:22px;
fill:#EEEEEE;
stroke:#FF0000;
stroke-width:3px;
transform: rotate(-30);
',
    ),
    'padding' => (object)array(
      'style' => '
padding-left:20px;
padding-right:20px;
padding-top:20px;
padding-bottom:20px;
',
    ),
    'opacity' => (object)array(
      'style' => '
font-size:22px;
fill:#FF0000;
opacity:0.5;
',
    ),
    'rgba' => (object)array(
      'style' => '
font-size:22px;
fill:#FF000040;
',
    ),
    'fixed-width' => (object)array(
      'style' => '
font-size:22px;
width:200px;
height:100px;
',
    ),
    'word-wrap' => (object)array(
      'style' => '
font-size:22px;
width:200px;
height:100px;
white-space:normal;
',
    ),
    'right-align' => (object)array(
      'style' => '
font-size:22px;
width:150px;
white-space:normal;
text-align:right;
',
    ),
    'center-align' => (object)array(
      'style' => '
font-size:12px;
width:150px;
white-space:normal;
text-align:center;
',
    ),
    'align-stroked' => (object)array(
      'style' => '
font-size:12px;
width:150px;
white-space:normal;
text-align:center;
stroke:#666666;
stroke-width:5px;
background-color:#333333;
',
    ),
    'wrap-rotated' => (object)array(
      'style' => '
font-size:20px;
width:100px;
white-space:normal;
text-align:left;
transform: rotate(-10);
',
    ),
    'with-newline' => (object)array(
      'style' => '
font-size:20px;
width:200px;
white-space:normal;
text-align:center;
',
      'text' => "text with \n a newline in it",
    ),
    'test-tokenizing' => (object)array(
      'style' => '
font-size:15px;
width:100px;
white-space:normal;
text-align:center;
',
      'text' => "A & B or 'a&b' or \"A & B\" or a-and-b or \n-A-\nand\n-B-",
    ),
    'test-tokenizing-2' => (object)array(
      'style' => '
font-size:18px;
width:100px;
white-space:normal;
text-align:center;
',
      'text' => "A & B or 'a&b' or \"A & B\" or a-and-b or \n-A-\nand\n-B-",
    ),
  
  
  );
}