<?php
add_action( 'wp_head', function() { ?>
<script>
(function() {
    var p = new URLSearchParams(window.location.search);
    var isTee = window.location.href.indexOf('/product/tee') !== -1;
    if (p.get('kit') === '1' && !isTee) {
        document.documentElement.style.visibility = 'hidden';
    }
})();
</script>
<?php } );

add_action( 'wp_footer', function() { ?>
<script>
jQuery(function($) {
    var params = new URLSearchParams(window.location.search);
    if (params.get('kit') !== '1') return;
    if (window.location.href.indexOf('/product/tee') !== -1) return;
    console.log('chainmail: goods snippet running');

    var resumeUrl = 'https://chainmail-pi.vercel.app/?resume=goods';
    var backUrl = 'https://chainmail-pi.vercel.app/?back=goods';

    if (params.get('added-to-cart') && $('.woocommerce-message').length > 0) {
        window.location.href = resumeUrl;
        return;
    }

    document.documentElement.style.visibility = 'visible';

    var backLink = document.createElement('a');
    backLink.href = backUrl;
    backLink.textContent = 'Back to Kit Builder';
    backLink.style.display = 'block';
    backLink.style.color = '#6A449B';
    backLink.style.fontSize = '14px';
    backLink.style.fontWeight = '600';
    backLink.style.textDecoration = 'none';
    backLink.style.marginBottom = '16px';

    var target = document.querySelector('.summary.entry-summary');
    if (!target) target = document.querySelector('.product_title');
    if (!target) target = document.querySelector('form.cart');
    if (target) target.insertBefore(backLink, target.firstChild);

    var qty = parseInt(params.get('quantity'), 10);
    if (qty > 0) {
        var input = document.querySelector('input.qty');
        if (input) input.value = qty;
    }

    $(document).on('added_to_cart', function() {
        window.location.href = resumeUrl;
    });

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType !== 1) return;
                var isMsg = $(node).hasClass('woocommerce-message')
                    || $(node).find('.woocommerce-message').length > 0;
                if (isMsg) {
                    observer.disconnect();
                    window.location.href = resumeUrl;
                }
            });
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });
    console.log('chainmail: goods observer attached');
});
</script>
<?php } );
