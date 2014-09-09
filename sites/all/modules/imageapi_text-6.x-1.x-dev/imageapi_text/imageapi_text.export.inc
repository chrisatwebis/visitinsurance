<?php
/**
 * @file Simple crack at supporting ctools exportables API
 * 
 * Built modelled on instructions from help/ctools/export
 * 
 */
 
/**
 * @defgroup imageapi_text export
 * @{
 */

/*
hooks expected by ctools export

export callback  MODULENAME_export_OBJECTNAME
list callback  MODULENAME_OBJECTNAME_list
to hook code callback  MODULENAME_OBJECTNAME_to_hook_code
*/ 

/**
 * Any time we are messing with this, it's implicit we need admin functions, but
 * the menu reouter only includes onr file.
 */
module_load_include('inc', 'imageapi_text', 'imageapi_text.admin');
 
/**
 * Export a style and display it in a form.
 * 
 * @ingroup export
 */
function imageapi_text_style_export_page($style) {
  drupal_set_title(t("Export %style_name", array('%style_name' => $style->name)));
  imageapi_text_fix_active_trail();
  $code = imageapi_text_export_imageapi_text_style($style);
  return drupal_get_form('ctools_export_form', $code, check_plain('Settings for "' . $style->name . '"'));
}


/**
 * Export a text style setting as code.
 * By declaring this function in the schema, anyone can easily export a myobj
 * just by knowing that myobj exists.
 * 
 * MODULENAME_export_OBJECTNAME()
 */
function imageapi_text_export_imageapi_text_style($myobj, $indent = '') {
  ctools_include('export');
  // 'imageapi_text_styles' is not really a database table, but ctools seems to need to think it is

  // What does ctools think is happening?
  #dpm(ctools_export_get_schema('imageapi_text_style'));

  $output = ctools_export_object('imageapi_text_style', $myobj, $indent);
  return $output;
}


//////////////////////
// IMPORT

/**
 * Form from page callback to import a style
 */
function imageapi_text_style_import_form(&$form_state) {
  $strings = array('%object_name' => 'style');
  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => t('%object_name name', $strings),
    '#description' => t('Enter the name of the new %object_name. This is optional and is not necessary if you do not wish to rename the object.', $strings),
  );

  $form['object'] = array(
    '#type' => 'textarea',
    '#title' => t('Paste %object_name code here', $strings),
    '#rows' => 15,
  );

  $form['import'] = array(
    '#type' => 'submit',
    '#value' => t('Import'),
  );
  
  return $form;
}


/**
 * Make sure that an import actually provides a handler.
 */
function imageapi_text_style_import_form_validate($form, &$form_state) {
  $object_name = 'style';
  $strings = array('%object_name' => 'style');
  // First, run the PHP and turn the input code into an object.
  ob_start();
  eval($form_state['values']['object']);
  ob_end_clean();

  // The object should appear as $style. This was the "identifier" set in the export section of the schema.
  if (empty($$object_name)) {
    $errors = ob_get_contents();
    if (empty($errors)) {
      $errors = t('No %object_name found.', $strings);
    }

    form_error($form['object'], t('Unable to get a %object_name from the import. Errors reported: @errors', $strings + array('@errors' => $errors)));
  }

  $form_state['obj'] = $$object_name;
}

/**
 * Save the imported object.
 */
function imageapi_text_style_import_form_submit($form, &$form_state) {
  $myobj = $form_state['obj'];

  if (!empty($form_state['values']['name'])) {
    $myobj->name = $form_state['values']['name'];
  }

  imageapi_text_style_save($myobj);
  $form_state['redirect'] = IMAGEAPI_TEXT_ADMIN_PATH . '/styles/' . $myobj->sid;
}


/**
 * @} End of "defgroup imageapi_text export".
 */