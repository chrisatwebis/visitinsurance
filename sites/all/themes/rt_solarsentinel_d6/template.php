<?php
$current_theme_path = path_to_theme();
drupal_add_js($current_theme_path.'/custom/js/custom.js'); // load the javascript
/**
 * Comment out for production!
 * For development only. Defeats theme register caching.
 * Changes to theme show immediately, but performance is effected.
 * Comment out for production.
 */
drupal_rebuild_theme_registry();

/*
* Initialize theme settings
*/
if (is_null(theme_get_setting('theme_variation'))) {  

  global $theme_key;

	if (!(function_exists('solarsentinel_settings_defaults'))){
		include('theme-settings.php');
	}
	
	
  $defaults = solarsentinel_settings_defaults();

  // Get default theme settings.
  $settings = theme_get_settings($theme_key);
  // Don't save the toggle_node_info_ variables.
  if (module_exists('node')) {
    foreach (node_get_types() as $type => $name) {
      unset($settings['toggle_node_info_' . $type]);
    }
  }
  // Save default theme settings.
  variable_set(
    str_replace('/', '_', 'theme_'. $theme_key .'_settings'),
    array_merge($defaults, $settings)
  );

  // Force refresh of Drupal internals.
  theme_get_setting('', TRUE);

}



/**
* Override or insert PHPTemplate variables into the search_block_form template.
*
* @param $vars
*   A sequential array of variables to pass to the theme template.
* @param $hook
*   The name of the theme function being called (not used in this case.)
*/
function solarsentinel_preprocess_search_block_form(&$variables) {
  
  $variables['form']['search_block_form']['#title'] = 'Search...';
  $variables['form']['search_block_form']['#size'] = 20;
  $variables['form']['search_block_form']['#value'] = 'search...';
  $variables['form']['search_block_form']['#attributes'] = array('onblur' => "if (this.value == '') {this.value = '".$variables['form']['search_block_form']['#value']."';}", 'onfocus' => "if (this.value == '".$variables['form']['search_block_form']['#value']."') {this.value = '';}" );
  unset($variables['form']['search_block_form']['#printed']);

  $variables['search']['search_block_form'] = drupal_render($variables['form']['search_block_form']);

  $variables['search_form'] = implode($variables['search']);
}



function phptemplate_preprocess_block(&$variables){
	
	static $user123_count;
	if($variables['block']->region == 'user123'){
		$user123_count++;
	}
	$variables['user123_count'] = $user123_count;
	
	static $user456_count;
	if($variables['block']->region == 'user456'){
		$user456_count++;
	}
	$variables['user456_count'] = $user456_count;
	
	static $user789_count;
	if($variables['block']->region == 'user789'){
		$user789_count++;
	}
	$variables['user789_count'] = $user789_count;
	
	static $bottom123_count;
	if($variables['block']->region == 'bottom123'){
		$bottom123_count++;
	}
	$variables['bottom123_count'] = $bottom123_count;
	
	static $showcase123_count;
	if($variables['block']->region == 'showcase123'){
		$showcase123_count++;
	}
	$variables['showcase123_count'] = $showcase123_count;

}


function solarsentinel_preprocess_maintenance_page(&$vars) {
		phptemplate_preprocess_page($vars);
	}

