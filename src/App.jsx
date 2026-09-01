import React, { useState, useEffect } from 'react';
import { ChevronRight, ChevronLeft, Upload, Wine } from 'lucide-react';

const BRAND_COLOR = 'var(--color-brand)';

// Per-good dropdown config — TODO: confirm all options with client before launch
// TODO: swap all placeholder images for real per-variant product images once assets are ready
const goodsConfig = {
  'tee-short': {
    name: 'Short Sleeve',
    image: '/tee-preview.jpg',
    dropdowns: [
      { id: 'color', label: 'COLOR', options: ['Black', 'Dust', 'White'], key: 'color' },
    ],
  },
  'tee-long': {
    name: 'Long Sleeve',
    image: null,
    dropdowns: [
      { id: 'color', label: 'COLOR', options: ['Black', 'Dust', 'White'], key: 'color' },
    ],
  },
  hoodie: {
    name: 'Hoodie',
    image: null,
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Pullover', 'Zip-Up'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Grey', 'Navy'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['1 Color Print', '4 Color Print', '1 Color Embroider'], key: 'decoration' },
    ],
  },
  cap: {
    name: 'Cap',
    image: null,
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Structured', 'Unstructured'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Navy', 'Grey'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['1 Color Print', '1 Color Embroider'], key: 'decoration' }, // TODO: confirm options
    ],
  },
  tote: {
    name: 'Tote',
    image: null,
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Standard', 'Large'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Natural', 'Black'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['1 Color Print', '4 Color Print', '1 Color Embroider'], key: 'decoration' },
    ],
  },
  bottle: {
    name: 'Tumbler',
    image: null,
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['16oz', '20oz'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Silver', 'White'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['Laser Engrave', '1 Color Print'], key: 'decoration' }, // TODO: confirm options
    ],
  },
  journal: {
    name: 'Journal',
    image: null,
    dropdowns: [
      { id: 'style', label: 'STYLE', options: ['Hardcover', 'Softcover'], key: 'style' }, // TODO: confirm options
      { id: 'color', label: 'COLOR', options: ['Black', 'Brown', 'Navy'], key: 'color' }, // TODO: confirm options
      { id: 'logoPosition', label: 'LOGO POSITION', options: ['Center', 'Left'], key: 'logoPosition' },
      { id: 'decoration', label: 'DECORATION', options: ['Deboss', 'Laser Engrave', '1 Color Print'], key: 'decoration' }, // TODO: confirm options
    ],
  },
};

const spirits = [
  { id: 'kettle-one',          wcId: 5252, name: 'Kettle One',           type: 'Vodka',                price: 30 },
  { id: 'manojo',              wcId: 5253, name: 'Manojo',               type: 'Mezcal',               price: 48 },
  { id: 'jonnie-walker-black', wcId: 5255, name: 'Johnnie Walker Black', type: 'Blended Scotch',       price: 36 },
  { id: 'jameson',             wcId: 5256, name: 'Jameson',              type: 'Irish Whiskey',        price: 30 },
  { id: 'four-roses',          wcId: 5258, name: 'Four Roses',           type: 'Small Batch Bourbon',  price: 60 },
  { id: 'casamigos',           wcId: 5259, name: 'Casamigos',            type: 'Tequila Blanco',       price: 42 },
  { id: 'maestro-dobel',       wcId: 5260, name: 'Maestro Dobel',        type: 'Tequila Blanco',       price: 48 },
];

const goodsWcUrls = {
  'tee-short': 'https://chainmail.store/product/tee/',
  'tee-long':  'https://chainmail.store/product/long-sleeve-tee/',
  hoodie:      'https://chainmail.store/product/hoodie/',
  cap:         'https://chainmail.store/product/cap/',
  tote:        'https://chainmail.store/product/tote/',
  bottle:      'https://chainmail.store/product/tumbler/',
  journal:     'https://chainmail.store/product/journal/',
};

const premiumGoods = [
  { id: 'tee-short', name: 'Short Sleeve', price: 20, icon: '/icon-tee-short.svg' },
  { id: 'tee-long',  name: 'Long Sleeve',  price: 20, icon: '/icon-tee-long.svg'  },
  { id: 'hoodie',    name: 'Hoodie',            price: 60, icon: '/icon-hoodie.svg'   },
  { id: 'cap',       name: 'Cap',               price: 15, icon: '/icon-cap.svg'      },
  { id: 'tote',      name: 'Tote',              price: 25, icon: '/icon-tote.svg'     },
  { id: 'bottle',    name: 'Tumbler',           price: 30, icon: '/icon-bottle.svg'   },
  { id: 'journal',   name: 'Journal',           price: 20, icon: '/icon-journal.svg'  },
];

