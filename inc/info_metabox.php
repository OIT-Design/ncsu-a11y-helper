<?php 

// Add "Accessibility at NC State metabox"

function ncsu_a11y_meta_box() {

    $ncsu_a11y_options = get_option( 'ncsu_a11y', array() );

    if ( $ncsu_a11y_options['post_types'] ) {
        $checked_post_types = $ncsu_a11y_options['post_types'];
    } else {
        $checked_post_types = get_post_types( array( 'public' => true ) );
    }

    add_meta_box( 'ncsu_a11y', 'Accessibility Helper', 'ncsu_a11y_meta_content', $checked_post_types, 'normal', 'default', null );
}
add_action( 'add_meta_boxes', 'ncsu_a11y_meta_box' );

function ncsu_a11y_meta_content() {

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

    $button = sprintf(
        '<a class="button" href="%s" target="wp-preview-%s" id="ncsu-a11y-preview">
                %s
            </a>',
        get_preview_post_link( $post, $query_args ) . '#a11y-report',
        $post->ID,
        __( 'Run Accessibility Check<span class="screen-reader-text"> (opens in a new window)</span>', 'ncsu-a11y-helper' )
        );

    $default_meta_text = '<h3>' . __( 'What is Web Accessibility?', 'ncsu-a11y-helper' ) . '</h3>'
                         .'<p><a href="http://go.ncsu.edu/what-is-a11y">' . __( 'Web accessibility', 'ncsu-a11y-helper' ) . '</a> '
                         . __( 'is about ensuring that everyone who visits your website will be able to understand and interact with your website. This especially includes users with disabilities, but good accessibility has benefits to all users. Learn more about ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/a11y-intro">' . __( 'the basics of web accessibility', 'ncsu-a11y-helper' ) . '</a>, <a href="http://go.ncsu.edu/inclusive-design-principles">' . __( 'Inclusive Design Principles', 'ncsu-a11y-helper' ) . '</a>, ' . __( 'and ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/universal-design-principles">' . __( 'Universal Design Principles', 'ncsu-a11y-helper' ) . '</a>.</p>'
                         . '<h3>' . __( 'Testing for Web Accessibility', 'ncsu-a11y-helper' ) . '</h3>'
                         . '<p>' . __( 'Before publishing new content, run the Accessibility Check:', 'ncsu-a11y-helper' ) . '</p>'
                         . $button
                         . '<p>' . __( 'The Accessibility Check will scan your post or page for common accessibility issues. If it detects something that might be an issue, it will highlight that part of your post or page, and provide more information about what the issue is, why it matters, and how you can fix it.', 'ncsu-a11y-helper' ) . '</p>'
                         . '<p>' . __( 'But automated testing can\'t detect everything. As you write and build your post or page, think about potential barriers that would prevent some users from understanding your content. Some easy manual tests you can do include:', 'ncsu-a11y-helper' ) . '</p>'
                         . '<ul>'
                         . '<li><strong>' . __( 'View your page with volume muted.', 'ncsu-a11y-helper' ) . '</strong> ' . __( 'If you have audio or video, do you have captions or a transcript?', 'ncsu-a11y-helper' ) . '</li>'
                         . '<li><strong>' . __( 'Use your page without using a mouse.', 'ncsu-a11y-helper' ) . '</strong> ' . __( 'Can you navigate to all links using only your keyboard\'s [TAB] key?', 'ncsu-a11y-helper' ) . '</li>'
                         . '<li><strong>' . __( 'Listen to your page using a screen reader.', 'ncsu-a11y-helper' ) . '</strong> ' . __( 'Mac users can activate ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/apple-voiceover">' . __( 'VoiceOver', 'ncsu-a11y-helper' ) . '</a>. ' . __( 'Windows 10 users can activate ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/microsoft-narrator">' . __( 'Narrator', 'ncsu-a11y-helper' ) . '</a>.</li>'
                         . '</ul>';

        echo $default_meta_text;

}