<?php
// Adds spirit and/or send-in product to cart when kit builder
// redirects to checkout with ?cm_spirit_id=&cm_spirit_qty= and/or ?cm_addfee=
// Uses wp hook so it fires for both classic and block checkout.

// Allow kit builder origins to call the WC Store API.
add_filter('allowed_http_origins', function($origins) {
    $origins[] = 'https://chainmail-pi.vercel.app';
    $origins[] = 'http://localhost:5173';
    return $origins;
});

define('SENDIN_PRODUCT_ID', 5269);

add_action('wp', function() {
    if (!function_exists('WC') || !WC()->cart) return;

    $needs_spirit  = isset($_GET['cm_spirit_id'], $_GET['cm_spirit_qty']);
    $needs_sendin  = isset($_GET['cm_addfee']);
    if (!$needs_spirit && !$needs_sendin) return;

    $added = false;

    // Spirit
    if ($needs_spirit) {
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
    if ($needs_sendin) {
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

    // Redirect to clean checkout URL so params
    // don't re-add items on every page refresh
    if ($added && is_checkout()) {
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }
});
