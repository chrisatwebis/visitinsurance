<div id="searchmod-surround">
	<h3>Site Search</h3>
		<div id="searchmod">
			<div class="module">


<form action="?q=search" method="post">
	<div class="rokajaxsearch">
		<div class="input-field-l png">
			<div class="input-field-r png">
				<input id="roksearch_search_str" name="keys" type="text" class="inputbox png" value="Search..."  onblur="if(this.value=='') this.value='Search...';" onfocus="if(this.value=='Search...') this.value='';" />  
			</div>
		</div>
		<!--<input type="submit" value="Go" class="button" onclick="this.form.searchword.focus();"/>-->
	</div>
		<!-- <input type="submit" value="Search" name="op" title="Search" alt="Search" />  -->
		<input type="hidden" value="<?php print drupal_get_token('search_form'); ?>" name="form_token" />  
		<input type="hidden" value="search_form" id="edit-search-form" name="form_id" />  
</form> 

			</div>
		</div>
</div>