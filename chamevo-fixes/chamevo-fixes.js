/**
 * CHAMEVO PRODUCT PAGE — AUTO-OPEN FIX
 * Deploy to: wp-content/themes/hello-biz-child/chamevo-fixes.js
 * Enqueued by: functions.php (see chamevo-fixes-functions.php)
 *
 * What this does on the WooCommerce product page:
 *  1. Hides the Chamevo editor embed on page load so customers see
 *     the clean WooCommerce product images instead of the generic "TEE" mockup.
 *  2. When the customer selects all 4 variation dropdowns, WooCommerce fires
 *     the `show_variation` event. We then auto-click #cha-start-customizing-button
 *     which navigates them to the Chamevo configurator with the correct variation
 *     product already loaded.
 *  3. If they clear/reset their selections (reset_data event), the auto-open
 *     flag resets so it'll trigger again on the next complete selection.
 *
 * Dependencies: jQuery (loaded by WooCommerce), chamevo_setup_configs (global
 * injected by the Chamevo plugin via wp_localize_script).
 *
 * WooCommerce variation events reference:
 *  - show_variation : fires when ALL selects have values AND a matching
 *                     variation exists. Passes the variation object.
 *  - reset_data     : fires when a select is cleared or no match found.
 */

(function ($) {
  'use strict';

  // Only run on single product pages that have the variations form.
  var $form = $('form.cart.variations_form');
  if (!$form.length) return;

  // -----------------------------------------------------------------------
  // Step 1: Hide the Chamevo embed container on page load.
  //
  // Chamevo reads window.chamevo_setup_configs.selector and calls:
  //   document.getElementById(selector)
  // to create its editor. We add .chainmail-chamevo-hidden to that element
  // so the generic mockup never shows. The CSS rule hides it.
  // -----------------------------------------------------------------------
  function hideChamevoContainer() {
    var config = window.chamevo_setup_configs;
    if (config && config.selector) {
      var container = document.getElementById(config.selector);
      if (container) {
        container.classList.add('chainmail-chamevo-hidden');
      }
    }
  }

  // chamevo_setup_configs is set via wp_localize_script before DOMContentLoaded,
  // so it's available synchronously. Run immediately.
  hideChamevoContainer();

  // -----------------------------------------------------------------------
  // Step 2: Auto-open Chamevo when all 4 variation dropdowns have values.
  //
  // WooCommerce fires show_variation on the form element when:
  //   - All select[name="attribute_*"] have a value, AND
  //   - A matching product variation exists in the catalog.
  //
  // The Chamevo JS also listens to show_variation and updates
  // #cha-start-customizing-button's data-href with the variation product ID.
  // We delay our click slightly (400ms) to let Chamevo finish updating
  // the href before we trigger navigation.
  // -----------------------------------------------------------------------
  var hasAutoOpened = false;

  $form.on('show_variation', function (event, variation) {
    if (hasAutoOpened) return;

    // variation.chamevo_variation_product_id is set by Chamevo's WC integration
    // when this product has a linked Chamevo product. If it's missing, there's
    // no configurator to open for this combination.
    if (!variation || !variation.chamevo_variation_product_id) return;

    hasAutoOpened = true;

    setTimeout(function () {
      var btn = document.querySelector('#cha-start-customizing-button');
      if (btn && btn.dataset.href) {
        // Navigate to the Chamevo configurator URL (same as clicking the button)
        window.location.href = btn.dataset.href;
      }
    }, 400);
  });

  // Reset flag if the customer changes a selection, so the next complete
  // selection triggers auto-open again.
  $form.on('reset_data', function () {
    hasAutoOpened = false;
  });

}(jQuery));
