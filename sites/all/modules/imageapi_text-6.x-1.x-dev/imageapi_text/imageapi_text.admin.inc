<?php
// $ID:  $
/**
 * @file
 *   Admin UI for imageapi_text
 * 
 * Forms and tools for managing text styles and global settings.
 * 
 * Include this file if you want to make use of the style editing widget in your
 * own form.
 * 
 * Created on May 19, 2010
 *
 * @author 'dman' Dan Morrison http://coders.co.nz/
 */

/**
 * @ingorup imageapi_text
 * @{
 */


/**
 * A subform to use when exposing text rendering options.
 * 
 * This is DESIGNED TO BE REUSED in your own forms if setting up to talk to
 * imageapi_text.
 * 
 * It includes full help, and a validate function. Provided as a convenience for
 * other modules to re-use.
 * 
 * The $style parameter is expected to contain a $style->style and a
 * $style->fontfile value.
 * 
 * @param $style text rendering definition
 * @return $form object
 * @ingroup drupal_forms
 */
function imageapi_text_style_widget($style) {
  $defaults = imageapi_text_style_default();
  $style = (object) array_merge((array) $defaults, (array) $style);
  
  // Display samples of the fonts next to the selector
  $all_fonts = imageapi_text_available_fonts();
  $all_fonts = array_merge(array('' => '<em>Default/Inherit</em>'), $all_fonts);
  $preview_profile = (object) $defaults;
  foreach ($all_fonts as $fontfile => $fontname) {
    $preview_profile->fontfile = $fontfile;
    $text = "$fontname - Sample text (0123456789)";
    $image_src = imageapi_text_dynamic_link($text, $preview_profile);
    $all_fonts[$fontfile] .= ' '. "<img src=\"$image_src\" style=\"vertical-align:bottom; margin-left:1em;\" >" ;
  }

  // Ensure a valid font is selected.
  if (! isset($style->fontfile) || ! in_array($style->fontfile, array_keys($all_fonts)) ) {
    // Default to the first valid font found.
    foreach ($all_fonts as $fontfile => $fontfilename) {
      $style->fontfile = $fontfile;
      break;
    }
  }
  $sampletext = isset($style->text) ? $style->text : (isset($style->name) ? $style->name : "Sample 123");

  $form = array(
    'sample' => array(
      '#type' => 'markup',
      #'#value' => theme('image', imageapi_text_dynamic_link($sampletext, $style), NULL, 'sample', array('id' => "imageapi-text-sample"), FALSE),
      # not using the theme func because it double-escapes my already complex URL 
      '#value' => "<div><img src=\"". imageapi_text_dynamic_link($sampletext, $style) ."\" id=\"imageapi-text-sample\"></div>",
    ),

    'style' => array(
      '#type' => 'textarea',
      '#title' => t('Style'),
      '#default_value' => $style->style,
      '#description' => t('
        Enter a subset of SVG (CSS-like but <b>not CSS</b>) style attributes separated by "<code>;</code>".
      '),
      '#attributes' => array('class' => 'font-style'),
      '#rows' => 8,
    ),
    'style_help' => array(
      '#type' => 'fieldset',
      '#collapsible' => TRUE, 
      '#collapsed' => TRUE, 
      '#title' => t('Style syntax help'),
      '#value' => file_get_contents(drupal_get_path('module', 'imageapi_text') . '/help/syntax_reference.html'),
    ),

    'fontfile' => array(
      '#type' => 'radios',
      '#title' => t('Font file'),
      '#default_value' => $style->fontfile,
      '#required' => TRUE,
      '#options' => $all_fonts,
      '#description' => t('For help finding and configuring available fonts,
        see the !settingslink.
      ', array('!settingslink' => l('ImageApi Text fonts configuration', IMAGEAPI_TEXT_ADMIN_PATH . '/settings') )),
      '#element_validate' => array('imageapi_text_validate_fontfile'),
      '#prefix' => "<div id='font-selector' style='height:15em; overflow:auto'>",
      '#suffix' => "</div>",
      '#attributes' => array('class' => 'font-selection'),
    ),

  );
  if (module_exists('advanced_help')) {
    unset($form['style_help']);
    $form['style']['#description'] .= '<br/>'. theme('advanced_help_topic', 'imageapi_text', 'syntax_reference') . " : Text style syntax reference";
  }
  $form['clear'] = array(
    '#type' => 'markup',
    '#value' => "<div style='clear:left'></div>",
  );

  drupal_add_js(drupal_get_path('module', 'imageapi_text') .'/imageapi_text.admin.js');
  drupal_add_css(drupal_get_path('module', 'imageapi_text') .'/imageapi_text.admin.css');
  return $form;  
}


/**
 * Global imageapi_text settings
 * 
 * Form where you can set the font search paths, and unicode handling for
 * fonts.
 * 
 * @ingroup drupal_forms
 */
function imageapi_text_settings() {
  $form = array();

  $form['fontwarning'] = array(
    '#type' => 'markup',
    '#value' => '<p>'. t('
      Some fonts (eg LiberationSans-Regular.ttf) have been found 
      to calculate the bounding box incorrectly 
      (cutting off the last few pixels). 
      This appears to be a limitation of some versions of GD 
      (imagettfbbox).
      One work-around is to add an invisible text-shadow-blur
      of a few pixels to give the text a little more space, eg
      <code>text-shadow-blur:5px</code>.
      Blur is currently unsupported, but it does add appropriate spacing,
      and if NOT accompanied with any text-shadow-color will be invisible.
    ' . '</p>'),
  );

  $fontpaths = imageapi_text_get_fontpaths();
  $form['fontpaths'] = array(
    '#type' => 'fieldset',
    '#title' => t("Current Font Search Paths"),
    'current' => array(
      '#type' => 'markup',
      '#value' => theme('item_list', $fontpaths)
        .'This list is generated based on your current theme, your configured
        user files directory, this module, and a (recommended) system-wide
        <code>sites/all/libraries</code> default.
        ',
    ),
  );
  $form['fontpaths']['imageapi_text_fontpath'] = array(
    '#type' => 'textfield',
    '#title' => t('Font Search Path'),
    '#description' => t('
      Add a : separated list of directories to search for your font files. 
      This will be searched as well as the default directories listed above.
      <br/>
      You may be able to add your servers system directory here also, eg
      <code>/usr/share/fonts/truetype</code> - although some systems (safe mode)
      will not allow that.
      <br/>
      Fonts and the font directories <em>must be readable</em> by the server.

    '),
    '#default_value' => variable_get('imageapi_text_fontpath', ''),
  );

  $available_fonts = imageapi_text_available_fonts();

  $form['imageapi_text_default_font'] = array(
    '#title' => "System default font",
    '#type' => 'select',
    '#options' => $available_fonts,
    '#description' => t('Font to be used if no other is defined for the style'),
    '#default_value' => variable_get('imageapi_text_default_font', ''),
  );


  $form['imageapi_text_font_characters'] = array(
    '#type' => 'fieldset',
    '#title' => t("Available Fonts"),
    '#tree' => TRUE,
    'current' => array(
      '#type' => 'markup',
      '#value' => t('
        Most fonts support only the ASCII character set by default, 
        so strings containing unicode characters may not render correctly.
        Paste here all unicode characters your font <em>does</em> include 
        so strings which only contain characters from this list 
        and the default ASCII charset will be converted as requested. 
        Unrecognised characters may be stripped otherwise.
        Put \'all\' in this field to allow all unicode characters.
      '),
    ),
  );

  $non_ascii_list = variable_get('imageapi_text_font_characters', array());
  foreach ($available_fonts as $fontpath => $fontname) {
    $form['imageapi_text_font_characters'][$fontpath] = array(
      '#title' => "<abbr title=\"$fontpath\">$fontname</abbr>",
      '#type' => 'textfield',
      '#description' => t('Allowed non-ASCII characters'),
      '#default_value' => empty($non_ascii_list[$fontpath]) ? '' : $non_ascii_list[$fontpath],
    );
  }

  $form['imageapi_text_specialcharnotice'] = array(
    '#type' => 'checkbox',
    '#title' => t('Special character notice'),
    '#description' => t('
      Each time time a string is to be rendered it is checked for 
      special characters outside the ASCII character set. 
      If any are found and the current user has the permission to 
      configure imageAPI, output a notice how to allow these characters.
    '),
    '#default_value' => variable_get('imageapi_text_specialcharnotice', TRUE),
  );

  $form['advanced'] = array(
    '#type' => 'fieldset',
    '#title' => t('Advanced'),
    '#description' => t('There are several ways to safely do image replacement, although they have different effects on accessability.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['advanced']['imageapi_text_replacement_method'] = array(
    '#type' => 'radios',
    '#title' => t('Replacement method'),
    '#default_value' => variable_get('imageapi_text_replacement_method', 'embedded'),
    '#options' => array(
      'embedded' => 'Embedded img tag',
      'leahy_langridge' => 'CSS offset using padding and hidden overflow (Leahy/Langridge)',
      'phark' => '"Phark" CSS offset using negative text position',
      'css_overlay' => 'Overlay element with background image (safe with images disabled)',
    ),
  );

  $form['flush_file_cache'] = array(
    '#type' => 'submit',
    '#value' => t('Delete all cached files'),
    '#attributes' => array('title' => 'This wil erase ALL cached files in the file diretory. They will be rebuilt as needed.'),
    '#submit' => array('imageapi_text_flush_cache'),
  );

  return system_settings_form($form);
}

/**
 * FAPI form to manage available text styles
 * 
 * List currently available styles, add or delete them and link to edit them.
 */
function imageapi_text_style_list() {
  $form = array();
  $styles = imageapi_text_style_load_all();
  $rows = array();
  foreach ($styles as $sid => $style) {
    $row = array(
      'name' => $style->name,
      'sample' => $style->name,
      'sample' => theme('imageapi_text', $style->name, $style),
      'edit' => l(t('edit'), IMAGEAPI_TEXT_ADMIN_PATH . '/styles/' . $style->sid),
      'delete' => l(t('delete'), IMAGEAPI_TEXT_ADMIN_PATH . '/styles/' . $style->sid . '/delete')
    );
    if (module_exists('ctools')) {
      $row['export'] = l(t('export'), IMAGEAPI_TEXT_ADMIN_PATH . '/styles/' . $style->sid . '/export');
    }
    $rows[] = $row;
  }
  if (empty($rows)) {
    $rows[] = array(array('data' => t('No profiles'), 'colspan' => '4', 'class' => 'message'));
  }
  $rows[] = array(
  array('data' => 
    l(t('Add a text style'), IMAGEAPI_TEXT_ADMIN_PATH . '/styles/add'),
    'colspan' => '4',
    ),
  );
  // tablesort.inc doesn't like named columns. That's a shame
  $header = array(
    t('Style'),
    t('Sample'),
    t('Actions'),
  );
  $form['styles'] = array(
    '#value' => theme('table', $header, $rows)
  );
 
  $form['delete_all'] = array(
    '#type' => 'submit',
    '#value' => t('Delete all styles'),
    '#attributes' => array('title' => 'This wil erase ALL defined styles and start with a clean slate.'),
    '#submit' => array('imageapi_text_style_delete_all'),
  );
 
  return $form;
}


/**
 * FAPI form to edit one text style profile
 * 
 * Basically exposes the text style widget.
 * Called directly as a menu callback.
 */
function imageapi_text_style_edit($form_state, $style = NULL) {
  if (empty($style)) {
    $style = imageapi_text_style_default();
    $style->name = "New style";
  }
  drupal_set_title(t('Edit'). ' "'. $style->name . '"');
  $form = array();
  $form['name'] = array(
    '#title' => t('Name'),
    '#type' => 'textfield',
    '#default_value' => $style->name,
  );
  $form['sid'] = array(
    '#type' => 'hidden',
    '#default_value' => $style->sid,
  );
  $form = array_merge($form, imageapi_text_style_widget($style));
  $form['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );
  
  imageapi_text_fix_active_trail();
  
  return $form;
}

/**
 * FAPI callback - validate a style from the edit form submission
 * 
 * This validate isn't needed much thanks to the live preview.
 * 
 * @see imageapi_text_style_edit
 */
function imageapi_text_style_edit_validate($form, &$form_state) {
  $text_style = (object) $form_state['values'];
  $parsed_style = imageapi_text_parse_style($text_style->style, FALSE); 
  if (isset($parsed_style['color'])) {
    drupal_set_message("Style definition includes 'color'. That won't work. You probably mean 'fill' instead.", 'error');
  }
  #dpm($parsed_style);
}


/**
 * FAPI callback - save a style from the edit form submission
 * 
 * @see imageapi_text_style_edit
 */
function imageapi_text_style_edit_submit($form, &$form_state) {
  $style = (object) array();
  // Use the default style to enumerate the keys in a style and grab them from the form
  // (just to keep track of them in case more fields are added)
  $default_style = imageapi_text_style_default();
  foreach ($form_state['values'] as $var => $val) {
    if (isset($default_style->$var)) {
      $style->$var = $val;
    }
  }
  imageapi_text_style_save($style);
  drupal_set_message(t('Saved text style %style_name', array('%style_name' => $style->name)));
  #dpm($style);
  drupal_goto(IMAGEAPI_TEXT_ADMIN_PATH);
}

/**
 * Junior CRUD function. Just save style definitions in the variables table for
 * now.
 * @TODO refactor if we are managing dozens.
 *
 * @ingroup imageapi_text crud
 * 
 */
function imageapi_text_style_save($style) {
  $styles = variable_get('imageapi_text_styles', array());
  $new_sid = form_clean_id($style->name);
  if (!empty($style->sid) && $style->sid != $new_sid) {
    // Allow renaming
    unset($styles[$style->sid]);
  } 
  $style->sid = $new_sid;
  $styles[$style->sid] = $style;
  variable_set('imageapi_text_styles', $styles);
}

/**
 * Delete a style with a one-click action from the management screen.
 * 
 * @see imageapi_text_style_list
 */
function imageapi_text_style_delete_callback($style) {
  imageapi_text_style_delete($style);
  drupal_set_message("Deleted text style %style_name", array('%style_name' => $style->name));
  drupal_goto(IMAGEAPI_TEXT_ADMIN_PATH);
}

/**
 * Delete a style
 */
function imageapi_text_style_delete($style) {
  $styles = variable_get('imageapi_text_styles', array());
  unset($styles[$style->sid]);
  variable_set('imageapi_text_styles', $styles);
}

/**
 * Delete all styles with a submit button action.
 * 
 * @see imageapi_text_style_list()
 */
function imageapi_text_style_delete_all() {
  variable_set('imageapi_text_styles', array());
  drupal_set_message(t("All styles deleted"));
}

/**
 * Form submit callback to remove all temp files
 * 
 * @see imageapi_text_settings()
 */
function imageapi_text_flush_cache() {
  $cachedir = variable_get('imageapi_text_cachedir', file_directory_path() .'/imageapi-text');
  $path = realpath($cachedir);
  if (is_dir($path)) {
    $d = dir($path);
    while (($entry = $d->read()) !== FALSE) {
      if ($entry == '.' || $entry == '..') continue;
      file_delete($path .'/'. $entry);
    }
    $d->close();
    rmdir($path);
  }
  drupal_set_message(t("All cache files deleted from %cachedir", array('%cachedir' => $cachedir)));
}


/**
 * Check if the named font is available.
 * 
 * Triggers a warning in the UI, but no return.
 */
function imageapi_text_validate_fontfile(&$element, &$form_status) {
  if (! is_readable($element['#value'])) {
    // Just warn, don't prevent
    drupal_set_message(t("Seems like '%fontfile' is not accessible on your system.", array('%fontfile' => $element['#value'])), 'warning' );
  }
}

/**
 * Returns paths to be searched for fonts.
 * 
 * This code lifted from signwriter.module.
 */
function imageapi_text_get_fontpaths() {
  // Search these likely directories as well as user-supplied dirs for a font
  $fontpaths = array(
    'sites/all/libraries/fonts',
    file_directory_path(), 
    file_directory_path() .'/fonts', 
    path_to_theme(), 
    path_to_theme() .'/fonts',
    drupal_get_path('module', 'imageapi_text') .'/fonts',
  );
  $userfontpath = variable_get('imageapi_text_fontpath', '');
  if ($userfontpath != '') {
    array_push($fontpaths, $userfontpath);
  }
  if (ini_get('safe_mode')) {
    // Convert relative to absolute paths.
    foreach ($fontpaths as $index => $path) {
      $fontpaths[$index] = realpath($path);
    }
  }
  return $fontpaths;
}

/**
 * Internal: Returns a list of all available fonts in the system, searching the font path and other likely spots.
 * 
 * This code lifted from signwriter.module.
 */
function imageapi_text_available_fonts() {
  $fontpath = imageapi_text_get_fontpaths();
  $fonts = array();
  foreach ($fontpath as $dir) {
    $fontfiles = file_scan_directory($dir, '.*\.ttf$');
    foreach ($fontfiles as $font) {
      $fonts[$font->filename] = $font->basename;
    }
  }
  return $fonts;
}


/**
 * URL callback that generates and returns a text image dynamically.
 * 
 * Use only for admin screens, and don't allow public access to this, as it's a
 * bit of a short-cut.
 * 
 * The 'profile' arg is both serialized and URLencoded
 * 
 * The profile to use is expected to have been POSTed and will be deduced from
 * global context.
 * 
 * @param $text (passed in through the url path)
 */
function imageapi_text_dynamic_callback($text) {
  $text = urldecode($text);

  // Allow the args to be passed straight in flat in the _REQUEST.
  // Assume they've got the right amount of data to create a profile. Ignore the rest.
  // This is used by the jscript realtime requests that just collate and semi-submit a form
  $text_style = (object)$_REQUEST;
  
  // Paranoia
  if (empty($text_style->style)) {
    trigger_error("imageapi_text_dynamic_callback() called with absolutely no style information. Using defaults", E_USER_WARNING);
    $text_style = imageapi_text_style_default();
  }

  // Something flaky in drupal_urlencode or drupal_query_string_encode 
  // double-escaped my # from imageapi_text_dynamic_link ()
  // and ruined the colors.
  // Apparently Apache gets the blame.
  // Anyway,
  $text_style->style = str_replace('%23', '#', $text_style->style);

  // Normally text is passed in at the end of the drupal path, but you can override it from the form if you like.
  if (! empty($_REQUEST['text'])) $text = $_REQUEST['text'];

  // Run the imageapi routine. Need a dummy imageapi image object first
  $image = new stdClass();
  $image->info = array(
    'mime_type' => isset($style->mime_type) ? $text_style->mime_type : 'image/png'
  );
  $image->toolkit = imageapi_default_toolkit();

  // Special case to allow imagecache_actions : textactions preview
  if (!empty($text_style->evaluate_text) && function_exists('textactions_evaluate_text') ) {
    $text = textactions_evaluate_text($image, array('text' => $text));
  }

  // Now draw the text.
  imageapi_image_create_text($image, $text, $text_style);

  // Send it straight back to the browser
  imageapi_image_return($image);
}

/**
 * Create an URL defining a text image to make.
 * Profile parameters are folded into the GET string
 * 
 * Because this was already run through url(), you can't call l() on it again
 * later? It re-encodes the URL :-/
 */
function imageapi_text_dynamic_link($text, $style) {
  $args = (array)$style;
  return url('imageapi_text/dynamic/'. urlencode($text), array('query' => $args));
}

/**
 * local menus got a bit tangled because they are too deep or something.
 * 
 * Ensure that imageapi text is in the breadcrumbs by inserting it above the
 * current item.
 */
function imageapi_text_fix_active_trail() {
  // Fix up the breadcrumbs
  $parent_item = menu_get_item(IMAGEAPI_TEXT_ADMIN_PATH);
  $menu_trail = menu_get_active_trail();
  $this_item = array_pop($menu_trail);
  array_push($menu_trail, $parent_item, $this_item);
  menu_set_active_trail($menu_trail);
}

/**
 * @} End of "ingroup imageapi_text".
 */
