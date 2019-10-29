
; /* Start:"a:4:{s:4:"full";s:93:"/local/templates/us_2018/components/bitrix/catalog.element/.default/script.js?157234198199818";s:6:"source";s:77:"/local/templates/us_2018/components/bitrix/catalog.element/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
(function(window){
	'use strict';

	if (window.JCCatalogElement)
		return;

	var BasketButton = function(params)
	{
		BasketButton.superclass.constructor.apply(this, arguments);
		this.buttonNode = BX.create('SPAN', {
			props: {className: params.className ? params.className : 'btn btn-default btn-buy btn-sm', id: this.id},
			style: typeof params.style === 'object' ? params.style : {},
			text: params.text,
			events: this.contextEvents
		});

		if (BX.browser.IsIE())
		{
			this.buttonNode.setAttribute('hideFocus', 'hidefocus');
		}
	};
	BX.extend(BasketButton, BX.PopupWindowButton);

	window.JCCatalogElement = function(arParams)
	{
		this.productType = 0;

		this.config = {
			useCatalog: true,
			showQuantity: true,
			showPrice: true,
			showAbsent: true,
			showOldPrice: false,
			showPercent: false,
			showSkuProps: false,
			showOfferGroup: false,
			useCompare: false,
			useStickers: false,
			useSubscribe: false,
			usePopup: false,
			useMagnifier: false,
			usePriceRanges: false,
			basketAction: ['BUY'],
			showClosePopup: false,
			templateTheme: '',
			showSlider: false,
			sliderInterval: 5000,
			useEnhancedEcommerce: false,
			dataLayerName: 'dataLayer',
			brandProperty: false,
			alt: '',
			title: '',
			magnifierZoomPercent: 200
		};

		this.checkQuantity = false;
		this.maxQuantity = 0;
		this.minQuantity = 0;
		this.stepQuantity = 1;
		this.isDblQuantity = false;
		this.canBuy = true;
		this.isGift = false;
		this.canSubscription = true;
		this.currentIsSet = false;
		this.updateViewedCount = false;

		this.currentPriceMode = '';
		this.currentPrices = [];
		this.currentPriceSelected = 0;
		this.currentQuantityRanges = [];
		this.currentQuantityRangeSelected = 0;

		this.precision = 6;
		this.precisionFactor = Math.pow(10, this.precision);

		this.visual = {};
		this.basketMode = '';
		this.product = {
			checkQuantity: false,
			maxQuantity: 0,
			stepQuantity: 1,
			startQuantity: 1,
			isDblQuantity: false,
			canBuy: true,
			canSubscription: true,
			name: '',
			pict: {},
			id: 0,
			addUrl: '',
			buyUrl: '',
			slider: {},
			sliderCount: 0,
			useSlider: false,
			sliderPict: []
		};
		this.mess = {};

		this.basketData = {
			useProps: false,
			emptyProps: false,
			quantity: 'quantity',
			props: 'prop',
			basketUrl: '',
			sku_props: '',
			sku_props_var: 'basket_props',
			add_url: '',
			buy_url: ''
		};
		this.compareData = {
			compareUrl: '',
			compareDeleteUrl: '',
			comparePath: ''
		};

		this.defaultPict = {
			preview: null,
			detail: null
		};

		this.offers = [];
		this.offerNum = 0;
		this.treeProps = [];
		this.selectedValues = {};

		this.mouseTimer = null;
		this.isTouchDevice = BX.hasClass(document.documentElement, 'bx-touch');
		this.touch = null;
		this.slider = {
			interval: null,
			progress: null,
			paused: null,
			controls: []
		};

		this.quantityDelay = null;
		this.quantityTimer = null;

		this.obProduct = null;
		this.obQuantity = null;
		this.obQuantityUp = null;
		this.obQuantityDown = null;
		this.obStoreList = null;
		this.obPrice = {
			price: null,
			full: null,
			discount: null,
			percent: null,
			total: null
		};
		this.obTree = null;
		this.obPriceRanges = null;
		this.obBuyBtn = null;
		this.obAddToBasketBtn = null;
		this.obBasketActions = null;
		this.obNotAvail = null;
		this.obSubscribe = null;
		this.obSkuProps = null;
		this.obMainSkuProps = null;
		this.obBigSlider = null;
		this.obMeasure = null;
		this.obQuantityLimit = {
			all: null,
			value: null
		};
		this.obCompare = null;
		this.obTabsPanel = null;

		this.node = {};
		// top panel small card
		this.smallCardNodes = {};

		this.magnify = {
			enabled: false,
			obBigImg: null,
			obBigSlider: null,
			height: 0,
			width: 0,
			timer: 0
		};
		this.currentImg = {
			id: 0,
			src: '',
			width: 0,
			height: 0
		};
		this.viewedCounter = {
			path: '/bitrix/components/bitrix/catalog.element/ajax.php',
			params: {
				AJAX: 'Y',
				SITE_ID: '',
				PRODUCT_ID: 0,
				PARENT_ID: 0
			}
		};

		this.obPopupWin = null;
		this.basketUrl = '';
		this.basketParams = {};

		this.errorCode = 0;

		if (typeof arParams === 'object')
		{
			this.params = arParams;
			this.initConfig();

			if (this.params.MESS)
			{
				this.mess = this.params.MESS;
			}

			switch (this.productType)
			{
				case 0: // no catalog
				case 1: // product
				case 2: // set
					this.initProductData();
					break;
				case 3: // sku
					this.initOffersData();
					break;
				default:
					this.errorCode = -1;
			}

			this.initBasketData();
			this.initCompareData();
		}
		if (this.errorCode === 0)
		{
			BX.ready(BX.delegate(this.init, this));
		} else {
			this.obProduct = BX(this.visual.ID);
			this.obBigSlider = BX(this.visual.BIG_SLIDER_ID);
			this.node.imageContainer = this.getEntity(this.obProduct, 'images-container');
			this.node.imageSliderBlock = this.getEntity(this.obProduct, 'images-slider-block');
			this.node.sliderProgressBar = this.getEntity(this.obProduct, 'slider-progress-bar');
			this.node.sliderControlLeft = this.getEntity(this.obBigSlider, 'slider-control-left');
			this.node.sliderControlRight = this.getEntity(this.obBigSlider, 'slider-control-right');

			this.product.sliderCount = parseInt(this.params.PRODUCT.SLIDER_COUNT, 10) || 0;
			this.product.slider = {
				ID: this.visual.SLIDER_CONT_ID,
				CONT: BX(this.visual.SLIDER_CONT_ID),
				COUNT: this.product.sliderCount
			};

			this.product.slider.ITEMS = this.getEntities(this.product.slider.CONT, 'slider-control');
			var j = 0;
			for (j = 0; j < this.product.slider.ITEMS.length; j++)
			{
				BX.bind(this.product.slider.ITEMS[j], 'mouseenter', BX.delegate(this.onSliderControlHover, this));
				BX.bind(this.product.slider.ITEMS[j], 'mouseleave', BX.delegate(this.onSliderControlLeave, this));
				BX.bind(this.product.slider.ITEMS[j], 'click', BX.delegate(this.selectSliderImg, this));
			}
			this.product.sliderPict = this.params.PRODUCT.SLIDER;
			// debugger;
			this.setCurrentImg(this.product.sliderPict[0], true, true);
			this.checkSliderControls(this.product.sliderCount);
			this.initPopup();

			if (this.config.showSlider && !this.isTouchDevice)
			{
				BX.bind(this.obBigSlider, 'mouseenter', BX.proxy(this.stopSlider, this));
				BX.bind(this.obBigSlider, 'mouseleave', BX.proxy(this.cycleSlider, this));
			}

			if (this.isTouchDevice)
			{
				BX.bind(this.node.imageContainer, 'touchstart', BX.proxy(this.touchStartEvent, this));
				BX.bind(this.node.imageContainer, 'touchend', BX.proxy(this.touchEndEvent, this));
				BX.bind(this.node.imageContainer, 'touchcancel', BX.proxy(this.touchEndEvent, this));
			}

			BX.bind(this.node.sliderControlLeft, 'click', BX.proxy(this.slidePrev, this));
			BX.bind(this.node.sliderControlRight, 'click', BX.proxy(this.slideNext, this));

			if (this.product.slider.ITEMS.length > 1)
			{
				this.initSlider();
			}

		}

		this.params = {};

		BX.addCustomEvent('onSaleProductIsGift', BX.delegate(this.onSaleProductIsGift, this));
		BX.addCustomEvent('onSaleProductIsNotGift', BX.delegate(this.onSaleProductIsNotGift, this));
	};

	window.JCCatalogElement.prototype = {
		getEntity: function(parent, entity, additionalFilter)
		{
			if (!parent || !entity)
				return null;

			additionalFilter = additionalFilter || '';

			return parent.querySelector(additionalFilter + '[data-entity="' + entity + '"]');
		},

		getEntities: function(parent, entity, additionalFilter)
		{
			if (!parent || !entity)
				return {length: 0};

			additionalFilter = additionalFilter || '';

			return parent.querySelectorAll(additionalFilter + '[data-entity="' + entity + '"]');
		},

		onSaleProductIsGift: function(productId, offerId)
		{
			if (offerId && this.offers && this.offers[this.offerNum].ID == offerId)
			{
				this.setGift();
			}
		},

		onSaleProductIsNotGift: function(productId, offerId)
		{
			if (offerId && this.offers && this.offers[this.offerNum].ID == offerId)
			{
				this.restoreSticker();
				this.isGift = false;
				this.setPrice();
			}
		},

		reloadGiftInfo: function()
		{
			if (this.productType === 3)
			{
				this.checkQuantity = true;
				this.maxQuantity = 1;

				this.setPrice();
				this.redrawSticker({text: BX.message('PRODUCT_GIFT_LABEL')});
			}
		},

		setGift: function()
		{
			if (this.productType === 3)
			{
				// sku
				this.isGift = true;
			}

			if (this.productType === 1 || this.productType === 2)
			{
				// simple
				this.isGift = true;
			}

			if (this.productType === 0)
			{
				this.isGift = false;
			}

			this.reloadGiftInfo();
		},

		setOffer: function(offerNum)
		{
			this.offerNum = parseInt(offerNum);
			this.setCurrent();
		},

		init: function()
		{
			var i = 0,
				j = 0,
				treeItems = null;

			this.obProduct = BX(this.visual.ID);
			if (!this.obProduct)
			{
				this.errorCode = -1;
			}

			this.obBigSlider = BX(this.visual.BIG_SLIDER_ID);
			this.node.imageContainer = this.getEntity(this.obProduct, 'images-container');
			this.node.imageSliderBlock = this.getEntity(this.obProduct, 'images-slider-block');
			this.node.sliderProgressBar = this.getEntity(this.obProduct, 'slider-progress-bar');
			this.node.sliderControlLeft = this.getEntity(this.obBigSlider, 'slider-control-left');
			this.node.sliderControlRight = this.getEntity(this.obBigSlider, 'slider-control-right');

			if (!this.obBigSlider || !this.node.imageContainer || !this.node.imageContainer)
			{
				this.errorCode = -2;
			}

			if (this.config.showPrice)
			{
				this.obPrice.price = BX(this.visual.PRICE_ID);
				if (!this.obPrice.price && this.config.useCatalog)
				{
					this.errorCode = -16;
				}
				else
				{
					this.obPrice.total = BX(this.visual.PRICE_TOTAL);

					if (this.config.showOldPrice)
					{
						this.obPrice.full = BX(this.visual.OLD_PRICE_ID);
						this.obPrice.discount = BX(this.visual.DISCOUNT_PRICE_ID);

						if (!this.obPrice.full || !this.obPrice.discount)
						{
							this.config.showOldPrice = false;
						}
					}

					if (this.config.showPercent)
					{
						this.obPrice.percent = BX(this.visual.DISCOUNT_PERCENT_ID);
						if (!this.obPrice.percent)
						{
							this.config.showPercent = false;
						}
					}
				}

				this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
				if (this.obBasketActions)
				{
					if (BX.util.in_array('BUY', this.config.basketAction))
					{
						this.obBuyBtn = BX(this.visual.BUY_LINK);
					}

					if (BX.util.in_array('ADD', this.config.basketAction))
					{
						this.obAddToBasketBtn = BX(this.visual.ADD_BASKET_LINK);
					}
				}
				this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);
			}

			if (this.config.showQuantity)
			{
				this.obQuantity = BX(this.visual.QUANTITY_ID);
				this.node.quantity = this.getEntity(this.obProduct, 'quantity-block');
				if (this.visual.QUANTITY_UP_ID)
				{
					this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
				}

				if (this.visual.QUANTITY_DOWN_ID)
				{
					this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
				}
			}

			if (this.productType === 3)
			{
				if (this.visual.TREE_ID)
				{
					this.obTree = BX(this.visual.TREE_ID);
					if (!this.obTree)
					{
						this.errorCode = -256;
					}
				}

				if (this.visual.QUANTITY_MEASURE)
				{
					this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
				}

				if (this.visual.QUANTITY_LIMIT && this.config.showMaxQuantity !== 'N')
				{
					this.obQuantityLimit.all = BX(this.visual.QUANTITY_LIMIT);
					if (this.obQuantityLimit.all)
					{
						this.obQuantityLimit.value = this.getEntity(this.obQuantityLimit.all, 'quantity-limit-value');
						if (!this.obQuantityLimit.value)
						{
							this.obQuantityLimit.all = null;
						}
					}
				}

				if (this.config.usePriceRanges)
				{
					this.obPriceRanges = this.getEntity(this.obProduct, 'price-ranges-block');
				}
			}

			if (this.config.showSkuProps)
			{
				this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
				this.obMainSkuProps = BX(this.visual.DISPLAY_MAIN_PROP_DIV);
			}

			if (this.config.useCompare)
			{
				this.obCompare = BX(this.visual.COMPARE_LINK);
			}

			if (this.config.useSubscribe)
			{
				this.obSubscribe = BX(this.visual.SUBSCRIBE_LINK);
			}

			this.obTabs = BX(this.visual.TABS_ID);
			this.obTabContainers = BX(this.visual.TAB_CONTAINERS_ID);
			this.obTabsPanel = BX(this.visual.TABS_PANEL_ID);

			this.smallCardNodes.panel = BX(this.visual.SMALL_CARD_PANEL_ID);
			if (this.smallCardNodes.panel)
			{
				this.smallCardNodes.picture = this.getEntity(this.smallCardNodes.panel, 'panel-picture');
				this.smallCardNodes.title = this.getEntity(this.smallCardNodes.panel, 'panel-title');
				this.smallCardNodes.price = this.getEntity(this.smallCardNodes.panel, 'panel-price');
				this.smallCardNodes.sku = this.getEntity(this.smallCardNodes.panel, 'panel-sku-container');
				this.smallCardNodes.oldPrice = this.getEntity(this.smallCardNodes.panel, 'panel-old-price');
				this.smallCardNodes.buyButton = this.getEntity(this.smallCardNodes.panel, 'panel-buy-button');
				this.smallCardNodes.addButton = this.getEntity(this.smallCardNodes.panel, 'panel-add-button');
				this.smallCardNodes.notAvailableButton = this.getEntity(this.smallCardNodes.panel, 'panel-not-available-button');
				this.smallCardNodes.aligner = this.getEntity(this.obProduct, 'main-button-container');
			}

			this.initPopup();
			this.initTabs();

			if (this.smallCardNodes.panel)
			{
				this.smallCardNodes.picture && BX.bind(this.smallCardNodes.picture.parentNode, 'click', BX.proxy(this.scrollToProduct, this));
				this.smallCardNodes.title && BX.bind(this.smallCardNodes.title, 'click', BX.proxy(this.scrollToProduct, this));
				this.smallCardNodes.sku && BX.bind(this.smallCardNodes.sku, 'click', BX.proxy(this.scrollToProduct, this));
			}

			if (this.obTabsPanel || this.smallCardNodes.panel)
			{
				this.checkTopPanels();
				BX.bind(window, 'scroll', BX.proxy(this.checkTopPanels, this));
			}

			if (this.errorCode === 0)
			{
				// product slider events
				if (this.config.showSlider && !this.isTouchDevice)
				{
					BX.bind(this.obBigSlider, 'mouseenter', BX.proxy(this.stopSlider, this));
					BX.bind(this.obBigSlider, 'mouseleave', BX.proxy(this.cycleSlider, this));
				}

				if (this.isTouchDevice)
				{
					BX.bind(this.node.imageContainer, 'touchstart', BX.proxy(this.touchStartEvent, this));
					BX.bind(this.node.imageContainer, 'touchend', BX.proxy(this.touchEndEvent, this));
					BX.bind(this.node.imageContainer, 'touchcancel', BX.proxy(this.touchEndEvent, this));
				}

				BX.bind(this.node.sliderControlLeft, 'click', BX.proxy(this.slidePrev, this));
				BX.bind(this.node.sliderControlRight, 'click', BX.proxy(this.slideNext, this));

				if (this.config.showQuantity)
				{
					var startEventName = this.isTouchDevice ? 'touchstart' : 'mousedown';
					var endEventName = this.isTouchDevice ? 'touchend' : 'mouseup';

					if (this.obQuantityUp)
					{
						BX.bind(this.obQuantityUp, startEventName, BX.proxy(this.startQuantityInterval, this));
						BX.bind(this.obQuantityUp, endEventName, BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityUp, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityUp, 'click', BX.delegate(this.quantityUp, this));
					}

					if (this.obQuantityDown)
					{
						BX.bind(this.obQuantityDown, startEventName, BX.proxy(this.startQuantityInterval, this));
						BX.bind(this.obQuantityDown, endEventName, BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityDown, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityDown, 'click', BX.delegate(this.quantityDown, this));
					}

					if (this.obQuantity)
					{
						BX.bind(this.obQuantity, 'change', BX.delegate(this.quantityChange, this));
					}
				}

				switch (this.productType)
				{
					case 0: // no catalog
					case 1: // product
					case 2: // set
						if (this.product.useSlider)
						{
							this.product.slider = {
								ID: this.visual.SLIDER_CONT_ID,
								CONT: BX(this.visual.SLIDER_CONT_ID),
								COUNT: this.product.sliderCount
							};
							this.product.slider.ITEMS = this.getEntities(this.product.slider.CONT, 'slider-control');
							for (j = 0; j < this.product.slider.ITEMS.length; j++)
							{
								BX.bind(this.product.slider.ITEMS[j], 'mouseenter', BX.delegate(this.onSliderControlHover, this));
								BX.bind(this.product.slider.ITEMS[j], 'mouseleave', BX.delegate(this.onSliderControlLeave, this));
								BX.bind(this.product.slider.ITEMS[j], 'click', BX.delegate(this.selectSliderImg, this));
							}

							this.setCurrentImg(this.product.sliderPict[0], true, true);
							this.checkSliderControls(this.product.sliderCount);

							if (this.product.slider.ITEMS.length > 1)
							{
								this.initSlider();
							}
						}

						this.checkQuantityControls();
						this.fixFontCheck();
						this.setAnalyticsDataLayer('showDetail');
						break;
					case 3: // sku
						treeItems = this.obTree.querySelectorAll('li');
						for (i = 0; i < treeItems.length; i++)
						{
							BX.bind(treeItems[i], 'click', BX.delegate(this.selectOfferProp, this));
						}

						for (i = 0; i < this.offers.length; i++)
						{
							this.offers[i].SLIDER_COUNT = parseInt(this.offers[i].SLIDER_COUNT, 10) || 0;

							if (this.offers[i].SLIDER_COUNT === 0)
							{
								this.slider.controls[i] = {
									ID: '',
									COUNT: this.offers[i].SLIDER_COUNT,
									ITEMS: []
								};
							}
							else
							{
								for (j = 0; j < this.offers[i].SLIDER.length; j++)
								{
									this.offers[i].SLIDER[j].WIDTH = parseInt(this.offers[i].SLIDER[j].WIDTH, 10);
									this.offers[i].SLIDER[j].HEIGHT = parseInt(this.offers[i].SLIDER[j].HEIGHT, 10);
								}

								this.slider.controls[i] = {
									ID: this.visual.SLIDER_CONT_OF_ID + this.offers[i].ID,
									OFFER_ID: this.offers[i].ID,
									CONT: BX(this.visual.SLIDER_CONT_OF_ID + this.offers[i].ID),
									COUNT: this.offers[i].SLIDER_COUNT
								};

								this.slider.controls[i].ITEMS = this.getEntities(this.slider.controls[i].CONT, 'slider-control');
								for (j = 0; j < this.slider.controls[i].ITEMS.length; j++)
								{
									BX.bind(this.slider.controls[i].ITEMS[j], 'mouseenter', BX.delegate(this.onSliderControlHover, this));
									BX.bind(this.slider.controls[i].ITEMS[j], 'mouseleave', BX.delegate(this.onSliderControlLeave, this));
									BX.bind(this.slider.controls[i].ITEMS[j], 'click', BX.delegate(this.selectSliderImg, this));
								}
							}
						}

						this.setCurrent();
						break;
				}

				this.obBuyBtn && BX.bind(this.obBuyBtn, 'click', BX.proxy(this.buyBasket, this));
				this.smallCardNodes.buyButton && BX.bind(this.smallCardNodes.buyButton, 'click', BX.proxy(this.buyBasket, this));

				this.obAddToBasketBtn && BX.bind(this.obAddToBasketBtn, 'click', BX.proxy(this.add2Basket, this));
				this.smallCardNodes.addButton && BX.bind(this.smallCardNodes.addButton, 'click', BX.proxy(this.add2Basket, this));

				if (this.obCompare)
				{
					BX.bind(this.obCompare, 'click', BX.proxy(this.compare, this));
					BX.addCustomEvent('onCatalogDeleteCompare', BX.proxy(this.checkDeletedCompare, this));
				}
			}
		},

		initConfig: function()
		{
			if (this.params.PRODUCT_TYPE)
			{
				this.productType = parseInt(this.params.PRODUCT_TYPE, 10);
			}

			if (this.params.CONFIG.USE_CATALOG !== 'undefined' && BX.type.isBoolean(this.params.CONFIG.USE_CATALOG))
			{
				this.config.useCatalog = this.params.CONFIG.USE_CATALOG;
			}

			this.config.showQuantity = this.params.CONFIG.SHOW_QUANTITY;
			this.config.showPrice = this.params.CONFIG.SHOW_PRICE;
			this.config.showPercent = this.params.CONFIG.SHOW_DISCOUNT_PERCENT;
			this.config.showOldPrice = this.params.CONFIG.SHOW_OLD_PRICE;
			this.config.showSkuProps = this.params.CONFIG.SHOW_SKU_PROPS;
			this.config.showOfferGroup = this.params.CONFIG.OFFER_GROUP;
			this.config.useCompare = this.params.CONFIG.DISPLAY_COMPARE;
			this.config.useStickers = this.params.CONFIG.USE_STICKERS;
			this.config.useSubscribe = this.params.CONFIG.USE_SUBSCRIBE;
			this.config.showMaxQuantity = this.params.CONFIG.SHOW_MAX_QUANTITY;
			this.config.relativeQuantityFactor = parseInt(this.params.CONFIG.RELATIVE_QUANTITY_FACTOR);
			this.config.usePriceRanges = this.params.CONFIG.USE_PRICE_COUNT;

			if (this.params.CONFIG.MAIN_PICTURE_MODE)
			{
				this.config.usePopup = BX.util.in_array('POPUP', this.params.CONFIG.MAIN_PICTURE_MODE);
				this.config.useMagnifier = BX.util.in_array('MAGNIFIER', this.params.CONFIG.MAIN_PICTURE_MODE);
			}

			if (this.params.CONFIG.ADD_TO_BASKET_ACTION)
			{
				this.config.basketAction = this.params.CONFIG.ADD_TO_BASKET_ACTION;
			}

			this.config.showClosePopup = this.params.CONFIG.SHOW_CLOSE_POPUP;
			this.config.templateTheme = this.params.CONFIG.TEMPLATE_THEME || '';
			this.config.showSlider = this.params.CONFIG.SHOW_SLIDER === 'Y';

			if (this.config.showSlider && !this.isTouchDevice)
			{
				this.config.sliderInterval = parseInt(this.params.CONFIG.SLIDER_INTERVAL) || 5000;
			}
			else
			{
				this.config.sliderInterval = false;
			}

			this.config.useEnhancedEcommerce = this.params.CONFIG.USE_ENHANCED_ECOMMERCE === 'Y';
			this.config.dataLayerName = this.params.CONFIG.DATA_LAYER_NAME;
			this.config.brandProperty = this.params.CONFIG.BRAND_PROPERTY;

			this.config.alt = this.params.CONFIG.ALT || '';
			this.config.title = this.params.CONFIG.TITLE || '';

			this.config.magnifierZoomPercent = parseInt(this.params.CONFIG.MAGNIFIER_ZOOM_PERCENT) || 200;

			if (!this.params.VISUAL || typeof this.params.VISUAL !== 'object' || !this.params.VISUAL.ID)
			{
				this.errorCode = -1;
				return;
			}

			this.visual = this.params.VISUAL;
		},

		initProductData: function()
		{
			var j = 0;

			if (this.params.PRODUCT && typeof this.params.PRODUCT === 'object')
			{
				if (this.config.showQuantity)
				{
					this.product.checkQuantity = this.params.PRODUCT.CHECK_QUANTITY;
					this.product.isDblQuantity = this.params.PRODUCT.QUANTITY_FLOAT;

					if (this.config.showPrice)
					{
						this.currentPriceMode = this.params.PRODUCT.ITEM_PRICE_MODE;
						this.currentPrices = this.params.PRODUCT.ITEM_PRICES;
						this.currentPriceSelected = this.params.PRODUCT.ITEM_PRICE_SELECTED;
						this.currentQuantityRanges = this.params.PRODUCT.ITEM_QUANTITY_RANGES;
						this.currentQuantityRangeSelected = this.params.PRODUCT.ITEM_QUANTITY_RANGE_SELECTED;
					}

					if (this.product.checkQuantity)
					{
						this.product.maxQuantity = this.product.isDblQuantity
							? parseFloat(this.params.PRODUCT.MAX_QUANTITY)
							: parseInt(this.params.PRODUCT.MAX_QUANTITY, 10);
					}

					this.product.stepQuantity = this.product.isDblQuantity
						? parseFloat(this.params.PRODUCT.STEP_QUANTITY)
						: parseInt(this.params.PRODUCT.STEP_QUANTITY, 10);
					this.checkQuantity = this.product.checkQuantity;
					this.isDblQuantity = this.product.isDblQuantity;
					this.stepQuantity = this.product.stepQuantity;
					this.maxQuantity = this.product.maxQuantity;
					this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;

					if (this.isDblQuantity)
					{
						this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
					}
				}

				this.product.canBuy = this.params.PRODUCT.CAN_BUY;
				this.canSubscription = this.product.canSubscription = this.params.PRODUCT.SUBSCRIPTION;

				this.product.name = this.params.PRODUCT.NAME;
				this.product.pict = this.params.PRODUCT.PICT;
				this.product.id = this.params.PRODUCT.ID;
				this.product.category = this.params.PRODUCT.CATEGORY;

				if (this.params.PRODUCT.ADD_URL)
				{
					this.product.addUrl = this.params.PRODUCT.ADD_URL;
				}

				if (this.params.PRODUCT.BUY_URL)
				{
					this.product.buyUrl = this.params.PRODUCT.BUY_URL;
				}

				if (this.params.PRODUCT.SLIDER_COUNT)
				{
					this.product.sliderCount = parseInt(this.params.PRODUCT.SLIDER_COUNT, 10) || 0;

					if (this.product.sliderCount > 0 && this.params.PRODUCT.SLIDER.length)
					{
						for (j = 0; j < this.params.PRODUCT.SLIDER.length; j++)
						{
							this.product.useSlider = true;
							this.params.PRODUCT.SLIDER[j].WIDTH = parseInt(this.params.PRODUCT.SLIDER[j].WIDTH, 10);
							this.params.PRODUCT.SLIDER[j].HEIGHT = parseInt(this.params.PRODUCT.SLIDER[j].HEIGHT, 10);
						}

						this.product.sliderPict = this.params.PRODUCT.SLIDER;
						this.setCurrentImg(this.product.sliderPict[0], false);
					}
				}

				this.currentIsSet = true;
			}
			else
			{
				this.errorCode = -1;
			}
		},

		initOffersData: function()
		{
			if (this.params.OFFERS && BX.type.isArray(this.params.OFFERS))
			{
				this.offers = this.params.OFFERS;
				this.offerNum = 0;

				if (this.params.OFFER_SELECTED)
				{
					this.offerNum = parseInt(this.params.OFFER_SELECTED, 10) || 0;
				}

				if (this.params.TREE_PROPS)
				{
					this.treeProps = this.params.TREE_PROPS;
				}

				if (this.params.DEFAULT_PICTURE)
				{
					this.defaultPict.preview = this.params.DEFAULT_PICTURE.PREVIEW_PICTURE;
					this.defaultPict.detail = this.params.DEFAULT_PICTURE.DETAIL_PICTURE;
				}

				if (this.params.PRODUCT && typeof this.params.PRODUCT === 'object')
				{
					this.product.id = parseInt(this.params.PRODUCT.ID, 10);
					this.product.name = this.params.PRODUCT.NAME;
					this.product.category = this.params.PRODUCT.CATEGORY;
				}
			}
			else
			{
				this.errorCode = -1;
			}
		},

		initBasketData: function()
		{
			if (this.params.BASKET && typeof this.params.BASKET === 'object')
			{
				if (this.productType === 1 || this.productType === 2)
				{
					this.basketData.useProps = this.params.BASKET.ADD_PROPS;
					this.basketData.emptyProps = this.params.BASKET.EMPTY_PROPS;
				}

				if (this.params.BASKET.QUANTITY)
				{
					this.basketData.quantity = this.params.BASKET.QUANTITY;
				}

				if (this.params.BASKET.PROPS)
				{
					this.basketData.props = this.params.BASKET.PROPS;
				}

				if (this.params.BASKET.BASKET_URL)
				{
					this.basketData.basketUrl = this.params.BASKET.BASKET_URL;
				}

				if (this.productType === 3)
				{
					if (this.params.BASKET.SKU_PROPS)
					{
						this.basketData.sku_props = this.params.BASKET.SKU_PROPS;
					}
				}

				if (this.params.BASKET.ADD_URL_TEMPLATE)
				{
					this.basketData.add_url = this.params.BASKET.ADD_URL_TEMPLATE;
				}

				if (this.params.BASKET.BUY_URL_TEMPLATE)
				{
					this.basketData.buy_url = this.params.BASKET.BUY_URL_TEMPLATE;
				}

				if (this.basketData.add_url === '' && this.basketData.buy_url === '')
				{
					this.errorCode = -1024;
				}
			}
		},

		initCompareData: function()
		{
			if (this.config.useCompare)
			{
				if (this.params.COMPARE && typeof this.params.COMPARE === 'object')
				{
					if (this.params.COMPARE.COMPARE_PATH)
					{
						this.compareData.comparePath = this.params.COMPARE.COMPARE_PATH;
					}

					if (this.params.COMPARE.COMPARE_URL_TEMPLATE)
					{
						this.compareData.compareUrl = this.params.COMPARE.COMPARE_URL_TEMPLATE;
					}
					else
					{
						this.config.useCompare = false;
					}

					if (this.params.COMPARE.COMPARE_DELETE_URL_TEMPLATE)
					{
						this.compareData.compareDeleteUrl = this.params.COMPARE.COMPARE_DELETE_URL_TEMPLATE;
					}
					else
					{
						this.config.useCompare = false;
					}
				}
				else
				{
					this.config.useCompare = false;
				}
			}
		},

		initSlider: function()
		{
			if (this.node.sliderProgressBar)
			{
				if (this.slider.progress)
				{
					this.resetProgress();
				}
				else
				{
					this.slider.progress = new BX.easing({
						transition: BX.easing.transitions.linear,
						step: BX.delegate(function(state){
							this.node.sliderProgressBar.style.width = state.width / 10 + '%';
						}, this)
					});
				}
			}

			this.cycleSlider();
		},

		setAnalyticsDataLayer: function(action)
		{
			if (!this.config.useEnhancedEcommerce || !this.config.dataLayerName)
				return;

			var item = {},
				info = {},
				variants = [],
				i, k, j, propId, skuId, propValues;

			switch (this.productType)
			{
				case 0: //no catalog
				case 1: //product
				case 2: //set
					item = {
						'id': this.product.id,
						'name': this.product.name,
						'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
						'category': this.product.category,
						'brand': BX.type.isArray(this.config.brandProperty) ? this.config.brandProperty.join('/') : this.config.brandProperty
					};
					break;
				case 3: //sku
					for (i in this.offers[this.offerNum].TREE)
					{
						if (this.offers[this.offerNum].TREE.hasOwnProperty(i))
						{
							propId = i.substring(5);
							skuId = this.offers[this.offerNum].TREE[i];

							for (k in this.treeProps)
							{
								if (this.treeProps.hasOwnProperty(k) && this.treeProps[k].ID == propId)
								{
									for (j in this.treeProps[k].VALUES)
									{
										propValues = this.treeProps[k].VALUES[j];
										if (propValues.ID == skuId)
										{
											variants.push(propValues.NAME);
											break;
										}
									}

								}
							}
						}
					}

					item = {
						'id': this.offers[this.offerNum].ID,
						'name': this.offers[this.offerNum].NAME,
						'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
						'category': this.product.category,
						'brand': BX.type.isArray(this.config.brandProperty) ? this.config.brandProperty.join('/') : this.config.brandProperty,
						'variant': variants.join('/')
					};
					break;
			}

			switch (action)
			{
				case 'showDetail':
					info = {
						'event': 'showDetail',
						'ecommerce': {
							'currencyCode': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '',
							'detail': {
								'products': [{
									'name': item.name || '',
									'id': item.id || '',
									'price': item.price || 0,
									'brand': item.brand || '',
									'category': item.category || '',
									'variant': item.variant || ''
								}]
							}
						}
					};
					break;
				case 'addToCart':
					info = {
						'event': 'addToCart',
						'ecommerce': {
							'currencyCode': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '',
							'add': {
								'products': [{
									'name': item.name || '',
									'id': item.id || '',
									'price': item.price || 0,
									'brand': item.brand || '',
									'category': item.category || '',
									'variant': item.variant || '',
									'quantity': this.config.showQuantity && this.obQuantity ? this.obQuantity.value : 1
								}]
							}
						}
					};
					break;
			}

			window[this.config.dataLayerName] = window[this.config.dataLayerName] || [];
			window[this.config.dataLayerName].push(info);
		},

		initTabs: function()
		{
			var tabs = this.getEntities(this.obTabs, 'tab'),
				panelTabs = this.getEntities(this.obTabsPanel, 'tab');

			var	tabValue, targetTab, haveActive = false;

			if (tabs.length !== panelTabs.length)
				return;

			for (var i in tabs)
			{
				if (tabs.hasOwnProperty(i) && BX.type.isDomNode(tabs[i]))
				{
					tabValue = tabs[i].getAttribute('data-value');
					if (tabValue)
					{
						targetTab = this.obTabContainers.querySelector('[data-value="' + tabValue + '"]');
						if (BX.type.isDomNode(targetTab))
						{
							BX.bind(tabs[i], 'click', BX.proxy(this.changeTab, this));
							BX.bind(panelTabs[i], 'click', BX.proxy(this.changeTab, this));

							if (!haveActive)
							{
								BX.addClass(tabs[i], 'active');
								BX.addClass(panelTabs[i], 'active');
								BX.show(targetTab);
								haveActive = true;
							}
							else
							{
								BX.removeClass(tabs[i], 'active');
								BX.removeClass(panelTabs[i], 'active');
								BX.hide(targetTab);
							}
						}
					}
				}
			}
		},

		checkTouch: function(event)
		{
			if (!event || !event.changedTouches)
				return false;

			return event.changedTouches[0].identifier === this.touch.identifier;
		},

		touchStartEvent: function(event)
		{
			if (event.touches.length != 1)
				return;

			this.touch = event.changedTouches[0];
		},

		touchEndEvent: function(event)
		{
			if (!this.checkTouch(event))
				return;

			var deltaX = this.touch.pageX - event.changedTouches[0].pageX,
				deltaY = this.touch.pageY - event.changedTouches[0].pageY;

			if (Math.abs(deltaX) >= Math.abs(deltaY) + 10)
			{
				if (deltaX > 0)
				{
					this.slideNext();
				}

				if (deltaX < 0)
				{
					this.slidePrev();
				}
			}
		},

		cycleSlider: function(event)
		{
			event || (this.slider.paused = false);

			this.slider.interval && clearInterval(this.slider.interval);

			if (this.config.sliderInterval && !this.slider.paused)
			{
				if (this.slider.progress)
				{
					this.slider.progress.stop();

					var width = parseInt(this.node.sliderProgressBar.style.width);

					this.slider.progress.options.duration = this.config.sliderInterval * (100 - width) / 100;
					this.slider.progress.options.start = {width: width * 10};
					this.slider.progress.options.finish = {width: 1000};
					this.slider.progress.options.complete = BX.delegate(function(){
						this.slider.interval = true;
						this.slideNext();
					}, this);
					this.slider.progress.animate();
				}
				else
				{
					this.slider.interval = setInterval(BX.proxy(this.slideNext, this), this.config.sliderInterval);
				}
			}
		},

		stopSlider: function(event)
		{
			event || (this.slider.paused = true);

			this.slider.interval && (this.slider.interval = clearInterval(this.slider.interval));

			if (this.slider.progress)
			{
				this.slider.progress.stop();

				var width = parseInt(this.node.sliderProgressBar.style.width);

				this.slider.progress.options.duration = this.config.sliderInterval * width / 200;
				this.slider.progress.options.start = {width: width * 10};
				this.slider.progress.options.finish = {width: 0};
				this.slider.progress.options.complete = null;
				this.slider.progress.animate();
			}
		},

		resetProgress: function()
		{
			this.slider.progress && this.slider.progress.stop();
			this.node.sliderProgressBar.style.width = 0;
		},

		slideNext: function()
		{
			return this.slide('next');
		},

		slidePrev: function()
		{
			return this.slide('prev');
		},

		slide: function(type)
		{
			if (!this.product.slider || !this.product.slider.CONT)
				return;

			var active = this.getEntity(this.product.slider.CONT, 'slider-control', '.active'),
				next = this.getItemForDirection(type, active);

			BX.removeClass(active, 'active');
			this.selectSliderImg(next);

			this.slider.interval && this.cycleSlider();
		},

		getItemForDirection: function(direction, active)
		{
			var activeIndex = this.getItemIndex(active),
				delta = direction === 'prev' ? -1 : 1,
				itemIndex = (activeIndex + delta) % this.product.slider.COUNT;

			return this.eq(this.product.slider.ITEMS, itemIndex);
		},

		getItemIndex: function(item)
		{
			return BX.util.array_values(this.product.slider.ITEMS).indexOf(item);
		},

		eq: function(obj, i)
		{
			var len = obj.length,
				j = +i + (i < 0 ? len : 0);

			return j >= 0 && j < len ? obj[j] : {};
		},

		scrollToProduct: function()
		{
			var scrollTop = BX.GetWindowScrollPos().scrollTop,
				containerTop = BX.pos(this.obProduct).top - 30;

			if (scrollTop > containerTop)
			{
				new BX.easing({
					duration: 500,
					start: {scroll: scrollTop},
					finish: {scroll: containerTop},
					transition: BX.easing.makeEaseOut(BX.easing.transitions.quint),
					step: BX.delegate(function(state){
						window.scrollTo(0, state.scroll);
					}, this)
				}).animate();
			}
		},

		checkTopPanels: function()
		{
			var scrollTop = BX.GetWindowScrollPos().scrollTop,
				targetPos;

			if (this.smallCardNodes.panel)
			{
				targetPos = BX.pos(this.smallCardNodes.aligner).bottom - 50;

				if (scrollTop > targetPos)
				{
					BX.addClass(this.smallCardNodes.panel, 'active');
				}
				else if (BX.hasClass(this.smallCardNodes.panel, 'active'))
				{
					BX.removeClass(this.smallCardNodes.panel, 'active');
				}
			}

			if (this.obTabsPanel)
			{
				targetPos = BX.pos(this.obTabs).top;

				if (scrollTop + 73 > targetPos)
				{
					BX.addClass(this.obTabsPanel, 'active');
				}
				else if (BX.hasClass(this.obTabsPanel, 'active'))
				{
					BX.removeClass(this.obTabsPanel, 'active');
				}
			}
		},

		changeTab: function(event)
		{
			BX.PreventDefault(event);

			var targetTabValue = BX.proxy_context && BX.proxy_context.getAttribute('data-value'),
				containers, tabs, panelTabs;

			if (!BX.hasClass(BX.proxy_context, 'active') && targetTabValue)
			{
				containers = this.getEntities(this.obTabContainers, 'tab-container');
				for (var i in containers)
				{
					if (containers.hasOwnProperty(i) && BX.type.isDomNode(containers[i]))
					{
						if (containers[i].getAttribute('data-value') === targetTabValue)
						{
							BX.show(containers[i]);
						}
						else
						{
							BX.hide(containers[i]);
						}
					}
				}

				tabs = this.getEntities(this.obTabs, 'tab');
				panelTabs = this.getEntities(this.obTabsPanel, 'tab');

				for (i in tabs)
				{
					if (tabs.hasOwnProperty(i) && BX.type.isDomNode(tabs[i]))
					{
						if (tabs[i].getAttribute('data-value') === targetTabValue)
						{
							BX.addClass(tabs[i], 'active');
							BX.addClass(panelTabs[i], 'active');
						}
						else
						{
							BX.removeClass(tabs[i], 'active');
							BX.removeClass(panelTabs[i], 'active');
						}
					}
				}
			}

			var scrollTop = BX.GetWindowScrollPos().scrollTop,
				containerTop = BX.pos(this.obTabContainers).top;

			if (scrollTop + 150 > containerTop)
			{
				new BX.easing({
					duration: 500,
					start: {scroll: scrollTop},
					finish: {scroll: containerTop - 150},
					transition: BX.easing.makeEaseOut(BX.easing.transitions.quint),
					step: BX.delegate(function(state){
						window.scrollTo(0, state.scroll);
					}, this)
				}).animate();
			}
		},

		initPopup: function()
		{
			if (this.config.usePopup)
			{
				this.node.imageContainer.style.cursor = 'zoom-in';
				BX.bind(this.node.imageContainer, 'click', BX.delegate(this.toggleMainPictPopup, this));
				BX.bind(document, 'keyup', BX.proxy(this.closeByEscape, this));
				BX.bind(
					this.getEntity(this.obBigSlider, 'close-popup'),
					'click',
					BX.proxy(this.hideMainPictPopup, this)
				);
			}
		},

		checkSliderControls: function(count)
		{
			var display = count > 1 ? '' : 'none';

			this.node.sliderControlLeft && (this.node.sliderControlLeft.style.display = display);
			this.node.sliderControlRight && (this.node.sliderControlRight.style.display = display);
		},

		setCurrentImg: function(img, showImage, showPanelImage)
		{
			var images, l;

			this.currentImg.id = img.ID;
			this.currentImg.src = img.SRC;
			this.currentImg.width = img.WIDTH;
			this.currentImg.height = img.HEIGHT;
			if (showImage && this.node.imageContainer)
			{
				images = this.getEntities(this.node.imageContainer, 'image');
				l = images.length;
				while (l--)
				{
					if (images[l].getAttribute('data-id') == img.ID)
					{
						if (!BX.hasClass(images[l], 'active'))
						{
							this.node.sliderProgressBar && this.resetProgress();
						}

						BX.addClass(images[l], 'active');
					}
					else if (BX.hasClass(images[l], 'active'))
					{
						BX.removeClass(images[l], 'active');
					}
				}
			}

			if (showPanelImage && this.smallCardNodes.picture)
			{
				this.smallCardNodes.picture.setAttribute('src', this.currentImg.src);
			}

			if (this.config.useMagnifier && !this.isTouchDevice)
			{
				this.setMagnifierParams();

				if (showImage)
				{
					this.disableMagnifier(true);
				}
			}
		},

		setMagnifierParams: function()
		{
			var images = this.getEntities(this.node.imageContainer, 'image'),
				l = images.length,
				current;

			while (l--)
			{
				// disable image title show
				current = images[l].querySelector('img');
				current.setAttribute('data-title', current.getAttribute('title') || '');
				current.removeAttribute('title');

				if (images[l].getAttribute('data-id') == this.currentImg.id)
				{
					BX.unbind(this.currentImg.node, 'mouseover', BX.proxy(this.enableMagnifier, this));

					this.currentImg.node = current;
					this.currentImg.node.style.backgroundImage = 'url(\'' + this.currentImg.src + '\')';
					this.currentImg.node.style.backgroundSize = '100% auto';

					BX.bind(this.currentImg.node, 'mouseover', BX.proxy(this.enableMagnifier, this));
				}
			}
		},

		enableMagnifier: function()
		{
			BX.bind(document, 'mousemove', BX.proxy(this.moveMagnifierArea, this));
		},

		disableMagnifier: function(animateSize)
		{
			if (!this.magnify.enabled)
				return;

			clearTimeout(this.magnify.timer);
			BX.removeClass(this.obBigSlider, 'magnified');
			this.magnify.enabled = false;

			this.currentImg.node.style.backgroundSize = '100% auto';
			if (animateSize)
			{
				// set initial size for css animation
				this.currentImg.node.style.height = this.magnify.height + 'px';
				this.currentImg.node.style.width = this.magnify.width + 'px';

				this.magnify.timer = setTimeout(
					BX.delegate(function(){
						this.currentImg.node.src = this.currentImg.src;
						this.currentImg.node.style.height = '';
						this.currentImg.node.style.width = '';
					}, this),
					250
				);
			}
			else
			{
				this.currentImg.node.src = this.currentImg.src;
				this.currentImg.node.style.height = '';
				this.currentImg.node.style.width = '';
			}

			BX.unbind(document, 'mousemove', BX.proxy(this.moveMagnifierArea, this));
		},

		moveMagnifierArea: function(e)
		{
			var posBigImg = BX.pos(this.currentImg.node),
				currentPos = this.inRect(e, posBigImg);

			if (this.inBound(posBigImg, currentPos))
			{
				var posPercentX = (currentPos.X / this.currentImg.node.width) * 100,
					posPercentY = (currentPos.Y / this.currentImg.node.height) * 100,
					resolution, sliderWidth, w, h, zoomPercent;

				this.currentImg.node.style.backgroundPosition = posPercentX + '% ' + posPercentY + '%';

				if (!this.magnify.enabled)
				{
					clearTimeout(this.magnify.timer);
					BX.addClass(this.obBigSlider, 'magnified');

					// set initial size for css animation
					this.currentImg.node.style.height = (this.magnify.height = this.currentImg.node.clientHeight) + 'px';
					this.currentImg.node.style.width = (this.magnify.width = this.currentImg.node.offsetWidth) + 'px';

					resolution = this.currentImg.width / this.currentImg.height;
					sliderWidth = this.obBigSlider.offsetWidth;

					if (sliderWidth > this.currentImg.width && !BX.hasClass(this.obBigSlider, 'popup'))
					{
						w = sliderWidth;
						h = w / resolution;
						zoomPercent = 100;
					}
					else
					{
						w = this.currentImg.width;
						h = this.currentImg.height;
						zoomPercent = this.config.magnifierZoomPercent > 100 ? this.config.magnifierZoomPercent : 100;
					}

					// base64 transparent pixel
					this.currentImg.node.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVQI12P4zwAAAgEBAKrChTYAAAAASUVORK5CYII=';
					this.currentImg.node.style.backgroundSize = zoomPercent + '% auto';

					// set target size
					this.magnify.timer = setTimeout(BX.delegate(function(){
							this.currentImg.node.style.height = h + 'px';
							this.currentImg.node.style.width = w + 'px';
						}, this),
						10
					);
				}

				this.magnify.enabled = true;
			}
			else
			{
				this.disableMagnifier(true);
			}
		},

		inBound: function(rect, point)
		{
			return (
				(point.Y >= 0 && rect.height >= point.Y)
				&& (point.X >= 0 && rect.width >= point.X)
			);
		},

		inRect: function(e, rect)
		{
			var wndSize = BX.GetWindowSize(),
				currentPos = {
					X: 0,
					Y: 0,
					globalX: 0,
					globalY: 0
				};

			currentPos.globalX = e.clientX + wndSize.scrollLeft;

			if (e.offsetX && e.offsetX < 0)
			{
				currentPos.globalX -= e.offsetX;
			}

			currentPos.X = currentPos.globalX - rect.left;
			currentPos.globalY = e.clientY + wndSize.scrollTop;

			if (e.offsetY && e.offsetY < 0)
			{
				currentPos.globalY -= e.offsetY;
			}

			currentPos.Y = currentPos.globalY - rect.top;

			return currentPos;
		},

		setProductMainPict: function(intPict)
		{
			var indexPict = -1,
				i = 0,
				j = 0,
				value = '';

			if (this.product.sliderCount)
			{
				for (j = 0; j < this.product.sliderPict.length; j++)
				{
					if (intPict === this.product.sliderPict[j].ID)
					{
						indexPict = j;
						break;
					}
				}

				if (indexPict > -1)
				{
					if (this.product.sliderPict[indexPict])
					{
						this.setCurrentImg(this.product.sliderPict[indexPict], true);
					}

					for (i = 0; i < this.product.slider.ITEMS.length; i++)
					{
						value = this.product.slider.ITEMS[i].getAttribute('data-value');

						if (value === intPict)
						{
							BX.addClass(this.product.slider.ITEMS[i], 'active');
						}
						else if (BX.hasClass(this.product.slider.ITEMS[i], 'active'))
						{
							BX.removeClass(this.product.slider.ITEMS[i], 'active');
						}
					}
				}
			}
		},

		onSliderControlHover: function()
		{
			var target = BX.proxy_context;

			this.mouseTimer = setTimeout(
				BX.delegate(function(){
					this.selectSliderImg(target);
				}, this),
				200
			);
		},

		onSliderControlLeave: function()
		{
			clearTimeout(this.mouseTimer);
			this.mouseTimer = null;
		},

		selectSliderImg: function(target)
		{
			var strValue = '',
				arItem = [];

			target = BX.type.isDomNode(target) ? target : BX.proxy_context;

			if (target && target.hasAttribute('data-value'))
			{
				strValue = target.getAttribute('data-value');

				if (strValue.indexOf('_') !== -1)
				{
					arItem = strValue.split('_');
					this.setMainPict(arItem[0], arItem[1]);
				}
				else
				{
					this.setProductMainPict(strValue);
				}
			}
		},

		setMainPict: function(intSlider, intPict, changePanelPict)
		{
			var index = -1,
				indexPict = -1,
				i,
				j,
				value = '',
				strValue = '';

			for (i = 0; i < this.offers.length; i++)
			{
				if (intSlider === this.offers[i].ID)
				{
					index = i;
					break;
				}
			}

			if (index > -1)
			{
				if (this.offers[index].SLIDER_COUNT > 0)
				{
					for (j = 0; j < this.offers[index].SLIDER.length; j++)
					{
						if (intPict === this.offers[index].SLIDER[j].ID)
						{
							indexPict = j;
							break;
						}
					}

					if (indexPict > -1)
					{
						if (this.offers[index].SLIDER[indexPict])
						{
							this.setCurrentImg(this.offers[index].SLIDER[indexPict], true, changePanelPict);
						}

						strValue = intSlider + '_' + intPict;

						for (i = 0; i < this.product.slider.ITEMS.length; i++)
						{
							value = this.product.slider.ITEMS[i].getAttribute('data-value');

							if (value === strValue)
							{
								BX.addClass(this.product.slider.ITEMS[i], 'active');
							}
							else if (BX.hasClass(this.product.slider.ITEMS[i], 'active'))
							{
								BX.removeClass(this.product.slider.ITEMS[i], 'active');
							}
						}
					}
				}
			}
		},

		setMainPictFromItem: function(index)
		{
			if (this.node.imageContainer)
			{
				var boolSet = false,
					obNewPict = {};

				if (this.offers[index])
				{
					if (this.offers[index].DETAIL_PICTURE)
					{
						obNewPict = this.offers[index].DETAIL_PICTURE;
						boolSet = true;
					}
					else if (this.offers[index].PREVIEW_PICTURE)
					{
						obNewPict = this.offers[index].PREVIEW_PICTURE;
						boolSet = true;
					}
				}

				if (!boolSet)
				{
					if (this.defaultPict.detail)
					{
						obNewPict = this.defaultPict.detail;
						boolSet = true;
					}
					else if (this.defaultPict.preview)
					{
						obNewPict = this.defaultPict.preview;
						boolSet = true;
					}
				}

				if (boolSet)
				{
					this.setCurrentImg(obNewPict, true, true);
				}
			}
		},

		toggleMainPictPopup: function()
		{
			if (BX.hasClass(this.obBigSlider, 'popup'))
			{
				this.hideMainPictPopup();
			}
			else
			{
				this.showMainPictPopup();
			}
		},

		showMainPictPopup: function()
		{
			this.config.useMagnifier && this.disableMagnifier(false);
			BX.addClass(this.obBigSlider, 'popup');
			this.node.imageContainer.style.cursor = '';
			// remove double scroll bar
			document.body.style.overflow = 'hidden';
		},

		hideMainPictPopup: function()
		{
			this.config.useMagnifier && this.disableMagnifier(false);
			BX.removeClass(this.obBigSlider, 'popup');
			this.node.imageContainer.style.cursor = 'zoom-in';
			// remove double scroll bar
			document.body.style.overflow = '';
		},

		closeByEscape: function(event)
		{
			event = event || window.event;

			if (event.keyCode == 27)
			{
				this.hideMainPictPopup();
			}
		},

		startQuantityInterval: function()
		{
			var target = BX.proxy_context;
			var func = target.id === this.visual.QUANTITY_DOWN_ID
				? BX.proxy(this.quantityDown, this)
				: BX.proxy(this.quantityUp, this);

			this.quantityDelay = setTimeout(
				BX.delegate(function() {
					this.quantityTimer = setInterval(func, 150);
				}, this),
				300
			);
		},

		clearQuantityInterval: function()
		{
			clearTimeout(this.quantityDelay);
			clearInterval(this.quantityTimer);
		},

		quantityUp: function()
		{
			var curValue = 0,
				boolSet = true;

			if (this.errorCode === 0 && this.config.showQuantity && this.canBuy && !this.isGift)
			{
				curValue = this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10);
				if (!isNaN(curValue))
				{
					curValue += this.stepQuantity;

					curValue = this.checkQuantityRange(curValue, 'up');

					if (this.checkQuantity && curValue > this.maxQuantity)
					{
						boolSet = false;
					}

					if (boolSet)
					{
						if (this.isDblQuantity)
						{
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}

						this.obQuantity.value = curValue;

						this.setPrice();
					}
				}
			}
		},

		quantityDown: function()
		{
			var curValue = 0,
				boolSet = true;

			if (this.errorCode === 0 && this.config.showQuantity && this.canBuy && !this.isGift)
			{
				curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
				if (!isNaN(curValue))
				{
					curValue -= this.stepQuantity;

					curValue = this.checkQuantityRange(curValue, 'down');

					if (curValue < this.minQuantity)
					{
						boolSet = false;
					}

					if (boolSet)
					{
						if (this.isDblQuantity)
						{
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}

						this.obQuantity.value = curValue;

						this.setPrice();
					}
				}
			}
		},

		quantityChange: function()
		{
			var curValue = 0,
				intCount;

			if (this.errorCode === 0 && this.config.showQuantity)
			{
				if (this.canBuy)
				{
					curValue = this.isDblQuantity ? parseFloat(this.obQuantity.value) : Math.round(this.obQuantity.value);
					if (!isNaN(curValue))
					{
						curValue = this.checkQuantityRange(curValue);

						if (this.checkQuantity)
						{
							if (curValue > this.maxQuantity)
							{
								curValue = this.maxQuantity;
							}
						}

						this.checkPriceRange(curValue);

						if (curValue < this.minQuantity)
						{
							curValue = this.minQuantity;
						}
						else
						{
							intCount = Math.round(
									Math.round(curValue * this.precisionFactor / this.stepQuantity) / this.precisionFactor
								) || 1;
							curValue = (intCount <= 1 ? this.stepQuantity : intCount * this.stepQuantity);
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}

						this.obQuantity.value = curValue;
					}
					else
					{
						this.obQuantity.value = this.minQuantity;
					}
				}
				else
				{
					this.obQuantity.value = this.minQuantity;
				}

				this.setPrice();
			}
		},

		quantitySet: function(index)
		{
			var strLimit, resetQuantity;

			var newOffer = this.offers[index],
				oldOffer = this.offers[this.offerNum];

			if (this.errorCode === 0)
			{
				this.canBuy = newOffer.CAN_BUY;

				this.currentPriceMode = newOffer.ITEM_PRICE_MODE;
				this.currentPrices = newOffer.ITEM_PRICES;
				this.currentPriceSelected = newOffer.ITEM_PRICE_SELECTED;
				this.currentQuantityRanges = newOffer.ITEM_QUANTITY_RANGES;
				this.currentQuantityRangeSelected = newOffer.ITEM_QUANTITY_RANGE_SELECTED;

				if (this.canBuy)
				{
					this.node.quantity && BX.style(this.node.quantity, 'display', '');

					this.obBasketActions && BX.style(this.obBasketActions, 'display', '');
					this.smallCardNodes.buyButton && BX.style(this.smallCardNodes.buyButton, 'display', '');
					this.smallCardNodes.addButton && BX.style(this.smallCardNodes.addButton, 'display', '');

					this.obNotAvail && BX.style(this.obNotAvail, 'display', 'none');
					this.smallCardNodes.notAvailableButton && BX.style(this.smallCardNodes.notAvailableButton, 'display', 'none');

					this.obSubscribe && BX.style(this.obSubscribe, 'display', 'none');
				}
				else
				{
					this.node.quantity && BX.style(this.node.quantity, 'display', 'none');

					this.obBasketActions && BX.style(this.obBasketActions, 'display', 'none');
					this.smallCardNodes.buyButton && BX.style(this.smallCardNodes.buyButton, 'display', 'none');
					this.smallCardNodes.addButton && BX.style(this.smallCardNodes.addButton, 'display', 'none');

					this.obNotAvail && BX.style(this.obNotAvail, 'display', '');
					this.smallCardNodes.notAvailableButton && BX.style(this.smallCardNodes.notAvailableButton, 'display', '');

					if (this.obSubscribe)
					{
						if (newOffer.CATALOG_SUBSCRIBE === 'Y')
						{
							BX.style(this.obSubscribe, 'display', '');
							this.obSubscribe.setAttribute('data-item', newOffer.ID);
							BX(this.visual.SUBSCRIBE_LINK + '_hidden').click();
						}
						else
						{
							BX.style(this.obSubscribe, 'display', 'none');
						}
					}
				}

				this.isDblQuantity = newOffer.QUANTITY_FLOAT;
				this.checkQuantity = newOffer.CHECK_QUANTITY;

				if (this.isDblQuantity)
				{
					this.stepQuantity = Math.round(parseFloat(newOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
					this.maxQuantity = parseFloat(newOffer.MAX_QUANTITY);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				}
				else
				{
					this.stepQuantity = parseInt(newOffer.STEP_QUANTITY, 10);
					this.maxQuantity = parseInt(newOffer.MAX_QUANTITY, 10);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseInt(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				}

				if (this.config.showQuantity)
				{
					var isDifferentMinQuantity = oldOffer.ITEM_PRICES.length
						&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED]
						&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED].MIN_QUANTITY != this.minQuantity;

					if (this.isDblQuantity)
					{
						resetQuantity = Math.round(parseFloat(oldOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor !== this.stepQuantity
							|| isDifferentMinQuantity
							|| oldOffer.MEASURE !== newOffer.MEASURE
							|| (
								this.checkQuantity
								&& parseFloat(oldOffer.MAX_QUANTITY) > this.maxQuantity
								&& parseFloat(this.obQuantity.value) > this.maxQuantity
							);
					}
					else
					{
						resetQuantity = parseInt(oldOffer.STEP_QUANTITY, 10) !== this.stepQuantity
							|| isDifferentMinQuantity
							|| oldOffer.MEASURE !== newOffer.MEASURE
							|| (
								this.checkQuantity
								&& parseInt(oldOffer.MAX_QUANTITY, 10) > this.maxQuantity
								&& parseInt(this.obQuantity.value, 10) > this.maxQuantity
							);
					}

					this.obQuantity.disabled = !this.canBuy;

					if (resetQuantity)
					{
						this.obQuantity.value = this.minQuantity;
					}

					if (this.obMeasure)
					{
						if (newOffer.MEASURE)
						{
							BX.adjust(this.obMeasure, {html: newOffer.MEASURE});
						}
						else
						{
							BX.adjust(this.obMeasure, {html: ''});
						}
					}
				}

				if (this.obQuantityLimit.all)
				{
					if (!this.checkQuantity || this.maxQuantity == 0)
					{
						BX.adjust(this.obQuantityLimit.value, {html: ''});
						BX.adjust(this.obQuantityLimit.all, {style: {display: 'none'}});
					}
					else
					{
						if (this.config.showMaxQuantity === 'M')
						{
							strLimit = (this.maxQuantity / this.stepQuantity >= this.config.relativeQuantityFactor)
								? BX.message('RELATIVE_QUANTITY_MANY')
								: BX.message('RELATIVE_QUANTITY_FEW');
						}
						else
						{
							strLimit = this.maxQuantity;

							if (newOffer.MEASURE)
							{
								strLimit += (' ' + newOffer.MEASURE);
							}
						}

						BX.adjust(this.obQuantityLimit.value, {html: strLimit});
						BX.adjust(this.obQuantityLimit.all, {style: {display: ''}});
					}
				}

				if (this.config.usePriceRanges && this.obPriceRanges)
				{
					if (
						this.currentPriceMode === 'Q'
						&& newOffer.PRICE_RANGES_HTML
					)
					{
						var rangesBody = this.getEntity(this.obPriceRanges, 'price-ranges-body'),
							rangesRatioHeader = this.getEntity(this.obPriceRanges, 'price-ranges-ratio-header');

						if (rangesBody)
						{
							rangesBody.innerHTML = newOffer.PRICE_RANGES_HTML;
						}

						if (rangesRatioHeader)
						{
							rangesRatioHeader.innerHTML = newOffer.PRICE_RANGES_RATIO_HTML;
						}

						this.obPriceRanges.style.display = '';
					}
					else
					{
						this.obPriceRanges.style.display = 'none';
					}

				}
			}
		},

		selectOfferProp: function()
		{
			var i = 0,
				strTreeValue = '',
				arTreeItem = [],
				rowItems = null,
				target = BX.proxy_context,
				smallCardItem;

			if (target && target.hasAttribute('data-treevalue'))
			{
				if (BX.hasClass(target, 'selected'))
					return;

				if (typeof document.activeElement === 'object')
				{
					document.activeElement.blur();
				}

				strTreeValue = target.getAttribute('data-treevalue');
				arTreeItem = strTreeValue.split('_');
				this.searchOfferPropIndex(arTreeItem[0], arTreeItem[1]);
				rowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);

				if (rowItems && rowItems.length)
				{
					for (i = 0; i < rowItems.length; i++)
					{
						BX.removeClass(rowItems[i], 'selected');
					}
				}

				BX.addClass(target, 'selected');

				if (this.smallCardNodes.panel)
				{
					smallCardItem = this.smallCardNodes.panel.querySelector('[data-treevalue="' + strTreeValue + '"]');
					if (smallCardItem)
					{
						rowItems = this.smallCardNodes.panel.querySelectorAll('[data-sku-line="' + smallCardItem.getAttribute('data-sku-line') + '"]');
						for (i = 0; i < rowItems.length; i++)
						{
							rowItems[i].style.display = 'none';
						}

						smallCardItem.style.display = '';
					}
				}
			}
		},

		searchOfferPropIndex: function(strPropID, strPropValue)
		{
			var strName = '',
				arShowValues = false,
				arCanBuyValues = [],
				allValues = [],
				index = -1,
				i, j,
				arFilter = {},
				tmpFilter = [];

			for (i = 0; i < this.treeProps.length; i++)
			{
				if (this.treeProps[i].ID === strPropID)
				{
					index = i;
					break;
				}
			}

			if (index > -1)
			{
				for (i = 0; i < index; i++)
				{
					strName = 'PROP_' + this.treeProps[i].ID;
					arFilter[strName] = this.selectedValues[strName];
				}

				strName = 'PROP_' + this.treeProps[index].ID;
				arFilter[strName] = strPropValue;

				for (i = index + 1; i < this.treeProps.length; i++)
				{
					strName = 'PROP_' + this.treeProps[i].ID;
					arShowValues = this.getRowValues(arFilter, strName);

					if (!arShowValues)
						break;

					allValues = [];

					if (this.config.showAbsent)
					{
						arCanBuyValues = [];
						tmpFilter = [];
						tmpFilter = BX.clone(arFilter, true);

						for (j = 0; j < arShowValues.length; j++)
						{
							tmpFilter[strName] = arShowValues[j];
							allValues[allValues.length] = arShowValues[j];
							if (this.getCanBuy(tmpFilter))
								arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
					else
					{
						arCanBuyValues = arShowValues;
					}

					if (this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues))
					{
						arFilter[strName] = this.selectedValues[strName];
					}
					else
					{
						if (this.config.showAbsent)
						{
							arFilter[strName] = (arCanBuyValues.length ? arCanBuyValues[0] : allValues[0]);
						}
						else
						{
							arFilter[strName] = arCanBuyValues[0];
						}
					}

					this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
				}

				this.selectedValues = arFilter;
				this.changeInfo();
			}
		},

		updateRow: function(intNumber, activeId, showId, canBuyId)
		{
			var i = 0,
				value = '',
				isCurrent = false,
				rowItems = null;

			var lineContainer = this.getEntities(this.obTree, 'sku-line-block');

			if (intNumber > -1 && intNumber < lineContainer.length)
			{
				rowItems = lineContainer[intNumber].querySelectorAll('li');
				for (i = 0; i < rowItems.length; i++)
				{
					value = rowItems[i].getAttribute('data-onevalue');
					isCurrent = value === activeId;

					if (isCurrent)
					{
						BX.addClass(rowItems[i], 'selected');
					}
					else
					{
						BX.removeClass(rowItems[i], 'selected');
					}

					if (BX.util.in_array(value, canBuyId))
					{
						BX.removeClass(rowItems[i], 'notallowed');
					}
					else
					{
						BX.addClass(rowItems[i], 'notallowed');
					}

					rowItems[i].style.display = BX.util.in_array(value, showId) ? '' : 'none';

					if (isCurrent)
					{
						lineContainer[intNumber].style.display = (value == 0 && canBuyId.length == 1) ? 'none' : '';
					}
				}

				if (this.smallCardNodes.panel)
				{
					rowItems = this.smallCardNodes.panel.querySelectorAll('[data-sku-line="' + intNumber + '"]');
					for (i = 0; i < rowItems.length; i++)
					{
						value = rowItems[i].getAttribute('data-onevalue');
						isCurrent = value === activeId;

						if (isCurrent)
						{
							rowItems[i].style.display = '';
						}
						else
						{
							rowItems[i].style.display = 'none';
						}

						if (BX.util.in_array(value, canBuyId))
						{
							BX.removeClass(rowItems[i], 'notallowed');
						}
						else
						{
							BX.addClass(rowItems[i], 'notallowed');
						}

						if (isCurrent)
						{
							rowItems[i].style.display = (value == 0 && canBuyId.length == 1) ? 'none' : '';
						}
					}
				}
			}
		},

		getRowValues: function(arFilter, index)
		{
			var arValues = [],
				i = 0,
				j = 0,
				boolSearch = false,
				boolOneSearch = true;

			if (arFilter.length === 0)
			{
				for (i = 0; i < this.offers.length; i++)
				{
					if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
					{
						arValues[arValues.length] = this.offers[i].TREE[index];
					}
				}
				boolSearch = true;
			}
			else
			{
				for (i = 0; i < this.offers.length; i++)
				{
					boolOneSearch = true;

					for (j in arFilter)
					{
						if (arFilter[j] !== this.offers[i].TREE[j])
						{
							boolOneSearch = false;
							break;
						}
					}

					if (boolOneSearch)
					{
						if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
						{
							arValues[arValues.length] = this.offers[i].TREE[index];
						}

						boolSearch = true;
					}
				}
			}

			return (boolSearch ? arValues : false);
		},

		getCanBuy: function(arFilter)
		{
			var i,
				j = 0,
				boolOneSearch = true,
				boolSearch = false;

			for (i = 0; i < this.offers.length; i++)
			{
				boolOneSearch = true;

				for (j in arFilter)
				{
					if (arFilter[j] !== this.offers[i].TREE[j])
					{
						boolOneSearch = false;
						break;
					}
				}

				if (boolOneSearch)
				{
					if (this.offers[i].CAN_BUY)
					{
						boolSearch = true;
						break;
					}
				}
			}

			return boolSearch;
		},

		setCurrent: function()
		{
			var i,
				j = 0,
				strName = '',
				arShowValues = false,
				arCanBuyValues = [],
				arFilter = {},
				tmpFilter = [],
				current = this.offers[this.offerNum].TREE;

			for (i = 0; i < this.treeProps.length; i++)
			{
				strName = 'PROP_' + this.treeProps[i].ID;
				arShowValues = this.getRowValues(arFilter, strName);

				if (!arShowValues)
					break;

				if (BX.util.in_array(current[strName], arShowValues))
				{
					arFilter[strName] = current[strName];
				}
				else
				{
					arFilter[strName] = arShowValues[0];
					this.offerNum = 0;
				}

				if (this.config.showAbsent)
				{
					arCanBuyValues = [];
					tmpFilter = [];
					tmpFilter = BX.clone(arFilter, true);

					for (j = 0; j < arShowValues.length; j++)
					{
						tmpFilter[strName] = arShowValues[j];

						if (this.getCanBuy(tmpFilter))
						{
							arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
				}
				else
				{
					arCanBuyValues = arShowValues;
				}

				this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}

			this.selectedValues = arFilter;
			this.changeInfo();
		},

		changeInfo: function()
		{
			var index = -1,
				j = 0,
				boolOneSearch = true,
				eventData = {
					currentId: (this.offerNum > -1 ? this.offers[this.offerNum].ID : 0),
					newId: 0
				};

			var i, offerGroupNode;

			for (i = 0; i < this.offers.length; i++)
			{
				boolOneSearch = true;

				for (j in this.selectedValues)
				{
					if (this.selectedValues[j] !== this.offers[i].TREE[j])
					{
						boolOneSearch = false;
						break;
					}
				}

				if (boolOneSearch)
				{
					index = i;
					break;
				}
			}

			if (index > -1)
			{
				if (index != this.offerNum)
				{
					this.isGift = false;
				}

				this.drawImages(this.offers[index].SLIDER);
				this.checkSliderControls(this.offers[index].SLIDER_COUNT);

				for (i = 0; i < this.offers.length; i++)
				{
					if (this.config.showOfferGroup && this.offers[i].OFFER_GROUP)
					{
						if (offerGroupNode = BX(this.visual.OFFER_GROUP + this.offers[i].ID))
						{
							offerGroupNode.style.display = (i == index ? '' : 'none');
						}
					}

					if (this.slider.controls[i].ID)
					{
						if (i === index)
						{
							this.product.slider = this.slider.controls[i];
							this.slider.controls[i].CONT && BX.show(this.slider.controls[i].CONT);
						}
						else
						{
							this.slider.controls[i].CONT && BX.hide(this.slider.controls[i].CONT);
						}
					}
					else if (i === index)
					{
						this.product.slider = {};
					}
				}

				if (this.offers[index].SLIDER_COUNT > 0)
				{
					this.setMainPict(this.offers[index].ID, this.offers[index].SLIDER[0].ID, true);
				}
				else
				{
					this.setMainPictFromItem(index);
				}

				if (this.offers[index].SLIDER_COUNT > 1)
				{
					this.initSlider();
				}
				else
				{
					this.stopSlider();
				}

				if (this.config.showSkuProps)
				{
					if (this.obSkuProps)
					{
						if (!this.offers[index].DISPLAY_PROPERTIES)
						{
							BX.adjust(this.obSkuProps, {style: {display: 'none'}, html: ''});
						}
						else
						{
							BX.adjust(this.obSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES});
						}
					}

					if (this.obMainSkuProps)
					{
						if (!this.offers[index].DISPLAY_PROPERTIES_MAIN_BLOCK)
						{
							BX.adjust(this.obMainSkuProps, {style: {display: 'none'}, html: ''});
						}
						else
						{
							BX.adjust(this.obMainSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES_MAIN_BLOCK});
						}
					}
				}

				this.offerNum = index;
				this.quantitySet(index);
				this.setPrice();
				this.setCompared(this.offers[index].COMPARED);

				this.fixFontCheck();
				this.setAnalyticsDataLayer('showDetail');
				this.incViewedCounter();

				eventData.newId = this.offers[this.offerNum].ID;
				// only for compatible catalog.store.amount custom templates
				BX.onCustomEvent('onCatalogStoreProductChange', [this.offers[this.offerNum].ID]);
				// new event
				BX.onCustomEvent('onCatalogElementChangeOffer', [eventData]);
				eventData = null;

				this.getStoreList(); // ruri@uvelirsoft.ru
				BX.bind(BX('all_size_link'), 'click', this.getStoreList.bind(this, {razmer:'all'}) );

				this.changeValuesInPopup(); // ugrm@uvelirsoft.ru
				updateDropdown(); // ugrm@uvelirsoft.ru
			}
		},

		drawImages: function(images)
		{
			if (!this.node.imageContainer)
				return;

			var i, img, entities = this.getEntities(this.node.imageContainer, 'image');
			for (i in entities)
			{
				if (entities.hasOwnProperty(i) && BX.type.isDomNode(entities[i]))
				{
					BX.remove(entities[i]);
				}
			}

			for (i = 0; i < images.length; i++)
			{
				img = BX.create('IMG', {
					props: {
						src: images[i].SRC,
						alt: this.config.alt,
						title: this.config.title
					}
				});

				if (i == 0)
				{
					img.setAttribute('itemprop', 'image');
				}

				this.node.imageContainer.appendChild(
					BX.create('DIV', {
						attrs: {
							'data-entity': 'image',
							'data-id': images[i].ID
						},
						props: {
							className: 'product-item-detail-slider-image' + (i == 0 ? ' active' : '')
						},
						children: [img]
					})
				);
			}
		},

		restoreSticker: function()
		{
			if (this.previousStickerText)
			{
				this.redrawSticker({text: this.previousStickerText});
			}
			else
			{
				this.hideSticker();
			}
		},

		hideSticker: function()
		{
			BX.hide(BX(this.visual.STICKER_ID));
		},

		redrawSticker: function(stickerData)
		{
			stickerData = stickerData || {};
			var text = stickerData.text || '';

			var sticker = BX(this.visual.STICKER_ID);
			if (!sticker)
				return;

			BX.show(sticker);

			var previousStickerText = sticker.getAttribute('title');
			if (previousStickerText && previousStickerText != text)
			{
				this.previousStickerText = previousStickerText;
			}

			BX.adjust(sticker, {text: text, attrs: {title: text}});
		},

		checkQuantityRange: function(quantity, direction)
		{
			if (typeof quantity === 'undefined'|| this.currentPriceMode !== 'Q')
			{
				return quantity;
			}

			quantity = parseFloat(quantity);

			var nearestQuantity = quantity;
			var range, diffFrom, absDiffFrom, diffTo, absDiffTo, shortestDiff;

			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					range = this.currentQuantityRanges[i];

					if (
						parseFloat(quantity) >= parseFloat(range.SORT_FROM)
						&& (
							range.SORT_TO === 'INF'
							|| parseFloat(quantity) <= parseFloat(range.SORT_TO)
						)
					)
					{
						nearestQuantity = quantity;
						break;
					}
					else
					{
						diffFrom = parseFloat(range.SORT_FROM) - quantity;
						absDiffFrom = Math.abs(diffFrom);
						diffTo = parseFloat(range.SORT_TO) - quantity;
						absDiffTo = Math.abs(diffTo);

						if (shortestDiff === undefined || shortestDiff > absDiffFrom)
						{
							if (
								direction === undefined
								|| (direction === 'up' && diffFrom > 0)
								|| (direction === 'down' && diffFrom < 0)
							)
							{
								shortestDiff = absDiffFrom;
								nearestQuantity = parseFloat(range.SORT_FROM);
							}
						}

						if (shortestDiff === undefined || shortestDiff > absDiffTo)
						{
							if (
								direction === undefined
								|| (direction === 'up' && diffFrom > 0)
								|| (direction === 'down' && diffFrom < 0)
							)
							{
								shortestDiff = absDiffTo;
								nearestQuantity = parseFloat(range.SORT_TO);
							}
						}
					}
				}
			}

			return nearestQuantity;
		},

		checkPriceRange: function(quantity)
		{
			if (typeof quantity === 'undefined'|| this.currentPriceMode !== 'Q')
			{
				return;
			}

			var range, found = false;

			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					range = this.currentQuantityRanges[i];

					if (
						parseFloat(quantity) >= parseFloat(range.SORT_FROM)
						&& (
							range.SORT_TO === 'INF'
							|| parseFloat(quantity) <= parseFloat(range.SORT_TO)
						)
					)
					{
						found = true;
						this.currentQuantityRangeSelected = range.HASH;
						break;
					}
				}
			}

			if (!found && (range = this.getMinPriceRange()))
			{
				this.currentQuantityRangeSelected = range.HASH;
			}

			for (var k in this.currentPrices)
			{
				if (this.currentPrices.hasOwnProperty(k))
				{
					if (this.currentPrices[k].QUANTITY_HASH == this.currentQuantityRangeSelected)
					{
						this.currentPriceSelected = k;
						break;
					}
				}
			}
		},

		getMinPriceRange: function()
		{
			var range;

			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					if (
						!range
						|| parseInt(this.currentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)
					)
					{
						range = this.currentQuantityRanges[i];
					}
				}
			}

			return range;
		},

		checkQuantityControls: function()
		{
			if (!this.obQuantity)
				return;

			var reachedTopLimit = this.checkQuantity && parseFloat(this.obQuantity.value) + this.stepQuantity > this.maxQuantity,
				reachedBottomLimit = parseFloat(this.obQuantity.value) - this.stepQuantity < this.minQuantity;

			if (reachedTopLimit)
			{
				BX.addClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
			}
			else if (BX.hasClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled'))
			{
				BX.removeClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
			}

			if (reachedBottomLimit)
			{
				BX.addClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
			}
			else if (BX.hasClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled'))
			{
				BX.removeClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
			}

			if (reachedTopLimit && reachedBottomLimit)
			{
				this.obQuantity.setAttribute('disabled', 'disabled');
			}
			else
			{
				this.obQuantity.removeAttribute('disabled');
			}
		},

		setPrice: function()
		{
			var economyInfo = '', price;

			if (this.obQuantity)
			{
				this.checkPriceRange(this.obQuantity.value);
			}

			this.checkQuantityControls();

			price = this.currentPrices[this.currentPriceSelected];

			if (this.isGift)
			{
				price.PRICE = 0;
				price.DISCOUNT = price.BASE_PRICE;
				price.PERCENT = 100;
			}

			if (this.obPrice.price)
			{
				if (price)
				{
					BX.adjust(this.obPrice.price, {html: BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true)});
					this.smallCardNodes.price && BX.adjust(this.smallCardNodes.price, {
						html: BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true)
					});
				}
				else
				{
					BX.adjust(this.obPrice.price, {html: ''});
					this.smallCardNodes.price && BX.adjust(this.smallCardNodes.price, {html: ''});
				}


				if (price && price.RATIO_PRICE !== price.RATIO_BASE_PRICE)
				{
					if (this.config.showOldPrice)
					{
						this.obPrice.full && BX.adjust(this.obPrice.full, {
							style: {display: ''},
							html: BX.Currency.currencyFormat(price.RATIO_BASE_PRICE, price.CURRENCY, true)
						});
						this.smallCardNodes.oldPrice && BX.adjust(this.smallCardNodes.oldPrice, {
							style: {display: ''},
							html: BX.Currency.currencyFormat(price.RATIO_BASE_PRICE, price.CURRENCY, true)
						});

						if (this.obPrice.discount)
						{
							economyInfo = BX.message('ECONOMY_INFO_MESSAGE');
							economyInfo = economyInfo.replace('#ECONOMY#', BX.Currency.currencyFormat(price.RATIO_DISCOUNT, price.CURRENCY, true));
							BX.adjust(this.obPrice.discount, {style: {display: ''}, html: economyInfo});
						}
					}

					if (this.config.showPercent)
					{
						this.obPrice.percent && BX.adjust(this.obPrice.percent, {
							style: {display: ''},
							html: -price.PERCENT + '%'
						});
					}
				}
				else
				{
					if (this.config.showOldPrice)
					{
						this.obPrice.full && BX.adjust(this.obPrice.full, {style: {display: 'none'}, html: ''});
						this.smallCardNodes.oldPrice && BX.adjust(this.smallCardNodes.oldPrice, {style: {display: 'none'}, html: ''});
						this.obPrice.discount && BX.adjust(this.obPrice.discount, {style: {display: 'none'}, html: ''});
					}

					if (this.config.showPercent)
					{
						this.obPrice.percent && BX.adjust(this.obPrice.percent, {style: {display: 'none'}, html: ''});
					}
				}

				if (this.obPrice.total)
				{
					if (price && this.obQuantity && this.obQuantity.value != this.stepQuantity)
					{
						BX.adjust(this.obPrice.total, {
							html: BX.message('PRICE_TOTAL_PREFIX') + ' <strong>'
							+ BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true)
							+ '</strong>',
							style: {display: ''}
						});
					}
					else
					{
						BX.adjust(this.obPrice.total, {
							html: '',
							style: {display: 'none'}
						});
					}
				}

				// ruri@uvelirsoft.ru  -  акции (старая цена)
				if ( !!this.offers[this.offerNum]["ACTIONS"]["AKTSIYA"] ) {
					this.obPrice.full && BX.adjust(this.obPrice.full, {
						style: {display: ''},
						html: BX.Currency.currencyFormat(this.offers[this.offerNum]["ACTIONS"]['STARAYA_TSENA'], price.CURRENCY, true)
					});
					this.obPrice.discount && BX.adjust(this.obPrice.discount, {style: {display: ''}, html: this.offers[this.offerNum]["ACTIONS"]["AKTSIYA"]});
				}else{
					BX.adjust(this.obPrice.full, {html: '',style: {display: 'none'}});
					BX.adjust(this.obPrice.discount, {html: '',style: {display: 'none'}});
				}

			}

			this.changeValuesInPopup(); // ugrm@uvelirsoft.ru
		},

		compare: function(event)
		{
			var checkbox = this.obCompare.querySelector('[data-entity="compare-checkbox"]'),
				target = BX.getEventTarget(event),
				checked = true;

			if (checkbox)
			{
				checked = target === checkbox ? checkbox.checked : !checkbox.checked;
			}

			var url = checked ? this.compareData.compareUrl : this.compareData.compareDeleteUrl,
				compareLink;

			if (url)
			{
				if (target !== checkbox)
				{
					BX.PreventDefault(event);
					this.setCompared(checked);
				}

				switch (this.productType)
				{
					case 0: // no catalog
					case 1: // product
					case 2: // set
						compareLink = url.replace('#ID#', this.product.id.toString());
						break;
					case 3: // sku
						compareLink = url.replace('#ID#', this.offers[this.offerNum].ID);
						break;
				}

				BX.ajax({
					method: 'POST',
					dataType: checked ? 'json' : 'html',
					url: compareLink + (compareLink.indexOf('?') !== -1 ? '&' : '?') + 'ajax_action=Y',
					onsuccess: checked
						? BX.proxy(this.compareResult, this)
						: BX.proxy(this.compareDeleteResult, this)
				});
			}
		},

		compareResult: function(result)
		{
			var popupContent, popupButtons;

			if (this.obPopupWin)
			{
				this.obPopupWin.close();
			}

			if (!BX.type.isPlainObject(result))
				return;

			this.initPopupWindow();

			if (this.offers.length > 0)
			{
				this.offers[this.offerNum].COMPARED = result.STATUS === 'OK';
			}

			if (result.STATUS === 'OK')
			{
				BX.onCustomEvent('OnCompareChange');

				popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
					+ BX.message('COMPARE_MESSAGE_OK')
					+ '</p></div>';

				if (this.config.showClosePopup)
				{
					popupButtons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
							events: {
								click: BX.delegate(this.compareRedirect, this)
							},
							style: {marginRight: '10px'}
						}),
						new BasketButton({
							text: BX.message('BTN_MESSAGE_CLOSE_POPUP'),
							events: {
								click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
							}
						})
					];
				}
				else
				{
					popupButtons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
							events: {
								click: BX.delegate(this.compareRedirect, this)
							}
						})
					];
				}
			}
			else
			{
				popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
					+ (result.MESSAGE ? result.MESSAGE : BX.message('COMPARE_UNKNOWN_ERROR'))
					+ '</p></div>';
				popupButtons = [
					new BasketButton({
						text: BX.message('BTN_MESSAGE_CLOSE'),
						events: {
							click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
						}
					})
				];
			}

			this.obPopupWin.setTitleBar(BX.message('COMPARE_TITLE'));
			this.obPopupWin.setContent(popupContent);
			this.obPopupWin.setButtons(popupButtons);
			this.obPopupWin.show();
		},

		compareDeleteResult: function()
		{
			BX.onCustomEvent('OnCompareChange');

			if (this.offers && this.offers.length)
			{
				this.offers[this.offerNum].COMPARED = false;
			}
		},

		setCompared: function(state)
		{
			if (!this.obCompare)
				return;

			var checkbox = this.getEntity(this.obCompare, 'compare-checkbox');
			if (checkbox)
			{
				checkbox.checked = state;
			}
		},

		setCompareInfo: function(comparedIds)
		{
			if (!BX.type.isArray(comparedIds))
				return;

			for (var i in this.offers)
			{
				if (this.offers.hasOwnProperty(i))
				{
					this.offers[i].COMPARED = BX.util.in_array(this.offers[i].ID, comparedIds);
				}
			}
		},

		compareRedirect: function()
		{
			if (this.compareData.comparePath)
			{
				location.href = this.compareData.comparePath;
			}
			else
			{
				this.obPopupWin.close();
			}
		},

		checkDeletedCompare: function(id)
		{
			switch (this.productType)
			{
				case 0: // no catalog
				case 1: // product
				case 2: // set
					if (this.product.id == id)
					{
						this.setCompared(false);
					}

					break;
				case 3: // sku
					var i = this.offers.length;
					while (i--)
					{
						if (this.offers[i].ID == id)
						{
							this.offers[i].COMPARED = false;

							if (this.offerNum == i)
							{
								this.setCompared(false);
							}

							break;
						}
					}
			}
		},

		initBasketUrl: function()
		{
			this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);
			switch (this.productType)
			{
				case 1: // product
				case 2: // set
					this.basketUrl = this.basketUrl.replace('#ID#', this.product.id.toString());
					break;
				case 3: // sku
					this.basketUrl = this.basketUrl.replace('#ID#', this.offers[this.offerNum].ID);
					break;
			}

			this.basketParams = {
				'ajax_basket': 'Y'
			};

			if (this.config.showQuantity)
			{
				this.basketParams[this.basketData.quantity] = this.obQuantity.value;
			}

			if (this.basketData.sku_props)
			{
				this.basketParams[this.basketData.sku_props_var] = this.basketData.sku_props;
			}
		},

		fillBasketProps: function()
		{
			if (!this.visual.BASKET_PROP_DIV)
				return;

			var
				i = 0,
				propCollection = null,
				foundValues = false,
				obBasketProps = null;

			if (this.basketData.useProps && !this.basketData.emptyProps)
			{
				if (this.obPopupWin && this.obPopupWin.contentContainer)
				{
					obBasketProps = this.obPopupWin.contentContainer;
				}
			}
			else
			{
				obBasketProps = BX(this.visual.BASKET_PROP_DIV);
			}

			if (obBasketProps)
			{
				propCollection = obBasketProps.getElementsByTagName('select');
				if (propCollection && propCollection.length)
				{
					for (i = 0; i < propCollection.length; i++)
					{
						if (!propCollection[i].disabled)
						{
							switch (propCollection[i].type.toLowerCase())
							{
								case 'select-one':
									this.basketParams[propCollection[i].name] = propCollection[i].value;
									foundValues = true;
									break;
								default:
									break;
							}
						}
					}
				}

				propCollection = obBasketProps.getElementsByTagName('input');
				if (propCollection && propCollection.length)
				{
					for (i = 0; i < propCollection.length; i++)
					{
						if (!propCollection[i].disabled)
						{
							switch (propCollection[i].type.toLowerCase())
							{
								case 'hidden':
									this.basketParams[propCollection[i].name] = propCollection[i].value;
									foundValues = true;
									break;
								case 'radio':
									if (propCollection[i].checked)
									{
										this.basketParams[propCollection[i].name] = propCollection[i].value;
										foundValues = true;
									}
									break;
								default:
									break;
							}
						}
					}
				}
			}

			if (!foundValues)
			{
				this.basketParams[this.basketData.props] = [];
				this.basketParams[this.basketData.props][0] = 0;
			}
		},

		sendToBasket: function()
		{
			if (!this.canBuy)
				return;

			this.initBasketUrl();
			this.fillBasketProps();
			BX.ajax({
				method: 'POST',
				dataType: 'json',
				url: this.basketUrl,
				data: this.basketParams,
				onsuccess: BX.proxy(this.basketResult, this)
			});
		},

		add2Basket: function()
		{
			this.basketMode = 'ADD';
			this.basket();
		},

		buyBasket: function()
		{
			this.basketMode = 'BUY';
			this.basket();
		},

		basket: function()
		{
			var contentBasketProps = '';

			if (!this.canBuy)
				return;

			switch (this.productType)
			{
				case 1: // product
				case 2: // set
					if (this.basketData.useProps && !this.basketData.emptyProps)
					{
						this.initPopupWindow();
						this.obPopupWin.setTitleBar(BX.message('TITLE_BASKET_PROPS'));

						if (BX(this.visual.BASKET_PROP_DIV))
						{
							contentBasketProps = BX(this.visual.BASKET_PROP_DIV).innerHTML;
						}

						this.obPopupWin.setContent(contentBasketProps);
						this.obPopupWin.setButtons([
							new BasketButton({
								text: BX.message('BTN_SEND_PROPS'),
								events: {
									click: BX.delegate(this.sendToBasket, this)
								}
							})
						]);
						this.obPopupWin.show();
					}
					else
					{
						this.sendToBasket();
					}
					break;
				case 3: // sku
					this.sendToBasket();
					break;
			}
		},

		basketResult: function(arResult)
		{
			var popupContent, popupButtons, productPict;

			if (this.obPopupWin)
			{
				this.obPopupWin.close();
			}

			if (!BX.type.isPlainObject(arResult))
				return;

			if (arResult.STATUS === 'OK')
			{
				this.setAnalyticsDataLayer('addToCart');
			}

			if (arResult.STATUS === 'OK' && this.basketMode === 'BUY')
			{
				this.basketRedirect();
			}
			else
			{
				this.initPopupWindow();

				if (arResult.STATUS === 'OK')
				{
					BX.onCustomEvent('OnBasketChange');
					switch (this.productType)
					{
						case 1: // product
						case 2: // set
							productPict = this.product.pict.SRC;
							break;
						case 3: // sku
							productPict = this.offers[this.offerNum].PREVIEW_PICTURE
								? this.offers[this.offerNum].PREVIEW_PICTURE.SRC
								: this.defaultPict.pict.SRC;
							break;
					}

					popupContent = '<div style="width: 100%; margin: 0; text-align: center;">'
						+ '<img src="' + productPict + '" height="130" style="max-height:130px"><p>'
						+ this.product.name + '</p></div>';

					if (this.config.showClosePopup)
					{
						popupButtons = [
							new BasketButton({
								text: BX.message('BTN_MESSAGE_CLOSE_POPUP'),
								events: {
									click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
								},
								className: "btn btn-link product-item-detail-buy-button close-popup"
							}),
							new BasketButton({
								text: BX.message('BTN_MESSAGE_BASKET_REDIRECT'),
								events: {
									click: BX.delegate(this.basketRedirect, this)
								},
								style: {marginRight: '10px'},
								className: "btn btn-link product-item-detail-buy-button goto-basket"
							})
						];
					}
					else
					{
						popupButtons = [
							new BasketButton({
								text: BX.message('BTN_MESSAGE_BASKET_REDIRECT'),
								events: {
									click: BX.delegate(this.basketRedirect, this)
								}
							})
						];
					}
				}
				else
				{
					popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
						+ (arResult.MESSAGE ? arResult.MESSAGE : BX.message('BASKET_UNKNOWN_ERROR'))
						+ '</p></div>';
					popupButtons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_CLOSE'),
							events: {
								click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
							}
						})
					];
				}

				this.obPopupWin.setTitleBar(arResult.STATUS === 'OK' ? BX.message('TITLE_SUCCESSFUL') : BX.message('TITLE_ERROR'));
				this.obPopupWin.setContent(popupContent);
				this.obPopupWin.setButtons(popupButtons);
				this.obPopupWin.show();
			}
		},

		basketRedirect: function()
		{
			location.href = (this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('BASKET_URL'));
		},

		initPopupWindow: function()
		{
			if (this.obPopupWin)
				return;

			this.obPopupWin = BX.PopupWindowManager.create('CatalogElementBasket_' + this.visual.ID, null, {
				autoHide: false,
				offsetLeft: 0,
				offsetTop: 0,
				overlay: true,
				closeByEsc: true,
				titleBar: true,
				closeIcon: true,
				contentColor: 'white',
				className: this.config.templateTheme ? 'bx-' + this.config.templateTheme : ''
			});
		},

		incViewedCounter: function()
		{
			if(this.errorCode !== 0) return;

			if (this.currentIsSet && !this.updateViewedCount)
			{
				switch (this.productType)
				{
					case 1:
					case 2:
						this.viewedCounter.params.PRODUCT_ID = this.product.id;
						this.viewedCounter.params.PARENT_ID = this.product.id;
						break;
					case 3:
						this.viewedCounter.params.PARENT_ID = this.product.id;
						this.viewedCounter.params.PRODUCT_ID = this.offers[this.offerNum].ID;
						break;
					default:
						return;
				}

				this.viewedCounter.params.SITE_ID = BX.message('SITE_ID');
				this.updateViewedCount = true;
				BX.ajax.post(
					this.viewedCounter.path,
					this.viewedCounter.params,
					BX.delegate(function()
					{
						this.updateViewedCount = false;
					}, this)
				);
			}
		},

		allowViewedCount: function(update)
		{
			this.currentIsSet = true;

			if (update)
			{
				this.incViewedCounter();
			}
		},

		fixFontCheck: function()
		{
			if (BX.type.isDomNode(this.obPrice.price))
			{
				BX.FixFontSize && BX.FixFontSize.init({
					objList: [{
						node: this.obPrice.price,
						maxFontSize: 28,
						smallestValue: false,
						scaleBy: this.obPrice.price.parentNode
					}],
					onAdaptiveResize: true
				});

			}
		},
		getStoreList: function(params){
			// debugger;
			this.obStoreList = BX(this.visual.STORE_LIST_ID);
			if (!!this.obStoreList) {
				// console.log(this.offers[this.offerNum].ID);
				// console.log(this.offers[this.offerNum].TREE_PROPS_VALUES["RAZMER"]);

				var wait = BX.showWait(this.obStoreList);
				var	tree_props = this.offers[this.offerNum].TREE_PROPS_VALUES;
				if (!!params && params.razmer=='all') {
					tree_props={};
				}

				BX.ajax({
					method: 'POST',
					dataType: 'json',
					url: '/ajax/shopList.php',
					data: {
						// ID: this.offers[this.offerNum].ID,
						ID: this.product.id,
						TREE_PROPS: tree_props,
					},
					onsuccess: BX.proxy(this.setStoreList, this)
				});

			}
		},
		setStoreList: function(result){

			this.obStoreList = BX(this.visual.STORE_LIST_ID);
			BX.closeWait(this.obStoreList);
			if (result['ERROR']) {
				console.log(result['ERROR']);
				BX.adjust(this.obStoreList, {html: result});
			} else {

				var tbody = '<table class="table table-hover" style="">';

				if(Object.keys(result['SHOP_LIST']).length > 0){
					var buyIDs = {},
						shopID= false,
						buyID = false;
					for(var i in result['SHOP_LIST']){
						if (!!result['SHOP_LIST'][i]['QUANTITY'] && result['SHOP_LIST'][i]['QUANTITY']>0 ) {
							shopID = 'shop_list_row_' + i;
							tbody += '<tr onclick="toggleShopRow(this,\''+shopID+'\')">';
							tbody += '<th>';
							// tbody += '<td>' + result['SHOP_LIST'][i]['NAME'] +'<br>'+ result['SHOP_LIST'][i]['ADRESS']+'</td>';
							tbody += '<td>Заказать в интернет-магазине +7-495-788-77-22</td>';
							tbody += '<td class="nowrap">В&nbsp;наличии: ' + result['SHOP_LIST'][i]['QUANTITY'] + ' шт.</td>';
							tbody += '<td><i class="hide fa fa-chevron-up"></i>'; //<i class="fa fa-chevron-up"></i>
							tbody += '</td>';
							tbody += '</th>';
							tbody += '</tr>';

							var offers = result['SHOP_LIST'][i]['OFFERS']['RAZMER'];
							for(var size in offers){
								for(var offer in offers[size]){

								buyID = 'offer_row_' + offers[size][offer]['ID'];
								buyIDs[buyID] = {
									OFFER_ID: offers[size][offer]['ID'],
									NAME: result['SHOP_LIST'][i]['NAME'],
									ADRESS: result['SHOP_LIST'][i]['ADRESS'],
								};

/*
								tbody += '<tr class="hide shop-list-offers" data-shop="'+shopID+'">';
									tbody += '<td>'+'</td>';
									tbody += '<td>Размер: '+ size + '</td>';
									tbody += '<td class="nowrap">' + offers[size][0]['PRICE']['FORMAT_VALUE'] + '</td>';
									tbody += '<td>';
										tbody += '<a href="javascript:void(0)" class="btn btn-detial small" id="' + buyID + '">Купить</a>';
									tbody += '</td>';
									tbody += '<td>';
									tbody += '</td>';
								tbody += '</tr>';
*/
								tbody += '<tr class="shop-list-offers" data-shop="'+shopID+'">';
									tbody += '<td colspan="5">';
										tbody += '<div class="row">';
											// tbody += '<div class="col-md-3 picture">';
											// 	if(result['PRODUCT_INFO']['PICTURE']){
											// 		tbody += '<img src="'+result['PRODUCT_INFO']['PICTURE']+'" alt="'+result['PRODUCT_INFO']['NAME']+'">';
											// 	}
											// tbody += '</div>';
											tbody += '<div class="col-md-12 info">';
												tbody += '<div><h4>' + result['PRODUCT_INFO']['NAME'] + '</h4></div>';
												tbody += '<div class = "flex-container">';
												tbody += '<div class = "col-1">';
												if(result['PRODUCT_INFO']['PROPS']['ARTNUMBER']){
													tbody += '<div class = "info-articul"><span>Артикул</span>: <span>' + result['PRODUCT_INFO']['PROPS']['ARTNUMBER'] + '</span></div>';
												}
												// console.log(result);
													if(result['PRODUCT_INFO']['PROPS']['METALL']){
														tbody += '<div><span>Металл</span>: <span>' + result['PRODUCT_INFO']['PROPS']['METALL'] + '</span></div>';
													}
													if(result['PRODUCT_INFO']['PROPS']['PROBA']){
														tbody += '<div><span>Проба</span>: <span>' + result['PRODUCT_INFO']['PROPS']['PROBA'] + '</span></div>';
													}
												tbody += '</div>';
												tbody += '<div class = "col-2">';
												for(var propCode in offers[size][offer]['PROPERTIES']){
													if(offers[size][offer]['PROPERTIES'][propCode]['VALUE']){
														tbody += '<div><span>' + offers[size][offer]['PROPERTIES'][propCode]['NAME'] + '</span>: <span>' + offers[size][offer]['PROPERTIES'][propCode]['VALUE'] + '</span></div>';
													}
												}
												tbody += '</div>';
												tbody += '<div class = "col-3">';
												tbody += '<div><span>Адрес</span>: <span>'+ result['SHOP_LIST'][i]['ADRESS']+'</span></div>';
												tbody += '<div><span>Телефон</span>: <span>'+result['SHOP_LIST'][i]['PHONE'] +'</span></div>';
												tbody += '</div>';

												tbody += '</div>';

											tbody += '</div>';
										tbody += '</div>';
										tbody += '<div class="row">';

											tbody += '<div class="col-md-12 flex-container">';
											if (offers[size][offer]['AKTSIYA']) {
												tbody += '<div class = "action">';
												tbody += offers[size][offer]['AKTSIYA'];
												tbody += '</div>';
											}

												tbody += '<div class = "price">';
													if (offers[size][offer]['AKTSIYA']) {

														tbody += '<div class = "old-price">';
															tbody += 'Цена: ' + BX.Currency.currencyFormat(offers[size][offer]['STARAYA_TSENA'], 'RUB', true);
														tbody += '</div>';
														tbody += '<div class = "action">Цена: ' + offers[size][offer]['PRICE']['FORMAT_VALUE'] + '</div>';
													}
													else{
														tbody += '<div + class>Цена: ' + offers[size][offer]['PRICE']['FORMAT_VALUE'] + '</div>';
													}

												tbody += '</div>';
												tbody += '<div class = "btns">';
													tbody += '<a href="javascript:void(0)" class="btn btn-detial small" id="' + buyID + '">В корзину</a>';
													var offer_razm=offers[size][0]['PROPERTIES']['RAZMER']['VALUE'];
													var offer_vstavka=offers[size][0]['PROPERTIES']['VSTAVKA']['VALUE'];
													var offer_cena=offers[size][offer]['PRICE']['FORMAT_VALUE'];
													var onclick="uploadPopup('"+offer_razm+"','"+offer_vstavka+"','"+offer_cena+"');";
													tbody += '<a href="javascript:void(0)" onclick="'+onclick+'" class="btn-cart-sale btn btn-default product-item-detail-buy-button" id="">Купить в 1 клик</a>';

													tbody += '</div>';
											tbody += '</div>';
										tbody += '</div>';
										tbody += '<hr>';
									tbody += '</td>';
								tbody += '</tr>';
							}
							}
						}
					}
				}else{
					tbody += '<tr>';
						tbody += '<td colspan="5">Нет в наличии</td>';
					tbody += '</tr>';
				}
				tbody += '</table>';

				if(!!this.obStoreList){
					// BX.adjust(athis.obStoreList, {style: {display: ''}});
					BX.adjust(this.obStoreList, {html: tbody});

					var obShopLink = false,
						nthis = false;

					for (var buyID in buyIDs){
						obShopLink = BX(buyID);
						if(!!obShopLink){

							var tree_props_tmp;
							for (i = 0; i < this.offers.length; i++) {
								if ( this.offers[i].ID==buyIDs[buyID].OFFER_ID ) {
									tree_props_tmp = this.offers[i].TREE_PROPS_VALUES;
								}
							}

							BX.bind(obShopLink, 'click', BX.proxy(this.addShop2basket, {
								'AJAX': 'Y',
								'OFFER_ID': buyIDs[buyID].OFFER_ID,
								'TREE_PROPS': tree_props_tmp,
								'SALON': {
									'NAME': 'Магазин',
									// 'VALUE': buyIDs[buyID].NAME + ' (' + buyIDs[buyID].ADRESS + ')',
									'VALUE': buyIDs[buyID].ADRESS,
									'CODE': 'SALON',
									'SORT': 1000
								}
							}));
						}
					}
				}
			}
		},

		addShop2basket: function(){
			BX.ajax({
				method: 'POST',
				dataType: 'json',
				async: false,
				url: '/ajax/add2basket.php',
				data: this,
				onsuccess: function(result){
					eval(obCatalogElementName).basketResult(result);
				}
			});
		},

		changeValuesInPopup: function()
		{
			/* ugrm@uvelirsoft.ru */
			if(BX('popupToOrderInOneClick')){
				var arOffer = this.offers[this.offerNum];

				if(arOffer['TREE_PROPS_VALUES']['RAZMER']){
					$('#popupToOrderInOneClick #razmer').val(arOffer['TREE_PROPS_VALUES']['RAZMER']);
				}

				if(arOffer['TREE_PROPS_VALUES']['VSTAVKA']){
					$('#popupToOrderInOneClick #vstavka').val(arOffer['TREE_PROPS_VALUES']['VSTAVKA']);
				}

				var price = this.currentPrices[this.currentPriceSelected],
					quantity = this.obQuantity.value;

				if(price && this.obQuantity){
					$('#popupToOrderInOneClick #tovar_quantity').val(this.obQuantity.value);
					$('#popupToOrderInOneClick #tovar_price').val(BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true));
				}
			}
		}
		/* ugrm@uvelirsoft.ru */
	}
})(window);

