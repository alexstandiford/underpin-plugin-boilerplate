<?php
/**
 * Taxonomy Abstraction.
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Taxonomy
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Core\Abstracts
 */
abstract class Taxonomy extends Feature_Extension {

	/**
	 * The taxonomy name.
	 *
	 * @since 1.0.0
	 *
	 * @var string The post type "$type" argument.
	 */
	protected $name = '';

	/**
	 * The post type args.
	 *
	 * @since 1.0.0
	 *
	 * @var array The list of post type args. See https://developer.wordpress.org/reference/functions/register_post_type/
	 */
	protected $args = [];

	/**
	 * The post type, or types to use.
	 *
	 * @var string|array A single post type, or an array of registered post types.z
	 */
	protected $post_type = 'post';

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		add_action( 'init', [ $this, 'register' ], 11 );
	}

	/**
	 * Registers the post type.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$registered = register_taxonomy( $this->name, $this->post_type, $this->args );

		if ( is_wp_error( $registered ) ) {
			plugin_name_replace_me()->logger()->log_wp_error( 'error', $registered );
		}
	}

}