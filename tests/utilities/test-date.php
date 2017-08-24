<?php
namespace AffWP\Utils\Date;

use AffWP\Tests\UnitTestCase;

/**
 * Tests for AffWP\Utils\Date class.
 *
 * @covers \AffWP\Utils\Date
 *
 * @group utils
 * @group dates
 */
class Tests extends UnitTestCase {

	/**
	 * Utilities object fixture.
	 *
	 * @access protected
	 * @var    \Affiliate_WP_Utilities
	 */
	protected static $utils;

	/**
	 * Date object fixture.
	 *
	 * @access protected
	 * @var    \AffWP\Utils\Date
	 */
	protected static $date;

	/**
	 * Date string fixture.
	 *
	 * @var string
	 */
	protected static $date_string = '4/4/2004';

	/**
	 * Test batch ID.
	 *
	 * @access protected
	 * @var    string
	 */
	protected static $batch_id = 'affwp';

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$utils = new \Affiliate_WP_Utilities;
		self::$date = self::$utils->date;
	}

	/**
	 * @covers \AffWP\Utils\Date::$timezone
	 */
	public function test_timezone_prop_should_contain_the_WP_timezone() {
		$timezone = self::$date->get_core_timezone();

		$this->assertSame( $timezone, self::$date->timezone );
	}

	/**
	 * @covers \AffWP\Utils\Date::$date_format
	 */
	public function test_date_format_should_contain_the_WP_date_format() {
		$date_format = get_option( 'date_format', 'M j, Y' );

		$this->assertSame( $date_format, self::$date->date_format );
	}

	/**
	 * @covers \AffWP\Utils\Date::$time_format
	 */
	public function test_time_format_should_contain_the_WP_time_format() {
		$time_format = get_option( 'time_format', 'g:i a' );

		$this->assertSame( $time_format, self::$date->time_format );
	}

	/**
	 * @covers \AffWP\Utils\Date::$datetime_format
	 */
	public function test_datetime_format_should_contain_the_WP_date_and_time_formats() {
		$date_format = get_option( 'date_format', 'M j, Y' );
		$time_format = get_option( 'time_format', 'g:i a' );;

		$this->assertSame( $date_format . ' ' . $time_format, self::$date->datetime_format );
	}

}
