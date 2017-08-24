<?php
namespace AffWP\Creative\Object;

use AffWP\Tests\UnitTestCase;
use AffWP\Creative;

/**
 * Tests for AffWP\Creative
 *
 * @covers AffWP\Creative
 * @covers AffWP\Base_Object
 *
 * @group creatives
 * @group objects
 */
class Tests extends UnitTestCase {

	/**
	 * Creative fixture.
	 *
	 * @access protected
	 * @var int
	 * @static
	 */
	protected static $creative_id = 0;

	/**
	 * Date fixture.
	 *
	 * @access protected
	 * @var    string
	 * @static
	 */
	protected static $date;

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$date = current_time( 'mysql' );

		self::$creative_id = parent::affwp()->creative->create( array(
			'date' => self::$date
		) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_invalid_creative_id_should_return_false() {
		$this->assertFalse( Creative::get_instance( 0 ) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_creative_id_should_return_Creative_object() {
		$creative_id = affiliate_wp()->creatives->add();

		$this->assertInstanceOf( 'AffWP\Creative', Creative::get_instance( $creative_id ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_default_format_empty_should_return_stored_date_registered() {
		$creative = affwp_get_creative( self::$creative_id );

		$this->assertSame( self::$date, $creative->date() );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_true_should_return_datetime_formatted_date_registered() {
		$creative = affwp_get_creative( self::$creative_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $creative->date( true ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_date_should_return_date_formatted_date_registered() {
		$creative = affwp_get_creative( self::$creative_id );

		$expected = date( affiliate_wp()->utils->date->date_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $creative->date( 'date' ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_time_should_return_time_formatted_date_registered() {
		$creative = affwp_get_creative( self::$creative_id );

		$expected = date( affiliate_wp()->utils->date->time_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $creative->date( 'time' ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_datetime_should_return_datetime_formatted_date_registered() {
		$creative = affwp_get_creative( self::$creative_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $creative->date( 'datetime' ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_utc_should_return_datetime_formatted_date_registered() {
		$creative = affwp_get_creative( self::$creative_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $creative->date( 'utc' ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_object_should_return_Carbon_object() {
		$creative = affwp_get_creative( self::$creative_id );

		$this->assertInstanceOf( '\Carbon\Carbon', $creative->date( 'object' ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_timestamp_should_return_timestamp() {
		$creative = affwp_get_creative( self::$creative_id );

		$this->assertSame( strtotime( self::$date ), $creative->date( 'timestamp' ) );
	}

	/**
	 * @covers \AffWP\Creative::date()
	 * @group dates
	 */
	public function test_date_format_real_date_format_should_return_formatted_date_registered() {
		$format = 'l jS \of F Y h:i:s A';

		$creative = affwp_get_creative( self::$creative_id );
		$expected = date( $format, strtotime( self::$date ) );

		$this->assertSame( $expected, $creative->date( $format ) );
	}

}
