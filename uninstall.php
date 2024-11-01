<?php
/**
 * Runs on Uninstall of  Repeater custom field helper
 *
 * @package   WP CUSTOM FIELD REPEATER
 * @author    Cordiace Solutions
 * @license   
 * @link      
 */
// Check that we should be doing this
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit; // Exit if accessed directly
}

global $wpdb;
$cptName = 'wprp-repeater-fields';
$tablePostMeta = $wpdb->prefix . 'postmeta';
$tablePosts = $wpdb->prefix . 'posts';

$postMetaDeleteQuery = "DELETE FROM $tablePostMeta".
                      " WHERE post_id IN".
                      " (SELECT id FROM $tablePosts WHERE post_type='$cptName'";
$postDeleteQuery = "DELETE FROM $tablePosts WHERE post_type='$cptName'";

$postMetaDeleteAdmin = "DELETE FROM $tablePostMeta WHERE meta_key='wprp_admin_customdata_group'"; 
$postMetaDelete = "DELETE FROM $tablePostMeta WHERE meta_key='wprp_customdata_group'"; 

$wpdb->query($postMetaDeleteQuery);
$wpdb->query($postDeleteQuery);
$wpdb->query($postMetaDeleteAdmin);
$wpdb->query($postMetaDelete);
?>