<?php 

add_action('post_submitbox_misc_actions', 'ncsu_a11y_run_button');

// Add "Run Accessibility Scan" button to Classic Editor Publish metabox
function ncsu_a11y_run_button($post) {

    $post_id = (int) $post->ID;

    $ncsu_a11y_options = get_option( 'ncsu_a11y', array() );
    $current_screen = get_current_screen()->id;

    if ( $ncsu_a11y_options['post_types'] ) {
        $checked_post_types = $ncsu_a11y_options['post_types'];
    } else {
        $checked_post_types = get_post_types( array( 'public' => true ) );
    }

	$query_args = array();

	$query_args['preview_id']    = $post->ID;
	$query_args['preview_nonce'] = wp_create_nonce( 'post_preview_' . $post->ID );
	if ( isset( $_POST['post_format'] ) ) {
		$query_args['post_format'] = empty( $_POST['post_format'] ) ? 'standard' : sanitize_key( $_POST['post_format'] );
	}
	if ( isset( $_POST['_thumbnail_id'] ) ) {
		$query_args['_thumbnail_id'] = ( intval( $_POST['_thumbnail_id'] ) <= 0 ) ? '-1' : intval( $_POST['_thumbnail_id'] );
	}

	$query_args['ncsu_a11y'] = 'true';
    

    if ( in_array($current_screen, $checked_post_types) ) {
        echo sprintf(
            '<div class="misc-pub-section">
                <a class="preview button" href="%s" target="wp-preview-%s" id="ncsu-a11y-preview">
                    %s
                </a>
                <input type="hidden" name="wp-preview" id="wp-preview" value="">
                <br />
            </div>
            <a class="misc-pub-section preview" href="#ncsu_a11y">
                %s
            </a>',
            get_preview_post_link( $post, $query_args ) . '#a11y-report',
            $post->ID,
            __( 'Run Accessibility Check<span class="screen-reader-text"> (opens in a new window)</span>', 'ncsu-a11y-helper' ),
            __( 'Learn more about accessibility', 'ncsu-a11y-helper' )
            );
        
    }

}