function solarsentinel_preprocess_node(&$variables)
{
	$node = $variables['node'];
	if($node->type == "webform")
	{
		$variables['title'] = t($node->title);
	}
	if($node->type == "insurance")
	{
		$variables['title'] = t($node->title);
	}
	
}
function phptemplate_preprocess_page(&$variables) {
	$node = $variables['node'];
	
	//Get the body class
	
	global $language;

	if( isset( $_COOKIE['ss_preset_style'] ) )
		$this_preset_style = $_COOKIE['ss_preset_style']; 
	else
		$this_preset_style = theme_get_setting('preset_style');
	
	// Get related values based on selected preset style
	$rt_style_includes = path_to_theme() . "/styles.php";
	include $rt_style_includes;

	$style = $stylesList[$this_preset_style];
	
	
	if( isset( $_COOKIE['ss_fontsize'] ) )
		$this_fontsize = $_COOKIE['ss_fontsize']; 
	else
		$this_fontsize = theme_get_setting('default_font');
	
	
	if( isset( $_COOKIE['ss_bg_style'] ) )
		$this_bg_style = $_COOKIE['ss_bg_style']; 
	else
		$this_bg_style = $style[2];
	
	
	$variables["body_classes"] = 'f-' . $this_fontsize;
	$variables["body_classes"] .= ' ' . $this_preset_style;		
	$variables["body_classes"] .= ' ' . $this_bg_style;
	$variables["body_classes"] .= ' iehandle';
	$variables["body_classes"] .= ' '.$language->language;
 	
	if (isset($node)) {
		$variables["body_classes"] .= " full-node ";                                           // Page is one full node
		$variables["body_classes"] .= " ".((($node->type == 'forum') || (arg(0) == 'forum')) ? 'forum' : '');     // Page is Forum page
		$variables["body_classes"] .= " ".(($node->type) ? 'node-type-'. $node->type : '');               // Page has node-type-x, e.g., node-type-page
		$variables["body_classes"] .= " ".(($node->nid) ? 'nid-'. $node->nid : '');                       // Page has id-x, e.g., id-32
	} 
	
  $body_classes = array();
  $body_classes[] = 'layout-'. (($variables['left']) ? 'left-main' : 'main') . (($variables['right']) ? '-right' : '');  // Page sidebars are active (Jello Mold)
  $body_classes[] = ($variables['is_admin']) ? 'admin' : 'not-admin';                                    // Page user is admin
  $body_classes[] = ($variables['logged_in']) ? 'logged-in' : 'not-logged-in';                           // Page user is logged in
  $body_classes[] = ($variables['is_front']) ? 'front' : 'not-front';                                    // Page is front page
  if (isset($variables['node'])) {
    $body_classes[] = ($variables['node']) ? 'full-node' : '';                                           // Page is one full node
    $body_classes[] = (($variables['node']->type == 'forum') || (arg(0) == 'forum')) ? 'forum' : '';     // Page is Forum page
    $body_classes[] = ($variables['node']->type) ? 'node-type-'. $variables['node']->type : '';               // Page has node-type-x, e.g., node-type-page
    $body_classes[] = ($variables['node']->nid) ? 'nid-'. $variables['node']->nid : '';                       // Page has id-x, e.g., id-32
  }
  else {
    $body_classes[] = (arg(0) == 'forum') ? 'forum' : '';                                           // Page is Forum page
  }
  
  $body_classes = array_filter($body_classes);                                                      // Remove empty elements
  $variables['body_classes'] .= " ".implode(' ', $body_classes);   
	
	//$theme_settings = variable_get('theme_solarsentinel_settings', array());
	
	$variables['path'] = base_path() . path_to_theme();
	$css_path = path_to_theme().'/css/';
	$js_path = path_to_theme() . '/js/';
	$variables['file_path'] = base_path().file_directory_path();
	$variables['default_color'] = theme_get_setting('default_color');
	$variables['url'] = "http://" . $_SERVER['HTTP_HOST'] . url();
    $variables['uri'] = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $variables['tabs2'] = menu_secondary_local_tasks();
    
    $variables['show_date1'] = theme_get_settings(show_date);
	
	
	// Set the default logo
	if (theme_get_setting('default_logo')){
		$variables['logo'] = $variables['path'].'/images/logo.png';
	}
	
	// Set preset_style
	if( isset( $_COOKIE['ss_preset_style'] ) )
		$variables['ss_preset_style'] = $_COOKIE['ss_preset_style']; 
	else
		$variables['ss_preset_style'] = theme_get_setting(preset_style);
	
	// Get related values based on selected preset style
	$rt_style_includes = path_to_theme() . "/styles.php";
	include $rt_style_includes;

	$style = $stylesList[$variables['ss_preset_style']];
	
	// Set related values 
	if( isset( $_COOKIE['ss_header_style'] ) )
		$variables['ss_header_style'] = $_COOKIE['ss_header_style']; 
	else
		$variables['ss_header_style'] = $style[0];
		
	if( isset( $_COOKIE['ss_body_style'] ) )
		$variables['ss_body_style'] = $_COOKIE['ss_body_style']; 
	else
		$variables['ss_body_style'] = $style[1];	
		
	if( isset( $_COOKIE['ss_bg_style'] ) )
		$variables['ss_bg_style'] = $_COOKIE['ss_bg_style']; 
	else
		$variables['ss_bg_style'] = $style[2];	
	
	if( isset( $_COOKIE['ss_footer_style'] ) )
		$variables['ss_footer_style'] = $_COOKIE['ss_footer_style']; 
	else
		$variables['ss_footer_style'] = $style[3];	
		
	if( isset( $_COOKIE['ss_primary_color'] ) )
		$variables['ss_primary_color'] = $_COOKIE['ss_primary_color']; 
	else
		$variables['ss_primary_color'] = $style[4];	
	

	
	if( isset( $_COOKIE['ss_fontsize'] ) )
		$variables['ss_fontsize'] = $_COOKIE['ss_fontsize']; 
	else
		$variables['ss_fontsize'] = theme_get_setting('default_style');
	
	// set global for menu style if exists
	if( isset( $_COOKIE['ss_menu_type'] ) )
		$variables['ss_menu_type'] = $_COOKIE['ss_menu_type']; 
	else
		$variables['ss_menu_type'] = theme_get_setting('m_type');
		
		
	if($variables['ss_menu_type'] == "moomenu" or $variables['ss_menu_type'] == "suckerfish"){
		drupal_add_css($css_path . 'rokmoomenu.css', 'theme', 'all', FALSE);	
	}

	
	$theme_variation = theme_get_setting('default_theme');
	
	

$enable_ie6_warning = theme_get_setting('enable_ie6_warning');

if ($enable_ie6_warning){
	drupal_add_js($js_path.'rokie6warn.js', 'theme');
}



if ($variables['ss_menu_type']=="moomenu"){
	$moomenu_bgiframe = theme_get_setting('moomenu_bgiframe');	
	$moomenu_delay = theme_get_setting('moomenu_delay');
	$moomenu_duration = theme_get_setting('moomenu_duration');
	$moomenu_transition = theme_get_setting('moomenu_transition');
	
	//drupal_add_js($js_path.'mootools.js', 'theme');
	//drupal_add_js($js_path.'rokmoomenu.js', 'theme');
	//drupal_add_js($js_path.'mootools.bgiframe.js', 'theme');
	
	
	$inline_js = 'window.addEvent("domready", function() {
		new Rokmoomenu("ul.menutop", {

			bgiframe: '.$moomenu_bgiframe.',
			delay: '.$moomenu_delay.',
			vehore: true,
			animate: {
				props: ["height"],
				opts: {
					duration:'.$moomenu_duration.',
					transition: Fx.Transitions.'.$moomenu_transition.'
				}
			},
    		bg: {
    			enabled: true,
    			overEffect: {
    				duration: 500,
    				transition: Fx.Transitions.Sine.easeOut
    			},
    			outEffect: {
    				duration: 600,
    				transition: Fx.Transitions.Sine.easeOut
    			}
    		},
    		submenus: {
    			enabled: true,
    			opacity: 0.9,
    			overEffect: {
    				duration: 50,
    				transition: Fx.Transitions.Expo.easeOut
    			},
    			outEffect: {
    				duration: 200,
    				transition: Fx.Transitions.Sine.easeIn
    			},
    			offsets: {
    				top: 3,
    				right: 1,
    				bottom: 0,
    				left: 1
    			}
    		}

		});
	});';
	

	//drupal_add_js($inline_js, 'inline');
	
	
} else {	
	drupal_set_html_head(
		'<!--[if IE]>		
	  <script type="text/javascript" src="'.$js_path.'ie_suckerfish.js"></script>
	  <![endif]-->'
	);
	
	$inline_js = 'window.addEvent("domready", function() {
	if (window.ie6) {
	 var mainShadow = $("main-shadow"), featuredShadow = $("featured-shadow");
	 if (!mainShadow || !featuredShadow) return false;
	 else {
	  window.addEvent("resize", function() {
	   var size = mainShadow.getPosition().x;
	   featuredShadow.setStyle("margin-left", size - 63);
	  });
	  window.fireEvent("resize");
	 }
	};
	});';
		
	//drupal_add_js($inline_js, 'inline');
		
	}
	
	//drupal_add_js($js_path.'featured-modules.js', 'theme');
	
