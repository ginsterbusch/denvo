<?php
/**
 * Main admin pages etc. pp.
 * 
 * @author Fabian Wolf (@link http://usability-idealist.de/)
 * @package denvo
 * @since 0.1
 */
 

class denvo__Admin {	
	var $pluginName = 'Denvo',
		$pluginPrefix = 'denvo__',
		$pluginVersion = '0.1',
		$pluginMenuSlug = 'denvo-admin';
	/**
	 * Plugin instance.
	 *
	 * @see get_instance()
	 * @type object
	 */
	protected static $instance = NULL;

	

	/**
	 * Access this plugin’s working instance
	 *
	 * @wp-hook plugins_loaded
	 * @since   04/05/2013
	 * @return  object of this class
	 */
	public static function get_instance() {

		NULL === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	
	function __construct() {
		// initialize variables
		$this->strTemplatePath = DENVO__TEMPLATE_PATH;
		
		// register scripts
		add_action('admin_init', array( $this, 'init_assets' ) );
		
		// add the menu
		add_action('admin_menu', array( $this, 'add_admin_menu' ) );
	}
	
	
	
	function add_admin_menu() {
		$page_hook_suffix = add_management_page(
			$this->pluginName, $this->pluginName, 'manage_options', $this->pluginMenuSlug, array( $this, 'admin_page' )
		);
		
		add_action('admin_print_scripts-' . $page_hook_suffix, array( $this, 'load_assets') );
	}
	
	function init_assets() {
		wp_register_style( $this->pluginPrefix . 'admin', DENVO__PLUGIN_URL . '/includes/css/denvo-admin.css' );
	}
	
	function load_assets() {
		wp_enqueue_style( $this->pluginPrefix . 'admin' );
	}
	
	function admin_page() {
		// initial variables
		$plugin_version = $this->pluginVersion;
		
		$strTemplateName = 'home.php';
		$strAction = ( !empty($_GET['action'] ) ? $_GET['action'] : false );
		
		
		
		$navigation = array(
			__('Home', 'denvo__' ) => array('url' => admin_url( 'tools.php?page=' . $this->pluginMenuSlug ) ),
			__('Full phpinfo()', 'denvo__' ) => array('url' => admin_url( 'tools.php?page=' . $this->pluginMenuSlug . '&action=phpinfo' ) ),
			__('WP Query', 'denvo__' ) => array('url' => admin_url( 'tools.php?page=' . $this->pluginMenuSlug . '&action=wpquery' ) ),
			__('SQL Query', 'denvo__' ) => array('url' => admin_url( 'tools.php?page=' . $this->pluginMenuSlug . '&action=sqlquery' ) ),
			__('Roll your own', 'denvo__' ) => array('url' => admin_url( 'tools.php?page=' . $this->pluginMenuSlug . '&action=rollyourown' ) ),
		);
		
		// controller
		switch( $strAction ) {
			
			case 'phpinfo':
				$navigation[__('Full phpinfo()', 'denvo__' )]['current'] = true;
				$strTemplateName = 'phpinfo.php';
				
				$phpinfo = $this->get_phpinfo();
				
				break;
			case 'edit':
				/*$option = get_option( $_GET['id'], false );
			
				if( !empty( $option ) ) {
			
					$strTemplateName = 'option-edit.php';
				} else {
					$msg = array(
						'type' => 'error',
						'message' => 'Cannot edit option, because its name has not been given.',
					);
					$this->add_message( $msg );
				}*/
				
				break;
			case 'wp_query':
				$navigation[__('WP Query', 'denvo__' )]['current'] = true;
				
				$strTemplateName = 'wpquery.php';
				
				if( !empty( $_POST['query'] ) ) {
					if( $this->is_valid_wp_query( $_POST['query'] ) != false ) { // either query string or full array
					
						$result = $this->wp_query( $_POST['query'] ); /** NOTE: Run a sanitizer! */
					} else {
						$msg = array(
							'type' => 'error',
							'message' => sprintf( __( 'Invalid query. Should be either a proper PHP array or a classic WP HTTP query. See <a href="%s">WP Codex &raquo; WP Query</a> for detailed info.', 'denvo__' ), 'http://codex.wordpress.org/Class_Reference/WP_Query' ),
						);
					}
				}
				
			
				break;
				
			case 'sql_query':
				$navigation[__('SQL Query', 'denvo__' )]['current'] = true;
				
				$strTemplateName = 'wpdb.php';
				
				if( !empty( $_POST['query'] ) ) {
					if( $this->is_valid_sql_query( $_POST['query'] ) != false ) { // either query string or full array
					
						$result = $this->sql_query( $_POST['query'] ); /** NOTE: Run a sanitizer! */
					} else {
						$msg = array(
							'type' => 'error',
							'message' => sprintf( __( 'Invalid query. See <a href="%s">WP Codex &raquo; WPDB Class</a> for detailed info.', 'denvo__' ), 'http://codex.wordpress.org/Class_Reference/wpdb' ),
						);
					}
				}
				
			
				break;
			case 'rollyourown':
			case 'custom':
				$strTemplateName = 'rollyourown.php';
				
				$navigation[ __('Roll your own', 'denvo__' ) ]['current'] = true;
				
				break;
			case 'overview':
			default:
				$navigation[__('Home', 'denvo__' )]['current'] = true;
			
				// fetch + prepare base information
				$sysinfo = array(
					'Site title' => get_bloginfo('name'),
					'Home URL' => get_bloginfo('url'),
					'__FILE__' => __FILE__,
					'template_directory' => get_template_directory(),
					'uname -a' => '',
					'PHP version' => phpversion(),
					'memory_limit' => ini_get( 'memory_limit' ),
				);
				
				if( defined('WP_MEMORY_LIMIT' ) ) {
					$sysinfo['constant WP_MEMORY_LIMIT'] = WP_MEMORY_LIMIT;
				}
				
				$arrMemoryUsage = array(
					'usage' => memory_get_usage(),
					'real_usage' => memory_get_usage( true ),
					'peak' => memory_get_peak_usage(),
					'real_peak' => memory_get_peak_usage( true ),
				);
				
				$sysinfo['Memory usage'] = sprintf( __('%d (real: %d); human readable: %G MB (real: %G MB)', 'denvo__'), $arrMemoryUsage['usage'], $arrMemoryUsage['real_usage'], round( ($arrMemoryUsage['usage'] / 1024 ) / 1024, 2 ), round( ($arrMemoryUsage['real_usage'] / 1024 ) / 1024, 2 ) );
				$sysinfo['Memory peak usage'] = sprintf( __('%d (real: %d); human readable: %G MB (real: %G MB)', 'denvo__'), $arrMemoryUsage['peak'], $arrMemoryUsage['real_peak'], round( ( $arrMemoryUsage['peak'] / 1024 ) / 1024, 2 ), round(  ( $arrMemoryUsage['real_peak'] / 1024 ) / 1024 , 2 ) );

				break;
		}
		
		if( !empty( $msg ) ) {
			$this->add_message( $msg );
		}
		
		// output data (aka view)
		include( $this->strTemplatePath . $strTemplateName );
	}
	
	function is_valid_sql_query( $query = false ) {
		return false;
	}
	
	function is_valid_wp_query( $query = false ) {
		return false;
	}
	
	function get_phpinfo() {
		$return = false;
		
		ob_start();
		phpinfo();

		preg_match ('%<style type="text/css">(.*?)</style>.*?(<body>.*</body>)%s', ob_get_clean(), $matches);
		
		$arrReturn['css'] = join( "\n", 
			array_map( 
				create_function( '$i', 'return ".phpinfodisplay " . preg_replace( "/,/", ",.phpinfodisplay ", $i );' ),
				preg_split( '/\n/', $matches[1] )
			)
		);
		
		if( !empty( $matches[2] ) ) {
			$arrReturn['html'] = str_replace(' width="600"', ' width="80%"', $matches[2] );
		}
		
		if( !empty( $arrReturn ) ) {
			$return = $arrReturn;
		}
		
		return $return;
		
	}
	
	/**
	 * Simple message adding using the WP Admin UI
	 * 
	 * @param string $type 		Message type, eg. error, info, update, etc.
	 * @param string $message	The actual message. May contain HTML (which automatically DISABLES p-tag-wrapping)
	 */
	function add_message( $arrMessage = array() ) {
		$return = false;
		
		if( !empty( $arrMessage ) && !empty( $arrMessage['message'] ) ) {
			
			// compile message class
			$arrClass = array('fade');
			
			if( !empty( $arrMessage['type'] ) ) {
				$arrClass[] = $arrMessage['type'];
			}
			
			// compile message text
			$strMessage = $arrMessage['message'];
			$strUnformattedMessage = strip_tags( $strMessage, '<strong><em><b><i><a><button><img>' );
			
			// no html
			if( trim($strUnformattedMessage) == trim( $strMessage ) ) {
				$strMessage = '<p>'.$strMessage.'</p>';
			}
	
			$strMessageContainer = '<div class="' . implode(' ', $arrClass ) . '">'.$strMessage.'</div>';
	
			// set uo error message
			if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
				add_action( 'network_admin_notices', create_function( '', "echo '$strMessageContainer';" ) );
			}
			
			add_action( 'admin_notices', create_function( '', "echo '$strMessageContainer';" ) );
			
			$return = true;
		}
		return $return;
	}
}
