<?php
/**
 * Registers a shortcode
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */


namespace Plugin_Name_Replace_Me\Abstracts;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Shortcode
 *
 * @since   1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Shortcode {

	/**
	 * The shortcode attributes, parsed by shortcode atts.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $atts = [];

	/**
	 * The default shortcode att values.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $defaults;

	/**
	 * The actions this shortcode should take when called. use $this->atts to access the parsed shortcode atts.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed The shortcode action result.
	 */
	public abstract function shortcode_actions();

	/**
	 * Shortcode constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param string $shortcode The name of the shortcode.
	 * @param array  $defaults  The default attribute values.
	 */
	public function __construct( $shortcode, array $defaults ) {
		$this->defaults = $defaults;
		add_shortcode( $shortcode, [ $this, 'shortcode' ] );
	}

	/**
	 * The actual shortcode callback. Sets shortcode atts to the class so other methods can access the arguments.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts The shortcode attributes
	 * @return mixed The shortcode action result.
	 */
	public function shortcode( $atts ) {
		$this->atts = shortcode_atts( $this->defaults, $atts );

		return $this->shortcode_actions();
	}

}