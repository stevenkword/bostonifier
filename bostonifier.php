<?php
/**
 * Bostonifier plugin
 *
 * @package Bostonifier
 */

/**
 * Plugin Name:     Bostonifier
 * Plugin URI:      http://wordpress.org/plugins/hello-beantown/
 * Description:     This is an awesome plugin that translates English into Bostonian.
 * Author:          Steven K Word
 * Author URI:      http://stevenword.com
 * Text Domain:     bostonifier
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Bostonifier
 */

require_once( __DIR__ . '/class-bostonifier-translator.php' );
require_once( __DIR__ . '/class-bostonifier-rest-widget.php' );
