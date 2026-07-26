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
  overflow: visible;
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

#tee-conf-logo-img {
  max-width: 70px;
  max-height: 70px;
  object-fit: contain;
}

#tee-conf-counter {
  position: absolute;
  top: 14px;
  right: 16px;
  background: rgba(0,0,0,0.55);
  color: #fff;
  font-size: 12px;
  font-weight: 700;
  padding: 4px 12px;
  border-radius: 20px;
  letter-spacing: 0.04em;
}

/* Tabs overlaid at bottom of image */
#tee-conf-tabs {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  background: rgba(20,10,30,0.6);
  padding: 10px 8px;
  gap: 5px;
}

.tee-ctab {
  flex: 1;
  min-width: 0;
  padding: 7px 4px;
  font-size: 10px;
  font-weight: 700;
  color: rgba(255,255,255,0.45);
  text-align: center;
  text-transform: uppercase;
  letter-spacing: 0.06em;
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
  color: #fff;
  border-bottom-color: #fff;
}

.tee-ctab.done {
  color: rgba(255,255,255,0.85);
}

/* Step label below image */
#tee-conf-step {
  flex-shrink: 0;
  background: #fff;
  padding: 18px 20px 10px;
  font-size: 22px;
  font-weight: 700;
  color: #000;
  line-height: 1.2;
}

/* Options area */
#tee-conf-opts {
  background: #fff;
  flex-shrink: 0;
  overflow: visible;
  min-height: 80px;
}

.tee-sel-wrap {
  position: relative;
  margin: 0 20px 12px;
}

.tee-sel-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border: 1px solid #ddd;
  border-radius: 4px;
  overflow: hidden;
  cursor: pointer;
  -webkit-user-select: none;
  user-select: none;
  height: 50px;
  background: #fff;
}

.tee-sel-val {
  flex: 1;
  padding: 0 14px;
  font-size: 15px;
  font-weight: 400;
  color: #bbb;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.tee-sel-val.has-val {
  color: #000;
  font-weight: 600;
}

.tee-sel-icon {
  width: 50px;
  height: 50px;
  background: #6A449B;
  color: #fff;
  font-size: 26px;
  font-weight: 300;
  line-height: 50px;
  text-align: center;
  flex-shrink: 0;
}

.tee-opt-list {
  display: none;
  position: absolute;
  bottom: 100%;
  top: auto;
  left: 0;
  right: 0;
  z-index: 500;
  background: #fff;
  border: 1px solid #ddd;
  border-bottom: none;
  border-radius: 4px 4px 0 0;
  max-height: 220px;
  overflow-y: auto;
  box-shadow: 0 -6px 16px rgba(0,0,0,0.18);
}

.tee-opt-list.open {
  display: block;
}

.tee-copt {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 13px 16px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  font-size: 16px;
  font-weight: 500;
  color: #000;
  -webkit-user-select: none;
  user-select: none;
}

.tee-copt:last-child { border-bottom: none; }

.tee-copt.chosen {
  color: #6A449B;
  font-weight: 700;
}

.tee-copt-check {
  font-size: 16px;
  color: #6A449B;
  font-weight: 700;
  opacity: 0;
  flex-shrink: 0;
}

.tee-copt.chosen .tee-copt-check { opacity: 1; }

/* Bottom nav */
#tee-conf-nav {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 12px 20px;
  padding-bottom: calc(
    12px + env(safe-area-inset-bottom)
  );
  padding-right: 120px;
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

/* Hide native WC form behind overlay */
body.cm-kit-mode table.variations,
body.cm-kit-mode .woocommerce-variation-add-to-cart,
body.cm-kit-mode .woocommerce-variation-description,
body.cm-kit-mode .quantity {
  display: none !important;
}

/* Logo upload button */
.tee-upload-wrap {
  padding: 0 20px 16px;
}

