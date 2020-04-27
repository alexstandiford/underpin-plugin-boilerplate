<?php
/**
 *
 *
 * @since
 * @package
 */


namespace Plugin_Name_Replace_Me\Core\Utilities;


use Plugin_Name_Replace_Me\Core\Abstracts\Script;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Debug_Bar
 *
 *
 * @since
 * @package
 */
class Debug_Bar_Script extends Script {

	protected $handle = 'plugin_name_replace_me_debug';

	protected $deps = [ 'jquery' ];

	protected $contexts = [ 'site', 'admin', 'author' ];

	protected $in_footer = true;

	public function __construct() {
		$this->src = PLUGIN_NAME_REPLACE_ME_JS_URL . 'debug.min.js';
		parent::__construct();
	}

}