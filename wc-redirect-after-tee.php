<?php
/**
 * After adding TEE (product ID 1946) to cart, redirect back to the kit builder
 * with ?resume=goods so the React app can restore state and continue the flow.
 *
 * Add this snippet to the child theme's functions.php
 * (hello-biz-child/functions.php)
 */
add_filter( 'woocommerce_add_to_cart_redirect', function( $url ) {
    $kit_builder_url = 'https://chainmail.store/dev/kit-builder/'; // TODO: replace with actual kit builder page URL

    if ( isset( $_POST['add-to-cart'] ) && absint( $_POST['add-to-cart'] ) === 1946 ) {
        return add_query_arg( 'resume', 'goods', $kit_builder_url );
    }

    return $url;
} );

/**
 * Pre-fill the quantity input from the ?quantity= URL param on the TEE product page.
 * Runs only on the TEE product page (ID 1946).
 */
add_action( 'woocommerce_before_add_to_cart_button', function() {
    global $product;
    if ( ! $product || $product->get_id() !== 1946 ) return;
    ?>
    <script>
    (function() {
        var params = new URLSearchParams( window.location.search );
        var qty = parseInt( params.get('quantity'), 10 );
        if ( qty > 0 ) {
            var input = document.querySelector( 'input.qty' );
            if ( input ) { input.value = qty; }
        }
    })();
    </script>
    <?php
} );
