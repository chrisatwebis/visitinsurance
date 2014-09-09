<?php
// $Id$

/**
 * @file
 * Administrative page callbacks for the seochecklist module.
 */

define('SEOCHECKLIST_BOOK_PURCHASE', 'http://www.packtpub.com/drupal-6-search-engine-optimization-seo/mid/170909568gh3?utm_source=volacci.com%2F&utm_medium=affiliate&utm_content=authorsite&utm_campaign=mdb_000690');

/**
 * Define the settings form.
 */
function seochecklist_admin_settings() {
  global $user;
  global $seochecklist_items;
  global $seochecklist_groups;
  
  $seochecklist_items = $seochecklist_groups = array();
  _seochecklist_fill();
  
  $form['save_above'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
	'#weight' => '-100',
  );
  // Fetch modules and groups from database.
  $group_id = 0;

  // Every group is a fieldset.
  foreach ($seochecklist_groups as $group_id=>$data) {
    $form['group_' . $group_id] = array(
      '#type' => 'fieldset',
      '#title' => $data->name,
      '#collapsible' => TRUE,
      '#description' => $data->description,
      '#group' => 'seochecklist',

    );
  }

  $res = db_query('SELECT * FROM seo_checklist');
  while ($row = db_fetch_object($res)) {
    $seochecklist_items[$row->id]->completed = $row->completed;
    $seochecklist_items[$row->id]->uid = $row->uid;
  }
  
  foreach ($seochecklist_items as $row) {
    $row->links = array();
    if (@$row->description) {
      $row->links[] = $row->description;
    }

    if (@$row->completed) {
      // Show when and who completed this task.
      $row->links[] = t('Completed on %date by !username', array('%date' => format_date($row->completed, 'small'), '!username' => theme('username', user_load($row->uid))));
    }
    else {
      $row->completed = 0;
      // Show non-completed sub-tasks.
      if ($row->download) {
        $row->links[] = l(t('Download'), $row->download, array('attributes' => array('target' => '_blank')));
      }
      if ($row->enable) {
        $row->links[] = l(t('Enable'), $row->enable);
      }
    }

    if ($row->configure && (!$row->module || module_exists($row->module))) {
      // Show the link to configure if this isn't a module or module is enabled.
      $row->links[] = l(t('Configure'), $row->configure);
    }

    if (variable_get('seo_checklist_book_references', 1) && $page = seochecklist_get_book_references($row->id)) {
      $row->links[] = t('See <a href="@book-purchase">Drupal 6 SEO</a> p. @page', array('@page' => $page, '@book-purchase' => SEOCHECKLIST_BOOK_PURCHASE));
    }

    $form['group_' . $row->group_id]['seochecklist_task_' . $row->id] = array(
      '#type' => 'checkbox',
      '#title' => t($row->name),
      '#default_value' => $row->completed || ($row->module && module_exists($row->module)),
      '#description' => join(' | ', $row->links),
    );
    if (strpos($row->name, 'Clean URLs') === 0) {
      $form['group_' . $row->group_id]['seochecklist_task_' . $row->id]['#disabled'] = !variable_get('clean_url', 0);
      $form['group_' . $row->group_id]['seochecklist_task_' . $row->id]['#default_value'] |= variable_get('clean_url', 0);
    }
  }

  $form['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
    '#weight' => 100,
  );

  if (module_exists('vertical_tabs')) {
    $form['#pre_render'][] = 'vertical_tabs_form_pre_render';
    $form['save_above']['#attributes']['class'] = 'js-hide';
  }
  else {
    drupal_set_message(t('Your SEO Checklist interface (in addition to your content and content type edit pages) will be greatly enhanced by installing the <a href="@vertical-tabs">Vertical Tabs module</a>.', array('@vertical-tabs' => 'http://drupal.org/project/vertical_tabs')), 'status', FALSE);
  }
  
  return $form;
}

/**
 * Submit callback for seochecklist_admin_settings().
 */
