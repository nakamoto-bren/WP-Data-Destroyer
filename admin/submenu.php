
<?php global $wpdatadestroyer; ?>

<?php if ( $wpdatadestroyer->flash_msgs ) : ?>
	<div class="updated">
		<?php	foreach ($wpdatadestroyer->flash_msgs as $flash_msg) : ?>
			<p><strong><?php echo $flash_msg; ?></strong></p>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<div class="wrap">
	<h2>
		<?php _e( 'データ削除ツール', 'wpdatadestroyer' ); ?>
	</h2>
	<form name="form1" method="post">
		<hr />
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e( 'Delete' ); ?>" />
		</p>
	</form>
</div>
