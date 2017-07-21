<?php
/**
 * Class BostonifierTranslatorTest
 *
 * @package Bostonifer
 */

class BostonifierTranslatorTest extends WP_UnitTestCase {

	public function setUp() {
		$this->translator = Bostonifier_Translator::instance();
	}

	function test_er_ending() {
		$input = 'corner';
		$expected = 'cornah';
		$actual = $this->translator->translate_er_word_endings( $input );
		$this->assertEquals( $expected, $actual );
	}

	function test_awesome_to_wicked_cool() {
		$input = 'awesome';
		$expected = 'wicked cool';
		$actual = $this->translator->translate_awesome_to_wicked_cool( $input );
		$this->assertEquals( $expected, $actual );
	}

	function test_bostonify() {
		$input = 'super awesome';
		$expected = 'supah wicked cool';
		$actual = $this->translator->bostonify( $input );
		$this->assertEquals( $expected, $actual );
	}
}
