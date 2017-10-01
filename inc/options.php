<?php 

add_action('wp_loaded', function(){
    $post_types = get_post_types( array( 'public' => true ) ); 

    $post_types_choices = array();

    foreach ( $post_types as $post_type ) {
        $post_types_choices[$post_type] = $post_type;
    }

    $page_templates = wp_get_theme()->get_page_templates();

    $wrapper_elements = array();

    if ( $page_templates ) {

        foreach ( $post_types as $post_type ) {
            if ( $post_type != 'page' ) {
                $wrapper_elements[$post_type] = array(
                                                    'title' => $post_type,
                                                    'value' => '.type-' . $post_type
                                                    );
            }
        }

        foreach ( $page_templates as $template_filename => $template_name ) {
            $wrapper_elements[preg_replace('/\\.[^.\\s]{3,4}$/', '', $template_filename)] = array(
                                                                                                'title' => preg_replace('/\\.[^.\\s]{3,4}$/', '', $template_filename),
                                                                                                'value' => '.type-page',
                                                                                                );
            $wrapper_elements['page'] = array(
                                                'title' => $post_type,
                                                'value' => '.type-page'
                                                );
        }

    } else {
        foreach ( $post_types as $post_type ) {
            $wrapper_elements[$post_type] = array(
                                                'title' => $post_type,
                                                'value' => '.type-' . $post_type
                                                );
        }
    }

    $site_url = parse_url( get_site_url(), PHP_URL_HOST);

    $default_meta_text = '<h3 id="ncsu_a11y">' . __( 'What is Web Accessibility?', 'ncsu-a11y-helper' ) . '</h3>'
                         . '<p><a href="http://go.ncsu.edu/what-is-a11y:'. $site_url .'">' . __( 'Web accessibility', 'ncsu-a11y-helper' ) . '</a> '
                         . __( 'is about ensuring that everyone who visits your website will be able to use your website. This especially includes users with disabilities, but ensuring that your website follows accessibility best practices has benefits to all users.', 'ncsu-a11y-helper' ) . '</p>'
                         . '<p>' . __( 'Learn more about the ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/a11y-intro:'. $site_url .'">' . __( 'basics of web accessibility', 'ncsu-a11y-helper' ) . '</a>, ' . __( 'the ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/inclusive-design-principles:'. $site_url .'">' . __( 'principles of Inclusive Design', 'ncsu-a11y-helper' ) . '</a>, ' . __( 'and the ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/universal-design-principles:'. $site_url .'">' . __( 'principles of Universal Design', 'ncsu-a11y-helper' ) . '</a>.</p>'
                         . '<h3>' . __( 'Web Accessibility at NC State', 'ncsu-a11y-helper' ) . '</h3>'
                         . '<p><strong>' . __( 'NC State University is committed to providing a barrier-free IT environment to all people.', 'ncsu-a11y-helper' ) . '</strong> ' . __( 'That commitment reflects the university\'s legal obligations as well as our dedication to building an inclusive campus community.', 'ncsu-a11y-helper' ) . '</p>'
                         . '<p>' . __( 'The Office of Information Technology makes resources available to campus to help reach our accessibility goals, including this Accessibility Check tool. If you have any questions, please contact the ', 'ncsu-a11y-helper' ) . '<a href="https://help.ncsu.edu/">' . __( 'Help Desk', 'ncsu-a11y-helper' ) . '</a>.</p>'
                         . '<p>' . __( 'Learn more about ', 'ncsu-a11y-helper' ) . '<a href="https://accessibility.ncsu.edu/">' . __( 'accessibility at NC State', 'ncsu-a11y-helper' ) . '</a> ' . __( 'and ', 'ncsu-a11y-helper' ) . '<a href="https://accessibility.oit.ncsu.edu/">' . __( 'IT accessibility on campus', 'ncsu-a11y-helper' ) . '</a>.</p>'
                         . '<h3>' . __( 'What Can I Do?', 'ncsu-a11y-helper' ) . '</h3>'
                         . '<p>' . __( 'When creating content on this website, be sure to run an accessibility check before publishing:', 'ncsu-a11y-helper' ) . '</p>'
                         . '<p>[a11y_check]</p>'
                         . '<p>' . __( 'The accessibility check will scan your post or page for common accessibility issues. If it detects something that might be an issue, it will highlight that part of your post or page, and provide more information about what the issue is, why it matters, and how you can fix it.', 'ncsu-a11y-helper' ) . '</p>'
                         . '<p>' . __( 'But automated testing can\'t detect everything. As you write and build your post or page, think about potential barriers that would prevent some users from understanding your content. Some easy manual tests you can do include:', 'ncsu-a11y-helper' ) . '</p>'
                         . '<ul>'
                         . '<li><strong>' . __( 'View your page with volume muted.', 'ncsu-a11y-helper' ) . '</strong> ' . __( 'If you have audio or video, do you have captions or a transcript? (Learn more about the ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/caption-grant:'. $site_url .'">' . __( 'NC State captioning grant', 'ncsu-a11y-helper' ) . '</a>.)</li>'
                         . '<li><strong>' . __( 'Use your page without using a mouse.', 'ncsu-a11y-helper' ) . '</strong> ' . __( 'Can you navigate to all links using only your keyboard\'s [TAB] key?', 'ncsu-a11y-helper' ) . '</li>'
                         . '<li><strong>' . __( 'Listen to your page using a screen reader.', 'ncsu-a11y-helper' ) . '</strong> ' . __( 'Mac users can activate ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/apple-voiceover:'. $site_url .'">' . __( 'VoiceOver', 'ncsu-a11y-helper' ) . '</a>. ' . __( 'Windows users can activate ', 'ncsu-a11y-helper' ) . '<a href="http://go.ncsu.edu/microsoft-narrator:'. $site_url .'">' . __( 'Narrator', 'ncsu-a11y-helper' ) . '</a>.</li>'
                         . '</ul>';

    $pages = array(
        'ncsu_a11y'  => array(
            'parent_slug'   => 'options-general.php',
            'page_title'    => __( 'NC State Accessibility Helper', 'ncsu-a11y-helper' ),
            'menu_slug'     => 'ncsu_a11y',
            'menu_title'    => 'Accessibility Helper',
            'sections'      => array(
                'post-types'   => array(
                    'title'         => __( 'Select Post Types to Check', 'ncsu-a11y-helper' ),
                    'text'          => '<p>' . __( 'Choose which post types should have the Accessibility Helper meta box and "Accessibility Check" button to open the annotated preview. We recommend ALL post types be included, but there may be some special cases where it doesn\'t make sense.', 'ncsu-a11y-helper' ) . '</p>',
                    'fields'        => array(
                        'post-types'    => array(
                                                'title'     => __( 'Post Types', 'ncsu-a11y-helper' ),
                                                'type'      => 'checkbox',
                                                'choices'   => $post_types_choices,
                                            ),
                        ),
                ),
                'wrapper-element'   => array(
                    'title'         => __( 'Wrapper Elements', 'ncsu-a11y-helper' ),
                    'text'          => '<p>' . __( 'For each post type (and for each page template), enter the element ID or class that contains your post content. We use WordPress post type classes by default, but some themes may require different IDs or classes. See the ', 'ncsu-a11y-helper' ) . '<a href="#">' . __( 'NC State Accessibility Helper documentation', 'ncsu-a11y-helper' ) . '</a> ' . __( 'for an example and more details.', 'ncsu-a11y-helper' ) . '</p>',
                    'fields'        => $wrapper_elements,
                ),
                'custom-meta-text'   => array(
                    'title'         => __( 'Custom Meta Box Text', 'ncsu-a11y-helper' ),
                    'text'          => '<p>' . __( 'By default, the Accessibility Helper meta box contains basic information about accessibility and links to useful learning resources. You can customize the message your users see by editing the text below. NOTE: We strongly recommend you use this space to encourage users to learn more and do manual testing, because automated testing cannot identify all accessibility issues.', 'ncsu-a11y-helper' ) . '</p>',
                    'fields'        => array(
                        'meta-text'     => array(
                                                'title'         => __( 'Meta Box Text', 'ncsu-a11y-helper' ),
                                                'type'          => 'wp_editor',
                                                'text'          => __( 'Use the shortcode ', 'ncsu-a11y-helper' ) . '<strong>[a11y_check]</strong> ' . __( 'to insert the "Run Accessibility Check" button.', 'ncsu-a11y-helper' ),
                                                'value'         => $default_meta_text,
                                                'sanitize'      => false,
                                            ),
                        ),
                ),
                'custom-config'   => array(
                    'title'         => __( 'Custom Configuration File', 'ncsu-a11y-helper' ),
                    'text'          => '<p>' . __( 'You can customize the messages your users see, learning resource links, and enable/disable individual accessibility tests through a JSON configuration file. See the ', 'ncsu-a11y-helper' ) . '<a href="#">' . __( 'NC State Accessibility Helper documentation', 'ncsu-a11y-helper' ) . '</a> ' . __( 'for an example and more details.', 'ncsu-a11y-helper' ) . '</p>',
                    'fields'        => array(
                        'config_file'         => array(
                            'title'         => __( 'Configuration File Upload', 'ncsu-a11y-helper' ),
                            'type'          => 'media',
                            'id'            => 'config_file',
                        ),
                    ),
                ),
                'info'   => array(
                    'title'         => __( 'About This Plugin', 'ncsu-a11y-helper' ),
                    'text'          => '<p>' . __( 'The NC State Accessibility Helper is a project of the NC State Office of Information Technology\'s Design team, the university\'s IT Accessibility Coordinator, and many helpful contributors. If you want to help improve this tool, please consider ', 'ncsu-a11y-helper' ) . '<a href="https://github.ncsu.edu/oitdesign/ncsu-a11y-helper">' . __( 'contributing on GitHub', 'ncsu-a11y-helper' ) . '</a>!</p>' 
                                        . '<h3 style="font-size: 1.1em;">' . __( 'About These Accessibility Tests', 'ncsu-a11y-helper' ) . '</h3>' . '<p>' . __( 'The majority of the accessibility tests included in this plugin are powered by ', 'ncsu-a11y-helper' ) . '<a href="https://axe-core.org/">' . __( 'aXe-core by Deque Systems', 'ncsu-a11y-helper' ) . '</a>. ' . __( 'aXe-core is designed to avoid false positives, which provides a solid testing foundation. In some cases, we have written our own custom tests that sit on top of aXe, in order to prompt content creators to pay closer attention to common issues.', 'ncsu-a11y-helper' ) . '</p>' 
                                        . '<h3 style="font-size: 1.1em;">' . __( 'Statement of Philosophy', 'ncsu-a11y-helper' ) . '</h3>' . '<p>' . __( 'Everything we build should be accessible, and the only way we can do that is if we work with all of our content creators and engage them in the effort. This plugin is about detecting and fixing issues before they\'re ever published, educating content creators about accessibility best practices, and (hopefully) inspiring our content creators to think about how they can help build a more inclusive user experience.', 'ncsu-a11y-helper' ) . '</p>',
                ),
            ),
        ),
    );
    $option_page = new RationalOptionPages( $pages );
});