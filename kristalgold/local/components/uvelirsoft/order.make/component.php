<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult = array();
use Bitrix\Main,
Bitrix\Main\Localization\Loc as Loc,
	    Bitrix\Main\Loader,
	    Bitrix\Main\Config\Option,
	    Bitrix\Sale\Delivery,
	    Bitrix\Sale\PaySystem,
	    Bitrix\Sale,
	    Bitrix\Sale\Order,
	    Bitrix\Sale\DiscountCouponsManager,
	    Bitrix\Main\Context,
	    Bitrix\Main\Event;

if (!Loader::IncludeModule('sale') or !Loader::IncludeModule('iblock')) die("iblock error!");

// найдем список магазинов, если указан инфоблок
if($arParams["SHOP_IBLOCK_ID"]>0){

	// свойства инфоблока магазинов
	$resProps = CIBlock::GetProperties(IntVal($arParams["SHOP_IBLOCK_ID"]));
	$arFieldsShop = [];
	while($res_arr = $resProps->Fetch()){
		$arFieldsShop[$res_arr["CODE"]] = $res_arr["NAME"]." (".$res_arr["CODE"].")";
	}

	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
	$arFilter = array("IBLOCK_ID"=>IntVal($arParams["SHOP_IBLOCK_ID"]), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
	while($ob = $res->GetNextElement()){
		$arFields = $ob->GetFields();
		$arProps = $ob->GetProperties();
		$arResult["SHOPS"][$arFields["ID"]]["NAME"] = $arFields["NAME"];
		$templateList = str_replace("#NAME#",$arFields["NAME"],$arParams["SHOP_TEMPLATE_LIST"]);
		$templateSelect = str_replace("#NAME#",$arFields["NAME"],$arParams["SHOP_TEMPLATE_SELECT"]);
		$templateBalloon = str_replace("#NAME#",$arFields["NAME"],$arParams["SHOP_TEMPLATE_BALLOON"]);

		foreach ($arFieldsShop as $codeProp => $nameProp) {
			$templateList = str_replace("#".$codeProp."#",$arProps[$codeProp]["VALUE"],$templateList);
			$templateSelect = str_replace("#".$codeProp."#",$arProps[$codeProp]["VALUE"],$templateSelect);
			$templateBalloon = str_replace("#".$codeProp."#",$arProps[$codeProp]["VALUE"],$templateBalloon);

			switch ($codeProp) {
				case $arParams["SHOP_LAT"]:
					$arResult["SHOPS"][$arFields["ID"]]["LAT"] = $arProps[$codeProp]["VALUE"];
					break;
				case $arParams["SHOP_LON"]:
					$arResult["SHOPS"][$arFields["ID"]]["LON"] = $arProps[$codeProp]["VALUE"];
					break;				
				default:
					$arResult["SHOPS"][$arFields["ID"]][$codeProp] = $arProps[$codeProp]["VALUE"];
					break;
			}


		}
		$arResult["SHOP_TEMPLATE_LIST"][$arFields["ID"]] = $templateList;
		$arResult["SHOP_TEMPLATE_SELECT"][$arFields["ID"]] = $templateSelect;
		$arResult["SHOP_TEMPLATE_BALLOON"][$arFields["ID"]] = $templateBalloon;

	}
}


// СОЗДАНИЕ AJAX заказа ////////////////////////////////////////////////////////
if($_POST["CREATE_ORDER"] == "AJAX"){

										$GLOBALS['APPLICATION']->RestartBuffer();

										if(trim(strip_tags($_POST["MAIL"])) == "" or empty($_POST["MAIL"]) or !isset($_POST["MAIL"])){
											// выходим если емейл пользователя не указан
											exit;
										}
										global $USER;
										if (!Loader::IncludeModule('sale') or !Loader::IncludeModule('iblock')) die("iblock error!");

										$order_id = "";
										// в $result укладываются данные, которые потом могут потребоваться в событии
										$siteId = \Bitrix\Main\Context::getCurrent()->getSite();
										$result["SITE_ID"] = $siteId;

										// если пользователь аторизован - берем текущего
										if ($USER->IsAuthorized()){

											$rsUser = CUser::GetByID($USER->GetID());
											$result["USER"] = $rsUser->Fetch();
											$userID = $USER->GetID();
										}else{
											// пытаемся найти пользователя по емейл и ему запихнуть заказ
											$filter = array(
											    "ACTIVE"              => "Y",
											    "EMAIL"               => $_POST["MAIL"],
											);
											$rsUsers = CUser::GetList(($by="ID"), ($order="DESC"), $filter); // выбираем пользователей
											if($arUser = $rsUsers->fetch()){
												// берем данные первого найденного пользователя
												$userID = $arUser["ID"];
											}else{
												// создаем нового
												$user = new CUser;
												$userPassword = md5(mt_rand(32423141324,435234476856876));
												$arFields = Array(
												  "NAME"              => $_POST["NAME"],
												  "EMAIL"             => $_POST["MAIL"],
												  "LOGIN"             => $_POST["MAIL"],
												  "LID"               => \Bitrix\Main\Context::getCurrent()->getSite(),
												  "ACTIVE"            => "Y",
												  "GROUP_ID"		  => array(2),
												  "PASSWORD"          => $userPassword,
												  "CONFIRM_PASSWORD"  => $userPassword,
												  "UF_PERSONAL_INFO"  => "Y"
												);
												$userID = $user->Add($arFields);
												if (!intval($userID) > 0){
													echo $user->LAST_ERROR;
													exit;
												}
											}
										}

										// echo $userID;
										//exit;

										// валюта по-умолчанию
										$currencyCode = Option::get('sale', 'default_currency', 'RUB');
										// создаем заказ
										$order = Order::create($siteId, $userID);
										$order->setPersonTypeId($arParams["PERSON_TYPE"]);
										$basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), $siteId)->getOrderableItems();
										$order->setBasket($basket);
										// доставка
										$shipmentCollection = $order->getShipmentCollection();
										$shipment = $shipmentCollection->createItem(Bitrix\Sale\Delivery\Services\Manager::getObjectById(intval($_POST["DELIVERY"])));
										//$shipmentItemCollection = $shipment->getShipmentItemCollection();

										// Создаём одну отгрузку и устанавливаем способ доставки - "Без доставки" (он служебный)
										/*
										$shipmentCollection = $order->getShipmentCollection();
										$shipment = $shipmentCollection->createItem();
										$service = Delivery\Services\Manager::getById(Delivery\Services\EmptyDeliveryService::getEmptyDeliveryServiceId());
										$shipment->setFields(array(
										    'DELIVERY_ID' => $service['ID'],
										    'DELIVERY_NAME' => $service['NAME'],
										));
										*/

										$shipmentItemCollection = $shipment->getShipmentItemCollection();

										foreach ($basket as $basketItem){
										        $item = $shipmentItemCollection->createItem($basketItem);
										        $item->setQuantity($basketItem->getQuantity());
										        $basketPropertyCollection = $basketItem->getPropertyCollection();
										        $result["ITEMS"][$basketItem->getId()] = array(
										            "ID" => $basketItem->getId(),         // ID записи в корзине
										            "PRODUCT_ID" => $basketItem->getProductId(),  // ID товара
										            "QUANTITY" => $basketItem->getQuantity(),   // Количество
										            "CURRENCY" => $basketItem->getCurrency(),     // Валюта
										            "WEIGHT" => $basketItem->getWeight(),     // Вес
										            "NAME" => $basketItem->getField('NAME'),// Любое поле товара в корзине
													"NOTES" => $basketItem->getField('NOTES'),
										            "PROPS" => $basketPropertyCollection->getPropertyValues()
										        );
										}
										// оплата
										// -- конец оплаты
										$order->doFinalAction(true);
										$order->setField('CURRENCY', $currencyCode);
										$order->setField('USER_DESCRIPTION', ($_POST['COMMENT'] ? htmlspecialchars($_POST['COMMENT']):""));
										$order->setField('PRICE', $basket->getPrice()); // Сумма с учетом скидок
										$result["USER_DESCRIPTION"] = ($_POST['COMMENT'] ? htmlspecialchars($_POST['COMMENT']):"");

										$propertyCollection = $order->getPropertyCollection();
										foreach ($propertyCollection as $property){
											$arProperty = $property->getProperty();
											if($arProperty["CODE"] == "COMMENT" and $_POST["DELIVERY"] == $arParams["DELIVERY_SHOP_TYPE"]){
												$_POST["COMMENT"] = "Самовывоз из магазина - ".str_replace('"',"",str_replace("'","",strip_tags(html_entity_decode(htmlspecialchars_decode(htmlspecialchars_decode($arResult['SHOP_TEMPLATE_LIST'][$_POST["SHOP"]]))))));
											}
											$property->setValue(($_POST[$arProperty["CODE"]] ? htmlspecialchars($_POST[$arProperty["CODE"]]):""));
									        $result["ORDER_PROPS"][$arProperty["CODE"]] = ($_POST[$arProperty["CODE"]] ? htmlspecialchars($_POST[$arProperty["CODE"]]):"");
										}
										$order->save();
										$orderId = $order->GetId();

										if($orderId>0){
											echo $orderId;
										}else{
											echo "error!";
										}
										exit;
}
// КОНЕЦ СОЗДАНИя AJAX заказа //////////////////////////////////////////////////
$arResult["PROFILE_USER_ID"] = false;
$arResult["PROFILE_USER"] = array();
$arResult["USER"] = array();

