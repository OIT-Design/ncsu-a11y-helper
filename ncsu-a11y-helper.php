<?php
/**
 * Plugin Name: NC State Accessibility Helper
 * Description: Checks for common accessibility issues users introduce when creating content in WordPress, and highlights them in an annotated preview
 * Version: 1.0.0
 * Author: OIT Design & Web Services
 * Author URI: https://design.oit.ncsu.edu/
 *
 * GitHub Plugin URI: briandeconinck/ncsu-a11y-helper
 *
 */

define( __NCSU_A11Y_HELPER_PATH__, plugin_dir_path(__FILE__) );
define( __NCSU_A11Y_HELPER_URL__, plugins_url(__FILE__) );

// Misc useful functions
require_once( plugin_dir_path(__FILE__) . '/inc/misc.php' );

// Require class for creating options page
require_once('vendor/RationalOptionPages/RationalOptionPages.php');

// Create plugin options
require_once( plugin_dir_path(__FILE__) . '/inc/options.php' );

// Add "Run Accessibility Check" button to the publish meta box
require_once( plugin_dir_path(__FILE__) . '/inc/publish_metabox.php' );

// Add the info meta box
require_once( plugin_dir_path(__FILE__) . '/inc/info_metabox.php' );

// Do all the important stuff here
// Scripts for running aXe and generating the annotated preview
function ncsu_a11y_helper__scripts_front( $hook ) {

    global $post;

    $ncsu_a11y_options = get_option( 'ncsu_a11y', array() );
    $post_id = $post->ID;
    $post_type = get_post_type($post_id);
    $page_templates = wp_get_theme()->get_page_templates();

    // Get meta data for our additional custom tests
    require_once( __NCSU_A11Y_HELPER_PATH__ . '/config/additional_tests_meta.php' );
    $additional_tests_meta = json_encode($additional_tests_meta);

    // Get contents of custom config file as string
    if ( $ncsu_a11y_options['config_file'] ) {
        $config_file_path = get_attached_file( ncsu_a11y_get_attachment_id( $ncsu_a11y_options['config_file'] ) );
        $config_str = json_decode( file_get_contents( $config_file_path, FILE_USE_INCLUDE_PATH ) );
        $ncsu_defaults_json = null;
    } else {
        // If no custom config file, use ncsu_defaults.php
        require_once( __NCSU_A11Y_HELPER_PATH__ . '/config/ncsu_defaults.php' );
        $config_str = null;
        $ncsu_defaults_json = json_encode($ncsu_defaults);
    }

    // Get wrapper class/id for this post type or page template
    if ( $post_type == 'page' && $page_templates ) {
        $my_template = ( get_page_template_slug($post_id) ) ? preg_replace('/\\.[^.\\s]{3,4}$/', '', get_page_template_slug($post_id) ) : 'page' ;

        $my_wrapper = ( $ncsu_a11y_options[str_replace( '-', '_', $my_template )] ) ? $ncsu_a11y_options[str_replace( '-', '_', $my_template )] : '.type-page';
        
    } else {
        $my_wrapper = ( $ncsu_a11y_options[$post_type] ) ? $ncsu_a11y_options[$post_type] : '.' . $post_type;
    }

    $custom_options = array(
                            'wrapper'               => $my_wrapper,
                            'additional_tests_meta' => $additional_tests_meta,
                            'ncsu_defaults'         => $ncsu_defaults_json,
                            'config'                => $config_str,
                        );

    if ( is_preview() && get_query_var('ncsu_a11y') == 'true' ) {
        // aXe: https://github.com/dequelabs/axe-core and https://www.deque.com/products/axe/
        wp_register_script( 'axe-core', 'https://cdnjs.cloudflare.com/ajax/libs/axe-core/2.6.1/axe.min.js', array(), null, true  );
        wp_enqueue_script( 'axe-core' );

        // Script to run aXe tests and generate annotated preview
        wp_register_script( 'a11y_tests', plugins_url('a11y_tests.js', __FILE__), array(), null, true );
        wp_enqueue_script( 'a11y_tests' );

        // Access plugin options in JS
        wp_localize_script( 'a11y_tests', 'custom_options', $custom_options );

        // Script for annotated preview accessible modal
        wp_register_script( 'a11y-dialog', plugins_url('vendor/a11y-dialog/a11y-dialog.min.js', __FILE__), array(), null, true );
        wp_enqueue_script( 'a11y-dialog' );

        // Styles for annotated preview
        wp_register_style( 'a11y_styles', plugins_url('a11y_styles.css', __FILE__) );
        wp_enqueue_style( 'a11y_styles' );

    }
        
}
add_action( 'wp_enqueue_scripts', 'ncsu_a11y_helper__scripts_front' );


