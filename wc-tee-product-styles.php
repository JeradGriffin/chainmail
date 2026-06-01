<?php
// Scoped to Tee product page only (ID 1946)
add_action('wp_head', function() {
    if (!is_singular() || get_the_ID() !== 1946) return;
    echo <<<'STYLE'
<style>

/* ================================================
   GLOBAL CLUTTER REMOVAL
   ================================================ */

.woocommerce-breadcrumb,
.related.products,
.up-sells.products,
.woocommerce-tabs,
.woocommerce-product-rating,
.posted_in,
.tagged_as {
  display: none !important;
}

/* ================================================
   MOBILE — stack image above content, full-bleed
   ================================================ */

@media (max-width: 768px) {

  /* Hide WP admin bar so page starts at top */
  #wpadminbar {
    display: none !important;
  }
  html {
    margin-top: 0 !important;
  }

  /* Stack the two-column WC layout */
  .woocommerce div.product {
    display: flex !important;
    flex-direction: column !important;
  }

  /* Full-bleed gallery — remove gutters */
  .woocommerce-product-gallery {
    width: 100% !important;
    float: none !important;
    margin: 0 !important;
  }

  .woocommerce-product-gallery__wrapper {
    margin: 0 !important;
  }

  .woocommerce-product-gallery__image {
    margin: 0 !important;
  }

  .woocommerce-product-gallery__image img,
  .woocommerce-product-gallery__image a img {
    width: 100% !important;
    height: 56vw !important;
    object-fit: cover !important;
    object-position: center top !important;
    display: block !important;
  }

  /* Gallery nav dots/arrows — hide on mobile */
  .flex-control-nav,
  .flex-viewport .flex-control-nav,
  .woocommerce-product-gallery__trigger {
    display: none !important;
  }

  /* Summary section */
  .summary.entry-summary {
    width: 100% !important;
    float: none !important;
    padding: 20px 20px 40px !important;
    margin: 0 !important;
  }

  /* Product title */
  .product_title.entry-title {
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #000 !important;
    margin-bottom: 6px !important;
    line-height: 1.1 !important;
  }

  /* Short description */
  .woocommerce-product-details__short-description {
    font-size: 13px !important;
    color: #444 !important;
    line-height: 1.5 !important;
    margin-bottom: 16px !important;
  }

  /* Variation label column */
  .variations th.label {
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #6A449B !important;
    padding-right: 12px !important;
    white-space: nowrap !important;
  }

  /* Variation select dropdowns */
  .variations select {
    width: 100% !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    padding: 9px 10px !important;
    font-size: 14px !important;
    color: #222 !important;
    background: #fff !important;
    margin-bottom: 4px !important;
  }

  /* Variation table spacing */
  .variations {
    width: 100% !important;
    border-collapse: collapse !important;
    margin-bottom: 16px !important;
  }

  .variations tr {
    display: flex !important;
    flex-direction: column !important;
    margin-bottom: 10px !important;
  }

  .variations td,
  .variations th {
    display: block !important;
    padding: 0 !important;
  }

  /* Hide the "clear" link on variations */
  .reset_variations {
    display: none !important;
  }

  /* Product Add-Ons section */
  .wc-pao-addon-wrap,
  .pewc-group {
    margin-bottom: 16px !important;
  }

  .wc-pao-addon-name,
  .pewc-item-label {
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #6A449B !important;
    margin-bottom: 6px !important;
    display: block !important;
  }

  /* Add to Cart button — full-width brand purple */
  .single_add_to_cart_button,
  button.single_add_to_cart_button {
    width: 100% !important;
    background: #6A449B !important;
    border-color: #6A449B !important;
    color: #fff !important;
    font-size: 16px !important;
    font-weight: 700 !important;
    padding: 14px 20px !important;
    border-radius: 4px !important;
    letter-spacing: 0.02em !important;
    margin-top: 8px !important;
    cursor: pointer !important;
  }

  .single_add_to_cart_button:hover {
    background: #5a3685 !important;
    border-color: #5a3685 !important;
  }

  .single_add_to_cart_button.disabled,
  .single_add_to_cart_button:disabled {
    background: #c4aee0 !important;
    border-color: #c4aee0 !important;
    cursor: not-allowed !important;
  }

  /* Price */
  p.price,
  span.price {
    color: #6A449B !important;
    font-size: 20px !important;
    font-weight: 700 !important;
    margin-bottom: 12px !important;
    display: block !important;
  }

  /* Quantity field */
  .quantity input.qty {
    width: 60px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    padding: 10px !important;
    font-size: 15px !important;
    text-align: center !important;
  }

  /* Site header — slim it down */
  .site-header {
    padding: 10px 16px !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 100 !important;
    background: #fff !important;
    border-bottom: 1px solid #eee !important;
  }

  /* Page wrapper padding */
  .site-main,
  .woocommerce {
    padding: 0 !important;
  }

  article.product {
    padding: 0 !important;
    margin: 0 !important;
  }

}

/* ================================================
   DESKTOP — brand color polish only
   ================================================ */

@media (min-width: 769px) {

  .product_title.entry-title {
    font-size: 36px !important;
    font-weight: 800 !important;
  }

  .single_add_to_cart_button,
  button.single_add_to_cart_button {
    background: #6A449B !important;
    border-color: #6A449B !important;
    color: #fff !important;
    font-weight: 700 !important;
    padding: 14px 28px !important;
    border-radius: 4px !important;
  }

  .single_add_to_cart_button:hover {
    background: #5a3685 !important;
    border-color: #5a3685 !important;
  }

  .single_add_to_cart_button.disabled,
  .single_add_to_cart_button:disabled {
    background: #c4aee0 !important;
    border-color: #c4aee0 !important;
  }

  p.price,
  span.price {
    color: #6A449B !important;
    font-weight: 700 !important;
  }

  .variations th.label {
    font-weight: 700 !important;
    color: #6A449B !important;
  }

}

</style>
STYLE;
});
