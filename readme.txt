=== NC State Accessibility Helper (beta) ===
Contributors: OIT Design
Donate link: https://design.oit.ncsu.edu/
Tags: accessibility
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 4.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
Checks for common accessibility issues users may have when creating content in the Visual Editor, and highlights them in an annotated preview
 
== Description ==

Wouldn't it be great if content creators caught and fixed their accessibility issues *before* they hit the `Publish` button?

The **NC State Accessibility Helper** tests your content and generates an annotated preview inside of WordPress. In the annotated preview, detected accessibility issues are highlighted, and the annotation describes the detected issue and links to a documentation resource where you can learn more about the issue and how to avoid it.

This plugin is VERY beta. [Contributions and collaboration are welcome!](https://github.ncsu.edu/oitdesign/ncsu-a11y-helper)

= How It Works =

The **NC State Accessibility Helper** meta box appears on the edit screen of every post type. Inside that metabox, we use `the_content()` to generate a preview. (Why `the_content()`? It's important to us to render shortcodes prior to preview.) This doesn't give a perfect preview of what your content will look like, but it's close enough for our purposes here.

We then call a JavaScript file that performs a variety of tests on the HTML contents of that preview. For most tests, we use the [aXe Accessibility Engine](https://github.com/dequelabs/axe-core) by [Deque Systems](https://www.deque.com/) to perform accessibility tests on content in WordPress. 

The plugin also defines and tests some custom rules. aXe is very conservative and tries to avoid all false positives, and so doesn't test anything they can't be certain about. But there are some cases where we'd rather have a false positive that puts helpful information in front of our users and gets them thinking about best practices. For those situations, we write a custom rule.

If the JS file detects a violation, it will:
* Wrap the offending code in a `span`, which we style to indicate the type of violation
* Append an annotation to the offending code, which we style to position in the right-hand margin

For aXe tests, annotations use the default help text and help URL provided by aXe. In cases where we'd rather write our own or send users to a different help resource, we can override that.

aXe categorizes detected issues with impact levels:
* Critical
* Serious
* Moderate
* Minor

For our custom rules, we either assign one of those impact levels, or otherwise assign an impact of **Info**.

 
== Installation ==
 
This plugin is available to campus through Cthulhu.
 
== Frequently Asked Questions ==
 
FAQ to come
 
== Screenshots ==
 
Screenshots to come
 
== Changelog ==
 
= 0.1.0 =
* Initial release to campus
 
== Upgrade Notice ==
 
= 0.1.0 =
Initial release to campus
