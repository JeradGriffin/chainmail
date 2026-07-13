<?php
// Configurator — mobile overlay + desktop inline tabs — all WC product pages.
// Add as a Code Snippets entry. Keep wc-tee-product-styles.php active too.

add_action('wp_head', function() {
    if (!is_singular() || !function_exists('is_product')) return;
    if (!is_product()) return;
    echo <<<'STYLE'
<style>

/* ---- Mobile: full-screen overlay ---- */
@media (max-width: 767px) {

#tee-conf {
  position: fixed;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  max-width: 430px;
  height: 100dvh;
  background: #111;
  z-index: 999999;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  font-family: 'Inter', -apple-system, sans-serif;
}

#tee-conf-topbar {
  flex-shrink: 0;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 16px;
}

#tee-conf-icons {
  display: flex;
  align-items: center;
  gap: 20px;
}

#tee-conf-topbar a,
#tee-menu-btn {
  color: #000;
  display: flex;
  align-items: center;
  text-decoration: none;
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  -webkit-tap-highlight-color: transparent;
}

#tee-conf-site-logo {
  height: 32px;
  width: auto;
  display: block;
}

#tee-conf-logo-txt {
  font-size: 17px;
  font-weight: 700;
  color: #000;
  letter-spacing: -0.02em;
}

#tee-conf-nav-panel {
  display: none;
  position: absolute;
  top: 52px;
  left: 0;
  right: 0;
  z-index: 2;
  flex-direction: column;
  background: #fff;
  border-bottom: 1px solid #eee;
  box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

#tee-conf-nav-panel.open {
  display: flex;
}

#tee-conf-nav-panel a {
  padding: 16px 20px;
  font-size: 16px;
  font-weight: 600;
  color: #000;
  text-decoration: none;
  border-bottom: 1px solid #f0f0f0;
}

#tee-conf-chain {
  flex-shrink: 0;
  background: #fff;
  line-height: 0;
  overflow: hidden;
}

#tee-conf-chain img {
  width: 100%;
  display: block;
  height: auto;
}

#tee-conf-img {
  position: relative;
  flex: 1;
  min-height: 0;
  overflow: hidden;
  background: #111;
}

#tee-conf-photo {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: top center;
  display: block;
}

#tee-conf-logo-overlay {
  position: absolute;
  top: 38%;
  left: 50%;
  transform: translateX(-50%);
  pointer-events: none;
}

#tee-conf-logo-overlay.pos-left {
  left: auto;
  right: 33%;
  transform: none;
}

#tee-conf-logo-overlay.pos-center {
  left: 50%;
  transform: translateX(-50%);
}

#tee-conf-logo-overlay.pos-right {
  left: 22%;
  right: auto;
  transform: none;
}

#tee-conf-logo-img {
  max-width: 70px;
  max-height: 70px;
  object-fit: contain;
}

/* Scrollable native form area */
#tee-conf-form {
  flex: 0 1 auto;
  min-height: 0;
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  background: #fff;
}

/* Variation selects */
#tee-conf-form table.variations {
  width: 100%;
  border-collapse: collapse;
}

#tee-conf-form table.variations th {
  text-align: left;
  padding: 14px 20px 4px;
  font-size: 12px;
  font-weight: 700;
  color: #888;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  white-space: nowrap;
}

#tee-conf-form table.variations td {
  padding: 0 20px 12px;
}

#tee-conf-form table.variations select {
  width: 100%;
  height: 50px;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 0 14px;
  font-size: 15px;
  font-weight: 500;
  color: #000;
  background: #fff;
  box-sizing: border-box;
}

/* Hide WC clutter in form */
#tee-conf-form .reset_variations,
#tee-conf-form .woocommerce-variation-price,
#tee-conf-form .woocommerce-variation-availability,
#tee-conf-form .quantity {
  display: none !important;
}

/* PEWC native elements */
#tee-conf-form [class*="pewc-item"],
#tee-conf-form [class*="pewc-group"] {
  padding: 12px 20px;
  border-bottom: 1px solid #f0f0f0;
}

#tee-conf-form [class*="pewc-item-label"],
#tee-conf-form [class*="pewc-group-title"] {
  font-size: 12px;
  font-weight: 700;
  display: block;
  margin-bottom: 8px;
  color: #888;
  text-transform: uppercase;
  letter-spacing: 0.06em;
}

#tee-conf-form label {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 6px 0;
  font-size: 15px;
  color: #000;
  cursor: pointer;
}

