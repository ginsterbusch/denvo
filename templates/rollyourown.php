<?php
/**
 * Template: Roll your own
 */
 
?>
<div class="wrap denvo-rollyourown">
	<h2><?php printf( __('%s &raquo; %s:', 'denvo__' ), __('Denvo', 'denvo__'), __('Roll your own', 'denvo__' ) ); ?></h2>
	
	<?php if( !empty( $navigation ) ) : 
		include( DENVO__TEMPLATE_PATH . 'nav.php' );
	endif; ?>


		
	<p><?php _e('Use the following text field to add your own PHP queries', 'denvo__' ); ?></p>
	
	<form method="post" action="<?php echo admin_url( 'tools.php?page=' . $this->pluginMenuSlug ); ?>">
		<label>
			<textarea name="custom" placeholder="Example: array('theme stylesheet URL' => get_stylesheet_directory_url() );"><?php 
			if( !empty( $field_rollyourown ) ) :
				echo $field_rollyourown;
			endif;
			?></textarea>
		</label>
		
		<?php submit_button(__('Make it so', 'denvo__') ); ?>
	</form>

<?php if( !empty( $result_rollyourown ) ) : ?>
	<div class="denvo-result">
		<pre><?php print_r( $result_rollyourown ); ?></pre>
	</div>
<?php endif; ?>

</div>


	
