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
                                                'title' => 'page',
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

    $pages = array(
        'ncsu_a11y'  => array(
            'parent_slug'   => 'options-general.php',
            'page_title'    => __( 'NC State Accessibility Helper', 'ncsu-a11y-helper' ),
            'menu_slug'     => 'ncsu_a11y',
            'menu_title'    => 'Accessibility Helper',
            'sections'      => array(
                'post-types'   => array(
                    'title'         => __( 'Select Post Types to Check', 'ncsu-a11y-helper' ),
                    'text'          => '<p>' . __( 'Choose which post types should have the Accessibility Helper meta box and "Accessibility Check" button to open the annotated preview. We recommend all post types be included, but there may be some special cases where it doesn\'t make sense.', 'ncsu-a11y-helper' ) . '</p>',
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
                    'text'          => '<p>' . __( 'For each post type (and for each page template), enter the element ID or class that contains your post content. We use WordPress post type classes by default, but some themes may require different IDs or classes. See the ', 'ncsu-a11y-helper' ) . '<a href="https://design.oit.ncsu.edu/docs/a11y-helper">' . __( 'NC State Accessibility Helper documentation', 'ncsu-a11y-helper' ) . '</a> ' . __( 'for an example and more details.', 'ncsu-a11y-helper' ) . '</p>',
                    'fields'        => $wrapper_elements,
                ),
                'custom-config'   => array(
                    'title'         => __( 'Custom Configuration File', 'ncsu-a11y-helper' ),
                    'text'          => '<p>' . __( 'You can customize the messages your users see, learning resource links, and enable/disable individual accessibility tests through a .txt configuration file. See the ', 'ncsu-a11y-helper' ) . '<a href="https://design.oit.ncsu.edu/docs/a11y-helper">' . __( 'NC State Accessibility Helper documentation', 'ncsu-a11y-helper' ) . '</a> ' . __( 'for an example and more details.', 'ncsu-a11y-helper' ) . '</p>',
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
                    'text'          => '<p>' . __( 'The NC State Accessibility Helper is a project of  NC State University\'s Office of Information Technology; in particular, the OIT Design & Web Services team, the IT Accessibility Coordinator, and other helpful contributors. If you want to help improve this tool, please consider ', 'ncsu-a11y-helper' ) . '<a href="https://www.github.com/briandeconinck/ncsu-a11y-helper">' . __( 'contributing on GitHub', 'ncsu-a11y-helper' ) . '</a>!</p>' 
                                        . '<h3 style="font-size: 1.1em;">' . __( 'About These Accessibility Tests', 'ncsu-a11y-helper' ) . '</h3>' . '<p>' . __( 'The majority of the accessibility tests included in this plugin are powered by ', 'ncsu-a11y-helper' ) . '<a href="https://axe-core.org/">' . __( 'aXe-core by Deque Systems', 'ncsu-a11y-helper' ) . '</a>. ' . __( 'aXe-core is designed to avoid false positives, which provides a solid testing foundation. In some cases, we have written our own custom tests that sit on top of aXe, in order to prompt content creators to pay closer attention to common issues.', 'ncsu-a11y-helper' ) . '</p>' 
                                        . '<h3 style="font-size: 1.1em;">' . __( 'Statement of Philosophy', 'ncsu-a11y-helper' ) . '</h3>' . '<p>' . __( 'Everything we build online should be accessible, and the only way we can do that is if we work with all of our content creators and engage them in that effort. This plugin is about detecting and fixing issues before they\'re ever published, educating content creators about accessibility best practices, and (hopefully) inspiring our content creators to think about how they can help build a more inclusive user experience.', 'ncsu-a11y-helper' ) . '</p>',
                ),
            ),
        ),
    );
    $option_page = new RationalOptionPages( $pages );
});