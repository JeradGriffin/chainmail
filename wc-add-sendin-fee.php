<?php
// Adds "My Send-In Product" to cart when kit builder
// redirects to checkout with ?cm_addfee={qty}.
// Cody: replace SENDIN_PRODUCT_ID with the real WC product ID.

define('SENDIN_PRODUCT_ID', 0); // ← SET THIS to the WC product ID

add_action('woocommerce_before_checkout_form', function() {
    if (!isset($_GET['cm_addfee'])) return;
    $qty = intval($_GET['cm_addfee']);
    if ($qty <= 0) return;
    $pid = SENDIN_PRODUCT_ID;
    if (!$pid) return;

    // Remove any existing send-in line items to avoid duplicates
    foreach (WC()->cart->get_cart() as $key => $item) {
        if ((int) $item['product_id'] === $pid) {
            WC()->cart->remove_cart_item($key);
        }
    }

    WC()->cart->add_to_cart($pid, $qty);
});
