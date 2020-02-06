<?php
/**
 * Abstraction for settings fields
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */


namespace Plugin_Name_Replace_Me\Abstracts;

use Plugin_Name_Replace_Me\Traits\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Settings_Field
 *
 * @since 1.0.0
 * @package Plugin_Name_Replace_Me\Abstracts
 */
abstract class Settings_Field {
	use Templates;

	/**
	 * Field parameters.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $field_params;

	/**
	 * Field value.
	 *
	 * @since 1.0.0
	 * @var mixed
	 */
	protected $value;

	/**
	 * Gets the field type
	 *
	 * @since 1.0.0
	 *
	 * @return string The field type
	 */
	abstract function get_field_type();

	/**
	 * Sanitizes a value using this field's sanitization method.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to sanitize.
	 * @return mixed The sanitized value.
	 */
	abstract function sanitize( $value );

	/**
	 * Settings_Field constructor.
	 *
	 * @param mixed $value The current value of the field.
	 * @param array $params The field parameters
	 */
	public function __construct( $value, array $params ) {
		$this->value        = $value;
		$this->field_params = $params;
	}

	/**
	 * Retrieves the specified field parameter.
	 *
	 * @since 1.0.0
	 *
	 * @param $param The param to retrieve.
	 * @return mixed|\WP_Error The param value, or a \WP_Error object if the param could not be retrieved.
	 */
	public function get_field_param( $param ) {
		if ( isset( $this->field_params[ $param ] ) ) {
			return $this->field_params[ $param ];
		}

		return plugin_name_replace_me()->logger()->log(
			'plugin_name_replace_me_error',
			'invalid_field_type',
			__( 'The requested param is not a valid param for this field.', 'plugin-name-replace-me' ),
			[
				'param'            => $param,
				'available_params' => array_keys( $this->field_params ),
				'field_type'       => $this->get_field_type(),
			]
		);
	}

	/**
	 * Renders the field template.
	 * The templates can be found in templates/admin/settings-fields/FIELD_TYPE
	 *
	 * @since 1.0.0
	 *
	 * @param bool $as_table True if field should be placed using WordPress settings table markup.
	 * @return string The template HTML output, or the error message for the template.
	 */
	public function place( $as_table = false ) {
		$template_name = true === $as_table ? 'settings-field' : 'field';
		$template      = $this->get_template( $template_name, $this->field_params );

		if ( is_wp_error( $template ) ) {
			$template = $template->get_error_message();
		}

		return $template;
	}

	/**
	 * Places a series of attributes in an HTML element.
	 *
	 * @since 1.0.0
	 *
	 * @param array $names Array of tag names from which to fetch the attribute value.
	 * @return string The HTML output
	 */
	public function attributes( $names ) {
		$result = [];

		foreach ( $names as $name ) {
			$attribute = $this->attribute( $name );
			if ( '' !== $attribute ) {
				$result[] = $attribute;
			}
		}

		return implode( ' ', $result );
	}

	/**
	 * Gets the field ID value. Falls back to the name value if id is not set.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed The field ID, or the field name.
	 */
	public function get_id() {
		$id = $this->get_param( 'id', '' );

		if ( ! $id ) {
			$id = $this->get_param( 'name', '' );
		}

		return $id;
	}

	/**
	 * Places an HTML attribute, if it is set.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The tag name from which to fetch the attribute value.
	 * @return string The HTML output.
	 */
	public function attribute( $name ) {
		$attribute_value = $this->get_param( $name );
		$result          = '';
		if ( false !== $attribute_value ) {
			$result = "$name=\"$attribute_value\"";
		}

		return $result;
	}

	/**
	 * Gets the field value
	 *
	 * @since 1.0.0
	 *
	 * @return mixed The field value.
	 */
	public function get_field_value() {
		return $this->value;
	}

	/**
	 * Updates the field value. Sanitizes the field before setting.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to set
	 * @return mixed The sanitized value
	 */
	public function update_value( $value ) {
		$this->value = $this->sanitize( $value );

		return $this->value;
	}

	/**
	 * Fetches the valid templates and their visibility.
	 *
	 * override_visibility can be either "theme", "plugin", "public" or "private".
	 *  theme   - sets the template to only be override-able by a parent, or child theme.
	 *  plugin  - sets the template to only be override-able by another plugin.
	 *  public  - sets the template to be override-able anywhere.
	 *  private - sets the template to be non override-able.
	 *
	 * @since 1.0.0
	 *
	 * @return array of template properties keyed by the template name
	 */
	protected function get_templates() {
		return [
			'settings-field' => [
				'override_visibility' => 'private',
			],
			'input'          => [
				'override_visibility' => 'private',
			],
			'field'          => [
				'override_visibility' => 'private',
			],
		];
	}

	/**
	 * Locates the template based on the settings field's different hierarchy.
	 *
	 * @since 1.0.0
	 *
	 * @param $template_name string The template name to locate.
	 * @return string The path to the located template.
	 */
	protected function locate_template( $template_name ) {

		// Bail early if this is the input template, or if the template path has a file to override.
		if ( 'input' === $template_name || file_exists( $this->get_template_path( $template_name ) ) ) {
			return $this->get_template_path( $template_name );
		} else {
			return $this->get_admin_template_path( $template_name );
		}
	}

	/**
	 * Gets the admin template path.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template_name The template name to locate.
	 * @return string The path to the admin template.
	 */
	private function get_admin_template_path( $template_name ) {
		return trailingslashit( PLUGIN_NAME_REPLACE_ME_TEMPLATE_DIR ) . 'admin/settings-fields/' . $template_name . '.php';
	}

	/**
	 * Checks to see if the template file exists.
	 *
	 * @since 1.0.0
	 *
	 * @param $template_name string The template name to check.
	 * @return bool True if the template file exists, false otherwise.
	 */
	protected function template_file_exists( $template_name ) {
		$template_file_exists = file_exists( $this->get_template_path( $template_name ) );
		$settings_file_exists = file_exists( $this->get_admin_template_path( $template_name ) );

		return $template_file_exists || $settings_file_exists;
	}

	/**
	 * Fetches the template group name. This determines the sub-directory for the templates.
	 *
	 * @since 1.0.0
	 *
	 * @return string The template group name
	 */
	protected function get_template_group() {
		return 'admin/settings-fields/' . $this->get_field_type();
	}

}