function seochecklist_admin_settings_submit($form, &$form_state) {
  global $user;
  $count = 0;
  foreach ($form_state['values'] as $key => $value) {
    if (preg_match('/seochecklist_task_/', $key)) {
      $key = explode('_', $key);
      $key = $key[2];
      $current = db_result(db_query("SELECT completed FROM {seo_checklist} WHERE id = %d", $key));
      
      if ($current === FALSE) {
        db_query("INSERT INTO {seo_checklist} (completed, uid, id) VALUES (%d, %d, %d)", $value ? time() : 0, $user->uid, $key);
        if ($value == TRUE) {
          $count++;
        }
      } 
      elseif ((bool)$current != $value) {
        // If the checkbox changed states, update the record.
        db_query("UPDATE {seo_checklist} SET completed = %d, uid = %d WHERE id = %d", $value ? time() : 0, $user->uid, $key);
        $count++;
      }
    }
  }

  variable_set('seo_checklist_link', $form_state['values']['seochecklist_task_66']);
  variable_set('seo_checklist_book_references', $form_state['values']['seochecklist_task_69']);
  
  drupal_set_message(format_plural($count, 'Updated @count task successfully.', 'Updated @count tasks successfully.'));
}



function _seochecklist_item_insert($id, $name, $module = '', $options = array()) {
  global $seochecklist_items;
  global $seochecklist_groups;
  
  if (is_array($name)) {
    $t = array_shift($name);
    $name_text = t($t, $name);
  } 
  else {
    $name_text = t($name);
  }

  if ($module) {
    if (!isset($options['download'])) {
      $options['download'] = 'http://drupal.org/project/' . $module;
    }
    if (!isset($options['enable'])) {
      $options['enable'] = 'admin/build/modules';
    }
  }
  
  $description_text = '';
  if (isset($options['description'])) {
    if (is_array($options['description'])) {
      $t = array_shift($options['description']);
      $description_text = t($t, $options['description']);
    } 
    else {
      $description_text = t($options['description']);
    }
  }
  
  $group_id = count($seochecklist_groups) - 1;
  $seochecklist_items[$id] = (object)array(
      'id' => $id,
      'group_id' => $group_id,
      'name' => $name_text,
      'module' => $module,
      'download' => @$options['download'],
      'enable' => @$options['enable'],
      'configure' => @$options['configure'],
      'description' => $description_text,
  );
}

function _seochecklist_group_insert($name, $description = '') {
  global $seochecklist_groups;
  
  if (is_array($name)) {
    $t = array_shift($name);
    $name_text = t($t, $name);
  } 
  else {
    $name_text = t($name);
  }

  if (is_array($description)) {
    $t = array_shift($description);
    $description_text = t($t, $description);
  } 
  else {
    $description_text = t($description);
  }
  
  $seochecklist_groups[] = (object)array(
      'name' => $name_text,
      'description' => $description_text,
  );
}

