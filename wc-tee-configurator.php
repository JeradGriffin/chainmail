<?php
// Step-by-step configurator overlay — runs on all WC product pages.
// Add as a Code Snippets entry. Keep wc-tee-product-styles.php active too.

add_action('wp_head', function() {
    if (!is_singular() || !function_exists('is_product')) return;
    if (!is_product()) return;
    echo <<<'STYLE'
<style>

/* Full-screen overlay */
#tee-conf {
  position: fixed;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  max-width: 430px;
  height: 100vh;
  height: 100dvh;
  background: #111;
  z-index: 9000;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  font-family: 'Inter', -apple-system, sans-serif;
}

/* Image section */
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

/* Step counter badge top-right */
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

/* Step label fades into image bottom */
#tee-conf-label {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 48px 20px 16px;
  background: linear-gradient(transparent, rgba(0,0,0,0.75));
  color: #fff;
  font-size: 22px;
  font-weight: 700;
  line-height: 1.2;
}

/* Options panel */
#tee-conf-opts {
  background: #fff;
  overflow-y: auto;
  flex-shrink: 0;
  max-height: 42vh;
}

.tee-copt {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
  font-size: 17px;
  font-weight: 500;
  color: #000;
  -webkit-user-select: none;
  user-select: none;
}

.tee-copt:last-child {
  border-bottom: none;
}

.tee-copt.chosen {
  color: #6A449B;
  font-weight: 700;
}

.tee-copt-check {
  font-size: 18px;
  color: #6A449B;
  font-weight: 700;
  opacity: 0;
  flex-shrink: 0;
}

.tee-copt.chosen .tee-copt-check {
  opacity: 1;
}

/* Bottom nav bar */
#tee-conf-nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 20px;
  padding-bottom: calc(14px + env(safe-area-inset-bottom));
  background: #f2f2f2;
  border-top: 1px solid #ddd;
  flex-shrink: 0;
}

#tee-conf-back {
  font-size: 15px;
  font-weight: 600;
  color: #000;
  background: none;
  border: none;
  cursor: pointer;
  padding: 10px 4px;
  visibility: hidden;
}

#tee-conf-preview {
  font-size: 15px;
  font-weight: 600;
  color: #6A449B;
  background: #fff;
  border: 2px solid #6A449B;
  padding: 10px 18px;
  cursor: pointer;
}

#tee-conf-next {
  font-size: 15px;
  font-weight: 700;
  color: #fff;
  background: #6A449B;
  border: 2px solid #6A449B;
  padding: 10px 18px;
  cursor: pointer;
}

#tee-conf-next:disabled {
  background: transparent;
  color: #6A449B;
  cursor: not-allowed;
}

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

    function attrLabel(nm) {
        var s = nm.replace('attribute_pa_', '');
        s = s.replace(/[-_]/g, ' ');
        s = s.replace(/\b\w/g, function(c) {
            return c.toUpperCase();
        });
        return 'Select ' + s;
    }

    var steps = [];
    var cur = 0;

    $('.variations select').each(function() {
        var $s = $(this);
        var nm = $s.attr('name') || '';
        var lbl = attrLabel(nm);
        var opts = [];
        $s.find('option').each(function() {
            var v = $(this).val();
            if (v) {
                opts.push({
                    v: v,
                    t: $(this).text().trim()
                });
            }
        });
        if (opts.length) {
            steps.push({
                kind: 'select',
                ref: $s,
                label: lbl,
                opts: opts,
                val: ''
            });
        }
    });

    $('.wc-pao-addon-wrap').each(function() {
        var $w = $(this);
        var nm = $w.find('.wc-pao-addon-name').text().trim();
        if (!nm) return;
        var opts = [];
        $w.find('input[type="radio"]').each(function() {
            var $r = $(this);
            var lt = $r.closest('label').text().trim();
            if (!lt) lt = $r.val();
            opts.push({ v: $r.val(), t: lt, ref: $r });
        });
        if (opts.length) {
            steps.push({
                kind: 'radio',
                label: 'Select ' + nm,
                opts: opts,
                val: ''
            });
        }
    });

    if (!steps.length) return;

    var $gi = $('.woocommerce-product-gallery__image img');
    var imgSrc = $gi.attr('data-large_image')
        || $gi.attr('src') || '';

    var h = '<div id="tee-conf">';
    h += '<div id="tee-conf-img">';
    if (imgSrc) {
        h += '<img id="tee-conf-photo" src="' + imgSrc + '">';
    }
    h += '<div id="tee-conf-counter"></div>';
    h += '<div id="tee-conf-label"></div>';
    h += '</div>';
    h += '<div id="tee-conf-opts"></div>';
    h += '<div id="tee-conf-nav">';
    h += '<button id="tee-conf-back">Back</button>';
    h += '<button id="tee-conf-preview">Preview</button>';
    h += '<button id="tee-conf-next" disabled>';
    h += 'Continue</button>';
    h += '</div>';
    h += '</div>';
    $('body').append(h);

    function paint(i) {
        var step = steps[i];
        var total = steps.length;
        $('#tee-conf-label').text(step.label);
        $('#tee-conf-counter').text(
            (i + 1) + ' / ' + total
        );

        var oh = '';
        for (var j = 0; j < step.opts.length; j++) {
            var o = step.opts[j];
            var sel = (step.val === o.v);
            var cls = sel ? 'tee-copt chosen' : 'tee-copt';
            oh += '<div class="' + cls + '"';
            oh += ' data-v="' + o.v + '"';
            oh += ' data-i="' + i + '">';
            oh += '<span>' + o.t + '</span>';
            oh += '<span class="tee-copt-check">&#10003;</span>';
            oh += '</div>';
        }
        $('#tee-conf-opts').html(oh);

        var isLast = (i === steps.length - 1);
        var allDone = true;
        for (var k = 0; k < steps.length; k++) {
            if (!steps[k].val) {
                allDone = false;
                break;
            }
        }
        var canNext = isLast ? allDone : !!step.val;
        $('#tee-conf-next').prop('disabled', !canNext);

        var nxt = isLast ? 'Add to Kit' : 'Continue';
        $('#tee-conf-next').text(nxt);

        if (i === 0) {
            $('#tee-conf-back').css('visibility', 'hidden');
        } else {
            $('#tee-conf-back').css('visibility', 'visible');
        }
    }

    paint(0);

    $(document).on('click', '.tee-copt', function() {
        var val = $(this).data('v');
        var idx = $(this).data('i');
        steps[idx].val = val;
        var step = steps[idx];
        if (step.kind === 'select') {
            step.ref.val(val).trigger('change');
        } else if (step.kind === 'radio') {
            for (var k = 0; k < step.opts.length; k++) {
                if (step.opts[k].v === val) {
                    step.opts[k].ref
                        .prop('checked', true)
                        .trigger('change');
                }
            }
        }
        paint(idx);
    });

    $('#tee-conf-next').on('click', function() {
        var isLast = (cur === steps.length - 1);
        if (isLast) {
            $('.single_add_to_cart_button').trigger('click');
        } else {
            cur++;
            paint(cur);
        }
    });

    $('#tee-conf-back').on('click', function() {
        if (cur > 0) { cur--; paint(cur); }
    });

    $('#tee-conf-preview').on('click', function() {
        $('.woocommerce-product-gallery__image a')
            .first().trigger('click');
    });

    $('body').on(
        'found_variation.wc-variation-form',
        'form.variations_form',
        function(e, v) {
            if (v.image && v.image.src) {
                $('#tee-conf-photo').attr('src', v.image.src);
            }
        }
    );
});
</script>
<?php });
