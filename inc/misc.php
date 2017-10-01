<?php 

// Add ncsu_a11y query variable
function ncsu_a11y_query_vars_filter( $vars ){
  $vars[] = 'ncsu_a11y';
  return $vars;
}
add_filter( 'query_vars', 'ncsu_a11y_query_vars_filter' );

// Retrieves the attachment ID from the file URL, https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
function ncsu_a11y_get_attachment_id($attachment_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $attachment_url )); 
        return $attachment[0]; 
}