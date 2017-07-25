<?php
/**
 * Bostonifier_Translator class
 *
 * Translates English to Boston-speak.
 *
 * @package Bostonifier
 */

/**
 * Class Bostonifier
 *
 * Translates English to Boston-speak.
 */
class Bostonifier_Translator {

	/**
	 * Define singleton
	 *
	 * @var instance
	 */
	private static $instance = false;

	/**
	 * Register singleton
	 *
	 * @return Bostonifier self
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new Bostonifier_Translator();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	function __construct() {
		add_filter( 'gettext', array( $this, 'filter_gettext' ), 10, 2 );
		add_filter( 'gettext_with_context', array( $this, 'filter_gettext' ), 10, 2 );
	}

	/**
	 * Translates words ending with "er" to "ah".
	 *
	 * E.g.) "Corner" to "Cornah"
	 *
	 * @param string $input Input string to be translated.
	 * @return string The translated input
	 */
	function translate_er_word_endings( $input ) {
		$output = str_replace( 'er', 'ah', $input );
		return $output;
	}

	/**
	 * Translates words ending with "ar" to "ah".
	 *
	 * E.g.) "Car" to "Cah"
	 *
	 * @param string $input Input string to be translated.
	 * @return string The translated input
	 */
	function translate_ar_word_endings( $input ) {
		$output = str_replace( 'ar', 'ah', $input );
		return $output;
	}

	/**
	 * Translates all the things to be "wicked cool"
	 *
	 * E.g.) "awesome" to "wicked cool"
	 *
	 * @param string $input Input string to be translated.
	 * @return string The translated input
	 */
	function translate_awesome_to_wicked_cool( $input ) {
		$output = str_replace( 'awesome', 'wicked cool', $input );
		return $output;
	}

	/**
	 * Revert unintentionial changes that just don't work.
	 *
	 * Explicitly revert certain transformations.
	 *
	 * @param string $input Input string to be translated.
	 * @return string The translated input
	 */
	function translate_revert_blacklist( $input ) {
		$blacklist = array(
			'an wicked cool' => 'a wicked cool',
			'Vahsion' => 'Version',
			'ahy' => 'ery',
			'ahah' => 'erah',
			'sahvah' => 'servah',  
		);
		$output = $input;
		foreach ( $blacklist as $oops => $correction ) {
			$output = str_replace( $oops, $correction, $output );
		}
		return $output;
	}

	/**
	 * Main translation handler
	 *
	 * Runs all of the translation functions in aggregate.
	 *
	 * @param string $original Input string to be translated.
	 * @return string The translated input
	 */
	function bostonify( $original ) {
		try {
			$translated = $original;
			$translated = $this->translate_er_word_endings( $translated );
			$translated = $this->translate_ar_word_endings( $translated );
			$translated = $this->translate_awesome_to_wicked_cool( $translated );
			$translated = $this->translate_revert_blacklist( $translated );
		} catch ( \Exception $e ) {
			wp_die( esc_html( $e->getMessage() ) );
		}
		return $translated;
	}

	/**
	 * Filters WordPress strings through the Bostonifier
	 *
	 * @param string $translated The translated input.
	 * @param string $original Input string to be translated.
	 * @return string The translated input
	 */
	function filter_gettext( $translated, $original ) {
		return $this->bostonify( $original );
	}
}
Bostonifier_Translator::instance();
