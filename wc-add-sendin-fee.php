<?php
// Adds spirit and/or send-in product to cart when kit builder
// redirects to checkout with ?cm_spirit_id=&cm_spirit_qty= and/or ?cm_addfee=

define('SENDIN_PRODUCT_ID', 5269);

add_action('woocommerce_before_checkout_form', function() {
    $added = false;

    // Spirit
    if (isset($_GET['cm_spirit_id'], $_GET['cm_spirit_qty'])) {
        $spirit_id  = intval($_GET['cm_spirit_id']);
        $spirit_qty = intval($_GET['cm_spirit_qty']);
        if ($spirit_id > 0 && $spirit_qty > 0) {
            foreach (WC()->cart->get_cart() as $key => $item) {
                if ((int) $item['product_id'] === $spirit_id) {
                    WC()->cart->remove_cart_item($key);
                }
            }
            WC()->cart->add_to_cart($spirit_id, $spirit_qty);
            $added = true;
        }
    }

    // Send-in fee
    if (isset($_GET['cm_addfee'])) {
        $qty = intval($_GET['cm_addfee']);
        $pid = SENDIN_PRODUCT_ID;
        if ($qty > 0 && $pid) {
            foreach (WC()->cart->get_cart() as $key => $item) {
                if ((int) $item['product_id'] === $pid) {
                    WC()->cart->remove_cart_item($key);
                }
            }
            WC()->cart->add_to_cart($pid, $qty);
            $added = true;
        }
    }
});
