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

  /* Full-bleed gallery */
  .woocommerce-product-gallery {
    width: 100% !important;
    float: none !important;
    margin: 0 !important;
  }

  .woocommerce-product-gallery__wrapper,
  .woocommerce-product-gallery__image {
    margin: 0 !important;
  }

  .woocommerce-product-gallery__image img,
  .woocommerce-product-gallery__image a img {
    width: 100% !important;
    height: 70vw !important;
    object-fit: cover !important;
    object-position: center top !important;
    display: block !important;
  }

  .flex-control-nav,
  .flex-viewport .flex-control-nav,
  .woocommerce-product-gallery__trigger {
    display: none !important;
  }

  /* Summary section */
  .summary.entry-summary {
    width: 100% !important;
    float: none !important;
    padding: 16px 20px 40px !important;
    margin: 0 !important;
  }

  /* Product title */
  .product_title.entry-title {
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #000 !important;
    margin-bottom: 4px !important;
    line-height: 1.1 !important;
  }

  /* Short description — smaller, muted */
  .woocommerce-product-details__short-description {
    font-size: 12px !important;
    color: #666 !important;
    line-height: 1.5 !important;
    margin-bottom: 12px !important;
  }

  /* "CHOOSE TEE > POSITION LOGO..." — shrink it down */
  .woocommerce-product-details__short-description p:last-child,
  .woocommerce-product-details__short-description a {
    font-size: 11px !important;
    color: #999 !important;
    font-weight: 400 !important;
    letter-spacing: 0 !important;
  }

  /* Variations table — convert to stacked list */
  .variations,
  .variations tbody,
  .variations tr,
  .variations td,
  .variations th {
    display: block !important;
    width: 100% !important;
    padding: 0 !important;
    border: none !important;
  }

  .variations tr {
    margin-bottom: 12px !important;
  }

  .variations th.label {
    font-size: 11px !important;
    font-weight: 700 !important;
    color: #6A449B !important;
    text-transform: uppercase !important;
    letter-spacing: 0.06em !important;
    margin-bottom: 4px !important;
  }

  .variations th.label label {
    white-space: normal !important;
  }

  .variations select {
    width: 100% !important;
    border: 1px solid #ddd !important;
    border-radius: 4px !important;
    padding: 10px 12px !important;
    font-size: 14px !important;
    color: #222 !important;
    background: #fff !important;
    appearance: auto !important;
  }

  .reset_variations {
    display: none !important;
  }

  /* Product Add-Ons */
  .wc-pao-addon-wrap,
  .pewc-group {
    margin-bottom: 12px !important;
  }

  .wc-pao-addon-name,
  .pewc-item-label,
  .pewc-group-title {
    font-size: 11px !important;
    font-weight: 700 !important;
    color: #6A449B !important;
    text-transform: uppercase !important;
    letter-spacing: 0.06em !important;
    margin-bottom: 4px !important;
    display: block !important;
  }

  /* Add to Cart — full-width brand purple */
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
    text-transform: uppercase !important;
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
  p.price, span.price {
    color: #6A449B !important;
    font-size: 18px !important;
    font-weight: 700 !important;
    margin-bottom: 10px !important;
    display: block !important;
  }

  /* Quantity */
  .quantity input.qty {
    width: 60px !important;
    border: 1px solid #ccc !important;
    border-radius: 4px !important;
    padding: 10px !important;
    font-size: 15px !important;
    text-align: center !important;
  }

  /* Site header */
  .site-header {
    padding: 10px 16px !important;
    position: sticky !important;
    top: 0 !important;
    z-index: 100 !important;
    background: #fff !important;
    border-bottom: 1px solid #eee !important;
  }

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

  p.price, span.price {
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
