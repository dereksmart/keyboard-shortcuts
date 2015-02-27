<h2>Jetpack Shortcuts</h2>
<table class="shortcut-table">
	<tr>
		<td>Navigate to Jetpack Settings</td>
		<td class="keys">j + e + t</td>
	</tr>
	<tr>
		<td>Omnisearch:</td>
		<td class="keys">cmd + shift + s</td>
	</tr>
</table>


<h2>WordPress Shortcuts</h2>
<table class="shortcut-table">
	<tr>
		<td>New Post</td>
		<td class="keys">cmd + shift + s</td>
	</tr>
</table>

<hr/>

<form method="post" id="keyboard_shortcuts_settings">
	<label for="saved_keys">Which keys would you like to save? <br> separate by comma <br></label>
	<input type="text" id="saved_keys" name="saved_keys"/>
	<input class="button-primary" type="submit" value="Update Settings" name="submit">
	<?php wp_nonce_field( 'keyboard_shortcuts_save' , 'keyboard_shortcuts_save_nonce' ); ?>
</form>


<?php echo 'Option keys_to_save<pre>';
print_r( get_option( 'keys_to_save' ) );
echo '</pre>'; ?>