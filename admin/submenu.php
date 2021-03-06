
<?php global $wp_data_destroyer; ?>

<?php if ( $wp_data_destroyer->messages ) : ?>
	<div class="updated">
		<?php foreach ($wp_data_destroyer->messages as $flash_msg) : ?>
			<p><strong><?php echo $flash_msg; ?></strong></p>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php if ( $wp_data_destroyer->errors ) : ?>
	<div class="updated">
		<?php foreach ($wp_data_destroyer->errors as $flash_error) : ?>
			<p class="error-message"><strong><?php echo $flash_error; ?></strong></p>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="wrap">
	<h2>
		<?php _e( 'WP Data Destroyer', $this->text_domain ); ?>
	</h2>

	<hr />

	<h3>
		<?php _e( 'Delete for Post, Page, Attachment, Category(non-default), Tag, Nav Menu, Custom Posts.', $this->text_domain ); ?>
	</h3>
	
	<form method="post" onsubmit="return window.confirm('Delete all?')">

		<?php wp_nonce_field( 'wp_data_destroyer_action' ); ?>
		<input type="hidden" name="action" value="delete">

		<table class="form-table">

			<tr>
				<th scope="row">
					<label for="delete-mode-all">
						<input type="radio" id="delete-mode-all" name="delete_mode" value="all">
						<span><?php _e('Delete for all', $this->text_domain) ?></span>
					</label>
				</th>
				<td>
					<p class=""><?php _e( 'Post, Page, Attachment, Category(non-default), Tag, Nav Menu, Custom Posts', $this->text_domain ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="delete-mode-selected">
						<input type="radio" id="delete-mode-selected" name="delete_mode" value="selected">
						<span><?php _e('Delete for selected', $this->text_domain) ?></span>
					</label>
				</th>
				<td>
					<fieldset>
						<label for="select_post">
							<input type="checkbox" id="select_post" name="select_post" value="1">
							<span><?php _e('Post', $this->text_domain) ?></span>
						</label><br>
						<label for="select_page">
							<input type="checkbox" id="select_page" name="select_page" value="1">
							<span><?php _e('Page', $this->text_domain) ?></span>
						</label><br>
						<label for="select_attachment">
							<input type="checkbox" id="select_attachment" name="select_attachment" value="1">
							<span><?php _e('Attachment', $this->text_domain) ?></span>
						</label><br>
						<label for="select_nav_menus">
							<input type="checkbox" id="select_nav_menus" name="select_nav_menus" value="1">
							<span><?php _e('Nav Menu', $this->text_domain) ?></span>
						</label><br>
						<label for="select_categories">
							<input type="checkbox" id="select_categories" name="select_categories" value="1">
							<span><?php _e('Category', $this->text_domain) ?></span>
						</label><br>
						<label for="select_tags">
							<input type="checkbox" id="select_tags" name="select_tags" value="1">
							<span><?php _e('Tag', $this->text_domain) ?></span>
						</label><br>
						<label for="select_custom_posts">
							<input type="checkbox" id="select_custom_posts" name="select_custom_posts" value="1">
							<span><?php _e('Custom Posts', $this->text_domain) ?></span>
						</label><br>
					</fieldset>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="delete-confirm">
						<input type="checkbox" id="delete-confirm" name="delete_confirm" value="confirm">
						<span><?php _e('Confirm', $this->text_domain) ?></span>
					</label>
				</th>
				<td>
					<p class="description">
						<?php _e('Please backup your database before deleting.', $this->text_domain); ?><br>
						<?php _e('and Please check for Confirm.', $this->text_domain); ?><br>
					</p>
				</td>
			</tr>

		</table>

		<p class="submit">
			<input type="submit" name="Submit" class="button button-primary" value="<?php _e( 'Delete', $this->text_domain ); ?>" />
		</p>

	</form>

</div>
