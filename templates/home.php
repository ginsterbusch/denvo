<?php
/**
 * Template: Overview / Home
 */
 
?>
<div class="wrap denvo-home">
	<h2><?php printf( '%s %s:', __('Denvo', 'denvo__'), $plugin_version ); ?></h2>
	
	<?php if( !empty( $navigation ) ) : 
		include( DENVO__TEMPLATE_PATH . 'nav.php' );
	endif; ?>

	<h3><?php _e('Basic System Information:', 'denvo__'); ?></h3>

<?php if( !empty( $sysinfo ) ) : ?>
	<ul>
		<?php foreach( $sysinfo as $strTitle => $value ) : ?>
		<li><?php echo $strTitle . ': ' . $value; ?></li>
		<?php endforeach; ?>
	</ul>
<?php else : ?>
	<p><?php _e('Something broke right now .. :-(', 'denvo__' ); ?></p>
<?php endif; ?>

</div>


	
