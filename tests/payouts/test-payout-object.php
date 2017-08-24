<?php
namespace AffWP\Payout\Object;

use AffWP\Tests\UnitTestCase;
use AffWP\Affiliate\Payout;

/**
 * Tests for AffWP\Affiliate\Payout
 *
 * @covers AffWP\Affiliate\Payout
 * @covers AffWP\Base_Object
 *
 * @group payouts
 * @group objects
 */
class Tests extends UnitTestCase {

	/**
	 * Payout fixture.
	 *
	 * @access protected
	 * @var int
	 * @static
	 */
	protected static $payout_id = 0;

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

		self::$payout_id = parent::affwp()->payout->create( array(
			'date' => self::$date
		) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_invalid_payout_id_should_return_false() {
		$this->assertFalse( Payout::get_instance( 0 ) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_payout_id_should_return_Payout_object() {
		$payout_id = $this->factory->payout->create();

		$payout = Payout::get_instance( $payout_id );

		$this->assertInstanceOf( 'AffWP\Affiliate\Payout', $payout );

		// Clean up.
		affwp_delete_payout( $payout_id );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_default_format_empty_should_return_stored_date_registered() {
		$payout = affwp_get_payout( self::$payout_id );

		$this->assertSame( self::$date, $payout->date() );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_true_should_return_datetime_formatted_date_registered() {
		$payout = affwp_get_payout( self::$payout_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $payout->date( true ) );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_date_should_return_date_formatted_date_registered() {
		$payout = affwp_get_payout( self::$payout_id );

		$expected = date( affiliate_wp()->utils->date->date_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $payout->date( 'date' ) );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_time_should_return_time_formatted_date_registered() {
		$payout = affwp_get_payout( self::$payout_id );

		$expected = date( affiliate_wp()->utils->date->time_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $payout->date( 'time' ) );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_datetime_should_return_datetime_formatted_date_registered() {
		$payout = affwp_get_payout( self::$payout_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $payout->date( 'datetime' ) );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_utc_should_return_datetime_formatted_date_registered() {
		$payout = affwp_get_payout( self::$payout_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $payout->date( 'utc' ) );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_object_should_return_Carbon_object() {
		$payout = affwp_get_payout( self::$payout_id );

		$this->assertInstanceOf( '\Carbon\Carbon', $payout->date( 'object' ) );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_timestamp_should_return_timestamp() {
		$payout = affwp_get_payout( self::$payout_id );

		$this->assertSame( strtotime( self::$date ), $payout->date( 'timestamp' ) );
	}

	/**
	 * @covers \AffWP\Affiliate\Payout::date()
	 * @group dates
	 */
	public function test_date_format_real_date_format_should_return_formatted_date_registered() {
		$format = 'l jS \of F Y h:i:s A';

		$payout = affwp_get_payout( self::$payout_id );
		$expected = date( $format, strtotime( self::$date ) );

		$this->assertSame( $expected, $payout->date( $format ) );
	}

}