$(function(){
	$('#sale-on-ckick').click(function(){
	   showPopup();
	});




	






});
/* End */
;
; /* Start:"a:4:{s:4:"full";s:82:"/bitrix/components/bitrix/iblock.vote/templates/stars/script.min.js?15723421044586";s:6:"source";s:63:"/bitrix/components/bitrix/iblock.vote/templates/stars/script.js";s:3:"min";s:67:"/bitrix/components/bitrix/iblock.vote/templates/stars/script.min.js";s:3:"map";s:67:"/bitrix/components/bitrix/iblock.vote/templates/stars/script.map.js";}"*/
(function(t){if(t.JCIblockVoteStars)return;t.JCIblockVoteStars=function(t){this.progressObj=null;this.ratingObj=null;this.starsObj=null;this.progressId="";this.ratingId="";this.starsId="";this.ajaxParams={};this.siteId="";this.voteData={element:0,percent:0,count:0};this.config={readOnly:false,alreadyVoted:true,request:false};if(BX.type.isPlainObject(t)){if(BX.type.isNotEmptyString(t.progressId))this.progressId=t.progressId;if(BX.type.isNotEmptyString(t.ratingId))this.ratingId=t.ratingId;if(BX.type.isNotEmptyString(t.starsId))this.starsId=t.starsId;if(BX.type.isNotEmptyString(t.ajaxUrl))this.ajaxUrl=t.ajaxUrl;if(BX.type.isNotEmptyString(t.checkVoteUrl))this.checkVoteUrl=t.checkVoteUrl;if(BX.type.isPlainObject(t.ajaxParams))this.ajaxParams=t.ajaxParams;if(BX.type.isNotEmptyString(t.siteId))this.siteId=t.siteId;if(BX.type.isPlainObject(t.voteData)){if(BX.type.isNumber(t.voteData.element))this.voteData.element=t.voteData.element;if(BX.type.isNumber(t.voteData.percent))this.voteData.percent=this.preparePercent(t.voteData.percent);if(BX.type.isNumber(t.voteData.count))this.voteData.count=t.voteData.count}if(BX.type.isBoolean(t.readOnly))this.config.readOnly=t.readOnly}BX.ready(BX.proxy(this.init,this))};t.JCIblockVoteStars.prototype.init=function(){if(BX.type.isNotEmptyString(this.progressId))this.progressObj=BX(this.progressId);if(BX.type.isNotEmptyString(this.ratingId))this.ratingObj=BX(this.ratingId);if(BX.type.isNotEmptyString(this.starsId))this.starsObj=BX(this.starsId);this.showProgress(this.voteData.percent);this.showVotes();this.checkVote()};t.JCIblockVoteStars.prototype.checkVote=function(){if(this.config.readOnly||this.voteData.element<=0)return;BX.ajax({timeout:30,method:"POST",dataType:"json",url:this.checkVoteUrl,data:{sessid:BX.bitrix_sessid(),checkVote:"Y",vote_id:this.voteData.element,site_id:this.siteId},onsuccess:BX.proxy(this.checkVoteResult,this)})};t.JCIblockVoteStars.prototype.checkVoteResult=function(t){if(BX.type.isPlainObject(t)){if(t.success)this.config.alreadyVoted=t.voted}if(this.config.readOnly||this.config.alreadyVoted||this.voteData.element<=0)return;if(BX.type.isElementNode(this.starsObj)){BX.bind(this.starsObj,"mousemove",BX.proxy(this.handlerMouseMove,this));BX.bind(this.starsObj,"mouseout",BX.proxy(this.handlerMouseOut,this));BX.bind(this.starsObj,"click",BX.proxy(this.handlerClick,this))}};t.JCIblockVoteStars.prototype.destroy=function(){if(BX.type.isElementNode(this.progressObj))BX.unbindAll(this.progressObj);this.progressObj=null;if(BX.type.isElementNode(this.ratingObj))BX.unbindAll(this.ratingObj);this.ratingObj=null;if(BX.type.isElementNode(this.starsObj))BX.unbindAll(this.starsObj);this.starsObj=null};t.JCIblockVoteStars.prototype.preparePercent=function(t){t=parseInt(t,10);if(isNaN(t))t=0;else if(t>100)t=100;else if(t<0)t=0;return t};t.JCIblockVoteStars.prototype.showProgress=function(t){if(!BX.type.isElementNode(this.progressObj))return;BX.style(this.progressObj,"width",t.toString()+"%")};t.JCIblockVoteStars.prototype.showVotes=function(){if(!BX.type.isElementNode(this.ratingObj))return;this.ratingObj.innerHTML="( "+this.voteData.count+" )"};t.JCIblockVoteStars.prototype.handlerMouseMove=function(e){var s,i;if(this.config.readOnly||this.config.alreadyVoted||this.config.request)return;e=e||t.event;if(!BX.type.isElementNode(this.starsObj))return;s=BX.pos(this.starsObj);i=(e.pageX-s.left)/s.width*5;this.showProgress(this.preparePercent(Math.ceil(i)*20))};t.JCIblockVoteStars.prototype.handlerMouseOut=function(){if(this.config.readOnly||this.config.alreadyVoted||this.config.request)return;this.showProgress(this.voteData.percent)};t.JCIblockVoteStars.prototype.handlerClick=function(e){var s,i;if(this.config.readOnly||this.config.alreadyVoted||this.config.request)return;this.config.request=true;e=e||t.event;if(!BX.type.isElementNode(this.starsObj))return;s=BX.pos(this.starsObj);i=parseInt(Math.ceil((e.pageX-s.left)/s.width*5),10);if(isNaN(i))return;this.ajaxParams.rating=i-1;this.ajaxParams.vote="Y";this.ajaxParams.vote_id=this.voteData.element;this.ajaxParams.sessid=BX.bitrix_sessid();this.ajaxParams.site_id=this.siteId;BX.ajax({timeout:30,method:"POST",dataType:"json",url:this.ajaxUrl,data:this.ajaxParams,onsuccess:BX.proxy(this.clickResult,this)})};t.JCIblockVoteStars.prototype.clickResult=function(t){this.config.request=false;if(BX.type.isPlainObject(t)){this.config.alreadyVoted=true;this.voteData.percent=this.preparePercent(t.value*20);this.voteData.count=t.votes;this.showProgress(this.voteData.percent);this.showVotes()}}})(window);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:100:"/bitrix/components/bitrix/catalog.product.subscribe/templates/.default/script.min.js?157234209211818";s:6:"source";s:80:"/bitrix/components/bitrix/catalog.product.subscribe/templates/.default/script.js";s:3:"min";s:84:"/bitrix/components/bitrix/catalog.product.subscribe/templates/.default/script.min.js";s:3:"map";s:84:"/bitrix/components/bitrix/catalog.product.subscribe/templates/.default/script.map.js";}"*/
(function(e){if(!!e.JCCatalogProductSubscribe){return}var t=function(e){t.superclass.constructor.apply(this,arguments);this.nameNode=BX.create("span",{props:{id:this.id},style:typeof e.style==="object"?e.style:{},text:e.text});this.buttonNode=BX.create("span",{attrs:{className:e.className},style:{marginBottom:"0",borderBottom:"0 none transparent"},children:[this.nameNode],events:this.contextEvents});if(BX.browser.IsIE()){this.buttonNode.setAttribute("hideFocus","hidefocus")}};BX.extend(t,BX.PopupWindowButton);e.JCCatalogProductSubscribe=function(e){this.buttonId=e.buttonId;this.buttonClass=e.buttonClass;this.jsObject=e.jsObject;this.ajaxUrl="/bitrix/components/bitrix/catalog.product.subscribe/ajax.php";this.alreadySubscribed=e.alreadySubscribed;this.listIdAlreadySubscribed=e.listIdAlreadySubscribed;this.urlListSubscriptions=e.urlListSubscriptions;this.listOldItemId={};this.landingId=e.landingId;this.elemButtonSubscribe=null;this.elemPopupWin=null;this.defaultButtonClass="bx-catalog-subscribe-button";this._elemButtonSubscribeClickHandler=BX.delegate(this.subscribe,this);this._elemHiddenClickHandler=BX.delegate(this.checkSubscribe,this);BX.ready(BX.delegate(this.init,this))};e.JCCatalogProductSubscribe.prototype.init=function(){if(!!this.buttonId){this.elemButtonSubscribe=BX(this.buttonId);this.elemHiddenSubscribe=BX(this.buttonId+"_hidden")}if(!!this.elemButtonSubscribe){BX.bind(this.elemButtonSubscribe,"click",this._elemButtonSubscribeClickHandler)}if(!!this.elemHiddenSubscribe){BX.bind(this.elemHiddenSubscribe,"click",this._elemHiddenClickHandler)}this.setButton(this.alreadySubscribed);this.setIdAlreadySubscribed(this.listIdAlreadySubscribed)};e.JCCatalogProductSubscribe.prototype.checkSubscribe=function(){if(!this.elemHiddenSubscribe||!this.elemButtonSubscribe)return;if(this.listOldItemId.hasOwnProperty(this.elemButtonSubscribe.dataset.item)){this.setButton(true)}else{BX.ajax({method:"POST",dataType:"json",url:this.ajaxUrl,data:{sessid:BX.bitrix_sessid(),checkSubscribe:"Y",itemId:this.elemButtonSubscribe.dataset.item},onsuccess:BX.delegate(function(e){if(e.subscribe){this.setButton(true);this.listOldItemId[this.elemButtonSubscribe.dataset.item]=true}else{this.setButton(false)}},this)})}};e.JCCatalogProductSubscribe.prototype.subscribe=function(){this.elemButtonSubscribe=BX.proxy_context;if(!this.elemButtonSubscribe)return false;BX.ajax({method:"POST",dataType:"json",url:this.ajaxUrl,data:{sessid:BX.bitrix_sessid(),subscribe:"Y",itemId:this.elemButtonSubscribe.dataset.item,siteId:BX.message("SITE_ID"),landingId:this.landingId},onsuccess:BX.delegate(function(e){if(e.success){this.createSuccessPopup(e);this.setButton(true);this.listOldItemId[this.elemButtonSubscribe.dataset.item]=true}else if(e.contactFormSubmit){this.initPopupWindow();this.elemPopupWin.setTitleBar(BX.message("CPST_SUBSCRIBE_POPUP_TITLE"));var s=this.createContentForPopup(e);this.elemPopupWin.setContent(s);this.elemPopupWin.setButtons([new t({text:BX.message("CPST_SUBSCRIBE_BUTTON_NAME"),className:"btn btn-primary",events:{click:BX.delegate(function(){if(!this.validateContactField(e.contactTypeData)){return false}BX.ajax.submitAjax(s,{method:"POST",url:this.ajaxUrl,processData:true,onsuccess:BX.delegate(function(e){e=BX.parseJSON(e,{});if(e.success){this.createSuccessPopup(e);this.setButton(true);this.listOldItemId[this.elemButtonSubscribe.dataset.item]=true}else if(e.error){if(e.hasOwnProperty("setButton")){this.listOldItemId[this.elemButtonSubscribe.dataset.item]=true;this.setButton(true)}var t=e.message;if(e.hasOwnProperty("typeName")){t=e.message.replace("USER_CONTACT",e.typeName)}BX("bx-catalog-subscribe-form-notify").style.color="red";BX("bx-catalog-subscribe-form-notify").innerHTML=t}},this)})},this)}}),new t({text:BX.message("CPST_SUBSCRIBE_BUTTON_CLOSE"),className:"btn",events:{click:BX.delegate(function(){this.elemPopupWin.destroy()},this)}})]);this.elemPopupWin.show()}else if(e.error){if(e.hasOwnProperty("setButton")){this.listOldItemId[this.elemButtonSubscribe.dataset.item]=true;this.setButton(true)}this.showWindowWithAnswer({status:"error",message:e.message})}},this)})};e.JCCatalogProductSubscribe.prototype.validateContactField=function(e){var t=BX.findChildren(BX("bx-catalog-subscribe-form"),{tag:"input",attribute:{id:"userContact"}},true);if(!t.length||typeof e!=="object"){BX("bx-catalog-subscribe-form-notify").style.color="red";BX("bx-catalog-subscribe-form-notify").innerHTML=BX.message("CPST_SUBSCRIBE_VALIDATE_UNKNOW_ERROR");return false}var s,a,i,r=[],o=[];for(var c=0;c<t.length;c++){s=t[c].getAttribute("data-id");a=t[c].value;i=BX("bx-contact-use-"+s);if(i&&i.value=="N"){o.push(true);continue}if(!a.length){r.push(BX.message("CPST_SUBSCRIBE_VALIDATE_ERROR_EMPTY_FIELD").replace("#FIELD#",e[s].contactLable))}}if(t.length==o.length){BX("bx-catalog-subscribe-form-notify").style.color="red";BX("bx-catalog-subscribe-form-notify").innerHTML=BX.message("CPST_SUBSCRIBE_VALIDATE_ERROR");return false}if(r.length){BX("bx-catalog-subscribe-form-notify").style.color="red";for(var n=0;n<r.length;n++){BX("bx-catalog-subscribe-form-notify").innerHTML=r[n]}return false}return true};e.JCCatalogProductSubscribe.prototype.reloadCaptcha=function(){BX.ajax.get(this.ajaxUrl+"?reloadCaptcha=Y","",function(e){BX("captcha_sid").value=e;BX("captcha_img").src="/bitrix/tools/captcha.php?captcha_sid="+e+""})};e.JCCatalogProductSubscribe.prototype.createContentForPopup=function(e){if(!e.hasOwnProperty("contactTypeData")){return null}var t=e.contactTypeData,s=Object.keys(t).length,a="",i="N",r=document.createDocumentFragment();if(s>1){i="Y";a="display:none;";r.appendChild(BX.create("p",{text:BX.message("CPST_SUBSCRIBE_MANY_CONTACT_NOTIFY")}))}r.appendChild(BX.create("p",{props:{id:"bx-catalog-subscribe-form-notify"}}));for(var o in t){if(s>1){r.appendChild(BX.create("div",{props:{className:"bx-catalog-subscribe-form-container"},children:[BX.create("div",{props:{className:"checkbox"},children:[BX.create("lable",{props:{className:"bx-filter-param-label"},attrs:{onclick:this.jsObject+".selectContactType("+o+", event);"},children:[BX.create("input",{props:{type:"hidden",id:"bx-contact-use-"+o,name:"contact["+o+"][use]",value:"N"}}),BX.create("input",{props:{id:"bx-contact-checkbox-"+o,type:"checkbox"}}),BX.create("span",{props:{className:"bx-filter-param-text"},text:t[o].contactLable})]})]})]}))}r.appendChild(BX.create("div",{props:{id:"bx-catalog-subscribe-form-container-"+o,className:"bx-catalog-subscribe-form-container",style:a},children:[BX.create("div",{props:{className:"bx-catalog-subscribe-form-container-label"},text:BX.message("CPST_SUBSCRIBE_LABLE_CONTACT_INPUT").replace("#CONTACT#",t[o].contactLable)}),BX.create("div",{props:{className:"bx-catalog-subscribe-form-container-input"},children:[BX.create("input",{props:{id:"userContact",className:"",type:"text",name:"contact["+o+"][user]"},attrs:{"data-id":o}})]})]}))}if(e.hasOwnProperty("captchaCode")){r.appendChild(BX.create("div",{props:{className:"bx-catalog-subscribe-form-container"},children:[BX.create("span",{props:{className:"bx-catalog-subscribe-form-star-required"},text:"*"}),BX.message("CPST_ENTER_WORD_PICTURE"),BX.create("div",{props:{className:"bx-captcha"},children:[BX.create("input",{props:{type:"hidden",id:"captcha_sid",name:"captcha_sid",value:e.captchaCode}}),BX.create("img",{props:{id:"captcha_img",src:"/bitrix/tools/captcha.php?captcha_sid="+e.captchaCode+""},attrs:{width:"180",height:"40",alt:"captcha",onclick:this.jsObject+".reloadCaptcha();"}})]}),BX.create("div",{props:{className:"bx-catalog-subscribe-form-container-input"},children:[BX.create("input",{props:{id:"captcha_word",className:"",type:"text",name:"captcha_word"},attrs:{maxlength:"50"}})]})]}))}var c=BX.create("form",{props:{id:"bx-catalog-subscribe-form"},children:[BX.create("input",{props:{type:"hidden",name:"manyContact",value:i}}),BX.create("input",{props:{type:"hidden",name:"sessid",value:BX.bitrix_sessid()}}),BX.create("input",{props:{type:"hidden",name:"itemId",value:this.elemButtonSubscribe.dataset.item}}),BX.create("input",{props:{type:"hidden",name:"landingId",value:this.landingId}}),BX.create("input",{props:{type:"hidden",name:"siteId",value:BX.message("SITE_ID")}}),BX.create("input",{props:{type:"hidden",name:"contactFormSubmit",value:"Y"}})]});c.appendChild(r);return c};e.JCCatalogProductSubscribe.prototype.selectContactType=function(t,s){var a=BX("bx-catalog-subscribe-form-container-"+t),i="",r=BX("bx-contact-checkbox-"+t);if(!a){return false}if(r!=s.target){if(r.checked){r.checked=false}else{r.checked=true}}if(a.currentStyle){i=a.currentStyle.display}else if(e.getComputedStyle){var o=e.getComputedStyle(a,null);i=o.getPropertyValue("display")}if(i==="none"){BX("bx-contact-use-"+t).value="Y";BX.style(a,"display","")}else{BX("bx-contact-use-"+t).value="N";BX.style(a,"display","none")}};e.JCCatalogProductSubscribe.prototype.createSuccessPopup=function(e){this.initPopupWindow();this.elemPopupWin.setTitleBar(BX.message("CPST_SUBSCRIBE_POPUP_TITLE"));var s=BX.create("div",{props:{className:"bx-catalog-popup-content"},children:[BX.create("p",{props:{className:"bx-catalog-popup-message"},text:e.message})]});this.elemPopupWin.setContent(s);this.elemPopupWin.setButtons([new t({text:BX.message("CPST_SUBSCRIBE_BUTTON_CLOSE"),className:"btn btn-primary",events:{click:BX.delegate(function(){this.elemPopupWin.destroy()},this)}})]);this.elemPopupWin.show()};e.JCCatalogProductSubscribe.prototype.initPopupWindow=function(){this.elemPopupWin=BX.PopupWindowManager.create("CatalogSubscribe_"+this.buttonId,null,{autoHide:false,offsetLeft:0,offsetTop:0,overlay:true,closeByEsc:true,titleBar:true,closeIcon:true,contentColor:"white"})};e.JCCatalogProductSubscribe.prototype.setButton=function(e){this.alreadySubscribed=Boolean(e);if(this.alreadySubscribed){this.elemButtonSubscribe.className=this.buttonClass+" "+this.defaultButtonClass+" disabled";this.elemButtonSubscribe.innerHTML="<span>"+BX.message("CPST_TITLE_ALREADY_SUBSCRIBED")+"</span>";BX.unbind(this.elemButtonSubscribe,"click",this._elemButtonSubscribeClickHandler)}else{this.elemButtonSubscribe.className=this.buttonClass+" "+this.defaultButtonClass;this.elemButtonSubscribe.innerHTML="<span>"+BX.message("CPST_SUBSCRIBE_BUTTON_NAME")+"</span>";BX.bind(this.elemButtonSubscribe,"click",this._elemButtonSubscribeClickHandler)}};e.JCCatalogProductSubscribe.prototype.setIdAlreadySubscribed=function(e){if(BX.type.isPlainObject(e)){this.listOldItemId=e}};e.JCCatalogProductSubscribe.prototype.showWindowWithAnswer=function(e){e=e||{};if(!e.message){if(e.status=="success"){e.message=BX.message("CPST_STATUS_SUCCESS")}else{e.message=BX.message("CPST_STATUS_ERROR")}}var t=BX.create("div",{props:{className:"bx-catalog-subscribe-alert"},children:[BX.create("span",{props:{className:"bx-catalog-subscribe-aligner"}}),BX.create("span",{props:{className:"bx-catalog-subscribe-alert-text"},text:e.message}),BX.create("div",{props:{className:"bx-catalog-subscribe-alert-footer"}})]});var s=BX.PopupWindowManager.getCurrentPopup();if(s){s.destroy()}var a=setTimeout(function(){var e=BX.PopupWindowManager.getCurrentPopup();if(!e||e.uniquePopupId!="bx-catalog-subscribe-status-action"){return}e.close();e.destroy()},3500);var i=BX.PopupWindowManager.create("bx-catalog-subscribe-status-action",null,{content:t,onPopupClose:function(){this.destroy();clearTimeout(a)},autoHide:true,zIndex:2e3,className:"bx-catalog-subscribe-alert-popup"});i.show();BX("bx-catalog-subscribe-status-action").onmouseover=function(e){clearTimeout(a)};BX("bx-catalog-subscribe-status-action").onmouseout=function(e){a=setTimeout(function(){var e=BX.PopupWindowManager.getCurrentPopup();if(!e||e.uniquePopupId!="bx-catalog-subscribe-status-action"){return}e.close();e.destroy()},3500)}}})(window);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:103:"/bitrix/components/bitrix/sale.prediction.product.detail/templates/.default/script.min.js?1572342106394";s:6:"source";s:85:"/bitrix/components/bitrix/sale.prediction.product.detail/templates/.default/script.js";s:3:"min";s:89:"/bitrix/components/bitrix/sale.prediction.product.detail/templates/.default/script.min.js";s:3:"map";s:89:"/bitrix/components/bitrix/sale.prediction.product.detail/templates/.default/script.map.js";}"*/
(function(t){})(window);function bx_sale_prediction_product_detail_load(t,a,e){a=a||{};e=e||{};BX.ajax({url:"/bitrix/components/bitrix/sale.prediction.product.detail/ajax.php",method:"POST",data:BX.merge(a,e),dataType:"html",processData:false,start:true,onsuccess:function(a){var e=BX.processHTML(a);BX(t).innerHTML=e.HTML;BX.ajax.processScripts(e.SCRIPT)}})}
/* End */
;
; /* Start:"a:4:{s:4:"full";s:92:"/bitrix/components/bitrix/sale.products.gift/templates/.default/script.min.js?15723420913303";s:6:"source";s:73:"/bitrix/components/bitrix/sale.products.gift/templates/.default/script.js";s:3:"min";s:77:"/bitrix/components/bitrix/sale.products.gift/templates/.default/script.min.js";s:3:"map";s:77:"/bitrix/components/bitrix/sale.products.gift/templates/.default/script.map.js";}"*/
(function(){"use strict";if(!!window.JCSaleProductsGiftComponent)return;window.JCSaleProductsGiftComponent=function(t){this.formPosting=false;this.siteId=t.siteId||"";this.template=t.template||"";this.componentPath=t.componentPath||"";this.parameters=t.parameters||"";this.container=document.querySelector('[data-entity="'+t.container+'"]');this.currentProductId=t.currentProductId;if(t.initiallyShowHeader){BX.ready(BX.delegate(this.showHeader,this))}if(t.deferredLoad){BX.ready(BX.delegate(this.deferredLoad,this))}BX.addCustomEvent("onCatalogStoreProductChange",BX.delegate(function(t){t=parseInt(t);if(this.currentProductId===t){return}this.currentProductId=t;this.offerChangedEvent()},this))};window.JCSaleProductsGiftComponent.prototype={offerChangedEvent:function(){this.sendRequest({action:"deferredLoad",offerId:this.currentProductId})},deferredLoad:function(){this.sendRequest({action:"deferredLoad"})},sendRequest:function(t){var e={siteId:this.siteId,template:this.template,parameters:this.parameters};BX.ajax({url:this.componentPath+"/ajax.php"+(document.location.href.indexOf("clear_cache=Y")!==-1?"?clear_cache=Y":""),method:"POST",dataType:"json",timeout:60,data:BX.merge(e,t),onsuccess:BX.delegate(function(e){if(!e||!e.JS){this.hideHeader();BX.cleanNode(this.container);return}BX.ajax.processScripts(BX.processHTML(e.JS).SCRIPT,false,BX.delegate(function(){this.showAction(e,t)},this))},this)})},showAction:function(t,e){if(!e)return;switch(e.action){case"deferredLoad":this.processDeferredLoadAction(t);break}},processDeferredLoadAction:function(t){if(!t)return;this.processItems(t.items)},processItems:function(t){if(!t)return;var e=BX.processHTML(t,false),i=BX.create("DIV");var a,n,r;i.innerHTML=e.HTML;r=this.container.querySelectorAll('[data-entity="items-row"]');if(r.length){BX.cleanNode(this.container);this.showHeader(false)}else{this.showHeader(true)}a=i.querySelectorAll('[data-entity="items-row"]');for(n in a){if(a.hasOwnProperty(n)){a[n].style.opacity=0;this.container.appendChild(a[n])}}new BX.easing({duration:2e3,start:{opacity:0},finish:{opacity:100},transition:BX.easing.makeEaseOut(BX.easing.transitions.quad),step:function(t){for(var e in a){if(a.hasOwnProperty(e)){a[e].style.opacity=t.opacity/100}}},complete:function(){for(var t in a){if(a.hasOwnProperty(t)){a[t].removeAttribute("style")}}}}).animate();BX.ajax.processScripts(e.SCRIPT)},showHeader:function(t){var e=BX.findParent(this.container,{attr:{"data-entity":"parent-container"}}),i;if(e&&BX.type.isDomNode(e)){i=e.querySelector('[data-entity="header"]');if(i&&i.getAttribute("data-showed")==="false"){i.style.display="";if(t){this.animation=new BX.easing({duration:2e3,start:{opacity:0},finish:{opacity:100},transition:BX.easing.makeEaseOut(BX.easing.transitions.quad),step:function(t){i.style.opacity=t.opacity/100},complete:function(){i.removeAttribute("style");i.setAttribute("data-showed","true")}});this.animation.animate()}else{i.style.opacity=100}}}},hideHeader:function(){var t=BX.findParent(this.container,{attr:{"data-entity":"parent-container"}}),e;if(t&&BX.type.isDomNode(t)){e=t.querySelector('[data-entity="header"]');if(e){if(this.animation){this.animation.stop()}e.style.display="none";e.style.opacity=0;e.setAttribute("data-showed","false")}}}}})();
/* End */
;
; /* Start:"a:4:{s:4:"full";s:90:"/local/templates/us_2018/components/bitrix/catalog.item/.default/script.js?157234198163290";s:6:"source";s:74:"/local/templates/us_2018/components/bitrix/catalog.item/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
(function (window){
	'use strict';

	if (window.JCCatalogItem)
		return;

	var BasketButton = function(params)
	{
		BasketButton.superclass.constructor.apply(this, arguments);
		this.buttonNode = BX.create('span', {
			props: {className: 'btn btn-default btn-buy btn-sm', id: this.id},
			style: typeof params.style === 'object' ? params.style : {},
			text: params.text,
			events: this.contextEvents
		});

		if (BX.browser.IsIE())
		{
			this.buttonNode.setAttribute("hideFocus", "hidefocus");
		}
	};
	BX.extend(BasketButton, BX.PopupWindowButton);

	window.JCCatalogItem = function (arParams)
	{
		this.productType = 0;
		this.showQuantity = true;
		this.showAbsent = true;
		this.secondPict = false;
		this.showOldPrice = false;
		this.showMaxQuantity = 'N';
		this.relativeQuantityFactor = 5;
		this.showPercent = false;
		this.showSkuProps = false;
		this.basketAction = 'ADD';
		this.showClosePopup = false;
		this.useCompare = false;
		this.showSubscription = false;
		this.visual = {
			ID: '',
			PICT_ID: '',
			SECOND_PICT_ID: '',
			PICT_SLIDER_ID: '',
			QUANTITY_ID: '',
			QUANTITY_UP_ID: '',
			QUANTITY_DOWN_ID: '',
			PRICE_ID: '',
			PRICE_OLD_ID: '',
			DSC_PERC: '',
			SECOND_DSC_PERC: '',
			DISPLAY_PROP_DIV: '',
			BASKET_PROP_DIV: '',
			SUBSCRIBE_ID: ''
		};
		this.product = {
			checkQuantity: false,
			maxQuantity: 0,
			stepQuantity: 1,
			isDblQuantity: false,
			canBuy: true,
			name: '',
			pict: {},
			id: 0,
			addUrl: '',
			buyUrl: ''
		};

		this.basketMode = '';
		this.basketData = {
			useProps: false,
			emptyProps: false,
			quantity: 'quantity',
			props: 'prop',
			basketUrl: '',
			sku_props: '',
			sku_props_var: 'basket_props',
			add_url: '',
			buy_url: ''
		};

		this.compareData = {
			compareUrl: '',
			compareDeleteUrl: '',
			comparePath: ''
		};

		this.defaultPict = {
			pict: null,
			secondPict: null
		};

		this.defaultSliderOptions = {
			interval: 3000,
			wrap: true
		};
		this.slider = {
			options: {},
			items: [],
			active: null,
			sliding: null,
			paused: null,
			interval: null,
			progress: null
		};
		this.touch = null;

		this.quantityDelay = null;
		this.quantityTimer = null;

		this.checkQuantity = false;
		this.maxQuantity = 0;
		this.minQuantity = 0;
		this.stepQuantity = 1;
		this.isDblQuantity = false;
		this.canBuy = true;
		this.precision = 6;
		this.precisionFactor = Math.pow(10, this.precision);
		this.bigData = false;
		this.fullDisplayMode = false;
		this.viewMode = '';
		this.templateTheme = '';

		this.currentPriceMode = '';
		this.currentPrices = [];
		this.currentPriceSelected = 0;
		this.currentQuantityRanges = [];
		this.currentQuantityRangeSelected = 0;

		this.offers = [];
		this.offerNum = 0;
		this.treeProps = [];
		this.selectedValues = {};

		this.obProduct = null;
		this.blockNodes = {};
		this.obQuantity = null;
		this.obQuantityUp = null;
		this.obQuantityDown = null;
		this.obQuantityLimit = {};
		this.obPict = null;
		this.obSecondPict = null;
		this.obPictSlider = null;
		this.obPictSliderIndicator = null;
		this.obPrice = null;
		this.obTree = null;
		this.obBuyBtn = null;
		this.obBasketActions = null;
		this.obNotAvail = null;
		this.obSubscribe = null;
		this.obDscPerc = null;
		this.obSecondDscPerc = null;
		this.obSkuProps = null;
		this.obMeasure = null;
		this.obCompare = null;

		this.obPopupWin = null;
		this.basketUrl = '';
		this.basketParams = {};
		this.isTouchDevice = BX.hasClass(document.documentElement, 'bx-touch');
		this.hoverTimer = null;
		this.hoverStateChangeForbidden = false;
		this.mouseX = null;
		this.mouseY = null;

		this.useEnhancedEcommerce = false;
		this.dataLayerName = 'dataLayer';
		this.brandProperty = false;

		this.errorCode = 0;

		if (typeof arParams === 'object')
		{
			if (arParams.PRODUCT_TYPE)
			{
				this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
			}

			this.showQuantity = arParams.SHOW_QUANTITY;
			this.showAbsent = arParams.SHOW_ABSENT;
			this.secondPict = arParams.SECOND_PICT;
			this.showOldPrice = arParams.SHOW_OLD_PRICE;
			this.showMaxQuantity = arParams.SHOW_MAX_QUANTITY;
			this.relativeQuantityFactor = parseInt(arParams.RELATIVE_QUANTITY_FACTOR);
			this.showPercent = arParams.SHOW_DISCOUNT_PERCENT;
			this.showSkuProps = arParams.SHOW_SKU_PROPS;
			this.showSubscription = arParams.USE_SUBSCRIBE;

			if (arParams.ADD_TO_BASKET_ACTION)
			{
				this.basketAction = arParams.ADD_TO_BASKET_ACTION;
			}

			this.showClosePopup = arParams.SHOW_CLOSE_POPUP;
			this.useCompare = arParams.DISPLAY_COMPARE;
			this.fullDisplayMode = arParams.PRODUCT_DISPLAY_MODE === 'Y';
			this.bigData = arParams.BIG_DATA;
			this.viewMode = arParams.VIEW_MODE || '';
			this.templateTheme = arParams.TEMPLATE_THEME || '';
			this.useEnhancedEcommerce = arParams.USE_ENHANCED_ECOMMERCE === 'Y';
			this.dataLayerName = arParams.DATA_LAYER_NAME;
			this.brandProperty = arParams.BRAND_PROPERTY;

			this.visual = arParams.VISUAL;

			switch (this.productType)
			{
				case 0: // no catalog
				case 1: // product
				case 2: // set
					if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object')
					{
						this.currentPriceMode = arParams.PRODUCT.ITEM_PRICE_MODE;
						this.currentPrices = arParams.PRODUCT.ITEM_PRICES;
						this.currentPriceSelected = arParams.PRODUCT.ITEM_PRICE_SELECTED;
						this.currentQuantityRanges = arParams.PRODUCT.ITEM_QUANTITY_RANGES;
						this.currentQuantityRangeSelected = arParams.PRODUCT.ITEM_QUANTITY_RANGE_SELECTED;

						if (this.showQuantity)
						{
							this.product.checkQuantity = arParams.PRODUCT.CHECK_QUANTITY;
							this.product.isDblQuantity = arParams.PRODUCT.QUANTITY_FLOAT;

							if (this.product.checkQuantity)
							{
								this.product.maxQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.MAX_QUANTITY) : parseInt(arParams.PRODUCT.MAX_QUANTITY, 10));
							}

							this.product.stepQuantity = (this.product.isDblQuantity ? parseFloat(arParams.PRODUCT.STEP_QUANTITY) : parseInt(arParams.PRODUCT.STEP_QUANTITY, 10));

							this.checkQuantity = this.product.checkQuantity;
							this.isDblQuantity = this.product.isDblQuantity;
							this.stepQuantity = this.product.stepQuantity;
							this.maxQuantity = this.product.maxQuantity;
							this.minQuantity = this.currentPriceMode === 'Q'
								? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY)
								: this.stepQuantity;

							if (this.isDblQuantity)
							{
								this.stepQuantity = Math.round(this.stepQuantity * this.precisionFactor) / this.precisionFactor;
							}
						}

						this.product.canBuy = arParams.PRODUCT.CAN_BUY;

						if (arParams.PRODUCT.MORE_PHOTO_COUNT)
						{
							this.product.morePhotoCount = arParams.PRODUCT.MORE_PHOTO_COUNT;
							this.product.morePhoto = arParams.PRODUCT.MORE_PHOTO;
						}

						if (arParams.PRODUCT.RCM_ID)
						{
							this.product.rcmId = arParams.PRODUCT.RCM_ID;
						}

						this.canBuy = this.product.canBuy;
						this.product.name = arParams.PRODUCT.NAME;
						this.product.pict = arParams.PRODUCT.PICT;
						this.product.id = arParams.PRODUCT.ID;
						this.product.DETAIL_PAGE_URL = arParams.PRODUCT.DETAIL_PAGE_URL;

						if (arParams.PRODUCT.ADD_URL)
						{
							this.product.addUrl = arParams.PRODUCT.ADD_URL;
						}

						if (arParams.PRODUCT.BUY_URL)
						{
							this.product.buyUrl = arParams.PRODUCT.BUY_URL;
						}

						if (arParams.BASKET && typeof arParams.BASKET === 'object')
						{
							this.basketData.useProps = arParams.BASKET.ADD_PROPS;
							this.basketData.emptyProps = arParams.BASKET.EMPTY_PROPS;
						}
					}
					else
					{
						this.errorCode = -1;
					}

					break;
				case 3: // sku
					if (arParams.PRODUCT && typeof arParams.PRODUCT === 'object')
					{
						this.product.name = arParams.PRODUCT.NAME;
						this.product.id = arParams.PRODUCT.ID;
						this.product.DETAIL_PAGE_URL = arParams.PRODUCT.DETAIL_PAGE_URL;
						this.product.morePhotoCount = arParams.PRODUCT.MORE_PHOTO_COUNT;
						this.product.morePhoto = arParams.PRODUCT.MORE_PHOTO;

						if (arParams.PRODUCT.RCM_ID)
						{
							this.product.rcmId = arParams.PRODUCT.RCM_ID;
						}
					}

					if (arParams.OFFERS && BX.type.isArray(arParams.OFFERS))
					{
						this.offers = arParams.OFFERS;
						this.offerNum = 0;

						if (arParams.OFFER_SELECTED)
						{
							this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
						}

						if (isNaN(this.offerNum))
						{
							this.offerNum = 0;
						}

						if (arParams.TREE_PROPS)
						{
							this.treeProps = arParams.TREE_PROPS;
						}

						if (arParams.DEFAULT_PICTURE)
						{
							this.defaultPict.pict = arParams.DEFAULT_PICTURE.PICTURE;
							this.defaultPict.secondPict = arParams.DEFAULT_PICTURE.PICTURE_SECOND;
						}
					}

					break;
				default:
					this.errorCode = -1;
			}
			if (arParams.BASKET && typeof arParams.BASKET === 'object')
			{
				if (arParams.BASKET.QUANTITY)
				{
					this.basketData.quantity = arParams.BASKET.QUANTITY;
				}

				if (arParams.BASKET.PROPS)
				{
					this.basketData.props = arParams.BASKET.PROPS;
				}

				if (arParams.BASKET.BASKET_URL)
				{
					this.basketData.basketUrl = arParams.BASKET.BASKET_URL;
				}

				if (3 === this.productType)
				{
					if (arParams.BASKET.SKU_PROPS)
					{
						this.basketData.sku_props = arParams.BASKET.SKU_PROPS;
					}
				}

				if (arParams.BASKET.ADD_URL_TEMPLATE)
				{
					this.basketData.add_url = arParams.BASKET.ADD_URL_TEMPLATE;
				}

				if (arParams.BASKET.BUY_URL_TEMPLATE)
				{
					this.basketData.buy_url = arParams.BASKET.BUY_URL_TEMPLATE;
				}

				if (this.basketData.add_url === '' && this.basketData.buy_url === '')
				{
					this.errorCode = -1024;
				}
			}

			if (this.useCompare)
			{
				if (arParams.COMPARE && typeof arParams.COMPARE === 'object')
				{
					if (arParams.COMPARE.COMPARE_PATH)
					{
						this.compareData.comparePath = arParams.COMPARE.COMPARE_PATH;
					}

					if (arParams.COMPARE.COMPARE_URL_TEMPLATE)
					{
						this.compareData.compareUrl = arParams.COMPARE.COMPARE_URL_TEMPLATE;
					}
					else
					{
						this.useCompare = false;
					}

					if (arParams.COMPARE.COMPARE_DELETE_URL_TEMPLATE)
					{
						this.compareData.compareDeleteUrl = arParams.COMPARE.COMPARE_DELETE_URL_TEMPLATE;
					}
					else
					{
						this.useCompare = false;
					}
				}
				else
				{
					this.useCompare = false;
				}
			}
		}

		if (this.errorCode === 0)
		{
			BX.ready(BX.delegate(this.init,this));
		}
	};

	window.JCCatalogItem.prototype = {
		init: function()
		{
			var i = 0,
				treeItems = null;

			this.obProduct = BX(this.visual.ID);
			if (!this.obProduct)
			{
				this.errorCode = -1;
			}

			this.obPict = BX(this.visual.PICT_ID);
			if (!this.obPict)
			{
				this.errorCode = -2;
			}

			if (this.secondPict && this.visual.SECOND_PICT_ID)
			{
				this.obSecondPict = BX(this.visual.SECOND_PICT_ID);
			}

			this.obPictSlider = BX(this.visual.PICT_SLIDER_ID);
			this.obPictSliderIndicator = BX(this.visual.PICT_SLIDER_ID + '_indicator');
			this.obPictSliderProgressBar = BX(this.visual.PICT_SLIDER_ID + '_progress_bar');
			if (!this.obPictSlider)
			{
				this.errorCode = -4;
			}

			this.obPrice = BX(this.visual.PRICE_ID);
			this.obPriceOld = BX(this.visual.PRICE_OLD_ID);
			this.obPriceTotal = BX(this.visual.PRICE_TOTAL_ID);
			if (!this.obPrice)
			{
				this.errorCode = -16;
			}

			if (this.showQuantity && this.visual.QUANTITY_ID)
			{
				this.obQuantity = BX(this.visual.QUANTITY_ID);
				this.blockNodes.quantity = this.obProduct.querySelector('[data-entity="quantity-block"]');

				if (!this.isTouchDevice)
				{
					BX.bind(this.obQuantity, 'focus', BX.proxy(this.onFocus, this));
					BX.bind(this.obQuantity, 'blur', BX.proxy(this.onBlur, this));
				}

				if (this.visual.QUANTITY_UP_ID)
				{
					this.obQuantityUp = BX(this.visual.QUANTITY_UP_ID);
				}

				if (this.visual.QUANTITY_DOWN_ID)
				{
					this.obQuantityDown = BX(this.visual.QUANTITY_DOWN_ID);
				}
			}

			if (this.visual.QUANTITY_LIMIT && this.showMaxQuantity !== 'N')
			{
				this.obQuantityLimit.all = BX(this.visual.QUANTITY_LIMIT);
				if (this.obQuantityLimit.all)
				{
					this.obQuantityLimit.value = this.obQuantityLimit.all.querySelector('[data-entity="quantity-limit-value"]');
					if (!this.obQuantityLimit.value)
					{
						this.obQuantityLimit.all = null;
					}
				}
			}

			if (this.productType === 3 && this.fullDisplayMode)
			{
				if (this.visual.TREE_ID)
				{
					this.obTree = BX(this.visual.TREE_ID);
					if (!this.obTree)
					{
						this.errorCode = -256;
					}
				}

				if (this.visual.QUANTITY_MEASURE)
				{
					this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
				}
			}

			this.obBasketActions = BX(this.visual.BASKET_ACTIONS_ID);
			if (this.obBasketActions)
			{
				if (this.visual.BUY_ID)
				{
					this.obBuyBtn = BX(this.visual.BUY_ID);
				}
			}

			this.obNotAvail = BX(this.visual.NOT_AVAILABLE_MESS);

			if (this.showSubscription)
			{
				this.obSubscribe = BX(this.visual.SUBSCRIBE_ID);
			}

			if (this.showPercent)
			{
				if (this.visual.DSC_PERC)
				{
					this.obDscPerc = BX(this.visual.DSC_PERC);
				}
				if (this.secondPict && this.visual.SECOND_DSC_PERC)
				{
					this.obSecondDscPerc = BX(this.visual.SECOND_DSC_PERC);
				}
			}

			if (this.showSkuProps)
			{
				if (this.visual.DISPLAY_PROP_DIV)
				{
					this.obSkuProps = BX(this.visual.DISPLAY_PROP_DIV);
				}
			}

			if (this.errorCode === 0)
			{
				// product slider events
				if (this.isTouchDevice)
				{
					BX.bind(this.obPictSlider, 'touchstart', BX.proxy(this.touchStartEvent, this));
					BX.bind(this.obPictSlider, 'touchend', BX.proxy(this.touchEndEvent, this));
					BX.bind(this.obPictSlider, 'touchcancel', BX.proxy(this.touchEndEvent, this));
				}
				else
				{
					if (this.viewMode === 'CARD')
					{
						// product hover events
						BX.bind(this.obProduct, 'mouseenter', BX.proxy(this.hoverOn, this));
						BX.bind(this.obProduct, 'mouseleave', BX.proxy(this.hoverOff, this));
					}

					// product slider events
					BX.bind(this.obProduct, 'mouseenter', BX.proxy(this.cycleSlider, this));
					BX.bind(this.obProduct, 'mouseleave', BX.proxy(this.stopSlider, this));
				}

				if (this.bigData)
				{
					var links = BX.findChildren(this.obProduct, {tag:'a'}, true);
					if (links)
					{
						for (i in links)
						{
							if (links.hasOwnProperty(i))
							{
								if (links[i].getAttribute('href') == this.product.DETAIL_PAGE_URL)
								{
									BX.bind(links[i], 'click', BX.proxy(this.rememberProductRecommendation, this));
								}
							}
						}
					}
				}

				if (this.showQuantity)
				{
					var startEventName = this.isTouchDevice ? 'touchstart' : 'mousedown';
					var endEventName = this.isTouchDevice ? 'touchend' : 'mouseup';

					if (this.obQuantityUp)
					{
						BX.bind(this.obQuantityUp, startEventName, BX.proxy(this.startQuantityInterval, this));
						BX.bind(this.obQuantityUp, endEventName, BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityUp, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityUp, 'click', BX.delegate(this.quantityUp, this));
					}

					if (this.obQuantityDown)
					{
						BX.bind(this.obQuantityDown, startEventName, BX.proxy(this.startQuantityInterval, this));
						BX.bind(this.obQuantityDown, endEventName, BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityDown, 'mouseout', BX.proxy(this.clearQuantityInterval, this));
						BX.bind(this.obQuantityDown, 'click', BX.delegate(this.quantityDown, this));
					}

					if (this.obQuantity)
					{
						BX.bind(this.obQuantity, 'change', BX.delegate(this.quantityChange, this));
					}
				}

				switch (this.productType)
				{
					case 0: // no catalog
					case 1: // product
					case 2: // set
						if (parseInt(this.product.morePhotoCount) > 1 && this.obPictSlider)
						{
							this.initializeSlider();
						}

						this.checkQuantityControls();

						break;
					case 3: // sku
						if (this.offers.length > 0)
						{
							treeItems = BX.findChildren(this.obTree, {tagName: 'li'}, true);

							if (treeItems && treeItems.length)
							{
								for (i = 0; i < treeItems.length; i++)
								{
									BX.bind(treeItems[i], 'click', BX.delegate(this.selectOfferProp, this));
								}
							}

							this.setCurrent();
						}
						else if (parseInt(this.product.morePhotoCount) > 1 && this.obPictSlider)
						{
							this.initializeSlider();
						}

						break;
				}

				if (this.obBuyBtn)
				{
					if (this.basketAction === 'ADD')
					{
						BX.bind(this.obBuyBtn, 'click', BX.proxy(this.add2Basket, this));
					}
					else
					{
						BX.bind(this.obBuyBtn, 'click', BX.proxy(this.buyBasket, this));
					}
				}

				if (this.useCompare)
				{
					this.obCompare = BX(this.visual.COMPARE_LINK_ID);
					if (this.obCompare)
					{
						BX.bind(this.obCompare, 'click', BX.proxy(this.compare, this));
					}

					BX.addCustomEvent('onCatalogDeleteCompare', BX.proxy(this.checkDeletedCompare, this));
				}
			}
		},

		setAnalyticsDataLayer: function(action)
		{
			if (!this.useEnhancedEcommerce || !this.dataLayerName)
				return;

			var item = {},
				info = {},
				variants = [],
				i, k, j, propId, skuId, propValues;

			switch (this.productType)
			{
				case 0: //no catalog
				case 1: //product
				case 2: //set
					item = {
						'id': this.product.id,
						'name': this.product.name,
						'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
						'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty
					};
					break;
				case 3: //sku
					for (i in this.offers[this.offerNum].TREE)
					{
						if (this.offers[this.offerNum].TREE.hasOwnProperty(i))
						{
							propId = i.substring(5);
							skuId = this.offers[this.offerNum].TREE[i];

							for (k in this.treeProps)
							{
								if (this.treeProps.hasOwnProperty(k) && this.treeProps[k].ID == propId)
								{
									for (j in this.treeProps[k].VALUES)
									{
										propValues = this.treeProps[k].VALUES[j];
										if (propValues.ID == skuId)
										{
											variants.push(propValues.NAME);
											break;
										}
									}

								}
							}
						}
					}

					item = {
						'id': this.offers[this.offerNum].ID,
						'name': this.offers[this.offerNum].NAME,
						'price': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].PRICE,
						'brand': BX.type.isArray(this.brandProperty) ? this.brandProperty.join('/') : this.brandProperty,
						'variant': variants.join('/')
					};
					break;
			}

			switch (action)
			{
				case 'addToCart':
					info = {
						'event': 'addToCart',
						'ecommerce': {
							'currencyCode': this.currentPrices[this.currentPriceSelected] && this.currentPrices[this.currentPriceSelected].CURRENCY || '',
							'add': {
								'products': [{
									'name': item.name || '',
									'id': item.id || '',
									'price': item.price || 0,
									'brand': item.brand || '',
									'category': item.category || '',
									'variant': item.variant || '',
									'quantity': this.showQuantity && this.obQuantity ? this.obQuantity.value : 1
								}]
							}
						}
					};
					break;
			}

			window[this.dataLayerName] = window[this.dataLayerName] || [];
			window[this.dataLayerName].push(info);
		},

		hoverOn: function(event)
		{
			clearTimeout(this.hoverTimer);
			this.obProduct.style.height = getComputedStyle(this.obProduct).height;
			BX.addClass(this.obProduct, 'hover');

			BX.PreventDefault(event);
		},

		hoverOff: function(event)
		{
			if (this.hoverStateChangeForbidden)
				return;

			BX.removeClass(this.obProduct, 'hover');
			this.hoverTimer = setTimeout(
				BX.delegate(function(){
					this.obProduct.style.height = 'auto';
				}, this),
				300
			);

			BX.PreventDefault(event);
		},

		onFocus: function()
		{
			this.hoverStateChangeForbidden = true;
			BX.bind(document, 'mousemove', BX.proxy(this.captureMousePosition, this));
		},

		onBlur: function()
		{
			this.hoverStateChangeForbidden = false;
			BX.unbind(document, 'mousemove', BX.proxy(this.captureMousePosition, this));

			var cursorElement = document.elementFromPoint(this.mouseX, this.mouseY);
			if (!cursorElement || !this.obProduct.contains(cursorElement))
			{
				this.hoverOff();
			}
		},

		captureMousePosition: function(event)
		{
			this.mouseX = event.clientX;
			this.mouseY = event.clientY;
		},

		getCookie: function(name)
		{
			var matches = document.cookie.match(new RegExp(
				"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
			));

			return matches ? decodeURIComponent(matches[1]) : null;
		},

		rememberProductRecommendation: function()
		{
			// save to RCM_PRODUCT_LOG
			var cookieName = BX.cookie_prefix + '_RCM_PRODUCT_LOG',
				cookie = this.getCookie(cookieName),
				itemFound = false;

			var cItems = [],
				cItem;

			if (cookie)
			{
				cItems = cookie.split('.');
			}

			var i = cItems.length;

			while (i--)
			{
				cItem = cItems[i].split('-');

				if (cItem[0] == this.product.id)
				{
					// it's already in recommendations, update the date
					cItem = cItems[i].split('-');

					// update rcmId and date
					cItem[1] = this.product.rcmId;
					cItem[2] = BX.current_server_time;

					cItems[i] = cItem.join('-');
					itemFound = true;
				}
				else
				{
					if ((BX.current_server_time - cItem[2]) > 3600 * 24 * 30)
					{
						cItems.splice(i, 1);
					}
				}
			}

			if (!itemFound)
			{
				// add recommendation
				cItems.push([this.product.id, this.product.rcmId, BX.current_server_time].join('-'));
			}

			// serialize
			var plNewCookie = cItems.join('.'),
				cookieDate = new Date(new Date().getTime() + 1000 * 3600 * 24 * 365 * 10).toUTCString();

			document.cookie = cookieName + "=" + plNewCookie + "; path=/; expires=" + cookieDate + "; domain=" + BX.cookie_domain;
		},

		startQuantityInterval: function()
		{
			var target = BX.proxy_context;
			var func = target.id === this.visual.QUANTITY_DOWN_ID
				? BX.proxy(this.quantityDown, this)
				: BX.proxy(this.quantityUp, this);

			this.quantityDelay = setTimeout(
				BX.delegate(function() {
					this.quantityTimer = setInterval(func, 150);
				}, this),
				300
			);
		},

		clearQuantityInterval: function()
		{
			clearTimeout(this.quantityDelay);
			clearInterval(this.quantityTimer);
		},

		quantityUp: function()
		{
			var curValue = 0,
				boolSet = true;

			if (this.errorCode === 0 && this.showQuantity && this.canBuy)
			{
				curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
				if (!isNaN(curValue))
				{
					curValue += this.stepQuantity;
					if (this.checkQuantity)
					{
						if (curValue > this.maxQuantity)
						{
							boolSet = false;
						}
					}

					if (boolSet)
					{
						if (this.isDblQuantity)
						{
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}

						this.obQuantity.value = curValue;

						this.setPrice();
					}
				}
			}
		},

		quantityDown: function()
		{
			var curValue = 0,
				boolSet = true;

			if (this.errorCode === 0 && this.showQuantity && this.canBuy)
			{
				curValue = (this.isDblQuantity ? parseFloat(this.obQuantity.value) : parseInt(this.obQuantity.value, 10));
				if (!isNaN(curValue))
				{
					curValue -= this.stepQuantity;

					this.checkPriceRange(curValue);

					if (curValue < this.minQuantity)
					{
						boolSet = false;
					}

					if (boolSet)
					{
						if (this.isDblQuantity)
						{
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}

						this.obQuantity.value = curValue;

						this.setPrice();
					}
				}
			}
		},

		quantityChange: function()
		{
			var curValue = 0,
				intCount;

			if (this.errorCode === 0 && this.showQuantity)
			{
				if (this.canBuy)
				{
					curValue = this.isDblQuantity ? parseFloat(this.obQuantity.value) : Math.round(this.obQuantity.value);
					if (!isNaN(curValue))
					{
						if (this.checkQuantity)
						{
							if (curValue > this.maxQuantity)
							{
								curValue = this.maxQuantity;
							}
						}

						this.checkPriceRange(curValue);

						if (curValue < this.minQuantity)
						{
							curValue = this.minQuantity;
						}
						else
						{
							intCount = Math.round(
									Math.round(curValue * this.precisionFactor / this.stepQuantity) / this.precisionFactor
								) || 1;
							curValue = (intCount <= 1 ? this.stepQuantity : intCount * this.stepQuantity);
							curValue = Math.round(curValue * this.precisionFactor) / this.precisionFactor;
						}

						this.obQuantity.value = curValue;
					}
					else
					{
						this.obQuantity.value = this.minQuantity;
					}
				}
				else
				{
					this.obQuantity.value = this.minQuantity;
				}

				this.setPrice();
			}
		},

		quantitySet: function(index)
		{
			var resetQuantity, strLimit;
			
			var newOffer = this.offers[index],
				oldOffer = this.offers[this.offerNum];

			if (this.errorCode === 0)
			{
				this.canBuy = newOffer.CAN_BUY;

				this.currentPriceMode = newOffer.ITEM_PRICE_MODE;
				this.currentPrices = newOffer.ITEM_PRICES;
				this.currentPriceSelected = newOffer.ITEM_PRICE_SELECTED;
				this.currentQuantityRanges = newOffer.ITEM_QUANTITY_RANGES;
				this.currentQuantityRangeSelected = newOffer.ITEM_QUANTITY_RANGE_SELECTED;

				if (this.canBuy)
				{
					if (this.blockNodes.quantity)
					{
						BX.style(this.blockNodes.quantity, 'display', '');
					}

					if (this.obBasketActions)
					{
						BX.style(this.obBasketActions, 'display', '');
					}

					if (this.obNotAvail)
					{
						BX.style(this.obNotAvail, 'display', 'none');
					}

					if (this.obSubscribe)
					{
						BX.style(this.obSubscribe, 'display', 'none');
					}
				}
				else
				{
					if (this.blockNodes.quantity)
					{
						BX.style(this.blockNodes.quantity, 'display', 'none');
					}

					if (this.obBasketActions)
					{
						BX.style(this.obBasketActions, 'display', 'none');
					}

					if (this.obNotAvail)
					{
						BX.style(this.obNotAvail, 'display', '');
					}

					if (this.obSubscribe)
					{
						if (newOffer.CATALOG_SUBSCRIBE === 'Y')
						{
							BX.style(this.obSubscribe, 'display', '');
							this.obSubscribe.setAttribute('data-item', newOffer.ID);
							BX(this.visual.SUBSCRIBE_ID + '_hidden').click();
						}
						else
						{
							BX.style(this.obSubscribe, 'display', 'none');
						}
					}
				}

				this.isDblQuantity = newOffer.QUANTITY_FLOAT;
				this.checkQuantity = newOffer.CHECK_QUANTITY;

				if (this.isDblQuantity)
				{
					this.stepQuantity = Math.round(parseFloat(newOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor;
					this.maxQuantity = parseFloat(newOffer.MAX_QUANTITY);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseFloat(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				}
				else
				{
					this.stepQuantity = parseInt(newOffer.STEP_QUANTITY, 10);
					this.maxQuantity = parseInt(newOffer.MAX_QUANTITY, 10);
					this.minQuantity = this.currentPriceMode === 'Q' ? parseInt(this.currentPrices[this.currentPriceSelected].MIN_QUANTITY) : this.stepQuantity;
				}

				if (this.showQuantity)
				{
					var isDifferentMinQuantity = oldOffer.ITEM_PRICES.length
						&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED]
						&& oldOffer.ITEM_PRICES[oldOffer.ITEM_PRICE_SELECTED].MIN_QUANTITY != this.minQuantity;

					if (this.isDblQuantity)
					{
						resetQuantity = Math.round(parseFloat(oldOffer.STEP_QUANTITY) * this.precisionFactor) / this.precisionFactor !== this.stepQuantity
							|| isDifferentMinQuantity
							|| oldOffer.MEASURE !== newOffer.MEASURE
							|| (
								this.checkQuantity
								&& parseFloat(oldOffer.MAX_QUANTITY) > this.maxQuantity
								&& parseFloat(this.obQuantity.value) > this.maxQuantity
							);
					}
					else
					{
						resetQuantity = parseInt(oldOffer.STEP_QUANTITY, 10) !== this.stepQuantity
							|| isDifferentMinQuantity
							|| oldOffer.MEASURE !== newOffer.MEASURE
							|| (
								this.checkQuantity
								&& parseInt(oldOffer.MAX_QUANTITY, 10) > this.maxQuantity
								&& parseInt(this.obQuantity.value, 10) > this.maxQuantity
							);
					}

					this.obQuantity.disabled = !this.canBuy;

					if (resetQuantity)
					{
						this.obQuantity.value = this.minQuantity;
					}

					if (this.obMeasure)
					{
						if (newOffer.MEASURE)
						{
							BX.adjust(this.obMeasure, {html: newOffer.MEASURE});
						}
						else
						{
							BX.adjust(this.obMeasure, {html: ''});
						}
					}
				}

				if (this.obQuantityLimit.all)
				{
					if (!this.checkQuantity || this.maxQuantity == 0)
					{
						BX.adjust(this.obQuantityLimit.value, {html: ''});
						BX.adjust(this.obQuantityLimit.all, {style: {display: 'none'}});
					}
					else
					{
						if (this.showMaxQuantity === 'M')
						{
							strLimit = (this.maxQuantity / this.stepQuantity >= this.relativeQuantityFactor)
								? BX.message('RELATIVE_QUANTITY_MANY')
								: BX.message('RELATIVE_QUANTITY_FEW');
						}
						else
						{
							strLimit = this.maxQuantity;

							if (newOffer.MEASURE)
							{
								strLimit += (' ' + newOffer.MEASURE);
							}
						}

						BX.adjust(this.obQuantityLimit.value, {html: strLimit});
						BX.adjust(this.obQuantityLimit.all, {style: {display: ''}});
					}
				}
			}
		},

		initializeSlider: function()
		{
			var wrap = this.obPictSlider.getAttribute('data-slider-wrap');
			if (wrap)
			{
				this.slider.options.wrap = wrap === 'true';
			}
			else
			{
				this.slider.options.wrap = this.defaultSliderOptions.wrap;
			}

			if (this.isTouchDevice)
			{
				this.slider.options.interval = false;
			}
			else
			{
				this.slider.options.interval = parseInt(this.obPictSlider.getAttribute('data-slider-interval')) || this.defaultSliderOptions.interval;
				// slider interval must be more than 700ms because of css transitions
				if (this.slider.options.interval < 700)
				{
					this.slider.options.interval = 700;
				}

				if (this.obPictSliderIndicator)
				{
					var controls = this.obPictSliderIndicator.querySelectorAll('[data-go-to]');
					for (var i in controls)
					{
						if (controls.hasOwnProperty(i))
						{
							BX.bind(controls[i], 'click', BX.proxy(this.sliderClickHandler, this));
						}
					}
				}

				if (this.obPictSliderProgressBar)
				{
					if (this.slider.progress)
					{
						this.resetProgress();
						this.cycleSlider();
					}
					else
					{
						this.slider.progress = new BX.easing({
							transition: BX.easing.transitions.linear,
							step: BX.delegate(function(state){
								this.obPictSliderProgressBar.style.width = state.width / 10 + '%';
							}, this)
						});
					}
				}
			}
		},

		checkTouch: function(event)
		{
			if (!event || !event.changedTouches)
				return false;

			return event.changedTouches[0].identifier === this.touch.identifier;
		},

		touchStartEvent: function(event)
		{
			if (event.touches.length != 1)
				return;

			this.touch = event.changedTouches[0];
		},

		touchEndEvent: function(event)
		{
			if (!this.checkTouch(event))
				return;

			var deltaX = this.touch.pageX - event.changedTouches[0].pageX,
				deltaY = this.touch.pageY - event.changedTouches[0].pageY;

			if (Math.abs(deltaX) >= Math.abs(deltaY) + 10)
			{
				if (deltaX > 0)
				{
					this.slideNext();
				}

				if (deltaX < 0)
				{
					this.slidePrev();
				}
			}
		},

		sliderClickHandler: function(event)
		{
			var target = BX.getEventTarget(event),
				slideIndex = target.getAttribute('data-go-to');

			if (slideIndex)
			{
				this.slideTo(slideIndex)
			}

			BX.PreventDefault(event);
		},

		slideNext: function()
		{
			if (this.slider.sliding)
				return;

			return this.slide('next');
		},

		slidePrev: function()
		{
			if (this.slider.sliding)
				return;

			return this.slide('prev');
		},

		slideTo: function(pos)
		{
			this.slider.active = BX.findChild(this.obPictSlider, {className: 'item active'}, true, false);
			this.slider.progress && (this.slider.interval = true);

			var activeIndex = this.getItemIndex(this.slider.active);

			if (pos > (this.slider.items.length - 1) || pos < 0)
				return;

			if (this.slider.sliding)
				return false;

			if (activeIndex == pos)
			{
				this.stopSlider();
				this.cycleSlider();
				return;
			}

			return this.slide(pos > activeIndex ? 'next' : 'prev', this.eq(this.slider.items, pos));
		},

		slide: function(type, next)
		{
			var active = BX.findChild(this.obPictSlider, {className: 'item active'}, true, false),
				isCycling = this.slider.interval,
				direction = type === 'next' ? 'left' : 'right';

			next = next || this.getItemForDirection(type, active);

			if (BX.hasClass(next, 'active'))
			{
				return (this.slider.sliding = false);
			}

			this.slider.sliding = true;

			isCycling && this.stopSlider();

			if (this.obPictSliderIndicator)
			{
				BX.removeClass(this.obPictSliderIndicator.querySelector('.active'), 'active');
				var nextIndicator = this.obPictSliderIndicator.querySelectorAll('[data-go-to]')[this.getItemIndex(next)];
				nextIndicator && BX.addClass(nextIndicator, 'active');
			}

			if (BX.hasClass(this.obPictSlider, 'slide') && !BX.browser.IsIE())
			{
				var self = this;
				BX.addClass(next, type);
				next.offsetWidth; // force reflow
				BX.addClass(active, direction);
				BX.addClass(next, direction);
				setTimeout(function() {
					BX.addClass(next, 'active');
					BX.removeClass(active, 'active');
					BX.removeClass(active, direction);
					BX.removeClass(next, type);
					BX.removeClass(next, direction);
					self.slider.sliding = false;
				}, 700);
			}
			else
			{
				BX.addClass(next, 'active');
				this.slider.sliding = false;
			}

			this.obPictSliderProgressBar && this.resetProgress();
			isCycling && this.cycleSlider();
		},

		stopSlider: function(event)
		{
			event || (this.slider.paused = true);

			this.slider.interval && clearInterval(this.slider.interval);

			if (this.slider.progress)
			{
				this.slider.progress.stop();

				var width = parseInt(this.obPictSliderProgressBar.style.width);

				this.slider.progress.options.duration = this.slider.options.interval * width / 200;
				this.slider.progress.options.start = {width: width * 10};
				this.slider.progress.options.finish = {width: 0};
				this.slider.progress.options.complete = null;
				this.slider.progress.animate();
			}
		},

		cycleSlider: function(event)
		{
			event || (this.slider.paused = false);

			this.slider.interval && clearInterval(this.slider.interval);

			if (this.slider.options.interval && !this.slider.paused)
			{
				if (this.slider.progress)
				{
					this.slider.progress.stop();

					var width = parseInt(this.obPictSliderProgressBar.style.width);

					this.slider.progress.options.duration = this.slider.options.interval * (100 - width) / 100;
					this.slider.progress.options.start = {width: width * 10};
					this.slider.progress.options.finish = {width: 1000};
					this.slider.progress.options.complete = BX.delegate(function(){
						this.slider.interval = true;
						this.slideNext();
					}, this);
					this.slider.progress.animate();
				}
				else
				{
					this.slider.interval = setInterval(BX.proxy(this.slideNext, this), this.slider.options.interval);
				}
			}
		},

		resetProgress: function()
		{
			this.slider.progress && this.slider.progress.stop();
			this.obPictSliderProgressBar.style.width = 0;
		},

		getItemForDirection: function(direction, active)
		{
			var activeIndex = this.getItemIndex(active),
				willWrap = direction === 'prev' && activeIndex === 0
					|| direction === 'next' && activeIndex == (this.slider.items.length - 1);

			if (willWrap && !this.slider.options.wrap)
				return active;

			var delta = direction === 'prev' ? -1 : 1,
				itemIndex = (activeIndex + delta) % this.slider.items.length;

			return this.eq(this.slider.items, itemIndex);
		},

		getItemIndex: function(item)
		{
			this.slider.items = BX.findChildren(item.parentNode, {className: 'item'}, true);

			return this.slider.items.indexOf(item || this.slider.active);
		},

		eq: function(obj, i)
		{
			var len = obj.length,
				j = +i + (i < 0 ? len : 0);

			return j >= 0 && j < len ? obj[j] : {};
		},

		selectOfferProp: function()
		{
			var i = 0,
				value = '',
				strTreeValue = '',
				arTreeItem = [],
				rowItems = null,
				target = BX.proxy_context;

			if (target && target.hasAttribute('data-treevalue'))
			{
				if (BX.hasClass(target, 'selected'))
					return;

				strTreeValue = target.getAttribute('data-treevalue');
				arTreeItem = strTreeValue.split('_');
				if (this.searchOfferPropIndex(arTreeItem[0], arTreeItem[1]))
				{
					rowItems = BX.findChildren(target.parentNode, {tagName: 'li'}, false);
					if (rowItems && 0 < rowItems.length)
					{
						for (i = 0; i < rowItems.length; i++)
						{
							value = rowItems[i].getAttribute('data-onevalue');
							if (value === arTreeItem[1])
							{
								BX.addClass(rowItems[i], 'selected');
							}
							else
							{
								BX.removeClass(rowItems[i], 'selected');
							}
						}
					}
				}
			}
		},

		searchOfferPropIndex: function(strPropID, strPropValue)
		{
			var strName = '',
				arShowValues = false,
				i, j,
				arCanBuyValues = [],
				allValues = [],
				index = -1,
				arFilter = {},
				tmpFilter = [];

			for (i = 0; i < this.treeProps.length; i++)
			{
				if (this.treeProps[i].ID === strPropID)
				{
					index = i;
					break;
				}
			}

			if (-1 < index)
			{
				for (i = 0; i < index; i++)
				{
					strName = 'PROP_'+this.treeProps[i].ID;
					arFilter[strName] = this.selectedValues[strName];
				}
				strName = 'PROP_'+this.treeProps[index].ID;
				arShowValues = this.getRowValues(arFilter, strName);
				if (!arShowValues)
				{
					return false;
				}
				if (!BX.util.in_array(strPropValue, arShowValues))
				{
					return false;
				}
				arFilter[strName] = strPropValue;
				for (i = index+1; i < this.treeProps.length; i++)
				{
					strName = 'PROP_'+this.treeProps[i].ID;
					arShowValues = this.getRowValues(arFilter, strName);
					if (!arShowValues)
					{
						return false;
					}
					allValues = [];
					if (this.showAbsent)
					{
						arCanBuyValues = [];
						tmpFilter = [];
						tmpFilter = BX.clone(arFilter, true);
						for (j = 0; j < arShowValues.length; j++)
						{
							tmpFilter[strName] = arShowValues[j];
							allValues[allValues.length] = arShowValues[j];
							if (this.getCanBuy(tmpFilter))
								arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
					else
					{
						arCanBuyValues = arShowValues;
					}
					if (this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues))
					{
						arFilter[strName] = this.selectedValues[strName];
					}
					else
					{
						if (this.showAbsent)
							arFilter[strName] = (arCanBuyValues.length > 0 ? arCanBuyValues[0] : allValues[0]);
						else
							arFilter[strName] = arCanBuyValues[0];
					}
					this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
				}
				this.selectedValues = arFilter;
				this.changeInfo();
			}
			return true;
		},

		updateRow: function(intNumber, activeID, showID, canBuyID)
		{
			var i = 0,
				value = '',
				isCurrent = false,
				rowItems = null;

			var lineContainer = this.obTree.querySelectorAll('[data-entity="sku-line-block"]'),
				listContainer;

			if (intNumber > -1 && intNumber < lineContainer.length)
			{
				listContainer = lineContainer[intNumber].querySelector('ul');
				rowItems = BX.findChildren(listContainer, {tagName: 'li'}, false);
				if (rowItems && 0 < rowItems.length)
				{
					for (i = 0; i < rowItems.length; i++)
					{
						value = rowItems[i].getAttribute('data-onevalue');
						isCurrent = value === activeID;

						if (isCurrent)
						{
							BX.addClass(rowItems[i], 'selected');
						}
						else
						{
							BX.removeClass(rowItems[i], 'selected');
						}

						if (BX.util.in_array(value, canBuyID))
						{
							BX.removeClass(rowItems[i], 'notallowed');
						}
						else
						{
							BX.addClass(rowItems[i], 'notallowed');
						}

						rowItems[i].style.display = BX.util.in_array(value, showID) ? '' : 'none';

						if (isCurrent)
						{
							lineContainer[intNumber].style.display = (value == 0 && canBuyID.length == 1) ? 'none' : '';
						}
					}
				}
			}
		},

		getRowValues: function(arFilter, index)
		{
			var i = 0,
				j,
				arValues = [],
				boolSearch = false,
				boolOneSearch = true;

			if (0 === arFilter.length)
			{
				for (i = 0; i < this.offers.length; i++)
				{
					if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
					{
						arValues[arValues.length] = this.offers[i].TREE[index];
					}
				}
				boolSearch = true;
			}
			else
			{
				for (i = 0; i < this.offers.length; i++)
				{
					boolOneSearch = true;
					for (j in arFilter)
					{
						if (arFilter[j] !== this.offers[i].TREE[j])
						{
							boolOneSearch = false;
							break;
						}
					}
					if (boolOneSearch)
					{
						if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
						{
							arValues[arValues.length] = this.offers[i].TREE[index];
						}
						boolSearch = true;
					}
				}
			}
			return (boolSearch ? arValues : false);
		},

		getCanBuy: function(arFilter)
		{
			var i, j,
				boolSearch = false,
				boolOneSearch = true;

			for (i = 0; i < this.offers.length; i++)
			{
				boolOneSearch = true;
				for (j in arFilter)
				{
					if (arFilter[j] !== this.offers[i].TREE[j])
					{
						boolOneSearch = false;
						break;
					}
				}
				if (boolOneSearch)
				{
					if (this.offers[i].CAN_BUY)
					{
						boolSearch = true;
						break;
					}
				}
			}

			return boolSearch;
		},

		setCurrent: function()
		{
			var i,
				j = 0,
				arCanBuyValues = [],
				strName = '',
				arShowValues = false,
				arFilter = {},
				tmpFilter = [],
				current = this.offers[this.offerNum].TREE;

			for (i = 0; i < this.treeProps.length; i++)
			{
				strName = 'PROP_'+this.treeProps[i].ID;
				arShowValues = this.getRowValues(arFilter, strName);
				if (!arShowValues)
				{
					break;
				}
				if (BX.util.in_array(current[strName], arShowValues))
				{
					arFilter[strName] = current[strName];
				}
				else
				{
					arFilter[strName] = arShowValues[0];
					this.offerNum = 0;
				}
				if (this.showAbsent)
				{
					arCanBuyValues = [];
					tmpFilter = [];
					tmpFilter = BX.clone(arFilter, true);
					for (j = 0; j < arShowValues.length; j++)
					{
						tmpFilter[strName] = arShowValues[j];
						if (this.getCanBuy(tmpFilter))
						{
							arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
						}
					}
				}
				else
				{
					arCanBuyValues = arShowValues;
				}
				this.updateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
			}
			this.selectedValues = arFilter;
			this.changeInfo();
		},

		changeInfo: function()
		{
			var i, j,
				index = -1,
				boolOneSearch = true,
				quantityChanged;

			for (i = 0; i < this.offers.length; i++)
			{
				boolOneSearch = true;
				for (j in this.selectedValues)
				{
					if (this.selectedValues[j] !== this.offers[i].TREE[j])
					{
						boolOneSearch = false;
						break;
					}
				}
				if (boolOneSearch)
				{
					index = i;
					break;
				}
			}
			if (index > -1)
			{
				if (parseInt(this.offers[index].MORE_PHOTO_COUNT) > 1 && this.obPictSlider)
				{
					// hide pict and second_pict containers
					if (this.obPict)
					{
						this.obPict.style.display = 'none';
					}

					if (this.obSecondPict)
					{
						this.obSecondPict.style.display = 'none';
					}

					// clear slider container
					BX.cleanNode(this.obPictSlider);

					// fill slider container with slides
					for (i in this.offers[index].MORE_PHOTO)
					{
						if (this.offers[index].MORE_PHOTO.hasOwnProperty(i))
						{
							this.obPictSlider.appendChild(
								BX.create('SPAN', {
									props: {className: 'product-item-image-slide item' + (i == 0 ? ' active' : '')},
									style: {backgroundImage: 'url(\'' + this.offers[index].MORE_PHOTO[i].SRC + '\')'}
								})
							);
						}
					}

					// fill slider indicator if exists
					if (this.obPictSliderIndicator)
					{
						BX.cleanNode(this.obPictSliderIndicator);

						for (i in this.offers[index].MORE_PHOTO)
						{
							if (this.offers[index].MORE_PHOTO.hasOwnProperty(i))
							{
								this.obPictSliderIndicator.appendChild(
									BX.create('DIV', {
										attrs: {'data-go-to': i},
										props: {className: 'product-item-image-slider-control' + (i == 0 ? ' active' : '')}
									})
								);
								this.obPictSliderIndicator.appendChild(document.createTextNode(' '));
							}
						}

						this.obPictSliderIndicator.style.display = '';
					}

					if (this.obPictSliderProgressBar)
					{
						this.obPictSliderProgressBar.style.display = '';
					}

					// show slider container
					this.obPictSlider.style.display = '';
					this.initializeSlider();
				}
				else
				{
					// hide slider container
					if (this.obPictSlider)
					{
						this.obPictSlider.style.display = 'none';
					}

					if (this.obPictSliderIndicator)
					{
						this.obPictSliderIndicator.style.display = 'none';
					}

					if (this.obPictSliderProgressBar)
					{
						this.obPictSliderProgressBar.style.display = 'none';
					}

					// show pict and pict_second containers
					if (this.obPict)
					{
						if (this.offers[index].PREVIEW_PICTURE)
						{
							BX.adjust(this.obPict, {style: {backgroundImage: 'url(\'' + this.offers[index].PREVIEW_PICTURE.SRC + '\')'}});
						}
						else
						{
							BX.adjust(this.obPict, {style: {backgroundImage: 'url(\'' + this.defaultPict.pict.SRC + '\')'}});
						}

						this.obPict.style.display = '';
					}

					if (this.secondPict && this.obSecondPict)
					{
						if (this.offers[index].PREVIEW_PICTURE_SECOND)
						{
							BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(\'' + this.offers[index].PREVIEW_PICTURE_SECOND.SRC + '\')'}});
						}
						else if (this.offers[index].PREVIEW_PICTURE.SRC)
						{
							BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(\'' + this.offers[index].PREVIEW_PICTURE.SRC + '\')'}});
						}
						else if (this.defaultPict.secondPict)
						{
							BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(\'' + this.defaultPict.secondPict.SRC + '\')'}});
						}
						else
						{
							BX.adjust(this.obSecondPict, {style: {backgroundImage: 'url(\'' + this.defaultPict.pict.SRC + '\')'}});
						}

						this.obSecondPict.style.display = '';
					}
				}

				if (this.showSkuProps && this.obSkuProps)
				{
					if (this.offers[index].DISPLAY_PROPERTIES.length)
					{
						BX.adjust(this.obSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES});
					}
					else
					{
						BX.adjust(this.obSkuProps, {style: {display: 'none'}, html: ''});
					}
				}

				this.quantitySet(index);
				this.setPrice();
				this.setCompared(this.offers[index].COMPARED);

				this.offerNum = index;
			}
		},

		checkPriceRange: function(quantity)
		{
			if (typeof quantity === 'undefined'|| this.currentPriceMode != 'Q')
				return;

			var range, found = false;

			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					range = this.currentQuantityRanges[i];

					if (
						parseInt(quantity) >= parseInt(range.SORT_FROM)
						&& (
							range.SORT_TO == 'INF'
							|| parseInt(quantity) <= parseInt(range.SORT_TO)
						)
					)
					{
						found = true;
						this.currentQuantityRangeSelected = range.HASH;
						break;
					}
				}
			}

			if (!found && (range = this.getMinPriceRange()))
			{
				this.currentQuantityRangeSelected = range.HASH;
			}

			for (var k in this.currentPrices)
			{
				if (this.currentPrices.hasOwnProperty(k))
				{
					if (this.currentPrices[k].QUANTITY_HASH == this.currentQuantityRangeSelected)
					{
						this.currentPriceSelected = k;
						break;
					}
				}
			}
		},

		getMinPriceRange: function()
		{
			var range;

			for (var i in this.currentQuantityRanges)
			{
				if (this.currentQuantityRanges.hasOwnProperty(i))
				{
					if (
						!range
						|| parseInt(this.currentQuantityRanges[i].SORT_FROM) < parseInt(range.SORT_FROM)
					)
					{
						range = this.currentQuantityRanges[i];
					}
				}
			}

			return range;
		},

		checkQuantityControls: function()
		{
			if (!this.obQuantity)
				return;

			var reachedTopLimit = this.checkQuantity && parseFloat(this.obQuantity.value) + this.stepQuantity > this.maxQuantity,
				reachedBottomLimit = parseFloat(this.obQuantity.value) - this.stepQuantity < this.minQuantity;

			if (reachedTopLimit)
			{
				BX.addClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
			}
			else if (BX.hasClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled'))
			{
				BX.removeClass(this.obQuantityUp, 'product-item-amount-field-btn-disabled');
			}

			if (reachedBottomLimit)
			{
				BX.addClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
			}
			else if (BX.hasClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled'))
			{
				BX.removeClass(this.obQuantityDown, 'product-item-amount-field-btn-disabled');
			}

			if (reachedTopLimit && reachedBottomLimit)
			{
				this.obQuantity.setAttribute('disabled', 'disabled');
			}
			else
			{
				this.obQuantity.removeAttribute('disabled');
			}
		},

		setPrice: function()
		{
			var obData, price;

			if (this.obQuantity)
			{
				this.checkPriceRange(this.obQuantity.value);
			}

			this.checkQuantityControls();

			price = this.currentPrices[this.currentPriceSelected];

			if (this.obPrice)
			{
				if (price)
				{
					BX.adjust(this.obPrice, {html: BX.Currency.currencyFormat(price.RATIO_PRICE, price.CURRENCY, true)});
				}
				else
				{
					BX.adjust(this.obPrice, {html: ''});
				}

				if (this.showOldPrice && this.obPriceOld)
				{
					if (price && price.RATIO_PRICE !== price.RATIO_BASE_PRICE)
					{
						BX.adjust(this.obPriceOld, {
							style: {display: ''},
							html: BX.Currency.currencyFormat(price.RATIO_BASE_PRICE, price.CURRENCY, true)
						});
					}
					else
					{
						BX.adjust(this.obPriceOld, {
							style: {display: 'none'},
							html: ''
						});
					}
				}

				if (this.obPriceTotal)
				{
					if (price && this.obQuantity && this.obQuantity.value != this.stepQuantity)
					{
						BX.adjust(this.obPriceTotal, {
							html: BX.message('PRICE_TOTAL_PREFIX') + ' <strong>'
							+ BX.Currency.currencyFormat(price.PRICE * this.obQuantity.value, price.CURRENCY, true)
							+ '</strong>',
							style: {display: ''}
						});
					}
					else
					{
						BX.adjust(this.obPriceTotal, {
							html: '',
							style: {display: 'none'}
						});
					}
				}

				if (this.showPercent)
				{
					if (price && parseInt(price.DISCOUNT) > 0)
					{
						obData = {style: {display: ''}, html: -price.PERCENT + '%'};
					}
					else
					{
						obData = {style: {display: 'none'}, html: ''};
					}

					if (this.obDscPerc)
					{
						BX.adjust(this.obDscPerc, obData);
					}

					if (this.obSecondDscPerc)
					{
						BX.adjust(this.obSecondDscPerc, obData);
					}
				}
			}
		},

		compare: function(event)
		{
			var checkbox = this.obCompare.querySelector('[data-entity="compare-checkbox"]'),
				target = BX.getEventTarget(event),
				checked = true;

			if (checkbox)
			{
				checked = target === checkbox ? checkbox.checked : !checkbox.checked;
			}

			var url = checked ? this.compareData.compareUrl : this.compareData.compareDeleteUrl,
				compareLink;

			if (url)
			{
				if (target !== checkbox)
				{
					BX.PreventDefault(event);
					this.setCompared(checked);
				}

				switch (this.productType)
				{
					case 0: // no catalog
					case 1: // product
					case 2: // set
						compareLink = url.replace('#ID#', this.product.id.toString());
						break;
					case 3: // sku
						compareLink = url.replace('#ID#', this.offers[this.offerNum].ID);
						break;
				}

				BX.ajax({
					method: 'POST',
					dataType: checked ? 'json' : 'html',
					url: compareLink + (compareLink.indexOf('?') !== -1 ? '&' : '?') + 'ajax_action=Y',
					onsuccess: checked
						? BX.proxy(this.compareResult, this)
						: BX.proxy(this.compareDeleteResult, this)
				});
			}
		},

		compareResult: function(result)
		{
			var popupContent, popupButtons;

			if (this.obPopupWin)
			{
				this.obPopupWin.close();
			}

			if (!BX.type.isPlainObject(result))
				return;

			this.initPopupWindow();

			if (this.offers.length > 0)
			{
				this.offers[this.offerNum].COMPARED = result.STATUS === 'OK';
			}

			if (result.STATUS === 'OK')
			{
				BX.onCustomEvent('OnCompareChange');

				popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
					+ BX.message('COMPARE_MESSAGE_OK')
					+ '</p></div>';

				if (this.showClosePopup)
				{
					popupButtons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
							events: {
								click: BX.delegate(this.compareRedirect, this)
							},
							style: {marginRight: '10px'}
						}),
						new BasketButton({
							text: BX.message('BTN_MESSAGE_CLOSE_POPUP'),
							events: {
								click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
							}
						})
					];
				}
				else
				{
					popupButtons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_COMPARE_REDIRECT'),
							events: {
								click: BX.delegate(this.compareRedirect, this)
							}
						})
					];
				}
			}
			else
			{
				popupContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
					+ (result.MESSAGE ? result.MESSAGE : BX.message('COMPARE_UNKNOWN_ERROR'))
					+ '</p></div>';
				popupButtons = [
					new BasketButton({
						text: BX.message('BTN_MESSAGE_CLOSE'),
						events: {
							click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
						}
					})
				];
			}

			this.obPopupWin.setTitleBar(BX.message('COMPARE_TITLE'));
			this.obPopupWin.setContent(popupContent);
			this.obPopupWin.setButtons(popupButtons);
			this.obPopupWin.show();
		},

		compareDeleteResult: function()
		{
			BX.onCustomEvent('OnCompareChange');

			if (this.offers && this.offers.length)
			{
				this.offers[this.offerNum].COMPARED = false;
			}
		},

		setCompared: function(state)
		{
			if (!this.obCompare)
				return;

			var checkbox = this.obCompare.querySelector('[data-entity="compare-checkbox"]');
			if (checkbox)
			{
				checkbox.checked = state;
			}
		},

		setCompareInfo: function(comparedIds)
		{
			if (!BX.type.isArray(comparedIds))
				return;

			for (var i in this.offers)
			{
				if (this.offers.hasOwnProperty(i))
				{
					this.offers[i].COMPARED = BX.util.in_array(this.offers[i].ID, comparedIds);
				}
			}
		},

		compareRedirect: function()
		{
			if (this.compareData.comparePath)
			{
				location.href = this.compareData.comparePath;
			}
			else
			{
				this.obPopupWin.close();
			}
		},

		checkDeletedCompare: function(id)
		{
			switch (this.productType)
			{
				case 0: // no catalog
				case 1: // product
				case 2: // set
					if (this.product.id == id)
					{
						this.setCompared(false);
					}

					break;
				case 3: // sku
					var i = this.offers.length;
					while (i--)
					{
						if (this.offers[i].ID == id)
						{
							this.offers[i].COMPARED = false;

							if (this.offerNum == i)
							{
								this.setCompared(false);
							}

							break;
						}
					}
			}
		},

		initBasketUrl: function()
		{
			this.basketUrl = (this.basketMode === 'ADD' ? this.basketData.add_url : this.basketData.buy_url);
			switch (this.productType)
			{
				case 1: // product
				case 2: // set
					this.basketUrl = this.basketUrl.replace('#ID#', this.product.id.toString());
					break;
				case 3: // sku
					this.basketUrl = this.basketUrl.replace('#ID#', this.offers[this.offerNum].ID);
					break;
			}
			this.basketParams = {
				'ajax_basket': 'Y'
			};
			if (this.showQuantity)
			{
				this.basketParams[this.basketData.quantity] = this.obQuantity.value;
			}
			if (this.basketData.sku_props)
			{
				this.basketParams[this.basketData.sku_props_var] = this.basketData.sku_props;
			}
		},

		fillBasketProps: function()
		{
			if (!this.visual.BASKET_PROP_DIV)
			{
				return;
			}
			var
				i = 0,
				propCollection = null,
				foundValues = false,
				obBasketProps = null;

			if (this.basketData.useProps && !this.basketData.emptyProps)
			{
				if (this.obPopupWin && this.obPopupWin.contentContainer)
				{
					obBasketProps = this.obPopupWin.contentContainer;
				}
			}
			else
			{
				obBasketProps = BX(this.visual.BASKET_PROP_DIV);
			}
			if (obBasketProps)
			{
				propCollection = obBasketProps.getElementsByTagName('select');
				if (propCollection && propCollection.length)
				{
					for (i = 0; i < propCollection.length; i++)
					{
						if (!propCollection[i].disabled)
						{
							switch (propCollection[i].type.toLowerCase())
							{
								case 'select-one':
									this.basketParams[propCollection[i].name] = propCollection[i].value;
									foundValues = true;
									break;
								default:
									break;
							}
						}
					}
				}
				propCollection = obBasketProps.getElementsByTagName('input');
				if (propCollection && propCollection.length)
				{
					for (i = 0; i < propCollection.length; i++)
					{
						if (!propCollection[i].disabled)
						{
							switch (propCollection[i].type.toLowerCase())
							{
								case 'hidden':
									this.basketParams[propCollection[i].name] = propCollection[i].value;
									foundValues = true;
									break;
								case 'radio':
									if (propCollection[i].checked)
									{
										this.basketParams[propCollection[i].name] = propCollection[i].value;
										foundValues = true;
									}
									break;
								default:
									break;
							}
						}
					}
				}
			}
			if (!foundValues)
			{
				this.basketParams[this.basketData.props] = [];
				this.basketParams[this.basketData.props][0] = 0;
			}
		},

		add2Basket: function()
		{
			this.basketMode = 'ADD';
			this.basket();
		},

		buyBasket: function()
		{
			this.basketMode = 'BUY';
			this.basket();
		},

		sendToBasket: function()
		{
			if (!this.canBuy)
			{
				return;
			}

			// check recommendation
			if (this.product && this.product.id && this.bigData)
			{
				this.rememberProductRecommendation();
			}

			this.initBasketUrl();
			this.fillBasketProps();
			BX.ajax({
				method: 'POST',
				dataType: 'json',
				url: this.basketUrl,
				data: this.basketParams,
				onsuccess: BX.proxy(this.basketResult, this)
			});
		},

		basket: function()
		{
			var contentBasketProps = '';
			if (!this.canBuy)
			{
				return;
			}
			switch (this.productType)
			{
				case 1: // product
				case 2: // set
					if (this.basketData.useProps && !this.basketData.emptyProps)
					{
						this.initPopupWindow();
						this.obPopupWin.setTitleBar(BX.message('TITLE_BASKET_PROPS'));
						if (BX(this.visual.BASKET_PROP_DIV))
						{
							contentBasketProps = BX(this.visual.BASKET_PROP_DIV).innerHTML;
						}
						this.obPopupWin.setContent(contentBasketProps);
						this.obPopupWin.setButtons([
							new BasketButton({
								text: BX.message('BTN_MESSAGE_SEND_PROPS'),
								events: {
									click: BX.delegate(this.sendToBasket, this)
								}
							})
						]);
						this.obPopupWin.show();
					}
					else
					{
						this.sendToBasket();
					}
					break;
				case 3: // sku
					this.sendToBasket();
					break;
			}
		},

		basketResult: function(arResult)
		{
			var strContent = '',
				strPict = '',
				successful,
				buttons = [];

			if (this.obPopupWin)
				this.obPopupWin.close();

			if (!BX.type.isPlainObject(arResult))
				return;

			successful = arResult.STATUS === 'OK';

			if (successful)
			{
				this.setAnalyticsDataLayer('addToCart');
			}

			if (successful && this.basketAction === 'BUY')
			{
				this.basketRedirect();
			}
			else
			{
				this.initPopupWindow();

				if (successful)
				{
					BX.onCustomEvent('OnBasketChange');

					if  (BX.findParent(this.obProduct, {className: 'bx_sale_gift_main_products'}, 10))
					{
						BX.onCustomEvent('onAddToBasketMainProduct', [this]);
					}

					switch (this.productType)
					{
						case 1: // product
						case 2: // set
							strPict = this.product.pict.SRC;
							break;
						case 3: // sku
							strPict = (this.offers[this.offerNum].PREVIEW_PICTURE ?
									this.offers[this.offerNum].PREVIEW_PICTURE.SRC :
									this.defaultPict.pict.SRC
							);
							break;
					}

					strContent = '<div style="width: 100%; margin: 0; text-align: center;"><img src="'
						+ strPict + '" height="130" style="max-height:130px"><p>' + this.product.name + '</p></div>';

					if (this.showClosePopup)
					{
						buttons = [
							new BasketButton({
								text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
								events: {
									click: BX.delegate(this.basketRedirect, this)
								},
								style: {marginRight: '10px'}
							}),
							new BasketButton({
								text: BX.message("BTN_MESSAGE_CLOSE_POPUP"),
								events: {
									click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
								}
							})
						];
					}
					else
					{
						buttons = [
							new BasketButton({
								text: BX.message("BTN_MESSAGE_BASKET_REDIRECT"),
								events: {
									click: BX.delegate(this.basketRedirect, this)
								}
							})
						];
					}
				}
				else
				{
					strContent = '<div style="width: 100%; margin: 0; text-align: center;"><p>'
						+ (arResult.MESSAGE ? arResult.MESSAGE : BX.message('BASKET_UNKNOWN_ERROR'))
						+ '</p></div>';
					buttons = [
						new BasketButton({
							text: BX.message('BTN_MESSAGE_CLOSE'),
							events: {
								click: BX.delegate(this.obPopupWin.close, this.obPopupWin)
							}
						})
					];
				}
				this.obPopupWin.setTitleBar(successful ? BX.message('TITLE_SUCCESSFUL') : BX.message('TITLE_ERROR'));
				this.obPopupWin.setContent(strContent);
				this.obPopupWin.setButtons(buttons);
				this.obPopupWin.show();
			}
		},

		basketRedirect: function()
		{
			location.href = (this.basketData.basketUrl ? this.basketData.basketUrl : BX.message('BASKET_URL'));
		},

		initPopupWindow: function()
		{
			if (this.obPopupWin)
				return;

			this.obPopupWin = BX.PopupWindowManager.create('CatalogSectionBasket_' + this.visual.ID, null, {
				autoHide: true,
				offsetLeft: 0,
				offsetTop: 0,
				overlay : true,
				closeByEsc: true,
				titleBar: true,
				closeIcon: true,
				contentColor: 'white',
				className: this.templateTheme ? 'bx-' + this.templateTheme : ''
			});
		}
	};
})(window);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:97:"/bitrix/components/bitrix/sale.gift.main.products/templates/.default/script.min.js?15723421072661";s:6:"source";s:78:"/bitrix/components/bitrix/sale.gift.main.products/templates/.default/script.js";s:3:"min";s:82:"/bitrix/components/bitrix/sale.gift.main.products/templates/.default/script.min.js";s:3:"map";s:82:"/bitrix/components/bitrix/sale.gift.main.products/templates/.default/script.map.js";}"*/
BX.namespace("BX.Sale");BX.Sale.GiftMainProductsClass=function(){var t=function(t){this.ajaxUrl="/bitrix/components/bitrix/sale.gift.main.products/ajax.php";this.contextAjaxData=t.contextAjaxData||{};this.mainProductState=t.mainProductState||null;this.injectId=t.injectId||null;this.isGift=!!t.isGift;this.productId=t.productId;this.offerId=t.offerId;this.offers=t.offers||[];this.setEvents();if(document.location.hash.match(/as_gift/g)){if(this.isGift){this.enableGift()}else{this.raiseNonGiftEvent()}}BX.bindDelegate(BX(this.injectId),"click",{tagName:"a"},BX.proxy(this.clickNavLink,this))};t.prototype.clickNavLink=function(t){if(this.onPageNavigationByLink(BX.proxy_context)){return BX.PreventDefault(t)}};t.prototype.setEvents=function(){BX.addCustomEvent("onCatalogStoreProductChange",BX.proxy(this.onCatalogStoreProductChange,this));BX.addCustomEvent("onAddToBasketMainProduct",BX.proxy(this.onAddToBasketMainProduct,this))};t.prototype.unsubscribeEvents=function(){BX.removeCustomEvent("onCatalogStoreProductChange",BX.proxy(this.onCatalogStoreProductChange,this))};t.prototype.onAddToBasketMainProduct=function(t){this.enableGift()};t.prototype.onCatalogStoreProductChange=function(t){if(t==this.offerId){return}BX.ajax({url:this.ajaxUrl,method:"POST",data:BX.merge(this.contextAjaxData,{offerId:t,mainProductState:this.mainProductState,SITE_ID:BX.message("SITE_ID")}),dataType:"html",processData:false,start:true,onsuccess:BX.delegate(function(e){this.offerId=t;var i=BX.processHTML(e);if(!i.HTML){if(document.location.hash.match(/as_gift/g)){if(this.isGift){this.raiseGiftEvent()}else{this.raiseNonGiftEvent()}}return}this.unsubscribeEvents();BX(this.injectId).innerHTML=i.HTML;BX.ajax.processScripts(i.SCRIPT)},this)})};t.prototype.onPageNavigationByLink=function(t){var e=BX.delegate(function(t){if(!BX.type.isElementNode(t)||!t.href){return false}if(t.href.indexOf(this.ajaxUrl)>=0){return true}return t.href.indexOf("PAGEN_")!==-1},this);if(!e(t)){return false}BX.ajax({url:t.href,method:"POST",data:BX.merge(this.contextAjaxData,{SITE_ID:BX.message("SITE_ID")}),dataType:"html",processData:false,start:true,onsuccess:BX.delegate(function(t){var e=BX.processHTML(t);if(!e.HTML){return}this.unsubscribeEvents();BX(this.injectId).innerHTML=e.HTML;BX.ajax.processScripts(e.SCRIPT)},this)});return true};t.prototype.enableGift=function(){this.isGift=true;this.raiseGiftEvent()};t.prototype.raiseGiftEvent=function(){BX.onCustomEvent("onSaleProductIsGift",[this.productId,this.offerId])};t.prototype.raiseNonGiftEvent=function(){BX.onCustomEvent("onSaleProductIsNotGift",[this.productId,this.offerId])};return t}();
/* End */
;
; /* Start:"a:4:{s:4:"full";s:80:"/local/components/uvelirsoft/reviews/templates/.default/script.js?15723419811071";s:6:"source";s:65:"/local/components/uvelirsoft/reviews/templates/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/

function latlng2distance(lat1, long1, lat2, long2) {
    //радиус Земли
    var R = 6372795;
    //перевод коордитат в радианы
    lat1 *= Math.PI / 180;
    lat2 *= Math.PI / 180;
    long1 *= Math.PI / 180;
    long2 *= Math.PI / 180;
    //вычисление косинусов и синусов широт и разницы долгот
    var cl1 = Math.cos(lat1);
    var cl2 = Math.cos(lat2);
    var sl1 = Math.sin(lat1);
    var sl2 = Math.sin(lat2);
    var delta = long2 - long1;
    var cdelta = Math.cos(delta);
    var sdelta = Math.sin(delta);
    //вычисления длины большого круга
    var y = Math.sqrt(Math.pow(cl2 * sdelta, 2) + Math.pow(cl1 * sl2 - sl1 * cl2 * cdelta, 2));
    var x = sl1 * sl2 + cl1 * cl2 * cdelta;
    var ad = Math.atan2(y, x);
    var dist = ad * R; //расстояние между двумя координатами в метрах
    return dist
}

function compareNumbers(n1,n2)
{
  if (n1==n2) return 0;
  if (n1>n2)
    return 1;
  else 
    return -1;
}
/* End */
;
; /* Start:"a:4:{s:4:"full";s:96:"/local/templates/us_2018/components/bitrix/main.file.input/drag_n_drop/script.js?157234198120259";s:6:"source";s:80:"/local/templates/us_2018/components/bitrix/main.file.input/drag_n_drop/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
(function() {
if (window.BlogBFileDialog)
	return;
window.BlogBFileDialogUniqueID = [];
window.BlogBFileDialog = function(arParams)
{
	this.dialogName = 'AttachmentsDialog';
	this.agent = false;
	this.uploadFileUrl = arParams.upload_path; // from file.input php

	this.id = (!!arParams["id"] ? arParams["id"] : this.getID());
	this.controlID = arParams["id"];
	this.enabled = true;

	this.controller = (!! arParams.controller ) ? arParams.controller : null;
	this.fileInput = arParams.fileInput;
	arParams.hAttachEvents = BX.delegate(this.InitAgent, this);

	this.msg = arParams.msg;
	this.dropAutoUpload = arParams.dropAutoUpload;
	this.CID = arParams.CID;
	this.multiple = !!arParams.multiple;

	arParams.caller = this;
	arParams.classes = {
		'uploaderParent' : 'file-uploader',
		'uploader' : 'file-fileUploader',
		'tpl_simple' : 'file-simple',
		'tpl_extended' : 'file-extended',
		'selector' : 'file-selector',
		'selector_active' : 'file-selector-active'
	};
	arParams.doc_prefix = 'wd-doc';
	arParams.placeholder = BX.findChild(this.controller, {'className': 'file-placeholder-tbody'}, true);
	this.doc_prefix = arParams.doc_prefix;
	this.values = (arParams["values"] || []);

	if (!!BX.FileUploadAgent) {
		this.agent = new BX.FileUploadAgent(arParams);
		BX.addCustomEvent(this, 'ShowUploadedFile', BX.delegate(this.ShowUploadedFile, this));
		BX.addCustomEvent(this, 'StopUpload', BX.delegate(this.StopUpload, this));
		BX.onCustomEvent(BX(this.controller.parentNode), "BFileDLoadFormControllerInit", [this]);
	} else {
		BX.debug('/bitrix/components/bitrix/main.file.input/templates/drag_n_drop/script.js: BX.FileUploadAgent is not defined.' +
			' You need to load /bitrix/js/main/file_upload_agent.js');
	}
}

window.BlogBFileDialog.prototype.getID = function() {
	return '' + new Date().getTime();
}

window.BlogBFileDialog.prototype.InitAgent = function(agent)
{
	if (this.controller) {
		agent.placeholder = BX.findChild(this.controller, {'className': 'file-placeholder-tbody'}, true);
	}
}

window.BlogBFileDialog.prototype.ShowUploadedFile = function(agent) // event
{
	this.agent = agent;
	var uploadResult = agent.uploadResult;

	if (uploadResult && (uploadResult.element_id > 0)) {
		if (!!agent.inputName && agent.inputName.length > 0) {
			var hidden = BX.create('INPUT', {
				props: {
					'id': 'file-doc'+uploadResult.element_id,
					'type': 'hidden',
					'name': agent.inputName + (this.multiple ? '[]' : ''),
					'value': uploadResult.element_id
				}
			});
			agent.controller.appendChild(hidden);
		}
		this.values.push(this.CreateFileRow(uploadResult));
		agent._clearPlace();

		if (this.controller && this.controller.parentNode)
			BX.onCustomEvent(this.controller.parentNode, 'OnFileUploadSuccess', [uploadResult, this]);

	} else {
		var text = (uploadResult && uploadResult["error"] ? uploadResult["error"] : this.msg.upload_error);
		agent.ShowUploadError(text);

		if (this.controller && this.controller.parentNode)
			BX.onCustomEvent(this.controller.parentNode, 'OnFileUploadFail');
	}
}

window.BlogBFileDialog.prototype.CreateFileRow = function(result)
{
	var res = result;
	var mode = 'file';
	if (!! res.element_content_type && (res.element_content_type.indexOf('image/') == 0) &&
		!!res.element_image && (res.element_image.length > 0) &&
		!!res.element_thumbnail && (res.element_thumbnail.length > 0) ) {
		mode = 'image';
	}

	var tpl = BX("file-" + mode + "-template");

	BX.template(tpl, BX.delegate(function(node) {
		this.tplFileRow(node, res);
	}, this));
	var newNode = BX.clone(tpl);

	if (mode == 'image') {
		var span = null;
		for (i=0;i<newNode.children.length;i++)
		{
			span = newNode.children[i];
			if (span.nodeType == 1)
				break;
		}

		span.setAttribute('id', this.doc_prefix + result.element_id);
		var closeControl = BX.findChild(span, {'className': 'feed-add-post-del-but'}, true);
		BX.bind(closeControl, 'click', BX.delegate(
			function() {
				var control = closeControl;
				var parent = control.parentNode;
				this.agent.StopUpload(parent);
				BX.cleanNode(parent, true);
			}, this));
		this.agent.AddNodeToPlaceholder(span);
		newNode = span;
	} else {
		newNode.setAttribute('id', this.doc_prefix + result.element_id);
		this.agent.AddRowToPlaceholder(newNode);
	}
	return newNode;
}

window.BlogBFileDialog.prototype.GetUploadDialog = function(agent)
{
	return new BlogBFileDialogUploader(this, agent);
}

window.BlogBFileDialog.prototype.tplFileRow = function(nodes, res)
{
	for (id in nodes)
	{
		if (! nodes.hasOwnProperty(id))
			continue;

		var node = nodes[id];

		if ((id == 'image') &&
			!!res.element_image && (res.element_image.length > 0) &&
			!!res.element_thumbnail && (res.element_thumbnail.length > 0))
		{
			node.setAttribute('src', res.element_image);
			node.setAttribute('rel', res.element_thumbnail);
		}
		else
		{
			if (!! res['element_'+id])
				node.innerHTML = res['element_'+id];
		}
	}
}

window.BlogBFileDialog.prototype._addUrlParam = function(url, param)
{
	if (!url)
		return null;
	if (url.indexOf(param) == -1)
		url += ((url.indexOf('?') == -1) ? '?' : '&') + param ;
	return url;
}

window.BlogBFileDialog.prototype.LoadDialogs = function(dialogs)
{
	if (!!this.agent)
		this.agent.LoadDialogs(dialogs);
	else {
		var dlgs = dialogs;
		setTimeout(BX.delegate(function() {this.LoadDialogs(dlgs);}, this), 100);
	}
}

window.BlogBFileDialog.prototype.StopUpload = function(agent, parent)
{
	this.agent = agent;
	id = false;
	mID = parent.id.match(new RegExp(this.doc_prefix + '(\\d+)'));
	if (!!mID) {
		id = mID[1];
	}

	if (this.controller && this.controller.parentNode)
		BX.onCustomEvent(this.controller.parentNode, 'OnFileUploadRemove', [id, this]);

	var data = {
		fileID : id,
		sessid : BX.bitrix_sessid(),
		cid : this.CID,
		controlID : this.controlID,
		mfi_mode : "delete"
	};
	BX.ajax.post(this.uploadFileUrl, data);
}

window.BlogBFileDialogDispatcher = function(controller)
{
	this.id = this.getID();
	this.controller = controller;
	BX.loadScript('/bitrix/js/main/core/core_dd.js', BX.delegate(function() {
		if (BX.type.isElementNode(this.controller) && this.controller.parentNode && this.controller.parentNode.parentNode)
		{
			var target = this.controller.parentNode.parentNode;
			this.dropbox = new BX.DD.dropFiles(target);
			if (this.dropbox && this.dropbox.supported() && BX.ajax.FormData.isSupported()) {
				this.hExpandUploader = BX.proxy(this.ExpandUploader, this);
				BX.addCustomEvent(this.dropbox, 'dragEnter', this.hExpandUploader);
				BX.addCustomEvent(target, "UnbindDndDispatcher", BX.delegate(this.Unbind, this));
			}
		}
	}, this));
}

window.BlogBFileDialogDispatcher.prototype.getID = function() {
	return '' + new Date().getTime();
}

window.BlogBFileDialogDispatcher.prototype.ExpandUploader = function()
{
	BX.onCustomEvent(BX(this.controller.parentNode), "BFileDLoadFormController", ['show']);
//	this.Unbind();
}

window.BlogBFileDialogDispatcher.prototype.Unbind = function()
{
	BX.removeCustomEvent(this.dropbox, 'dragEnter', this.hExpandUploader);
}

// upoader section
window.BlogBFileDialogUploader = function(arParams, agent)
{
	this.WDUploaded = false;
	this.WDUploadInProgress = false;
	this.documentExists = false;
	this.fileDropped = false;

	this.caller = arParams;
	this.agent = agent;
	this.parentID = this.agent.id;
	this.id = this.caller.getID();

	this.msg = arParams.msg;
	this.dropAutoUpload = arParams.dropAutoUpload;
	this.uploadFileUrl = arParams.uploadFileUrl; // from file.input php
	this.CID = arParams.CID;
	this.controlID = arParams.controlID;

	this.CreateElements();
	this.fileInput = (!!agent.fileInput ? agent.fileInput : ((BX.type.isDomNode(agent.fileInputID)) ? agent.fileInputID : BX(arParams.fileInput)));
	if (BX.type.isDomNode(this.fileInput)) {
		this.fileInput.name = 'mfi_files[]';
	}
	this.fileList = this.__form;

	BX.loadScript('/bitrix/js/main/core/core_dd.js', BX.delegate(
		function() {
			var dropbox = new BX.DD.dropFiles();
			if (dropbox && dropbox.supported() && BX.ajax.FormData.isSupported())
			{
				this.dropbox = dropbox;
			}
			this.agent.BindUploadEvents(this);
		}, this));
}

window.BlogBFileDialogUploader.prototype.CreateElements = function()
{
	var uniqueID;
	do {
		uniqueID = Math.floor(Math.random() * 99999);
	} while(BX("iframe-" + uniqueID));

	var iframeName = "iframe-" + this.id;
	var iframe = BX.create("IFRAME", {
		props: {name: iframeName, id: iframeName},
		style: {display: "none"}
	});
	document.body.appendChild(iframe);
	this.iframeUpload = iframe;

	var form = BX.create("FORM", {
		props: {
			id: "form-" + uniqueID,
			method: "POST",
			action: this.uploadFileUrl,
			enctype: "multipart/form-data",
			encoding: "multipart/form-data",
			target: iframeName
		},
		style: {display: "none"},
		children: [
			BX.create("INPUT", {
				props: {
					type: "hidden",
					name: "sessid",
					value: BX.bitrix_sessid()
				}
			}),
			BX.create("INPUT", {
				props: {
					type: "hidden",
					name: "uniqueID",
					value: uniqueID
				}
			}),
			BX.create("INPUT", {
				props: {
					type: "hidden",
					name: "cid",
					value: this.CID
				}
			}),
			BX.create("INPUT", {
				props: {
					type: "hidden",
					name: "controlID",
					value: (!!this.controlID ? this.controlID : '')
				}
			}),
			BX.create("INPUT", {
				props: {
					type: "hidden",
					name: "mfi_mode",
					value: "upload"
				}
			})
		]
	});
	document.body.appendChild(form);
	this.__form = form;

	window['FILE_UPLOADER_CALLBACK_' + uniqueID] = BX.proxy(this.Callback, this);
}

window.BlogBFileDialogUploader.prototype.GetUploadFileName = function()
{
	var fileName = '';
	if (this.fileInput && (this.fileInput.value.length > 0)) {
		var fileName = this.fileInput.value;
		if (fileName.indexOf('\\') > -1) // deal with Chrome fakepath
			fileName = fileName.substr(fileName.lastIndexOf('\\')+1);
	} else {
		var fileNode = this.fileList;
		if (fileNode.file)
			fileName = fileNode.file.fileName || fileNode.file.name;
	}
	return fileName;
}

window.BlogBFileDialogUploader.prototype.Callback = function(files, uniqueID)
{
	if (files.length > 0) {
		for(var i = 0; i < files.length; i++) {
			var result = {};
			result.success = true;
			result.storage = 'bfile';
			result.element_id = files[i].fileID;
			result.element_name = files[i].fileName;
			result.element_size = files[i].fileSize;
			result.element_url = files[i].fileURL;
			result.element_content_type = (files[i].content_type ? files[i].content_type : files[i].fileContentType);

			result.element_image = ((!!files[i].img_thumb_src) ? files[i].img_thumb_src : files[i].fileSrc);
			if (!!result.element_image)
				result.element_image = result.element_image.replace(/\/([^\/]+)$/, function(str, name) { return "/" + BX.util.urlencode(name); } );
			result.element_thumbnail = ((!!files[i].img_source_src) ? files[i].img_source_src: files[i].fileSrc);
			if (!!result.element_thumbnail)
				result.element_thumbnail = result.element_thumbnail.replace(/\/([^\/]+)$/, function(str, name) { return "/" + BX.util.urlencode(name); } );
			if (files[i]["error"])
				result["error"] = files[i]["error"];

			BX.onCustomEvent(this, 'uploadFinish', [result]);
		}
	} else {
		var result = {};
		result.success = false;
		result.messages = this.msg.upload_error;
		BX.onCustomEvent(this, 'uploadFinish', [result]);
	}
	window['FILE_UPLOADER_CALLBACK_' + uniqueID] = BX.DoNothing;
	BX.cleanNode(BX("iframe-" + uniqueID), true);
	BX.cleanNode(BX("form-" + uniqueID), true);
	this.agent.uploadDialog = null;
}

window.BlogBFileDialogUploader.prototype.UploadResponse = function(evt, responseJSONStr)
{
	this.WDUploadInProgress = false;
	BX.unbind(window, 'beforeunload', BX.proxy(this.UploadLeave, this));

	if (!  responseJSONStr
		|| responseJSONStr.length <= 0)
	{
		this.onError();
	}
}

window.BlogBFileDialogUploader.prototype.UploadResponseIframe = function(evt, responseJSONStr)
{
	this.WDUploadInProgress = false;
	BX.unbind(window, 'beforeunload', BX.proxy(this.UploadLeave, this));
}

window.BlogBFileDialogUploader.prototype.UploadLeave = function(e)
{
	var e = e || window.event;
	var msg = '';
	if (this.WDUploadInProgress)
		msg = this.msg.UploadInterrupt;
	else if (((!this.WDUploaded) && this.fileInput && (this.fileInput.value.length > 0)))
		msg = this.msg.UploadNotDone;
	if (msg != '')
	{
		if (e)
			e.returnValue = msg;
		return msg; // safari & chrome
	}
	return;
}

window.BlogBFileDialogUploader.prototype.UpdateListFiles = function(files)
{
	if (this && files)
	{
		if (files.length < 1)
			return;
		var fileNode = this.fileList;
		fileNode.file = files[0];

		this.WDUploadInProgress = true;
		this.fileDropped = true;
		this.CallSubmit();
	}
}

window.BlogBFileDialogUploader.prototype.GetInputData = function(parentNode)
{
	var elements = [];
	var data = {};
	elements = elements.concat(
		BX.findChildren(parentNode, {'tag': 'input'}, true),
		BX.findChildren(parentNode, {'tag': 'textarea'}, true),
		BX.findChildren(parentNode, {'tag': 'select'}, true));

	for(var i=0; i<elements.length; i++)
	{
		var el = elements[i];
		if (!el || el.disabled || el.name.length < 1)
			continue;
		switch(el.type.toLowerCase())
		{
			case 'text':
			case 'textarea':
			case 'password':
			case 'hidden':
			case 'select-one':
				data[el.name] = el.value;
				break;
			case 'radio':
				if(el.checked)
					data[el.name] = el.value;
				break;
			case 'checkbox':
				data[el.name] = (el.checked ? 'Y':'N');
				break;
			case 'select-multiple':
				var l = el.options.length;
				if (l > 0) data[el.name] = new Array();
				for (j=0; j<l; j++)
					if (el.options[j].selected)
						data[el.name].push(el.options[j].value);
				break;
			default:
				break;
		}
	}
	return data;
}

window.BlogBFileDialogUploader.prototype.SetFileInput = function(fileInput)
{
	if (!! this.__form.mfi_save)
		return;
	if (this.fileInput && this.fileInput != fileInput)
		BX.remove(this.fileInput);
	this.__form.appendChild(fileInput);
	this.fileInput = fileInput;
}

window.BlogBFileDialogUploader.prototype.CallSubmit = function()
{
	if (!! this.__form.mfi_save)
		return;
	BX.onCustomEvent(this, 'uploadStart', [this]);

	BX.bind(window, 'beforeunload', BX.proxy(this.UploadLeave, this));
	BX.bind(this.iframeUpload, 'load', BX.delegate(this.UploadResponseIframe, this));

	if (this.dropbox) {
		this.onProgress(0.15);
		if (this.fileInput && (this.fileInput.files.length > 0)) {
			var fileNode = this.fileList;
			fileNode.file = this.fileInput.files[0];
		}

		var arConstParams = this.GetInputData(this.__form);
		this.fileNodes = [this.fileList];
		for (i in this.fileNodes) {
			if (this.fileNodes[i].file) {
				var fd = new BX.ajax.FormData();

				for (item in this.fileNodes[i].data)
				{
					fd.append(item, this.fileNodes[i].data[item]);
				}

				if (!! Object && !! Object.keys) // for IE 10 ....
				{
					var keys = Object.keys(arConstParams);
					for (var k in keys)
					{
						var key = keys[k]
						var cons = arConstParams[key]
						fd.append(key, cons);
					}
				}
				else
				{
					for (item in arConstParams)
					{
						fd.append(item, arConstParams[item]);
					}
				}

				fd.append('mfi_files[]', this.fileNodes[i].file);

				fd.send(
					this.uploadFileUrl,
					BX.delegate(function(ajaxdata) {
						this.UploadResponse(null, ajaxdata);
					}, this),
					BX.delegate(this.onProgress, this)
				);
			}
		}
	} else {
		this.onProgress(0.15);
		this.WDUploadInProgress = true;
		var fid = this.__form.id;
		BX.submit(this.__form, 'mfi_save', 'Y');
	}
}

window.BlogBFileDialogUploader.prototype.onProgress = function(percent)
{
	if (isNaN(percent))
		return;
	BX.onCustomEvent(this, 'progress', [percent]);
}

window.BlogBFileDialogUploader.prototype.onError = function()
{
	BX.onCustomEvent(this, 'uploadFinish', [{success: false, messages: this.msg.upload_error}]);
}

top.BlogBFileDialog = window.BlogBFileDialog;
top.BlogBFileDialogUploader = window.BlogBFileDialogUploader;
top.BlogBFileDialogDispatcher = window.BlogBFileDialogDispatcher;

window.MFIDD = function(params){
	BX.loadCSS('/bitrix/components/bitrix/main.file.input/templates/drag_n_drop/style.css');

	var status = (params["status"] === 'show' ? 'show' : (params["status"] === 'hide' ? 'hide' : 'switch'));
	if (status == 'switch')
		status = (params['controller'].style.display != 'none' ? 'hide' : 'show');
	var showControl = function(status)
	{
		if (status == "show")
		{
			BX.fx.show(params['controller'], 'fade', {time:0.2});
			if (params['switcher'] && params['switcher'].style.display != 'none')
				BX.fx.hide(params['switcher'], 'fade', {time:0.1});
			if (!! window['BfileUnbindDispatcher' + params['uid']])
				window['BfileUnbindDispatcher' + params['uid']]();
		}
		else if (params['controller'].style.display !== "none")
		{
			BX.fx.hide(params['controller'], 'fade', {time:0.2});
		}
	};
	if (! params['controller'].loaded)
	{
		params['controller'].loaded = true;
		var dropbox = new BX.DD.dropFiles(),
			variant = (dropbox && dropbox.supported() && BX.ajax.FormData.isSupported() ? 'extended' : 'simple');

		top['BfileFD' + params['uid']] = window['BfileFD' + params['uid']] = new BlogBFileDialog({
			'mode' : variant,
			'CID' : params['CID'],
			'id' : params['id'],
			'upload_path' : params['upload_path'],
			'multiple' : params['multiple'],
			'controller':  params['controller'],
			'inputName' : params['inputName'],
			'fileInput' :  ("file-fileUploader-" + params['uid']),
			'fileInputName' : "mfi_files[]",
			'values' : BX.findChildren(BX('file-selectdialog-' + params['uid']), {"className" : "file-inline-file"}, true),
			'msg' : {
				'loading' : BX.message('loading'),
				'file_exists' : BX.message('file_exists'),
				'upload_error' : BX.message('upload_error'),
				'access_denied' : BX.message('access_denied')
			}
		});
		showControl(status);
		window['BfileFD' + params['uid']].LoadDialogs('DropInterface');
		BX.onCustomEvent('BFileDSelectFileDialogLoaded', [window['BfileFD' + params['uid']]]);
	}
	else
		showControl(status);
}
window.BlogBFileJustDialog = function(arParams)
{
	this.dialogName = 'AttachmentsDialog';
	this.agent = false;

	this.id = (!!arParams["id"] ? arParams["id"] : this.getID());
	this.controlID = arParams["id"];
	this.enabled = true;
	this.uploadFileUrl = arParams.upload_path; // from file.input php

	this.controller = (!! arParams.controller ) ? arParams.controller : null;
	this.CID = arParams.CID;

	arParams.caller = this;
	arParams.doc_prefix = 'wd-doc';
	arParams._mkFileInput = BX.DoNothing
	arParams.mode = 'extended';
	arParams.classes = {
		'tpl_simple' : 'file-simple',
		'tpl_extended' : 'file-extended'
	};

	this.doc_prefix = arParams.doc_prefix;

	if (!!BX.FileUploadAgent) {
		this.agent = new BX.FileUploadAgent(arParams);
		BX.addCustomEvent(this, 'StopUpload', BX.delegate(this.StopUpload, this));
		BX.onCustomEvent(BX(this.controller.parentNode), "BFileDLoadFormControllerInit", [this]);
	} else {
		BX.debug('/bitrix/components/bitrix/main.file.input/templates/drag_n_drop/script.js: BX.FileUploadAgent is not defined.' +
			' You need to load /bitrix/js/main/file_upload_agent.js');
	}
}
window.BlogBFileJustDialog.prototype.StopUpload = function(agent, parent)
{
	this.agent = agent;
	id = false;
	mID = parent.id.match(new RegExp(this.doc_prefix + '(\\d+)'));
	if (!!mID) {
		id = mID[1];
	}

	if (this.controller && this.controller.parentNode)
		BX.onCustomEvent(this.controller.parentNode, 'OnFileUploadRemove', [id, this]);

	var data = {
		fileID : id,
		sessid : BX.bitrix_sessid(),
		cid : this.CID,
		controlID : this.controlID,
		mfi_mode : "delete"
	};
	BX.ajax.post(this.uploadFileUrl, data);
}


window.MFIS = function(params)
{
	if (! params['controller'].loaded)
	{
		params['controller'].loaded = true;
		top['BfileFD' + params['uid']] = window['BfileFD' + params['uid']] = new BlogBFileJustDialog({
			'CID' : params['CID'],
			'id' : params['id'],
			'upload_path' : params['upload_path'],
			'controller':  params['controller'],
			'values' : BX.findChildren(BX('file-selectdialog-' + params['uid']), {"className" : "file-inline-file"}, true)
		});
		BX.fx.show(params['controller'], 'fade', {time:0.2});
		BX.onCustomEvent('BFileDSelectFileDialogLoaded', [window['BfileFD' + params['uid']]]);
	}
}

})(window);

/* End */
;
; /* Start:"a:4:{s:4:"full";s:61:"/local/templates/us_2018/js/jquery.mask.min.js?15723419818185";s:6:"source";s:46:"/local/templates/us_2018/js/jquery.mask.min.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
// jQuery Mask Plugin v1.14.15
// github.com/igorescobar/jQuery-Mask-Plugin
var $jscomp={scope:{},findInternal:function(a,l,d){a instanceof String&&(a=String(a));for(var p=a.length,h=0;h<p;h++){var b=a[h];if(l.call(d,b,h,a))return{i:h,v:b}}return{i:-1,v:void 0}}};$jscomp.defineProperty="function"==typeof Object.defineProperties?Object.defineProperty:function(a,l,d){if(d.get||d.set)throw new TypeError("ES3 does not support getters and setters.");a!=Array.prototype&&a!=Object.prototype&&(a[l]=d.value)};
$jscomp.getGlobal=function(a){return"undefined"!=typeof window&&window===a?a:"undefined"!=typeof global&&null!=global?global:a};$jscomp.global=$jscomp.getGlobal(this);$jscomp.polyfill=function(a,l,d,p){if(l){d=$jscomp.global;a=a.split(".");for(p=0;p<a.length-1;p++){var h=a[p];h in d||(d[h]={});d=d[h]}a=a[a.length-1];p=d[a];l=l(p);l!=p&&null!=l&&$jscomp.defineProperty(d,a,{configurable:!0,writable:!0,value:l})}};
$jscomp.polyfill("Array.prototype.find",function(a){return a?a:function(a,d){return $jscomp.findInternal(this,a,d).v}},"es6-impl","es3");
(function(a,l,d){"function"===typeof define&&define.amd?define(["jquery"],a):"object"===typeof exports?module.exports=a(require("jquery")):a(l||d)})(function(a){var l=function(b,e,f){var c={invalid:[],getCaret:function(){try{var a,r=0,g=b.get(0),e=document.selection,f=g.selectionStart;if(e&&-1===navigator.appVersion.indexOf("MSIE 10"))a=e.createRange(),a.moveStart("character",-c.val().length),r=a.text.length;else if(f||"0"===f)r=f;return r}catch(C){}},setCaret:function(a){try{if(b.is(":focus")){var c,
g=b.get(0);g.setSelectionRange?g.setSelectionRange(a,a):(c=g.createTextRange(),c.collapse(!0),c.moveEnd("character",a),c.moveStart("character",a),c.select())}}catch(B){}},events:function(){b.on("keydown.mask",function(a){b.data("mask-keycode",a.keyCode||a.which);b.data("mask-previus-value",b.val());b.data("mask-previus-caret-pos",c.getCaret());c.maskDigitPosMapOld=c.maskDigitPosMap}).on(a.jMaskGlobals.useInput?"input.mask":"keyup.mask",c.behaviour).on("paste.mask drop.mask",function(){setTimeout(function(){b.keydown().keyup()},
100)}).on("change.mask",function(){b.data("changed",!0)}).on("blur.mask",function(){d===c.val()||b.data("changed")||b.trigger("change");b.data("changed",!1)}).on("blur.mask",function(){d=c.val()}).on("focus.mask",function(b){!0===f.selectOnFocus&&a(b.target).select()}).on("focusout.mask",function(){f.clearIfNotMatch&&!h.test(c.val())&&c.val("")})},getRegexMask:function(){for(var a=[],b,c,f,n,d=0;d<e.length;d++)(b=m.translation[e.charAt(d)])?(c=b.pattern.toString().replace(/.{1}$|^.{1}/g,""),f=b.optional,
(b=b.recursive)?(a.push(e.charAt(d)),n={digit:e.charAt(d),pattern:c}):a.push(f||b?c+"?":c)):a.push(e.charAt(d).replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&"));a=a.join("");n&&(a=a.replace(new RegExp("("+n.digit+"(.*"+n.digit+")?)"),"($1)?").replace(new RegExp(n.digit,"g"),n.pattern));return new RegExp(a)},destroyEvents:function(){b.off("input keydown keyup paste drop blur focusout ".split(" ").join(".mask "))},val:function(a){var c=b.is("input")?"val":"text";if(0<arguments.length){if(b[c]()!==a)b[c](a);
c=b}else c=b[c]();return c},calculateCaretPosition:function(){var a=b.data("mask-previus-value")||"",e=c.getMasked(),g=c.getCaret();if(a!==e){var f=b.data("mask-previus-caret-pos")||0,e=e.length,d=a.length,m=a=0,h=0,l=0,k;for(k=g;k<e&&c.maskDigitPosMap[k];k++)m++;for(k=g-1;0<=k&&c.maskDigitPosMap[k];k--)a++;for(k=g-1;0<=k;k--)c.maskDigitPosMap[k]&&h++;for(k=f-1;0<=k;k--)c.maskDigitPosMapOld[k]&&l++;g>d?g=10*e:f>=g&&f!==d?c.maskDigitPosMapOld[g]||(f=g,g=g-(l-h)-a,c.maskDigitPosMap[g]&&(g=f)):g>f&&
(g=g+(h-l)+m)}return g},behaviour:function(f){f=f||window.event;c.invalid=[];var e=b.data("mask-keycode");if(-1===a.inArray(e,m.byPassKeys)){var e=c.getMasked(),g=c.getCaret();setTimeout(function(){c.setCaret(c.calculateCaretPosition())},a.jMaskGlobals.keyStrokeCompensation);c.val(e);c.setCaret(g);return c.callbacks(f)}},getMasked:function(a,b){var g=[],d=void 0===b?c.val():b+"",n=0,h=e.length,q=0,l=d.length,k=1,r="push",p=-1,t=0,y=[],v,z;f.reverse?(r="unshift",k=-1,v=0,n=h-1,q=l-1,z=function(){return-1<
n&&-1<q}):(v=h-1,z=function(){return n<h&&q<l});for(var A;z();){var x=e.charAt(n),w=d.charAt(q),u=m.translation[x];if(u)w.match(u.pattern)?(g[r](w),u.recursive&&(-1===p?p=n:n===v&&n!==p&&(n=p-k),v===p&&(n-=k)),n+=k):w===A?(t--,A=void 0):u.optional?(n+=k,q-=k):u.fallback?(g[r](u.fallback),n+=k,q-=k):c.invalid.push({p:q,v:w,e:u.pattern}),q+=k;else{if(!a)g[r](x);w===x?(y.push(q),q+=k):(A=x,y.push(q+t),t++);n+=k}}d=e.charAt(v);h!==l+1||m.translation[d]||g.push(d);g=g.join("");c.mapMaskdigitPositions(g,
y,l);return g},mapMaskdigitPositions:function(a,b,e){a=f.reverse?a.length-e:0;c.maskDigitPosMap={};for(e=0;e<b.length;e++)c.maskDigitPosMap[b[e]+a]=1},callbacks:function(a){var h=c.val(),g=h!==d,m=[h,a,b,f],q=function(a,b,c){"function"===typeof f[a]&&b&&f[a].apply(this,c)};q("onChange",!0===g,m);q("onKeyPress",!0===g,m);q("onComplete",h.length===e.length,m);q("onInvalid",0<c.invalid.length,[h,a,b,c.invalid,f])}};b=a(b);var m=this,d=c.val(),h;e="function"===typeof e?e(c.val(),void 0,b,f):e;m.mask=
e;m.options=f;m.remove=function(){var a=c.getCaret();m.options.placeholder&&b.removeAttr("placeholder");b.data("mask-maxlength")&&b.removeAttr("maxlength");c.destroyEvents();c.val(m.getCleanVal());c.setCaret(a);return b};m.getCleanVal=function(){return c.getMasked(!0)};m.getMaskedVal=function(a){return c.getMasked(!1,a)};m.init=function(d){d=d||!1;f=f||{};m.clearIfNotMatch=a.jMaskGlobals.clearIfNotMatch;m.byPassKeys=a.jMaskGlobals.byPassKeys;m.translation=a.extend({},a.jMaskGlobals.translation,f.translation);
m=a.extend(!0,{},m,f);h=c.getRegexMask();if(d)c.events(),c.val(c.getMasked());else{f.placeholder&&b.attr("placeholder",f.placeholder);b.data("mask")&&b.attr("autocomplete","off");d=0;for(var l=!0;d<e.length;d++){var g=m.translation[e.charAt(d)];if(g&&g.recursive){l=!1;break}}l&&b.attr("maxlength",e.length).data("mask-maxlength",!0);c.destroyEvents();c.events();d=c.getCaret();c.val(c.getMasked());c.setCaret(d)}};m.init(!b.is("input"))};a.maskWatchers={};var d=function(){var b=a(this),e={},f=b.attr("data-mask");
b.attr("data-mask-reverse")&&(e.reverse=!0);b.attr("data-mask-clearifnotmatch")&&(e.clearIfNotMatch=!0);"true"===b.attr("data-mask-selectonfocus")&&(e.selectOnFocus=!0);if(p(b,f,e))return b.data("mask",new l(this,f,e))},p=function(b,e,f){f=f||{};var c=a(b).data("mask"),d=JSON.stringify;b=a(b).val()||a(b).text();try{return"function"===typeof e&&(e=e(b)),"object"!==typeof c||d(c.options)!==d(f)||c.mask!==e}catch(t){}},h=function(a){var b=document.createElement("div"),d;a="on"+a;d=a in b;d||(b.setAttribute(a,
"return;"),d="function"===typeof b[a]);return d};a.fn.mask=function(b,d){d=d||{};var e=this.selector,c=a.jMaskGlobals,h=c.watchInterval,c=d.watchInputs||c.watchInputs,t=function(){if(p(this,b,d))return a(this).data("mask",new l(this,b,d))};a(this).each(t);e&&""!==e&&c&&(clearInterval(a.maskWatchers[e]),a.maskWatchers[e]=setInterval(function(){a(document).find(e).each(t)},h));return this};a.fn.masked=function(a){return this.data("mask").getMaskedVal(a)};a.fn.unmask=function(){clearInterval(a.maskWatchers[this.selector]);
delete a.maskWatchers[this.selector];return this.each(function(){var b=a(this).data("mask");b&&b.remove().removeData("mask")})};a.fn.cleanVal=function(){return this.data("mask").getCleanVal()};a.applyDataMask=function(b){b=b||a.jMaskGlobals.maskElements;(b instanceof a?b:a(b)).filter(a.jMaskGlobals.dataMaskAttr).each(d)};h={maskElements:"input,td,span,div",dataMaskAttr:"*[data-mask]",dataMask:!0,watchInterval:300,watchInputs:!0,keyStrokeCompensation:10,useInput:!/Chrome\/[2-4][0-9]|SamsungBrowser/.test(window.navigator.userAgent)&&
h("input"),watchDataMask:!1,byPassKeys:[9,16,17,18,36,37,38,39,40,91],translation:{0:{pattern:/\d/},9:{pattern:/\d/,optional:!0},"#":{pattern:/\d/,recursive:!0},A:{pattern:/[a-zA-Z0-9]/},S:{pattern:/[a-zA-Z]/}}};a.jMaskGlobals=a.jMaskGlobals||{};h=a.jMaskGlobals=a.extend(!0,{},h,a.jMaskGlobals);h.dataMask&&a.applyDataMask();setInterval(function(){a.jMaskGlobals.watchDataMask&&a.applyDataMask()},h.watchInterval)},window.jQuery,window.Zepto);

/* End */
;
; /* Start:"a:4:{s:4:"full";s:90:"/bitrix/components/bitrix/catalog.store.amount/templates/.default/script.js?15723421041401";s:6:"source";s:75:"/bitrix/components/bitrix/catalog.store.amount/templates/.default/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
window.JCCatalogStoreSKU = function(params)
{
	var i;

	if(!params)
		return;

	this.config = {
		'id' : params.ID,
		'showEmptyStore'	: params.SHOW_EMPTY_STORE,
		'useMinAmount'		: params.USE_MIN_AMOUNT,
		'minAmount'			: params.MIN_AMOUNT
	};

	this.messages = params.MESSAGES;
	this.sku = params.SKU;
	this.stores = params.STORES;
	this.obStores = {};
	for (i in this.stores)
		this.obStores[this.stores[i]] = BX(this.config.id+"_"+this.stores[i]);

	BX.addCustomEvent(window, "onCatalogStoreProductChange", BX.proxy(this.offerOnChange, this));
};

window.JCCatalogStoreSKU.prototype.offerOnChange = function(id)
{
	var curSku = this.sku[id],
		k,
		message,
		parent;

	for (k in this.obStores)
	{
		message = (!!this.config.useMinAmount) ? this.getStringCount(0) : '0';
		BX.adjust(this.obStores[k], {html: message});
		if (!!curSku[k])
		{
			message = (!!this.config.useMinAmount) ? this.getStringCount(curSku[k]) : curSku[k];
			BX.adjust(this.obStores[k],  {html: message});
		}
		parent = BX.findParent(this.obStores[k], {tagName: 'li'});
		if (!!this.config.showEmptyStore || curSku[k] > 0)
			BX.show(parent);
		else
			BX.hide(parent);
	}
};

window.JCCatalogStoreSKU.prototype.getStringCount = function(num)
{
	if (num == 0)
		return this.messages['ABSENT'];
	else if (num >= this.config.minAmount)
		return this.messages['LOT_OF_GOOD'];
	else
		return this.messages['NOT_MUCH_GOOD'];
};
/* End */
;
; /* Start:"a:4:{s:4:"full";s:91:"/local/templates/us_2018/components/bitrix/catalog.section/popular/script.js?15723419817950";s:6:"source";s:76:"/local/templates/us_2018/components/bitrix/catalog.section/popular/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
(function() {
	'use strict';

	if (!!window.JCCatalogSectionComponent)
		return;

	window.JCCatalogSectionComponent = function(params) {
		this.formPosting = false;
		this.siteId = params.siteId || '';
		this.ajaxId = params.ajaxId || '';
		this.template = params.template || '';
		this.componentPath = params.componentPath || '';
		this.parameters = params.parameters || '';

		if (params.navParams)
		{
			this.navParams = {
				NavNum: params.navParams.NavNum || 1,
				NavPageNomer: parseInt(params.navParams.NavPageNomer) || 1,
				NavPageCount: parseInt(params.navParams.NavPageCount) || 1
			};
		}

		this.bigData = params.bigData || {enabled: false};
		this.container = document.querySelector('[data-entity="' + params.container + '"]');
		this.showMoreButton = null;
		this.showMoreButtonMessage = null;

		if (this.bigData.enabled && BX.util.object_keys(this.bigData.rows).length > 0)
		{
			BX.cookie_prefix = this.bigData.js.cookiePrefix || '';
			BX.cookie_domain = this.bigData.js.cookieDomain || '';
			BX.current_server_time = this.bigData.js.serverTime;

			BX.ready(BX.delegate(this.bigDataLoad, this));
		}

		if (params.initiallyShowHeader)
		{
			BX.ready(BX.delegate(this.showHeader, this));
		}

		if (params.deferredLoad)
		{
			BX.ready(BX.delegate(this.deferredLoad, this));
		}

		if (params.lazyLoad)
		{
			this.showMoreButton = document.querySelector('[data-use="show-more-' + this.navParams.NavNum + '"]');
			this.showMoreButtonMessage = this.showMoreButton.innerHTML;
			BX.bind(this.showMoreButton, 'click', BX.proxy(this.showMore, this));
		}

		if (params.loadOnScroll)
		{
			BX.bind(window, 'scroll', BX.proxy(this.loadOnScroll, this));
		}
	};

	window.JCCatalogSectionComponent.prototype =
	{
		checkButton: function()
		{
			if (this.showMoreButton)
			{
				if (this.navParams.NavPageNomer == this.navParams.NavPageCount)
				{
					BX.remove(this.showMoreButton);
				}
				else
				{
					this.container.appendChild(this.showMoreButton);
				}
			}
		},

		enableButton: function()
		{
			if (this.showMoreButton)
			{
				BX.removeClass(this.showMoreButton, 'disabled');
				this.showMoreButton.innerHTML = this.showMoreButtonMessage;
			}
		},

		disableButton: function()
		{
			if (this.showMoreButton)
			{
				BX.addClass(this.showMoreButton, 'disabled');
				this.showMoreButton.innerHTML = BX.message('BTN_MESSAGE_LAZY_LOAD_WAITER');
			}
		},

		loadOnScroll: function()
		{
			var scrollTop = BX.GetWindowScrollPos().scrollTop,
				containerBottom = BX.pos(this.container).bottom;

			if (scrollTop + window.innerHeight > containerBottom)
			{
				this.showMore();
			}
		},

		showMore: function()
		{
			if (this.navParams.NavPageNomer < this.navParams.NavPageCount)
			{
				var data = {};
				data['action'] = 'showMore';
				data['PAGEN_' + this.navParams.NavNum] = this.navParams.NavPageNomer + 1;

				if (!this.formPosting)
				{
					this.formPosting = true;
					this.disableButton();
					this.sendRequest(data);
				}
			}
		},

		bigDataLoad: function()
		{
			var url = 'https://analytics.bitrix.info/crecoms/v1_0/recoms.php',
				data = BX.ajax.prepareData(this.bigData.params);

			if (data)
			{
				url += (url.indexOf('?') !== -1 ? '&' : '?') + data;
			}

			var onReady = BX.delegate(function(result){
				this.sendRequest({
					action: 'deferredLoad',
					bigData: 'Y',
					items: result && result.items || [],
					rid: result && result.id,
					count: this.bigData.count,
					rowsRange: this.bigData.rowsRange,
					shownIds: this.bigData.shownIds
				});
			}, this);

			BX.ajax({
				method: 'GET',
				dataType: 'json',
				url: url,
				timeout: 3,
				onsuccess: onReady,
				onfailure: onReady
			});
		},

		deferredLoad: function()
		{
			this.sendRequest({action: 'deferredLoad'});
		},

		sendRequest: function(data)
		{
			var defaultData = {
				siteId: this.siteId,
				template: this.template,
				parameters: this.parameters
			};

			if (this.ajaxId)
			{
				defaultData.AJAX_ID = this.ajaxId;
			}

			BX.ajax({
				url: this.componentPath + '/ajax.php' + (document.location.href.indexOf('clear_cache=Y') !== -1 ? '?clear_cache=Y' : ''),
				method: 'POST',
				dataType: 'json',
				timeout: 60,
				data: BX.merge(defaultData, data),
				onsuccess: BX.delegate(function(result){
					if (!result || !result.JS)
						return;

					BX.ajax.processScripts(
						BX.processHTML(result.JS).SCRIPT,
						false,
						BX.delegate(function(){this.showAction(result, data);}, this)
					);
				}, this)
			});
		},

		showAction: function(result, data)
		{
			if (!data)
				return;

			switch (data.action)
			{
				case 'showMore':
					this.processShowMoreAction(result);
					break;
				case 'deferredLoad':
					this.processDeferredLoadAction(result, data.bigData === 'Y');
					break;
			}
		},

		processShowMoreAction: function(result)
		{
			this.formPosting = false;
			this.enableButton();

			if (result)
			{
				this.navParams.NavPageNomer++;
				this.processItems(result.items);
				this.processPagination(result.pagination);
				this.checkButton();
			}
		},

		processDeferredLoadAction: function(result, bigData)
		{
			if (!result)
				return;

			var position = bigData ? this.bigData.rows : {};

			this.processItems(result.items, BX.util.array_keys(position));
		},

		processItems: function(itemsHtml, position)
		{
			if (!itemsHtml)
				return;

			var processed = BX.processHTML(itemsHtml, false),
				temporaryNode = BX.create('DIV');

			var items, k, origRows;

			temporaryNode.innerHTML = processed.HTML;
			items = temporaryNode.querySelectorAll('[data-entity="items-row"]');

			if (items.length)
			{
				this.showHeader(true);

				for (k in items)
				{
					if (items.hasOwnProperty(k))
					{
						origRows = position ? this.container.querySelectorAll('[data-entity="items-row"]') : false;
						items[k].style.opacity = 0;

						if (origRows && BX.type.isDomNode(origRows[position[k]]))
						{
							origRows[position[k]].parentNode.insertBefore(items[k], origRows[position[k]]);
						}
						else
						{
							this.container.appendChild(items[k]);
						}
					}
				}

				new BX.easing({
					duration: 2000,
					start: {opacity: 0},
					finish: {opacity: 100},
					transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
					step: function(state){
						for (var k in items)
						{
							if (items.hasOwnProperty(k))
							{
								items[k].style.opacity = state.opacity / 100;
							}
						}
					},
					complete: function(){
						for (var k in items)
						{
							if (items.hasOwnProperty(k))
							{
								items[k].removeAttribute('style');
							}
						}
					}
				}).animate();
			}

			BX.ajax.processScripts(processed.SCRIPT);
		},

		processPagination: function(paginationHtml)
		{
			if (!paginationHtml)
				return;

			var pagination = document.querySelectorAll('[data-pagination-num="' + this.navParams.NavNum + '"]');
			for (var k in pagination)
			{
				if (pagination.hasOwnProperty(k))
				{
					pagination[k].innerHTML = paginationHtml;
				}
			}
		},

		showHeader: function(animate)
		{
			var parentNode = BX.findParent(this.container, {attr: {'data-entity': 'parent-container'}}),
				header;

			if (parentNode && BX.type.isDomNode(parentNode))
			{
				header = parentNode.querySelector('[data-entity="header"]');

				if (header && header.getAttribute('data-showed') != 'true')
				{
					header.style.display = '';

					if (animate)
					{
						new BX.easing({
							duration: 2000,
							start: {opacity: 0},
							finish: {opacity: 100},
							transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
							step: function(state){
								header.style.opacity = state.opacity / 100;
							},
							complete: function(){
								header.removeAttribute('style');
								header.setAttribute('data-showed', 'true');
							}
						}).animate();
					}
					else
					{
						header.style.opacity = 100;
					}
				}
			}
		}
	};
})();
/* End */
;
; /* Start:"a:4:{s:4:"full";s:73:"/local/templates/us_2018/owl.carousel/owl.carousel.min.js?157234198144342";s:6:"source";s:53:"/local/templates/us_2018/owl.carousel/owl.carousel.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
/**
 * Owl Carousel v2.3.4
 * Copyright 2013-2018 David Deutsch
 * Licensed under: SEE LICENSE IN https://github.com/OwlCarousel2/OwlCarousel2/blob/master/LICENSE
 */
