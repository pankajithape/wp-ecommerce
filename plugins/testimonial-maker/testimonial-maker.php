<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
@package Testimonial Maker
Plugin Name: Testimonial Maker
Plugin URI:  https://awplife.com/
Description: A very easy Plugin for make testimonials.
Version:     1.1.14
Author:      A WP Life
Author URI:  https://awplife.com/
Text Domain: testimonial-maker
Domain Path: /languages
License:     GPL2

Testimonial Maker is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Testimonial Maker is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Testimonial Maker. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html.
*/

if ( ! class_exists( 'awl_testimonial' ) ) {
	class awl_testimonial {

		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}

		protected function _constants() {
			// Plugin Version
			define( 'TML_PLUGIN_VER', '1.1.14' );

			// Plugin Text Domain
			define( 'TML_TXTDM', 'testimonial-maker' );

			// Plugin Name
			define( 'TML_PLUGIN_NAME', __( 'Testimonial Maker', TML_TXTDM ) );

			// Plugin Slug
			define( 'TML_PLUGIN_SLUG', 'testimonial-maker' );

			// Plugin Directory Path
			define( 'TML_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin Directory URL
			define( 'TML_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			define( 'TML_SECURE_KEY', md5( NONCE_KEY ) );

		} // end of constructor function

		protected function _hooks() {
			// Load text domain
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

			// add testimonial menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, 'tmonial_menu' ), 101 );

			// Create testimonial Filter testimonial Custom Post
			add_action( 'init', array( $this, 'Testimonial' ) );

			// Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, 'admin_add_meta_box' ) );

			// loaded during admin init
			add_action( 'admin_init', array( $this, 'admin_add_meta_box' ) );

			add_action( 'save_post', array( &$this, '_tml_save_settings' ) );

			// Shortcode Compatibility in Text Widgets
			add_action( 'widget_text', 'do_shortcode' );
		} // end of hook function

		public function load_textdomain() {
			load_plugin_textdomain( 'testimonial-maker', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public function tmonial_menu() {
			$testimonial_setting = add_submenu_page( 'edit.php?post_type=' . TML_PLUGIN_SLUG, __( 'Testimonial Setting', 'testimonial-maker' ), __( 'Testimonial Setting', 'testimonial-maker' ), 'administrator', 'tmonial-setting-page', array( $this, 'tmonial_setting_page' ) );
			$featured_setting    = add_submenu_page( 'edit.php?post_type=' . TML_PLUGIN_SLUG, __( 'Featured Plugin', 'testimonial-maker' ), __( 'Featured Plugin', 'testimonial-maker' ), 'administrator', 'tmonial-featured-page', array( $this, 'tmonial_featured_page' ) );
		}

		public function Testimonial() {
			$labels = array(
				'name'               => __( 'Testimonial Maker', 'post type general name', 'testimonial-maker' ),
				'singular_name'      => __( 'Testimonial', 'post type singular name', 'testimonial-maker' ),
				'menu_name'          => __( 'Testimonial Maker', 'testimonial-maker' ),
				'name_admin_bar'     => __( 'Testimonial', 'testimonial-maker' ),
				'add_new'            => __( 'Add New Testimonial', 'testimonial-maker' ),
				'add_new_item'       => __( 'Add New Testimonial', 'testimonial-maker' ),
				'new_item'           => __( 'New Testimonial', 'testimonial-maker' ),
				'edit_item'          => __( 'Edit Testimonial', 'testimonial-maker' ),
				'view_item'          => __( 'View Testimonial', 'testimonial-maker' ),
				'all_items'          => __( 'All Testimonial', 'testimonial-maker' ),
				'search_items'       => __( 'Search Testimonial', 'testimonial-maker' ),
				'parent_item_colon'  => __( 'Parent Testimonial:', 'testimonial-maker' ),
				'not_found'          => __( 'No Testimonial found.', 'testimonial-maker' ),
				'not_found_in_trash' => __( 'No Testimonial found in Trash.', 'testimonial-maker' ),
			);

			$args = array(
				'labels'             => __( 'Testimonial', 'testimonial-maker' ),
				'description'        => __( 'Description.', 'your-plugin-textdomain' ),
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				// 'rewrite'            => array( 'slug' => 'testimonial' ),
				'capability_type'    => 'page',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_icon'          => 'dashicons-format-status',
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'thumbnail' ),
			);

			register_post_type( 'testimonial-maker', $args );
		}
		public function admin_add_meta_box() {
			add_meta_box( '', __( 'Add Client detail', 'testimonial-maker' ), array( &$this, 'tml_testimonial_upload' ), 'testimonial-maker', 'normal', 'default' );
		}
		public function tml_testimonial_upload( $post ) {
			// load settings
			$testimonial_post_settings = get_post_meta( $post->ID, 'awl_testimonial' . $post->ID, true );

			?>
			<div>
				<p><?php esc_html_e( 'Client Website URL', 'testimonial-maker' ); ?></p>
				<?php
				if ( isset( $testimonial_post_settings['website_link'] ) ) {
					$website_link = $testimonial_post_settings['website_link'];
				} else {
					$website_link = '';
				}
				?>
					
				<p><input type="text" class="form-control" id="website_link" name="website_link" style="margin-left: 15px; width: 300px;" value="<?php echo esc_url( $website_link ); ?>" /></p>
				<p><?php esc_html_e( 'Client Designation', 'testimonial-maker' ); ?></p>
				<?php
				if ( isset( $testimonial_post_settings['designation'] ) ) {
					$designation = $testimonial_post_settings['designation'];
				} else {
					$designation = '';
				}
				?>
					
				<p><input type="text" class="form-control" id="designation" name="designation" style="margin-left: 15px; width: 300px;" value="<?php echo esc_html( $designation ); ?>" /></p>
			</div>
			<?php
			// syntax: wp_nonce_field( 'name_of_my_action', 'name_of_nonce_field' );
			wp_nonce_field( 'tml_post_save_settings', 'tml_post_save_nonce' );
		}

		public function _tml_save_settings( $post_id ) {
			if ( isset( $_POST['tml_post_save_nonce'] ) ) {
				if ( ! isset( $_POST['tml_post_save_nonce'] ) || ! wp_verify_nonce( $_POST['tml_post_save_nonce'], 'tml_post_save_settings' ) ) {
					print 'Sorry, your nonce did not verify.';
					exit;
				} else {
					$testimonial_post_settings         = array(
						'website_link' => sanitize_text_field( $_POST['website_link'] ),
						'designation'  => sanitize_text_field( $_POST['designation'] ),
					);
					$awl_testimonial_shortcode_setting = 'awl_testimonial' . $post_id;
					update_post_meta( $post_id, $awl_testimonial_shortcode_setting, $testimonial_post_settings );
				}
			}// end save setting
		}//end _tml_save_settings()

		public function tmonial_setting_page() {
			require_once 'include/testimonial-setting.php';
		}
		public function tmonial_featured_page() {
			require_once 'featured-plugins/featured-plugins.php';
		}
	}
	$new_testimonial_object = new awl_testimonial();
	require_once 'shortcode.php';
}
?>
