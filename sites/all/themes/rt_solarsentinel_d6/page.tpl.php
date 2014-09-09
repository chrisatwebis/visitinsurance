
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?php print $head; ?>
		<title><?php print $head_title ?></title>

		<?php
				$rt_utils_includes = path_to_theme() . "/rt_utils.php";
				include $rt_utils_includes;
				$head_includes = path_to_theme() . "/rt_head_includes.php";
				include $head_includes;
				$style_switcher = path_to_theme() . "/rt_styleswitcher.php";
				include $style_switcher;
				
				
				print $styles;
				print $scripts;
			
			
		?>
		
		<!--[if IE 7]>
		<link href="<?php echo base_path() . path_to_theme(); ?>/css/template_ie7.css" rel="stylesheet" type="text/css" />	
		<![endif]-->	
		
		<!--[if lte IE 6]>
		<link href="<?php echo base_path() . path_to_theme(); ?>/css/template_ie6.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo base_path() . path_to_theme(); ?>/js/DD_belatedPNG.js"></script>
		<script>
		    DD_belatedPNG.fix('.png');
		</script>
		<![endif]-->
		<?php
			global $user;
			
			if($user->uid != 1 && false)
			{
				
			
		?>
		<script type="text/javascript" language="javascript">
			$(document).ready(function()
			{
			   $(document).bind("contextmenu",function(e){
					  return false;
			   });
			}); 
		</script>
		<?php
			}
		?>
		
		<script type="text/javascript">var switchTo5x=true;</script>
		<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
		<script type="text/javascript" src="http://s.sharethis.com/loader.js"></script>
		
	</head>
	<body id="<?php print 'ff-' . theme_get_setting('font_family');?>" class="<?php print $body_classes; ?>" >	
		
		<div id="page-bg">
			<div class="wrapper">
				<div id="body-left" class="png"><div id="body-right" class="png">
				<!--Begin Top Bar-->
				
				<?php if ($topleft or $login or $topright or $syndicate): ?>
				<div id="top-bar">
					<div class="topbar-strip">
						
						<div class="date-block">
							<div id="site-contact">
							<?php print t("Call us at <code>514-606-6767, 1-866-850-7890</code>(Free Toll) or <a href='@url'>Contact Us</a>", array("@url"=>url("webform/contact-us")));?>
							</div>
						</div>

						<?php if ($syndicate AND $is_front) : ?>
						<div class="syndicate-module">
							<?php print $syndicate; ?>
						</div>
						<?php endif; ?>

						<div class="quick_login">
						<?php if (!$user->uid) : ?>
							<a href="<?php print url("user/login",array('query' => drupal_get_destination() ));?>" id="lock-button" class="login"><span><?php print t("Sign in");?></span></a> | <a href="<?php print url("user/register",array('query' => drupal_get_destination()) );?>" id="signup" title="Create new account"><span><?php print t("Sign up");?></span></a>
						<?php else : ?>
							<a href="<?php print url("user");?>" id="lock-button"><span><?php print t("My account");?></span></a> | <a href="<?php print url( "logout",array('query' => drupal_get_destination()) );?>" id="signup"><span><?php print t("Log out");?></span></a>
						<?php endif; ?>
						</div>
						
				
					</div>
					<?php if ($topleft) : ?>
					<div class="topbar-left-mod">
						
							<?php print $topleft; ?>
					
					</div>
					<?php endif; ?>
					<?php if ($topright) : ?>
					<div class="topbar-right-mod">
						<div class="moduletable">
							<?php print $topright; ?>
						</div>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<!--End Top Bar-->
				<!--Begin Header-->
				<?php if ($logo or $search) : ?>
				<div id="header-bar">
					<div id='logo-wrapper'>
						<a href="<?php print check_url($front_page); ?>" id="logo">
							<?php if ($site_slogan) : ?>
								<span class="logo-text"><?php echo t($site_slogan); ?></span>
							<?php endif; ?>
						</a>
						<span class="site-name"><?php echo t($site_name); ?></span>
					</div>
					<?php if ($search) : ?>
						<div class="search_region">
						<?php print $search; ?>
						</div>
					<?php endif; ?>
				</div>
				
				<?php endif; ?>
					
					<?php if($ss_menu_type == "moomenu" OR $ss_menu_type == "suckerfish"): ?>
				
						<div id="horiz-menu" class="<?php print $ss_menu_type; ?>">
							<?php
								$tree = menu_tree_page_data('primary-links');
								
								$main_menu = main_menu_tree_output($tree, 1);
							   	print $main_menu;	
							?>
						<div class="clr"></div>
						</div>

					<?php elseif($ss_menu_type == "splitmenu"): ?>
						
						<div id="horiz-menu" class="<?php print $ss_menu_type; ?>">
							<?php
								$tree = menu_tree_page_data('primary-links');  
								$main_menu = main_menu_tree_output($tree, 0);
							   	print $main_menu;	
							?>
												
						<div class="clr"></div>
						</div>
						
					<?php endif; ?>
					<!-- END MENU -->
				<!--End Header-->
				<!--Begin Custom1 -->
				<?php if ($custom1_1 || $custom1_2) : ?>
				<div class="custom1-surround">
					<?php
						if ($custom1_1):
					?>
					<div id="custom1_1" class="spacer ">
						<?php print $custom1_1; ?>						
					</div>
					<?php
						endif;
					?>
					<?php
						if ($custom1_2):
					?>
					<div id="custom1_2" class="spacer ">
						<?php print $custom1_2; ?>						
					</div>
					<?php endif;?>
				</div>
				<?php endif; ?>
				<!--End Custom1 -->
				<!--Begin Custom2 -->
				<?php  if ($custom2_1 || $custom2_2 || $custom2_3) :?>
				<div class="custom2-surround">
					<?php  if ($custom2_1) :?>
					<div id="custom2_1" class="spacer ">
						<?php print $custom2_1; ?>						
					</div>
					<?php endif;?>
					<?php  if ($custom2_2) :?>
					<div id="custom2_2" class="spacer ">
						<?php print $custom2_2; ?>						
					</div>
					<?php endif;?>
					<?php  if ($custom2_3) :?>
					<div id="custom2_3" class="spacer ">
						<?php print $custom2_3; ?>						
					</div>
					<?php endif;?>
				</div>
				<?php endif; ?>
				<!--End Custom2 -->
				
				<!--Begin Showcase Modules-->
				<?php if ($showcase123) : ?>
				<div class="showcase-surround">
					<div id="showmodules" class="spacer <?php echo $showcase123_width; ?>">
						<?php print $showcase123; ?>						
					</div>
				</div>
				<?php endif; ?>
				<!--End Showcase Modules-->
				<div id="main-body">
					<div id="main-body-surround">
						<!--Begin Main Content Block-->
						<div id="main-content" class="<?php echo $col_mode; ?>">
						    <div class="colmask leftmenu">
						        <div class="colmid">
    					    	    <div class="colright">
        						        <!--Begin col1wrap -->    
            						    <div class="col1wrap">
            						        <div class="col1pad">
            						            <div class="col1">
                    						        <div id="maincol2">
                    									<div class="maincol2-padding">
															<?php if ($feature) : ?>
	                    									<div id="feature-section">
	                    										<div class="feature-module">
																		<div class="moduletable">
																			<div style="margin: 5px;">
																				<?php print $feature; ?>
																			</div>
																		</div>
																	</div>
	                    									</div>
	                    									<?php endif; ?>
	                    									
															<?php if ($newsflash) : ?>
	                    									<div id="newsflash-bar">
	                    										<?php print $newsflash; ?>
	                    									</div>
	                    									<?php endif; ?>
	                    									
                    										<?php if ($user123) : ?>
                    										
                    										<div id="mainmodules" class="spacer <?php echo $user123_width; ?>">
																<?php print $user123; ?>
                    										</div>
                    										
                    										<?php endif; ?>
                    								
	                    									<?php if (theme_get_setting(show_breadcrumb) == 1) : ?>
	                    										<div id="breadcrumbs">
		                    										<a href="<?php echo base_path(); ?>" id="breadcrumbs-home"></a>
																						<?php print $breadcrumb; ?>	                    										
	                    										</div>
	                    										
	                    									<?php endif; ?>
	                    									
	                    									<!-- Print Section Heading -->
															<?php
																if (!$is_front) {
																	if (arg(0) == 'search') {
																		echo "<div class='pageheading'><h2 class='contentheading'>Search</h2></div>";
																	}
																	elseif (arg(2) AND arg(2) == 'edit') {
																		echo "<div class='pageheading'><h2 class='contentheading'>Edit Content</h2></div>";
																	}
																	elseif (arg(2) AND arg(2) == 'delete') {
																		echo "<div class='pageheading'><h2 class='contentheading'>Delete Content?</h2></div>";
																	}
																	elseif (arg(0) == 'node' && arg(1)) {
																		
																	}
																	else {
																		echo "<div class='pageheading'><h2 class='contentheading'>" . $title . "</h2></div>";
																	}
																}
															?>	
														
	                    									<!-- Begin Messages -->
															<?php if ($messages): ?>
																<div id="messages">
																	<?php print $messages; ?>
																</div>
															<?php endif; ?>			
															<!-- End Messages -->
                    									
	                    									<div class="bodycontent">
	                    										<?php if ($inset2 and !$editmode) : ?>
	                    										<div id="inset-block-right"><div class="right-padding">
	                    											<?php print $inset2; ?>
	                    										</div></div>
	                    										<?php endif; ?>
	                    										<?php if ($inset and !$editmode) : ?>
	                    										<div id="inset-block-left"><div class="left-padding">
	                    											<?php print $inset; ?>
	                    										</div></div>
	                    										<?php endif; ?>
	                    										<div id="maincontent-block">
	                												
	                												<?php if (theme_get_setting(show_tabs) == 1) : ?>
		                												<!-- Begin Admin Tabs -->
																		<?php if ($tabs): print '<ul class="primary">' . $tabs .'</ul>'; print "<br>"; endif; ?>
																		<?php if ($tabs2): print '<ul class="secondary">' . $tabs2 .'</ul>'; print "<br>"; endif; ?>
																		<!-- End Admin Tabs -->
																	<?php endif; ?>
																	
																	<?php
																		print $content; 
																	?>
																	
	                    										</div>
	                    										</div>
	                    										<div class="clr"></div>
																<?php if ($micronews) : ?>
																	<div id="rokmicronews">
																		<?php print $micronews; ?>
																	</div>
																<?php endif; ?>
	                        									<?php  if ($user456) : ?>
	                        									<div id="mainmodules2" class="spacer <?php echo $user456_width; ?>">
	                        										<?php print $user456; ?>
	                        									</div>
	                        									<?php endif; ?>
	                    									</div>
                    								</div>    
                    							</div>
            						        </div>
            						    </div>
            						    <!--End col1wrap -->
           						        <!--Begin col2 -->
           						       
           						        <?php if (theme_get_setting(leftcolumn_width) != 0) : ?>
            						    <div class="col2">
                							<div id="leftcol">
                                  <div id="leftcol-bg">
                									<?php if ($submenu and $splitmenu_col=="leftcol") : ?>
                									<div class="sidenav-block">
                										<?php echo $submenu; ?>
                									</div>
                									<?php endif; ?>
                									<?php print $left; ?>
                                  </div>
                							</div>
            						    </div>
            						    <?php endif; ?> 
            						    <!---End col2 -->
            						    <!--Begin col3 -->
            						    <?php if (theme_get_setting(rightcolumn_width) != 0) : ?>
            						    <div class="col3">
                							<div id="rightcol">
           										
           										<?php if ($submenu and $splitmenu_col=="rightcol") : ?>
            									<div class="sidenav-block">
            										<?php echo $submenu; ?>
            									</div>
            									<?php endif; ?>
            							
            									<?php print $right; ?>
            								
                							</div>
            						    </div>
            						    <?php endif; ?> 
            						    <!--End col3-->
        							</div>
    							</div>
							</div>
						</div>
						<!--End Main Content Block-->
					</div>
					<!--Begin Bottom Main Modules-->
					<?php /*$mClasses = modulesClasses('case3');*/ if ($user789) : ?>
					<div id="bottom-main">
						<div id="mainmodules3" class="spacer <?php echo $user789_width; ?>">
							<?php print $user789; ?>
						</div>
					</div>
					<?php endif; ?>
					<!--End Bottom Main Modules-->
					<!--Begin Bottom Bar-->
					<?php if (theme_get_setting(show_topbutton) == 1 or ($bottommenu)) : ?>
					<div id="botbar">
						<?php if ($bottommenu) : ?>
						<div id="bottom-menu">
							<?php print $bottommenu; ?>
						</div>
						<?php endif; ?>
						<?php if (theme_get_setting(show_topbutton) == 1) : ?>
							
							<div id="top-button"><a href="#" id="top-scroll" class="top-button-desc"><?php echo "Back to Top"; ?></a></div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<!--End Bottom Bar-->
					
				</div>
			</div></div></div>
		</div>
		<div class="footer-bottom">
			<!--Begin Bottom Section-->
			<?php if (theme_get_setting(show_copyright) == 1 or $footer or ($bottom123)) : ?>
			<div id="bottom">
				<?php /*$mClasses = modulesClasses('case4');*/ if ($bottom123) : ?>
				<div id="mainmodules4" class="spacer <?php echo $bottom123_width; ?>">
					
					<?php print $bottom123; ?>
					
				</div>
				<?php endif; ?>
				<?php if (theme_get_setting(show_copyright) == 1) : ?>
				<div class="copyright-block">
					<div id="copyright">
						<a href="http://www.webis.ca" target="_blank">Undeniable Design Strength Website from <b>Webis.ca</b></a>
						<div>
							<span id="siteseal"><script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=CnUEjDI0UPX08Xj8ZasNhU2bpjzYj6VsIhApl5aCPWXT1ZttiGPJZfAN7o"></script></span>
						</div>
						
					</div>
					<!--a href="http://www.webis.ca/" title="" id="rocket"></a-->
				</div>
				<?php else: ?>
				<div class="footer-mod">
					<?php print $footer; ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<!--End Bottom Section-->
		</div>
		<?php if ($debug) : ?>
		<div id="debug-mod">
			<?php print $debug; ?>
		</div>
		<?php endif; ?>
		<?php if ($login) : ?>
		<div id="login-module">
			<?php if ($user->guest) : ?>
				<?php print $login; ?>
			<?php else : ?>
			<div class="logout">
				<?php print $login; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		
		<?php print $closure; ?>
		
<script type="text/javascript">stLight.options({publisher: "1d004593-8e61-45c4-97e2-acf62f645432", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<script>
var options={ "publisher": "1d004593-8e61-45c4-97e2-acf62f645432", "position": "left", "ad": { "visible": false, "openDelay": 5, "closeDelay": 0}, "chicklets": { "items": ["facebook", "twitter", "sina", "linkedin", "google_bmarks", "pinterest", "baidu", "email", "sharethis"]}};
var st_hover_widget = new sharethis.widgets.hoverbuttons(options);
</script>

	</body>
</html>