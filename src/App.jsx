import React, { useState, useEffect } from 'react';
import { ChevronRight, ChevronLeft, ShoppingBag, Menu, Upload } from 'lucide-react';

const BRAND_COLOR = 'var(--color-brand)';

// Per-good dropdown config — TODO: confirm all options with client before launch
// TODO: swap all placeholder images for real per-variant product images once assets are ready
const goodsConfig = {
  tee: {
    name: 'Tee',
    image: '/tee-preview.jpg',
    dropdowns: [
      { id: 'sleeve', label: 'SLEEVE LENGTH', options: ['Long Sleeve', 'Short Sleeve'], key: 'sleeve' },
      { id: 'color', label: 'COLOR', options: ['Black', 'Tan', 'White'], key: 'color' },
    ],
  },
  hoodie: {
    name: 'Hoodie',
    image: '/hoodie-placeholder.png',
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Pullover', 'Zip-Up'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Grey', 'Navy'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['1 Color Print', '4 Color Print', '1 Color Embroider'], key: 'decoration' },
    ],
  },
  cap: {
    name: 'Cap',
    image: '/cap-placeholder.png',
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Structured', 'Unstructured'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Navy', 'Grey'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['1 Color Print', '1 Color Embroider'], key: 'decoration' }, // TODO: confirm options
    ],
  },
  tote: {
    name: 'Tote',
    image: '/tote-placeholder.png',
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Standard', 'Large'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Natural', 'Black'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['1 Color Print', '4 Color Print', '1 Color Embroider'], key: 'decoration' },
    ],
  },
  bottle: {
    name: 'Bottle',
    image: '/bottle-placeholder.png',
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['16oz', '20oz'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Silver', 'White'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['Laser Engrave', '1 Color Print'], key: 'decoration' }, // TODO: confirm options
    ],
  },
  journal: {
    name: 'Journal',
    image: '/journal-placeholder.png',
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Hardcover', 'Softcover'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Brown', 'Navy'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['Deboss', 'Laser Engrave', '1 Color Print'], key: 'decoration' }, // TODO: confirm options
    ],
  },
};

const spiritCategories = [
  { id: 'bourbon',   name: 'Bourbon'   },
  { id: 'vodka',     name: 'Vodka'     },
  { id: 'rum',       name: 'Rum'       },
  { id: 'mezcal',    name: 'Mezcal'    },
  { id: 'champagne', name: 'Champagne' },
  { id: 'gin',       name: 'Gin'       },
];

// TODO: confirm brand options for all categories with Cody before launch
const spiritBrands = {
  bourbon:   [], // TODO
  vodka:     [], // TODO
  rum:       [], // TODO
  mezcal:    [
    { name: 'Manojo',     price: 40 },
    { name: 'Del Maguey', price: 30 },
    { name: 'Ojo de Tigre', price: 35 },
  ],
  champagne: [], // TODO
  gin:       [], // TODO
};

export default function KitBuilder() {
  const [currentStep, setCurrentStep] = useState(-1);
  const [kitQuantityIndex, setKitQuantityIndex] = useState(0);
  const [addProduct, setAddProduct] = useState(false);
  const [confirmSize, setConfirmSize] = useState(false);
  const [shippingOption, setShippingOption] = useState('list'); // 'list' | 'me'
  const [shippingFile, setShippingFile] = useState(null);
  const [logoFile, setLogoFile] = useState(null);
  const [showLogoWarning, setShowLogoWarning] = useState(false);
  const [showResolutionWarning, setShowResolutionWarning] = useState(false);
  const [selectedGoods, setSelectedGoods] = useState([]);
  const [configuringGood, setConfiguringGood] = useState(null);
  const [goodConfigurations, setGoodConfigurations] = useState({});
  const [openDropdown, setOpenDropdown] = useState(null);
  const [showPreview, setShowPreview] = useState(false);
  const [selectedSpiritCategory, setSelectedSpiritCategory] = useState(null);
  const [selectedSpiritBrand, setSelectedSpiritBrand] = useState(null);
  const [spiritSubStep, setSpiritSubStep] = useState('category'); // 'category' | 'brand'
  const [messageText, setMessageText] = useState('');
  const [messageConfirmed, setMessageConfirmed] = useState(false);

  const quantities = [24, 48, 72, 96, '120+'];

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('resume') === 'goods') {
      try {
        const saved = JSON.parse(localStorage.getItem('chainmail_kit_state') || '{}');
        if (saved.kitQuantityIndex !== undefined) setKitQuantityIndex(saved.kitQuantityIndex);
        if (saved.shippingOption) setShippingOption(saved.shippingOption);
        if (saved.addProduct !== undefined) setAddProduct(saved.addProduct);
        if (saved.confirmSize !== undefined) setConfirmSize(saved.confirmSize);
        if (saved.selectedGoods) {
          const withTee = [...new Set([...saved.selectedGoods, 'tee'])];
          setSelectedGoods(withTee);
        }
        if (saved.goodConfigurations) setGoodConfigurations(saved.goodConfigurations);
        localStorage.removeItem('chainmail_kit_state');
      } catch (e) {}
      setCurrentStep(3);
      window.history.replaceState({}, '', window.location.pathname);
    }
  }, []);
