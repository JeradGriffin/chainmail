<?php
add_action( 'wp_head', function() { ?>
<script>
(function() {
    var isTee = window.location.href.indexOf('/product/tee') !== -1;
    if (isTee) return;
    var p = new URLSearchParams(window.location.search);
    if (p.get('kit') === '1') {
        sessionStorage.setItem('cm_kit', window.location.pathname);
    }
    var stored = sessionStorage.getItem('cm_kit');
    if (stored && stored === window.location.pathname) {
        document.documentElement.style.visibility = 'hidden';
    }
})();
</script>
<?php } );

add_action( 'wp_footer', function() { ?>
<script>
jQuery(function($) {
    var isTee = window.location.href.indexOf('/product/tee') !== -1;
    if (isTee) return;
    var params = new URLSearchParams(window.location.search);
    var isKit = params.get('kit') === '1';
    var stored = sessionStorage.getItem('cm_kit');
    var isKitPage = stored === window.location.pathname;
    if (!isKit && !isKitPage) return;
    if (isKit) {
        sessionStorage.setItem('cm_kit', window.location.pathname);
    }
    var resumeUrl = 'https://chainmail-pi.vercel.app/?resume=goods';
    var backUrl = 'https://chainmail-pi.vercel.app/?back=goods';
    function captureGood() {
        var gid = params.get('gid');
        if (!gid) return;
        var d = {};
        var im = $('img.wp-post-image').first().attr('src');
        if (im) d.image = im;
        var pts = [];
        $('table.variations select').each(function() {
            var v = $(this).find('option:selected').text().trim();
            if (v && v.indexOf('Choose') < 0) pts.push(v);
        });
        $('[class*="pewc"] input[type="radio"]:checked').each(function() {
            var lb = $(this).closest('label').text().trim();
            if (!lb) lb = $(this).val().replace(/_/g, ' ');
            if (lb) pts.push(lb);
        });
        if (pts.length) d.detail = pts.join(' | ');
        localStorage.setItem('cm_good_' + gid, JSON.stringify(d));
    }
    function goResume() {
        captureGood();
        sessionStorage.removeItem('cm_kit');
        window.location.href = resumeUrl;
    }
    var msg = $('.woocommerce-message').length > 0;
    var justAdded = params.get('added-to-cart') !== null;
    // form submit, ?kit=1 preserved in URL
    if (isKit && justAdded && msg) { goResume(); return; }
    // form submit, ?kit=1 stripped — rely on sessionStorage
    if (!isKit && isKitPage && msg) { goResume(); return; }
    document.documentElement.style.visibility = 'visible';
    console.log('chainmail: goods running on ' + window.location.pathname);
    var backLink = document.createElement('a');
    backLink.href = backUrl;
    backLink.textContent = 'Back to Kit Builder';
    backLink.style.cssText = 'display:block;color:#6A449B;' +
        'font-size:14px;font-weight:600;' +
        'text-decoration:none;margin-bottom:16px';
    var target = document.querySelector('.summary.entry-summary')
        || document.querySelector('.product_title')
        || document.querySelector('form.cart');
    if (target) target.insertBefore(backLink, target.firstChild);
    var qty = parseInt(params.get('quantity'), 10);
    if (qty > 0) {
        var qin = document.querySelector('input.qty');
        if (qin) qin.value = qty;
    }
    $(document).on('added_to_cart', function() { goResume(); });
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType !== 1) return;
                var isMsg = $(node).hasClass('woocommerce-message')
                    || $(node).find('.woocommerce-message').length > 0;
                if (isMsg) { observer.disconnect(); goResume(); }
            });
        });
    });
    observer.observe(document.body, {childList:true, subtree:true});
    console.log('chainmail: goods observer attached');
});
</script>
<?php } );