//	$variables['scripts'] = drupal_get_js();
	//$variables['scripts'] = "";
	//$variables['head'] = drupal_get_html_head();
	$variables['styles'] = drupal_get_css();
	


	
	// get widths for block regions
	
	$block_regions = array('user123', 'user456', 'user789', 'bottom123', 'showcase123');
	
	$block_region_widths = array(
		1 => 'w99',
		2 => 'w49',
		3 => 'w33',
		4 => 'w24'
	);
	
 	foreach($block_regions as $block_region){
		$blocks = block_list($block_region);	
		$variables[$block_region.'_width'] = ($block_region_widths[count($blocks)] ? $block_region_widths[count($blocks)] : $block_region_widths[4]);
	} 
	

	if (strpos(request_uri(), 'wrapper') != false){
		$variables['template_file'] = 'page-wrapper';
	}

}


//********************************************
// PRIMARY LINK MENU ITEM INFO
//********************************************

/**
 * Returns a rendered menu tree.
 *
 * @param $tree
 *   A data structure representing the tree as returned from menu_tree_data.
 * @return
 *   The rendered HTML of that data structure.
 */
function main_menu_tree_output($tree, $showChild) {
  $output = '';
  $items = array();
  // Pull out just the menu items we are going to render so that we
  // get an accurate count for the first/last classes.
  foreach ($tree as $data) {
    if (!$data['link']['hidden']) {

      $items[] = $data;
			
    }
		
  }

  $num_items = count($items);
  foreach ($items as $i => $data) {
    
    $extra_class = NULL;
    
    if (stristr($i, 'active')) {
        $extra_class .= " active";
      }
    
    
    if ($i == 0) {
      $extra_class .= ' first ';
    }

    if ($i == $num_items - 1) {
      $extra_class .= ' last ';
    }
    $link = main_menu_item_link($data['link']);
    if ($data['below'] AND $showChild == 1) {
      $extra_class .= " parent ";
      $output .= main_menu_item($link, $data['link']['has_children'], sub_menu_tree_output($data['below']), $data['link']['in_active_trail'], $extra_class);
    }
    else {
      $output .= main_menu_item($link, $data['link']['has_children'], '', $data['link']['in_active_trail'], $extra_class);
    }
  }
  return $output ? main_menu_tree($output) : '';
}