global $USER;
if($USER->IsAuthorized()){
	$rsUser = CUser::GetByID($USER->GetID());
	$arResult["USER"] = $rsUser->Fetch();

	// получим последний профиль пользователя
	$db_sales = CSaleOrderUserProps::GetList(
		array("DATE_UPDATE" => "DESC"),
		array("USER_ID" => $USER->GetID()),
		false,
		array("nTopCount" => 1)
	);

	while ($ar_sales = $db_sales->Fetch()){
		$arResult["PROFILE_USER_ID"] = $ar_sales['ID'];
	}

	if($arResult["PROFILE_USER_ID"]){
		// получим свойства профиля
		$rsProfile = CSaleOrderUserPropsValue::GetList(($b="SORT"), ($o="ASC"), Array("USER_PROPS_ID"=>$arResult["PROFILE_USER_ID"]));
		while ($arProfile = $rsProfile->Fetch()){
			$arResult["PROFILE_USER"][$arProfile["CODE"]] = $arProfile["VALUE"];
		}
	}
}



$arResult["DELIVERY"] = \Bitrix\Sale\Delivery\Services\Manager::getActiveList();

// создадим массив стоимости доставки (для испоьлзования в JS)
foreach ($arResult["DELIVERY"] as $arDelivery) {
	if(isset($arDelivery["CONFIG"]["MAIN"]["PRICE"])){
		$arResult["DELIVERY_PRICE"][$arDelivery["ID"]] = $arDelivery["CONFIG"]["MAIN"]["PRICE"];
	}
}


