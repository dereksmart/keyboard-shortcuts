<h2>Keyboard Shortcuts</h2>

<h3>Jetpack Shortcuts</h3>
<ul>
	<li>Omnisearch: cmd + shift + s</li>
</ul>

<h3>WordPress Shortcuts</h3>
<ul>
	<li>New Post: p + o + s + t</li>
</ul>

<form method="post" id="keyboard_shortcuts_settings">
	<label for="saved_keys">Which keys would you like to save? <br> separate by comma <br></label>
	<input type="text" id="saved_keys" name="saved_keys"/>
	<input class="button-primary" type="submit" value="Update Settings" name="submit">
	<?php wp_nonce_field( 'keyboard_shortcuts_save' , 'keyboard_shortcuts_save_nonce' ); ?>
</form>


<?php echo 'Option keys_to_save<pre>';
print_r( get_option( 'keys_to_save' ) );
echo '</pre>'; ?>