function sub_menu_tree_output($tree) {
  $output = '';
  $items = array();

  // Pull out just the menu items we are going to render so that we
  // get an accurate count for the first/last classes.
  foreach ($tree as $data) {
    if (!$data['link']['hidden']) {
      $items[] = $data;
    }
  }

  $num_items = count($items);
  foreach ($items as $i => $data) {
    
    $extra_class = NULL;
    //$extra_class = get_link_color($items[$i]['title']);
    
    if (stristr($i, 'active')) {
        $extra_class .= " active";
      }
    
    
    if ($i == 0) {
      $extra_class .= ' first ';
    }
    if ($i == $num_items - 1) {
      $extra_class .= ' last ';
    }
    $link = sub_menu_item_link($data['link']);
    if ($data['below']) {
      $extra_class .= " parent ";
      $output .= sub_menu_item($link, $data['link']['has_children'], sub_menu_tree_output($data['below']), $data['link']['in_active_trail'], $extra_class);
    }
    else {
      $output .= sub_menu_item($link, $data['link']['has_children'], '', $data['link']['in_active_trail'], $extra_class);
    }
  }
  return $output ? sub_menu_tree($output) : '';
}

/**
 * FULL MENU TREE
 */
function main_menu_tree($tree) {
  	return '<ul class="menutop">'. $tree .'</ul>';
}

/**
 * SUB MENU TREE
 */
function sub_menu_tree($tree) {
  	return '<div class="drop-wrap columns-' . theme_get_setting(menu_columns) . '"><div class="drop1 png"></div><ul class="png columns-' . theme_get_setting(menu_columns) . '">'. $tree .'</ul></div>';
}

/**
  * MENU ITEM 
 */
