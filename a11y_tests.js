// Accessibility Tests

(function($) {

	// Custom messages, for custom tests or when we want to override aXe messages
	var ncsu_tests = {
	    ncsu_skipped_heading : {
	    						id : 'ncsu_skipped_heading',
	    						impact : 'serious',
	    						help : 'Do not skip heading levels. Headings should not increment by more than 1.',
	    						helpurl : 'https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/headings/',
						    	},
		ncsu_multiple_h1 : {
	    						id : 'ncsu_multiple_h1',
	    						impact : 'moderate',
	    						help : 'In most themes, you should not use Heading 1\'s in your content.',
	    						helpurl : 'https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/headings/',
						    	},
		ncsu_empty_alt : {
								id : 'ncsu_empty_alt',
								impact : 'moderate',
								help : 'Image alt attributes should be empty only if the image is purely decorative.',
								helpurl : 'https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/alternative-text/',
								},
		ncsu_filename_alt : {
								id : 'ncsu_filename_alt',
								impact : 'minor',
								help : 'Image alt attributes should describe the image contents, not just be the file name.',
								helpurl : 'https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/alternative-text/',
								},

		ncsu_reminder_alt : {
								id : 'ncsu_reminder_alt',
								impact : 'info',
								help : 'For non-decorative images, double-check your alt text to be sure it describes the contents of the image.',
								helpurl : 'https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/alternative-text/',
								},

		ncsu_reminder_table : {
								id : 'ncsu_reminder_table',
								impact : 'info',
								help : 'Be sure to use tables for tabular information only, eg. data tables. Do not use tables for layout.',
								helpurl : 'https://accessibility.oit.ncsu.edu/it-accessibility-at-nc-state/developers/accessibility-handbook/tables/',
								},
	};

	var issues_detected = false;

	/***** aXe Testing  *****/

	// aXe Context: We're only going to be testing the #annotated-preview, the div we've created containing the_content()
	var context = { include: ['#annotated-content'] };
	
	// aXe Options: Skip some rules that aren't relevant to what you can do in the WP Visual Editor, ie. things that are issues in a theme that a developer could fix, but are outside the scope of a content creator's work
	/*var options = {
					rules: {
						"frame-title": { enabled: false },
						"frame-title-unique": { enabled: false },
						"bypass": { enabled: false },
					}
				}; */


	axe.run(context, function(err, results) {
		if (err) throw err;

		// Merge incompletes into the violations array
		var violations = $.merge(results['violations'], results['incomplete']);

		if (violations.length > 0) {
			issues_detected = true;
		}

		// For each violation...
		$.each(violations, function( i, violation ) {

			var description = violation['description'];
			var help = violation['help'];
			var helpurl = violation['helpUrl'];
			var id = violation['id'];

			// Make all "null" impacts "info" -- Not 100% sure about this choice, but don't want people ignoring potential issues labeled "null"
			if ( !violation['impact'] ) {
				var impact = 'info';
			} else {
				var impact = violation['impact'];
			}

			// Build annotation
			var annotation = '<a class="a11y-annotation" href="' 
								+ helpurl 
								+ '" target="_blank">' 
								+ '<span class="a11y-indicator a11y-' + impact + '-indicator" aria-hidden="true"></span>'
								+ '<strong class="a11y-impact">' + impact + ':</strong> '
								+ '<span class="a11y-help">' + help + '</span>' 
								+ '</a>';

			$.each(violation['nodes'], function( j, instance ) {
				var target = instance['target'][0];

				$(target).wrap(function() {
 						return "<span class='a11y-issue a11y-" + impact + " a11y-" + id + "'></span>";
					});

			});

			$(annotation).appendTo( $('.a11y-' + id) );

		});

		console.log(results);

	});



	/***** Additional Custom Testing  *****/
	// Extra tests not included in aXe, either because we're more strict/less concerned with false positives, or because we want to give our users additional best practices

	// Check each heading
			// - Did you skip a heading level?
			// - Do you have extra h1's?
			// - Do you have a lot of text in a heading?
			// - Are you using <strong>...</strong> as a heading?

			$('#annotated-content h1,#annotated-content h2,#annotated-content h3,#annotated-content h4,#annotated-content h5,#annotated-content h6').addClass('a11y-heading');

			// Empty variable for comparing against the previous heading
			var previous = null;

			$('#annotated-content .a11y-heading').each(
		        function(){

		        	var depthcurrent = parseInt(this.tagName.substring(1));

		        	// Check for skipped heading levels
		        	if ( previous ) {

		        		var ncsu_test = ncsu_tests.ncsu_skipped_heading;
		        		
		        		var diff = depthcurrent - previous;

		        		if ( diff > 1 ) {

		        			$(this).wrap(function() {
 								return "<span class='a11y-issue a11y-" + ncsu_test.impact + " a11y-" + ncsu_test.id + "'></span>";
							});

		        		}

		        	}

		        	// Make current depth previous for the next heading in the each loop
		        	previous = depthcurrent;  

		        	var depthcurrent = parseInt(this.tagName.substring(1));

		        	// Are you using h1 headings in your post or page?
		        	if ( depthcurrent == 1 && !$(this).hasClass('entry-title') ) {

		        		var ncsu_test = ncsu_tests.ncsu_multiple_h1;

		        		$(this).wrap(function() {
 							return "<span class='a11y-issue a11y-" + ncsu_test.impact + " a11y-" + ncsu_test.id + "'></span>";
						});

		        	}  	

		        }
		           	
		    ); 	

	// Check each image
			// Does the image have an alt attribute? (Covered by aXe test)
			// Is the alt attribute empty?
			// Does the alt attribute exactly match the image file name?

			$('#annotated-preview img').each(
				        function(){

				        	if ( $(this)[0].hasAttribute('alt') ) {
				        		var imgalt = $(this).attr('alt');
				        		var imgsrc = $(this).attr('src');
				        		var imgfile = imgsrc.split('/').pop();
				        		var re = /(?:\.([^.]+))?$/;
				        		var extension = re.exec(imgfile)[0];
				        		var imgfilename = imgfile.replace(extension, '')

				        		if ( imgalt == false || imgalt == typeof undefined ) {
				        			// Empty alt attribute
				        			
				        			var ncsu_test = ncsu_tests.ncsu_empty_alt;

					        		$(this).wrap(function() {
			 							return "<span class='a11y-issue a11y-" + ncsu_test.impact + " a11y-" + ncsu_test.id + "'></span>";
									});

				        		} else if ( imgalt == imgfilename ) {
				        			// Image alt matches filename exactly

				        			var ncsu_test = ncsu_tests.ncsu_filename_alt;

					        		$(this).wrap(function() {
			 							return "<span class='a11y-issue a11y-" + ncsu_test.impact + " a11y-" + ncsu_test.id + "'></span>";
									});

				        		} else {
				        			// Alt text reminder

				        			var ncsu_test = ncsu_tests.ncsu_reminder_alt;

					        		$(this).wrap(function() {
			 							return "<span class='a11y-issue a11y-" + ncsu_test.impact + " a11y-" + ncsu_test.id + "'></span>";
									});
				        		}

				        	} else {
				        		// There is no alt attribute. Should be captured by aXe test
				        	}



				        }
				    );

			// Check each table
			// Reminder: Tables shouldn't be used for layout

			$('#annotated-preview table').each(
				        function(){

				        	// Alt text reminder

				        	var ncsu_test = ncsu_tests.ncsu_reminder_table;

					        $(this).wrap(function() {
			 					return "<span class='a11y-issue a11y-" + ncsu_test.impact + " a11y-" + ncsu_test.id + "'></span>";
							});		
				        }
				    );



			// Add annotations for each additional test
		    $.each(ncsu_tests, function(j, my_ncsu_test) {

		    	// Build annotation
		    	var annotation = '<a class="a11y-annotation" href="' 
		    						+ my_ncsu_test.helpurl 
		    						+ '" target="_blank">' 
		    						+ '<span class="a11y-indicator a11y-' + my_ncsu_test.impact + '-indicator" aria-hidden="true"></span>'
		    						+ '<strong class="a11y-impact">' + my_ncsu_test.impact + ':</strong> '
		    						+ '<span class="a11y-help">' + my_ncsu_test.help + '</span>' 
		    						+ '</a>';

		    	$(annotation).appendTo( $('.a11y-' + my_ncsu_test.id) );

		    });


		/*    if (issues_detected) {
		    	$('#issues-detected').css('display', 'block');
		   	} else {
		   		$('#no-issues-detected').css('display', 'block');
		   	} */


})( jQuery );