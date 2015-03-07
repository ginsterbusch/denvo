<?php
/**
 * Plugin Name: DEveloper ENvironment Info
 * Plugin URI:  http://github.com/ginsterbusch/denvo/
 * Text Domain: denvo__
 * Domain Path: /languages
 * Description: Extremely light-weight, simple environment information for developers, including data like PHP version, WP Version, memory_limit, MySQL version and so on.
 * Author:      Fabian Wolf
 * Author URI:  http://usability-idealist.de/
 * Version:     0.1
 * License:     GPLv3
 */

// init
define( 'DENVO__TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'templates/' );
define( 'DENVO__PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DENVO__PLUGIN_URL', plugin_dir_url( __FILE__ ) );


// includes
//require_once( 'classes/denvo.class.php');
require_once( 'classes/denvo-admin.class.php');


// activate classes

add_action( 'plugins_loaded', array ( 'denvo__Admin', 'get_instance' ) );
