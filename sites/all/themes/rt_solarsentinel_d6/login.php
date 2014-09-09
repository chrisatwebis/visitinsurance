<?php  global $user; ?>

<div class="main-login-form">

<?php if (!$user->uid) : ?>


	<form action="<?php print "?q=user&".drupal_get_destination();?>" method="post" class="form-login">
	
		<div class="username-block">
			<input name="name" id="mod_login_username" class="inputbox" type="text" alt="username" size="10" value="Username" onfocus="if (this.value=='Username') this.value=''" onblur="if(this.value=='') { this.value='Username'; return false; }" />
		</div>
	
		<div class="password-block">
			<input type="password" id="mod_login_password" class="inputbox" name="pass" size="10" alt="password" value="Password" onfocus="if (this.value=='Password') this.value=''" onblur="if(this.value=='') { this.value='Password'; return false; }" />
		</div>
	
	   	<div class="login-extras">
			
			<div class="remember-me">
	   			<input type="checkbox" name="remember" id="mod_login_remember" class="checkbox" value="yes" alt="Remember Me" /> Remember me
			</div>
				
			<div class="readon-wrap1">
				<div class="readon1-l"></div>
					<a class="readon-main">
						<span class="readon1-m">
							<span class="readon1-r">
				 				<input type="hidden" name="form_id" id="edit-user-login" value="user_login" /> 
								<input type="submit" name="Submit" class="button" value="Login" />
							</span>
						</span>
					</a>
				</div>
				<div class="clr"></div>
				
		
			<div class="login-links">
				<p><a href="?q=user/password">Lost Password?</a></p>
		                    
				<p><a href="?q=user/register">Register</a></p>
			</div>
		
		</div>
				            
	</form>


<?php else: ?>


	<form action="?q=logout" method="post" name="logout"  class="log">	
	
		<div>
			<b>Hi, admin</b>
			<br />  
			<a href="?q=node/add">Create Content</a>  |  <a href="?q=admin">Site Admin</a>
		</div>
	
		
		<div class="readon-wrap1"><div class="readon1-l"></div><a class="readon-main"><span class="readon1-m"><span class="readon1-r">
			<input type="submit" name="Submit" class="button" value="Log out" />
		</span></span></a></div><div class="clr"></div>
			
			
	</form>

<?php endif; ?>

</div>