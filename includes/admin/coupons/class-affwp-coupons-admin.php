<?php
/**
 * 'Coupons' Admin Table
 *
 * @package   AffiliateWP\Admin\Coupons
 * @copyright Copyright (c) 2017, AffiliateWP, LLC
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     2.2
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * AffWP_Coupons_Admin class.
 *
 * Renders the Coupons table on Affiliate-edit and screens.
 *
 * @since 2.2
 */
class AffWP_Coupons_Admin {

	/**
	 * Coupons table constructor.
	 *
	 * @access public
	 * @since 2.2
	 *
	 * @see WP_List_Table::__construct()
	 *
	 * @param array $args Optional. Arbitrary display and query arguments to pass through
	 *                    the list table. Default empty array.
	 */
	public function __construct( $args = array() ) {
	}

	/**
	 * Renders the coupons table on affiliate edit and new screens.
	 *
	 * @since  2.2
	 *
	 * @param  integer $affiliate_id Affiliate ID.
	 *
	 * @return void
	 */
	public function coupons_table( $affiliate_id = 0 ) {

		if ( ! affwp_is_affiliate( $affiliate_id ) ) {

			$affiliate    = affwp_get_affiliate( absint( $_GET['affiliate_id'] ) );
			$affiliate_id = $affiliate ? $affiliate->affiliate_id : 0;

			if ( ! $affiliate_id ) {
				affiliate_wp()->utils->log( 'Unable to determine affiliate ID in coupons_table method.' );
				return false;
			}

		}

		/**
		 * Fires at the top of coupons admin table views.
		 *
		 * @since 2.2
		 */
		do_action( 'affwp_affiliate_coupons_table_top' );

		$coupons = affwp_get_affiliate_coupons( $affiliate_id );

		if ( empty( $coupons ) ) {
			_e( 'No coupons were found for this affiliate.', 'affiliate-wp' );

			return;
		}

		?>

		<hr />

		<p>
			<style type="text/css">
				#affiliatewp-coupons th {
					padding-left: 10px;
				}
			</style>
			<strong>
				<?php _e( 'Coupons for this affiliate:', 'affiliate-wp' ); ?>
			</strong>
		</p>

		<table id="affiliatewp-coupons" class="form-table wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th><?php _e( 'Integration', 'affiliate-wp' ); ?></th>
					<th><?php _e( 'Coupon Code', 'affiliate-wp' ); ?></th>
					<th><?php _e( 'ID',          'affiliate-wp' ); ?></th>
					<th><?php _e( 'Referrals',   'affiliate-wp' ); ?></th>
					<th><?php _e( 'View/Edit',        'affiliate-wp' ); ?></th>
					<th style="width:5%;"></th>
				</tr>
			</thead>
			<tbody>
				<?php

				affiliate_wp()->utils->log( 'Coupons for affiliate ID: ' . $affiliate_id . ' ' . print_r( $coupons, true ) );

				$integrations = affiliate_wp()->integrations->get_enabled_integrations();

				foreach ( $integrations as $integration_id => $integration_term ) {

					if ( affwp_integration_has_coupon_support( $integration_id ) ) {

						$template_id = affwp_get_coupon_template_id( $integration_id );

						$template_url = affwp_get_coupon_edit_url( $template_id, $integration_id );

						$args = array(
							'affiliate_id' => $affiliate_id,
							'integration'  => $integration_id
						);

						$coupons = affiliate_wp()->affiliates->coupons->get_coupons( $args );

						if ( ! empty( $coupons ) ) {

							foreach ( $coupons as $coupon ) {

								$coupon = is_array( $coupon ) ? $coupon : (array) $coupon;

								$coupon_referrals = affiliate_wp()->referrals->get_referrals( array(
										'number'       => -1,
										'affiliate_id' => $affiliate_id,
										'coupon_id'    => $coupon['coupon_id']

									)
								);

								$referrals_url = affwp_admin_url( 'referrals' );
								$referrals_url = $coupon['coupon_id'] ? add_query_arg( 'coupon_id', $coupon['coupon_id'], $referrals_url ) : $referrals_url;

								?>
								<tr>
									<td>
										<?php echo $coupon['integration']; ?>
									</td>
									<td>
										<?php echo $coupon['coupon_code']; ?>
									</td>
									<td>
										<?php echo $coupon['integration_coupon_id']; ?>
									</td>
									<td>
										<?php echo ! empty( $coupon_referrals ) ? count( $coupon_referrals ) . ' <a href="' . esc_url( $referrals_url ) . '">' . __( 'View', 'affiliate-wp' ) . '</a>' : __( 'No referrals were found.', 'affiliate-wp' ); ?>
									</td>
									<td>
										<?php
										$coupon_edit_url = affwp_get_coupon_edit_url( $coupon[ 'integration_coupon_id' ], $coupon[ 'integration' ] );
										if ( $coupon_edit_url ) {
											echo '<a href="' . esc_url( $coupon_edit_url ) . '">' . __( 'View/Edit', 'affiliate-wp' ) . '</a>';
										} else {
											affiliate_wp()->utils->log( sprintf( 'Unable to get coupon edit URL for the %s integration.', $coupon[ 'integration' ] ) );
										} ?>

									</td>
								</tr>
					<?php   }

						}
					}
				}
?>

			</tbody>
			<tfoot>
			</tfoot>
		</table>

		<p class="description">
			<?php echo __( 'The current coupons for this affiliate.', 'affiliate-wp' ); ?>
		</p>

	<?php

		/**
		 * Fires at the bottom of coupons admin table views.
		 *
		 * @since 2.2
		 */
		do_action( 'affwp_affiliate_coupons_table_bottom' );
	}

}
