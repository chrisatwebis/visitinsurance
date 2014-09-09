<?php
// $Id: node.tpl.php,v 1.4 2008/01/25 21:21:44 goba Exp $

/**
 * @file node.tpl.php
 *
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $picture: The authors picture of the node output from
 *   theme_user_picture().
 * - $date: Formatted creation date (use $created to reformat with
 *   format_date()).
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $name: Themed username of node author output from theme_user().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $submitted: themed submission information output from
 *   theme_node_submitted().
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $teaser: Flag for the teaser state.
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 */
?>
<?php 
  if($page && ($node->nid != "84")):
?>
<script>


</script>
<?php endif;?>
<div id="node-<?php print $node->nid; ?>" class="node<?php if ($sticky) { print ' sticky'; } ?><?php if (!$status) { print ' node-unpublished'; } ?> clear-block">

<?php //print $picture ?>


	<div class="">
		<div id="page" class="box">
			<div class="article-rel-wrapper box-c">
				<h2 class="contentheading title"><?php print $title ?></h2>
				<?php 
					if($node->nid==84):?>
					<div id="search-step">
						<ul>
							<li class="step1"><span><?php print t("Get Quotes") ?></span></li>
							<li class="step2"><span><?php print t("Compare Plans") ?></span></li>
							<li class="step3"><span><?php print t("Apply Online") ?></span></li>
						</ul>
					</div>
				<?php endif;?>
			  <?php if ($submitted): ?>
			    <span class="submitted"><?php //print $submitted ?></span>
			  <?php endif; ?>
			
			  <?php if ($terms): ?>
			   <div class="terms terms-inline"><?php //print $terms ?></div>
			  <?php endif;?>
				
				<?php 
					global $language;
					switch($language->language)
					{
						case "zh-hans":
							print $node->field_simp_cn_body[0]["value"];
							break;
						case "zh-hant":
							print $node->field_trad_cn_body[0]["value"];
							break;
						case "en":
							print $node->field_en_body[0]["value"];
							break;
					}
					print $content;
					
				?>
	    
	    	</div>
		</div>
	</div>

  <?php //print $links; ?>
</div>