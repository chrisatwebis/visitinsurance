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
    [title] == Node: Title
    [field_image_fid] == Content: Image (field_image)
    [field_en_note_value] == Content: English Note (field_en_note)
    [field_simp_cn_note_value] == Content: Simplified Chinese Note (field_simp_cn_note)
    [field_trad_cn_note_value] == Content: Traditional Chinese Note (field_trad_cn_note)

<?php foreach ($fields as $id => $field): ?>
  <?php if (!empty($field->separator)): ?>
    <?php print $field->separator; ?>
  <?php endif; ?>

  <?php print $field->wrapper_prefix; ?>
    <?php print $field->label_html; ?>
    <?php print $field->content; ?>
  <?php print $field->wrapper_suffix; ?>
<?php endforeach; ?>
 */
?>


	<?php print $fields["title"]->wrapper_prefix; ?>
		<?php print $fields["title"]->label_html; ?>
		<?php print $fields["title"]->content; ?>
	<?php print $fields["title"]->wrapper_suffix; ?>
	
	<?php print $fields["field_image_fid"]->wrapper_prefix; ?>
		<?php print $fields["field_image_fid"]->label_html; ?>
		<?php print $fields["field_image_fid"]->content; ?>
	<?php print $fields["field_image_fid"]->wrapper_suffix; ?>
	
<?php
	
	global $language;
	switch($language->language)
	{
		case "zh-hans":
			print $fields["field_simp_cn_note_value"]->wrapper_prefix;
			print $fields["field_simp_cn_note_value"]->label_html;
			print $fields["field_simp_cn_note_value"]->content;
			print $fields["field_simp_cn_note_value"]->wrapper_suffix;
			break;
		case "zh-hant":
			print $fields["field_trad_cn_note_value"]->wrapper_prefix;
			print $fields["field_trad_cn_note_value"]->label_html;
			print $fields["field_trad_cn_note_value"]->content;
			print $fields["field_trad_cn_note_value"]->wrapper_suffix;
			break;
		case "en":
			print $fields["field_en_note_value"]->wrapper_prefix;
			print $fields["field_en_note_value"]->label_html;
			print $fields["field_en_note_value"]->content;
			print $fields["field_en_note_value"]->wrapper_suffix;
			break;
	}
	
	
?>