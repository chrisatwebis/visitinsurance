<?php

/**
 * @file user-profile.tpl.php
 * Default theme implementation to present all user profile data.
 *
 * This template is used when viewing a registered member's profile page,
 * e.g., example.com/user/123. 123 being the users ID.
 *
 * By default, all user profile data is printed out with the $user_profile
 * variable. If there is a need to break it up you can use $profile instead.
 * It is keyed to the name of each category or other data attached to the
 * account. If it is a category it will contain all the profile items. By
 * default $profile['summary'] is provided which contains data on the user's
 * history. Other data can be included by modules. $profile['user_picture'] is
 * available by default showing the account picture.
 *
 * Also keep in mind that profile items and their categories can be defined by
 * site administrators. They are also available within $profile. For example,
 * if a site is configured with a category of "contact" with
 * fields for of addresses, phone numbers and other related info, then doing a
 * straight print of $profile['contact'] will output everything in the
 * category. This is useful for altering source order and adding custom
 * markup for the group.
 *
 * To check for all available data within $profile, use the code below.
 * @code
 *   print '<pre>'. check_plain(print_r($profile, 1)) .'</pre>';
 * @endcode
 *
 * Available variables:
 *   - $user_profile: All user profile data. Ready for print.
 *   - $profile: Keyed array of profile categories and their items or other data
 *     provided by modules.
 *
 * @see user-profile-category.tpl.php
 *   Where the html is handled for the group.
 * @see user-profile-item.tpl.php
 *   Where the html is handled for each item in the group.
 * @see template_preprocess_user_profile()
 */
?>
<?php
	drupal_add_css(drupal_get_path("module","stb_user_profile")."/includes/css/stb_user_profile.css");
?>
<div class="profile custom_profile">
	
	<div class="user_basic_info_wrapper">
	<?php
		$tem_view = views_get_view('user_page');
		$display = $tem_view->execute_display( 'block_1');
		print ($display["content"]);
	?>
	</div>
	<div class="res_gre_bias_tit">
		<b class="gre_v_line1"></b><b class="gre_v_line2"></b>
		<h3 class="res_gre_bias_main"><?php print t("The insured you have");?></h3>
		<i class="res_gre_bias_cor"></i>
	</div>
	<div class="owners_stores_wrapper">
	<?php
		$tem_view = views_get_view('insurance_related');
		$display = $tem_view->execute_display( 'the_insured_by_buyer_uid');
		print ($display["content"]);
	?>
	</div>
	<div class="res_gre_bias_tit">
		<b class="gre_v_line1"></b><b class="gre_v_line2"></b>
		<h3 class="res_gre_bias_main"><?php print t("The policies you hold");?></h3>
		<i class="res_gre_bias_cor"></i>
	</div>
	<div class="owners_stores_wrapper">
	<?php
		$tem_view = views_get_view('insurance_related');
		$display = $tem_view->execute_display( 'get_policy_by_buyer_uid');
		print ($display["content"]);
	?>
	</div>
	
</div>