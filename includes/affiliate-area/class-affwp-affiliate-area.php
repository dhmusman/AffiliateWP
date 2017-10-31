<?php

use \AffWP\Affiliate_Area\Tabs_Registry;

require_once AFFILIATEWP_PLUGIN_DIR . 'includes/affiliate-area/class-affiliate-area-tabs-registry.php';

/**
 * Core class that encapsulates affiliate area functionality.
 *
 * @since 2.1.7
 *
 */
class Affiliate_WP_Affiliate_Area {

	/**
	 * Tabs registry.
	 *
	 * @since 2.1.7
	 * @var   \AffWP\Affiliate_Area\Tabs_Registry
	 */
	public $tabs;

	/**
	 * Sets up some base Affiliate Area API functionality.
	 *
	 * @since 2.1.7
	 */
	public function __construct() {
		$this->tabs = new Tabs_Registry;

		$this->register_core_tabs();

		do_action( 'affwp_affiliate_area_register_tabs', $this );

		$this->_register_compat();

//		log_it( $this->tabs->get_tabs() );
	}

	/**
	 * Register's the core affiliate area tabs.
	 *
	 * @since 2.1.7
	 */
	public function register_core_tabs() {

		$this->tabs->add_tab( 'urls', array(
			'label'    => __( 'Affiliate URLS', 'affiliate-wp' ),
			'priority' => 0,
		) );

		$this->tabs->add_tab( 'stats', array(
			'label'    => __( 'Statistics', 'affiliate-wp' ),
			'priority' => 1,
		) );

		$this->tabs->add_tab( 'graphs', array(
			'label'    => __( 'Graphs', 'affiliate-wp' ),
			'priority' => 2,
		) );

		$this->tabs->add_tab( 'referrals', array(
			'label'    => __( 'Referrals', 'affiliate-wp' ),
			'priority' => 3,
		) );

		$this->tabs->add_tab( 'payouts', array(
			'label'    => __( 'Payouts', 'affiliate-wp' ),
			'priority' => 4,
		) );

		$this->tabs->add_tab( 'visits', array(
			'label'    => __( 'Visits', 'affiliate-wp' ),
			'priority' => 5,
		) );

		$this->tabs->add_tab( 'creatives', array(
			'label'    => __( 'Creatives', 'affiliate-wp' ),
			'priority' => 6,
		) );

		$this->tabs->add_tab( 'settings', array(
			'label'    => __( 'Settings', 'affiliate-wp' ),
			'priority' => 7,
		) );

	}

	/**
	 * Handles registering tabs previously added via the filter only.
	 *
	 * @since 2.1.7
	 */
	private function _register_compat() {
		$tabs       = apply_filters( 'affwp_affiliate_area_tabs', array() );
		$registered = array_keys( $this->tabs->get_tabs() );
		$missed     = array_intersect( $tabs, $registered );

		if ( ! empty( $missed ) ) {
			foreach ( $missed as $tab ) {
				$this->tabs->add_tab( $tab, array(
					'label' => ucfirst( $tab )
				) );
			}
		}
	}
}
