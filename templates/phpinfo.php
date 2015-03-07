<?php
/**
 * Template: phpinfo()
 */
 
?>
<div class="wrap">
	<h2><?php printf( __('%s &raquo; %s:', 'denvo__' ), __('Denvo', 'denvo__'), __('Full-blown phpinfo', 'denvo__' ) ); ?></h2>
	
	<?php if( !empty( $navigation ) ) : 
		include( DENVO__TEMPLATE_PATH . 'nav.php' );
	endif; ?>

	<?php if( !empty( $phpinfo ) ) : ?>
		<div class="denvo-phpinfo">
			<style type="text/css">
				<?php echo $phpinfo['css']; ?>
			</style>
			
			<div class="denvo-phpinfo-result">
				<?php echo $phpinfo['html']; ?>
			</div>
		</div>
		
	<?php endif; ?>

</div>


	
