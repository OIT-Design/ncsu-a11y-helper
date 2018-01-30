=== NC State Accessibility Helper ===
Contributors: OIT Design & Web Services, NC State University
Donate link: https://design.oit.ncsu.edu/
Tags: accessibility
Requires at least: 3.0.1
Tested up to: 4.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Checks for common accessibility issues users may have when creating content in the Visual Editor, and highlights them in an annotated preview
 
== Description ==

Wouldn't it be great if content creators caught and fixed their accessibility issues *before* they hit the `Publish` button?

The **NC State Accessibility Helper** tests your content and generates an annotated preview inside of WordPress. In the annotated preview, detected accessibility issues are highlighted, and the annotation describes the detected issue and links to a documentation resource where you can learn more about the issue and how to avoid it.

[Contributions and collaboration are welcome!](https://github.com/briandeconinck/ncsu-a11y-helper)

= How It Works =

In the Accessibility Helper options (Dashboard > Settings > Accessibility Helper), select the post types that you wish to be checkable using the Accessibility Helper. By default, the Accessibility Helper will check the contents of the `.type-[post-type]` element, but you can change this on the options page as well.

When editing a post, page, or custom post type for which the Accessibility Helper is enabled, you will see a "Run Accessibility Check" button in the "Publish" metabox, just above the Publish/Update button.

Clicking the "Run Accessibility Check" button will open an annotated preview. This is similar to a normal post preview, but with two additional JavaScript files and one additional CSS file. These JavaScript files perform a variety of tests on the contents of that preview. For most tests, we use the [aXe Accessibility Engine](https://github.com/dequelabs/axe-core) by [Deque Systems](https://www.deque.com/) to perform accessibility tests on content in WordPress. 

The plugin also defines and tests some custom rules. aXe is very conservative and tries to avoid all false positives, and so doesn't test anything they can't be certain about. But there are some cases where we'd rather have a false positive that puts helpful information in front of our users and gets them thinking about best practices. For those situations, we write a custom rule.

If the JS file detects an issue, it will:
* Wrap the offending code in a `span`, which we style to indicate the impact of the issue
* Append an annotation to the offending code
* Generates a modal dialog that users can view to see more information about the detected issue

aXe categorizes detected issues with impact levels:
* Critical
* Serious
* Moderate
* Minor

For our custom rules, we either assign one of those impact levels, or otherwise assign an impact of **Info**.

For aXe tests, annotations use the default help text and help URL provided by aXe. In cases where we'd rather write our own or send users to a different help resource, we can override that in either the `ncsu_defaults.php` file or by uploading a custom configuration file on a site-by-site basis.

We have replaced all of the documentation links provided by aXe with NC State Go Links, so that we can track clicks and get a sense for the kinds of problems our users are encountering. These Go Links currently redirect to Deque's documentation, but may eventually point to alternative documentation for issues that we want to more carefully discuss for users.
 
== Installation ==
 
This plugin is available to NC State through Cthulhu. (Not sure what that means? Contact the Help Desk for more information.) 

Off-campus users can install the plugin via a [public GitHub repository](https://github.com/briandeconinck/ncsu-a11y-helper) using Andy Fragen's [GitHub Updater plugin](https://github.com/afragen/github-updater).
 
== Changelog ==

= 1.0.0 =
* Major revisions, now stable enough for a 1.0 tag
* Add additional custom tests
* Make helper text more generic, less NC State-y
* Resolves some accessibility issues in the plugin itself

= 0.2.0 =
* Major revisions
* Change annotated preview to take advantage of WordPress's native Preview rather than using a metabox
* Added options page for customizing which post types are checked, which elements within those post types contain the content, and customizing which tests are run and what messages are displayed to users
* Complete rewrite of most of PHP and non-aXe JavaScript files
 
= 0.1.0 =
* Initial release to campus
 
== Upgrade Notice ==

= 1.0.0 =
General improvements, plugin now more stable

= 0.2.0 =
Almost complete rewrite of the plugin

= 0.1.0 =
Initial release to campus