export default function KitBuilder() {
  const [currentStep, setCurrentStep] = useState(-1);
  const [kitQuantityIndex, setKitQuantityIndex] = useState(0);
  const [addProduct, setAddProduct] = useState(false);
  const [confirmSize, setConfirmSize] = useState(false);
  const [confirmPrice, setConfirmPrice] = useState(false);
  const [confirmWeight, setConfirmWeight] = useState(false);
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
  const [selectedSpirit, setSelectedSpirit] = useState(null);
  const [messageText, setMessageText] = useState('');
  const [messageConfirmed, setMessageConfirmed] = useState(false);
  const [showExitConfirm, setShowExitConfirm] = useState(false);
  const [regulatedSubstance, setRegulatedSubstance] = useState(false);
  const [showRegulatedForm, setShowRegulatedForm] = useState(false);
  const [regulatedName, setRegulatedName] = useState('');
  const [regulatedEmail, setRegulatedEmail] = useState('');
  const [prices, setPrices] = useState(null);
  const [pricesError, setPricesError] = useState(false);

  const quantities = [24, 48, 72, 96, '120+'];

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const isResume = params.get('resume') === 'goods';
    const isBack   = params.get('back')   === 'goods';
    if (isResume || isBack) {
      try {
        const saved = JSON.parse(localStorage.getItem('chainmail_kit_state') || '{}');
        if (saved.kitQuantityIndex !== undefined) setKitQuantityIndex(saved.kitQuantityIndex);
        if (saved.shippingOption) setShippingOption(saved.shippingOption);
        if (saved.addProduct !== undefined) setAddProduct(saved.addProduct);
        if (saved.confirmSize !== undefined) setConfirmSize(saved.confirmSize);
        if (saved.selectedGoods) {
          const goods = isResume && saved.pendingGood
            ? [...new Set([...saved.selectedGoods, saved.pendingGood])]
            : saved.selectedGoods;
          setSelectedGoods(goods);
        }
        const baseCfg = saved.goodConfigurations || {};
        const wcCaptured = {};
        ['tee-short','tee-long','hoodie','cap','tote','bottle','journal'].forEach(id => {
          const raw = localStorage.getItem('cm_good_' + id);
          if (raw) { try { wcCaptured[id] = JSON.parse(raw); } catch(e) {} }
          localStorage.removeItem('cm_good_' + id);
        });
        // URL params carry cross-origin captured data from WC product pages
        if (isResume) {
          const urlGid    = params.get('gid');
          const urlImg    = params.get('img');
          const urlDetail = params.get('detail');
          console.log('cm-resume: gid=' + urlGid + ' img=' + (urlImg ? urlImg.split('/').pop() : 'NONE') + ' detail=' + urlDetail);
          if (urlGid && (urlImg || urlDetail)) {
            wcCaptured[urlGid] = { image: urlImg || '', detail: urlDetail || '' };
          }
        }
        // Canvas-composited images (product + logo) override plain product images
        ['tee-short','tee-long','hoodie','cap','tote','bottle','journal'].forEach(id => {
          const composited = localStorage.getItem('cm_composited_' + id);
          if (composited) {
            wcCaptured[id] = { ...(wcCaptured[id] || {}), image: composited };
            localStorage.removeItem('cm_composited_' + id);
          }
        });
        console.log('cm-resume: wcCaptured=', JSON.stringify(Object.keys(wcCaptured)));
        setGoodConfigurations({ ...baseCfg, ...wcCaptured });
        localStorage.removeItem('chainmail_kit_state');
      } catch (e) {}
      setCurrentStep(3);
      window.history.replaceState({}, '', window.location.pathname);
    }
  }, []);

  useEffect(() => {
    async function fetchPrices() {
      try {
        const ids = spirits.map(s => s.wcId).join(',');
        const res = await fetch(
          `https://chainmail.store/wp-json/wc/store/v1/products?include=${ids}&per_page=10`
        );
        if (!res.ok) throw new Error();
        const data = await res.json();
        const spiritPrices = {};
        const spiritImages = {};
        data.forEach(item => {
          const s = spirits.find(s => s.wcId === item.id);
          if (s) {
            const minor = item.prices.currency_minor_unit ?? 2;
            spiritPrices[s.id] = parseInt(item.prices.price, 10) / Math.pow(10, minor);
            if (item.images?.[0]?.src) spiritImages[s.id] = item.images[0].src;
          }
        });
        setPrices({ spirits: spiritPrices, spiritImages });
      } catch {
        setPricesError(true);
      }
    }
    fetchPrices();
  }, []);

