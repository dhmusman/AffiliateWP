<?php
namespace AffWP\Utils\Date\Functions;

use AffWP\Tests\UnitTestCase;

/**
 * Tests for includes/date-functions.php
 *
 * @group functions
 * @group dates
 */
class Tests extends UnitTestCase {

	/**
	 * Date fixture.
	 *
	 * @var \Carbon\Carbon
	 */
	protected static $date;

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$date = affiliate_wp()->utils->date();
	}

	/**
	 * Logic to run after each test method is executed.
	 */
	public function tearDown() {
		remove_all_filters( 'affwp_get_filter_date_range' );
		remove_all_filters( 'affwp_get_filter_date_values' );

		parent::tearDown();
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_should_return_array_with_string_dates_for_this_month_by_default() {
		$expected = array(
			'start' => self::$date->copy()->startOfMonth()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfMonth()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_values_objects_should_return_array_with_object_dates_for_this_month_by_default() {
		$expected = array(
			'start' => self::$date->copy()->startOfMonth()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfMonth()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_last_month_date_range_values_strings_should_return_string_dates_for_last_month() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'last_month'; } );

		$expected = array(
			'start' => self::$date->copy()->subMonth( 1 )->startOfMonth()->toDateTimeString(),
			'end'   => self::$date->copy()->subMonth( 1 )->endOfMonth()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );

	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_last_month_date_range_values_objects_should_return_object_dates_for_last_month() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'last_month'; } );

		$expected = array(
			'start' => self::$date->copy()->subMonth( 1 )->startOfMonth()->toDateTimeString(),
			'end'   => self::$date->copy()->subMonth( 1 )->endOfMonth()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_today_date_range_should_return_string_dates_for_today() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'today'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfDay()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfDay()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_today_date_range_values_objects_should_return_object_dates_for_today() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'today'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfDay()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfDay()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_yesterday_date_range_should_return_string_dates_for_yesterday() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'yesterday'; } );

		$expected = array(
			'start' => self::$date->copy()->subDay( 1 )->startOfDay()->toDateTimeString(),
			'end'   => self::$date->copy()->subDay( 1 )->endOfDay()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_yesterday_date_range_values_objects_should_return_object_dates_for_yesterday() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'yesterday'; } );

		$expected = array(
			'start' => self::$date->copy()->subDay( 1 )->startOfDay()->toDateTimeString(),
			'end'   => self::$date->copy()->subDay( 1 )->endOfDay()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_this_week_date_range_should_return_string_dates_for_last_week() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'this_week'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfWeek()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfWeek()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_this_week_date_range_values_objects_should_return_object_dates_for_last_week() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'this_week'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfWeek()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfWeek()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_this_quarter_date_range_should_return_string_dates_for_this_quarter() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'this_quarter'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfQuarter()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfQuarter()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_this_quarter_date_range_values_objects_should_return_object_dates_for_this_quarter() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'this_quarter'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfQuarter()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfQuarter()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_last_quarter_date_range_should_return_string_dates_for_last_quarter() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'last_quarter'; } );

		$expected = array(
			'start' => self::$date->copy()->subQuarter( 1 )->startOfQuarter()->toDateTimeString(),
			'end'   => self::$date->copy()->subQuarter( 1 )->endOfQuarter()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_last_quarter_date_range_values_objects_should_return_object_dates_for_last_quarter() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'last_quarter'; } );

		$expected = array(
			'start' => self::$date->copy()->subQuarter( 1 )->startOfQuarter()->toDateTimeString(),
			'end'   => self::$date->copy()->subQuarter( 1 )->endOfQuarter()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_this_year_date_range_should_return_string_dates_for_this_year() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'this_year'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfYear()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfYear()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_this_year_date_range_values_objects_should_return_object_dates_for_this_year() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'this_year'; } );

		$expected = array(
			'start' => self::$date->copy()->startOfYear()->toDateTimeString(),
			'end'   => self::$date->copy()->endOfYear()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_last_year_date_range_should_return_string_dates_for_last_year() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'last_year'; } );

		$expected = array(
			'start' => self::$date->copy()->subYear( 1 )->startOfYear()->toDateTimeString(),
			'end'   => self::$date->copy()->subYear( 1 )->endOfYear()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_last_year_date_range_values_objects_should_return_object_dates_for_last_year() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'last_year'; } );

		$expected = array(
			'start' => self::$date->copy()->subYear( 1 )->startOfYear()->toDateTimeString(),
			'end'   => self::$date->copy()->subYear( 1 )->endOfYear()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_other_date_range_should_return_string_dates_for_filter_date_values() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'other'; } );

		$date = self::$date;

		add_filter( 'affwp_get_filter_date_values', function() use ( $date ) {
			return array(
				'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
				'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
			);
		} );

		$expected = array(
			'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
			'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_other_date_range_values_objects_should_return_object_dates_for_filter_date_values() {
		add_filter( 'affwp_get_filter_date_range', function() { return 'other'; } );

		$date = self::$date;

		add_filter( 'affwp_get_filter_date_values', function() use ( $date ) {
			return array(
				'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
				'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
			);
		} );

		$expected = array(
			'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
			'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_empty_date_range_should_return_string_dates_for_filter_date_values() {
		add_filter( 'affwp_get_filter_date_range', '__return_empty_string' );

		$date = self::$date;

		add_filter( 'affwp_get_filter_date_values', function() use ( $date ) {
			return array(
				'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
				'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
			);
		} );

		$expected = array(
			'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
			'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
		);

		$this->assertEqualSets( $expected, affwp_get_filter_dates() );
	}

	/**
	 * @covers ::affwp_get_filter_dates()
	 */
	public function test_get_filter_dates_with_empty_date_range_values_objects_should_return_object_dates_for_filter_date_values() {
		add_filter( 'affwp_get_filter_date_range', '__return_empty_string' );

		$date = self::$date;

		add_filter( 'affwp_get_filter_date_values', function() use ( $date ) {
			return array(
				'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
				'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
			);
		} );

		$expected = array(
			'start' => $date->copy()->addDay( 1 )->startOfDay()->toDateTimeString(),
			'end'   => $date->copy()->addDay( 1 )->endOfDay()->toDateTimeString(),
		);

		$result = affwp_get_filter_dates( 'objects' );

		$this->assertEqualSets( $expected, $this->convert_to_strings( $result ) );
	}

	/**
	 * Helper to convert object dates to strings for comparison.
	 *
	 * @param \Carbon\Carbon[] $dates Array of date objects.
	 * @return array Array of date strings.
	 */
	protected function convert_to_strings( $dates ) {
		// Convert to strings for comparison.
		$dates['start'] = $dates['start']->toDateTimeString();
		$dates['end']   = $dates['end']->toDateTimeString();

		return $dates;
	}

}
