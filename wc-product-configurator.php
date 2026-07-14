<?php
// Configurator — inline tab widget — all WC product pages.
// Variation selects + PEWC radio groups only.
// PEWC file upload (logo) stays native — Dropzone works
// in-page on both desktop and mobile.
// Keep wc-tee-product-styles.php active too.

add_action('wp_head', function() {
    if (!is_singular() || !function_exists('is_product')) return;
    if (!is_product()) return;
    echo <<<'STYLE'
<style>

/* ---- Mobile ---- */
@media (max-width: 767px) {

.variations {
  display: none !important;
}

#tee-desk {
  margin: 0 0 16px;
  border: 1px solid #eee;
  border-radius: 4px;
  font-family: 'Inter', -apple-system,
    sans-serif;
  overflow: hidden;
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
  padding: 12px 4px;
  font-size: 11px;
  font-weight: 600;
  color: #bbb;
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
  color: #6A449B;
  border-bottom-color: #6A449B;
}

.tee-ctab.done {
  color: #6A449B;
  opacity: 0.45;
}

.tee-sel-wrap { position: relative; }

.tee-sel-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px;
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
  padding: 14px 16px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  font-size: 16px;
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

} /* end mobile */

/* ---- Desktop ---- */
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

.tee-sel-wrap { position: relative; }

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
        s = s.replace(
            /\b\w/g, function(c) {
                return c.toUpperCase();
            }
        );
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
                var nm =
                    $s.attr('name') || '';
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
                var rn =
                    $r.attr('name') || '';
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

    detectAddons();
    if (!steps.length) return;

    var _moTimer;
    var _ownChange = false;
    var _mo = new MutationObserver(
        function() {
            if (_ownChange) return;
            if ($(
                '.tee-opt-list.open'
            ).length) {
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

    // Inline tab widget — all screen sizes.
    // PEWC widgets (incl. Dropzone logo
    // upload) stay in-page and work natively.
    $('.single_add_to_cart_button')
        .text('Add to Kit ›');
    var dh = '<div id="tee-desk">';
    dh += '<div id="tee-desk-tabs">';
    dh += '</div>';
    dh += '<div id="tee-desk-opts">';
    dh += '</div>';
    dh += '</div>';
    if ($('form.variations_form').length) {
        $('form.variations_form').before(dh);
    } else {
        $('form.cart').before(dh);
    }
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
            if (t === i) {
                cls += ' active';
            } else if (steps[t].val) {
                cls += ' done';
            }
            th += '<div class="'
                + cls + '"';
            th += ' data-t="' + t + '">';
            th += steps[t].tab + '</div>';
        }

        _ownChange = true;
        $(tabsId).html(th);
        setTimeout(function() {
            _ownChange = false;
        }, 0);

        var selTxt = 'tap to select';
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
        var oh =
            '<div class="tee-sel-wrap">';
        oh += '<div class="tee-sel-bar">';
        oh += '<span class="'
            + selCls + '">';
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
            var isSel =
                (step.val === o.v);
            var oCls = isSel
                ? 'tee-copt chosen'
                : 'tee-copt';
            oh += '<div class="'
                + oCls + '"';
            oh += ' data-v="'
                + o.v + '"';
            oh += ' data-i="'
                + i + '">';
            oh += '<span>'
                + o.t + '</span>';
            oh += '<span class='
                + '"tee-copt-check">';
            oh += '✓</span></div>';
        }
        oh += '</div></div>';

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
            var isOpen =
                $list.hasClass('open');
            var $ico = $(this).find(
                '.tee-sel-icon'
            );
            if (isOpen) {
                $ico.text('-');
            } else if (
                steps[cur]
                && steps[cur].val
            ) {
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
                    if (step.opts[_o].v
                        === val) {
                        step.opts[_o].ref
                            .prop(
                            'checked', true
                            )
                            .trigger('change');
                        setTimeout(
                            detectAddons,
                            200
                        );
                        break;
                    }
                }
            }
            paint(idx);
        }
    );

    // Restore button text after variation
    $('body').on(
        'found_variation'
        + '.wc-variation-form',
        'form.variations_form',
        function(ev, v) {
            $('.single_add_to_cart_button')
                .text('Add to Kit ›');
            setTimeout(detectAddons, 150);
        }
    );

    // Redirect after AJAX add to cart
    $(document).on(
        'added_to_cart', function() {
            window.location.href =
                'https://chainmail-pi'
                + '.vercel.app'
                + '/?resume=goods';
        }
    );
});
</script>
<?php });
