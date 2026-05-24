<?php
/**
 * CHAMEVO FIXES — CHILD THEME ENQUEUE
 *
 * Add the contents of this file to the bottom of:
 *   wp-content/themes/hello-biz-child/functions.php
 *
 * Or save as a separate file and require_once it from functions.php.
 *
 * What gets enqueued:
 *  - chamevo-fixes.css  : brand color overrides + product page placeholder hide
 *  - chamevo-fixes.js   : auto-open Chamevo on variation select (product page only)
 *
 * The JS is deferred and depends on 'jquery' and 'wc-add-to-cart-variation'
 * so it runs after both WooCommerce and jQuery are ready.
 */

add_action( 'wp_enqueue_scripts', 'chainmail_enqueue_chamevo_fixes' );

function chainmail_enqueue_chamevo_fixes() {

    // CSS — loads on all front-end pages.
    // The :root overrides for --cv-primary only affect pages that load Chamevo.
    wp_enqueue_style(
        'chainmail-chamevo-fixes',
        get_stylesheet_directory_uri() . '/chamevo-fixes.css',
        array( 'chld_thm_cfg_child' ), // load after child theme's own stylesheet
        '1.0.0'
    );

    // JS — only load on single product pages to keep the site light.
    // wc-add-to-cart-variation is WooCommerce's variations script; waiting on it
    // ensures the variations form and jQuery events are fully initialized.
    if ( is_product() ) {
        wp_enqueue_script(
            'chainmail-chamevo-fixes',
            get_stylesheet_directory_uri() . '/chamevo-fixes.js',
            array( 'jquery', 'wc-add-to-cart-variation' ),
            '1.0.0',
            true // load in footer
        );
    }
}
