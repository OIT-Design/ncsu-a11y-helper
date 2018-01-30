<?php 

// Add "Run Accessibility Scan" button to Publish metabox
function ncsu_a11y_run_button($post) {

    $ncsu_a11y_options = get_option( 'ncsu_a11y', array() );
    $current_screen = get_current_screen()->id;

    if ( $ncsu_a11y_options['post_types'] ) {
        $checked_post_types = $ncsu_a11y_options['post_types'];
    } else {
        $checked_post_types = get_post_types( array( 'public' => true ) );
    }

    if ( in_array($current_screen, $checked_post_types) ) {
        echo sprintf(
            '<div class="misc-pub-section"><a class="preview button" href="%s" target="wp-preview-1" id="ncsu-a11y-preview">%s</a><br /></div><a class="misc-pub-section preview" href="#ncsu_a11y">%s</a>',
            get_preview_post_link( $post, array( 'ncsu_a11y' => 'true' ) ),
            'Run Accessibility Check<span class="screen-reader-text"> (opens in a new window)</span>',
            'Learn more about accessibility'
            );
        
    }

}
add_action('post_submitbox_misc_actions', 'ncsu_a11y_run_button');