if($arParams['PERSON_TYPE']>0){
	$db_props = CSaleOrderProps::GetList(
			array("SORT" => "ASC"),
			array(
					"PERSON_TYPE_ID" => $arParams['PERSON_TYPE'],
					"ACTIVE" => "Y",
				),
			false,
			false,
			array()
		);
		while($props = $db_props->Fetch()){
			$arResult["ORDER_PROPS"][$props["ID"]] = $props;
			// заполним значения для полей из профиля или из пользователя
			if($props["IS_EMAIL"] == "Y"){
				$arResult["ORDER_PROPS"][$props["ID"]]["VALUE"] = ($arResult["PROFILE_USER"][$props["CODE"]] ? $arResult["PROFILE_USER"][$props["CODE"]]:$arResult["USER"]["EMAIL"]);
			}
			if($props["IS_PAYER"] == "Y"){
				$arResult["ORDER_PROPS"][$props["ID"]]["VALUE"] = ($arResult["PROFILE_USER"][$props["CODE"]] ? $arResult["PROFILE_USER"][$props["CODE"]]:$arResult["USER"]["NAME"]);
			}
			if(!$arResult["ORDER_PROPS"][$props["ID"]]["VALUE"] and $arResult["PROFILE_USER"][$props["CODE"]]){
				$arResult["ORDER_PROPS"][$props["ID"]]["VALUE"] = $arResult["PROFILE_USER"][$props["CODE"]];
			}

		}
}else{
	$arResult["ORDER_PROPS"] = array();
}
// возьмем товары из корзины
$arResult["BASKET"] = array();

$basket = \Bitrix\Sale\Basket::loadItemsForFUser(\Bitrix\Sale\Fuser::getId(), \Bitrix\Main\Context::getCurrent()->getSite());

