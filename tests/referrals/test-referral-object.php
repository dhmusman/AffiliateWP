<?php
namespace AffWP\Referral\Object;

use AffWP\Tests\UnitTestCase;
use AffWP\Referral;

/**
 * Tests for AffWP\Referral
 *
 * @covers AffWP\Referral
 * @covers AffWP\Base_Object
 *
 * @group referrals
 * @group objects
 */
class Tests extends UnitTestCase {

	/**
	 * Referral fixture.
	 *
	 * @access protected
	 * @var int
	 * @static
	 */
	protected static $referral_id = 0;

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

		self::$referral_id = parent::affwp()->referral->create( array(
			'date' => self::$date
		) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_invalid_referral_id_should_return_false() {
		$this->assertFalse( Referral::get_instance( 0 ) );
	}

	/**
	 * @covers AffWP\Base_Object::get_instance()
	 */
	public function test_get_instance_with_referral_id_should_return_Referral_object() {
		$user_id = $this->factory->user->create();

		$affiliate_id = affiliate_wp()->affiliates->add( array(
			'user_id' => $user_id
		) );

		$referral_id = affiliate_wp()->referrals->add( array(
			'affiliate_id' => $affiliate_id
		) );

		$referral = Referral::get_instance( $referral_id );

		$this->assertInstanceOf( 'AffWP\Referral', $referral );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_default_format_empty_should_return_stored_date_registered() {
		$referral = affwp_get_referral( self::$referral_id );

		$this->assertSame( self::$date, $referral->date() );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_true_should_return_datetime_formatted_date_registered() {
		$referral = affwp_get_referral( self::$referral_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $referral->date( true ) );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_date_should_return_date_formatted_date_registered() {
		$referral = affwp_get_referral( self::$referral_id );

		$expected = date( affiliate_wp()->utils->date->date_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $referral->date( 'date' ) );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_time_should_return_time_formatted_date_registered() {
		$referral = affwp_get_referral( self::$referral_id );

		$expected = date( affiliate_wp()->utils->date->time_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $referral->date( 'time' ) );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_datetime_should_return_datetime_formatted_date_registered() {
		$referral = affwp_get_referral( self::$referral_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $referral->date( 'datetime' ) );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_utc_should_return_datetime_formatted_date_registered() {
		$referral = affwp_get_referral( self::$referral_id );

		$expected = date( affiliate_wp()->utils->date->datetime_format, strtotime( self::$date ) );

		$this->assertSame( $expected, $referral->date( 'utc' ) );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_object_should_return_Carbon_object() {
		$referral = affwp_get_referral( self::$referral_id );

		$this->assertInstanceOf( '\Carbon\Carbon', $referral->date( 'object' ) );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_timestamp_should_return_timestamp() {
		$referral = affwp_get_referral( self::$referral_id );

		$this->assertSame( strtotime( self::$date ), $referral->date( 'timestamp' ) );
	}

	/**
	 * @covers \AffWP\Referral::date()
	 * @group dates
	 */
	public function test_date_format_real_date_format_should_return_formatted_date_registered() {
		$format = 'l jS \of F Y h:i:s A';

		$referral = affwp_get_referral( self::$referral_id );
		$expected = date( $format, strtotime( self::$date ) );

		$this->assertSame( $expected, $referral->date( $format ) );
	}

}