#tee-conf-form input[type="radio"] {
  width: 18px;
  height: 18px;
  accent-color: #6A449B;
  flex-shrink: 0;
}

#tee-conf-form input[type="file"] {
  display: block;
  width: calc(100% - 40px);
  margin: 0 20px 12px;
  padding: 24px 20px;
  border: 2px dashed #6A449B;
  border-radius: 8px;
  background: #f5f0ff;
  font-size: 14px;
  font-weight: 600;
  color: #6A449B;
  box-sizing: border-box;
  cursor: pointer;
}

/* Bottom nav */
#tee-conf-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 20px;
  padding-bottom: calc(
    12px + env(safe-area-inset-bottom)
  );
  background: #f2f2f2;
  border-top: 1px solid #ddd;
  flex-shrink: 0;
  gap: 10px;
}

#tee-conf-back {
  font-size: 15px;
  font-weight: 600;
  color: #444;
  background: none;
  border: none;
  cursor: pointer;
  padding: 10px 4px;
  white-space: nowrap;
}

#tee-conf-preview {
  font-size: 14px;
  font-weight: 600;
  color: #6A449B;
  background: #fff;
  border: 2px solid #6A449B;
  padding: 9px 14px;
  cursor: pointer;
  white-space: nowrap;
  border-radius: 4px;
}

/* WC Add to Kit — always fixed at bottom right */
body.cm-kit-mode .single_add_to_cart_button {
  position: fixed !important;
  bottom: calc(
    12px + env(safe-area-inset-bottom)
  ) !important;
  right: 20px !important;
  z-index: 9999999 !important;
  font-size: 15px !important;
  font-weight: 700 !important;
  color: #fff !important;
  background: #6A449B !important;
  border: 2px solid #6A449B !important;
  padding: 9px 14px !important;
  border-radius: 4px !important;
  white-space: nowrap !important;
  cursor: pointer !important;
  font-family: 'Inter', -apple-system,
    sans-serif !important;
  line-height: normal !important;
  text-transform: none !important;
  letter-spacing: normal !important;
  box-shadow: none !important;
}

body.cm-kit-mode
.single_add_to_cart_button:disabled {
  background: transparent !important;
  color: #6A449B !important;
  cursor: not-allowed !important;
}

} /* end mobile */

/* ---- Desktop: inline tab widget ---- */
@media (min-width: 768px) {

.variations {
  display: none !important;
}

#tee-desk {
  margin-bottom: 16px;
  border: 1px solid #eee;
  border-radius: 4px;
  font-family: 'Inter', -apple-system, sans-serif;
  position: relative;
}

#tee-desk-tabs {
  display: flex;
  background: #f8f8f8;
  border-bottom: 1px solid #eee;
}

#tee-desk-opts { background: #fff; }

.tee-ctab {
  flex: 1;
  min-width: 0;
  padding: 10px 4px;
  font-size: 11px;
  font-weight: 600;
  color: #bbb;
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  border-bottom: 2px solid transparent;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
  box-sizing: border-box;
}

.tee-ctab.active {
  color: #6A449B;
  border-bottom-color: #6A449B;
}

.tee-ctab.done {
  color: #6A449B;
  opacity: 0.45;
}

.tee-sel-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px;
  background: #fff;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
}

.tee-sel-val {
  font-size: 16px;
  font-weight: 500;
  color: #bbb;
}

.tee-sel-val.has-val {
  color: #6A449B;
  font-weight: 700;
}

.tee-sel-icon {
  font-size: 20px;
  color: #6A449B;
  line-height: 1;
  flex-shrink: 0;
  width: 24px;
  text-align: center;
}

.tee-sel-wrap {
  position: relative;
}

.tee-opt-list {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 500;
  background: #fff;
  border: 1px solid #ddd;
  border-top: none;
  border-radius: 0 0 4px 4px;
  max-height: 260px;
  overflow-y: auto;
  box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

.tee-opt-list.open { display: block; }

.tee-copt {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  font-size: 15px;
  font-weight: 500;
  color: #000;
  -webkit-user-select: none;
  user-select: none;
}

.tee-copt:last-child { border-bottom: none; }
.tee-copt.chosen { color: #6A449B; font-weight: 700; }

.tee-copt-check {
  font-size: 16px;
  color: #6A449B;
  font-weight: 700;
  opacity: 0;
  flex-shrink: 0;
}

.tee-copt-price {
  display: block;
  font-size: 12px;
  color: #666;
  font-weight: 400;
  flex-shrink: 0;
}

.tee-copt.chosen .tee-copt-check { opacity: 1; }

.tee-upload-area {
  margin: 16px;
  border: 2px dashed #6A449B;
  border-radius: 8px;
  padding: 32px 20px;
  text-align: center;
  background: #f5f0ff;
  cursor: pointer;
}

.tee-upload-hint {
  display: block;
  font-size: 15px;
  font-weight: 600;
  color: #6A449B;
}

.tee-upload-name {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: #333;
  word-break: break-all;
}

} /* end desktop */

</style>
STYLE;
});

