<?php
/**
 * Plugin Name: NC State Accessibility Helper (beta)
 * Description: Checks for common accessibility issues users may have when creating content in the Visual Editor, and highlights them in an annotated preview
 * Version: 0.1.0
 * Author: OIT Design
 * Author URI: https://design.oit.ncsu.edu
 *
 * GitHub Enterprise: https://github.ncsu.edu
 * GitHub Plugin URI: https://github.ncsu.edu/oitdesign/ncsu-a11y-helper
 */

// Add admin styles and scripts
function ncsu_a11y_helper__scripts( $hook ) {

        // aXe: https://github.com/dequelabs/axe-core and https://www.deque.com/products/axe/
        wp_register_script( 'axe-core', plugins_url('axe-core/axe.min.js', __FILE__), array(), null, true  );
        wp_enqueue_script( 'axe-core' );

        // Script to run aXe tests and generate annotated preview
        wp_register_script( 'a11y_tests', plugins_url('a11y_tests.js', __FILE__), array(), null, true );
        wp_enqueue_script( 'a11y_tests' );

        // Styles for metabox and annotated preview
        wp_register_style( 'a11y_styles', plugins_url('a11y_styles.css', __FILE__) );
        wp_enqueue_style( 'a11y_styles' );

        // Use WP's jQuery accordion to show/hide annotated preview
        wp_enqueue_script( 'jquery-ui-accordion' );
}
add_action( 'admin_enqueue_scripts', 'ncsu_a11y_helper__scripts' );

// Post data functions
class PostData {    

    public function myID() {
        $post_id = $_GET['post'];
        return $post_id;
    }

    public function myTitle() {
        return get_the_title( myID() );
    }

    public function myPostContent() {
        setup_postdata( $_GET['post'] );

        $content = get_the_content();
        $content = apply_filters( 'the_content', $content );
        return $content;
    }
}

$a11y_helper = new AccessibilityMeta;
return $a11y_helper;

// Accessibility Helper Metabox
class AccessibilityMeta
{
    /**
     * define what post types our meta should reference
     */
    private $postTypes = array(
        'page',
        'post',
    );

    /**
     * AccessibilityMeta constructor.
     */
    public function __construct()
    {
        // register a meta box
        add_action('add_meta_boxes', array($this, 'addMyMetaBox'));
    }

    /**
     * add our meta box, define the arguments
     */
    public function addMyMetaBox()
    {
        /**
         * iterate our affected post types to add the meta box to them
         *
         * last two arguments define placement, priority of metabox
         * see wp ref: https://developer.wordpress.org/reference/functions/add_meta_box/
         */
        foreach ($this->postTypes as $postType) {
            add_meta_box(
                'ncsu-a11y-helper',
                __('NC State Accessibility Helper (beta)', 'ncsu_a11y_helper'),
                array($this, 'ncsu_a11y_metabox'),
                $postType,
                'normal',
               // 'normal',
                'high'
            );
        }
    }
    
    public function ncsu_a11y_metabox()
    {
        // note that we can return straight HTML here, or extract
        // the content into methods. Just remember to RETURN instead
        // of echoing your content
        ?>

        <?php 

         $screen = get_current_screen();
         $post = new PostData;

         $id = $post->myID();
         $content = $post->myPostContent();

        // Metabox
        if ( empty( $post->myID() ) ) {
            echo __('Save your draft '. $screen->post_type .', then check this space again to see if we\'ve detected any accessibility issues!', 'ncsu_a11y_helper');

        } else {
            
        // Annotated preview
        ?>

        <div id="issues-detected">

            <h3>Automated Accessibility Testing</h3>

            <p><a href="https://en.wikipedia.org/wiki/Web_accessibility" target="_blank">Web accessibility</a> is important at NC State. Click the button below to generate a preview highlighting potential accessibility issues in your <?php echo $screen->post_type; ?>.</p>

            <p>For each issue that we were able to detect, we have included links to documentation explaining the issue, why it is important, and what you need to do to resolve it. (<strong>Note:</strong> Some theme and plugin styles may not be reflected in this preview.)</p>

        </div>

            <?php 
                if ( wp_is_post_autosave( $id ) ) {
                    
                    echo '<p>Save your '. $screen->post_type .' to test the latest changes.</p>';

                }
            ?>

            <script type="text/javascript">
            jQuery(function($){
                $(".accordion").accordion({ 
                    header: "a.annotated-preview-button",
                    heightStyle: "content",
                    collapsible: true,
                    active: false
                    });
            });
            </script>

            <div class="accordion">
                <h3>Annotated Preview</h3>
                <div>
                    <a href="#" class="annotated-preview-button button">Show/Hide Annotated Preview</a>
                    <div id="annotated-preview">

                        <div id="a11y-key">
                            <h3>Key</h3>

                            <p>Detected accessibility issues are categorized as:</p>
                            <ul>
                                <li><span class="a11y-critical-indicator a11y-indicator" aria-hidden="true"></span><strong>Critical</strong></li>
                                <li><span class="a11y-serious-indicator a11y-indicator" aria-hidden="true"></span><strong>Serious</strong></li>
                                <li><span class="a11y-moderate-indicator a11y-indicator" aria-hidden="true"></span><strong>Moderate</strong></li>
                                <li><span class="a11y-minor-indicator a11y-indicator" aria-hidden="true"></span><strong>Minor</strong></li>
                            </ul>
                            <p>Some issues may also be categorized as:</p>
                            <ul>
                                <li><span class="a11y-info-indicator a11y-indicator" aria-hidden="true"></span><strong>Information</strong></li>
                            </ul>
                            <p>That means we've detected something that might be an issue, but we can't tell for sure. It's up to you to read the documentation link and make that judgment.</p>
                        </div>

                        <section id="annotated-content">
                            <?php echo '<h1 class="entry-title">' . get_the_title($id) . '</h1>'; ?>

                            <?php echo $content; ?>
                        </section>

                    </div>

                </div>
            </div>


        <div id="no-issues-detected">

            <h3>Manual Accessibility Testing</h3>

            <p>If there are no issues were detected in the annotated preview above, that doesn't mean you shouldn't be thinking about accessibility! Try viewing your <?php echo $screen->post_type; ?>...</p>

                <ul>
                    <li><strong>With volume muted.</strong> If you have audio or video, do you have captions or a transcript? (Learn more about the <a href="https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/faculty/captioning-grant/">NC State captioning grant</a>.)</li>
                    <li><strong>Without using a mouse.</strong> Can you navigate to all links using only your keyboard's [TAB] key?</li>
                    <li><strong>Using a screen reader.</strong> Mac users can activate <a href="https://help.apple.com/voiceover/info/guide/10.12/">VoiceOver</a>. Windows users can activate <a href="https://support.microsoft.com/en-us/help/17173/windows-10-hear-text-read-aloud">Narrator</a> or download <a href="https://www.nvaccess.org/">NVDA</a> for free.</li>
                    <li><strong>With no CSS.</strong> Does your <?php echo $screen->post_type; ?> content still make sense without styles?</li>
                </ul>

            <p>And it's always a good idea to <a href="https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/">learn more about accessibility</a> in general.</p>


        </div>

        

        <?php 
        }

    }

}

