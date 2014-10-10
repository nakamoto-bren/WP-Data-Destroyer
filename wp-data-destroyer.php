<?php

/**
 * @package wp_data_destroyer
 */
/*
Plugin Name: WP Data Destoyer
Plugin URI: http://bren.jp/
Description: Delete for post/page/attachment/category/tag in WordPress
Version: 0.1
Author: bren
Author URI: http://bren.jp/
License: GPLv2 or later
*/

class WPDataDestroyerPlugin
{
	// domain
	public $text_domain;
	// result messages
	public $messages;
	// error messages
	public $errors;

	/**
	 *	Constructor
	 */
	public function __construct()
	{
		// Set the text domain.
		$this->text_domain = 'wp_data_destroyer';
		// Initialize for result message array.
		$this->messages = array();
		// Initialize for error message array.
		$this->errors = array();
	}

	/**
	 *	execute this plugin
	 */
	public function fire()
	{
		// Add filter for menu.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		// Add Filter for plugin loaded.
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}
	
	public function plugins_loaded()
	{
		load_plugin_textdomain( $this->text_domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	//
	//		Menu screen.
	//
	
	/**
	 *	Add sub-menu. (filter callback)
	 */
	function admin_menu()
	{
		add_submenu_page( 'tools.php',
				__( 'WP Data Destroyer', $this->text_domain ),
				__( 'WP Data Destroyer', $this->text_domain ),
				'import',
				'wp_data_destroyer_admin_submenu', array( &$this, 'admin_submenu' ) );
	}

	/**
	 *	Callback from sub-menu. (filter callback)
	 */
	function admin_submenu()
	{
		if ( strcasecmp( $_SERVER['REQUEST_METHOD'], 'post' ) == 0 ) {
			if ( check_admin_referer( 'wp_data_destroyer_action' ) ) {
				if ( $_POST['delete_confirm'] !== 'confirm' ) {
					$this->errors[] = sprintf( __( "Please check for 'Cofirm'.", $this->text_domain ) );
				} else {
					switch ( $_POST['delete_mode'] ) {
						case 'all':

							$this->_delete_posts();

							$this->_delete_pages();

							$this->_delete_attachments();

							$this->_delete_nav_menus();

							$this->_delete_categories();

							$this->_delete_tags();

							$this->_delete_custom_posts();

							wp_reset_postdata();

							break;

						case 'selected':

							if ( $_POST['select_post'] == 1) {
								$this->_delete_posts();
							}
							if ( $_POST['select_page'] == 1) {
								$this->_delete_pages();
							}
							if ( $_POST['select_attachment'] == 1) {
								$this->_delete_attachments();
							}
							if ( $_POST['select_nav_menus'] == 1) {
								$this->_delete_nav_menus();
							}
							if ( $_POST['select_categories'] == 1) {
								$this->_delete_categories();
							}
							if ( $_POST['select_tags'] == 1) {
								$this->_delete_tags();
							}
							if ( $_POST['select_custom_posts'] == 1) {
								$this->_delete_custom_posts();
							}

							wp_reset_postdata();

							break;

						default:
							$this->errors[] = sprintf( __( "Please select for 'all' or 'selected'.", $this->text_domain ) );
							break;
					}
				}
			}
		} elseif ( strcasecmp( $_SERVER['REQUEST_METHOD'], 'get' ) == 0 ) {
		}

		include 'admin/submenu.php';
	}
	
	//
	//		Delete functions.
	//

	/**
	 *	Delete for 'post'
	 */
	private function _delete_posts()
	{
		$deleted = 0;

		while ( true ) {
			$q = new WP_Query( array(
				'post_type'			=> 'post',
				'post_status'		=> 'any',
				'posts_per_page'	=> 10,
			) );
			if ( !$q->have_posts() ) {
				break;
			} else {
				do {
					$q->the_post();
					if ( wp_delete_post( $q->post->ID, true ) ) {
						++$deleted;
					}
				} while ( $q->have_posts() );
			}
		}

		if ( $deleted ) {
			$this->messages[] = sprintf( __( "Deleted %d post(s).", $this->text_domain ), $deleted );
		}
	}
	
	/**
	 *	Delete for 'page'
	 */
	private function _delete_pages()
	{
		$deleted = 0;

		while ( true ) {
			$q = new WP_Query( array(
				'post_type'			=> 'page',
				'post_status'		=> 'any',
				'posts_per_page'	=> 10,
			) );
			if ( !$q->have_posts() ) {
				break;
			} else {
				do {
					$q->the_post();
					if ( wp_delete_post( $q->post->ID, true ) ) {
						++$deleted;
					}
				} while ( $q->have_posts() );
			}
		}

		if ( $deleted ) {
			$this->messages[] = sprintf( __( "Deleted %d page(s).", $this->text_domain ), $deleted );
		}
	}

	/**
	 *	Delete for 'attachment'
	 */
	private function _delete_attachments()
	{
		$deleted = 0;

		while ( true ) {
			$q = new WP_Query( array(
				'post_type'			=> 'attachment',
				'post_status'		=> 'any',
				'posts_per_page'	=> 10,
			) );
			if ( !$q->have_posts() ) {
				break;
			} else {
				do {
					$q->the_post();
					if ( wp_delete_post( $q->post->ID, true ) ) {
						++$deleted;
					}
				} while ( $q->have_posts() );
			}
		}

		if ( $deleted ) {
			$this->messages[] = sprintf( __( "Deleted %d attachment(s).", $this->text_domain ), $deleted );
		}
	}

	/**
	 *	Delete for 'nav_menu'
	 */
	private function _delete_nav_menus()
	{
		$nav_menus = wp_get_nav_menus();
		if ( $nav_menus ) {
			$deleted = 0;
			foreach ( $nav_menus as $nav_menu ) {
				if ( wp_delete_nav_menu( $nav_menu->term_id ) ) {
					++$deleted;
				}
			}
			if ( $deleted ) {
				$this->messages[] = sprintf( __( "Deleted %d nav-menu(s).", $this->text_domain ), $deleted );
			}
		}
	}
	
	/**
	 *	Delete for 'category'
	 */
	private function _delete_categories()
	{
		$categories = get_categories( array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'category',
			'pad_counts'               => false 
		) );
		if ( $categories ) {
			$deleted = 0;
			$is_default = false;
			foreach ( $categories as $category ) {
				if ( !wp_delete_category( $category->cat_ID ) ) {
					if ( get_option( 'default_category' ) == $category->cat_ID ) {
						$is_default = true;
					}
				} else {
					++$deleted;
				}
			}
			if ( $deleted ) {
				$this->messages[] = sprintf( __( "Deleted %d category(s).", $this->text_domain ), $deleted );
			}
			if ( $is_default ) {
				$this->messages[] = sprintf( __( "Notice: Cannot delete 'Default Category'.", $this->text_domain ) );
			}
		}
	}

	/**
	 *	Delete for 'tag'
	 */
	private function _delete_tags()
	{
		$tags = get_tags( array(
			'orderby'				=> 'name',
			'order'					=> 'ASC',
			'hide_empty'			=> false,
			'exclude'				=> '',
			'include'				=> '',
			'number'				=> '',
			'offset'				=> '',
			'fields'				=> 'all',
			'slug'					=> '',
			'hierarchical'			=> 1,
			'search'				=> '',
			'name__like'			=> '',
			'pad_counts'			=> false,
			'get'					=> '',
			'child_of'				=> '',
			'parent'				=> '',
		) );
		if ( $tags ) {
			$deleted = 0;
			foreach ( $tags as $tag ) {
				if ( wp_delete_term( $tag->term_id, 'post_tag' ) ) {
					++$deleted;
				}
			}
			if ( $deleted ) {
				$this->messages[] = sprintf( __( "Deleted %d tag(s).", $this->text_domain ), $deleted );
			}
		}
	}

	/**
	 *	Delete for custom posts
	 */
	private function _delete_custom_posts()
	{
		$deleted = 0;
		
		$post_types = get_post_types( array(
			'public'		=> true,
			'_builtin'		=> false,
		) );

		foreach ( $post_types as $post_type ) {
			while ( true ) {
				$q = new WP_Query( array(
					'post_type'			=> $post_type,
					'post_status'		=> 'any',
					'posts_per_page'	=> 10,
				) );
				if ( !$q->have_posts() ) {
					break;
				} else {
					do {
						$q->the_post();
						if ( wp_delete_post( $q->post->ID, true ) ) {
							++$deleted;
						}
					} while ( $q->have_posts() );
				}
			}
		}

		if ( $deleted ) {
			$this->messages[] = sprintf( __( "Deleted %d custom post(s).", $this->text_domain ), $deleted );
		}
	}
	
}

// Class instance
$wp_data_destroyer = new WPDataDestroyerPlugin();
// Run
$wp_data_destroyer->fire();

if ( false ) :

/**
 * Implements example command.
 */
class Example_Command extends WP_CLI_Command
{
	/**
	 * Prints a greeting.
	 * 
	 * ## OPTIONS
	 * 
	 * <name>
	 * : The name of the person to greet.
	 * 
	 * ## EXAMPLES
	 * 
	 *     wp example hello Newman
	 *
	 * @synopsis <name>
	 */
	function hello( $args, $assoc_args ) {
		list( $name ) = $args;

		// Print a success message
		WP_CLI::success( "Hello, $name!" );
	}
}

WP_CLI::add_command( 'example', 'Example_Command' );

endif;
