<?php 

$additional_tests_meta = array(
    "ncsu_skipped_heading" => array(
        "id" => "ncsu_skipped_heading",
        "impact" => "serious",
        "help" => "Do not skip heading levels. Headings should not increment by more than 1.",
        "helpUrl" => "https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/headings/",
        "nodes" => array(
            0 => array(
                "failureSummary" => "Change your heading so that it is at most 1 larger than the previous heading (eg. if the previous heading was a Heading 2, this heading could be a Heading 3, but not a Heading 4).",
                "target" => array(
                    0 => null,
                )
            )
        ),
        "description" => "Headings should be used to denote sections and subsections of content, and provide structure to your content, especially for those using screen-reading software. Skipping heading levels can be disorienting.",
        "html" => null,
    ),
    "ncsu_multiple_h1" => array(
        "id" => "ncsu_multiple_h1",
        "impact" => "info",
        "help" => "In most cases, you should not use Heading 1's in your content.",
        "helpUrl" => "https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/headings/",
        "nodes" => array(
            0 => array(
                "failureSummary" => "Change your heading to a different heading level. Headings should be used to provide structure (like an outline), not to emphasize important content.",
                "target" => array(
                    0 => null,
                )
            )
        ),
        "description" => "In most cases, your page or post title will be a Heading 1. It should be the only Heading 1 in your post or page.",
        "html" => null,
    ),
    "ncsu_empty_alt" => array(
        "id" => "ncsu_empty_alt",
        "impact" => "moderate",
        "help" => "Image alt attributes should be empty only if the image is purely decorative.",
        "helpUrl" => "https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/alternative-text/",
        "nodes" => array(
            0 => array(
                "failureSummary" => "If your image is not purely decorative, add a text description of the contents of the image in the image's alt attribute.",
                "target" => array(
                    0 => null,
                )
            )
        ),
        "description" => "If your image is purely decorative (no information is being communicated to the user by including the image), then empty alt text is acceptable. Otherwise, you must provide the text equivalent of the image in the alt attribute.",
        "html" => null,
    ),
    "ncsu_reminder_alt" => array(
        "id" => "ncsu_reminder_alt",
        "impact" => "info",
        "help" => "Double-check your alt text to be sure it describes the contents of the image.",
        "helpUrl" => "https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/alternative-text/",
        "nodes" => array(
            0 => array(
                "failureSummary" => "Double-check your alt text. Make sure it's not just a filename or non-descriptive placeholder text.",
                "target" => array(
                    0 => null,
                )
            )
        ),
        "description" => "Alt attributes should provide a text equivalent of any information being communicated by the image. This is often a description of the contents of the image, emphasizing any key details or points users are expected to recognize.",
        "html" => null,
    ),
    "ncsu_reminder_table" => array(
        "id" => "ncsu_reminder_table",
        "impact" => "info",
        "help" => "Be sure to use tables for tabular information only, eg. data tables. Do not use tables for layout.",
        "helpUrl" => "https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/tables/",
        "nodes" => array(
            0 => array(
                "failureSummary" => "Double-check your table and verify that it is being used to communicate tabular information. If it is being used for layout, find another way to organize content that doesn't use tables.",
                "target" => array(
                    0 => null,
                )
            )
        ),
        "description" => "Tables should be used for displaying data or otherwise communicating information that can be sorted into labeled rows and columns. Tables should not be used for layout or aesthetic purposes.",
        "html" => null,
    ),
    "ncsu_captcha" => array(
        "id" => "ncsu_captcha",
        "impact" => "info",
        "help" => "CAPTCHAs can be an accessibility barrier. Avoid using them whenever possible.",
        "helpUrl" => "http://accessibility.psu.edu/captcha/",
        "nodes" => array(
            0 => array(
                "failureSummary" => "This CAPTCHA has likely been added by a plugin. Check the plugin options (or ask a site administrator to do so) to see if there is an alternative to using a CAPTCHA.",
                "target" => array(
                    0 => null,
                )
            )
        ),
        "description" => "While some CAPTCHAs can be made accessible, many present a barrier for users with disabilities and create a poor user experience in general. Consider alternatives to CAPTCHAs whenever possible.",
        "html" => null,
    ),
    "ncsu_ambiguous_link" => array(
        "id" => "ncsu_ambiguous_link",
        "impact" => "info",
        "help" => "Make sure your link text is unambiguous and makes sense out of context.",
        "helpUrl" => "https://www.wuhcag.com/link-purpose-link-only/",
        "nodes" => array(
            0 => array(
                "failureSummary" => "Our scan has detected words often associated with ambiguous link text, eg. 'click here' or 'download now.' When phrases like those are the only words in the link, the meaning of the link is unclear. Double-check your link text and avoid these kinds of link phrasings. For example, 'Click here to view examples of our work' (with 'click here' as the link and 'to view examples of our work' as context) could be rewritten simply as 'View examples of our work' (with the entire phrase as part of the link).",
                "target" => array(
                    0 => null,
                )
            )
        ),
        "description" => "Some screen reader users scan a web page by skipping between links, and the text of each link is announced without the context of the paragraph around it. The text of the link should be unambiguous and should clearly indicate its destination or its purpose.",
        "html" => null,
    )
);