$order = Bitrix\Sale\Order::create(\Bitrix\Main\Context::getCurrent()->getSite(), \Bitrix\Sale\Fuser::getId());
$order->setPersonTypeId($arParams["PERSON_TYPE"]);
$order->setBasket($basket);

$discounts = $order->getDiscount();
$res = $discounts->getApplyResult();
$price = $order->getPrice();  //



//  НО нам это не подойдет, потомучто в корзине могут быть деактивированные товары

$arResult['CURRENCY'] = COption::GetOptionString("sale", "default_currency","RUB");

//$arResult["PRICE"] = $basket->getPrice(); // Сумма с учетом скидок
//$arResult["FULL_PRICE"] = $basket->getBasePrice(); // Сумма без учета скидок
//$arResult["WEIGHT"] = $basket->getWeight(); // Общий вес корзины

$arResult["PRICE"] = 0;
$arResult["WEIGHT"] = 0;

foreach ($basket as $basketItem) {

	$arProps = array();
	$itemID = $basketItem->getId();
	$productID = $basketItem->getProductId();
	$basketPropertyCollection = $basketItem->getPropertyCollection();
	$arProps = $basketPropertyCollection->getPropertyValues();

	$arPicture = array();

	$mxResult = CCatalogSku::GetProductInfo($productID);
	if(is_array($mxResult) && !empty($mxResult['ID'])){
		$arProduct = CIBlockElement::GetByID($mxResult['ID'])->GetNext();
		}else{
		$arProduct = CIBlockElement::GetByID($productID)->GetNext();
	}

	if($arProduct["ACTIVE"] == "N") continue;

	if($arProduct){
		if($arProduct['PREVIEW_PICTURE'] > 0){
			$fileID = $arProduct['PREVIEW_PICTURE'];
		}elseif ($arProduct['DETAIL_PICTURE'] > 0){
			$fileID = $arProduct['DETAIL_PICTURE'];
		}else{
			$fileID = 0;
		}
		$arPicture = CFile::ResizeImageGet($fileID, array('width' => 90, 'height' => 110));
		$arPicture['SIZE'] = getimagesize($_SERVER['DOCUMENT_ROOT'].$arPicture['src']);
	}

	$arResult["BASKET"][$itemID] = array(
		"ID" => $basketItem->getId(),         // ID записи в корзине
		"PRODUCT_ID" => $productID,  // ID товара
		"PRICE" => $basketItem->getPrice(),      // Цена за единицу
		"DISCOUNT_PRICE" => $basketItem->getDiscountPrice(),      // Цена за единицу
		"QUANTITY" => $basketItem->getQuantity(),   // Количество
		"SUMMA" => $basketItem->getFinalPrice(), // Сумма
		"CURRENCY" => $basketItem->getCurrency(),
		"WEIGHT" => $basketItem->getWeight(),     // Вес
		"NAME" => $basketItem->getField('NAME'),// Любое поле товара в корзине
		"CAN_BUY" => $basketItem->canBuy(),        // true, если доступно для покупки
		"DELAY" => $basketItem->isDelay(),       // true, если отложено
		"PICTURE" => $arPicture,
		"DETAIL_PAGE_URL" => $arProduct["DETAIL_PAGE_URL"],
		"PROPS" => $arProps
	);

	$arResult["PRICE"] = $arResult["PRICE"] + $arResult["BASKET"][$itemID]["SUMMA"];
	$arResult["WEIGHT"] = $arResult["WEIGHT"] + $arResult["BASKET"][$itemID]["WEIGHT"];


	// отформатируем CurrencyFormat($arResult["PRICE"], "RUB");
	$arResult["BASKET"][$itemID]["SUMMA_FORMAT"] = CurrencyFormat($arResult["BASKET"][$itemID]["SUMMA"], $arResult["BASKET"][$itemID]["CURRENCY"]);
	$arResult["BASKET"][$itemID]["PRICE_FORMAT"] = CurrencyFormat($arResult["BASKET"][$itemID]["PRICE"], $arResult["BASKET"][$itemID]["CURRENCY"]);
}

$arResult["PRICE_FORMATED"] = CurrencyFormat($arResult["PRICE"], $arResult['CURRENCY']);

// $arResult["BASKET_LIST"] = $basket->getListOfFormatText();

$this->IncludeComponentTemplate();
