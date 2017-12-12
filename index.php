<?php
/**
 * Plugin Name: Recall Form
 * Plugin URI: http://osw3.net/wordpress/plugins/recall-form/
 * Description: jQuery Recall Form for WordPress.
 * Version: 0.1
 * Author: OSW3
 * Author URI: http://osw3.net/
 * License: GNU
 * Text Domain: recall_form
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) 
{
    echo "Hi there!<br>Do you want to plug me ?<br>";
	echo "If you looking for more about me, you can read at http://osw3.net/wordpress/plugins/recall-form/";
    exit;
}

require_once(__DIR__.'/ppm/index.php');