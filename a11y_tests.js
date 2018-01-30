// Accessibility Tests

(function($) {

	// For HTML escaping...

	var entityMap = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#39;',
		'/': '&#x2F;',
		'`': '&#x60;',
		'=': '&#x3D;'
	};

	function escapeHtml(string) {
		return String(string).replace(/[&<>"'`=\/]/g, function (s) {
			return entityMap[s];
		});
	}

	jQuery.fn.extend({
	    getPath: function() {
	        var pathes = [];

	        
	     //   domain.each(function(index, element) {
	        this.each(function(index, element) {
	            var path, $node = jQuery(element);

	            var domain = $(this).parentsUntil( $(custom_options.wrapper) );

	         //   for (domain[i]) {
	            while ($node.length) {
	         //  	while ( $node.parentsUntil( $(custom_options.wrapper) ) ) {

	                var realNode = $node.get(0), name = realNode.localName;
	                if (!name) { break; }

	                name = name.toLowerCase();
	                var parent = $node.parent();
	                var sameTagSiblings = parent.children(name);

	                if (sameTagSiblings.length > 1)
	                {
	                    allSiblings = parent.children();
	                    var index = allSiblings.index(realNode) +1;
	                    if (index > 0) {
	                        name += ':nth-child(' + index + ')';
	                    }
	                }

	                path = name + (path ? ' > ' + path : '');
	                $node = parent;

	                i++;
	            }

	            pathes.push(path);
	        });

	        return pathes.join(',');
	    }
	});

	// Options and custom configuration stored under the custom_options object

	// Default messages for our additional, non-aXe tests
	var my_additional_tests = {};
	my_additional_tests = JSON.parse(custom_options.additional_tests_meta);

	// Plugin comes packaged with a custom "ncsu defaults" config file to enable/disable certain tests and change the messages displayed to users. Site admins can upload a custom config file to override those defaults. Either way, it gets passed to the JS right here.
	var customize_tests = {};

	if ( custom_options.config ) {
		customize_tests = custom_options.config;
	} else if ( custom_options.ncsu_defaults ) {
		customize_tests = JSON.parse(custom_options.ncsu_defaults);
	}

	// aXe Context: We're only going to be testing the contents of the wrapper element specified for this post type/page template in the plugin settings
	var context = { 
					include: [custom_options.wrapper]
					};
	
	// aXe Options: Custom enable/disable of tests
	var options = {
					"rules": {}
				};

	// If a custom config file enables or disables tests, add that to the options
	$.each(customize_tests, function(test, attribute) {
		$.each(attribute, function(key, value) {
			if ( key == 'enabled' ) {
				options.rules[test] = { 'enabled' : value };
			}
		});
	});

	// Run aXe
	axe.run(context, options, generate_annotated_preview);

	// Run additional custom tests
	function additional_tests() {

		var i = 0;

		var failed_tests = {};

				// Heading Tests
					// - Did you skip a heading level?
					// - Do you have extra h1's?
					// - Do you have a lot of text in a heading?
					// - Are you using <strong>...</strong> as a heading?

					// Empty variable for comparing against the previous heading
					var previous = null;

					$(custom_options.wrapper).find('h1,h2,h3,h4,h5,h6').each(
				        function(){

				        	var depthcurrent = parseInt(this.tagName.substring(1));

				        	// Check for skipped heading levels
				        	if ( previous ) {

				        		var diff = depthcurrent - previous;

				        		if ( diff > 1 ) {

				        			var test = 'ncsu_skipped_heading';
				        			var test_msg = $.extend(true, {}, my_additional_tests[test]);

					        		$(this).addClass(test + '-' + i);
				        			failed_tests[i] = test_msg;
				        			failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
				        			failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
									i = i + 1;       			

				        		}

				        	}

				        	// Make current depth previous for the next heading in the each loop
				        	previous = depthcurrent;  

				        	var depthcurrent = parseInt(this.tagName.substring(1));

				        	// Are you using h1 headings in your post or page?
				        	if ( depthcurrent == 1 && !$(this).hasClass('entry-title') && $(this) != $('h1:first-of-type') ) {

				        		var test = 'ncsu_multiple_h1';
				        		var test_msg = $.extend(true, {}, my_additional_tests[test]);

				        		$(this).addClass(test + '-' + i);
				        		failed_tests[i] = test_msg;
				        		failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
				        		failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
								i = i + 1;

				        	}	

				        }
				           	
				    );

				// Check each image
					// Does the image have an alt attribute? (Covered by aXe test)
					// Is the alt attribute empty?
					// Does the alt attribute exactly match the image file name?

					$(custom_options.wrapper).find('img').each(
						function(){

						    if ( $(this)[0].hasAttribute('alt') ) {
						        var imgalt = $(this).attr('alt');
						        var imgsrc = $(this).attr('src');
						        var imgfile = imgsrc.split('/').pop();
						        var re = /(?:\.([^.]+))?$/;
						        var extension = re.exec(imgfile)[0];
						        var imgfilename = imgfile.replace(extension, '');

				        		if ( imgalt == false || imgalt == typeof undefined ) {
				        			// Empty alt attribute

					        		var test = 'ncsu_empty_alt';
				        			var test_msg = $.extend(true, {}, my_additional_tests[test]);

					        		$(this).addClass(test + '-' + i);
				        			failed_tests[i] = test_msg;
				        			failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
				        			failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
									i = i + 1;

				        		} else if ( imgalt ) {
				        			// Alt text reminder
					        		var test = 'ncsu_reminder_alt';
				        			var test_msg = $.extend(true, {}, my_additional_tests[test]);

					        		$(this).addClass(test + '-' + i);
				        			failed_tests[i] = test_msg;
				        			failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
				        			failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
									i = i + 1;
						        }

					        } 

					    }
				    );

				// Check each table
					// Reminder: Tables shouldn't be used for layout

					$(custom_options.wrapper).find('table').each(
						function(){

							// Table usage reminder

				       		var test = 'ncsu_reminder_table';
				        	var test_msg = $.extend(true, {}, my_additional_tests[test]);

					        $(this).addClass(test + '-' + i);
				        	failed_tests[i] = test_msg;
				        	failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
				        	failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
							i = i + 1; 	
				        }
				    );

				// Check for ReCAPTCHAs
					// Reminder: Don't use CAPTCHAs

					$(custom_options.wrapper).find('iframe').each(
						function(){

							var iframe_src = $(this).attr('src');
							var recaptcha_src = 'https://www.google.com/recaptcha/';

							// ReCAPTCHA

							if(iframe_src.indexOf(recaptcha_src) != -1){

					       		var test = 'ncsu_captcha';
					        	var test_msg = $.extend(true, {}, my_additional_tests[test]);

						        $(this).addClass(test + '-' + i);
					        	failed_tests[i] = test_msg;
					        	failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
					        	failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
								i = i + 1;

							}
							 	
				        }
				    );

				// Check for Really Simple CAPTCHA
					// Reminder: Don't use CAPTCHAs

					$(custom_options.wrapper).find('img').each(
						function(){

							var img_alt = $(this).attr('alt');

							// Really Simple CAPTCHA

							if(img_alt == 'captcha'){

					       		var test = 'ncsu_captcha';
					        	var test_msg = $.extend(true, {}, my_additional_tests[test]);

						        $(this).addClass(test + '-' + i);
					        	failed_tests[i] = test_msg;
					        	failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
					        	failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
								i = i + 1;

							}
							 	
				        }
				    );

				// Check for common ambiguous links

					$(custom_options.wrapper).find('a').each(
						function(){

							var link_contents = $(this).text().toUpperCase();
							var here = 'here'.toUpperCase();
							var click = 'click'.toUpperCase();
							var more = 'more'.toUpperCase();
							var download = 'download'.toUpperCase();
							var read = 'read'.toUpperCase();
							var ambiguous_text_detected = 0;

							if(link_contents.indexOf(here) != -1 || link_contents.indexOf(click) != -1 || link_contents.indexOf(more) != -1 || link_contents.indexOf(download) != -1 || link_contents.indexOf(read) != -1){
								ambiguous_text_detected = 1;
							}

							if(ambiguous_text_detected == 1){
								var test = 'ncsu_ambiguous_link';
					        	var test_msg = $.extend(true, {}, my_additional_tests[test]);

						        $(this).addClass(test + '-' + i);
					        	failed_tests[i] = test_msg;
					        	failed_tests[i]['nodes'][0]['target'][0] = '.' + test + '-' + i;
					        	failed_tests[i]['nodes'][0]['html'] = $(this)[0]['outerHTML'];
								i = i + 1;
							}	
				        }
				    );

		return failed_tests;
	}

	var additional_test_violations = additional_tests();

	

	// Generate the annotated preview
	function generate_annotated_preview(err, results) {
		if (err) throw err;

		// Merge incompletes into the violations array
		var violations = $.merge(results['violations'], results['incomplete']);
		violations = $.merge(violations, additional_test_violations);

		// console.log(violations);

		// Add summary report to the end of the wrapper
		var report = `<div class="a11y-dialog" aria-hidden="true" id="a11y-report" style="border: solid 1px red; margin: 2rem; padding: 2rem;">
						<div class="a11y-dialog-overlay" tabindex="-1" data-a11y-dialog-hide></div>
						<div class="a11y-dialog-content" aria-labelledby="dialogTitle" aria-describedby="a11y-desc-report" role="dialog">
						<div role="document">
							<button data-a11y-dialog-hide class="a11y-dialog-close" aria-label="Close this dialog window">&times;</button>

							<div id="a11y-desc-report" class="a11y-sr-text">Beginning of dialog window. It begins with a heading 1 called "Detected Accessibility Issues". Escape will cancel and close the window.</div>

								<h1 id="a11y-title-report">Detected Accessibility Issues</h1>

								<ul id="a11y-report-content">

								</ul>

								<div class="a11y-dialog-footer" role="footer">
									<p><em>Accessibility testing powered by <a href="https://axe-core.org/">aXe-core, by Deque Systems</a>, with additional tests by <a href="https://design.oit.ncsu.edu/docs/a11y-helper">NC State University's OIT Design & Web Services team</a>.</em></p>
								</div>
							          
							</div>
						</div>
					</div>`;

		$(custom_options.wrapper).append(report);

		// Add button in admin toolbar to open report modal
		var generate_button = '<li id="wp-admin-bar-a11y-generate-report" class="a11y-report-generator"><button data-a11y-dialog-show="a11y-report" class="ab-item"><span class="dashicons dashicons-universal-access-alt"></span> Accessibility Report</button>		</li>';

		$('#wp-admin-bar-root-default').append(generate_button);

		// Add JS for modal dialog
		var dialogEl_x = document.getElementById('a11y-report');
		var mainEl_x = $(custom_options.wrapper);
		var dialog_x = new window.A11yDialog(dialogEl_x, mainEl_x);

		// For each violation...
		$.each(violations, function( i, violation ) {

			var id = violation['id'];

			var description = ( customize_tests[id] !== undefined && customize_tests[id]['description'] !== undefined ) ? customize_tests[id]['description'] : violation['description'];
			var help = ( customize_tests[id] !== undefined && customize_tests[id]['help'] !== undefined ) ? customize_tests[id]['help'] : violation['help'];
			var helpurl = ( customize_tests[id] !== undefined && customize_tests[id]['helpUrl'] !== undefined ) ? customize_tests[id]['helpUrl'] : violation['helpUrl'];
			var summary = ( customize_tests[id] !== undefined && customize_tests[id]['failureSummary'] !== undefined ) ? customize_tests[id]['failureSummary'] : violation['nodes'][0]['failureSummary'];

			var html = violation['nodes'][0]['html'];
			var tags = violation['tags'];

			// Make all "null" impacts "info" -- Not 100% sure about this choice, but don't want people ignoring potential issues labeled "null"
			if ( !violation['impact'] ) {
				var impact = 'info';
			} else {
				var impact = violation['impact'];
			}

			// Build annotation
			var annotation = '<div class="a11y-annotation">' 
								+ '<span class="a11y-indicator a11y-' + impact + '-indicator" aria-hidden="true"></span>'
								+ '<strong class="a11y-impact">' + impact + ':</strong> '
								+ '<span class="a11y-help">' + escapeHtml(help) + '</span>'
								+ '<button class="a11y-more-button" data-a11y-dialog-show="a11y-more-' + i + '">Learn More<span class="a11y-sr-text"> about the issue: "' + escapeHtml(help) + '"</span></button>' 
								+ '</div>';

			// Build modal dialog
			var my_dialog = `
							<div class="a11y-dialog" aria-hidden="true" id="a11y-more-` + i + `">
							      <div class="a11y-dialog-overlay" tabindex="-1" data-a11y-dialog-hide></div>
							      <div class="a11y-dialog-content" aria-labelledby="dialogTitle" aria-describedby="a11y-desc-` + i + `" role="dialog">
							        <div role="document">
							          <button data-a11y-dialog-hide class="a11y-dialog-close" aria-label="Close this dialog window">&times;</button>

								      <div id="a11y-desc-` + i + `" class="a11y-sr-text">Beginning of dialog window. It begins with a heading 1 called "` + impact + `:</strong> ` + escapeHtml(help) + `". Escape will cancel and close the window.</div>

								      <h1 id="a11y-title-` + i + `"><span class="a11y-indicator a11y-` + impact + `-indicator" aria-hidden="true"></span><strong class="a11y-impact">` + impact + `:</strong> ` + escapeHtml(help) + `</h1>

								      <div class="a11y-test-content">

									      <div class="a11y-test-info">

									          <h2>Why We Test For This</h2>
									          <p>` + escapeHtml(description) + `</p>

									          <p class="a11y-external-resource"><a href="` + escapeHtml(helpurl) + `">Learn more about this issue</a></p>

									          <h2>Recommended Action</h2>
									          <p class="a11y-summary">` + escapeHtml(summary) + `</p>

									      </div>

								          <div class="a11y-test-html">

								          	<h2>Code</h2>
								          	<div class="a11y-html-code">
								          		<code>` + escapeHtml(html) + `</code>
								          	</div>

								          </div>

								      </div>

								      <div class="a11y-dialog-footer" role="footer">
								      	<p><em>Accessibility testing powered by <a href="https://axe-core.org/">aXe-core, by Deque Systems</a>, with additional tests by <a href="https://design.oit.ncsu.edu/docs/a11y-helper">NC State University's OIT Design & Web Services team</a>.</em></p>
								      </div>
							          
							        </div>
							      </div>
							    </div>
			`;

			// Build report entry
			var entry = `
							<li class="a11y-report-entry">
								<ul>
									<li><strong>Issue:</strong> <a href="` + escapeHtml(helpurl) + `">` + escapeHtml(help) + `</a></li>
									<li><strong>Code:</strong> <code>` + escapeHtml(html) + `</code></strong>
									<li><strong>Impact:</strong> <span class="a11y-indicator a11y-` + impact + `-indicator" aria-hidden="true"></span><span class="a11y-impact">` + impact + `</span></li>
									<li><strong>Description:</strong> ` + escapeHtml(description) + `</span></li>
									<li><strong>Recommended Action:</strong> <span class="a11y-summary">` + escapeHtml(summary) + `</span></li>
								</ul>
							</li>
						`;

			// Highlight the detected issue
			$.each(violation['nodes'], function( j, instance ) {
				var target = instance['target'][0];

				$(target).wrap(function() {
 						return "<span class='a11y-issue a11y-" + impact + " a11y-" + id + "'></span>";
					});

			});

			var screen_reader_issue_intro = "<span class='a11y-sr-text'>The following content has a possible accessibility issue. After the content, a description of the detected error will be read.</span>";

			// Tell screen reader users that they've reached an issue
			$(screen_reader_issue_intro).prependTo( $('.a11y-' + id) );

			// Add annotation to the detected issue
			$(annotation).appendTo( $('.a11y-' + id) );

			// Add modal dialog with issue details
			$(custom_options.wrapper).append(my_dialog);

			// Report Entries
			$('#a11y-report-content').append(entry);

			// Add JS for modal dialog
			var dialogEl = document.getElementById('a11y-more-' + i);
			var mainEl = $(custom_options.wrapper);
			var dialog = new window.A11yDialog(dialogEl, mainEl);

		});

	}

})( jQuery );

