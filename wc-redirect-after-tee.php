<?php
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
    var teeUrl = '/product/tee';
    if (window.location.href.indexOf(teeUrl) === -1) return;
    console.log('chainmail: snippet running');

    var resumeUrl = 'https://chainmail-pi.vercel.app/?resume=goods';
    var backUrl = 'https://chainmail-pi.vercel.app/?back=goods';

    if ($('.woocommerce-message').length > 0) {
        console.log('chainmail: cart message found, redirecting');
        window.location.href = resumeUrl;
        return;
    }

    console.log('chainmail: revealing page');
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
    console.log('chainmail: back button injected');

    var params = new URLSearchParams(window.location.search);
    var qty = parseInt(params.get('quantity'), 10);
    if (qty > 0) {
        var input = document.querySelector('input.qty');
        if (input) input.value = qty;
    }

    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType !== 1) return;
                var txt = node.textContent || node.innerText || '';
                if (txt.toLowerCase().indexOf('view cart') !== -1) {
                    console.log('chainmail: view cart detected');
                    observer.disconnect();
                    window.location.href = resumeUrl;
                }
            });
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });
    console.log('chainmail: observer attached');
});
</script>
<?php } );