function main_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  $class = "item";
  $id = "";
  if (!empty($extra_class)) {
    $class .= $extra_class;
  }
  if ($in_active_trail) {
    $class .= ' active';
    $id .= 'current';
  }
  return '<li class="'. $class . '">'. $link . $menu . "</li>\n";
}

/**
  * SUB MENU ITEM 
 */
function sub_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  //$class = ($menu ? 'expanded' : ($has_children ? 'collapsed' : 'leaf'));
  $class = "item c0";
  if (!empty($extra_class)) {
    $class .= $extra_class;
  }
  if ($in_active_trail) {
    $class .= 'active';
  }
  return '<li class="'. $class .'">'. $link . $menu . "</li>\n";
}


/**
 * Generate the HTML output for a single menu link.
 *
 * @ingroup themeable
 */
function main_menu_item_link($link) {

	global $language;
	
  if (empty($link['localized_options'])) {
    $link['localized_options'] = array();
  }
  if(strlen(strstr($link['href'],"http"))>0) {
  	$href = $link['href'];	
  }
  else {
		$href = url(drupal_get_path_alias($link['href']));

  }

  $this_link = "<a class=\"topdaddy link\" href='" . $href . "'><span>" . t($link['title']) . "</span></a>"; 	
  //return l($link['title'], $link['href'], $link['localized_options']);
  return $this_link;
}

 function sub_menu_item_link($link) {
  if (empty($link['localized_options'])) {
    $link['localized_options'] = array();
  }
  if(strlen(strstr($link['href'],"http"))>0) {
  	$href = $link['href'];	
  }
  else {
  	if(variable_get('clean_url', 0)) {
  		$href = $link['href'] == "<front>" ? base_path() : url(drupal_get_path_alias($link['href']));
  	}
  	else {
  		$href = $link['href'] == "<front>" ? base_path() : url(drupal_get_path_alias($link['href']));
  	}
  }	
  $this_link = "<a class=\"link\" href='" . $href . "'><span>" . $link['title'] . "</span></a>"; 	
  //return l($link['title'], $link['href'], $link['localized_options']);
  return $this_link;
}


//******************************************************************************





function change_font($change, $page=''){
	
	$cookie_prefix = "ss_";
	$cookie_time = time()+31536000;
	
	$cookie_name = $cookie_prefix . "fontsize";
	setcookie($cookie_name, $change, $cookie_time);
	
	if ($page){
		drupal_goto("node/$page");
	}
	else {
		drupal_goto('<front>');
	}

}

function solarsentinel_change_theme($change, $changeVal, $page=''){
	
	$theme_settings = variable_get('theme_solarsentinel_settings', array());

	//print_r($theme_settings);
	
	
	if($change && $changeVal){
		//print $change . " " . $changeVal;
		$cookie_prefix = "ss_";
		$cookie_time = time()+31536000;
		
		switch ($change){
			
			case 'fontfamily':
				
				$theme_settings['font_family'] = $changeVal;
			
			break;
			
			case 'tstyle':
				
				$rt_style_includes = path_to_theme() . "/styles.php";
				include $rt_style_includes;
			
				$style = $stylesList[$changeVal];
    			
    			$cookie_name = $cookie_prefix . "preset_style";
				setcookie($cookie_name, $changeVal, $cookie_time);
				
				$cookie_name = $cookie_prefix . "header_style";
				setcookie($cookie_name, $style[0], $cookie_time);
				$cookie_name = $cookie_prefix . "body_style";
				setcookie($cookie_name, $style[1], $cookie_time);
				$cookie_name = $cookie_prefix . "bg_style";
				setcookie($cookie_name, $style[2], $cookie_time);
				$cookie_name = $cookie_prefix . "footer_style";
				setcookie($cookie_name, $style[3], $cookie_time);
				$cookie_name = $cookie_prefix . "primary_color";
				setcookie($cookie_name, $style[4], $cookie_time);
    
    			/*
			    $theme_settings['header_style'] = $style[0];
			    $theme_settings['body_style'] = $style[1];
			    $theme_settings['bg_style'] = $style[2];
				$theme_settings['footer_style'] = $style[3];
			    $theme_settings['primary_color'] = $style[4];
			    */
				
			break;

			case 'mtype':
				$cookie_name = $cookie_prefix . "menu_type";
				setcookie($cookie_name, $changeVal, $cookie_time);
				//$theme_settings['m_type'] = $changeVal;
			
			break;
			
			
			case 'cstyle':
			
				$body_style = $theme_settings['default_style'];
			
				if($changeVal == 'next'){
				
				$body_style ++;
				
					if($body_style > 6){
						$body_style = 1;
					}	
					
				}
				elseif($changeVal == 'prev'){

					$body_style --;

						if($body_style < 1){
							$body_style = 6;
						}	

				}
				else {
					$body_style = $changeVal;
				}

				$theme_settings['default_style'] = $body_style;
			
			
			break;

		}

		// echo $theme_settings[$setting];
	
		variable_set('theme_solarsentinel_settings', $theme_settings);
		
		
	}
	
	 //print_r($theme_settings);
	if ($page){
		drupal_goto("node/$page");
	}
	else {
		drupal_goto('<front>');
	}
	
}



