<?php

/**
 * @package wpdatadestroyer
 */
/*
Plugin Name: WP Data Destoyer
Plugin URI: http://bren.jp/
Description: Delete for post/page/attachment/category/tag in WordPress
Version: 0.0.1
Author: bren
Author URI: http://bren.jp/
License: GPLv2 or later
*/

class WPDataDestroyerPlugin
{
	public $flash_msgs;

	//
	//		Initialization.
	//
	
	public function __construct()
	{
		$this->flash_msgs = array();

		// Add filter for menu.
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
	}

	//
	//		Menu screen.
	//
	
	// Add sub-menu. (filter callback)
	function admin_menu()
	{
		add_submenu_page( 'tools.php', __( 'WP Data Destroyer', 'wpdatadestroyer' ), __( 'WP Data Destroyer', 'wpdatadestroyer' ), 0, 'wpdatadestroyer_admin_submenu', array( &$this, 'admin_submenu' ) );
	}

	// Callback from sub-menu. (filter callback)
	function admin_submenu()
	{
		if ( $_POST['Submit'] == _x('Delete') ) {

			$this->_delete_posts();
			
			$this->_delete_pages();
			
			$this->_delete_attachments();
			
			$this->_delete_nav_menus();

			$this->_delete_categories();

			$this->_delete_tags();

			wp_reset_postdata();
		}

		include 'admin/submenu.php';
	}

	// Delete for 'post'.
	private function _delete_posts()
	{
		$deleted = 0;

		while ( true ) {
			$q = new WP_Query(array(
				'post_type'			=> 'post',
				'post_status'		=> 'any',
				'posts_per_page'	=> 10,
			));
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
			$this->flash_msgs[] = sprintf( __( "Deleted the post(s) of %d.", 'wpdatadestroyer' ), $deleted);
		}
	}
	
	// Delete for 'page'.
	private function _delete_pages()
	{
		$deleted = 0;

		while ( true ) {
			$q = new WP_Query(array(
				'post_type'			=> 'page',
				'post_status'		=> 'any',
				'posts_per_page'	=> 10,
			));
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
			$this->flash_msgs[] = sprintf( __( "Deleted the page(s) of %d.", 'wpdatadestroyer' ), $deleted);
		}
	}

	// Delete for 'attachment'.
	private function _delete_attachments()
	{
		$deleted = 0;

		while ( true ) {
			$q = new WP_Query(array(
				'post_type'			=> 'attachment',
				'post_status'		=> 'any',
				'posts_per_page'	=> 10,
			));
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
			$this->flash_msgs[] = sprintf( __( "Deleted the attachment(s) of %d.", 'wpdatadestroyer' ), $deleted);
		}
	}

	// Delete for 'nav_menu'.
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
				$this->flash_msgs[] = sprintf( __( "Deleted the nav-menu(s) of %d.", 'wpdatadestroyer' ), $deleted);
			}
		}
	}
	
	// Delete for 'category'.
	private function _delete_categories()
	{
		$categories = get_categories(array(
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
		));
		if ( $categories ) {
			$deleted = 0;
			$is_default = false;
			foreach ( $categories as $category ) {
				if ( !wp_delete_category($category->cat_ID) ) {
					if ( get_option( 'default_category' ) == $category->cat_ID ) {
						$is_default = true;
					}
				} else {
					++$deleted;
				}
			}
			if ( $deleted ) {
				$this->flash_msgs[] = sprintf( __( "Deleted the category(s) of %d.", 'wpdatadestroyer' ), $deleted);
			}
			if ( $is_default ) {
				$this->flash_msgs[] = sprintf( __( "Notice: Cannot delete 'Default Category'.", 'wpdatadestroyer' ) );
			}
		}
	}

	// Delete for 'tag'.
	private function _delete_tags()
	{
		$tags = get_tags(array(
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
		));
		if ( $tags ) {
			$deleted = 0;
			foreach ( $tags as $tag ) {
				if ( wp_delete_term( $tag->term_id, 'post_tag' ) ) {
					++$deleted;
				}
			}
			if ( $deleted ) {
				$this->flash_msgs[] = sprintf( __( "Deleted the tag(s) of %d.", 'wpdatadestroyer' ), $deleted);
			}
		}
	}
}

$wpdatadestroyer = new WPDataDestroyerPlugin();

