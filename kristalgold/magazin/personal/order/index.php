<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order", 
	"list", 
	array(
		"PROP_1" => array(
		),
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/personal/order/",
		"ORDERS_PER_PAGE" => "10",
		"PATH_TO_PAYMENT" => "/magazin/personal/order/payment/",
		"PATH_TO_BASKET" => "/magazin/personal/cart/",
		"SET_TITLE" => "Y",
		"SAVE_IN_SESSION" => "N",
		"NAV_TEMPLATE" => "arrows",
		"COMPONENT_TEMPLATE" => "list",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"DETAIL_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"PATH_TO_CATALOG" => "/magazin/catalog/",
		"RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "0",
		),
		"REFRESH_PRICES" => "N",
		"ORDER_DEFAULT_SORT" => "STATUS",
		"ALLOW_INNER" => "N",
		"ONLY_INNER_FULL" => "N",
		"DISALLOW_CANCEL" => "N",
		"SEF_URL_TEMPLATES" => array(
			"list" => "index.php",
			"detail" => "detail/#ID#/",
			"cancel" => "cancel/#ID#/",
		)
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>