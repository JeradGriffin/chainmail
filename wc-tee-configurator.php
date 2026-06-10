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

.tee-copt-price {
  display: block;
  font-size: 12px;
  color: #666;
  font-weight: 400;
  flex-shrink: 0;
}

.tee-copt.chosen .tee-copt-check { opacity: 1; }

.tee-upload-area {
  margin: 0 20px 12px;
  border: 2px dashed #6A449B;
  border-radius: 8px;
  padding: 32px 20px;
  text-align: center;
  background: #f5f0ff;
  cursor: pointer;
}

.tee-upload-hint {
  display: block;
  font-size: 16px;
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

/* Bottom nav */
#tee-conf-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 20px;
  padding-bottom: calc(12px + env(safe-area-inset-bottom));
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

#tee-conf-next {
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  background: #6A449B;
  border: 2px solid #6A449B;
  padding: 9px 14px;
  cursor: pointer;
  white-space: nowrap;
  border-radius: 4px;
}

#tee-conf-next:disabled {
  background: transparent;
  color: #6A449B;
  cursor: not-allowed;
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

    // Sync Quantity from URL parameter (passed from React App)
    var urlParams = new URLSearchParams(window.location.search);
    var kitQty = urlParams.get('quantity');
    if (kitQty) {
        var $qtyInput = $('input.qty');
        if ($qtyInput.length) {
            $qtyInput.val(kitQty).trigger('change');
            // lock qty to kit size
            $('.quantity').css('opacity', '0.5').css('pointer-events', 'none');
        }
    }

    function attrLabel(nm) {
        var s = nm.replace('attribute_pa_', '');
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
        var oldSnapshot = steps
            .map(function(s) { return s.label; })
            .join('|');
        var oldValues = steps
            .map(function(s) { return s.val; })
            .join('|');
        var previousSteps = steps;
        var nextSteps = [];

        function findPrev(label) {
            for (var _p = 0; _p < previousSteps.length; _p++) {
                if (previousSteps[_p].label === label) {
                    return previousSteps[_p];
                }
            }
            return null;
        }

        // 1. Variations
        $('.variations select').each(function() { 
            var $s = $(this);
            var nm = $s.attr('name') || '';
            var lbl = attrLabel(nm);
            var stepLabel = 'Select ' + lbl;
            var opts = [];
            var prev = findPrev(stepLabel);
            $s.find('option').each(function() {
                var v = $(this).val();
                if (v) opts.push({ v: v, t: $(this).text().trim() });
            });
            if (opts.length) {
                nextSteps.push({
                    kind: 'select',
                    ref: $s,
                    label: stepLabel,
                    tab: tabLabel(lbl),
                    opts: opts,
                    val: $s.val() || (prev ? prev.val : '')
                });
            }
        });

        // 2. Add-Ons — mobile only (desktop uses native pewc UI)
        if (isMob) {
        // Radio groups = logo position, decoration, etc.
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
                var $wrap = $r.closest([
                    '.pewc-group',
                    '.pewc-item',
                    '.wc-pao-addon-wrap'
                ].join(','));
                var nm = $wrap.find([
                    '.pewc-item-label',
                    '.pewc-group-title',
                    '.wc-pao-addon-name'
                ].join(',')).first().text().trim();
                if (!nm) nm = 'Position';
                var prevR = findPrev(nm);
                if (opts.length) {
                    nextSteps.push({
                        kind: 'radio',
                        label: nm,
                        tab: tabLabel(nm),
                        opts: opts,
                        val: curChk
                            || (prevR ? prevR.val : '')
                    });
                }
            });

        // File input = logo upload step (last)
        var $fi = $('input[type="file"]')
            .not('.variations *')
            .first();
        if ($fi.length) {
            var prevF = findPrev('Upload Your Logo');
            var fStep = {
                kind: 'file',
                ref: $fi,
                label: 'Upload Your Logo',
                tab: 'Logo',
                val: prevF ? prevF.val : '',
                fileObj: prevF ? prevF.fileObj : null
            };
            $fi.off('change.conf')
                .on('change.conf', function() {
                    var file = this.files
                        && this.files[0];
                    if (file) {
                        fStep.val = file.name;
                        fStep.fileObj = file;
                        paint(cur);
                        updateLogoOverlay();
                    }
                });
            nextSteps.push(fStep);
        }
        } // end if (isMob) addons block

        steps = nextSteps;
        var newSnapshot = steps
            .map(function(s) { return s.label; })
            .join('|');
        var newValues = steps
            .map(function(s) { return s.val; })
            .join('|');

        var tabsChanged = (newSnapshot !== oldSnapshot);
        var valsChanged = (newValues !== oldValues);
        var dropOpen = $('.tee-opt-list.open').length;
        if (tabsChanged || (valsChanged && !dropOpen)) {
            if (cur >= steps.length) {
                cur = Math.max(0, steps.length - 1);
            }
            paint(cur);
        }
    }

    function updateLogoOverlay() {
        if (!isMob) return;
        var logoStep = null;
        var posStep = null;
        for (var _u = 0; _u < steps.length; _u++) {
            if (steps[_u].tab === 'Logo') {
                logoStep = steps[_u];
            }
            if (steps[_u].tab === 'Position') {
                posStep = steps[_u];
            }
        }
        var $logoImg = $('#tee-conf-logo-img');
        
        if (logoStep && logoStep.fileObj) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $logoImg.attr('src', e.target.result);
            };
            reader.readAsDataURL(logoStep.fileObj);
        } else {
            $logoImg.attr('src', '');
        }

        var $ov = $('#tee-conf-logo-overlay');
        if (posStep && posStep.val) {
            var rv = (posStep.val || '').toLowerCase();
            if (rv.indexOf('left') !== -1) {
                $ov.css({
                    left: 'auto',
                    right: '33%',
                    transform: 'none'
                });
            } else if (rv.indexOf('right') !== -1) {
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
    }

    detectAddons();

    if (!steps.length) return;

    var _moTimer;
    var _mo = new MutationObserver(function() {
        clearTimeout(_moTimer);
        _moTimer = setTimeout(detectAddons, 300);
    });
    _mo.observe(document.body, {
        childList: true,
        subtree: true
    });

    var tabsId, optsId;

    if (isMob) {
        var $gi = $(
            '.woocommerce-product-gallery__image img'
        );
        var imgSrc = $gi.attr('data-large_image')
            || $gi.attr('src') || '';
        var cBase = 'https://chainmail-pi.vercel.app/';
        var cUrl = cBase + 'chain_3.svg';

        var h = '<div id="tee-conf">';
        h += '<div id="tee-conf-chain">';
        h += '<img src="' + cUrl + '">';
        h += '</div>';
        h += '<div id="tee-conf-img">';
        if (imgSrc) {
            h += '<img id="tee-conf-photo" src="';
            h += imgSrc + '">';
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
        h += '<button id="tee-conf-back">';
        h += '&lt; Exit</button>';
        h += '<button id="tee-conf-preview">';
        h += 'Preview</button>';
        h += '<button id="tee-conf-next" disabled>';
        h += 'Continue &gt;</button>';
        h += '</div></div>';
        $('body').append(h);

        var _sy = window.scrollY || 0;
        var _de = document.documentElement;
        _de.style.overflow = 'hidden';
        _de.style.height = '100%';
        document.body.style.position = 'fixed';
        document.body.style.top = '-' + _sy + 'px';
        document.body.style.width = '100%';
        document.body.style.height = '100%';
        document.body.style.overflow = 'hidden';

        tabsId = '#tee-conf-tabs';
        optsId = '#tee-conf-opts';

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
        }

        var th = '';
        for (var t = 0; t < steps.length; t++) {
            var cls = 'tee-ctab';
            if (t === i) cls += ' active';
            else if (steps[t].val) cls += ' done';
            th += '<div class="' + cls + '"';
            th += ' data-t="' + t + '">';
            th += steps[t].tab;
            th += '</div>';
        }
        $(tabsId).html(th);

        var oh = '';
        if (step.kind === 'file') {
            oh = '<div class="tee-upload-area"';
            oh += ' id="tee-upbtn">';
            if (step.val) {
                oh += '<span class="tee-upload-name">';
                oh += step.val + '</span>';
            } else {
                oh += '<span class="tee-upload-hint">';
                oh += 'Tap to upload your logo';
                oh += '</span>';
            }
            oh += '</div>';
        } else {
            var selTxt = 'tap to select';
            var selCls = 'tee-sel-val';
            for (var x = 0; x < step.opts.length; x++) {
                if (step.opts[x].v === step.val) {
                    selTxt = step.opts[x].t;
                    selCls = 'tee-sel-val has-val';
                    break;
                }
            }
            var listCls = 'tee-opt-list';
            var ico = step.val ? '✓' : '+';
            oh = '<div class="tee-sel-wrap">';
            oh += '<div class="tee-sel-bar">';
            oh += '<span class="' + selCls + '">';
            oh += selTxt + '</span>';
            oh += '<span class="tee-sel-icon">';
            oh += ico + '</span></div>';
            oh += '<div class="' + listCls + '">';
            for (var j = 0; j < step.opts.length; j++) {
                var o = step.opts[j];
                var isSel = (step.val === o.v);
                var oCls = isSel
                    ? 'tee-copt chosen'
                    : 'tee-copt';
                oh += '<div class="' + oCls + '"';
                oh += ' data-v="' + o.v + '"';
                oh += ' data-i="' + i + '">';
                oh += '<div>';
                oh += '<span>' + o.t + '</span>';
                if (o.price) {
                    oh += '<span class="tee-copt-price">';
                    oh += o.price + '</span>';
                }
                oh += '</div>';
                oh += '<span class="tee-copt-check">';
                oh += '✓</span></div>';
            }
            oh += '</div>';
            oh += '</div>';
        }
        $(optsId).html(oh);

        if (isMob) {
            var isLast = (i === steps.length - 1);
            var isFile = (step.kind === 'file');
            var allDone = true;
            for (var k = 0; k < steps.length; k++) {
                var sk = steps[k];
                if (!sk.val && sk.kind !== 'file') {
                    allDone = false;
                    break;
                }
            }
            var canNext = isLast
                ? allDone
                : (step.kind === 'file' || !!step.val);
            $('#tee-conf-next')
                .prop('disabled', !canNext);
            var nxt = isLast
                ? 'Add to Kit &gt;'
                : 'Continue &gt;';
            $('#tee-conf-next').html(nxt);
            var bk = (i === 0)
                ? '&lt; Exit'
                : '&lt; Back';
            $('#tee-conf-back').html(bk);
        }
    }

    paint(0);

    $(document).on('click', '#tee-upbtn', function() {
        var step = steps[cur];
        if (!step || step.kind !== 'file') return;
        
        var $realInput = step.wrap
            ? step.wrap.find('input[type="file"]')
            : step.ref;
        if ($realInput && $realInput.length) {
            $realInput.trigger('click');
        }
    });

    $(document).on('click', '.tee-ctab', function() {
        var t = parseInt($(this).data('t'), 10);
        var prev = steps[t - 1];
        var ok = !isMob
            || t <= cur
            || (prev && prev.val);
        if (ok) {
            cur = t;
            paint(cur);
        }
    });

    $(document).on('click', '.tee-sel-bar', function() {
        var $list = $(this).siblings('.tee-opt-list');
        $list.toggleClass('open');
        var isOpen = $list.hasClass('open');
        var $icon = $(this).find('.tee-sel-icon');
        if (isOpen) {
            $icon.text('-');
        } else if (steps[cur].val) {
            $icon.text('✓');
        } else {
            $icon.text('+');
        }
    });

    $(document).on('click', '.tee-copt', function() {
        var val = $(this).data('v');
        var idx = $(this).data('i');
        steps[idx].val = val;
        var step = steps[idx];
        if (step.kind === 'select') {
            step.ref.val(val).trigger('change');
        } else {
            var foundRadio = null;
            for (var _o = 0; _o < step.opts.length; _o++) {
                if (step.opts[_o].v === val) {
                    foundRadio = step.opts[_o];
                    break;
                }
            }
            if (foundRadio && foundRadio.ref
                && foundRadio.ref.length) {
                foundRadio.ref
                    .prop('checked', true)
                    .trigger('change');
                setTimeout(detectAddons, 200);
            }
        }
        paint(idx);
        updateLogoOverlay();
    });

    if (isMob) {
        $('#tee-conf-next').on('click', function() {
            var isLast = (cur === steps.length - 1);
            if (isLast) {
                var seen = {};
                $('[class*="pewc"] input[type="radio"]')
                    .each(function() {
                        var $r = $(this);
                        var rn = $r.attr('name') || '';
                        if (!rn || seen[rn]) return;
                        seen[rn] = true;
                        var chk = $('[name="' + rn
                            + '"]:checked');
                        if (!chk.length) {
                            $r.prop('checked', true)
                                .trigger('change');
                        }
                    });
                var $btn = $(
                    '.single_add_to_cart_button'
                );
                $btn.removeAttr('disabled');
                $btn.trigger('click');
            } else {
                cur++;
                paint(cur);
            }
        });

        $('#tee-conf-back').on('click', function() {
            if (cur === 0) {
                var _t = Math.abs(parseInt(
                    document.body.style.top, 10
                ));
                var _de2 = document.documentElement;
                _de2.style.overflow = '';
                _de2.style.height = '';
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                document.body.style.height = '';
                document.body.style.overflow = '';
                window.scrollTo(0, _t);
                window.location.href =
                    cBase + '?back=goods';
            } else {
                cur--;
                paint(cur);
            }
        });

        $('#tee-conf-preview').on('click', function() {
            var src = $('#tee-conf-photo').attr('src');
            if (!src) return;
            var $pv = $('<div>').attr('id', 'tee-pv');
            $pv.css({
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
            }).on('click', function(ev) {
                ev.stopPropagation();
                $pv.remove();
            }).appendTo($pv);
            $pv.on('click', function() {
                $pv.remove();
            });
            $('body').append($pv);
        });

        $('body').on(
            'found_variation.wc-variation-form',
            'form.variations_form',
            function(e, v) {
                if (v.image && v.image.src) {
                    $('#tee-conf-photo')
                        .attr('src', v.image.src);
                }
                setTimeout(detectAddons, 150); // Slightly increased timeout
            }
        );

        $(document).on('added_to_cart', function() {
            window.location.href =
                cBase + '?resume=goods';
        });
    }
});
</script>
<?php });
