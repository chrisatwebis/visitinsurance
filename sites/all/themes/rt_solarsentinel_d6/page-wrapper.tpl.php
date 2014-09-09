
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		

		<?php
				$rt_utils_includes = path_to_theme() . "/rt_utils.php";
				include $rt_utils_includes;
				$head_includes = path_to_theme() . "/rt_head_includes.php";
				include $head_includes;
				$style_switcher = path_to_theme() . "/rt_styleswitcher.php";
				include $style_switcher;
				
				print $head;
				print $styles;
				print $scripts;
			
			
		?>
		
	</head>
	<body <?php print phptemplate_body_class(); ?>>	

		<div id="page-bg">
			<div class="wrapper"><div id="body-left" class="png"><div id="body-right" class="png">
				<!--Begin Top Bar-->
				
				<?php if (theme_get_setting(show_date) == 1 or theme_get_setting(show_textsizer) == "true" or $topleft or $login or $topright or $syndicate): ?>
				<div id="top-bar">
					<div class="topbar-strip">
						
						<?php if (theme_get_setting(show_date) == 1) : ?>
						<div class="date-block">
							<?php echo date("l, F j, Y"); ?>
						</div>
						<?php endif; ?>
						<?php if ($syndicate AND $is_front) : ?>
						<div class="syndicate-module">
							<?php print $syndicate; ?>
						</div>
						<?php endif; ?>
						<?php if (theme_get_setting(show_textsizer)=="true") : ?>
						<div id="accessibility">
							<div id="buttons">
								<a href="?fontsize=larger&page=<?php print arg(1); ?>" title="" class="large"><span class="button png">&nbsp;</span></a>
								<a href="?fontsize=smaller&page=<?php print arg(1); ?>" title="" class="small"><span class="button png">&nbsp;</span></a>
							</div>
							<div class="textsizer-desc"><?php echo "Text Size"; ?></div>
						</div>
						<?php endif; ?>
						
						<?php if (!$user->uid) : ?>
							<a href="?q=user" id="lock-button" class="login"><span>Login</span></a>
						<?php else : ?>
							<a href="?q=admin" id="lock-button"><span>Site Admin</span></a> 
						<?php endif; ?>
				
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
					
						<a href="<?php print check_url($front_page); ?>" id="logo">
							<?php if ($site_slogan) : ?>
								<span class="logo-text"><?php echo $site_slogan; ?></span>
							<?php endif; ?>
						</a>
				
					<?php if ($search) : ?>
						<?php print $search; ?>
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
				<!--Begin Showcase Modules-->
				<?php /*$mClasses = modulesClasses('case5');*/ if ($showcase123) : ?>
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
																	<div class="">
																		<div class="moduletable">
																			<div style="margin: 5px;">
																				<?php print $feature; ?>
																			</div>
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
                    								
	                    									<?php if (theme_get_setting(show_breadcrumb) == 1 AND !$is_front) : ?>
	                    										<div id="breadcrumbs">

		                    										<a href="<?php echo base_path(); ?>" id="breadcrumbs-home"></a>
																	<span class="breadcrumbs pathway">
																		<?php print $breadcrumb; ?>
		                    										</span>
	                    										
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
															<?php if ($messages AND arg(0) == "admin"): ?>
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
	                												
	                												<script language="javascript" type="text/javascript">
																		function iFrameHeight() {
																			var h = 0;
																			if ( !document.all ) {
																				h = document.getElementById('blockrandom').contentDocument.height;
																				document.getElementById('blockrandom').style.height = h + 60 + 'px';
																			} else if( document.all ) {
																				h = document.frames('blockrandom').document.body.scrollHeight;
																				document.all.blockrandom.style.height = h + 20 + 'px';
																			}
																		}
																	</script>
																	<div class="contentpane">
																		<iframe id="blockrandom"
																			name="iframe"
																			src="http://www.google.com"
																			width="100%"
																			height="600"
																			scrolling="auto"
																			align="top"
																			frameborder="0"
																			class="wrapper">
																			This option will not work correctly. Unfortunately, your browser does not support Inline Frames</iframe>
																	</div>
																	
	                    										</div>
	                    										</div>
	                    										<div class="clr"></div>
																<?php if ($micronews) : ?>
																	<div id="rokmicronews">
																		<?php print $micronews; ?>
																	</div>
																<?php endif; ?>
	                        									<?php /*$mClasses = modulesClasses('case2');*/ if ($user456) : ?>
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
                									
                									<?php if ($subnav and $splitmenu_col=="leftcol") : ?>
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
					<!--Begin Bottom Section-->
					<?php if (theme_get_setting(show_copyright) == 1 or $footer or ($bottom123)) : ?>
					<div id="bottom">
						<?php /*$mClasses = modulesClasses('case4');*/ if ($bottom123) : ?>
						<div id="mainmodules4" class="spacer <?php echo $bottom123_width; ?>">
							<div class="block full">
								<?php print $bottom123; ?>
							</div>
						
						</div>
						<?php endif; ?>
						<?php if (theme_get_setting(show_copyright) == 1) : ?>
						<div class="copyright-block">
							<div id="copyright">
								Designed by RocketTheme
							</div>
							<a href="http://www.rockettheme.com/" title="" id="rocket"></a>
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
			</div></div></div>
		</div>
		<div class="footer-bottom"></div>
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
	</body>
</html>