add_action('wp_footer', function() {
    if (!is_singular() || !function_exists('is_product')) return;
    if (!is_product()) return; ?>
<script>
jQuery(function($) {
    var mq = '(max-width: 767px)';
    var isMob = window.matchMedia(mq).matches;

    if (!$('form.variations_form').length
        && !$('.wc-pao-addon-wrap').length) {
        return;
    }

    var uP = new URLSearchParams(
        window.location.search
    );
    var kitQty = uP.get('quantity');
    if (kitQty) {
        $('input.qty').val(kitQty)
            .trigger('change');
        $('.quantity').css({
            opacity: '0.5',
            'pointer-events': 'none'
        });
    }

    /* ====== MOBILE: native form overlay ====== */
    if (isMob) {
        var $gi = $(
            '.woocommerce-product-gallery'
            + '__image img'
        );
        var imgSrc = $gi.attr('data-large_image')
            || $gi.attr('src') || '';
        var cBase =
            'https://chainmail-pi.vercel.app/';
        var cUrl = cBase + 'chain_3.svg';
        var $cLogo = $('.custom-logo').first();
        var lSrc = $cLogo.length
            ? $cLogo.attr('src') : '';

        var h = '<div id="tee-conf">';
        h += '<div id="tee-conf-topbar">';
        if (lSrc) {
            h += '<img src="' + lSrc + '"';
            h += ' id="tee-conf-site-logo"';
            h += ' alt="Chainmail">';
        } else {
            h += '<span id="tee-conf-logo-txt">';
            h += 'chainmail</span>';
        }
        h += '<div id="tee-conf-icons">';
        h += '<a href="/cart/"';
        h += ' aria-label="Cart">';
        h += '<svg width="22" height="22"';
        h += ' viewBox="0 0 24 24" fill="none"';
        h += ' stroke="currentColor"';
        h += ' stroke-width="2"';
        h += ' stroke-linecap="round">';
        h += '<circle cx="9" cy="21" r="1"/>';
        h += '<circle cx="20" cy="21" r="1"/>';
        h += '<path d="M1 1h4l2.68 13.39';
        h += 'A2 2 0 0 0 7.68 16h9.72';
        h += 'a2 2 0 0 0 1.97-1.61L23 6H6"/>';
        h += '</svg></a>';
        h += '<button id="tee-menu-btn"';
        h += ' aria-label="Menu">';
        h += '<svg width="22" height="22"';
        h += ' viewBox="0 0 24 24" fill="none"';
        h += ' stroke="currentColor"';
        h += ' stroke-width="2"';
        h += ' stroke-linecap="round">';
        h += '<line x1="3" y1="6"';
        h += ' x2="21" y2="6"/>';
        h += '<line x1="3" y1="12"';
        h += ' x2="21" y2="12"/>';
        h += '<line x1="3" y1="18"';
        h += ' x2="21" y2="18"/>';
        h += '</svg></button>';
        h += '</div></div>';
        h += '<div id="tee-conf-nav-panel">';
        h += '<a href="/">Home</a>';
        h += '<a href="/shop/">Shop</a>';
        h += '</div>';
        h += '<div id="tee-conf-chain">';
        h += '<img src="' + cUrl + '">';
        h += '</div>';
        h += '<div id="tee-conf-img">';
        if (imgSrc) {
            h += '<img id="tee-conf-photo"';
            h += ' src="' + imgSrc + '">';
        }
        h += '<div id="tee-conf-logo-overlay">';
        h += '<img id="tee-conf-logo-img" src="">';
        h += '</div></div>';
        h += '<div id="tee-conf-form"></div>';
        h += '<div id="tee-conf-nav">';
        h += '<button id="tee-conf-back">';
        h += '&lt; Exit</button>';
        h += '<button id="tee-conf-preview">';
        h += 'Preview</button>';
        h += '</div></div>';
        $('body').append(h);

        document.body.classList.add('cm-kit-mode');
        $('.single_add_to_cart_button')
            .text('Add to Kit ›');

        // Move native form into overlay
        var $tcf = $('#tee-conf-form');
        $('form.variations_form').appendTo($tcf);
        // Move any PEWC elements outside the form
        $('[class*="pewc"]')
            .not('#tee-conf *')
            .each(function() {
                $tcf.append($(this));
            });

        // Scroll lock
        var _sy = window.scrollY || 0;
        var _de = document.documentElement;
        _de.style.overflow = 'hidden';
        _de.style.height = '100%';
        document.body.style.position = 'fixed';
        document.body.style.top =
            '-' + _sy + 'px';
        document.body.style.width = '100%';
        document.body.style.overflow = 'hidden';

        // Lower competing z-indexes
        setTimeout(function() {
            $('body *').not(
                '#tee-conf, #tee-conf *'
            ).each(function() {
                var z = parseInt(
                    $(this).css('z-index'), 10
                );
                if (!isNaN(z) && z > 9000) {
                    $(this).css('z-index', '1');
                }
            });
        }, 300);

        // Update image on variation select
        $('body').on(
            'found_variation.wc-variation-form',
            'form.variations_form',
            function(ev, v) {
                if (v.image && v.image.src) {
                    $('#tee-conf-photo')
                        .attr('src', v.image.src);
                }
                $('.single_add_to_cart_button')
                    .text('Add to Kit ›');
            }
        );

        // Logo preview on file select
        $(document).on(
            'change',
            'input[type="file"]',
            function() {
                var f = this.files
                    && this.files[0];
                if (!f) return;
                var rd = new FileReader();
                rd.onload = function(e) {
                    $('#tee-conf-logo-img').attr(
                        'src', e.target.result
                    );
                };
                rd.readAsDataURL(f);
            }
        );

        // Logo position on radio change
        $(document).on(
            'change',
            'input[type="radio"]'
            + ':not(.variations *)',
            function() {
                var v = $(this).val()
                    .toLowerCase();
                var $ov = $(
                    '#tee-conf-logo-overlay'
                );
                if (v.indexOf('left') !== -1) {
                    $ov.css({
                        left: 'auto',
                        right: '33%',
                        transform: 'none'
                    });
                } else if (
                    v.indexOf('right') !== -1
                ) {
                    $ov.css({
                        left: '22%',
                        right: 'auto',
                        transform: 'none'
                    });
                } else {
                    $ov.css({
                        left: '50%',
                        right: 'auto',
                        transform:
                            'translateX(-50%)'
                    });
                }
            }
        );

        // Exit button
        $('#tee-conf-back').on('click', function() {
            var _t = Math.abs(parseInt(
                document.body.style.top, 10
            ));
            _de.style.overflow = '';
            _de.style.height = '';
            document.body.style.position = '';
            document.body.style.top = '';
            document.body.style.width = '';
            document.body.style.overflow = '';
            window.scrollTo(0, _t);
            window.location.href =
                cBase + '?back=goods';
        });

        // Preview button
        $('#tee-conf-preview').on(
            'click', function() {
                var src = $(
                    '#tee-conf-photo'
                ).attr('src');
                if (!src) return;
                var $pv = $('<div>').css({
                    position: 'fixed',
                    top: 0, left: 0,
                    right: 0, bottom: 0,
                    zIndex: 1000001,
                    background:
                        'rgba(0,0,0,0.92)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center'
                });
                $('<img>').attr('src', src)
                    .css({
                        maxWidth: '100%',
                        maxHeight: '90%',
                        objectFit: 'contain',
                        display: 'block'
                    }).appendTo($pv);
                $('<button>').text('Close')
                    .css({
                        position: 'absolute',
                        top: '16px',
                        right: '16px',
                        background: 'none',
                        border: '2px solid #fff',
                        color: '#fff',
                        fontSize: '15px',
                        fontWeight: '700',
                        padding: '6px 14px',
                        borderRadius: '4px',
                        cursor: 'pointer'
                    }).on('click', function(e) {
                        e.stopPropagation();
                        $pv.remove();
                    }).appendTo($pv);
                $pv.on('click', function() {
                    $pv.remove();
                });
                $('body').append($pv);
            }
        );

        // Hamburger
        $(document).on(
            'click', '#tee-menu-btn',
            function() {
                $('#tee-conf-nav-panel')
                    .toggleClass('open');
            }
        );

        // Redirect after add to cart
        $(document).on(
            'added_to_cart', function() {
                window.location.href =
                    cBase + '?resume=goods';
            }
        );

    /* ====== DESKTOP: tab widget ====== */
    } else {

        function attrLabel(nm) {
            var s = nm.replace(
                'attribute_pa_', ''
            );
            s = s.replace('attribute_', '');
            s = s.replace(/[-_]/g, ' ');
            s = s.replace(/\b\w/g, function(c) {
                return c.toUpperCase();
            });
            return s;
        }

        function tabLabel(lbl) {
            var l = lbl.toLowerCase();
            if (l.indexOf('position') !== -1
                || l.indexOf('left') !== -1
                || l.indexOf('center') !== -1) {
                return 'Position';
            }
            if (l.indexOf('decoration') !== -1
                || l.indexOf('print') !== -1) {
                return 'Print';
            }
            if (l.indexOf('logo') !== -1) {
                return 'Logo';
            }
            return lbl.split(' ')[0];
        }

        var steps = [];
        var cur = 0;

        function detectAddons() {
            var oldSnap = steps
                .map(function(s) {
                    return s.label;
                }).join('|');
            var oldVals = steps
                .map(function(s) {
                    return s.val;
                }).join('|');
            var prevSteps = steps;
            var next = [];

            function findPrev(label) {
                for (
                    var _p = 0;
                    _p < prevSteps.length;
                    _p++
                ) {
                    if (prevSteps[_p].label
                        === label) {
                        return prevSteps[_p];
                    }
                }
                return null;
            }

            // Variations
            $('.variations select').each(
                function() {
                    var $s = $(this);
                    var nm = $s.attr('name')
                        || '';
                    var lbl = attrLabel(nm);
                    var sl = 'Select ' + lbl;
                    var opts = [];
                    var pv = findPrev(sl);
                    $s.find('option').each(
                        function() {
                            var v = $(this).val();
                            if (v) opts.push({
                                v: v,
                                t: $(this).text()
                                    .trim()
                            });
                        }
                    );
                    if (opts.length) {
                        next.push({
                            kind: 'select',
                            ref: $s,
                            label: sl,
                            tab: tabLabel(lbl),
                            opts: opts,
                            val: $s.val()
                                || (pv ? pv.val
                                    : '')
                        });
                    }
                }
            );

            // Radio add-ons (desktop)
            var rSeen = {};
            $('input[type="radio"]')
                .not('.variations *')
                .each(function() {
                    var $r = $(this);
                    var rn = $r.attr('name')
                        || '';
                    if (!rn || rSeen[rn]) return;
                    rSeen[rn] = true;
                    var opts = [];
                    var curChk = '';
                    $('[name="' + rn + '"]')
                        .each(function() {
                            var $ri = $(this);
                            if ($ri.is(':checked')){
                                curChk = $ri.val();
                            }
                            var lt = $ri
                                .closest('label')
                                .text().trim();
                            if (!lt) lt = $ri.val();
                            opts.push({
                                v: $ri.val(),
                                t: lt,
                                ref: $ri
                            });
                        });
                    var $w = $r.closest([
                        '.pewc-group',
                        '.pewc-item',
                        '.wc-pao-addon-wrap'
                    ].join(','));
                    var nm2 = $w.find([
                        '.pewc-item-label',
                        '.pewc-group-title',
                        '.wc-pao-addon-name'
                    ].join(',')).first()
                        .text().trim();
                    if (!nm2) nm2 = 'Position';
                    var pvR = findPrev(nm2);
                    if (opts.length) {
                        next.push({
                            kind: 'radio',
                            label: nm2,
                            tab: tabLabel(nm2),
                            opts: opts,
                            val: curChk
                                || (pvR
                                    ? pvR.val
                                    : '')
                        });
                    }
                });

            steps = next;
            var newSnap = steps
                .map(function(s) {
                    return s.label;
                }).join('|');
            var newVals = steps
                .map(function(s) {
                    return s.val;
                }).join('|');
            var tc = (newSnap !== oldSnap);
            var vc = (newVals !== oldVals);
            var dropOpen = $(
                '.tee-opt-list.open'
            ).length;
            if (tc || (vc && !dropOpen)) {
                if (cur >= steps.length) {
                    cur = Math.max(
                        0, steps.length - 1
                    );
                }
                paint(cur);
            }
        }

        function updateLogoOverlay() {
            var $fi = $('input[type="file"]')
                .not('.variations *').first();
            var file = $fi.length
                && $fi[0].files
                && $fi[0].files[0];
            var $li = $('#tee-conf-logo-img');
            if (file) {
                var r = new FileReader();
                r.onload = function(e) {
                    $li.attr('src',
                        e.target.result);
                };
                r.readAsDataURL(file);
            } else {
                $li.attr('src', '');
            }
        }

        detectAddons();
        if (!steps.length) return;

        var _moTimer;
        var _mo = new MutationObserver(
            function() {
                clearTimeout(_moTimer);
                _moTimer = setTimeout(
                    detectAddons, 300
                );
            }
        );
        _mo.observe(document.body, {
            childList: true,
            subtree: true
        });

        var dh = '<div id="tee-desk">';
        dh += '<div id="tee-desk-tabs"></div>';
        dh += '<div id="tee-desk-opts"></div>';
        dh += '</div>';
        $('form.variations_form').before(dh);
        var tabsId = '#tee-desk-tabs';
        var optsId = '#tee-desk-opts';

        function paint(i) {
            var step = steps[i];

            var th = '';
            for (
                var t = 0;
                t < steps.length;
                t++
            ) {
                var cls = 'tee-ctab';
                if (t === i) cls += ' active';
                else if (steps[t].val) {
                    cls += ' done';
                }
                th += '<div class="' + cls + '"';
                th += ' data-t="' + t + '">';
                th += steps[t].tab + '</div>';
            }
            $(tabsId).html(th);

            var oh = '';
            if (step.kind === 'radio'
                || step.kind === 'select') {
                var selTxt = 'select an option';
                var selCls = 'tee-sel-val';
                for (
                    var x = 0;
                    x < step.opts.length;
                    x++
                ) {
                    if (step.opts[x].v
                        === step.val) {
                        selTxt = step.opts[x].t;
                        selCls =
                            'tee-sel-val has-val';
                        break;
                    }
                }
                var ico = step.val ? '✓' : '+';
                oh = '<div class="tee-sel-wrap">';
                oh += '<div class="tee-sel-bar">';
                oh += '<span class="' + selCls;
                oh += '">' + selTxt + '</span>';
                oh += '<span class="tee-sel-icon">';
                oh += ico + '</span></div>';
                oh += '<div class="tee-opt-list">';
                for (
                    var j = 0;
                    j < step.opts.length;
                    j++
                ) {
                    var o = step.opts[j];
                    var isSel =
                        (step.val === o.v);
                    var oCls = isSel
                        ? 'tee-copt chosen'
                        : 'tee-copt';
                    oh += '<div class="' + oCls;
                    oh += '" data-v="' + o.v;
                    oh += '" data-i="' + i + '">';
                    oh += '<span>' + o.t
                        + '</span>';
                    oh += '<span class='
                        + '"tee-copt-check">';
                    oh += '✓</span></div>';
                }
                oh += '</div></div>';
            }
            $(optsId).html(oh);
        }

        paint(0);

        $(document).on(
            'click', '.tee-ctab',
            function() {
                var t = parseInt(
                    $(this).data('t'), 10
                );
                cur = t;
                paint(cur);
            }
        );

        $(document).on(
            'click', '.tee-sel-bar',
            function() {
                var $list = $(this).siblings(
                    '.tee-opt-list'
                );
                $list.toggleClass('open');
                var isOpen = $list.hasClass(
                    'open'
                );
                var $icon = $(this).find(
                    '.tee-sel-icon'
                );
                if (isOpen) {
                    $icon.text('-');
                } else if (steps[cur].val) {
                    $icon.text('✓');
                } else {
                    $icon.text('+');
                }
            }
        );

        $(document).on(
            'click', '.tee-copt',
            function() {
                var val = $(this).data('v');
                var idx = $(this).data('i');
                steps[idx].val = val;
                var step = steps[idx];
                if (step.kind === 'select') {
                    step.ref.val(val)
                        .trigger('change');
                } else {
                    for (
                        var _o = 0;
                        _o < step.opts.length;
                        _o++
                    ) {
                        if (step.opts[_o].v
                            === val) {
                            step.opts[_o].ref
                                .prop('checked',
                                    true)
                                .trigger('change');
                            setTimeout(
                                detectAddons, 200
                            );
                            break;
                        }
                    }
                }
                paint(idx);
                updateLogoOverlay();
            }
        );

        $('body').on(
            'found_variation'
            + '.wc-variation-form',
            'form.variations_form',
            function(ev, v) {
                setTimeout(detectAddons, 150);
            }
        );

    } // end desktop
});
</script>
<?php });
