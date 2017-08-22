<?php
namespace AffWP\Utils\Batch_Process;

use AffWP\Utils;
use AffWP\Utils\Batch_Process as Batch;

/**
 * Implements a batch processor for generating coupons logs and exporting them to a CSV file.
 *
 * @since 2.2
 *
 * @see \AffWP\Utils\Batch_Process
 * @see \AffWP\Utils\Batch_Process\With_PreFetch
 */
class Generate_Coupons extends Utils\Batch_Process implements Batch\With_PreFetch {

	/**
	 * Batch process ID.
	 *
	 * @access public
	 * @since  2.2
	 * @var    string
	 */
	public $batch_id = 'generate-coupons';

	/**
	 * Capability needed to perform the current export.
	 *
	 * @access public
	 * @since  2.2
	 * @var    string
	 */
	public $capability = 'manage_coupons';

	/**
	 * The number of coupons to generate in each step.
	 *
	 * @access public
	 * @since  2.2
	 * @var    int
	 */
	public $per_step = 20;

	/**
	 * Integrations for which affiliate coupons will be generated.
	 *
	 * @access public
	 * @since  2.2
	 * @var    string
	 */
	public $integrations = array();

	/**
	 * Initializes the batch process.
	 *
	 * This is the point where any relevant data should be initialized for use by the processor methods,
	 * and it only runs on the first step.
	 *
	 * @access public
	 * @since  2.2
	 *
	 * @param null|array $data Optional. Submitted form data to use for prefetching – and ultimately for
	 *                         use by subsequent steps. Default null.
	 */
	public function init( $data = null ) {

		if ( null !== $data ) {

			if ( ! empty( $data['integrations'] ) ) {
				$this->integrations = ! empty( $data['integrations'] ) ? (array) $data['integrations'] : array();
				$this->integrations = array_map( 'esc_attr', $this->integrations );
			} else {
				$this->integrations = affiliate_wp()->settings->get( 'coupon_integrations' );
			}

		}

	}

	/**
	 * Pre-fetches data to speed up processing.
	 *
	 * @access public
	 * @since  2.2
	 */
	public function pre_fetch() {
		$total_affiliates = affiliate_wp()->affiliates->count( array(
			'status' => 'active'
		) );

		$this->integrations = affiliate_wp()->settings->get( 'coupon_integrations' );
		$this->set_total_count( $total_affiliates );
	}

	/**
	 * Processes a single step for generating affiliate coupons.
	 *
	 * @access public
	 * @since  2.2
	 */
	public function process_step() {
		if ( ! $this->integrations ) {
			return new \WP_Error(
				'missing_integration_data',
				__( 'The integration and coupon template must be defined to generate affiliate coupons.', 'affiliate-wp' )
			);
		}

		$current_count = $this->get_current_count();

		$affiliate_ids = affiliate_wp()->affiliates->get_affiliates( array(
			'fields' => 'ids',
			'status' => 'active',
			'number' => $this->per_step,
			'offset' => $this->get_offset(),
		) );

		// If there are no more affiliates for which to generate coupons, we're done.
		if ( empty( $affiliate_ids ) ) {
			return 'done';
		}

		$generated    = array();
		$integrations = $this->integrations;

		foreach ( $affiliate_ids as $affiliate_id ) {

			foreach ( $integrations as $integration_id => $integration ) {

				$coupon   = false;
				$template = affwp_get_coupon_template( $integration_id );
				$args     = array(
					'affiliate_id'    => $affiliate_id,
					'coupon_code'     => affwp_generate_coupon_code( $affiliate_id, $integration_id, '' ),
					'integration'     => $integration_id,
					'template_id'     => affwp_get_coupon_template_id( $integration_id ),
					'referrals'       => array(),
					'owner'           => get_current_user_id(),
					'status'          => 'active',
					'expiration_date' => $template[ 'expiration_date' ],
					'is_template'     => 0
				);

				// Generate an integration coupon
				if ( affwp_generate_integration_coupon( $args ) ) {
					// If successful, generate an internal AffiliateWP coupon object.
					$coupon = affwp_add_coupon( $args );
				} else {
					affiliate_wp()->utils->log( 'Unable to generate integration coupon during batch process. Provided data: ' . print_r( $args, true ) );
				}

				if ( $coupon ) {
					$generated[] = $coupon;
				}
			}
		}

		$this->set_current_count( $current_count + count( $generated ) );

		return ++$this->step;
	}

	/**
	 * Retrieves a message for the given code.
	 *
	 * @access public
	 * @since  2.2
	 *
	 * @param string $code Message code.
	 * @return string Message.
	 */
	public function get_message( $code ) {

		switch( $code ) {

			case 'done':
				$final_count = $this->get_current_count();

				if ( ! $final_count ) {
					$message = __( 'No affiliate coupons were generated.', 'affiliate-wp' );
				} else {
					$message = sprintf(
						_n(
							'%1$s affiliate coupon was successfully generated for the selected %2$s coupon template.',
							'%1$s affiliate coupons were successfully generated for the selected %2$s coupon template.',
							$final_count,
							'affiliate-wp'
						), number_format_i18n( $final_count ), $this->integration
					);
				}
				break;

			default:
				$message = '';
				break;
		}

		return $message;
	}

}
