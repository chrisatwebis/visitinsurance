<?php
/**
 * @file views-view-fields.tpl.php
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates

 <?php foreach ($fields as $id => $field): ?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>

  <?php print $field->wrapper_prefix; ?>
    <?php print $field->label_html; ?>
    <?php print $field->content; ?>
  <?php print $field->wrapper_suffix; ?>
<?php endforeach; ?>

    [picture] == User: Picture
    [uid] == User: Uid
    [name] == User: Name
    [mail] == User: E-mail
    [access] == User: Last access
    [login] == User: Last login
    [language] == User: Language
    [field_given_name_value] == Content: Given name (field_given_name)
    [field_surname_value] == Content: Surname (field_surname)
    [field_user_sex_value] == Content: Sex (field_user_sex)
    [field_phone_number_value] == Content: Phone Number (field_phone_number)
    [field_user_address_lid] == Content: Address (field_user_address)

 */
?>
<?php
	
	$left = array(
					"name"=>$fields["name"],
					"field_given_name_value"=>$fields["field_given_name_value"],
					"field_surname_value"=>$fields["field_surname_value"],
					
					"mail"=>$fields["mail"],
					"field_user_address_lid"=>$fields["field_user_address_lid"]
				);
	$right = array(
					"uid" => $fields["uid"],
					"field_user_sex_value"=>$fields["field_user_sex_value"],
					"access"=>$fields["access"],
					"login"=>$fields["login"],
					"language"=>$fields["language"],
					"field_phone_number_value"=>$fields["field_phone_number_value"],
					
				);
?>
<div class="user_page_info_wrapper">
	<div class="res_gre_bias_tit">
		<b class="gre_v_line1"></b><b class="gre_v_line2"></b>
		<h3 class="res_gre_bias_main"><?php print t("Basic Info");?></h3>
		<i class="res_gre_bias_cor"></i>
	</div>
	<div class="user_page_info">
		<div class="avatar">
			<?php print $fields["picture"]->content;?>
		</div>
		<div class="col_left column">
			<?php foreach ($left as $id => $field):?>
				<?php print $field->wrapper_prefix; ?>
					<?php print $field->label_html; ?>
					<?php print $field->content; ?>
				<?php print $field->wrapper_suffix; ?>
			<?php endforeach; ?>
		</div>
		<div class="col_right column">
			<?php foreach ($right as $id => $field):?>
				<?php print $field->wrapper_prefix; ?>
					<?php print $field->label_html; ?>
					<?php print $field->content; ?>
				<?php print $field->wrapper_suffix; ?>
			<?php endforeach; ?>
		</div>
		
	</div>
</div>
