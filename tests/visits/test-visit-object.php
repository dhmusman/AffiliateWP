<?php
namespace AffWP\Visit\Object;

use AffWP\Tests\UnitTestCase;
use AffWP\Visit;

/**
 * Tests for AffWP\Visit
 *
 * @covers AffWP\Visit
 * @covers AffWP\Base_Object
 *
 * @group visits
 * @group objects
 */
class Tests extends UnitTestCase {

	/**
	 * Visit fixture.
	 *
	 * @access protected
	 * @var int
	 * @static
	 */
	protected static $visit_id = 0;

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

		self::$visit_id = parent::affwp()->visit->create( array(
			'date' => self::$date
		) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_invalid_visit_id_should_return_false() {
		$this->assertFalse( Visit::get_instance( 0 ) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_visit_id_should_return_Visit_object() {
		$visit_id = $this->factory->visit->create( array(
			'referral_id'  => $this->factory->referral->create()
		) );

		$visit = Visit::get_instance( $visit_id );

		$this->assertInstanceOf( 'AffWP\Visit', $visit );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_default_format_empty_should_return_stored_date_registered() {
		$visit = affwp_get_visit( self::$visit_id );

		$this->assertSame( self::$date, $visit->date() );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_true_should_return_datetime_formatted_date_registered() {
		$visit = affwp_get_visit( self::$visit_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $visit->date( true ) );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_date_should_return_date_formatted_date_registered() {
		$visit = affwp_get_visit( self::$visit_id );

		$expected = date( affiliate_wp()->utils->date->date_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $visit->date( 'date' ) );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_time_should_return_time_formatted_date_registered() {
		$visit = affwp_get_visit( self::$visit_id );

		$expected = date( affiliate_wp()->utils->date->time_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $visit->date( 'time' ) );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_datetime_should_return_datetime_formatted_date_registered() {
		$visit = affwp_get_visit( self::$visit_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $visit->date( 'datetime' ) );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_utc_should_return_datetime_formatted_date_registered() {
		$visit = affwp_get_visit( self::$visit_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $visit->date( 'utc' ) );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_object_should_return_Carbon_object() {
		$visit = affwp_get_visit( self::$visit_id );

		$this->assertInstanceOf( '\Carbon\Carbon', $visit->date( 'object' ) );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_timestamp_should_return_timestamp() {
		$visit = affwp_get_visit( self::$visit_id );

		$this->assertSame( strtotime( self::$date ), $visit->date( 'timestamp' ) );
	}

	/**
	 * @covers \AffWP\Visit::date()
	 * @group dates
	 */
	public function test_date_format_real_date_format_should_return_formatted_date_registered() {
		$format = 'l jS \of F Y h:i:s A';

		$visit = affwp_get_visit( self::$visit_id );
		$expected = date( $format, strtotime( self::$date ) );

		$this->assertSame( $expected, $visit->date( $format ) );
	}

}
