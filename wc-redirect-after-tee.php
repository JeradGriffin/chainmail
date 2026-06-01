<?php
/**
 * On the TEE product page (ID 1946):
 * 1. Hides page immediately on load to prevent flash
 * 2. If cart message is present (post add-to-cart reload), redirects back to kit builder with ?resume=goods
 * 3. Otherwise reveals the page, injects a Back button, pre-fills quantity from URL param
 * 4. After Add to Cart (AJAX), watches for View Cart notice and redirects
 */
add_action( 'wp_head', function() { ?>
<script>
if (window.location.href.indexOf('/product/tee') !== -1) {
    document.documentElement.style.visibility = 'hidden';
}
</script>
<?php } );

add_action( 'wp_footer', function() { ?>
<script>
jQuery(function($) {
    if (window.location.href.indexOf('/product/tee') === -1) return;

    // Post-add-to-cart reload: woocommerce-message already in DOM, redirect immediately
    if ($('.woocommerce-message').length > 0) {
        window.location.href = 'https://chainmail-pi.vercel.app/?resume=goods';
        return;
    }

    // Page loaded normally — reveal it
    document.documentElement.style.visibility = 'visible';

    // Inject back button above product title
    var backBtn = '<a href="https://chainmail-pi.vercel.app/?back=goods" style="display:inline-flex;align-items:center;gap:6px;color:#6A449B;font-size:14px;font-weight:600;text-decoration:none;margin-bottom:16px;">&#8592; Back to Kit Builder</a>';
    $('h1.product_title').before(backBtn);

    // Pre-fill quantity from URL param
    var qty = parseInt(new URLSearchParams(window.location.search).get('quantity'), 10);
    if (qty > 0) { var input = document.querySelector('input.qty'); if (input) input.value = qty; }

    // AJAX case — watch for View Cart notice appearing
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType !== 1) return;
                var text = node.textContent || node.innerText || '';
                if (text.toLowerCase().indexOf('view cart') !== -1) {
                    observer.disconnect();
                    window.location.href = 'https://chainmail-pi.vercel.app/?resume=goods';
                }
            });
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });
});
</script>
<?php } );
