<?php
/**
 * Created by PhpStorm.
 * User: Drew
 * Date: 10/31/17
 * Time: 10:56 AM
 */

namespace AffWP\Affiliate_Area;

use AffWP\Utils\Registry;

/**
 * Implements a registry for affiliate area tabs.
 *
 * @since 2.1.7
 *
 * @see \AffWP\Utils\Registry
 */
class Tabs_Registry extends Registry {

	/**
	 * Sets up the tabs registry.
	 *
	 * @since 2.1.7
	 */
	public function init() {}

	/**
	 * Adds a tab to the affiliate area registry.
	 *
	 * @since 2.1.7
	 *
	 * @param string $slug     Affiliate Area tab slug.
	 * @param array  $attributes {
	 *     Tab attributes.
	 *
	 *     @type string $label    Label for the tab.
	 *     @type int    $priority Priority by which to load the tab. Default 10.
	 *     @type bool   $enabled  Whether the tab should be considered enabled or not.
	 * }
	 * @return true Always true.
	 */
	public function add_tab( $slug, $attributes ) {
		$defaults = array(
			'priority' => 10,
			'enabled'  => true,
		);

		$attributes = array_merge( $defaults, $attributes );

		return parent::add_item( $slug, $attributes );
	}

	/**
	 * Removes a tab from the affiliate area registry by slug.
	 *
	 * @since 2.1.7
	 *
	 * @param string $slug Tab slug.
	 */
	public function remove_tab( $slug ) {
		parent::remove_item( $slug );
	}

	/**
	 * Retrieves registered affiliate area tabs.
	 *
	 * @since 2.1.7
	 *
	 * @return array The list of registered tabs.
	 */
	public function get_tabs() {
		$tabs = parent::get_items();

		$new_order = array();

		foreach ( $tabs as $tab => $attributes ) {
			$key = "{$attributes['priority']}:{$tab}";
			$new_order[ $key ] = $attributes;
		}

		// Reset tabs.
		$tabs = array();

		@natsort( $new_order );

		foreach ( $new_order as $slug => $attributes ) {
			$slug = substr( $slug, 2 );

			$tabs[ $slug ] = $attributes;
		}

		return $tabs;
	}

}
