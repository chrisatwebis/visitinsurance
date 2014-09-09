<?php
/**
 * @file Helper functions for imagecache_textactions
 * 
 * Static rendertext and dynamic caption functions
 * 
 * This is a rewrite of textactions to consolidate various text effects into a
 * new SVG-like syntax. Not compatible with earlier efforts. HOPEFULLY
 * compatible with broader imageapi efforts and signwriter, menuwriter usage
 * etc.
 * Quite simply, CSS is not good enough a syntax to do half the effects we want.
 * That's why we are doing images, right?
 * SVG however does have enough bells to make this worthwhile.
 * 
 * Contains a stub for imageapi functions : imageapi_image_create_text that may
 * be ported over to there if that makes sense.
 */

/**
 * Place text on top of the current canvas
 *
 * Implementation of imagecache_hook_form()
 *
 * @param $action array of settings for this action
 * @return a form definition
 */
function imagecache_textaction_form($action) {

  $defaults = array(
    'xpos' => 'left+10',
    'ypos' => 'bottom-10',
    'textstyle' => array(
      'fontfile' => drupal_get_path('module', 'imageapi_text') . '/fonts/liberation-fonts-1.04/LiberationSans-Regular.ttf',
      'style' => "font-size:12px;\nfill:#333333;\ntop:10px;\nright:10px;",
    ),
    'text' => 'Hello World!',
    'evaluate_text' => FALSE,
  );
  // Our 'textstyle' parameter is a nested array - reflecting the wiget fieldset structure
  // only because imagecache sets the form
  // #tree to true, and unsetting it doesn't work.

  // Use the edit widget that imageapi_text provides
  module_load_include('inc', 'imageapi_text', 'imageapi_text.admin');
  
  $action = array_merge($defaults, (array)$action);
  $form = array(
    'textstyle' => imageapi_text_style_widget($action['textstyle']),
    
    'text' => array(
      '#type' => 'textarea',
      '#rows' => 7,
      '#title' => t('Text'),
      '#default_value' => $action['text'],
      '#description' => t('
        The text string. 
        If you are using a WYSIWYG editor, you should disable it for this field!
        <br/>
        <b>Tokens</b> are supported here! 
        Global tokens such as [site-name] and [user] will be replaced.
        If at all possible, tokens from the node that owns the image will
        also be available, such as [title] [author-name].
        If you are using CCK filefield, [file-title] and [file-description]
        may also be available.
        <br/>
        See the notes in php help for details.
      '),
      '#wysiwyg' => FALSE,
    ),
    'evaluate_text' => array(
      '#type' => 'checkbox',
      '#title' => t('Evaluate text as PHP code'),
      '#default_value' => $action['evaluate_text'],
      '#description' => t('If selected, the text will be treated as PHP code.'),
    ),
    'php_help' => array(
      '#type' => 'fieldset',
      '#collapsible' => TRUE, 
      '#collapsed' => TRUE, 
      '#title' => t('PHP code help'),
      '#value' => file_get_contents(drupal_get_path('module', 'imagecache_textaction') .'/help/textaction_syntax.html'),
    ),
  );
  if (! user_access('administer site configuration')) {
    $form['evaluate_text']['#disabled'] = TRUE;
    $form['text']['#disabled'] = $action['evaluate_text']; // lock this if an admin has enabled evaluation.
    $form['evaluate_text']['#description'] = 'requires <b>administer site configuration</b> permissions.';
  }
  #$form['#tree'] = FALSE;
  #$form['textstyle']['#tree'] = FALSE;

  return $form;
}

/**
 * Implementation of theme_hook() for imagecache_ui.module
 */
function theme_imagecache_textaction($element) {
  $data = $element['#value'];
  $style_atts = imageapi_text_parse_style($data['textstyle']['style']);
  return t("<em>\"<strong>@data</strong>\"</em><br/>%style<br/>%font", array(
    '@data'  => $data['text'], 
    '%style' => $data['textstyle']['style'], 
    '%font'  => basename($data['textstyle']['fontfile'])
  )) ;
}

/**
 * Place the text defined in the action onto the current background
 *
 * Implementation of hook_image()
 *
 * @param $image
 * @param $action
 */
function imagecache_textaction_image(&$image, $action = array()) {

  if (!empty($action['evaluate_text'])) {
    $text = imagecache_actions_evaluate_text($image, $action);
  }
  else {
    $text = $action['text'];
    // apply tokens
    $text = token_replace($text, 'global');
    $node = imagecache_actions_node_from_filepath($image->source, $file_data);

    if ($node) {
      watchdog('imagecache_textactions', 'Found a node \'%title\' to apply tokens to the text from', array('%title' => $node->title), WATCHDOG_INFO);
      $text = token_replace($text, 'node', $node);
    }
    if ($file_data) {
      // $file_data may include 'description' or 'title' if cck fields are attached
      // or other values if meta_inspector deductions worked.
      $tokens = array(
        '[file-title]'       => $file_data->title,
        '[file-description]' => $file_data->description
      );
      $text = str_replace(array_keys($tokens), array_values($tokens), $text);
    }
  }
  
  
  if (empty($text)) {
    // Do nothing, carry on
    return TRUE;
  }

  // 2010-05-21 updated API call to imageapi_image_create_text
  $text_style = (object)array();
  $text_style->style = imageapi_text_parse_style($action['textstyle']['style']);
  $text_style->fontfile = $action['textstyle']['fontfile'];

  // Calculate position by first creating a temp image 
  // containing the text and then accessing its dimensions.
  // Inefficient, but reliable way to actually get the bounding box.

  // $textimage is a placeholder that will get the image created in it - an object to work like the rest of the imageapi functions.
  // We really need to force the toolkit to GD, but even then it can't be merged back into imagemagick
  $textimage = (object) array(
    'toolkit' => $image->toolkit,
  );
  
  if (! imageapi_image_create_text($textimage, $text, $text_style) || empty($textimage->info) ) {
    drupal_set_message(t('Failed to generate text image using %toolkit. Not overlaying text.', array('%toolkit' => $textimage->toolkit )), 'error');
    return FALSE;
  }

  // $textimage should now have its size info available.
  // Calc the position on the canvas
  $xy = imagecache_actions_calculate_relative_position($image, $textimage, $text_style->style);

  module_load_include('inc', 'imagecache_actions', 'imageapi_image_overlay');
  return imageapi_image_overlay($image, $textimage, $xy['x'], $xy['y']);
}

/**
 * Generate the dynamic text for this image.
 * 
 * Given only an image handle, figure out what node or database entry it's
 * related to, then use that information to process some text that may be used
 * to decorate it.
 * 
 *  TODO further code review for safety etc
 * 
 * @param $image object, as provided by imageapi
 * @param $action definition
 * 
 * @return $text Plain or code string to be placed on the imagecache process.
 */
function imagecache_actions_evaluate_text($image, $action) {

  // HOOK_metadata from file attempts to glean info from any direction possible - EXIF, XMP, DB, description.txt
  // @see the meta_* project
  if (!empty($image->source)) {
    $file_metadata = module_invoke_all('metadata_from_file', $image->source);
    #$file_data = (object) $meta;
    // $file_data is an array of namespaced keyed further arrays of values.
    // $file_data['dc:title'] = array('this pic');
    // $title = reset($file_data['dc:title']);
    // It's always an array because there may be conflicting or multiple values at any time.
    // The KEYS to that array will be the source of the data
    
    // $file_data is a simplified, flattened version of the same
    // provided for user convenience because namespaces are scary
    $file_data = (object)array();
    foreach($file_metadata as $key => $val_array) {
      @list($namespace, $shortname) = explode(":", $key);
      if (! $shortname) $shortname = $namespace;
      $shortname = strtolower($shortname);
      if (is_array($val_array)) $file_data->$shortname = array_shift($val_array);
      else $file_data->$shortname = $val_array;
    }
  }

  $node = imagecache_actions_node_from_filepath($image->source, $file_data) ;

  // Process the php using drupal_eval (rather than eval), 
  // but with GLOBAL variables, so they can be passed successfully
  $GLOBALS['image'] = $image;
  $GLOBALS['node'] = $node;
  $GLOBALS['file_data'] = $file_data;
  $GLOBALS['file_metadata'] = $file_metadata;

  $command = '<'.'?php  
      global $node; global $image; global $file_data; global $file_metadata;
      '. $action['text'] .'; 
      ?'.'>';

  // $text = @drupal_eval($command);
  // Silently recovering from user error makes it hard to debug.
  // Better we complain and let them fix it.
  $text = drupal_eval($command);

  #dpm(array('command' => $command, 'text' => $text));

  // Warn about errors in the php code if I can
  if (empty($text) && function_exists('error_get_last') && $last_error = error_get_last()) {
    drupal_set_message("Problem evaluating dynamic text. <br/><code>{$action['text']}</code><br/> ". $last_error['message'], 'error');
    trigger_error("Problem evaluating dynamic text. <br/><code>{$action['text']}</code><br/> ". $last_error['message'], E_USER_WARNING);
  }

  return $text;
}


/**
 * Given only a file filename, track back to see if we can detect the parent
 * node and provide some context info.
 * 
 * This will be different in different cases.
 * Supported :
 * image.module image nodes
 * imagefield cck fields (may be multiple)
 * upload.module attachments 
 * 
 * TODO: image_attach attachments
 * 
 * @param $filepath
 * @param $file_data MAY get some details filled in on it by reference if data
 * was found.
 * 
 * @return a loaded $node object
 */
function imagecache_actions_node_from_filepath($filepath, &$file_data = NULL) {
  static $panic; #This can trigger recursion! build-load-build-etc
  if ($panic) {
    watchdog('imagecache_actions', 'I think I\'ve encountered recursion in %function. Returning NULL', array('%function' => __FUNCTION__), WATCHDOG_WARNING);
    return;
  }
  $panic = TRUE;

  // This is a difficult job. cache results if I can
  static $cache, $file_data_cache;
  if (isset($cache[$filepath])) {
    $file_data = $file_data_cache[$filepath];
    return $cache[$filepath];
  }
  $node = NULL;

  if (module_exists('upload')) {
    $sql = "SELECT nid, f.fid FROM {upload} AS u INNER JOIN {files} AS f ON u.fid = f.fid WHERE f.filepath = '%s'";
    $results = db_query_range($sql, $filepath, 0, 1);
    if ( $row = db_fetch_array($results)) {
      $node = node_load($row['nid']);
      // also include the file description
      $file_data = $node->files[$row['fid']];
    }
  }

  // Lookup image.module nodes
  if (!$node && module_exists('image')) {
    $sql = "SELECT nid FROM {image} AS i INNER JOIN {files} AS f ON i.fid = f.fid WHERE f.filepath = '%s'";
    if ( $nid = db_result(db_query_range($sql, $filepath, 0, 1))) {
      $node = node_load($nid); 
      watchdog('imagecache_textactions', "Found a node %title that owns file %file", array('%title' => $node->title, '%file' => $filepath), WATCHDOG_DEBUG);
    }
  }


  // Lookup filefield imagefield CCK attachments.
  //
  // Drupal 6 version here based largely on work done by mikeytown2 
  // http://drupal.org/node/363434
  

  if (!$node && module_exists('content')) {

    // This is a terrible way to retrieve information, but CCK doesn't provide a way to reverse-lookup like this
    // BAD CODE follows
    // If someone could roll these DBlookups into a smaller ball, that would be fun.
    // Due to CCKs use of dynamically created table names .. I don't know how.
    
    // Multiple file ID's might have the same name, get all 
    // (but return just the first successful match)
    $result = db_query("SELECT fid FROM {files} WHERE filepath = '%s'", $filepath);
    $fids = array();
    while ($row = db_fetch_array($result)) {
      $fids[] = $row['fid'];
    }

    if (! empty($fids)) {
      // Find out if any filefield contains this file, and if so, which field
      // and node it belongs to. Required for later access checking.
      // CCK field analysis is in the outer loop, 
      // fids are scanned in the inner loop for a little speed.
  
      // Get A List Of ALL CCK Fields and look for them individually, 
      // it's the only way we can reverse the lookups
      foreach (content_fields() as $field) {
        // If Field is an Image (imagefield.module) or filefield then
        if ($field['type'] == 'image' || $field['type'] == 'filefield') {
          // Need to do lots of lookups to find out what the storage tables look like.
          // Grab All DB Column Names for that CCK Field
          $db_info = content_database_info($field);
          // Get Content Type DB name - FROM statement
          $tablename = $db_info['table'];
          //Get File ID DB Column Name - WHERE statement
          $fid_column = $db_info['columns']['fid']['column'];
  
          // Construct a Query that looks for all known fids in one go.
          // eg:
          // SELECT nid FROM content_type_story 
          //   WHERE field_illustration_fid = 77 
          //   OR field_illustration_fid = 99;
          
          $wheres = array();
          $query_args = array();
          foreach ($fids as $fid) {
            $wheres[] = " %s = %d ";
            $query_args[] = $fid_column; 
            $query_args[] = $fid; 
          }
  
          $result = db_query('SELECT nid FROM {'. $tablename .'} WHERE '. join(' OR ', $wheres), $query_args);
          
          while (!$node && ($row = db_fetch_array($result))) {
            // This while is a dummy loop - Just break out and return the first matching node. 
            // If more than one node owns this image then ???
            $node = node_load($row['nid']);
            watchdog('imagecache_textactions', "Found a node '%title' that has a field '%field' that contains the file %file", array('%title' => $node->title, '%field' => $field['field_name'], '%file' => $filepath), WATCHDOG_DEBUG);

            // Add even more info - the description "data" that MAY have been added 
            // to this file on this node using filefield_meta and stuff.
            // Return the data associated specifically with this file also;
            // Do this via the handle on the file_data object we were passed in.
            // Slightly mushed together - I want it to mostly resemble the traditional file attachment object.
            
            // We have the node but lost track of the file in the process.
            // Need to scan again to make sure we got the right one :-{
            if ( $file_fields = $node->{$field['field_name']} ) {
              foreach ($file_fields as $file_field) {
                if ($file_field['fid'] == $fid) {
                  $actual_file = $file_field;
                }
              }
              $file_data = (object) array_merge((array)$file_data, $actual_file['data'], $actual_file);
  
            }
          }
        }
      }
    }
  }
  $panic = FALSE;
  $cache[$filepath] = $node;
  $file_data_cache[$filepath] = $file_data;
  return $node;
}

