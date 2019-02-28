<?php

if ( function_exists('is_multisite') ) {

	if ( is_multisite() ) {

		add_action( 'network_admin_menu', 'ncsu_a11y_multisite_options' );
		
		function ncsu_a11y_multisite_options() {
		
			add_submenu_page(
				'settings.php', // Parent element
				'NC State Accessibility Helper Settings', // Text in browser title bar
				'Accessibility Helper', // Text to be displayed in the menu.
				'manage_options', // Capability
				'ncsu-a11y-helper', // Page slug, will be displayed in URL
				'ncsu_a11y_network_options' // Callback function which displays the page
			);
		
		}
	
		add_action('network_admin_edit_my_settings', __FILE__, 'ncsu_a11y_update_network_setting');
	
		function ncsu_a11y_update_network_setting() {
			update_site_option( 'ncsu_a11y_network_options', $_POST[ 'ncsu_a11y_network_options' ] );
			wp_redirect( add_query_arg( array( 'page' => ncsu_a11y_network_options, 'updated' => 'true' ), network_admin_url( 'settings.php' ) ) );
			exit;
		}
	
		function ncsu_a11y_network_options() {
			
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
	
			if ( isset( $_GET['settings-updated'] ) ) {
				add_settings_error( 'settings_boilerplate_messages', 'settings_boilerplate_message', __( 'Settings Saved', 'settings_boilerplate' ), 'updated' );
			}
	
			?>
	
			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
	
				<p>Add a JSON configuration file to set network-wide defaults, including:</p>
	
				<ul>
					<li>Post types that should include the accessibility helper</li>
					<li>Wrapper elements for each post type and page template</li>
					<li>Custom rule sets and feedback messages</li>
				</ul>
	
				<form action="edit.php?action=ncsu_a11y_network_options" method="post">
				<?php
					// WordPress will do most of the work for us in outputting the form
					// output security fields for the registered setting "settings_boilerplate"
					settings_fields( 'settings_boilerplate' );
					// output setting sections and their fields
					// (sections are registered for "settings_boilerplate", each field is registered to a specific section)
					do_settings_sections( 'settings_boilerplate' );
					// output save settings button
					submit_button( 'Save Settings' );
				?>
				</form>
			</div>
			<?php
	
		}
	
	}

}
