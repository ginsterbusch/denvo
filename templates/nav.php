<?php
/**
 * denvo Template: Navigation
 */
 
if( empty( $navigation ) || !is_array( $navigation ) ) :
	return;
endif; ?>
<div class="denvo-navigation">

	<ul>
	<?php foreach( $navigation as $strNavTitle => $arrNavMeta ) :
		$nav_class = ( !empty( $arrNavMeta['class'] ) ? $arrNavMeta['class' ] : 'nav-item' );
		if( !empty( $arrNavMeta['current'] ) ) :
			$nav_class .= ' current-nav-item';
		endif;
	 ?>
		<li class="<?php echo $nav_class; ?>">
			<a href="<?php echo $arrNavMeta['url']; ?>"><?php echo $strNavTitle; ?></a>
		</li>
	<?php endforeach; ?>
	</ul>
	
</div>
<?php