const handleQuantitySelect = (idx) => {
    setKitQuantityIndex(idx);
    if (quantities[idx] === '120+') {
      window.location.href = 'mailto:sales@chainmail.com?subject=Large Order - 120+ Kits';
    }
  };

  const handleStartKit = () => {
    setCurrentStep(0);
  };

  const renderWelcome = () => (
    <div className="min-h-screen bg-white flex flex-col welcome-page">
      {/* Header */}
      <header className="bg-white border-b border-gray-200 px-5 py-3 flex items-center justify-between">
        <img src="/chainmail-logo.png" alt="chainmail" className="logo" />
        <div className="flex items-center gap-5">
          <button onClick={() => {}} aria-label="Search" className="bg-transparent border-none cursor-pointer p-0">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <mask id="mask0_98_67" style={{maskType:'alpha'}} maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
                <rect width="24" height="24" fill="#D9D9D9"/>
              </mask>
              <g mask="url(#mask0_98_67)">
                <path d="M19.5423 20.577L13.2616 14.296C12.7616 14.7088 12.1866 15.0319 11.5366 15.2653C10.8866 15.4986 10.2141 15.6153 9.5193 15.6153C7.81014 15.6153 6.36364 15.0235 5.1798 13.84C3.99597 12.6565 3.40405 11.2103 3.40405 9.5015C3.40405 7.79283 3.9958 6.34617 5.1793 5.1615C6.3628 3.977 7.80897 3.38475 9.5178 3.38475C11.2265 3.38475 12.6731 3.97667 13.8578 5.1605C15.0423 6.34433 15.6346 7.79083 15.6346 9.5C15.6346 10.2142 15.5147 10.8963 15.2751 11.5463C15.0352 12.1963 14.7153 12.7616 14.3153 13.2423L20.5961 19.523L19.5423 20.577ZM9.5193 14.1155C10.8078 14.1155 11.8991 13.6683 12.7933 12.774C13.6876 11.8798 14.1348 10.7885 14.1348 9.5C14.1348 8.2115 13.6876 7.12017 12.7933 6.226C11.8991 5.33167 10.8078 4.8845 9.5193 4.8845C8.2308 4.8845 7.13947 5.33167 6.2453 6.226C5.35097 7.12017 4.9038 8.2115 4.9038 9.5C4.9038 10.7885 5.35097 11.8798 6.2453 12.774C7.13947 13.6683 8.2308 14.1155 9.5193 14.1155Z" fill="#1C1B1F"/>
              </g>
            </svg>
          </button>
          <button onClick={() => {}} aria-label="Shopping bag" className="bg-transparent border-none cursor-pointer p-0">
            <ShoppingBag size={20} color="#000" />
          </button>
          <button onClick={() => {}} aria-label="Menu" className="bg-transparent border-none cursor-pointer p-0">
            <Menu size={20} color="#000" />
          </button>
        </div>
      </header>

      {/* Content */}
      <div className="flex-1 px-7 pb-8 flex flex-col justify-evenly" style={{ gap: '32px' }}>
        <div className="welcome-body">
          <h1>Welcome.</h1>
          <p>You've made it to the Kit Builder. You'll be guided through a series of easy questions to make this as smooth as possible!</p>
          <p>Each product category has been pre-vetted for your convenience and trust.</p>
        </div>

        <h2 className="font-bold mb-5">How many kits we talking about?</h2>

        {/* Quantity Selector */}
        <div className="mb-8 qty-selector">
          {/* Labels + Ticks + Slider aligned together */}
          <div className="slider-wrapper">
            {/* Labels — each positioned absolutely so they center over their tick */}
            <div className="qty-labels">
              {quantities.map((qty, idx) => {
                const pct = (idx / (quantities.length - 1)) * 100;
                return (
                  <span
                    key={qty}
                    onClick={() => handleQuantitySelect(idx)}
                    className={`qty-label${kitQuantityIndex === idx ? ' active' : ''}`}
                    style={{ left: `${pct}%` }}
                  >
                    {qty}
                  </span>
                );
              })}
            </div>

            {/* Custom track + ticks */}
            <div className="track-container">
              {/* Track line */}
              <div className="track-line" />
              {/* Tick marks */}
              <div className="flex justify-between tick-marks">
                {quantities.map((_, idx) => (
                  <div key={idx} className="tick" />
                ))}
              </div>
              {/* Visual thumb — transitions smoothly */}
              <div
                className="slider-thumb"
                style={{ left: `${(kitQuantityIndex / (quantities.length - 1)) * 100}%` }}
              />
              {/* Range input (invisible, handles interaction) */}
              <input
                type="range"
                min="0"
                max={quantities.length - 1}
                value={kitQuantityIndex}
                className="range-input"
                onChange={(e) => {
                  const idx = parseInt(e.target.value);
                  handleQuantitySelect(idx);
                }}
              />
            </div>
          </div>

          <p className="italic text-center mt-3 qty-hint">
            Slide to select. Minimum order<br />quantities start at 24 kits.
          </p>
        </div>

        {/* Buttons */}
        <div>
          <button
            onClick={handleStartKit}
            className="w-full text-white font-bold flex items-center justify-between transition hover:opacity-90 start-kit-btn"
          >
            Start my kit
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 -1 28 24" fill="none" style={{ flexShrink: 0 }}>
              <path d="M9.03215 21.3118L7.06689 19.4571L16.9349 10.2347L7.06689 1.01208L9.03215 -0.84259L20.8846 10.2347L9.03215 21.3118Z" fill="white"/>
            </svg>
          </button>

          <button
            onClick={() => window.location.href = 'mailto:sales@chainmail.com?subject=Large Order - 120+ Kits'}
            className="w-full border-2 transition hover:opacity-70 large-order-btn"
          >
            More than 120 Kits?<br />Tap here, we'll assign an agent!
          </button>
        </div>

        {/* Back */}
        <button className="mt-8 font-semibold flex items-center gap-1 hover:opacity-70 back-btn">
          <ChevronLeft size={18} /> Back
        </button>
      </div>
    </div>
  );

  const chainStep = Math.min(Math.max(currentStep + 1, 0), 6);

  const renderStepIndicator = () => (
    <div className="step-indicator-wrap">
      <img
        src={`/chain_${chainStep}.svg`}
        alt=""
        className="step-chain-bg"
      />
    </div>
  );

  const renderHeader = () => (
    <header className="bg-white border-b border-gray-200 px-5 py-3 flex items-center justify-between">
      <img src="/chainmail-logo.png" alt="chainmail" className="logo" />
      <div className="flex items-center gap-5">
        <button onClick={() => {}} aria-label="Search" className="bg-transparent border-none cursor-pointer p-0">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <mask id="mask0_98_67" style={{maskType:'alpha'}} maskUnits="userSpaceOnUse" x="0" y="0" width="24" height="24">
              <rect width="24" height="24" fill="#D9D9D9"/>
            </mask>
            <g mask="url(#mask0_98_67)">
              <path d="M19.5423 20.577L13.2616 14.296C12.7616 14.7088 12.1866 15.0319 11.5366 15.2653C10.8866 15.4986 10.2141 15.6153 9.5193 15.6153C7.81014 15.6153 6.36364 15.0235 5.1798 13.84C3.99597 12.6565 3.40405 11.2103 3.40405 9.5015C3.40405 7.79283 3.9958 6.34617 5.1793 5.1615C6.3628 3.977 7.80897 3.38475 9.5178 3.38475C11.2265 3.38475 12.6731 3.97667 13.8578 5.1605C15.0423 6.34433 15.6346 7.79083 15.6346 9.5C15.6346 10.2142 15.5147 10.8963 15.2751 11.5463C15.0352 12.1963 14.7153 12.7616 14.3153 13.2423L20.5961 19.523L19.5423 20.577ZM9.5193 14.1155C10.8078 14.1155 11.8991 13.6683 12.7933 12.774C13.6876 11.8798 14.1348 10.7885 14.1348 9.5C14.1348 8.2115 13.6876 7.12017 12.7933 6.226C11.8991 5.33167 10.8078 4.8845 9.5193 4.8845C8.2308 4.8845 7.13947 5.33167 6.2453 6.226C5.35097 7.12017 4.9038 8.2115 4.9038 9.5C4.9038 10.7885 5.35097 11.8798 6.2453 12.774C7.13947 13.6683 8.2308 14.1155 9.5193 14.1155Z" fill="#1C1B1F"/>
            </g>
          </svg>
        </button>
        <button onClick={() => {}} aria-label="Shopping bag" className="bg-transparent border-none cursor-pointer p-0">
          <ShoppingBag size={20} color="#000" />
        </button>
        <button onClick={() => {}} aria-label="Menu" className="bg-transparent border-none cursor-pointer p-0">
          <Menu size={20} color="#000" />
        </button>
      </div>
    </header>
  );

  const renderAddProduct = () => (
    <div className="min-h-screen bg-white flex flex-col process-page">
      {renderHeader()}

      <div className="content-area">
        {renderStepIndicator()}

        <h2 className="process-title">All about you</h2>
        <p className="process-description">
          Each kit has space for your product to be included, so there's no confusion where it's coming from.
        </p>

        <p className="process-hint">Tap to make selections.</p>

        {/* Yes, add my product */}
        <label className="process-option" onClick={() => setAddProduct(true)}>
          <span className={`radio-circle ${addProduct ? 'selected' : ''}`} />
          <span className="option-text">
            <span><strong style={{ fontSize: '28px' }}>Yes,</strong> add my product</span>
          </span>
          <span className="option-price">$250</span>
        </label>

        {/* Confirm size radio — visible only after Yes is selected */}
        {addProduct && (
          <>
            <label className="process-checkbox" onClick={() => setConfirmSize(!confirmSize)}>
              <span className={`checkbox-circle ${confirmSize ? 'selected' : ''}`} />
              <span className="checkbox-text">
                Please confirm your item is less than 12″ x 12″ x 8″
              </span>
            </label>

            <p className="process-note">
              At the end of this process we will send an inbound form to get info about your product and how to ship it to us.
            </p>
          </>
        )}

      </div>

      {/* Bottom area */}
      <div className="bottom-area">
        <div className="bottom-skip">
          <hr className="process-divider" />
          <button
            className="skip-btn"
            onClick={() => {
              setAddProduct(false);
              setConfirmSize(false);
              setCurrentStep(1);
            }}
          >
            <strong className="skip-bold">Skip to next,</strong> do not add my item.
          </button>
        </div>
        <div className="bottom-bar-buttons">
          <button className="back-link" onClick={() => setCurrentStep(-1)}>
            <ChevronLeft size={16} /> Back
          </button>
          <button
            className="continue-btn"
            disabled={!addProduct || !confirmSize}
            onClick={() => setCurrentStep(1)}
          >
            Continue
            <ChevronRight size={18} color={!addProduct || !confirmSize ? BRAND_COLOR : '#fff'} />
          </button>
        </div>
      </div>
    </div>
  );

  const handleFileDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer?.files?.[0] || e.target?.files?.[0];
    if (file) {
      const ext = file.name.split('.').pop().toLowerCase();
      if (['xlsx', 'xls', 'csv'].includes(ext)) {
        setShippingFile(file);
      }
    }
  };

  const renderShipping = () => {
    const canContinue = shippingOption === 'me' || (shippingOption === 'list' && shippingFile);

    return (
      <div className="min-h-screen bg-white flex flex-col process-page">
        {renderHeader()}

        <div className="content-area">
          {renderStepIndicator()}

          <h2 className="process-title">Shipping</h2>
          <p className="process-description">
            Upload your recipient list or we can send it direct to you.
          </p>

          {/* Ship to my list */}
          {/*
            WooCommerce cart behavior (not yet implemented):
            - If list uploaded: parse list and show in cart line items
            - If no list (ship to me): auto-populate size distribution from kit quantity
              e.g. 24 kits → 30% XL / 50% Large / 20% Medium — send to cart for editing
          */}
          <label className="process-option" onClick={() => setShippingOption('list')}>
            <span className={`radio-circle ${shippingOption === 'list' ? 'selected' : ''}`} />
            <span className="option-text">
              <strong style={{ fontSize: '22px' }}>Ship to my list</strong>
            </span>
          </label>

        {/* Upload area */}
        <div className="shipping-upload-band">
          <div className="shipping-upload-band-inner">
            <div
              className={`upload-dropzone ${shippingFile ? 'has-file' : ''}`}
              onDragOver={(e) => e.preventDefault()}
              onDrop={handleFileDrop}
              onClick={() => document.getElementById('shipping-file-input').click()}
            >
              <Upload size={28} color="#993399" />
              <p className="upload-dropzone-title">
                {shippingFile ? shippingFile.name : 'Upload your list'}
              </p>
              {!shippingFile && (
                <p className="upload-dropzone-formats">(XLSX, XLS, CSV)</p>
              )}
              <input
                id="shipping-file-input"
                type="file"
                accept=".xlsx,.xls,.csv"
                style={{ display: 'none' }}
                onChange={handleFileDrop}
              />
            </div>
            <p className="upload-hint">Tap or Drag to place file here</p>
          </div>
        </div>
        <div className="shipping-template-band">
          <div className="shipping-template-band-inner">
            <button
              className="download-template-btn"
              onClick={(e) => {
                e.stopPropagation();
                // TODO: link to actual template file
              }}
            >
              Download shipping list template
            </button>
          </div>
        </div>

          {/* Ship to me */}
          <label className="process-option" onClick={() => setShippingOption('me')}>
            <span className={`radio-circle ${shippingOption === 'me' ? 'selected' : ''}`} />
            <span className="option-text">
              <strong style={{ fontSize: '22px' }}>Ship to me</strong>
              <br />
              <span style={{ fontSize: '14px', color: '#555' }}>
                I will provide my shipping information during the checkout process.
              </span>
            </span>
          </label>
        </div>

        {/* Bottom area */}
        <div className="bottom-area">
          <div className="bottom-bar-buttons">
            <button className="back-link" onClick={() => setCurrentStep(0)}>
              <ChevronLeft size={16} /> Back
            </button>
            <button
              className="continue-btn"
              disabled={!canContinue}
              onClick={() => setCurrentStep(3)}
            >
              Continue
              <ChevronRight size={18} color={!canContinue ? BRAND_COLOR : '#fff'} />
            </button>
          </div>
        </div>
      </div>
    );
  };

  const premiumGoods = [
    { id: 'tee', name: 'Tee', price: 25, icon: '/icon-tee.svg' },
    { id: 'hoodie', name: 'Hoodie', price: 45, icon: '/icon-hoodie.svg' },
    { id: 'cap', name: 'Cap', price: 30, icon: '/icon-cap.svg' },
    { id: 'tote', name: 'Tote', price: 35, icon: '/icon-tote.svg' },
    { id: 'bottle', name: 'Bottle', price: 30, icon: '/icon-bottle.svg' },
    { id: 'journal', name: 'Journal', price: 20, icon: '/icon-journal.svg' },
  ];

  const vectorExts = ['svg', 'ai', 'eps', 'pdf'];
  const rasterExts = ['png', 'jpg', 'jpeg'];
  const allLogoExts = ['svg', 'ai', 'eps', 'pdf', 'png', 'jpg', 'jpeg'];

  const handleLogoDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer?.files?.[0] || e.target?.files?.[0];
    if (file) {
      const ext = file.name.split('.').pop().toLowerCase();
      if (allLogoExts.includes(ext)) {
        setLogoFile(file);
        setShowLogoWarning(false);
        setShowResolutionWarning(false);
        if (!vectorExts.includes(ext)) {
          setShowLogoWarning(true);
        }
        if (rasterExts.includes(ext)) {
          const url = URL.createObjectURL(file);
          const img = new window.Image();
          img.onload = () => {
            if (img.naturalWidth < 1000 || img.naturalHeight < 1000) {
              setShowResolutionWarning(true);
            }
            URL.revokeObjectURL(url);
          };
          img.src = url;
        }
      }
    }
  };

  const renderLogo = () => (
    <div className="min-h-screen bg-white flex flex-col process-page">
      {renderHeader()}

      <div className="content-area">
        {renderStepIndicator()}

        <h2 className="process-title">Your logo</h2>
        <p className="process-description">
          Upload your logo now to see it on the products you select moving forward. Too much hassle? Skip for now and we can finish it later.
        </p>

        {/* Upload area — full-width red band */}
        <div className="shipping-upload-band">
          <div className="shipping-upload-band-inner">
            <div
              className={`upload-dropzone ${logoFile ? 'has-file' : ''}`}
              onDragOver={(e) => e.preventDefault()}
              onDrop={handleLogoDrop}
              onClick={() => document.getElementById('logo-file-input').click()}
            >
              <Upload size={28} color="#993399" />
              <p className="upload-dropzone-title">
                {logoFile ? logoFile.name : 'Upload your logo'}
              </p>
              {!logoFile && (
                <p className="upload-dropzone-formats">(SVG, AI, PNG, JPG)</p>
              )}
              <input
                id="logo-file-input"
                type="file"
                accept=".svg,.ai,.eps,.pdf,.png,.jpg,.jpeg"
                style={{ display: 'none' }}
                onChange={handleLogoDrop}
              />
            </div>
            <p className="upload-hint">Tap or Drag to place file here</p>
          </div>
        </div>

        <p className="logo-vector-hint">
          Vector images like SVG and AI files will show up best. PNG files with transparent background will work. JPG files will not have a preview until final review.
        </p>
      </div>

      {/* Low resolution warning modal */}
      {showResolutionWarning && !showLogoWarning && (
        <div className="logo-warning-overlay">
          <div className="logo-warning-modal">
            <p>
              Your logo looks a little small. For the best print quality at 5×5 inches, we recommend at least 1500×1500 pixels. Your file may appear blurry in the final product. A vector file (AI or SVG) would give you the sharpest result — no resolution limit.
            </p>
            <div className="logo-warning-buttons">
              <button
                className="logo-warning-btn-upload"
                onClick={() => {
                  setShowResolutionWarning(false);
                  setLogoFile(null);
                  document.getElementById('logo-file-input').click();
                }}
              >
                Upload a better file
              </button>
              <button
                className="logo-warning-btn-continue"
                onClick={() => setShowResolutionWarning(false)}
              >
                Continue anyway
              </button>
            </div>
          </div>
        </div>
      )}

      {/* JPG/non-vector warning modal */}
      {showLogoWarning && (
        <div className="logo-warning-overlay">
          <div className="logo-warning-modal">
            <p>
              Uh-oh! This isn't a vector file. If possible, please upload a vector file (AI / SVG / EPS) instead. JPG and other files usually have white backgrounds. If you don't have the correct file, we're here to help! We will make the changes and send you a virtual sample after checkout for your approval.
            </p>
            <div className="logo-warning-buttons">
              <button
                className="logo-warning-btn-upload"
                onClick={() => {
                  setShowLogoWarning(false);
                  setLogoFile(null);
                  document.getElementById('logo-file-input').click();
                }}
              >
                Upload a vector file
              </button>
              <button
                className="logo-warning-btn-continue"
                onClick={() => setShowLogoWarning(false)}
              >
                Continue with current file
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Bottom area */}
      <div className="bottom-area">
        <div className="bottom-skip">
          <hr className="process-divider" />
          <button
            className="skip-btn"
            onClick={() => setCurrentStep(3)}
          >
            <strong className="skip-bold">Skip to next,</strong> I'll do it later.
          </button>
        </div>
        <div className="bottom-bar-buttons">
          <button className="back-link" onClick={() => setCurrentStep(1)}>
            <ChevronLeft size={16} /> Back
          </button>
          <button
            className="continue-btn"
            disabled={!logoFile}
            onClick={() => setCurrentStep(3)}
          >
            Continue
            <ChevronRight size={18} color={!logoFile ? BRAND_COLOR : '#fff'} />
          </button>
        </div>
      </div>
    </div>
  );

  const renderGoodConfig = (goodId) => {
    const config = goodsConfig[goodId];
    if (!config) return null;

    const goodCfg = goodConfigurations[goodId] || {};
    const allSelected = config.dropdowns.every((dd) => goodCfg[dd.key]);

    const updateConfig = (key, value) => {
      setGoodConfigurations((prev) => ({
        ...prev,
        [goodId]: { ...(prev[goodId] || {}), [key]: value },
      }));
    };

    return (
      <div className="min-h-screen bg-white flex flex-col process-page" onClick={() => setOpenDropdown(null)}>
        {renderHeader()}

        <div className="flex-1 flex flex-col min-h-0">
          <div className="px-7 pt-6">
            {renderStepIndicator()}
            <div className="tee-config-header">
              <span className="tee-config-name">{config.name}</span>
              <span className="tee-config-sub">Make selections below.</span>
            </div>
          </div>

          <div className="tee-image-wrap">
            <img src={config.image} alt={`${config.name} preview`} className="tee-img" />
            <div className={`tee-logo-overlay ${goodCfg.logoPosition === 'Left' ? 'tee-logo-left' : 'tee-logo-center'}`}>
              {logoFile && (
                <img
                  src={URL.createObjectURL(logoFile)}
                  alt="logo"
                  className="tee-logo-img"
                />
              )}
            </div>
          </div>

          <div className="tee-dropdowns-band" onClick={(e) => e.stopPropagation()}>
            <div className="tee-dropdowns-grid">
              {config.dropdowns.map((dd) => (
                <div key={dd.id} className="tee-dropdown-cell">
                  <div className="tee-dd-label">{dd.label}</div>
                  <div
                    className="tee-dd-trigger"
                    onClick={(e) => {
                      e.stopPropagation();
                      setOpenDropdown(openDropdown === dd.id ? null : dd.id);
                    }}
                  >
                    <span className={goodCfg[dd.key] ? 'tee-dd-value' : 'tee-dd-placeholder'}>
                      {goodCfg[dd.key] || 'tap to choose'}
                    </span>
                    <span className="tee-dd-plus">+</span>
                  </div>
                  {openDropdown === dd.id && (
                    <div className="tee-dd-options">
                      {dd.options.map((opt) => (
                        <div
                          key={opt}
                          className="tee-dd-option"
                          onClick={(e) => {
                            e.stopPropagation();
                            updateConfig(dd.key, opt);
                            setOpenDropdown(null);
                          }}
                        >
                          {opt}
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>

        <div className="tee-bottom-bar">
          <button className="back-link" onClick={() => { setConfiguringGood(null); setOpenDropdown(null); }}>
            <ChevronLeft size={16} /> Back
          </button>
          <button
            className="tee-preview-btn"
            onClick={(e) => { e.stopPropagation(); setShowPreview(true); }}
          >
            Preview
          </button>
          <button
            className="tee-add-to-kit-btn"
            disabled={!allSelected}
            onClick={() => {
              setSelectedGoods((prev) => prev.includes(goodId) ? prev : prev.length < 3 ? [...prev, goodId] : prev);
              setConfiguringGood(null);
            }}
          >
            Add to Kit <ChevronRight size={18} />
          </button>
        </div>

        {showPreview && (
          <div className="tee-preview-overlay" onClick={() => setShowPreview(false)}>
            <div className="tee-preview-panel">
              <div className="tee-preview-card">
                <div className="tee-preview-img-wrap">
                  <img src={config.image} alt={`${config.name} preview`} className="tee-preview-img" />
                  <div className={`tee-preview-logo tee-preview-logo-${goodCfg.logoPosition === 'Left' ? 'left' : 'center'}`}>
                    {logoFile && (
                      <img
                        src={URL.createObjectURL(logoFile)}
                        alt="logo preview"
                        className="tee-logo-img"
                      />
                    )}
                  </div>
                </div>
              </div>
              <div className="tee-preview-close-bar">TAP TO CLOSE</div>
            </div>
          </div>
        )}
      </div>
    );
  };

  const renderGoods = () => {
    const canContinue = selectedGoods.length >= 1;

    return (
      <div className="min-h-screen bg-white flex flex-col process-page">
        {renderHeader()}

        <div className="content-area">
          {renderStepIndicator()}

          <h2 className="process-title">Add premium goods</h2>
          <p className="process-description">
            Select 1-3 of our curated premium items to be branded with your logo.
          </p>

          {premiumGoods.map((good) => {
            const isSelected = selectedGoods.includes(good.id);
            return (
              <div
                key={good.id}
                className={`goods-option ${isSelected ? 'selected' : ''}`}
                onClick={() => {
                  if (good.id === 'tee') {
                    const qty = quantities[kitQuantityIndex];
                    localStorage.setItem('chainmail_kit_state', JSON.stringify({
                      kitQuantityIndex,
                      shippingOption,
                      addProduct,
                      confirmSize,
                      selectedGoods,
                      goodConfigurations,
                    }));
                    window.location.href = `https://chainmail.store/dev/product/tee/?quantity=${qty}`;
                  } else {
                    setConfiguringGood(good.id);
                    setShowPreview(false);
                  }
                }}
              >
                <span className={`radio-circle ${isSelected ? 'selected' : ''}`} />
                <img src={good.icon} alt={good.name} className="goods-icon" />
                <span className={`goods-name ${isSelected ? 'selected' : ''}`}>{good.name}</span>
                <span className="goods-price">${good.price}</span>
              </div>
            );
          })}
        </div>

        {/* Bottom area */}
        <div className="bottom-area">
          <div className="bottom-skip">
            <hr className="process-divider" />
            <button
              className="skip-btn"
              onClick={() => setCurrentStep(4)}
            >
              <strong className="skip-bold">Skip to next,</strong> do not include goods.
            </button>
          </div>
          <div className="bottom-bar-buttons">
            <button className="back-link" onClick={() => setCurrentStep(1)}>
              <ChevronLeft size={16} /> Back
            </button>
            <button
              className="continue-btn"
              disabled={!canContinue}
              onClick={() => setCurrentStep(4)}
            >
              Continue
              <ChevronRight size={18} color={!canContinue ? BRAND_COLOR : '#fff'} />
            </button>
          </div>
        </div>
      </div>
    );
  };

  const renderSpirits = () => (
    <div className="min-h-screen bg-white flex flex-col process-page">
      {renderHeader()}

      <div className="content-area">
        {renderStepIndicator()}

        <h2 className="process-title">Add some booze</h2>
        <p className="process-description">Select one premium spirit category.</p>

        {spiritCategories.map((cat) => {
          const isSelected = selectedSpiritCategory === cat.id;
          return (
            <div
              key={cat.id}
              className={`goods-option ${isSelected ? 'selected' : ''}`}
              onClick={() => setSelectedSpiritCategory(cat.id)}
            >
              <span className={`radio-circle ${isSelected ? 'selected' : ''}`} />
              <span className={`goods-name ${isSelected ? 'selected' : ''}`}>{cat.name}</span>
            </div>
          );
        })}

        <p className="tbb-note">TBB Compliant Shipping: <em>Learn more</em></p>
      </div>

      <div className="bottom-area">
        <div className="bottom-skip">
          <hr className="process-divider" />
          <button
            className="skip-btn"
            onClick={() => setCurrentStep(5)}
          >
            <strong className="skip-bold">Skip to next,</strong> do not include spirit.
          </button>
        </div>
        <div className="bottom-bar-buttons">
          <button className="back-link" onClick={() => setCurrentStep(3)}>
            <ChevronLeft size={16} /> Back
          </button>
          <button
            className="continue-btn"
            disabled={!selectedSpiritCategory}
            onClick={() => setSpiritSubStep('brand')}
          >
            Continue
            <ChevronRight size={18} color={!selectedSpiritCategory ? BRAND_COLOR : '#fff'} />
          </button>
        </div>
      </div>
    </div>
  );

  const renderSpiritBrands = () => {
    const category = spiritCategories.find((c) => c.id === selectedSpiritCategory);
    const brands = spiritBrands[selectedSpiritCategory] || [];

    return (
      <div className="min-h-screen bg-white flex flex-col process-page">
        {renderHeader()}

        <div className="content-area">
          {renderStepIndicator()}

          <h2 className="process-title">{category?.name} Options</h2>
          <p className="process-description">Pick your favorite brand!</p>

          {brands.map((brand) => {
            const isSelected = selectedSpiritBrand === brand.name;
            return (
              <div
                key={brand.name}
                className={`goods-option ${isSelected ? 'selected' : ''}`}
                onClick={() => setSelectedSpiritBrand(brand.name)}
              >
                <span className={`radio-circle ${isSelected ? 'selected' : ''}`} />
                <span className={`goods-name ${isSelected ? 'selected' : ''}`}>{brand.name}</span>
                <span className="goods-price">${brand.price}</span>
              </div>
            );
          })}

          <p className="tbb-note">TBB Compliant Shipping: <em>Learn more</em></p>
        </div>

        <div className="bottom-area">
          <div className="bottom-bar-buttons">
            <button
              className="back-link"
              onClick={() => { setSpiritSubStep('category'); setSelectedSpiritBrand(null); }}
            >
              <ChevronLeft size={16} /> Back
            </button>
            <button
              className="continue-btn"
              disabled={!selectedSpiritBrand}
              onClick={() => setCurrentStep(5)}
            >
              Add to Kit
              <ChevronRight size={18} color={!selectedSpiritBrand ? BRAND_COLOR : '#fff'} />
            </button>
          </div>
        </div>
      </div>
    );
  };

  const renderReview = () => {
    const quantity = quantities[kitQuantityIndex];

    return (
      <div className="min-h-screen bg-white flex flex-col process-page">
        {renderHeader()}

        <div className="content-area">
          {renderStepIndicator()}

          <h2 className="process-title">Review your kit</h2>
          <p className="process-description">Everything look good? Send it to checkout.</p>

          <div className="review-list">

            <div className="review-row">
              <span className="review-label">Quantity</span>
              <span className="review-value">{quantity} kits</span>
            </div>

            <div className="review-row">
              <span className="review-label">Your Product</span>
              {addProduct
                ? <span className="review-value">Added <span className="review-price">+$250</span></span>
                : <span className="review-skipped">Skipped</span>
              }
            </div>

            <div className="review-row">
              <span className="review-label">Shipping</span>
              <span className="review-value">
                {shippingOption === 'me' ? 'Ship to me' : shippingFile ? shippingFile.name : 'Upload list'}
              </span>
            </div>

            <div className="review-row review-row--block">
              <span className="review-label">Premium Goods</span>
              {selectedGoods.length === 0
                ? <span className="review-skipped">Skipped</span>
                : <div className="review-goods-list">
                    {selectedGoods.map((gid) => {
                      const cfg = goodConfigurations[gid] || {};
                      const g = goodsConfig[gid];
                      const parts = [cfg.sleeve || cfg.style, cfg.color, cfg.logoPosition, cfg.decoration].filter(Boolean);
                      return (
                        <div key={gid} className="review-good-item">
                          <span className="review-good-name">{g?.name}</span>
                          {parts.length > 0 && (
                            <span className="review-good-detail">{parts.join(' · ')}</span>
                          )}
                        </div>
                      );
                    })}
                  </div>
              }
            </div>

            <div className="review-row">
              <span className="review-label">Spirit</span>
              {selectedSpiritCategory
                ? <span className="review-value">
                    {spiritCategories.find((c) => c.id === selectedSpiritCategory)?.name}
                    {selectedSpiritBrand ? ` — ${selectedSpiritBrand}` : ''}
                  </span>
                : <span className="review-skipped">Skipped</span>
              }
            </div>

            <div className="review-row review-row--block">
              <span className="review-label">Message</span>
              {messageText.trim()
                ? <span className="review-message-preview">
                    "{messageText.trim().slice(0, 100)}{messageText.trim().length > 100 ? '…' : ''}"
                  </span>
                : <span className="review-skipped">Skipped</span>
              }
            </div>

          </div>
        </div>

        <div className="bottom-area">
          <div className="bottom-bar-buttons">
            <button className="back-link" onClick={() => setCurrentStep(5)}>
              <ChevronLeft size={16} /> Back
            </button>
            <button
              className="continue-btn"
              onClick={() => {
                // TODO: push all selections to WooCommerce cart via Store API
                // then window.location.href = '/checkout'
              }}
            >
              Go to Checkout
              <ChevronRight size={18} color="#fff" />
            </button>
          </div>
        </div>
      </div>
    );
  };

  const renderMessage = () => {
    const canReview = messageText.trim().length > 0 && messageConfirmed;

    return (
      <div className="min-h-screen bg-white flex flex-col process-page">
        {renderHeader()}

        <div className="content-area">
          {renderStepIndicator()}

          <h2 className="process-title" style={{ color: 'var(--color-brand)' }}>Your message</h2>
          <p className="process-description">Include your custom message printed in every kit.</p>

          <textarea
            className="message-textarea"
            placeholder="type your message here..."
            value={messageText}
            onChange={(e) => {
              setMessageText(e.target.value);
              if (!e.target.value.trim()) setMessageConfirmed(false);
            }}
          />

          <div
            className="message-confirm-row"
            onClick={() => setMessageConfirmed((v) => !v)}
          >
            <span className="message-confirm-label">I confirm this message</span>
            <span className={`radio-circle ${messageConfirmed ? 'selected' : ''}`} />
          </div>
        </div>

        <div className="bottom-area">
          <div className="bottom-skip">
            <hr className="process-divider" />
            <button
              className="skip-btn"
              onClick={() => setCurrentStep(6)}
            >
              <strong className="skip-bold">Skip,</strong> do not include a message.
            </button>
          </div>
          <div className="bottom-bar-buttons">
            <button className="back-link" onClick={() => setCurrentStep(4)}>
              <ChevronLeft size={16} /> Back
            </button>
            <button
              className="continue-btn"
              disabled={!canReview}
              onClick={() => setCurrentStep(6)}
            >
              Continue
              <ChevronRight size={18} color={!canReview ? BRAND_COLOR : '#fff'} />
            </button>
          </div>
        </div>
      </div>
    );
  };

  if (currentStep === -1) {
    return renderWelcome();
  }

  if (currentStep === 0) {
    return renderAddProduct();
  }

  if (currentStep === 1) {
    return renderShipping();
  }

  if (currentStep === 3) {
    if (configuringGood) return renderGoodConfig(configuringGood);
    return renderGoods();
  }

  if (currentStep === 4) {
    if (spiritSubStep === 'brand') return renderSpiritBrands();
    return renderSpirits();
  }

  if (currentStep === 5) {
    return renderMessage();
  }

  if (currentStep === 6) {
    return renderReview();
  }

  return null;
}
