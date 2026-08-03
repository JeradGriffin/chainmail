<?php
add_action( 'wp_head', function() { ?>
<script>
(function() {
    var p = new URLSearchParams(
        window.location.search
    );
    if (p.get('kit') === '1') {
        sessionStorage.setItem(
            'cm_tee_kit',
            window.location.pathname
        );
    }
    var stored = sessionStorage
        .getItem('cm_tee_kit');
    var isKit = p.get('kit') === '1';
    var isKitPage =
        stored === window.location.pathname;
    if (isKit || isKitPage) {
        document.documentElement
            .style.visibility = 'hidden';
    }
})();
</script>
<?php } );

add_action( 'wp_footer', function() { ?>
<script>
jQuery(function($) {
    var p = new URLSearchParams(
        window.location.search
    );
    var isKit = p.get('kit') === '1';
    var stored = sessionStorage
        .getItem('cm_tee_kit');
    var isKitPage =
        stored === window.location.pathname;
    console.log('cm-tee isKit=' + isKit
        + ' stored=' + stored
        + ' path=' + window.location.pathname
        + ' isKitPage=' + isKitPage);
    if (!isKit && !isKitPage) return;

    var resumeUrl =
        'https://chainmail-pi.vercel.app'
        + '/?resume=goods';
    var backUrl =
        'https://chainmail-pi.vercel.app'
        + '/?back=goods';

    if (isKit) {
        sessionStorage.setItem(
            'cm_tee_kit',
            window.location.pathname
        );
    }

    var msg = $('.woocommerce-message')
        .length > 0;
    var justAdded =
        p.get('added-to-cart') !== null;
    console.log('cm-tee msg=' + msg
        + ' justAdded=' + justAdded);

    function goResume() {
        var _d = sessionStorage
            .getItem('cm_tee_draft');
        if (_d) {
            try {
                localStorage.setItem(
                    'cm_good_tee', _d
                );
            } catch(_e) {}
        }
        sessionStorage.removeItem(
            'cm_tee_kit'
        );
        sessionStorage.removeItem(
            'cm_tee_draft'
        );
        window.location.href = resumeUrl;
    }

    // Post-add-to-cart reload
    if (isKitPage && (msg || justAdded)) {
        goResume();
        return;
    }

    document.documentElement
        .style.visibility = 'visible';

    var backLink =
        document.createElement('a');
    backLink.href = backUrl;
    backLink.textContent =
        'Back to Kit Builder';
    backLink.style.cssText =
        'display:block;color:#6A449B;'
        + 'font-size:14px;font-weight:600;'
        + 'text-decoration:none;'
        + 'margin-bottom:16px';
    var target = document.querySelector(
        '.summary.entry-summary'
    ) || document.querySelector(
        '.product_title'
    ) || document.querySelector('form.cart');
    if (target) target.insertBefore(
        backLink, target.firstChild
    );

    var qty = parseInt(
        p.get('quantity'), 10
    );
    if (qty > 0) {
        var inp = document.querySelector(
            'input.qty'
        );
        if (inp) inp.value = qty;
    }

    // Watch for message added dynamically
    var obs = new MutationObserver(
        function(mutations) {
            mutations.forEach(function(m) {
                m.addedNodes.forEach(
                    function(node) {
                        if (node.nodeType !== 1)
                            return;
                        var isMsg =
                            $(node).hasClass(
                            'woocommerce-message'
                            ) || $(node).find(
                            '.woocommerce-message'
                            ).length > 0;
                        if (isMsg) {
                            obs.disconnect();
                            goResume();
                        }
                    }
                );
            });
        }
    );
    obs.observe(document.body, {
        childList: true, subtree: true
    });

    // Also catch AJAX add_to_cart event
    $(document).on(
        'added_to_cart', function() {
            obs.disconnect();
            goResume();
        }
    );
});
</script>
<?php } );
