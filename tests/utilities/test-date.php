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
	 * Utilities object.
	 *
	 * @access protected
	 * @var    \Affiliate_WP_Utilities
	 */
	protected static $utils;

	/**
	 * Date object.
	 *
	 * @access protected
	 * @var    \AffWP\Utils\Date
	 */
	protected static $date;

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


}