.tee-upload-btn {
  display: block;
  width: 100%;
  padding: 20px;
  border: 2px dashed #6A449B;
  border-radius: 8px;
  background: #f5f0ff;
  color: #6A449B;
  font-size: 16px;
  font-weight: 700;
  font-family: 'Inter', -apple-system,
    sans-serif;
  text-align: center;
  cursor: pointer;
  box-sizing: border-box;
  -webkit-tap-highlight-color:
    transparent;
}

.tee-upload-btn.done {
  background: #f0fff0;
  border-color: #2a7d2a;
  color: #1a5a1a;
}

.tee-upload-hint {
  font-size: 12px;
  color: #888;
  text-align: center;
  margin-top: 8px;
}

/* Hide 3rd-party accessibility widgets
   that overlay the configurator UI */
body.cm-kit-mode #ea11y-root,
body.cm-kit-mode .ea11y-widget,
body.cm-kit-mode [id*="userway"],
body.cm-kit-mode [class*="userway"],
body.cm-kit-mode [id*="accessib"],
body.cm-kit-mode [class*="accessib"],
body.cm-kit-mode [id*="audioeye"],
body.cm-kit-mode [class*="audioeye"] {
  display: none !important;
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

.tee-copt.chosen .tee-copt-check { opacity: 1; }

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
    var isTee = window.location.href
        .indexOf('/product/tee') !== -1;

    if (!$('form.variations_form').length
        && !$('.wc-pao-addon-wrap').length) {
        return;
    }

    // Logo upload forces form submit (not
    // AJAX) — detect page reload & redirect
    if (window.location.href
        .indexOf('/product/tee') !== -1) {
        var _qs = new URLSearchParams(
            window.location.search
        );
        if (_qs.get('added-to-cart')) {
            var _dr = sessionStorage
                .getItem('cm_tee_draft');
            if (_dr) {
                try {
                    localStorage.setItem(
                        'cm_good_tee', _dr
                    );
                    sessionStorage
                        .removeItem(
                        'cm_tee_draft'
                    );
                } catch(_le) {}
            }
            window.location.href =
                'https://chainmail-pi'
                + '.vercel.app'
                + '/?resume=goods';
            return;
        }
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
        if (l.indexOf('color') !== -1) {
            return 'Color';
        }
        if (l.indexOf('weight') !== -1) {
            return 'Weight';
        }
        if (l.indexOf('sleeve') !== -1) {
            return 'Sleeve';
        }
        if (l.indexOf('decoration') !== -1
            || l.indexOf('print') !== -1) {
            return 'Decoration';
        }
        if (l.indexOf('position') !== -1) {
            return 'Position';
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

        // 1. Variation selects
        $('.variations select').each(
            function() {
                var $s = $(this);
                var nm = $s.attr('name') || '';
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
                            || (pv ? pv.val : '')
                    });
                }
            }
        );

        // 2. PEWC radio groups
        var rSeen = {};
        $('input[type="radio"]')
            .not('.variations *')
            .each(function() {
                var $r = $(this);
                var rn = $r.attr('name') || '';
                if (!rn || rSeen[rn]) return;
                rSeen[rn] = true;
                var opts = [];
                var curChk = '';
                $('[name="' + rn + '"]')
                    .each(function() {
                        var $ri = $(this);
                        if ($ri.is(':checked')) {
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
                            || (pvR ? pvR.val : '')
                    });
                }
            });

        // 3. First PEWC file input only
        var _addedFile = false;
        $('input[type="file"]')
            .not('.variations *')
            .each(function() {
                if (_addedFile) return;
                _addedFile = true;
                var $fi = $(this);
                var $w = $fi.closest([
                    '.pewc-group',
                    '.pewc-item',
                    '.wc-pao-addon-wrap'
                ].join(','));
                var nm3 = $w.find([
                    '.pewc-item-label',
                    '.pewc-group-title',
                    '.wc-pao-addon-name'
                ].join(',')).first()
                    .text().trim();
                if (!nm3) nm3 = 'Upload Logo';
                var pvF = findPrev(nm3);
                next.push({
                    kind: 'file',
                    ref: $fi,
                    label: nm3,
                    tab: tabLabel(nm3),
                    val: (pvF && pvF.val) || '',
                    fileObj: pvF
                        ? pvF.fileObj : null
                });
                $fi.off('change.cf')
                    .on('change.cf', function() {
                        var _f = this.files[0]
                            || null;
                        for (
                            var _fs = 0;
                            _fs < steps.length;
                            _fs++
                        ) {
                            if (steps[_fs].kind
                                === 'file') {
                                steps[_fs].fileObj
                                    = _f;
                                steps[_fs].val
                                    = _f
                                    ? 'done' : '';
                                break;
                            }
                        }
                        // Keep file in input
                        // so PEWC validation
                        // passes (Dropzone
                        // clears input after
                        // processing)
                        if (_f) {
                            try {
                                var _dt =
                                    new DataTransfer();
                                _dt.items.add(_f);
                                $(
                                    'input[type'
                                    + '="file"]'
                                ).not('.variations *')
                                .each(function() {
                                    this.files =
                                        _dt.files;
                                });
                            } catch(_e) {}
                        }
                        updateLogoOverlay();
                        paint(cur);
                    });
            });

        steps = next;
        // Re-apply saved file whenever steps
        // are rebuilt (Dropzone may have
        // cleared or replaced the input)
        (function() {
            var _rf = null;
            for (
                var _rs = 0;
                _rs < steps.length;
                _rs++
            ) {
                if (steps[_rs].kind === 'file'
                    && steps[_rs].fileObj) {
                    _rf = steps[_rs].fileObj;
                    break;
                }
            }
            if (!_rf) return;
            try {
                var _rdt = new DataTransfer();
                _rdt.items.add(_rf);
                $(
                    'input[type="file"]'
                ).not('.variations *')
                .each(function() {
                    this.files = _rdt.files;
                });
            } catch(_re) {}
        })();
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
            if (isMob || !isMob) paint(cur);
        }
    }

    function updateLogoOverlay() {
        var fSt = null;
        for (var _f = 0; _f < steps.length; _f++) {
            if (steps[_f].kind === 'file') {
                fSt = steps[_f]; break;
            }
        }
        var file = fSt && fSt.fileObj;
        var $li = $('#tee-conf-logo-img');
        if (file) {
            var rd = new FileReader();
            rd.onload = function(e) {
                $li.attr('src', e.target.result);
            };
            rd.readAsDataURL(file);
        } else {
            $li.attr('src', '');
        }
        var posVal = '';
        $('input[type="radio"]:checked')
            .not('.variations *')
            .each(function() {
                var v = $(this).val()
                    .toLowerCase();
                if (v.indexOf('left') !== -1
                    || v.indexOf('center') !== -1
                    || v.indexOf('right') !== -1) {
                    posVal = v;
                }
            });
        var $ov = $('#tee-conf-logo-overlay');
        if (posVal.indexOf('left') !== -1) {
            $ov.css({
                left: 'auto',
                right: '33%',
                transform: 'none'
            });
        } else if (
            posVal.indexOf('right') !== -1
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
                transform: 'translateX(-50%)'
            });
        }
    }

    detectAddons();
    if (!steps.length) return;

    var _moTimer;
    var _ownChange = false;
    var _mo = new MutationObserver(
        function() {
            if (_ownChange) return;
            if ($('.tee-opt-list.open').length) {
                return;
            }
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

    var tabsId, optsId;

    /* ====== MOBILE OVERLAY ====== */
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
        h += ' type="button"';
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
        h += '</div>';
        h += '<div id="tee-conf-counter"></div>';
        h += '<div id="tee-conf-tabs"></div>';
        h += '</div>';
        h += '<div id="tee-conf-step"></div>';
        h += '<div id="tee-conf-opts"></div>';
        h += '<div id="tee-conf-nav">';
        h += '<button id="tee-conf-back"';
        h += ' type="button">';
        h += '&lt; Exit</button>';
        h += '<button id="tee-conf-preview"';
        h += ' type="button">';
        h += 'Preview</button>';
        h += '</div></div>';
        $('form.cart').append(h);

        document.body.classList.add('cm-kit-mode');
        $('.single_add_to_cart_button')
            .text('Add to Kit ›');

        var _sy = window.scrollY || 0;
        var _de = document.documentElement;
        _de.style.overflow = 'hidden';
        _de.style.height = '100%';
        document.body.style.position = 'fixed';
        document.body.style.top =
            '-' + _sy + 'px';
        document.body.style.width = '100%';
        document.body.style.overflow = 'hidden';

        function _supZ() {
            $('body *').not(
                '#tee-conf, #tee-conf *'
            ).each(function() {
                var z = parseInt(
                    $(this).css('z-index'),
                    10
                );
                if (!isNaN(z) && z > 9000) {
                    $(this).css(
                        'z-index', '1'
                    );
                }
            });
        }
        setTimeout(_supZ, 300);
        setTimeout(_supZ, 1000);
        setTimeout(_supZ, 3000);

        tabsId = '#tee-conf-tabs';
        optsId = '#tee-conf-opts';

    /* ====== DESKTOP WIDGET ====== */
    } else {
        var dh = '<div id="tee-desk">';
        dh += '<div id="tee-desk-tabs"></div>';
        dh += '<div id="tee-desk-opts"></div>';
        dh += '</div>';
        $('form.variations_form').before(dh);
        tabsId = '#tee-desk-tabs';
        optsId = '#tee-desk-opts';
    }

    function paint(i) {
        var step = steps[i];
        var total = steps.length;

        if (isMob) {
            $('#tee-conf-step').text(step.label);
            $('#tee-conf-counter').text(
                (i + 1) + ' / ' + total
            );
            var bk = (i === 0)
                ? '&lt; Exit'
                : '&lt; Back';
            $('#tee-conf-back').html(bk);
        }

        var th = '';
        for (var t = 0; t < steps.length; t++) {
            var cls = 'tee-ctab';
            if (t === i) cls += ' active';
            else if (steps[t].val) cls += ' done';
            th += '<div class="' + cls + '"';
            th += ' data-t="' + t + '">';
            th += steps[t].tab + '</div>';
        }

        _ownChange = true;
        $(tabsId).html(th);
        setTimeout(function() {
            _ownChange = false;
        }, 0);

        var oh = '';
        if (step.kind === 'file') {
            if (isMob) {
                var isDone = step.val === 'done';
                var btnC = isDone
                    ? 'tee-upload-btn done'
                    : 'tee-upload-btn';
                var btnT = isDone
                    ? '✓ Logo uploaded'
                    : 'Tap to upload logo';
                var hintT = isDone
                    ? 'Tap to replace'
                    : 'PNG, JPG or SVG';
                oh = '<div class='
                    + '"tee-upload-wrap">';
                oh += '<button class="'
                    + btnC + '"';
                oh += ' type="button"';
                oh += ' id="tee-logo-btn">';
                oh += btnT + '</button>';
                oh += '<div class='
                    + '"tee-upload-hint">';
                oh += hintT + '</div>';
                oh += '</div>';
            }
        } else {
            var selTxt = 'tap to select';
            var selCls = 'tee-sel-val';
            for (
                var x = 0;
                x < step.opts.length;
                x++
            ) {
                if (step.opts[x].v === step.val) {
                    selTxt = step.opts[x].t;
                    selCls = 'tee-sel-val has-val';
                    break;
                }
            }
            var ico = step.val ? '✓' : '+';
            oh = '<div class="tee-sel-wrap">';
            oh += '<div class="tee-sel-bar">';
            oh += '<span class="' + selCls + '">';
            oh += selTxt + '</span>';
            oh += '<span class="tee-sel-icon">';
            oh += ico + '</span></div>';
            oh += '<div class="tee-opt-list">';
            for (
                var j = 0;
                j < step.opts.length;
                j++
            ) {
                var o = step.opts[j];
                var isSel = (step.val === o.v);
                var oCls = isSel
                    ? 'tee-copt chosen'
                    : 'tee-copt';
                oh += '<div class="' + oCls + '"';
                oh += ' data-v="' + o.v + '"';
                oh += ' data-i="' + i + '">';
                oh += '<span>' + o.t + '</span>';
                oh += '<span class='
                    + '"tee-copt-check">';
                oh += '✓</span></div>';
            }
            oh += '</div></div>';
        }

        _ownChange = true;
        $(optsId).html(oh);
        setTimeout(function() {
            _ownChange = false;
        }, 0);

    }

    paint(0);

    // Tab click
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

    // Open/close dropdown
    $(document).on(
        'click', '.tee-sel-bar',
        function() {
            var $list = $(this).siblings(
                '.tee-opt-list'
            );
            $list.toggleClass('open');
            var isOpen = $list.hasClass('open');
            var $ico = $(this).find(
                '.tee-sel-icon'
            );
            if (isOpen) {
                $ico.text('-');
            } else if (steps[cur] && steps[cur].val) {
                $ico.text('✓');
            } else {
                $ico.text('+');
            }
        }
    );

    // Select option
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
                    if (step.opts[_o].v === val) {
                        step.opts[_o].ref
                            .prop('checked', true)
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

    if (isMob) {
        // Back button
        $('#tee-conf-back').on(
            'click', function() {
                if (cur === 0) {
                    var _t = Math.abs(parseInt(
                        document.body.style.top,
                        10
                    ));
                    _de.style.overflow = '';
                    _de.style.height = '';
                    document.body.style.position
                        = '';
                    document.body.style.top = '';
                    document.body.style.width
                        = '';
                    document.body.style.overflow
                        = '';
                    window.scrollTo(0, _t);
                    window.location.href =
                        cBase + '?back=goods';
                } else {
                    cur--;
                    paint(cur);
                }
            }
        );

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
                    background: 'rgba(0,0,0,0.92)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center'
                });
                $('<img>').attr('src', src).css({
                    maxWidth: '100%',
                    maxHeight: '90%',
                    objectFit: 'contain',
                    display: 'block'
                }).appendTo($pv);
                $('<button>').text('Close').css({
                    position: 'absolute',
                    top: '16px', right: '16px',
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

        // Image update on variation
        $('body').on(
            'found_variation'
            + '.wc-variation-form',
            'form.variations_form',
            function(ev, v) {
                if (v.image && v.image.src) {
                    $('#tee-conf-photo')
                        .attr('src', v.image.src);
                }
                $('.single_add_to_cart_button')
                    .text('Add to Kit ›');
                setTimeout(detectAddons, 150);
            }
        );

        // Logo upload button
        $(document).on(
            'click', '#tee-logo-btn',
            function() {
                var fSt = null;
                for (
                    var _fs = 0;
                    _fs < steps.length;
                    _fs++
                ) {
                    if (steps[_fs].kind
                        === 'file') {
                        fSt = steps[_fs];
                        break;
                    }
                }
                if (!fSt) return;
                var _t2 = Math.abs(
                    parseInt(
                        document.body
                            .style.top,
                        10
                    )
                );
                _de.style.overflow = '';
                _de.style.height = '';
                document.body.style
                    .position = '';
                document.body.style.top
                    = '';
                document.body.style.width
                    = '';
                document.body.style
                    .overflow = '';
                var _dzIn = $('body')
                    .children(
                        'input[type="file"]'
                    ).get(0) || null;
                (_dzIn || fSt.ref[0])
                    .click();
                function dzRelock() {
                    document.body.style
                        .position = 'fixed';
                    document.body.style
                        .top = '-' + _t2
                        + 'px';
                    document.body.style
                        .width = '100%';
                    document.body.style
                        .overflow = 'hidden';
                    _de.style.overflow
                        = 'hidden';
                    _de.style.height
                        = '100%';
                }
                var $dzLis = _dzIn
                    ? $(_dzIn) : fSt.ref;
                $dzLis
                    .off('change.cmu')
                    .one('change.cmu',
                    function() {
                        var _fc =
                            (this.files &&
                            this.files[0])
                            || null;
                        if (_fc) {
                            for (
                                var _di = 0;
                                _di <
                                steps.length;
                                _di++
                            ) {
                                if (steps[_di]
                                    .kind !==
                                    'file') {
                                    continue;
                                }
                                steps[_di]
                                    .fileObj
                                    = _fc;
                                steps[_di]
                                    .val =
                                    'done';
                                break;
                            }
                            updateLogoOverlay();
                            paint(cur);
                        }
                        setTimeout(
                            dzRelock, 50
                        );
                    });
                $(window)
                    .off('focus.cmu')
                    .one('focus.cmu',
                    function() {
                        setTimeout(
                            dzRelock, 300
                        );
                    }
                );
            }
        );

        // Re-apply file BEFORE WC click
        // handlers fire (mousedown/touchstart
        // precede click on document.body)
        function _reapplyFile() {
            var fSt = null;
            for (
                var _ri = 0;
                _ri < steps.length;
                _ri++
            ) {
                if (steps[_ri].kind
                    === 'file') {
                    fSt = steps[_ri];
                    break;
                }
            }
            if (!fSt || !fSt.fileObj) {
                return;
            }
            try {
                var _rdt = new DataTransfer();
                _rdt.items.add(fSt.fileObj);
                $('input[type="file"]')
                    .not('.variations *')
                    .each(function() {
                        this.files =
                            _rdt.files;
                    });
            } catch(_re) {}
        }
        $(document).on(
            'mousedown touchstart',
            '.single_add_to_cart_button',
            _reapplyFile
        );

        // Capture config to sessionStorage
        $(document).on(
            'click',
            '.single_add_to_cart_button',
            function() {
                _reapplyFile();
                if (!isTee) return;
                var _pts = [];
                for (
                    var _ci = 0;
                    _ci < steps.length;
                    _ci++
                ) {
                    var _cs = steps[_ci];
                    if (_cs.kind === 'file') {
                        if (_cs.val === 'done') {
                            _pts.push('Logo');
                        }
                        continue;
                    }
                    if (!_cs.val) continue;
                    for (
                        var _co = 0;
                        _co < _cs.opts.length;
                        _co++
                    ) {
                        var _cop =
                            _cs.opts[_co];
                        if (_cop.v ===
                            _cs.val) {
                            _pts.push(
                                _cop.t
                            );
                            break;
                        }
                    }
                }
                var _ph = '';
                var _pEl = document
                    .getElementById(
                    'tee-conf-photo'
                );
                if (_pEl) {
                    _ph = _pEl.src || '';
                }
                if (!_ph) {
                    var _gi = $(
                        '.woocommerce-product'
                        + '-gallery__image img'
                    );
                    _ph = _gi.attr(
                        'data-large_image'
                    ) || _gi.attr('src')
                    || '';
                }
                var _logo = $(
                    '#tee-conf-logo-img'
                ).attr('src') || '';
                if (_logo.length > 500000) {
                    _logo = '';
                }
                try {
                    sessionStorage.setItem(
                        'cm_tee_draft',
                        JSON.stringify({
                            image: _ph,
                            detail: _pts
                                .join(' | '),
                            logoDataUrl: _logo
                        })
                    );
                } catch(_se) {}
            }
        );

        // Redirect after add to cart (Tee only)
        $(document).on(
            'added_to_cart', function() {
                if (!isTee) return;
                var _d = sessionStorage
                    .getItem('cm_tee_draft');
                if (_d) {
                    try {
                        localStorage.setItem(
                            'cm_good_tee', _d
                        );
                        sessionStorage
                            .removeItem(
                            'cm_tee_draft'
                        );
                    } catch(_le) {}
                }
                window.location.href =
                    cBase + '?resume=goods';
            }
        );
    }
});
</script>
<?php });
