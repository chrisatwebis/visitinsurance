<?php


/**
* Implementation of THEMEHOOK_settings() function.
*
* @param $saved_settings
*   array An array of saved settings for this theme.
* @return
*   array A form array.
*/
function solarsentinel_settings($saved_settings){
	
  $defaults = solarsentinel_settings_defaults();

  // Merge the saved variables and their default values
  $settings = array_merge($defaults, $saved_settings);

  // Create the form widgets using Forms API

	 $form['theme'] = array(
	  '#type' => 'fieldset',
	  '#title' => t('Theme'),
	  '#collapsible' => TRUE,
	  '#collapsed' => FALSE,
	);
	
	$form['theme']['preset_style'] = array(
    '#type' => 'select',
    '#title' => t('Default Theme Style'),
		'#options' => array(
	  		'style1' => 'Style 1',  
	  		'style2' => 'Style 2', 
	  		'style3' => 'Style 3',  
	  		'style4' => 'Style 4',  
	  		'style5' => 'Style 5',
	  		'style6' => 'Style 6',  
	  		'style7' => 'Style 7', 
	  		'style8' => 'Style 8',  
	  		'style9' => 'Style 9',  
	  		'style10' => 'Style 10'

	),
	'#default_value' => $settings['preset_style'],
  );
	
	 $form['menu'] = array(
	  '#type' => 'fieldset',
	  '#title' => t('Menu settings'),
	  '#collapsible' => TRUE,
	  '#collapsed' => FALSE,
	);

  $form['menu']['m_type'] = array(
    '#type' => 'select',
    '#title' => t('Menu type'),
		'#options' => array(
			'moomenu' => 'MooMenu',
			'suckerfish' => 'Suckerfish', 
			'splitmenu' => 'SplitMenu',
			'module' => 'Module'
		),
		'#description' => t('Type of menu for main navigation'),
    '#default_value' => $settings['m_type'],
  );
  
  $form['menu']['menu_columns'] = array(
    '#type' => 'select',
    '#title' => t('Drop Down Columns'),
		'#options' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
		
			),
    '#default_value' => $settings['menu_columns']
  ); 
  
  $form['menu']['moomenu_bgiframe'] = array(
    '#type' => 'select',
    '#title' => t('MooMenu BGiFrame'),
		'#options' => array(
			'true' => t('True'),
			'false' => t('False')
		),
    '#default_value' => $settings['moomenu_bgiframe'],
  );
  
 $form['menu']['moomenu_delay'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Delay'),
	    '#default_value' => $settings['moomenu_delay'],
			'#size' => 10,
			'#required' => TRUE
	  );
  
  $form['menu']['moomenu_duration'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Duration'),
	    '#default_value' => $settings['moomenu_duration'],
			'#size' => 10,
			'#required' => TRUE
	  );
	  
  $form['menu']['moomenu_transition'] = array(
    '#type' => 'select',
    '#title' => t('MooMenu Transition'),
		'#options' => array(
			'linear' => 'linear',
			'Quad.easeOut' => 'Quad.easeOut',
			'Quad.easeIn' => 'Quad.easeIn',
			'Quad.easeInOut' => 'Quad.easeInOut',
			'Cubic.easeOut' => 'Cubic.easeOut',
			'Cubic.easeIn' => 'Cubic.easeIn',
			'Cubic.easeInOut' => 'Cubic.easeInOut',
			'Quart.easeOut' => 'Quart.easeOut',
			'Quart.easeIn' => 'Quart.easeIn',
			'Quart.easeInOut' => 'Quart.easeInOut',
			'Quint.easeOut' => 'Quint.easeOut',
			'Quint.easeIn' => 'Quint.easeIn',
			'Quint.easeInOut' => 'Quint.easeInOut',
			'Expo.easeOut' => 'Expo.easeOut',
			'Expo.easeIn' => 'Expo.easeIn',
			'Expo.easeInOut' => 'Expo.easeInOut',
			'Circ.easeOut' => 'Circ.easeOut',
			'Circ.easeIn' => 'Circ.easeIn',
			'Circ.easeInOut' => 'Circ.easeInOut',
			'Sine.easeOut' => 'Sine.easeOut',
			'Sine.easeIn' => 'Sine.easeIn',
			'Sine.easeInOut' => 'Sine.easeInOut',
			'Back.easeOut' => 'Back.easeOut',
			'Back.easeIn' => 'Back.easeIn',
			'Back.easeInOut' => 'Back.easeInOut',
			'Bounce.easeOut' => 'Bounce.easeOut',
			'Bounce.easeIn' => 'Bounce.easeIn',
			'Bounce.easeInOut' => 'Bounce.easeInOut',
			'Elastic.easeOut' => 'Elastic.easeOut',
			'Elastic.easeIn' => 'Elastic.easeIn',
			'Elastic.easeInOut' => 'Elastic.easeInOut',
			),
    '#default_value' => $settings['moomenu_transition'],
		'#description' => t('Any of the available MooTools transitions.')
  );	  


  $form['menu']['splitmenu_col'] = array(
    '#type' => 'select',
    '#title' => t('Splitmenu submenu side'),
		'#options' => array(
			'none' => t('None'),
			'leftcol' => t('Left Column'),
			'rightcol' => t('Right Column')
		),
    '#default_value' => $settings['splitmenu_col'],
  );