/**
 * Sets the body-tag class attribute.
 */
//function phptemplate_body_class() {
function phptemplate_body_class() {
	global $language;

	if( isset( $_COOKIE['ss_preset_style'] ) )
		$this_preset_style = $_COOKIE['ss_preset_style']; 
	else
		$this_preset_style = theme_get_setting('preset_style');
	
	// Get related values based on selected preset style
	$rt_style_includes = path_to_theme() . "/styles.php";
	include $rt_style_includes;

	$style = $stylesList[$this_preset_style];
	
	
	if( isset( $_COOKIE['ss_fontsize'] ) )
		$this_fontsize = $_COOKIE['ss_fontsize']; 
	else
		$this_fontsize = theme_get_setting('default_font');
	
	
	if( isset( $_COOKIE['ss_bg_style'] ) )
		$this_bg_style = $_COOKIE['ss_bg_style']; 
	else
		$this_bg_style = $style[2];
	
	
	$class = 'f-' . $this_fontsize;
	$class .= ' ' . $this_preset_style;		
	$class .= ' ' . $this_bg_style;
	$class .= ' iehandle';
	$class .= ' '.$language->language;

	
	
  print ' class="' . $class . '"';

}


/**
* Implementation of hook_theme.
*
* Register custom theme functions.
*/
function solarsentinel_theme() {
  return array(
    // The form ID.
    'user_login_block' => array(
      // Forms always take the form argument.
      'arguments' => array('form' => NULL),
    ),
	'user_login_top_section' => array(
    // Forms always take the form argument.
    'arguments' => array(),
  ),
  );
}


function solarsentinel_user_login_block(&$form){

	$form['links'] = array('#value' => '<div id="sl_lostpass"><a href="/user/password">Request new password</a></div>');

	return drupal_render($form);
	
}


function solarsentinel_user_login_top_section(){
	
	global $user;
	
	if(!$user->uid){
	$output = drupal_get_form('user_login_block');	
	}else{
	
	$output = '<div id="greeting">Hi '.$user->name.'</div>';
	$output .=	'<div id="sl_submitbutton">';
	$output .= l('Logout', 'logout', array('attributes' => array('class' => 'button')));	
	$output .= '</div>';	
		
	}
	
	return $output;
	
	
	
}


function solarsentinel_get_theme_headers($theme){
	
	$themes = array (
		2 => 10,
		3 => 2,
		6 => 3
	);

	return $themes[$theme];
	
}




/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function phptemplate_breadcrumb($breadcrumb) {
	global $base_url;
  if (!empty($breadcrumb)) {
    $breadcrumb[] = drupal_get_title();  // full breadcrumb ( ?= 鈥?, ?= &#187; &raquo;)
    return '<div class="breadcrumb">'. implode(' &raquo; ', $breadcrumb) .'</div>';
  }
}

/**
 * Allow themable wrapping of all comments.
 */
function phptemplate_comment_wrapper($content, $node) {
  if (!$content || $node->type == 'forum') {
    return '<div id="comments">'. $content .'</div>';
  }
  else {
    return '<div id="comments"><h2 class="comments">'. t('Comments') .'</h2>'. $content .'</div>';
  }
}



/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}

function phptemplate_comment_submitted($comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

function phptemplate_node_submitted($node) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}







