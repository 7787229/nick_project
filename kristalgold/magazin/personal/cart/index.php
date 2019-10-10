<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?><?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	"us", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADDITIONAL_PICT_PROP_1" => "-",
		"ADDITIONAL_PICT_PROP_19" => "-",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"ADDITIONAL_PICT_PROP_26" => "-",
		"ADDITIONAL_PICT_PROP_47" => "-",
		"AUTO_CALCULATION" => "Y",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "WEIGHT",
			2 => "PROPS",
			3 => "DELETE",
			4 => "DELAY",
			5 => "PRICE",
			6 => "QUANTITY",
			7 => "SUM",
		),
		"COLUMNS_LIST_EXT" => array(
			0 => "PREVIEW_PICTURE",
			1 => "WEIGHT",
			2 => "PROPS",
			3 => "DELETE",
			4 => "DELAY",
			5 => "SUM",
		),
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "us",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"CORRECT_RATIO" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_HIDE_NOT_AVAILABLE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_PLACE" => "BOTTOM",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "N",
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		"HIDE_COUPON" => "N",
		"OFFERS_PROPS" => array(
			0 => "VSTAVKA",
			1 => "RAZMER",
		),
		"PATH_TO_ORDER" => SITE_DIR."magazin/personal/order/make/",
		"PRICE_VAT_SHOW_VALUE" => "Y",
		"QUANTITY_FLOAT" => "N",
		"SET_TITLE" => "Y",
		"TEMPLATE_THEME" => "uvelirsoft",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_GIFTS" => "Y",
		"USE_PREPAYMENT" => "N",
		"DEFERRED_REFRESH" => "N",
		"USE_DYNAMIC_SCROLL" => "Y",
		"SHOW_FILTER" => "Y",
		"SHOW_RESTORE" => "Y",
		"COLUMNS_LIST_MOBILE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "WEIGHT",
			2 => "PROPS",
			3 => "DELETE",
			4 => "DELAY",
			5 => "SUM",
		),
		"TOTAL_BLOCK_DISPLAY" => array(
			0 => "top",
		),
		"DISPLAY_MODE" => "extended",
		"PRICE_DISPLAY_MODE" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"DISCOUNT_PERCENT_POSITION" => "top-left",
		"PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
		"USE_PRICE_ANIMATION" => "Y",
		"LABEL_PROP" => array(
			0 => "NEWPRODUCT",
			1 => "BESTSELLER",
			2 => "DISCOUNT",
		),
		"LABEL_PROP_MOBILE" => array(
			0 => "NEWPRODUCT",
			1 => "BESTSELLER",
			2 => "DISCOUNT",
		),
		"LABEL_PROP_POSITION" => "top-right"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>