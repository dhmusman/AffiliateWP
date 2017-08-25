<?php
namespace AffWP\Utils;

use \Carbon\Carbon;

/**
 * Implements date formatting helpers for AffiliateWP.
 *
 * @since 2.2
 * @final
 */
final class Date {

	/**
	 * The current WordPress timezone.
	 *
	 * @since 2.2
	 * @var   string
	 */
	public $timezone;

	/**
	 * The current WordPress date format.
	 *
	 * @since 2.2
	 * @var   string
	 */
	public $date_format;

	/**
	 * The current WordPress time format.
	 *
	 * @since 2.2
	 * @var   string
	 */
	public $time_format;

	/**
	 * A shorthand combination of the WordPress date and time formats.
	 *
	 * @since 2.2
	 * @var   string
	 */
	public $datetime_format;

	/**
	 * A shorthand version of the MySQL date format.
	 *
	 * @since 2.2
	 * @var   string
	 */
	public $mysql_format = 'Y-m-d H:i:s';

	/**
	 * Sets up the class.
	 *
	 * @since 2.2
	 */
	public function __construct() {
		$this->timezone        = $this->get_core_timezone();
		$this->date_format     = get_option( 'date_format', 'M j, Y' );
		$this->time_format     = get_option( 'time_format', 'g:i a' );
		$this->datetime_format = $this->date_format . ' ' . $this->time_format;
	}

	/**
	 * Formats a given date string according to WP date and time formats and timezone.
	 *
	 * @since 2.2
	 *
	 * @param string      $date_string Date string to format.
	 * @param string|true $type        Optional. How to format the date string.  Accepts 'date',
	 *                                 'time', 'datetime', 'utc', 'timestamp', 'object', or any
	 *                                 valid date_format() string. If true, 'datetime' will be
	 *                                 used. Default 'datetime'.
	 * @param string      $timezone    Optional. Timezone to convert the date to before formatting.
	 *                                 Default empty string (WP timezone).
	 * @return string|int|\Carbon\Carbon Formatted date string, timestamp if `$type` is timestamp,
	 *                                   or a Carbon object if `$type` is 'object'.
	 */
	public function format( $date_string, $type = 'datetime', $timezone = '' ) {
		if ( empty( $timezone ) ) {
			$timezone = affiliate_wp()->utils->date->timezone;
		}

		if ( 'utc' === $type ) {
			$timezone = 'UTC';
			$type     = 'datetime';
		}

		$date = affiliate_wp()->utils->date( $date_string )->setTimezone( $timezone );

		if ( empty( $type ) || true === $type ) {
			$type = 'datetime';
		}

		switch( $type ) {
			case 'date':
				$formatted = $date->format( $this->date_format );
				break;

			case 'time':
				$formatted = $date->format( $this->time_format );
				break;

			case 'datetime':
				$formatted = $date->format( $this->date_format . ' ' . $this->time_format );
				break;

			case 'object':
				$formatted = $date;
				break;

			case 'timestamp':
				$formatted = $date->timestamp;
				break;

			default:
				$formatted = $date->format( $type );
				break;
		}

		return $formatted;
	}

	/**
	 * Attempts to retrieve the WP timezone, or if not set, the timezone derived from the gmt_offset.
	 *
	 * @since 2.2
	 *
	 * @return string Timezone string, or if all checks fail, default is 'UTC'.
	 */
	public function get_core_timezone() {

		// Passing a $default value doesn't work for the timezeon_string option.
		$timezone = get_option( 'timezone_string' );

		/*
		 * If the timezone isn't set, or rather was set to a UTC offset, core saves the value
		 * to the gmt_offset option and leaves timezone_string empty – because that makes
		 * total sense, obviously. ¯\_(ツ)_/¯
		 *
		 * So, try to use the gmt_offset to derive a timezone.
		 */
		if ( empty( $timezone ) ) {
			// Try to grab the offset instead.
			$gmt_offset = get_option( 'gmt_offset', 0 );

			// Yes, core returns it as a string, so as not to confuse it with falsey.
			if ( '0' !== $gmt_offset ) {
				$timezone = timezone_name_from_abbr( '', (int) $gmt_offset * HOUR_IN_SECONDS, date( 'I' ) );
			}

			// If the offset was 0 or $timezone is still empty, just use 'UTC'.
			if ( '0' === $gmt_offset || empty( $timezone ) ) {
				$timezone = 'UTC';
			}
		}

		return $timezone;
	}

}
