<?php
/**
 * Registers Scripts to WordPress
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */


namespace Plugin_Name_Replace_Me\Core\Abstracts;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Script
 * Class Scripts
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Style extends Feature_Extension {

	/**
	 * The handle for this script.
	 *
	 * @since 1.0.0
	 * @var string the script handle.
	 */
	protected $handle;

	/**
	 * The version.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $ver = false;

	/**
	 * The source url for this script.
	 *
	 * @since 1.0.0
	 * @var bool|string
	 */
	protected $src = false;

	/**
	 * The dependencies for this script.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $deps = [];

	/**
	 * The media for which this stylesheet has been defined.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $media = 'all';

	public function __construct() {
		$this->ver = false === $this->ver ? PLUGIN_NAME_REPLACE_ME_PLUGIN_VERSION : $this->ver;
		$this->src = false === $this->src ? PLUGIN_NAME_REPLACE_ME_CSS_URL . $this->handle . '.min.css' : $this->src;
	}

	/**
	 * @inheritDoc
	 */
	public function do_actions() {
		add_action( 'init', [ $this, 'register' ] );
	}

	public function register() {
		wp_register_style( $this->handle, $this->src, $this->deps, $this->ver, $this->media );
	}

	public function enqueue() {
		wp_enqueue_style( $this->handle );
	}

}