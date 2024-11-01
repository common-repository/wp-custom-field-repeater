<?php
/*
Plugin Name: WP CUSTOM FIELD REPEATER
Plugin URI: https://developer.wordpress.org/plugins/wp-custom-field-repeater/
Description: A wordpress plugin for custom fields creation
Version: 1.0
Author: Cordiace Solutions
Author URI: https://www.cordiace.com
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
//define constants of the plugin_directory
define('WPRP_PLUGIN_PATH', plugin_dir_url(__FILE__));

//add the required class file
require 'includes/wprp-class-base-plugin.php';
require 'includes/wprp-admin-side.php';
require 'includes/wprp-custom-post.php';
require 'includes/wprp-short-code.php';

//this method initialize the class
function wprp_repeater_custom_field_helper_init()
{
    //instantiate the plugin class
    $plugin = new WPRP_Repeater_Custom_Field_Helper();
    //start the execution of the plugin class
    $plugin->run();
}
add_action('init', 'wprp_repeater_custom_field_helper_init');
?>