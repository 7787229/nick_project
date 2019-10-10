<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Iblock;
use Bitrix\Сatalog;
use Bitrix\Main\Context;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\Internals\DiscountCouponTable;

if (!Loader::includeModule('iblock') or !Loader::includeModule('sale')){
	return;
}

// infoblock type
$aShopIBlockType = CIBlockParameters::GetIBlockTypes();
$iblockFilterShop = (
	!empty($arCurrentValues['SHOP_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['SHOP_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilterShop);
while ($arr = $rsIBlock->Fetch()){
	$aShopIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}


$rsSites = CSite::GetList($by="sort", $order="desc", array());
while ($arSite = $rsSites->Fetch()){
   $arSiteList[$arSite["ID"]] = $arSite["NAME"];
}

if($arCurrentValues['SITE_ID']){
	// person type
	$rsPersonType = CSalePersonType::GetList(Array("SORT" => "ASC"), Array("LID"=>$arCurrentValues['SITE_ID']));
	while ($ptype = $rsPersonType->Fetch()){
		$arPersonType[$ptype["ID"]] = $ptype["NAME"];
	}

		if($arCurrentValues['PERSON_TYPE']){
		$db_props = CSaleOrderProps::GetList(
			array("SORT" => "ASC"),
			array(
					"PERSON_TYPE_ID" => $arCurrentValues['PERSON_TYPE'],
					"ACTIVE" => "Y",
				),
			false,
			false,
			array()
		);
		while($props = $db_props->Fetch()){
			$arProps[$props["ID"]] = $props["NAME"];
		}

		// группы свойств
		$db_propsGroup = CSaleOrderPropsGroup::GetList(
	        array("SORT" => "DESC"),
		        array("PERSON_TYPE_ID" => $arCurrentValues['PERSON_TYPE']),
		        false,
		        false,
		        array()
			);
		while ($propsGroup = $db_propsGroup->Fetch()){
			$arPropsGroup[$propsGroup["ID"]] = $propsGroup["NAME"];
		}

	}else{
		$arProps = array();
	}
}else{
	$arPersonType = array();
	$arProps = array();
}

if($arCurrentValues['SHOP_IBLOCK_ID']){
	// свойства инфоблока магазинов
	$res = CIBlock::GetProperties(IntVal($arCurrentValues['SHOP_IBLOCK_ID']));
	$arFieldsShop = array("" => "..", "NAME" => "Наименование (NAME)");
	while($res_arr = $res->Fetch()){
		$arFieldsShop[$res_arr["CODE"]] = $res_arr["NAME"]." (".$res_arr["CODE"].")";
	}
}

// выбор оплат
$arPayments =array();
$res = PaySystem\Manager::getList(array(
	'select' => array("ID", "PAY_SYSTEM_ID", "PERSON_TYPE_ID", "NAME"),
	'filter'  => array("ACTIVE" => "Y",),
	'order' => array("SORT"=>"ASC")
));
$arPayments[0]=" - выберите платежную систему -";
while ($arPay = $res->fetch()) {
	$arPayments[$arPay["ID"]]=" (".$arPay["ID"].") ".$arPay["NAME"];
}
// скидки
$arDiscounts=array();
$arDiscounts[0]=" - выберите скидку -";
$arFilter = array('ACTIVE' => 'Y');
$arSelect = array('ID', 'COUPON', 'DISCOUNT_ID', 'ACTIVE', 'DISCOUNT_NAME' => 'DISCOUNT.NAME');
$arCoupons = DiscountCouponTable::getList(array( 'select' => $arSelect, 'filter' => $arFilter ));
while ( $arCoupon = $arCoupons->fetch() ) {
	$arDiscounts[$arCoupon["DISCOUNT_ID"]]=" (".$arCoupon["DISCOUNT_ID"].") ".$arCoupon["DISCOUNT_NAME"];
}

unset($arr,$rsIBlock,$iblockFilterShop,$rsPersonType,$ptype,$props,$db_props,$rsSites,$arSite,$arPay,$res,$arCoupon);

$dbDeliveryResult = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();

foreach ($dbDeliveryResult as $arDeliveryResult){
	// обрабатываем только настраиваемые способы доставки,
	// автоматизированные способы требуют определения местоположения
	// для таких доставок лучше использовать полноценное оформление заказа
	if($arDeliveryResult["CLASS_NAME"] == "\Bitrix\Sale\Delivery\Services\Configurable"){
		$arDelivery[$arDeliveryResult["ID"]] = "(".$arDeliveryResult["CODE"].") ".$arDeliveryResult["NAME"];
	}
}

$arComponentParameters = array(
	"GROUPS" => array(
		"GENERAL" => array(
			"NAME" => GetMessage("General settings"),
		),
		"DELIVERY" => array(
			"NAME" => GetMessage("Delivery settings"),
		),
		"DELIVERY_SHOP" => array(
			"NAME" => GetMessage("Delivery Shop settings"),
		),
		"ORDER_PROPS" => array(
			"NAME" => GetMessage("Order props"),
		),
		"PAYMENT" => array(
			"NAME" => GetMessage("Payment settings"),
		),		
		"PAYMENT_SETTINGS" => array(
			"NAME" => "Настройки оплат",
		),

	),
	"PARAMETERS" => array(
		'DELIVERY_TYPE' => array(
			'PARENT' => 'DELIVERY',
			'NAME' => GetMessage('DELIVERY_METHODS_WITH_COMMENT'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arDelivery
			),
		'DELIVERY_SHOP_TYPE' => array(
			'PARENT' => 'DELIVERY_SHOP',
			'NAME' => GetMessage('DELIVERY_METHODS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arDelivery
			),
	    "SHOP_IBLOCK_TYPE" => array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => GetMessage("Type of information block"),
			"TYPE" => "LIST",
			"VALUES" => $aShopIBlockType,
			"REFRESH" => "Y",
			),
	    "SHOP_IBLOCK_ID" => array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => GetMessage("Information block"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => $aShopIBlock,
			"REFRESH" => "N",
		),
	    "HIDE_ORDER_PROPS" => array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => GetMessage("Order props to hide"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "Y",
			"VALUES" => $arProps,
			"REFRESH" => "N",
		),
	    "SITE_ID" => array(
			"PARENT" => "ORDER_PROPS",
			"NAME" => GetMessage("Sites"),
			"TYPE" => "LIST",
			"VALUES" => $arSiteList,
			"REFRESH" => "Y",
			),
	    "PERSON_TYPE" => array(
			"PARENT" => "ORDER_PROPS",
			"NAME" => GetMessage("Person type"),
			"TYPE" => "LIST",
			"VALUES" => $arPersonType,
			"REFRESH" => "Y",
			),
	    "ORDER_PROPS" => array(
			"PARENT" => "ORDER_PROPS",
			"NAME" => GetMessage("Order props"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "Y",
			"VALUES" => $arProps,
			"REFRESH" => "N",
		),
	    "PERSONAL_PROPS_GROUP" => array(
			"PARENT" => "ORDER_PROPS",
			"NAME" => GetMessage("Props group for personal"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arPropsGroup,
			"REFRESH" => "N",
		),
	    "ADDRESS_PROPS_GROUP" => array(
			"PARENT" => "ORDER_PROPS",
			"NAME" => GetMessage("Props group for address"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arPropsGroup,
			"REFRESH" => "N",
		),
	    "BUTTON_TEXT" => array(
			"PARENT" => "GENERAL",
			"NAME" => GetMessage("Button text"),
			"TYPE" => "STRING",
			"DEFAULT" => "Оформить заказ",
		),
	    "DELIVERY_TITLE" => array(
			"PARENT" => "GENERAL",
			"NAME" => GetMessage("Delivery title"),
			"TYPE" => "STRING",
			"DEFAULT" => "Способы получения",
		),
	    "PAYMENT_CASH" => array(
			"PARENT" => "PAYMENT",
			"NAME" => GetMessage("Payment cash"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arPayments,
			"REFRESH" => "N",
		),
	    "PAYMENT_CARDS" => array(
			"PARENT" => "PAYMENT",
			"NAME" => GetMessage("Payment cards"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arPayments,
			"REFRESH" => "N",
		),
	    "DISCOUNT_ID" => array(
			"PARENT" => "PAYMENT",
			"NAME" => GetMessage("Discount for pre-payment"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arDiscounts,
			"REFRESH" => "N",
		),
		'PAYMENT_TYPE' => array(
			'PARENT' => 'PAYMENT_SETTINGS',
			'NAME' => 'Способы оплаты',
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => '-',
			'VALUES' => $arPayments
		),
		"USER_CONSENT" => array(),
	)
);

if($arCurrentValues['PAYMENT_TYPE']){
	foreach($arCurrentValues['PAYMENT_TYPE'] as $payment){
		$arComponentParameters["PARAMETERS"]["DELIVERY_".$payment] = array(
			"PARENT" => "PAYMENT_SETTINGS",
			"NAME" => "Доставки для ".$arPayments[$payment],
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "Y",
			"VALUES" => $arDelivery,
			"REFRESH" => "N",
		);		
	}
}

if($arCurrentValues['SHOP_IBLOCK_ID']){

/*
	$arComponentParameters["PARAMETERS"]["SHOP_NAME"] = array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => "Поле с наименованием магазина",
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arFieldsShop,
			"REFRESH" => "N",
	);
	$arComponentParameters["PARAMETERS"]["SHOP_TOWN"] = array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => "Поле, где указан город",
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arFieldsShop,
			"REFRESH" => "N",
	);


*/

	$arComponentParameters["PARAMETERS"]["SHOP_LAT"] = array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => "Поле, где указана широта (LAT)",
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arFieldsShop,
			"REFRESH" => "N",
	);
	$arComponentParameters["PARAMETERS"]["SHOP_LON"] = array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => "Поле, где указана долгота (LON)",
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",
			"VALUES" => $arFieldsShop,
			"REFRESH" => "N",
	);

	$arComponentParameters["PARAMETERS"]["SHOP_TEMPLATE_LIST"] = array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => "Шаблон (html) магазина в списке, можно использовать следующие подстановки: ".str_replace("##,","","#".implode("#, #",  array_keys($arFieldsShop))."#"),
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",

	);
	$arComponentParameters["PARAMETERS"]["SHOP_TEMPLATE_SELECT"] = array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => "Шаблон выпадающего списка, можно использовать следующие подстановки: ".str_replace("##,","","#".implode("#, #",  array_keys($arFieldsShop))."#"),
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",

	);
	$arComponentParameters["PARAMETERS"]["SHOP_TEMPLATE_BALLOON"] = array(
			"PARENT" => "DELIVERY_SHOP",
			"NAME" => "Шаблон (html) магазина в балуне на карте, можно использовать следующие подстановки: ".str_replace("##,","","#".implode("#, #",  array_keys($arFieldsShop))."#"),
			"TYPE" => "STRING",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "N",

	);
}