!function(a,b,c,d){function e(b,c){this.settings=null,this.options=a.extend({},e.Defaults,c),this.$element=a(b),this._handlers={},this._plugins={},this._supress={},this._current=null,this._speed=null,this._coordinates=[],this._breakpoint=null,this._width=null,this._items=[],this._clones=[],this._mergers=[],this._widths=[],this._invalidated={},this._pipe=[],this._drag={time:null,target:null,pointer:null,stage:{start:null,current:null},direction:null},this._states={current:{},tags:{initializing:["busy"],animating:["busy"],dragging:["interacting"]}},a.each(["onResize","onThrottledResize"],a.proxy(function(b,c){this._handlers[c]=a.proxy(this[c],this)},this)),a.each(e.Plugins,a.proxy(function(a,b){this._plugins[a.charAt(0).toLowerCase()+a.slice(1)]=new b(this)},this)),a.each(e.Workers,a.proxy(function(b,c){this._pipe.push({filter:c.filter,run:a.proxy(c.run,this)})},this)),this.setup(),this.initialize()}e.Defaults={items:3,loop:!1,center:!1,rewind:!1,checkVisibility:!0,mouseDrag:!0,touchDrag:!0,pullDrag:!0,freeDrag:!1,margin:0,stagePadding:0,merge:!1,mergeFit:!0,autoWidth:!1,startPosition:0,rtl:!1,smartSpeed:250,fluidSpeed:!1,dragEndSpeed:!1,responsive:{},responsiveRefreshRate:200,responsiveBaseElement:b,fallbackEasing:"swing",slideTransition:"",info:!1,nestedItemSelector:!1,itemElement:"div",stageElement:"div",refreshClass:"owl-refresh",loadedClass:"owl-loaded",loadingClass:"owl-loading",rtlClass:"owl-rtl",responsiveClass:"owl-responsive",dragClass:"owl-drag",itemClass:"owl-item",stageClass:"owl-stage",stageOuterClass:"owl-stage-outer",grabClass:"owl-grab"},e.Width={Default:"default",Inner:"inner",Outer:"outer"},e.Type={Event:"event",State:"state"},e.Plugins={},e.Workers=[{filter:["width","settings"],run:function(){this._width=this.$element.width()}},{filter:["width","items","settings"],run:function(a){a.current=this._items&&this._items[this.relative(this._current)]}},{filter:["items","settings"],run:function(){this.$stage.children(".cloned").remove()}},{filter:["width","items","settings"],run:function(a){var b=this.settings.margin||"",c=!this.settings.autoWidth,d=this.settings.rtl,e={width:"auto","margin-left":d?b:"","margin-right":d?"":b};!c&&this.$stage.children().css(e),a.css=e}},{filter:["width","items","settings"],run:function(a){var b=(this.width()/this.settings.items).toFixed(3)-this.settings.margin,c=null,d=this._items.length,e=!this.settings.autoWidth,f=[];for(a.items={merge:!1,width:b};d--;)c=this._mergers[d],c=this.settings.mergeFit&&Math.min(c,this.settings.items)||c,a.items.merge=c>1||a.items.merge,f[d]=e?b*c:this._items[d].width();this._widths=f}},{filter:["items","settings"],run:function(){var b=[],c=this._items,d=this.settings,e=Math.max(2*d.items,4),f=2*Math.ceil(c.length/2),g=d.loop&&c.length?d.rewind?e:Math.max(e,f):0,h="",i="";for(g/=2;g>0;)b.push(this.normalize(b.length/2,!0)),h+=c[b[b.length-1]][0].outerHTML,b.push(this.normalize(c.length-1-(b.length-1)/2,!0)),i=c[b[b.length-1]][0].outerHTML+i,g-=1;this._clones=b,a(h).addClass("cloned").appendTo(this.$stage),a(i).addClass("cloned").prependTo(this.$stage)}},{filter:["width","items","settings"],run:function(){for(var a=this.settings.rtl?1:-1,b=this._clones.length+this._items.length,c=-1,d=0,e=0,f=[];++c<b;)d=f[c-1]||0,e=this._widths[this.relative(c)]+this.settings.margin,f.push(d+e*a);this._coordinates=f}},{filter:["width","items","settings"],run:function(){var a=this.settings.stagePadding,b=this._coordinates,c={width:Math.ceil(Math.abs(b[b.length-1]))+2*a,"padding-left":a||"","padding-right":a||""};this.$stage.css(c)}},{filter:["width","items","settings"],run:function(a){var b=this._coordinates.length,c=!this.settings.autoWidth,d=this.$stage.children();if(c&&a.items.merge)for(;b--;)a.css.width=this._widths[this.relative(b)],d.eq(b).css(a.css);else c&&(a.css.width=a.items.width,d.css(a.css))}},{filter:["items"],run:function(){this._coordinates.length<1&&this.$stage.removeAttr("style")}},{filter:["width","items","settings"],run:function(a){a.current=a.current?this.$stage.children().index(a.current):0,a.current=Math.max(this.minimum(),Math.min(this.maximum(),a.current)),this.reset(a.current)}},{filter:["position"],run:function(){this.animate(this.coordinates(this._current))}},{filter:["width","position","items","settings"],run:function(){var a,b,c,d,e=this.settings.rtl?1:-1,f=2*this.settings.stagePadding,g=this.coordinates(this.current())+f,h=g+this.width()*e,i=[];for(c=0,d=this._coordinates.length;c<d;c++)a=this._coordinates[c-1]||0,b=Math.abs(this._coordinates[c])+f*e,(this.op(a,"<=",g)&&this.op(a,">",h)||this.op(b,"<",g)&&this.op(b,">",h))&&i.push(c);this.$stage.children(".active").removeClass("active"),this.$stage.children(":eq("+i.join("), :eq(")+")").addClass("active"),this.$stage.children(".center").removeClass("center"),this.settings.center&&this.$stage.children().eq(this.current()).addClass("center")}}],e.prototype.initializeStage=function(){this.$stage=this.$element.find("."+this.settings.stageClass),this.$stage.length||(this.$element.addClass(this.options.loadingClass),this.$stage=a("<"+this.settings.stageElement+">",{class:this.settings.stageClass}).wrap(a("<div/>",{class:this.settings.stageOuterClass})),this.$element.append(this.$stage.parent()))},e.prototype.initializeItems=function(){var b=this.$element.find(".owl-item");if(b.length)return this._items=b.get().map(function(b){return a(b)}),this._mergers=this._items.map(function(){return 1}),void this.refresh();this.replace(this.$element.children().not(this.$stage.parent())),this.isVisible()?this.refresh():this.invalidate("width"),this.$element.removeClass(this.options.loadingClass).addClass(this.options.loadedClass)},e.prototype.initialize=function(){if(this.enter("initializing"),this.trigger("initialize"),this.$element.toggleClass(this.settings.rtlClass,this.settings.rtl),this.settings.autoWidth&&!this.is("pre-loading")){var a,b,c;a=this.$element.find("img"),b=this.settings.nestedItemSelector?"."+this.settings.nestedItemSelector:d,c=this.$element.children(b).width(),a.length&&c<=0&&this.preloadAutoWidthImages(a)}this.initializeStage(),this.initializeItems(),this.registerEventHandlers(),this.leave("initializing"),this.trigger("initialized")},e.prototype.isVisible=function(){return!this.settings.checkVisibility||this.$element.is(":visible")},e.prototype.setup=function(){var b=this.viewport(),c=this.options.responsive,d=-1,e=null;c?(a.each(c,function(a){a<=b&&a>d&&(d=Number(a))}),e=a.extend({},this.options,c[d]),"function"==typeof e.stagePadding&&(e.stagePadding=e.stagePadding()),delete e.responsive,e.responsiveClass&&this.$element.attr("class",this.$element.attr("class").replace(new RegExp("("+this.options.responsiveClass+"-)\\S+\\s","g"),"$1"+d))):e=a.extend({},this.options),this.trigger("change",{property:{name:"settings",value:e}}),this._breakpoint=d,this.settings=e,this.invalidate("settings"),this.trigger("changed",{property:{name:"settings",value:this.settings}})},e.prototype.optionsLogic=function(){this.settings.autoWidth&&(this.settings.stagePadding=!1,this.settings.merge=!1)},e.prototype.prepare=function(b){var c=this.trigger("prepare",{content:b});return c.data||(c.data=a("<"+this.settings.itemElement+"/>").addClass(this.options.itemClass).append(b)),this.trigger("prepared",{content:c.data}),c.data},e.prototype.update=function(){for(var b=0,c=this._pipe.length,d=a.proxy(function(a){return this[a]},this._invalidated),e={};b<c;)(this._invalidated.all||a.grep(this._pipe[b].filter,d).length>0)&&this._pipe[b].run(e),b++;this._invalidated={},!this.is("valid")&&this.enter("valid")},e.prototype.width=function(a){switch(a=a||e.Width.Default){case e.Width.Inner:case e.Width.Outer:return this._width;default:return this._width-2*this.settings.stagePadding+this.settings.margin}},e.prototype.refresh=function(){this.enter("refreshing"),this.trigger("refresh"),this.setup(),this.optionsLogic(),this.$element.addClass(this.options.refreshClass),this.update(),this.$element.removeClass(this.options.refreshClass),this.leave("refreshing"),this.trigger("refreshed")},e.prototype.onThrottledResize=function(){b.clearTimeout(this.resizeTimer),this.resizeTimer=b.setTimeout(this._handlers.onResize,this.settings.responsiveRefreshRate)},e.prototype.onResize=function(){return!!this._items.length&&(this._width!==this.$element.width()&&(!!this.isVisible()&&(this.enter("resizing"),this.trigger("resize").isDefaultPrevented()?(this.leave("resizing"),!1):(this.invalidate("width"),this.refresh(),this.leave("resizing"),void this.trigger("resized")))))},e.prototype.registerEventHandlers=function(){a.support.transition&&this.$stage.on(a.support.transition.end+".owl.core",a.proxy(this.onTransitionEnd,this)),!1!==this.settings.responsive&&this.on(b,"resize",this._handlers.onThrottledResize),this.settings.mouseDrag&&(this.$element.addClass(this.options.dragClass),this.$stage.on("mousedown.owl.core",a.proxy(this.onDragStart,this)),this.$stage.on("dragstart.owl.core selectstart.owl.core",function(){return!1})),this.settings.touchDrag&&(this.$stage.on("touchstart.owl.core",a.proxy(this.onDragStart,this)),this.$stage.on("touchcancel.owl.core",a.proxy(this.onDragEnd,this)))},e.prototype.onDragStart=function(b){var d=null;3!==b.which&&(a.support.transform?(d=this.$stage.css("transform").replace(/.*\(|\)| /g,"").split(","),d={x:d[16===d.length?12:4],y:d[16===d.length?13:5]}):(d=this.$stage.position(),d={x:this.settings.rtl?d.left+this.$stage.width()-this.width()+this.settings.margin:d.left,y:d.top}),this.is("animating")&&(a.support.transform?this.animate(d.x):this.$stage.stop(),this.invalidate("position")),this.$element.toggleClass(this.options.grabClass,"mousedown"===b.type),this.speed(0),this._drag.time=(new Date).getTime(),this._drag.target=a(b.target),this._drag.stage.start=d,this._drag.stage.current=d,this._drag.pointer=this.pointer(b),a(c).on("mouseup.owl.core touchend.owl.core",a.proxy(this.onDragEnd,this)),a(c).one("mousemove.owl.core touchmove.owl.core",a.proxy(function(b){var d=this.difference(this._drag.pointer,this.pointer(b));a(c).on("mousemove.owl.core touchmove.owl.core",a.proxy(this.onDragMove,this)),Math.abs(d.x)<Math.abs(d.y)&&this.is("valid")||(b.preventDefault(),this.enter("dragging"),this.trigger("drag"))},this)))},e.prototype.onDragMove=function(a){var b=null,c=null,d=null,e=this.difference(this._drag.pointer,this.pointer(a)),f=this.difference(this._drag.stage.start,e);this.is("dragging")&&(a.preventDefault(),this.settings.loop?(b=this.coordinates(this.minimum()),c=this.coordinates(this.maximum()+1)-b,f.x=((f.x-b)%c+c)%c+b):(b=this.settings.rtl?this.coordinates(this.maximum()):this.coordinates(this.minimum()),c=this.settings.rtl?this.coordinates(this.minimum()):this.coordinates(this.maximum()),d=this.settings.pullDrag?-1*e.x/5:0,f.x=Math.max(Math.min(f.x,b+d),c+d)),this._drag.stage.current=f,this.animate(f.x))},e.prototype.onDragEnd=function(b){var d=this.difference(this._drag.pointer,this.pointer(b)),e=this._drag.stage.current,f=d.x>0^this.settings.rtl?"left":"right";a(c).off(".owl.core"),this.$element.removeClass(this.options.grabClass),(0!==d.x&&this.is("dragging")||!this.is("valid"))&&(this.speed(this.settings.dragEndSpeed||this.settings.smartSpeed),this.current(this.closest(e.x,0!==d.x?f:this._drag.direction)),this.invalidate("position"),this.update(),this._drag.direction=f,(Math.abs(d.x)>3||(new Date).getTime()-this._drag.time>300)&&this._drag.target.one("click.owl.core",function(){return!1})),this.is("dragging")&&(this.leave("dragging"),this.trigger("dragged"))},e.prototype.closest=function(b,c){var e=-1,f=30,g=this.width(),h=this.coordinates();return this.settings.freeDrag||a.each(h,a.proxy(function(a,i){return"left"===c&&b>i-f&&b<i+f?e=a:"right"===c&&b>i-g-f&&b<i-g+f?e=a+1:this.op(b,"<",i)&&this.op(b,">",h[a+1]!==d?h[a+1]:i-g)&&(e="left"===c?a+1:a),-1===e},this)),this.settings.loop||(this.op(b,">",h[this.minimum()])?e=b=this.minimum():this.op(b,"<",h[this.maximum()])&&(e=b=this.maximum())),e},e.prototype.animate=function(b){var c=this.speed()>0;this.is("animating")&&this.onTransitionEnd(),c&&(this.enter("animating"),this.trigger("translate")),a.support.transform3d&&a.support.transition?this.$stage.css({transform:"translate3d("+b+"px,0px,0px)",transition:this.speed()/1e3+"s"+(this.settings.slideTransition?" "+this.settings.slideTransition:"")}):c?this.$stage.animate({left:b+"px"},this.speed(),this.settings.fallbackEasing,a.proxy(this.onTransitionEnd,this)):this.$stage.css({left:b+"px"})},e.prototype.is=function(a){return this._states.current[a]&&this._states.current[a]>0},e.prototype.current=function(a){if(a===d)return this._current;if(0===this._items.length)return d;if(a=this.normalize(a),this._current!==a){var b=this.trigger("change",{property:{name:"position",value:a}});b.data!==d&&(a=this.normalize(b.data)),this._current=a,this.invalidate("position"),this.trigger("changed",{property:{name:"position",value:this._current}})}return this._current},e.prototype.invalidate=function(b){return"string"===a.type(b)&&(this._invalidated[b]=!0,this.is("valid")&&this.leave("valid")),a.map(this._invalidated,function(a,b){return b})},e.prototype.reset=function(a){(a=this.normalize(a))!==d&&(this._speed=0,this._current=a,this.suppress(["translate","translated"]),this.animate(this.coordinates(a)),this.release(["translate","translated"]))},e.prototype.normalize=function(a,b){var c=this._items.length,e=b?0:this._clones.length;return!this.isNumeric(a)||c<1?a=d:(a<0||a>=c+e)&&(a=((a-e/2)%c+c)%c+e/2),a},e.prototype.relative=function(a){return a-=this._clones.length/2,this.normalize(a,!0)},e.prototype.maximum=function(a){var b,c,d,e=this.settings,f=this._coordinates.length;if(e.loop)f=this._clones.length/2+this._items.length-1;else if(e.autoWidth||e.merge){if(b=this._items.length)for(c=this._items[--b].width(),d=this.$element.width();b--&&!((c+=this._items[b].width()+this.settings.margin)>d););f=b+1}else f=e.center?this._items.length-1:this._items.length-e.items;return a&&(f-=this._clones.length/2),Math.max(f,0)},e.prototype.minimum=function(a){return a?0:this._clones.length/2},e.prototype.items=function(a){return a===d?this._items.slice():(a=this.normalize(a,!0),this._items[a])},e.prototype.mergers=function(a){return a===d?this._mergers.slice():(a=this.normalize(a,!0),this._mergers[a])},e.prototype.clones=function(b){var c=this._clones.length/2,e=c+this._items.length,f=function(a){return a%2==0?e+a/2:c-(a+1)/2};return b===d?a.map(this._clones,function(a,b){return f(b)}):a.map(this._clones,function(a,c){return a===b?f(c):null})},e.prototype.speed=function(a){return a!==d&&(this._speed=a),this._speed},e.prototype.coordinates=function(b){var c,e=1,f=b-1;return b===d?a.map(this._coordinates,a.proxy(function(a,b){return this.coordinates(b)},this)):(this.settings.center?(this.settings.rtl&&(e=-1,f=b+1),c=this._coordinates[b],c+=(this.width()-c+(this._coordinates[f]||0))/2*e):c=this._coordinates[f]||0,c=Math.ceil(c))},e.prototype.duration=function(a,b,c){return 0===c?0:Math.min(Math.max(Math.abs(b-a),1),6)*Math.abs(c||this.settings.smartSpeed)},e.prototype.to=function(a,b){var c=this.current(),d=null,e=a-this.relative(c),f=(e>0)-(e<0),g=this._items.length,h=this.minimum(),i=this.maximum();this.settings.loop?(!this.settings.rewind&&Math.abs(e)>g/2&&(e+=-1*f*g),a=c+e,(d=((a-h)%g+g)%g+h)!==a&&d-e<=i&&d-e>0&&(c=d-e,a=d,this.reset(c))):this.settings.rewind?(i+=1,a=(a%i+i)%i):a=Math.max(h,Math.min(i,a)),this.speed(this.duration(c,a,b)),this.current(a),this.isVisible()&&this.update()},e.prototype.next=function(a){a=a||!1,this.to(this.relative(this.current())+1,a)},e.prototype.prev=function(a){a=a||!1,this.to(this.relative(this.current())-1,a)},e.prototype.onTransitionEnd=function(a){if(a!==d&&(a.stopPropagation(),(a.target||a.srcElement||a.originalTarget)!==this.$stage.get(0)))return!1;this.leave("animating"),this.trigger("translated")},e.prototype.viewport=function(){var d;return this.options.responsiveBaseElement!==b?d=a(this.options.responsiveBaseElement).width():b.innerWidth?d=b.innerWidth:c.documentElement&&c.documentElement.clientWidth?d=c.documentElement.clientWidth:console.warn("Can not detect viewport width."),d},e.prototype.replace=function(b){this.$stage.empty(),this._items=[],b&&(b=b instanceof jQuery?b:a(b)),this.settings.nestedItemSelector&&(b=b.find("."+this.settings.nestedItemSelector)),b.filter(function(){return 1===this.nodeType}).each(a.proxy(function(a,b){b=this.prepare(b),this.$stage.append(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)},this)),this.reset(this.isNumeric(this.settings.startPosition)?this.settings.startPosition:0),this.invalidate("items")},e.prototype.add=function(b,c){var e=this.relative(this._current);c=c===d?this._items.length:this.normalize(c,!0),b=b instanceof jQuery?b:a(b),this.trigger("add",{content:b,position:c}),b=this.prepare(b),0===this._items.length||c===this._items.length?(0===this._items.length&&this.$stage.append(b),0!==this._items.length&&this._items[c-1].after(b),this._items.push(b),this._mergers.push(1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)):(this._items[c].before(b),this._items.splice(c,0,b),this._mergers.splice(c,0,1*b.find("[data-merge]").addBack("[data-merge]").attr("data-merge")||1)),this._items[e]&&this.reset(this._items[e].index()),this.invalidate("items"),this.trigger("added",{content:b,position:c})},e.prototype.remove=function(a){(a=this.normalize(a,!0))!==d&&(this.trigger("remove",{content:this._items[a],position:a}),this._items[a].remove(),this._items.splice(a,1),this._mergers.splice(a,1),this.invalidate("items"),this.trigger("removed",{content:null,position:a}))},e.prototype.preloadAutoWidthImages=function(b){b.each(a.proxy(function(b,c){this.enter("pre-loading"),c=a(c),a(new Image).one("load",a.proxy(function(a){c.attr("src",a.target.src),c.css("opacity",1),this.leave("pre-loading"),!this.is("pre-loading")&&!this.is("initializing")&&this.refresh()},this)).attr("src",c.attr("src")||c.attr("data-src")||c.attr("data-src-retina"))},this))},e.prototype.destroy=function(){this.$element.off(".owl.core"),this.$stage.off(".owl.core"),a(c).off(".owl.core"),!1!==this.settings.responsive&&(b.clearTimeout(this.resizeTimer),this.off(b,"resize",this._handlers.onThrottledResize));for(var d in this._plugins)this._plugins[d].destroy();this.$stage.children(".cloned").remove(),this.$stage.unwrap(),this.$stage.children().contents().unwrap(),this.$stage.children().unwrap(),this.$stage.remove(),this.$element.removeClass(this.options.refreshClass).removeClass(this.options.loadingClass).removeClass(this.options.loadedClass).removeClass(this.options.rtlClass).removeClass(this.options.dragClass).removeClass(this.options.grabClass).attr("class",this.$element.attr("class").replace(new RegExp(this.options.responsiveClass+"-\\S+\\s","g"),"")).removeData("owl.carousel")},e.prototype.op=function(a,b,c){var d=this.settings.rtl;switch(b){case"<":return d?a>c:a<c;case">":return d?a<c:a>c;case">=":return d?a<=c:a>=c;case"<=":return d?a>=c:a<=c}},e.prototype.on=function(a,b,c,d){a.addEventListener?a.addEventListener(b,c,d):a.attachEvent&&a.attachEvent("on"+b,c)},e.prototype.off=function(a,b,c,d){a.removeEventListener?a.removeEventListener(b,c,d):a.detachEvent&&a.detachEvent("on"+b,c)},e.prototype.trigger=function(b,c,d,f,g){var h={item:{count:this._items.length,index:this.current()}},i=a.camelCase(a.grep(["on",b,d],function(a){return a}).join("-").toLowerCase()),j=a.Event([b,"owl",d||"carousel"].join(".").toLowerCase(),a.extend({relatedTarget:this},h,c));return this._supress[b]||(a.each(this._plugins,function(a,b){b.onTrigger&&b.onTrigger(j)}),this.register({type:e.Type.Event,name:b}),this.$element.trigger(j),this.settings&&"function"==typeof this.settings[i]&&this.settings[i].call(this,j)),j},e.prototype.enter=function(b){a.each([b].concat(this._states.tags[b]||[]),a.proxy(function(a,b){this._states.current[b]===d&&(this._states.current[b]=0),this._states.current[b]++},this))},e.prototype.leave=function(b){a.each([b].concat(this._states.tags[b]||[]),a.proxy(function(a,b){this._states.current[b]--},this))},e.prototype.register=function(b){if(b.type===e.Type.Event){if(a.event.special[b.name]||(a.event.special[b.name]={}),!a.event.special[b.name].owl){var c=a.event.special[b.name]._default;a.event.special[b.name]._default=function(a){return!c||!c.apply||a.namespace&&-1!==a.namespace.indexOf("owl")?a.namespace&&a.namespace.indexOf("owl")>-1:c.apply(this,arguments)},a.event.special[b.name].owl=!0}}else b.type===e.Type.State&&(this._states.tags[b.name]?this._states.tags[b.name]=this._states.tags[b.name].concat(b.tags):this._states.tags[b.name]=b.tags,this._states.tags[b.name]=a.grep(this._states.tags[b.name],a.proxy(function(c,d){return a.inArray(c,this._states.tags[b.name])===d},this)))},e.prototype.suppress=function(b){a.each(b,a.proxy(function(a,b){this._supress[b]=!0},this))},e.prototype.release=function(b){a.each(b,a.proxy(function(a,b){delete this._supress[b]},this))},e.prototype.pointer=function(a){var c={x:null,y:null};return a=a.originalEvent||a||b.event,a=a.touches&&a.touches.length?a.touches[0]:a.changedTouches&&a.changedTouches.length?a.changedTouches[0]:a,a.pageX?(c.x=a.pageX,c.y=a.pageY):(c.x=a.clientX,c.y=a.clientY),c},e.prototype.isNumeric=function(a){return!isNaN(parseFloat(a))},e.prototype.difference=function(a,b){return{x:a.x-b.x,y:a.y-b.y}},a.fn.owlCarousel=function(b){var c=Array.prototype.slice.call(arguments,1);return this.each(function(){var d=a(this),f=d.data("owl.carousel");f||(f=new e(this,"object"==typeof b&&b),d.data("owl.carousel",f),a.each(["next","prev","to","destroy","refresh","replace","add","remove"],function(b,c){f.register({type:e.Type.Event,name:c}),f.$element.on(c+".owl.carousel.core",a.proxy(function(a){a.namespace&&a.relatedTarget!==this&&(this.suppress([c]),f[c].apply(this,[].slice.call(arguments,1)),this.release([c]))},f))})),"string"==typeof b&&"_"!==b.charAt(0)&&f[b].apply(f,c)})},a.fn.owlCarousel.Constructor=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._interval=null,this._visible=null,this._handlers={"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoRefresh&&this.watch()},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers)};e.Defaults={autoRefresh:!0,autoRefreshInterval:500},e.prototype.watch=function(){this._interval||(this._visible=this._core.isVisible(),this._interval=b.setInterval(a.proxy(this.refresh,this),this._core.settings.autoRefreshInterval))},e.prototype.refresh=function(){this._core.isVisible()!==this._visible&&(this._visible=!this._visible,this._core.$element.toggleClass("owl-hidden",!this._visible),this._visible&&this._core.invalidate("width")&&this._core.refresh())},e.prototype.destroy=function(){var a,c;b.clearInterval(this._interval);for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoRefresh=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._loaded=[],this._handlers={"initialized.owl.carousel change.owl.carousel resized.owl.carousel":a.proxy(function(b){if(b.namespace&&this._core.settings&&this._core.settings.lazyLoad&&(b.property&&"position"==b.property.name||"initialized"==b.type)){var c=this._core.settings,e=c.center&&Math.ceil(c.items/2)||c.items,f=c.center&&-1*e||0,g=(b.property&&b.property.value!==d?b.property.value:this._core.current())+f,h=this._core.clones().length,i=a.proxy(function(a,b){this.load(b)},this);for(c.lazyLoadEager>0&&(e+=c.lazyLoadEager,c.loop&&(g-=c.lazyLoadEager,e++));f++<e;)this.load(h/2+this._core.relative(g)),h&&a.each(this._core.clones(this._core.relative(g)),i),g++}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers)};e.Defaults={lazyLoad:!1,lazyLoadEager:0},e.prototype.load=function(c){var d=this._core.$stage.children().eq(c),e=d&&d.find(".owl-lazy");!e||a.inArray(d.get(0),this._loaded)>-1||(e.each(a.proxy(function(c,d){var e,f=a(d),g=b.devicePixelRatio>1&&f.attr("data-src-retina")||f.attr("data-src")||f.attr("data-srcset");this._core.trigger("load",{element:f,url:g},"lazy"),f.is("img")?f.one("load.owl.lazy",a.proxy(function(){f.css("opacity",1),this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("src",g):f.is("source")?f.one("load.owl.lazy",a.proxy(function(){this._core.trigger("loaded",{element:f,url:g},"lazy")},this)).attr("srcset",g):(e=new Image,e.onload=a.proxy(function(){f.css({"background-image":'url("'+g+'")',opacity:"1"}),this._core.trigger("loaded",{element:f,url:g},"lazy")},this),e.src=g)},this)),this._loaded.push(d.get(0)))},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this._core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Lazy=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(c){this._core=c,this._previousHeight=null,this._handlers={"initialized.owl.carousel refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&this.update()},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&"position"===a.property.name&&this.update()},this),"loaded.owl.lazy":a.proxy(function(a){a.namespace&&this._core.settings.autoHeight&&a.element.closest("."+this._core.settings.itemClass).index()===this._core.current()&&this.update()},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers),this._intervalId=null;var d=this;a(b).on("load",function(){d._core.settings.autoHeight&&d.update()}),a(b).resize(function(){d._core.settings.autoHeight&&(null!=d._intervalId&&clearTimeout(d._intervalId),d._intervalId=setTimeout(function(){d.update()},250))})};e.Defaults={autoHeight:!1,autoHeightClass:"owl-height"},e.prototype.update=function(){var b=this._core._current,c=b+this._core.settings.items,d=this._core.settings.lazyLoad,e=this._core.$stage.children().toArray().slice(b,c),f=[],g=0;a.each(e,function(b,c){f.push(a(c).height())}),g=Math.max.apply(null,f),g<=1&&d&&this._previousHeight&&(g=this._previousHeight),this._previousHeight=g,this._core.$stage.parent().height(g).addClass(this._core.settings.autoHeightClass)},e.prototype.destroy=function(){var a,b;for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.AutoHeight=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._videos={},this._playing=null,this._handlers={"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.register({type:"state",name:"playing",tags:["interacting"]})},this),"resize.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.video&&this.isInFullScreen()&&a.preventDefault()},this),"refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._core.is("resizing")&&this._core.$stage.find(".cloned .owl-video-frame").remove()},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&"position"===a.property.name&&this._playing&&this.stop()},this),"prepared.owl.carousel":a.proxy(function(b){if(b.namespace){var c=a(b.content).find(".owl-video");c.length&&(c.css("display","none"),this.fetch(c,a(b.content)))}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this._core.$element.on(this._handlers),this._core.$element.on("click.owl.video",".owl-video-play-icon",a.proxy(function(a){this.play(a)},this))};e.Defaults={video:!1,videoHeight:!1,videoWidth:!1},e.prototype.fetch=function(a,b){var c=function(){return a.attr("data-vimeo-id")?"vimeo":a.attr("data-vzaar-id")?"vzaar":"youtube"}(),d=a.attr("data-vimeo-id")||a.attr("data-youtube-id")||a.attr("data-vzaar-id"),e=a.attr("data-width")||this._core.settings.videoWidth,f=a.attr("data-height")||this._core.settings.videoHeight,g=a.attr("href");if(!g)throw new Error("Missing video URL.");if(d=g.match(/(http:|https:|)\/\/(player.|www.|app.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com|be\-nocookie\.com)|vzaar\.com)\/(video\/|videos\/|embed\/|channels\/.+\/|groups\/.+\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/),d[3].indexOf("youtu")>-1)c="youtube";else if(d[3].indexOf("vimeo")>-1)c="vimeo";else{if(!(d[3].indexOf("vzaar")>-1))throw new Error("Video URL not supported.");c="vzaar"}d=d[6],this._videos[g]={type:c,id:d,width:e,height:f},b.attr("data-video",g),this.thumbnail(a,this._videos[g])},e.prototype.thumbnail=function(b,c){var d,e,f,g=c.width&&c.height?"width:"+c.width+"px;height:"+c.height+"px;":"",h=b.find("img"),i="src",j="",k=this._core.settings,l=function(c){e='<div class="owl-video-play-icon"></div>',d=k.lazyLoad?a("<div/>",{class:"owl-video-tn "+j,srcType:c}):a("<div/>",{class:"owl-video-tn",style:"opacity:1;background-image:url("+c+")"}),b.after(d),b.after(e)};if(b.wrap(a("<div/>",{class:"owl-video-wrapper",style:g})),this._core.settings.lazyLoad&&(i="data-src",j="owl-lazy"),h.length)return l(h.attr(i)),h.remove(),!1;"youtube"===c.type?(f="//img.youtube.com/vi/"+c.id+"/hqdefault.jpg",l(f)):"vimeo"===c.type?a.ajax({type:"GET",url:"//vimeo.com/api/v2/video/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a[0].thumbnail_large,l(f)}}):"vzaar"===c.type&&a.ajax({type:"GET",url:"//vzaar.com/api/videos/"+c.id+".json",jsonp:"callback",dataType:"jsonp",success:function(a){f=a.framegrab_url,l(f)}})},e.prototype.stop=function(){this._core.trigger("stop",null,"video"),this._playing.find(".owl-video-frame").remove(),this._playing.removeClass("owl-video-playing"),this._playing=null,this._core.leave("playing"),this._core.trigger("stopped",null,"video")},e.prototype.play=function(b){var c,d=a(b.target),e=d.closest("."+this._core.settings.itemClass),f=this._videos[e.attr("data-video")],g=f.width||"100%",h=f.height||this._core.$stage.height();this._playing||(this._core.enter("playing"),this._core.trigger("play",null,"video"),e=this._core.items(this._core.relative(e.index())),this._core.reset(e.index()),c=a('<iframe frameborder="0" allowfullscreen mozallowfullscreen webkitAllowFullScreen ></iframe>'),c.attr("height",h),c.attr("width",g),"youtube"===f.type?c.attr("src","//www.youtube.com/embed/"+f.id+"?autoplay=1&rel=0&v="+f.id):"vimeo"===f.type?c.attr("src","//player.vimeo.com/video/"+f.id+"?autoplay=1"):"vzaar"===f.type&&c.attr("src","//view.vzaar.com/"+f.id+"/player?autoplay=true"),a(c).wrap('<div class="owl-video-frame" />').insertAfter(e.find(".owl-video")),this._playing=e.addClass("owl-video-playing"))},e.prototype.isInFullScreen=function(){var b=c.fullscreenElement||c.mozFullScreenElement||c.webkitFullscreenElement;return b&&a(b).parent().hasClass("owl-video-frame")},e.prototype.destroy=function(){var a,b;this._core.$element.off("click.owl.video");for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Video=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this.core=b,this.core.options=a.extend({},e.Defaults,this.core.options),this.swapping=!0,this.previous=d,this.next=d,this.handlers={"change.owl.carousel":a.proxy(function(a){a.namespace&&"position"==a.property.name&&(this.previous=this.core.current(),this.next=a.property.value)},this),"drag.owl.carousel dragged.owl.carousel translated.owl.carousel":a.proxy(function(a){a.namespace&&(this.swapping="translated"==a.type)},this),"translate.owl.carousel":a.proxy(function(a){a.namespace&&this.swapping&&(this.core.options.animateOut||this.core.options.animateIn)&&this.swap()},this)},this.core.$element.on(this.handlers)};e.Defaults={animateOut:!1,
animateIn:!1},e.prototype.swap=function(){if(1===this.core.settings.items&&a.support.animation&&a.support.transition){this.core.speed(0);var b,c=a.proxy(this.clear,this),d=this.core.$stage.children().eq(this.previous),e=this.core.$stage.children().eq(this.next),f=this.core.settings.animateIn,g=this.core.settings.animateOut;this.core.current()!==this.previous&&(g&&(b=this.core.coordinates(this.previous)-this.core.coordinates(this.next),d.one(a.support.animation.end,c).css({left:b+"px"}).addClass("animated owl-animated-out").addClass(g)),f&&e.one(a.support.animation.end,c).addClass("animated owl-animated-in").addClass(f))}},e.prototype.clear=function(b){a(b.target).css({left:""}).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),this.core.onTransitionEnd()},e.prototype.destroy=function(){var a,b;for(a in this.handlers)this.core.$element.off(a,this.handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.Animate=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){var e=function(b){this._core=b,this._call=null,this._time=0,this._timeout=0,this._paused=!0,this._handlers={"changed.owl.carousel":a.proxy(function(a){a.namespace&&"settings"===a.property.name?this._core.settings.autoplay?this.play():this.stop():a.namespace&&"position"===a.property.name&&this._paused&&(this._time=0)},this),"initialized.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.autoplay&&this.play()},this),"play.owl.autoplay":a.proxy(function(a,b,c){a.namespace&&this.play(b,c)},this),"stop.owl.autoplay":a.proxy(function(a){a.namespace&&this.stop()},this),"mouseover.owl.autoplay":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"mouseleave.owl.autoplay":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.play()},this),"touchstart.owl.core":a.proxy(function(){this._core.settings.autoplayHoverPause&&this._core.is("rotating")&&this.pause()},this),"touchend.owl.core":a.proxy(function(){this._core.settings.autoplayHoverPause&&this.play()},this)},this._core.$element.on(this._handlers),this._core.options=a.extend({},e.Defaults,this._core.options)};e.Defaults={autoplay:!1,autoplayTimeout:5e3,autoplayHoverPause:!1,autoplaySpeed:!1},e.prototype._next=function(d){this._call=b.setTimeout(a.proxy(this._next,this,d),this._timeout*(Math.round(this.read()/this._timeout)+1)-this.read()),this._core.is("interacting")||c.hidden||this._core.next(d||this._core.settings.autoplaySpeed)},e.prototype.read=function(){return(new Date).getTime()-this._time},e.prototype.play=function(c,d){var e;this._core.is("rotating")||this._core.enter("rotating"),c=c||this._core.settings.autoplayTimeout,e=Math.min(this._time%(this._timeout||c),c),this._paused?(this._time=this.read(),this._paused=!1):b.clearTimeout(this._call),this._time+=this.read()%c-e,this._timeout=c,this._call=b.setTimeout(a.proxy(this._next,this,d),c-e)},e.prototype.stop=function(){this._core.is("rotating")&&(this._time=0,this._paused=!0,b.clearTimeout(this._call),this._core.leave("rotating"))},e.prototype.pause=function(){this._core.is("rotating")&&!this._paused&&(this._time=this.read(),this._paused=!0,b.clearTimeout(this._call))},e.prototype.destroy=function(){var a,b;this.stop();for(a in this._handlers)this._core.$element.off(a,this._handlers[a]);for(b in Object.getOwnPropertyNames(this))"function"!=typeof this[b]&&(this[b]=null)},a.fn.owlCarousel.Constructor.Plugins.autoplay=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){"use strict";var e=function(b){this._core=b,this._initialized=!1,this._pages=[],this._controls={},this._templates=[],this.$element=this._core.$element,this._overrides={next:this._core.next,prev:this._core.prev,to:this._core.to},this._handlers={"prepared.owl.carousel":a.proxy(function(b){b.namespace&&this._core.settings.dotsData&&this._templates.push('<div class="'+this._core.settings.dotClass+'">'+a(b.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot")+"</div>")},this),"added.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.dotsData&&this._templates.splice(a.position,0,this._templates.pop())},this),"remove.owl.carousel":a.proxy(function(a){a.namespace&&this._core.settings.dotsData&&this._templates.splice(a.position,1)},this),"changed.owl.carousel":a.proxy(function(a){a.namespace&&"position"==a.property.name&&this.draw()},this),"initialized.owl.carousel":a.proxy(function(a){a.namespace&&!this._initialized&&(this._core.trigger("initialize",null,"navigation"),this.initialize(),this.update(),this.draw(),this._initialized=!0,this._core.trigger("initialized",null,"navigation"))},this),"refreshed.owl.carousel":a.proxy(function(a){a.namespace&&this._initialized&&(this._core.trigger("refresh",null,"navigation"),this.update(),this.draw(),this._core.trigger("refreshed",null,"navigation"))},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this.$element.on(this._handlers)};e.Defaults={nav:!1,navText:['<span aria-label="Previous">&#x2039;</span>','<span aria-label="Next">&#x203a;</span>'],navSpeed:!1,navElement:'button type="button" role="presentation"',navContainer:!1,navContainerClass:"owl-nav",navClass:["owl-prev","owl-next"],slideBy:1,dotClass:"owl-dot",dotsClass:"owl-dots",dots:!0,dotsEach:!1,dotsData:!1,dotsSpeed:!1,dotsContainer:!1},e.prototype.initialize=function(){var b,c=this._core.settings;this._controls.$relative=(c.navContainer?a(c.navContainer):a("<div>").addClass(c.navContainerClass).appendTo(this.$element)).addClass("disabled"),this._controls.$previous=a("<"+c.navElement+">").addClass(c.navClass[0]).html(c.navText[0]).prependTo(this._controls.$relative).on("click",a.proxy(function(a){this.prev(c.navSpeed)},this)),this._controls.$next=a("<"+c.navElement+">").addClass(c.navClass[1]).html(c.navText[1]).appendTo(this._controls.$relative).on("click",a.proxy(function(a){this.next(c.navSpeed)},this)),c.dotsData||(this._templates=[a('<button role="button">').addClass(c.dotClass).append(a("<span>")).prop("outerHTML")]),this._controls.$absolute=(c.dotsContainer?a(c.dotsContainer):a("<div>").addClass(c.dotsClass).appendTo(this.$element)).addClass("disabled"),this._controls.$absolute.on("click","button",a.proxy(function(b){var d=a(b.target).parent().is(this._controls.$absolute)?a(b.target).index():a(b.target).parent().index();b.preventDefault(),this.to(d,c.dotsSpeed)},this));for(b in this._overrides)this._core[b]=a.proxy(this[b],this)},e.prototype.destroy=function(){var a,b,c,d,e;e=this._core.settings;for(a in this._handlers)this.$element.off(a,this._handlers[a]);for(b in this._controls)"$relative"===b&&e.navContainer?this._controls[b].html(""):this._controls[b].remove();for(d in this.overides)this._core[d]=this._overrides[d];for(c in Object.getOwnPropertyNames(this))"function"!=typeof this[c]&&(this[c]=null)},e.prototype.update=function(){var a,b,c,d=this._core.clones().length/2,e=d+this._core.items().length,f=this._core.maximum(!0),g=this._core.settings,h=g.center||g.autoWidth||g.dotsData?1:g.dotsEach||g.items;if("page"!==g.slideBy&&(g.slideBy=Math.min(g.slideBy,g.items)),g.dots||"page"==g.slideBy)for(this._pages=[],a=d,b=0,c=0;a<e;a++){if(b>=h||0===b){if(this._pages.push({start:Math.min(f,a-d),end:a-d+h-1}),Math.min(f,a-d)===f)break;b=0,++c}b+=this._core.mergers(this._core.relative(a))}},e.prototype.draw=function(){var b,c=this._core.settings,d=this._core.items().length<=c.items,e=this._core.relative(this._core.current()),f=c.loop||c.rewind;this._controls.$relative.toggleClass("disabled",!c.nav||d),c.nav&&(this._controls.$previous.toggleClass("disabled",!f&&e<=this._core.minimum(!0)),this._controls.$next.toggleClass("disabled",!f&&e>=this._core.maximum(!0))),this._controls.$absolute.toggleClass("disabled",!c.dots||d),c.dots&&(b=this._pages.length-this._controls.$absolute.children().length,c.dotsData&&0!==b?this._controls.$absolute.html(this._templates.join("")):b>0?this._controls.$absolute.append(new Array(b+1).join(this._templates[0])):b<0&&this._controls.$absolute.children().slice(b).remove(),this._controls.$absolute.find(".active").removeClass("active"),this._controls.$absolute.children().eq(a.inArray(this.current(),this._pages)).addClass("active"))},e.prototype.onTrigger=function(b){var c=this._core.settings;b.page={index:a.inArray(this.current(),this._pages),count:this._pages.length,size:c&&(c.center||c.autoWidth||c.dotsData?1:c.dotsEach||c.items)}},e.prototype.current=function(){var b=this._core.relative(this._core.current());return a.grep(this._pages,a.proxy(function(a,c){return a.start<=b&&a.end>=b},this)).pop()},e.prototype.getPosition=function(b){var c,d,e=this._core.settings;return"page"==e.slideBy?(c=a.inArray(this.current(),this._pages),d=this._pages.length,b?++c:--c,c=this._pages[(c%d+d)%d].start):(c=this._core.relative(this._core.current()),d=this._core.items().length,b?c+=e.slideBy:c-=e.slideBy),c},e.prototype.next=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!0),b)},e.prototype.prev=function(b){a.proxy(this._overrides.to,this._core)(this.getPosition(!1),b)},e.prototype.to=function(b,c,d){var e;!d&&this._pages.length?(e=this._pages.length,a.proxy(this._overrides.to,this._core)(this._pages[(b%e+e)%e].start,c)):a.proxy(this._overrides.to,this._core)(b,c)},a.fn.owlCarousel.Constructor.Plugins.Navigation=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){"use strict";var e=function(c){this._core=c,this._hashes={},this.$element=this._core.$element,this._handlers={"initialized.owl.carousel":a.proxy(function(c){c.namespace&&"URLHash"===this._core.settings.startPosition&&a(b).trigger("hashchange.owl.navigation")},this),"prepared.owl.carousel":a.proxy(function(b){if(b.namespace){var c=a(b.content).find("[data-hash]").addBack("[data-hash]").attr("data-hash");if(!c)return;this._hashes[c]=b.content}},this),"changed.owl.carousel":a.proxy(function(c){if(c.namespace&&"position"===c.property.name){var d=this._core.items(this._core.relative(this._core.current())),e=a.map(this._hashes,function(a,b){return a===d?b:null}).join();if(!e||b.location.hash.slice(1)===e)return;b.location.hash=e}},this)},this._core.options=a.extend({},e.Defaults,this._core.options),this.$element.on(this._handlers),a(b).on("hashchange.owl.navigation",a.proxy(function(a){var c=b.location.hash.substring(1),e=this._core.$stage.children(),f=this._hashes[c]&&e.index(this._hashes[c]);f!==d&&f!==this._core.current()&&this._core.to(this._core.relative(f),!1,!0)},this))};e.Defaults={URLhashListener:!1},e.prototype.destroy=function(){var c,d;a(b).off("hashchange.owl.navigation");for(c in this._handlers)this._core.$element.off(c,this._handlers[c]);for(d in Object.getOwnPropertyNames(this))"function"!=typeof this[d]&&(this[d]=null)},a.fn.owlCarousel.Constructor.Plugins.Hash=e}(window.Zepto||window.jQuery,window,document),function(a,b,c,d){function e(b,c){var e=!1,f=b.charAt(0).toUpperCase()+b.slice(1);return a.each((b+" "+h.join(f+" ")+f).split(" "),function(a,b){if(g[b]!==d)return e=!c||b,!1}),e}function f(a){return e(a,!0)}var g=a("<support>").get(0).style,h="Webkit Moz O ms".split(" "),i={transition:{end:{WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd",transition:"transitionend"}},animation:{end:{WebkitAnimation:"webkitAnimationEnd",MozAnimation:"animationend",OAnimation:"oAnimationEnd",animation:"animationend"}}},j={csstransforms:function(){return!!e("transform")},csstransforms3d:function(){return!!e("perspective")},csstransitions:function(){return!!e("transition")},cssanimations:function(){return!!e("animation")}};j.csstransitions()&&(a.support.transition=new String(f("transition")),a.support.transition.end=i.transition.end[a.support.transition]),j.cssanimations()&&(a.support.animation=new String(f("animation")),a.support.animation.end=i.animation.end[a.support.animation]),j.csstransforms()&&(a.support.transform=new String(f("transform")),a.support.transform3d=j.csstransforms3d())}(window.Zepto||window.jQuery,window,document);
/* End */
;
; /* Start:"a:4:{s:4:"full";s:110:"/local/templates/us_2018/components/bitrix/catalog.products.viewed/detail-item-viewed/script.js?15723419811291";s:6:"source";s:95:"/local/templates/us_2018/components/bitrix/catalog.products.viewed/detail-item-viewed/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/
(function() {
	'use strict';

	if (!!window.JCCatalogProductsViewedComponent)
		return;

	window.JCCatalogProductsViewedComponent = function(params) {
		this.container = document.querySelector('[data-entity="' + params.container + '"]');

		if (params.initiallyShowHeader)
		{
			BX.ready(BX.delegate(this.showHeader, this));
		}
	};

	window.JCCatalogProductsViewedComponent.prototype =
	{
		showHeader: function(animate)
		{
			var parentNode = BX.findParent(this.container, {attr: {'data-entity': 'parent-container'}}),
				header;

			if (parentNode && BX.type.isDomNode(parentNode))
			{
				header = parentNode.querySelector('[data-entity="header"');

				if (header && header.getAttribute('data-showed') != 'true')
				{
					header.style.display = '';

					if (animate)
					{
						new BX.easing({
							duration: 2000,
							start: {opacity: 0},
							finish: {opacity: 100},
							transition: BX.easing.makeEaseOut(BX.easing.transitions.quad),
							step: function(state){
								header.style.opacity = state.opacity / 100;
							},
							complete: function(){
								header.removeAttribute('style');
								header.setAttribute('data-showed', 'true');
							}
						}).animate();
					}
					else
					{
						header.style.opacity = 100;
					}
				}
			}
		}
	}
})();
/* End */
;; /* /local/templates/us_2018/components/bitrix/catalog.element/.default/script.js?157234198199818*/
; /* /bitrix/components/bitrix/iblock.vote/templates/stars/script.min.js?15723421044586*/
; /* /bitrix/components/bitrix/catalog.product.subscribe/templates/.default/script.min.js?157234209211818*/
; /* /bitrix/components/bitrix/sale.prediction.product.detail/templates/.default/script.min.js?1572342106394*/
; /* /bitrix/components/bitrix/sale.products.gift/templates/.default/script.min.js?15723420913303*/
; /* /local/templates/us_2018/components/bitrix/catalog.item/.default/script.js?157234198163290*/
; /* /bitrix/components/bitrix/sale.gift.main.products/templates/.default/script.min.js?15723421072661*/
; /* /local/components/uvelirsoft/reviews/templates/.default/script.js?15723419811071*/
; /* /local/templates/us_2018/components/bitrix/main.file.input/drag_n_drop/script.js?157234198120259*/
; /* /local/templates/us_2018/js/jquery.mask.min.js?15723419818185*/
; /* /bitrix/components/bitrix/catalog.store.amount/templates/.default/script.js?15723421041401*/
; /* /local/templates/us_2018/components/bitrix/catalog.section/popular/script.js?15723419817950*/
; /* /local/templates/us_2018/owl.carousel/owl.carousel.min.js?157234198144342*/
; /* /local/templates/us_2018/components/bitrix/catalog.products.viewed/detail-item-viewed/script.js?15723419811291*/

//# sourceMappingURL=page_2f4938707416e82fe554c067efe8d6e2.map.js