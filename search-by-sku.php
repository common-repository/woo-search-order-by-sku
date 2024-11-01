<?php
/*
  Plugin Name: Search order by SKU
  Plugin URI: http://www.freewebmentor.com/
  Description: This plugin will provide the extra search functionality to your product by SKU number. This simple plugin adds this functionality to both the admin site and regular search.
  Author: Prem Tiwari
  Version: 1.0.2
  Author URI: http://www.freewebmentor.com/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check woocommerce plugin is activated
if ( ! function_exists( 'sobs_is_woocommerce_activated' ) ) {
	function sobs_is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/**
 * Search product by SKU in Admin Woocommerce Orders
 */
add_filter('woocommerce_shop_order_search_fields', function ($search_fields ) {
    $posts = get_posts(array('post_type' => 'shop_order'));

    foreach ($posts as $post) {
        $order_id = $post->ID;
        $order = new WC_Order($order_id);
        $items = $order->get_items();

        foreach ($items as $item) {
            $product_id = $item['product_id'];
            $search_sku = get_post_meta($product_id, "_sku", true);
            add_post_meta($order_id, "_product_sku", $search_sku);
        }
    }

    return array_merge($search_fields, array('_product_sku'));
});