// Page Widths -----------------------------

/*
	$form['widths'] = array(
	  '#type' => 'fieldset',
	  '#title' => t('Page Widths'),
	  '#collapsible' => TRUE,
	  '#collapsed' => FALSE,
	);
	
	$form['widths']['template_width'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Template Width'),
	    '#default_value' => $settings['template_width'],
			'#size' => 10,
			'#required' => TRUE
	  );
	  
	  
	  $form['widths']['leftinset_width'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Left Inset Width'),
	    '#default_value' => $settings['leftinset_width'],
			'#size' => 10,
			'#required' => TRUE
	  );
	  
	  $form['widths']['rightinset_width'] = array(
	    '#type' => 'textfield',
	    '#title' => t('Right Inset Width'),
	    '#default_value' => $settings['rightinset_width'],
			'#size' => 10,
			'#required' => TRUE
	  );
*/

// ELEMENTS ------------------------------
	 $form['elements'] = array(
	  '#type' => 'fieldset',
	  '#title' => t('Elements'),
	  '#collapsible' => TRUE,
	  '#collapsed' => FALSE,
	);
	
	$form['elements']['show_tabs'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show In-Page Admin Tabs'),
    '#default_value' => $settings['show_tabs']
	);
	
	$form['elements']['show_breadcrumb'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show breadcrumb'),
    '#default_value' => $settings['show_breadcrumb']
	);
	
	$form['elements']['show_topbutton'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Top Button'),
    '#default_value' => $settings['show_topbutton']
	);
	
	$form['elements']['show_copyright'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Copyright'),
    '#default_value' => $settings['show_copyright']
	);
	
	$form['elements']['show_date'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Date'),
    '#default_value' => $settings['show_date']
	);
	
	$form['elements']['show_frontpage_content'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show Front Page Content'),
    '#default_value' => $settings['show_frontpage_content']
	);
	
	$form['elements']['js_compatibility'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable JS Compatibility'),
    '#default_value' => $settings['js_compatibility']
	);	
	

	 $form['fonts'] = array(
	  '#type' => 'fieldset',
	  '#title' => t('Fonts'),
	  '#collapsible' => TRUE,
	  '#collapsed' => FALSE,
	);

  $form['fonts']['font_family'] = array(
    '#type' => 'select',
    '#title' => t('Font family'),
		'#options' => array(
			'default' => 'Default',
			'solarsentinel' => 'Solar Sentinel',
			'geneva' => 'Geneva', 
			'optima' => 'Optima', 
			'helvetica' => 'Helvetica',
			'trebuchet' => 'Trebuchet', 
			'lucida' => 'Lucida', 
			'georgia' => 'Georgia', 
			'palatino' => 'Palatino'
		),
    '#default_value' => $settings['font_family'],
  );


	$form['fonts']['default_font'] = array(
    '#type' => 'select',
    '#title' => t('Font size'),
		'#options' => array(
			'small' => 'Small', 
			'default' => 'Default', 
			'large' => 'Large'
		),
    '#default_value' => $settings['default_font'],
  );

	 $form['ie6'] = array(
	  '#type' => 'fieldset',
	  '#title' => t('Internet Explorer 6 Compatibility'),
	  '#collapsible' => TRUE,
	  '#collapsed' => FALSE,
	);
	
	
	$form['ie6']['enable_ie6_warning'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable IE6 Warning'),
    '#default_value' => $settings['enable_ie6_warning'],
		'#description' => t('Warn IE6 users their browser is old and they won\'t be getting the full site experience.')
  );



  // Return the additional form widgets
  return $form;
	
}




function solarsentinel_settings_defaults(){
	
$defaults = array(

'preset_style' 						=> "style7",
'header_style' 						=> "blue",
'body_style' 						=> "white",
'bg_style'							=> "bg-grey",
'footer_style' 						=> "black",
'primary_color'         			=> "#0269b3" ,
'frontpage_component'    			=> "show",
'enable_ie6warn'         			=> "true",
'font_family'            			=> "solarsentinel",
'enable_fontspans'       			=> "true",
'enable_inputstyle'					=> "true",
'template_width'					=> "979",
'leftcolumn_width'					=> "210",
'rightcolumn_width'					=> "210",
'leftinset_width'					=> "240",
'rightinset_width'					=> "240",
'splitmenu_col'						=> "none",
'menu_name' 						=> "mainmenu",
'm_type' 							=> "moomenu",
'menu_rows_per_column'   			=> "2",
'menu_columns'           			=> "2",
'menu_multicollevel'    			=> "1",
'default_font' 						=> "default",
'show_breadcrumb'					=> "1",
'show_date'							=> "true",
'clientside_date'					=> "true",
'show_textsizer'		 			=> "true",
'show_topbutton' 					=> "true",
'show_copyright'					=> "true",
'js_compatibility'	 				=> "0",
'show_tabs'							=> "1"

  );
  
	return $defaults;
	
}