function _seochecklist_fill() {
    // Page title tasks
  _seochecklist_group_insert('Page Titles', 'The single most important thing you can do for on-site SEO.');
  _seochecklist_item_insert(41, 'Token (required for other modules to function. Warning: Use beta modules at your own risk.)', 'token');
  _seochecklist_item_insert(1, 'Page Title Module. Warning: Use beta modules at your own risk.', 'page_title', array('configure' => 'admin/content/page_title'));

  // URL tasks
  _seochecklist_group_insert('URL paths', 'The second most important thing you can do.');
  _seochecklist_item_insert(2, 'Clean URLs - Activate (Usually automatic. Please double-check!)', '', array('configure' => 'admin/settings/clean-urls'));
  _seochecklist_item_insert(5, 'Pathauto Module', 'pathauto', array('configure' => 'admin/build/path/pathauto'));
  _seochecklist_item_insert(6, 'Global Redirect Module', 'globalredirect');
  _seochecklist_item_insert(38, 'Path Redirect Module', 'path_redirect');

  // Search engine account tasks
  _seochecklist_group_insert('Create Search Engine Accounts', 'Set yourself up with the search engines.');
  _seochecklist_item_insert(7, array('Get a Google Account - You will need this for several of the steps that follow - <a href="@url">@url</a>', '@url' => 'https://www.google.com/accounts/NewAccount'));
  _seochecklist_item_insert(8, array('Get a Yahoo Account - You will need this for steps that follow - <a href="@url">@url</a>', '@url' => 'http://www.yahoo.com/r/m7'));
  _seochecklist_item_insert(44, array('Get a Windows Live ID - You will need this for steps that follow - <a href="@url">@url</a>', '@url' => 'https://signup.live.com/'));

  // Visitor tracking tasks
  _seochecklist_group_insert('Track your visitors', 'Know where your visitors are coming from and what they do while visiting your site.');
  _seochecklist_item_insert(9, 'Google Analytics Module', 'googleanalytics', array('download' => 'http://drupal.org/project/google_analytics', 'configure' => 'admin/settings/googleanalytics'));
  _seochecklist_item_insert(10, array('Sign in to your Google Analytics Account - <a href="@url">@url</a>', '@url' => 'http://www.google.com/analytics'));
  _seochecklist_item_insert(11, 'Create an Analytics for your website');
  _seochecklist_item_insert(12, 'Paste Google Analytics code into Google Analytics Module');
  _seochecklist_item_insert(13, 'Authenticate your site with Google Analytics');
  // _seochecklist_item_insert(63, 'Google Analytics Tokenizer', 'ga_tokenizer');
  // _seochecklist_item_insert(64, 'Google Analytics Contact Form, Webform, Rules Email. Warning: Use beta modules at your own risk.', 'contact_google_analytics', array('configure' => 'admin/config/contact-google-analytics'));
  // _seochecklist_item_insert(65, 'Context Keywords', 'context_keywords');

  // Page content tasks
  _seochecklist_group_insert('Page content', 'Take control of your page content.');
  _seochecklist_item_insert(15, 'Meta Tags Module (AKA Nodewords)', 'nodewords', array('configure' => 'admin/content/nodewords'));
  
  //_seochecklist_item_insert(71, 'Meta tags', 'metatag');
  //_seochecklist_item_insert(15, 'Meta tags quick', 'metatags_quick', array('configure' => 'admin/structure/metatags_quick'));
  _seochecklist_item_insert(16, 'Scheduler Module', 'scheduler', array('configure' => 'admin/settings/scheduler'), 3);
  _seochecklist_item_insert(17, 'HTML Purifier Module', 'htmlpurifier', array('configure' => 'admin/settings/filters/1'));
  _seochecklist_item_insert(45, array('<a href="/sites/all/modules/htmlpurifier/INSTALL.txt">READ THE INSTALL INSTRUCTIONS!</a> then Download HTML Purifier. You will need
    3.1.0rc1 or later. - <a href="@url">@url</a>', '@url' => 'http://htmlpurifier.org/'));
  _seochecklist_item_insert(18, 'Search 404 Module', 'search404', array('configure' => 'admin/settings/search404'));
  
  // _seochecklist_item_insert(57, 'SEO Compliance Checker', 'seo_checker', array('configure' => 'admin/config/content/seo_checker'));
  // _seochecklist_item_insert(62, 'Read More Link. Warning: Use DEV modules at your own risk.', 'read_more', array('configure' => 'admin/config/content/read_more'));
            
  
  // Source code tasks
  _seochecklist_group_insert('Clean code', 'Well written markup is very important to the search engine spiders.');
  _seochecklist_item_insert(19, array('Validate your site - <a href="@url">@url</a>', '@url' => 'http://validator.w3.org/'));
  _seochecklist_item_insert(20, array('Check your links - <a href="@url">@url</a>', '@url' => 'http://validator.w3.org/checklink'));

  // XML sitemap tasks
  _seochecklist_group_insert('Submit your Site to the search engines.', 'Now that you\'ve got your site ready for the search engines, tell them about it!');
  _seochecklist_item_insert(47, 'Site Verification Module', 'site_verify');
  _seochecklist_item_insert(21, 'XML Sitemap Module', 'xmlsitemap', array('configure' => 'admin/settings/xmlsitemap'));
  _seochecklist_item_insert(46, 'Site Map Module - a plain text sitemap', 'site_map', array('configure' => 'admin/settings/sitemap'));
  _seochecklist_item_insert(22, array('Login to Google Webmaster Tools - <a href="@url">@url</a>', '@url' => 'http://www.google.com/webmasters/tools'));
  _seochecklist_item_insert(23, 'Authenticate your site with Google (page 26)');
  _seochecklist_item_insert(24, array('Submit your XML Sitemap to Google - <a href="@url">@url</a>', '@url' => 'http://www.google.com/webmasters/sitemaps/'));
  _seochecklist_item_insert(25, array('Login to Yahoo Site Explorer Account - <a href="@url">@url</a>', '@url' => 'https://siteexplorer.search.yahoo.com/'));
  _seochecklist_item_insert(26, 'Authenticate your site with Yahoo');
  _seochecklist_item_insert(27, array('Submit your XML Sitemap to Yahoo - <a href="@url">@url</a>', '@url' => 'https://siteexplorer.search.yahoo.com/submit'));
  _seochecklist_item_insert(42, array('Login to Bing - <a href="@url">@url</a>', '@url' => 'http://www.bing.com/webmaster/'));
  _seochecklist_item_insert(43, 'Authenticate your site with Bing');
  _seochecklist_item_insert(28, array('Submit your XML Sitemap to Bing - <a href="@url">@url</a>', '@url' => 'http://www.bing.com/webmaster/submitsitepage.aspx'));
  _seochecklist_item_insert(29, array('If appropriate, submit your company to Google Local Business Center - <a href="@url">@url</a>', '@url' => 'https://www.google.com/local/add/login'));

  // Social links tasks
  _seochecklist_group_insert('Social Media', 'Using Social news sites, blogs, etc? Consider these:');
  //_seochecklist_item_insert(30, 8, 'Digg This Module', 'diggthis', 'http://drupal.org/project/diggthis', 'admin/modules', 'admin/settings/diggthis', 1);
  _seochecklist_item_insert(30, 'AddThis Module', 'addthis');
  _seochecklist_item_insert(31, 'Service Links Module', 'service_links', array('configure' => 'admin/settings/service_links'));
  _seochecklist_item_insert(32, 'Trackback Module', 'trackback', array('configure' => 'admin/settings/trackback'));
  _seochecklist_item_insert(48, 'Activity Stream Module', 'activitystream');
  _seochecklist_item_insert(49, 'Add to Any Module', 'addtoany');
  //_seochecklist_item_insert(58, 'Facebook social plugins integration. Warning: Use DEV modules at your own risk.', 'fb_social', array('configure' => 'admin/config/fb_social'));
  //_seochecklist_item_insert(59, 'Follow', 'follow', array('configure' => 'admin/config/services/follow'));
  _seochecklist_item_insert(74, 'SEO Friend Module', 'seo_friend');
  _seochecklist_item_insert(75, 'Pingback Module', 'pingback');
  
  

  // Spam tasks
  _seochecklist_group_insert('Protect your site from Spam', 'If your site will get heavy use from visitors creating accounts, commenting and/or creating content then consider these. NOTE: Most sites just need Mollom. The other modules are here in case Mollom does not work for you for some reason.');
  _seochecklist_item_insert(40, array('HIGHLY RECOMMENDED: Sign up for Mollom\'s free service and get Mollom code - <a href="@url">@url</a>', '@url' => 'http://mollom.com/user/register'));
  _seochecklist_item_insert(39, 'HIGHLY RECOMMENDED: Mollom Module', 'mollom', array('configure' => 'admin/settings/mollom'));
  _seochecklist_item_insert(33, 'Captcha Module', 'captcha', array('configure' => 'admin/user/captcha'));
  _seochecklist_item_insert(34, 'Akismet Module', 'akismet', array('configure' => 'admin/settings/akismet'));
  _seochecklist_item_insert(36, 'Spam Module', 'spam', array('configure' => 'admin/settings/spam'));

  // Geographic tasks
  _seochecklist_group_insert('Geographic', '');
  _seochecklist_item_insert(50, 'hCard Module', 'hcard');
  _seochecklist_item_insert(51, 'Use the Meta Tags module to add geo meta tags to your site.');

  // Optional (but helpful) tasks
  _seochecklist_group_insert('Optional (but helpful)', '');
  _seochecklist_item_insert(52, 'Vertical Tabs Module', 'vertical_tabs');
  _seochecklist_item_insert(53, 'Administration Menu', 'admin_menu');
  // _seochecklist_item_insert(60, 'Elements', 'elements');
  // _seochecklist_item_insert(61, 'Security Review. Warning: Use DEV modules at your own risk.', 'security_review', array('configure' => 'admin/reports/security-review'));
                
  // Performance tasks
  _seochecklist_group_insert('Performance', '');
  _seochecklist_item_insert(54, 'Turn on Drupal\'s built in caching.', '', array('configure' => 'admin/settings/performance/default'));
  _seochecklist_item_insert(55, 'Boost Module', 'boost', array('configure' => 'admin/settings/performance/boost'));
  _seochecklist_item_insert(56, 'Authcache Module', 'authcache');

  _seochecklist_group_insert('Extras', '');
  _seochecklist_item_insert(73, array('Download free internet marketing whitepapers from Volacci - <a href="@url">@url</a>', '@url' => 'http://www.volacci.com/free-whitepapers'));
  _seochecklist_item_insert(66, 'Link to Volacci to thank them for this awesome module.', '', array('description' => 'A small link will appear at the very bottom of your website. You can disable it at any time by un-checking this box. We really appreciate it!'));
  _seochecklist_item_insert(67, 
          array('Send us feedback on the Drupal 6 SEO Checklist module or just say <em>Thanks!</em> and we will link to you from our website. Send your feedback and link information to <a href="mailto:@email">@email</a>.', '@email' => 'seochecklist@volacci.com'), '',
          array('description' => array('If you do not know why you should link with other websites, read the following article: <a href="@link">Why links help SEO</a>.', '@link' => 'http://www.volacci.com/why-links-help-seo')));
  _seochecklist_item_insert(68, array('Listen to the <a href="@podcast">Volacci Drupal SEO Podcast</a> for more tips and tricks about Drupal SEO.', '@podcast' => 'http://www.volacci.com/podcast'));
  _seochecklist_item_insert(69, 
          array('Include page number references from the <a href="@book">Drupal 6 SEO Book</a> by Ben Finklea.', '@book' => 'http://www.drupalseobook.com/'), '',
          array('description' => array('<a href="@book-purchase">Purchase from Packt Publishing</a>', '@book-purchase' => SEOCHECKLIST_BOOK_PURCHASE)));
  _seochecklist_item_insert(70, array('Buy Drupal 6 Search Engine Optimization by Ben Finklea from <a href="@amazon">Amazon</a> or <a href="@packt">Packt</a>.', '@amazon' => 'http://www.amazon.com/gp/product/1847198228?ie=UTF8&tag=dvdcentral02&linkCode=as2&camp=1789&creative=390957&creativeASIN=1847198228', '@packt' => 'https://www.packtpub.com/drupal-6-search-engine-optimization-seo/book?mid/170909568gh3'));
  _seochecklist_item_insert(72, 'Module Filter', 'module_filter', array('configure' => 'admin/config/user-interface/modulefilter'));
   

}