const handleQuantitySelect = (idx) => {
    setKitQuantityIndex(idx);
  };

  const handleStartKit = () => {
    if (quantities[kitQuantityIndex] === '120+') {
      window.location.href = 'mailto:sales@chainmail.com?subject=Large Order - 120+ Kits';
      return;
    }
    setCurrentStep(0);
  };

  const renderWelcome = () => (
    <div className="min-h-screen bg-white flex flex-col welcome-page">
      {/* Header */}
      <header className="bg-white border-b border-gray-200 px-5 py-3 flex items-center justify-between">
        <img src="/chainmail-logo.png" alt="chainmail" className="logo" style={{ marginLeft: '12px' }} />
        <button onClick={() => setShowExitConfirm(true)} aria-label="Exit" className="exit-btn">✕</button>
      </header>

      {/* Content */}
      <div className="welcome-content" style={{ gap: '32px' }}>
        <div className="welcome-body">
          <h1>Welcome.</h1>
          <p>You've made it to the Kit Builder. You'll be guided through a series of easy questions to make this as smooth as possible!</p>
          <p>Each product category has been pre-vetted for your convenience and trust.</p>
        </div>

        <h2 className="welcome-qty-heading">How many kits we talking about?</h2>

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

        {/* Leave */}
        <button
          onClick={() => setShowExitConfirm(true)}
          className="mt-8 font-semibold flex items-center gap-1 hover:opacity-70 back-btn"
        >
          <ChevronLeft size={18} /> Leave Kit Builder
        </button>
      </div>
    </div>
  );

  const chainStep = [0,1,2,2,3,4,5,5][currentStep + 1] ?? 0;

  const renderStepIndicator = () => (
    <div className="step-indicator-wrap">
      <img
        src={`/chain_${chainStep}.svg?v=2`}
        alt=""
        className="step-chain-bg"
      />
    </div>
  );

  const renderHeader = () => (
    <header className="bg-white border-b border-gray-200 px-5 py-3 flex items-center justify-between">
      <img src="/chainmail-logo.png" alt="chainmail" className="logo" style={{ marginLeft: '12px' }} />
      <button onClick={() => setShowExitConfirm(true)} aria-label="Exit" className="exit-btn">✕</button>
    </header>
  );

  const renderAddProduct = () => (
    <>
    {showRegulatedForm && (
      <div style={{
        position: 'fixed', inset: 0, background: 'rgba(0,0,0,0.5)',
        zIndex: 9999, display: 'flex', alignItems: 'center', justifyContent: 'center',
        padding: '24px'
      }}>
        <div style={{
          background: '#fff', borderRadius: '8px', padding: '32px 24px',
          width: '100%', maxWidth: '400px'
        }}>
          <h3 style={{ fontSize: '20px', fontWeight: '700', marginBottom: '8px' }}>
            Regulated Substance
          </h3>
          <p style={{ fontSize: '14px', color: '#555', marginBottom: '24px' }}>
            We'll reach out to confirm compliance details. Please provide your contact info.
          </p>
          <input
            type="text"
            placeholder="Your name"
            value={regulatedName}
            onChange={e => setRegulatedName(e.target.value)}
            style={{
              display: 'block', width: '100%', border: '1px solid #ddd',
              borderRadius: '4px', padding: '12px', fontSize: '15px',
              marginBottom: '12px', boxSizing: 'border-box'
            }}
          />
          <input
            type="email"
            placeholder="Your email"
            value={regulatedEmail}
            onChange={e => setRegulatedEmail(e.target.value)}
            style={{
              display: 'block', width: '100%', border: '1px solid #ddd',
              borderRadius: '4px', padding: '12px', fontSize: '15px',
              marginBottom: '24px', boxSizing: 'border-box'
            }}
          />
          <div style={{ display: 'flex', gap: '12px' }}>
            <button
              onClick={() => {
                setRegulatedSubstance(false);
                setRegulatedName('');
                setRegulatedEmail('');
                setShowRegulatedForm(false);
              }}
              style={{
                flex: 1, padding: '12px', border: '2px solid #6A449B',
                borderRadius: '4px', background: '#fff', color: '#6A449B',
                fontSize: '15px', fontWeight: '600', cursor: 'pointer'
              }}
            >
              Cancel
            </button>
            <button
              onClick={() => setShowRegulatedForm(false)}
              disabled={!regulatedName || !regulatedEmail}
              style={{
                flex: 1, padding: '12px', border: 'none',
                borderRadius: '4px',
                background: regulatedName && regulatedEmail ? '#6A449B' : '#c4aee0',
                color: '#fff', fontSize: '15px', fontWeight: '700', cursor: 'pointer'
              }}
            >
              Submit
            </button>
          </div>
        </div>
      </div>
    )}
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
        </label>

        {/* Checkboxes — visible only after Yes is selected */}
        {addProduct && (
          <>
            <div className="checkbox-group">
              <label className="process-checkbox" onClick={() => setConfirmSize(!confirmSize)}>
                <span className={`checkbox-circle ${confirmSize ? 'selected' : ''}`} />
                <span className="checkbox-text">
                  Please confirm your item is less than 12″ x 12″ x 8″
                </span>
              </label>

              <label className="process-checkbox" onClick={() => setConfirmPrice(!confirmPrice)}>
                <span className={`checkbox-circle ${confirmPrice ? 'selected' : ''}`} />
                <span className="checkbox-text">
                  I agree to a $1 per item service fee
                </span>
              </label>

              <label className="process-checkbox" onClick={() => setConfirmWeight(!confirmWeight)}>
                <span className={`checkbox-circle ${confirmWeight ? 'selected' : ''}`} />
                <span className="checkbox-text">
                  My product is under 4 lbs
                </span>
              </label>

              <div className="regulated-block">
                <label className="process-checkbox" onClick={() => {
                  const next = !regulatedSubstance;
                  setRegulatedSubstance(next);
                  if (next) setShowRegulatedForm(true);
                }}>
                  <span className={`checkbox-circle ${regulatedSubstance ? 'selected' : ''}`} />
                  <span className="checkbox-text">
                    My item contains Prohibited or Restricted Items.
                  </span>
                </label>

                <p className="policy-link-note">
                  <a href="https://chainmail.store/prohibited-and-restricted-shipping-items-policy/" className="policy-link" target="_blank" rel="noreferrer">
                    Click to see Prohibited / Restricted Items Policy
                  </a>
                </p>
              </div>
            </div>

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
              setConfirmPrice(false);
              setConfirmWeight(false);
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
            disabled={!addProduct || !confirmSize || !confirmPrice || !confirmWeight}
            onClick={() => setCurrentStep(1)}
          >
            Continue
            <ChevronRight size={18} color={!addProduct || !confirmSize || !confirmPrice || !confirmWeight ? BRAND_COLOR : '#fff'} />
          </button>
        </div>
      </div>
    </div>
    </>
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
    const canContinue = !!shippingOption;

    return (
      <div className="min-h-screen bg-white flex flex-col process-page">
        {renderHeader()}

        <div className="content-area">
          {renderStepIndicator()}

          <h2 className="process-title" style={{ color: 'var(--color-brand)' }}>Shipping</h2>

          {/*
            WooCommerce cart behavior (not yet implemented):
            - If list uploaded: parse list and show in cart line items
            - If no list (ship to me): auto-populate size distribution from kit quantity
              e.g. 24 kits → 30% XL / 50% Large / 20% Medium — send to cart for editing
          */}

          <p className="process-description">
            Upload your recipient list or we can send it direct to you.
          </p>

          {/* Ship to my list */}
          <label className="process-option" style={{ marginBottom: '16px' }} onClick={() => setShippingOption('list')}>
            <span className={`radio-circle ${shippingOption === 'list' ? 'selected' : ''}`} />
            <span className="option-text">
              <strong style={{ fontSize: '24px', color: shippingOption === 'list' ? 'var(--color-brand)' : '#000' }}>Ship to my list</strong>
            </span>
          </label>

        {/* Download template button */}
        <div className="shipping-template-band">
          <div className="shipping-template-band-inner">
            <a
              className="download-template-btn"
              href="https://chainmail.store/wp-content/uploads/2026/06/CHAINMAIL-SHIPPING-TEMPLATE.xlsx"
              download
            >
              Download shipping list template
            </a>
          </div>
        </div>

        {/* Upload area */}
        <div className="shipping-upload-band">
          <div className="shipping-upload-band-inner">
            <div
              className={`upload-dropzone ${shippingFile ? 'has-file' : ''}`}
              onDragOver={(e) => e.preventDefault()}
              onDrop={handleFileDrop}
              onClick={() => document.getElementById('shipping-file-input').click()}
            >
              <Upload size={36} color="#6A449B" />
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

          <p style={{ fontSize: '14px', color: 'var(--color-brand)', lineHeight: '1.5', margin: '16px 0' }}>
            Please note, a COMPLETED Recipient List, including all sizes, is required to start production on your order.
          </p>

          {/* Ship to me */}
          <label className="process-option" style={{ marginBottom: '16px' }} onClick={() => setShippingOption('me')}>
            <span className={`radio-circle ${shippingOption === 'me' ? 'selected' : ''}`} />
            <span className="option-text">
              <strong style={{ fontSize: '24px', color: shippingOption === 'me' ? 'var(--color-brand)' : '#000' }}>Ship to me</strong>
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
              <Upload size={28} color="#6A449B" />
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

          <h2 className="process-title" style={{ color: 'var(--color-brand)' }}>Add premium goods</h2>
          <p className="process-description">
            Each kit includes space to hold one of every item customized with your logo. 
          </p>
          <div className="goods-list">
          {premiumGoods.map((good) => {
            const isSelected = selectedGoods.includes(good.id);
            return (
              <div
                key={good.id}
                className={`goods-option ${isSelected ? 'selected' : ''}`}
                onClick={() => {
                  if (isSelected) {
                    setSelectedGoods(selectedGoods.filter(id => id !== good.id));
                    return;
                  }
                  if (selectedGoods.length >= 3) return;
                  const wcUrl = goodsWcUrls[good.id];
                  if (wcUrl) {
                    const qty = quantities[kitQuantityIndex];
                    localStorage.setItem('chainmail_kit_state', JSON.stringify({
                      kitQuantityIndex,
                      shippingOption,
                      addProduct,
                      confirmSize,
                      selectedGoods,
                      goodConfigurations,
                      pendingGood: good.id,
                    }));
                    window.location.href = `${wcUrl}?quantity=${qty}&kit=1&gid=${good.id}`;
                  } else {
                    setConfiguringGood(good.id);
                    setShowPreview(false);
                  }
                }}
              >
                <span className={`radio-circle ${isSelected ? 'selected' : ''}`} />
                <img src={good.icon} alt={good.name} className={`goods-icon ${isSelected ? 'selected' : ''}`} />
                <span className={`goods-name ${isSelected ? 'selected' : ''}`}>{good.name}</span>
              </div>
            );
          })}
          </div>
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

        <h2 className="process-title" style={{ color: 'var(--color-brand)' }}>Add some booze</h2>
        <p className="process-description">Select one premium spirit (750mL)</p>
        {pricesError && (
          <p style={{ color: '#c00', fontSize: '13px', marginBottom: '8px' }}>
            Couldn't load pricing — please refresh.
          </p>
        )}

        <div className="spirits-list">
          {spirits.map((spirit) => {
            const isSelected = selectedSpirit === spirit.id;
            return (
              <div
                key={spirit.id}
                className={`goods-option ${isSelected ? 'selected' : ''}`}
                onClick={() => setSelectedSpirit(spirit.id)}
              >
                <span className={`radio-circle ${isSelected ? 'selected' : ''}`} />
                <span className="spirit-text">
                  <span className="spirit-top-row">
                    <span className={`goods-name ${isSelected ? 'selected' : ''}`}>{spirit.name}</span>
                    <span className={`goods-price ${isSelected ? 'selected' : ''}`}>
                      {pricesError ? '—' : prices ? `$${prices.spirits[spirit.id]}` : '…'}
                    </span>
                  </span>
                  <span className="goods-subtitle">{spirit.type}</span>
                </span>
              </div>
            );
          })}
        </div>

      </div>

      <div className="bottom-area">
        <div className="bottom-skip">
          <p className="tbb-note">TBB Compliant Shipping: <em>Learn more</em></p>
          <hr className="process-divider" />
          <button className="skip-btn" onClick={() => setCurrentStep(5)}>
            <strong className="skip-bold">Skip to next,</strong> do not include spirit.
          </button>
        </div>
        <div className="bottom-bar-buttons">
          <button className="back-link" onClick={() => setCurrentStep(3)}>
            <ChevronLeft size={16} /> Back
          </button>
          <button
            className="continue-btn"
            disabled={!selectedSpirit}
            onClick={() => setCurrentStep(5)}
          >
            Continue
            <ChevronRight size={18} color={!selectedSpirit ? BRAND_COLOR : '#fff'} />
          </button>
        </div>
      </div>
    </div>
  );

  const renderReview = () => {
    const quantity = quantities[kitQuantityIndex];
    const numQty = Number.isInteger(quantity) ? quantity : 24;

    const goodsPriceTotal = selectedGoods.reduce((sum, gid) => {
      const g = premiumGoods.find((p) => p.id === gid);
      return sum + (g ? g.price : 0);
    }, 0);
    const spiritObj = selectedSpirit ? spirits.find((s) => s.id === selectedSpirit) : null;
    const spiritPrice = spiritObj ? (prices?.spirits[spiritObj.id] ?? 0) : 0;
    const pricePerKit = goodsPriceTotal + spiritPrice + (addProduct ? 1 : 0);

    const inclusions = [];
    selectedGoods.forEach((gid) => {
      const cfg = goodConfigurations[gid] || {};
      const g = goodsConfig[gid];
      const good = premiumGoods.find((p) => p.id === gid);
      const parts = [cfg.sleeve || cfg.style, cfg.color, cfg.decoration].filter(Boolean);
      const detail = cfg.detail || (parts.length ? parts.join(' | ') : '');
      const image = cfg.image || g?.image || null;
      inclusions.push({
        name: g?.name || gid,
        detail,
        price: good?.price || 0,
        image,
        icon: good?.icon || null,
        logoDataUrl: cfg.logoDataUrl || null,
      });
    });
    if (spiritObj) {
      inclusions.push({
        name: `Spirits – ${spiritObj.name}`,
        detail: spiritObj.type,
        price: prices?.spirits[spiritObj.id] ?? null,
        image: prices?.spiritImages?.[spiritObj.id] || null,
        isSpirit: true,
      });
    }

    return (
      <div className="min-h-screen bg-white flex flex-col process-page">
        {renderHeader()}

        <div className="content-area">
          {renderStepIndicator()}

          <h2 className="process-title" style={{ color: 'var(--color-brand)' }}>Review your kit</h2>
          <p className="process-description">Take a look. Ready to approve?</p>

          <div className="review-stats-row">
            <div className="review-stat">
              <span className="review-stat-label">Total Kits:</span>
              <span className="review-stat-num">{quantity}</span>
            </div>
            <div className="review-stat-sep" />
            <div className="review-stat">
              <span className="review-stat-label">Price Per Kit:</span>
              <span className="review-stat-num">
                {selectedSpirit && pricesError ? 'Error' : selectedSpirit && !prices ? '…' : `$${pricePerKit}`}
              </span>
            </div>
          </div>

          {addProduct && (
            <div className="review-sendin">
              <div className="review-sendin-header">
                <span className="review-sendin-title">Your send in</span>
                <span className="review-sendin-price">${numQty}</span>
              </div>
              <p className="review-sendin-desc">
                You'll receive an email inbound form shortly to the email address provided in Checkout.
              </p>
            </div>
          )}

          {inclusions.length > 0 && (
            <div className="review-inclusions">
              <h3 className="review-inclusions-title">Your inclusions</h3>
              <p className="review-inclusions-desc">
                What you selected / customized during the Kit Builder. Pricing is per item.
              </p>
              <div className="review-inclusions-list">
                {inclusions.map((item, i) => (
                  <div key={i} className="review-inclusion-item">
                    {item.image
                      ? (
                        <div className="review-inclusion-thumb review-inclusion-img-wrap">
                          <img src={item.image} alt={item.name} className="review-inclusion-img" />
                          {item.logoDataUrl && (
                            <img src={item.logoDataUrl} alt="" className="review-inclusion-logo-overlay" />
                          )}
                        </div>
                      )
                      : <div className="review-inclusion-thumb review-inclusion-icon">
                          {item.isSpirit
                            ? <Wine size={28} color="#6A449B" />
                            : item.icon
                              ? <img src={item.icon} alt={item.name} style={{ width: 28, height: 28 }} />
                              : null
                          }
                        </div>
                    }
                    <div className="review-inclusion-info">
                      <span className="review-inclusion-name">{item.name}</span>
                      {item.detail && (
                        <span className="review-inclusion-detail">{item.detail}</span>
                      )}
                    </div>
                    <span className="review-inclusion-price">
                      {item.price !== null ? `$${item.price}` : pricesError ? '—' : '…'}
                    </span>
                  </div>
                ))}
              </div>
            </div>
          )}

          <div className="review-footer-row">
            <span className="review-footer-label">Shipping</span>
            <span className="review-footer-value">
              {shippingOption === 'me' ? 'Ship to me' : shippingFile ? shippingFile.name : 'List'}
            </span>
          </div>

          {messageText.trim() && (
            <div className="review-footer-row">
              <span className="review-footer-label">Message</span>
              <span className="review-footer-value review-footer-italic">
                "{messageText.trim().slice(0, 80)}{messageText.trim().length > 80 ? '…' : ''}"
              </span>
            </div>
          )}
        </div>

        <div className="bottom-area">
          <div className="bottom-bar-buttons">
            <button className="back-link" onClick={() => setCurrentStep(5)}>
              <ChevronLeft size={16} /> Back
            </button>
            <button
              className="continue-btn"
              onClick={() => {
                const qty = quantities[kitQuantityIndex];
                const base = 'https://chainmail.store/checkout/';
                const params = new URLSearchParams();
                const spiritObj = spirits.find(s => s.id === selectedSpirit);
                if (spiritObj) {
                  params.set('cm_spirit_id', spiritObj.wcId);
                  params.set('cm_spirit_qty', qty);
                }
                if (addProduct) params.set('cm_addfee', qty);
                const qs = params.toString();
                window.location.href = qs ? `${base}?${qs}` : base;
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

        <div className="content-area" style={{ overflow: 'hidden', paddingBottom: 'calc(310px + env(safe-area-inset-bottom))' }}>
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

        </div>

        <div className="bottom-area">
          <div className="bottom-skip">
            <div
              className="message-confirm-row"
              onClick={() => setMessageConfirmed((v) => !v)}
            >
              <span className="message-confirm-label">I confirm this message</span>
              <span className={`radio-circle ${messageConfirmed ? 'selected' : ''}`} />
            </div>
            <p style={{ fontSize: '14px', color: 'var(--color-brand)', textAlign: 'center', lineHeight: '1.6', margin: '12px 0' }}>
              All messages are printed on an external note card that will be included with your kit.<br />
              <strong>If left empty, we will not include a card.</strong>
            </p>
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

  const renderExitConfirm = () => (
    <div className="exit-confirm-overlay" onClick={() => setShowExitConfirm(false)}>
      <div className="exit-confirm-modal" onClick={(e) => e.stopPropagation()}>
        <p>Are you sure you want to leave the Kit Builder?</p>
        <div className="exit-confirm-buttons">
          <button
            className="exit-confirm-btn"
            onClick={() => { window.location.href = 'https://chainmail.store/'; }}
          >
            Confirm
          </button>
          <button className="exit-cancel-btn" onClick={() => setShowExitConfirm(false)}>
            Cancel
          </button>
        </div>
      </div>
    </div>
  );

  const screen = (() => {
    if (currentStep === -1) return renderWelcome();
    if (currentStep === 0) return renderAddProduct();
    if (currentStep === 1) return renderShipping();
    if (currentStep === 3) {
      if (configuringGood) return renderGoodConfig(configuringGood);
      return renderGoods();
    }
    if (currentStep === 4) return renderSpirits();
    if (currentStep === 5) return renderMessage();
    if (currentStep === 6) return renderReview();
    return null;
  })();

  return (
    <>
      {screen}
      {showExitConfirm && renderExitConfirm()}
    </>
  );
}
