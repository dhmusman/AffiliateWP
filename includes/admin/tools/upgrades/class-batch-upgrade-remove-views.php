<?php
namespace AffWP\Utils\Batch_Process;

use AffWP\Utils;
use AffWP\Utils\Batch_Process as Batch;

/**
 * Implements a batch process to remove dependency on MySQLviews for AffiliateWP campaigns.
 *
 * @see \AffWP\Utils\Batch_Process\Base
 * @see \AffWP\Utils\Batch_Process
 * @package AffWP\Utils\Batch_Process
 */
class Upgrade_Remove_Views extends Utils\Batch_Process implements Batch\With_PreFetch {

	/**
	 * Batch process ID.
	 *
	 * @access public
	 * @since  2.1.6
	 * @var    string
	 */
	public $batch_id = 'upgrade-remove-views';

	/**
	 * Capability needed to perform the current batch process.
	 *
	 * @access public
	 * @since  2.1.6
	 * @var    string
	 */
	public $capability = 'manage_referrals';

	/**
	 * Number of campaigns to process per step.
	 *
	 * @access public
	 * @since  2.1.6
	 * @var    int
	 */
	public $per_step = 1;

	/**
	 * Initializes the batch process.
	 *
	 * @access public
	 * @since  2.1.6
	 */
	public function init( $data = null ) {

		// Affiliate schema update.
		affiliate_wp()->campaigns->create_table();
		affiliate_wp()->utils->log( 'Upgrade: MySQL views dependency is now removed, any existing campaigns will now be inserted into the campaigns table.' );


		wp_cache_set( 'last_changed', microtime(), 'campaigns' );
		affiliate_wp()->utils->log( 'Upgrade: The Campaigns cache has been invalidated following the 2.1.6 upgrade.' );



		parent::init( $data );
	}

	/**
	 * Pre-fetches data to speed up processing.
	 *
	 * @access public
	 * @since  2.1.6
	 */
	public function pre_fetch() {

		// @TODO Set the total count
	}

	/**
	 * Generates campaigns from referral data.
	 *
	 * @since  2.1.6
	 */
	public function generate_campaigns() {

		$referrals = affiliate_wp()->referrals->get_referrals( array( 'number' => -1 );
		$campaigns = affiliate_wp()->referrals->get_campaigns( array( 'number' => -1 ) );

		foreach ( $referrals as $referral ) {

			if ( ! empty( $referral->campaign ) ) {

				$maybe_campaign = affiliate_wp()->campaigns->get_

				if ( ! $maybe_campaign ) {

				}

			}

			// $data_sets[ $referral->affiliate_id ][] = $referral;
		}
	}


	/**
	 * Retrieves a message based on the given message code.
	 *
	 * @access public
	 * @since  2.1.6
	 *
	 * @param string $code Message code.
	 * @return string Message.
	 */
	public function get_message( $code ) {
		switch( $code ) {

			case 'done':
				$message = sprintf( __( 'Your database has been successfully upgraded. %s', 'affiliate-wp' ),
					sprintf( '<a href="">%s</a>', __( 'Dismiss Notice', 'affiliate-wp' ) )
				);
				break;

			default:
				$message = '';
				break;
		}

		return $message;
	}



	/**
	 * Defines logic to execute once batch processing is complete.
	 *
	 * @access public
	 * @since  2.1.6
	 */
	public function finish() {
		// Invalidate the campaigns cache.
		wp_cache_set( 'last_changed', microtime(), 'campaigns' );

		parent::finish();
	}

}
