<?php

AddEventHandler('catalog', 'OnSuccessCatalogImport1C', 'postPropcessImport1C');
function postPropcessImport1C($arParams, $path){
	// проверяем по файлу выгрузки (Обрабатываем только после файла выгрузки остатков - т.к. он последний в выгрузке )
	if ( strpos($path,'/rests_')===false ) return;

	global $DB;
	if(!CModule::IncludeModule("iblock")) die("iblock!!!");
	if(!CModule::IncludeModule("catalog")) die("catalog!!!");

	/**********************************************************************
	*** Параметры
	**********************************************************************/
	$OFFERS_IBLOCK_ID = 2;
	$PROP_NAME = "DATAVYGRUZKI"; // Символьный код "ДатаВыгрузки"
	$PROP_TABLE_NAME = 'b_iblock_element_prop_s2'; // Имя таблицы для хранения свойств в отдельной таблице
	$PROP_TABLE_NAME_PROD = 'b_iblock_element_prop_s1'; // Имя таблицы для хранения свойств в отдельной таблице
	/*********************************************************************/

	/**********************************************************************
	*** Опции интеграции 1С
	**********************************************************************/
	//Что делать с товарами, отсутствующими в файле импорта: "N" - ничего; "A" - деактивировать; "D" - удалить
	$mode_element = COption::GetOptionString("catalog", "1C_ELEMENT_ACTION");
	// Что делать с группами, отсутствующими в файле импорта: "N" - ничего; "A" - деактивировать; "D" - удалить
	$mode_section = COption::GetOptionString("catalog", "1C_SECTION_ACTION");
	// Что делать с товаром, если все торговые предложения деактивированы?: деактивировать - deactivate; Ничего не делать - nothing
	$prod_deactivated = COption::GetOptionString("uvelirsoft", "CATALOG_UPDATE_REST_OFFER_PRODUCT");

	// SKU_PROPERTY_ID
	$offer = CCatalogSKU::GetInfoByOfferIBlock($OFFERS_IBLOCK_ID);
	if (!$offer) return;

	// ИБ предложения
	//Определяем ID свойства "ДатаВыгрузки"
	$res = CIBlock::GetProperties($OFFERS_IBLOCK_ID, Array(), Array("CODE"=>$PROP_NAME));
	if($res_arr = $res->Fetch()) {
		$OFFERS_PROP_ID = $res_arr["ID"]; // ID свойства предложения "ДатаВыгрузки";
	}
	$arOffersProp = array(
		'ID' 		=> $OFFERS_PROP_ID,
		'CODE'		=> 'PROPERTY_'.$PROP_NAME,
		'CODE_VALUE'=> 'PROPERTY_'.$PROP_NAME.'_VALUE',
		'IBLOCK_ID' => $OFFERS_IBLOCK_ID,
		'TNAME' 	=> ( $offer["VERSION"]==2 ? $PROP_TABLE_NAME : ''), // Имя таблицы для хранения свойств в отдельной таблице
		'CNAME_PROP' 	=> ( $offer["VERSION"]==2 ? 'PROPERTY_'.$OFFERS_PROP_ID : ''),// Поле со свойством ДатаВыгрузки в отдельной таблице
		'CNAME_SKU' 	=> ( $offer["VERSION"]==2 ? 'PROPERTY_'.$offer["SKU_PROPERTY_ID"]  : ''),// Поле со свойством "ID товара" в отдельной таблице
	);

	// ИБ товары
	$resIblock = CIblock::GetList(
		array(),
		array('ID' => $offer['PRODUCT_IBLOCK_ID'])
	);
	$arIblock = $resIblock->Fetch();

	//Определяем ID свойства "ДатаВыгрузки"
	$res = CIBlock::GetProperties($offer['PRODUCT_IBLOCK_ID'], Array(), Array("CODE"=>$PROP_NAME));
	if($res_arr = $res->Fetch()) {
		$PROP_ID = $res_arr["ID"]; // ID свойства товара "ДатаВыгрузки";
	}
	if ( $PROP_ID ) {
		$arProp = array(
			'ID' 		=> $PROP_ID,
			'CODE'		=> 'PROPERTY_'.$PROP_NAME,
			'CODE_VALUE'=> 'PROPERTY_'.$PROP_NAME.'_VALUE',
			'IBLOCK_ID' => $offer['PRODUCT_IBLOCK_ID'],
			'TNAME' => ( $arIblock["VERSION"]==2 ? $PROP_TABLE_NAME_PROD : ''), // Имя таблицы для хранения свойств в отдельной таблице
			'CNAME_PROP'=> ( $offer["VERSION"]==2 ? 'PROPERTY_'.$PROP_ID : ''),// Поле со свойством ДатаВыгрузки в отдельной таблице
		);
	}

	// найдем идентификатор последнего запуска
	if ( isset($arProp["CODE"] ) ) {
		$arSelect = Array("ID", "IBLOCK_ID", $arProp["CODE"]);
		$arFilter = Array("IBLOCK_ID" => $arProp["IBLOCK_ID"]);
		$res = CIBlockElement::GetList(Array($arProp["CODE"] => "DESC"), $arFilter, false, Array("nPageSize" => 1), $arSelect);
		$ob = $res->Fetch();
		$DATE_V = $ob[$arProp['CODE_VALUE']];
	} else {
		$arSelect = Array("ID", "IBLOCK_ID", $arOffersProp["CODE"]);
		$arFilter = Array("IBLOCK_ID" => $arOffersProp["IBLOCK_ID"]);
		$res = CIBlockElement::GetList(Array($arOffersProp["CODE"] => "DESC"), $arFilter, false, Array("nPageSize" => 1), $arSelect);
		$ob = $res->Fetch();
		$DATE_V = $ob[$arOffersProp['CODE_VALUE']];
	}

	if ( !$DATE_V ) return;


	/*
	*** деактивация отсутствующих предложений (всех, не зависимо от товаров в выгрузке ) 
	*/


	if ( $offer["VERSION"]==1 ) { // при хранении свойств в общей таблице

		$sql = "UPDATE b_iblock_element e SET e.ACTIVE = 'N'
				WHERE e.IBLOCK_ID = '$arOffersProp[IBLOCK_ID]' AND e.active = 'Y'
    			  AND NOT EXISTS(
    			  	SELECT p2.IBLOCK_ELEMENT_ID FROM b_iblock_element_property p2
    			  	WHERE p2.IBLOCK_ELEMENT_ID=e.ID AND p2.IBLOCK_PROPERTY_ID = $arOffersProp[ID] AND p2.VALUE = '$DATE_V' )
			";


	} else { //  при хранении свойств в отдельной таблице
		$sql = "UPDATE b_iblock_element e SET e.ACTIVE = 'N'
			WHERE e.IBLOCK_ID='$arOffersProp[IBLOCK_ID]'
				AND NOT EXISTS(
					SELECT p2.IBLOCK_ELEMENT_ID
					FROM {$arOffersProp[TNAME]} p2
					WHERE p2.IBLOCK_ELEMENT_ID=e.ID AND p2.{$arOffersProp[CNAME_PROP]}='$DATE_V'
				)
			";

	}

	$DB->Query($sql, false, $err_mess.__LINE__);


	// Меняем доступность товара при отсутствии активных предложений

	$product_type = 3; // тип товара (3 - товар с предложениями)
	if ( $offer["VERSION"]==1 ) { // при хранении свойств в общей таблице

		$sql = "UPDATE b_catalog_product c SET c.AVAILABLE = 'N'
			WHERE c.TYPE = $product_type AND c.AVAILABLE='Y'
				AND EXISTS(
					SELECT 1 FROM b_iblock_element e2
					WHERE e2.id=c.id AND e2.IBLOCK_ID = '$arProp[IBLOCK_ID]' AND e2.active = 'Y'
				)
				AND NOT EXISTS(
					SELECT 1 FROM b_iblock_element e, b_iblock_element_property prod
					WHERE e.id=c.id AND e.active = 'Y'
						AND prod.VALUE=e.id AND prod.IBLOCK_PROPERTY_ID = $offer[SKU_PROPERTY_ID]
						AND EXISTS(
							SELECT 1 FROM b_iblock_element e1
							WHERE e1.id = prod.IBLOCK_ELEMENT_ID AND e1.active='Y'
						)
				)
			";

	} else { //  при хранении свойств в отдельной таблице

		$sql = "UPDATE b_catalog_product c SET c.AVAILABLE = 'N'
			WHERE c.TYPE = $product_type AND c.AVAILABLE='Y'
				AND EXISTS(
					SELECT 1 FROM b_iblock_element e2
					WHERE e2.id=c.id AND e2.IBLOCK_ID = '$arProp[IBLOCK_ID]' AND e2.active = 'Y'
				)
				AND NOT EXISTS(
					SELECT 1 FROM b_iblock_element e, {$arOffersProp[TNAME]} prod
					WHERE e.id=c.id AND e.active = 'Y'
						AND prod.{$arOffersProp[CNAME_SKU]}=e.id
						AND EXISTS(
							SELECT 1 FROM b_iblock_element e1
							WHERE e1.id = prod.IBLOCK_ELEMENT_ID AND e1.active='Y'
						)
				)
			";

	}

	$DB->Query($sql, false, $err_mess.__LINE__);

	// Очистка композитного кеша при выгрузке
	$staticHtmlCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
	$staticHtmlCache->deleteAll();

	// пересоздаем фасетный индекс
	/*
	Bitrix\Iblock\PropertyIndex\Manager::DeleteIndex($arProp["IBLOCK_ID"]);
	Bitrix\Iblock\PropertyIndex\Manager::markAsInvalid($arProp["IBLOCK_ID"]);
	$index = \Bitrix\Iblock\PropertyIndex\Manager::createIndexer($arProp["IBLOCK_ID"]);
	$index->startIndex();
	$index->continueIndex(0); // создание без ограничения по времени
	$index->endIndex();
	*/

}


AddEventHandler("main", "OnBeforeUserRegister", Array("UserRegistration", "OnBeforeUserRegisterHandler"));
AddEventHandler("main", "OnBeforeUserAdd", Array("UserRegistration", "OnBeforeUserAddHandler"));
class UserRegistration
{
	function OnBeforeUserRegisterHandler(&$arFields){
          $arFields["LOGIN"] = $arFields["EMAIL"];
    }

	function OnBeforeUserAddHandler(&$arFields){
		if(
			(isset($_REQUEST['action']) && $_REQUEST['action'] == 'saveOrderAjax')
			|| (isset($_REQUEST['soa-action']) && $_REQUEST['soa-action'] == 'saveOrderAjax')
		){
			if(!empty(userProp_personalAgreement) && !empty(orderPropID_personalAgreement)){

				$propCode = "ORDER_PROP_".orderPropID_personalAgreement;

				$arFields[userProp_personalAgreement] = ($_REQUEST[$propCode] == 'Y' ? 1 : 0);
			}
		}
	}
}


AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("CatalogItemPrice", "DoIBlockAfterSave") );
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("CatalogItemPrice", "DoIBlockAfterSave") );
AddEventHandler("catalog", "OnPriceAdd", Array("CatalogItemPrice", "DoIBlockAfterSave") );
AddEventHandler("catalog", "OnPriceUpdate", Array("CatalogItemPrice", "DoIBlockAfterSave") );

class CatalogItemPrice
{
	function DoIBlockAfterSave($arg1, $arg2 = false) {
		$ELEMENT_ID = false;
		$IBLOCK_ID = false;
		$OFFERS_IBLOCK_ID = false;
		$OFFERS_PROPERTY_ID = false;
		if(CModule::IncludeModule('currency'))
			$strDefaultCurrency = CCurrency::GetBaseCurrency();

		if(is_array($arg2) && $arg2["PRODUCT_ID"] > 0) {
			$rsPriceElement = CIBlockElement::GetList(
				array(),
				array(
					"ID" => $arg2["PRODUCT_ID"],
				),
				false,
				false,
				array("ID", "IBLOCK_ID")
			);
			if($arPriceElement = $rsPriceElement->Fetch()) {
				$arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
				if(is_array($arCatalog)) {
					if($arCatalog["OFFERS"] == "Y") {
						$rsElement = CIBlockElement::GetProperty(
							$arPriceElement["IBLOCK_ID"],
							$arPriceElement["ID"],
							"sort",
							"asc",
							array("ID" => $arCatalog["SKU_PROPERTY_ID"])
						);
						$arElement = $rsElement->Fetch();
						if($arElement && $arElement["VALUE"] > 0) {
							$ELEMENT_ID = $arElement["VALUE"];
							$IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
							$OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
							$OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
						}
					} elseif($arCatalog["OFFERS_IBLOCK_ID"] > 0) {
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
						$OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
					} else {
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = false;
						$OFFERS_PROPERTY_ID = false;
					}
				}
			}
		} elseif(is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0) {
			$arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
			if(is_array($arOffers)) {
				$ELEMENT_ID = $arg1["ID"];
				$IBLOCK_ID = $arg1["IBLOCK_ID"];
				$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
				$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
			}
		}

		if($ELEMENT_ID) {
			static $arPropCache = array();
			if(!array_key_exists($IBLOCK_ID, $arPropCache)) {
				$rsProperty = CIBlockProperty::GetByID("MINIMUM_PRICE", $IBLOCK_ID);
				$arProperty = $rsProperty->Fetch();
				if($arProperty)
					$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
				else
					$arPropCache[$IBLOCK_ID] = false;
			}

			if($arPropCache[$IBLOCK_ID]) {
				$arActions=array();
				if($OFFERS_IBLOCK_ID) {
					$rsOffers = CIBlockElement::GetList(
						array(),
						array(
							"IBLOCK_ID" => $OFFERS_IBLOCK_ID,
							"PROPERTY_".$OFFERS_PROPERTY_ID => $ELEMENT_ID,
							"CATALOG_AVAILABLE" => "Y",
	                        "ACTIVE" => "Y"
						),
						false,
						false,
						array("ID","PROPERTY_AKTSIYA_AKTIVNA","PROPERTY_AKTSIYA","PROPERTY_STARAYA_TSENA")
					);
					while($arOffer = $rsOffers->Fetch()) {
						$arProductID[] = $arOffer["ID"];
						// акционные предложения (с минимальной ценой)
						if ( $arOffer["PROPERTY_AKTSIYA_AKTIVNA_VALUE"]=="Да" ) {
							$arActions[$arOffer["ID"]]=array("AKTSIYA"=>$arOffer["PROPERTY_AKTSIYA_VALUE"],"STARAYA_TSENA"=>$arOffer["PROPERTY_STARAYA_TSENA_VALUE"]);
						}
					}

					if(!is_array($arProductID))
						$arProductID = array($ELEMENT_ID);
				} else
					$arProductID = array($ELEMENT_ID);

				$hasPicture = false;
				$rsElement = CIBlockElement::GetList(
					array('SORT' => 'ASC'),
					array(
						'ACTIVE' => 'Y',
						'=ID' => $ELEMENT_ID
					),
					false,
					false,
					array('ID','PREVIEW_PICTURE')
				);
				if($arElement = $rsElement->fetch()){
					if($arElement['PREVIEW_PICTURE']){
						$hasPicture = true;
					}
				}


				$minPrice = false;
				$arAction = array();
				// $minQuantity = false;
				$rsPrices = CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $arProductID,
					)
				);
				while($arPrice = $rsPrices->Fetch()) {
					if(CModule::IncludeModule('currency') && $strDefaultCurrency != $arPrice['CURRENCY'])
						$arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $strDefaultCurrency);

					$PRICE = $arPrice["PRICE"];
					// $ar_res = CCatalogProduct::GetByID($arPrice["PRODUCT_ID"]);
					// $QUANTITY = $ar_res["QUANTITY"];

					if($minPrice === false || $minPrice > $PRICE) {
						$minPrice = $PRICE;
						// $minQuantity = $QUANTITY;
					}

					if( isset($arActions[$arPrice["PRODUCT_ID"]]) && ( count($arAction)==0 || $arAction['PRICE'] > $PRICE) ) {
						$arAction['PRICE']=$PRICE;
						$arAction['AKTSIYA']=$arActions[$arPrice["PRODUCT_ID"]]["AKTSIYA"];
						$arAction['STARAYA_TSENA']=$arActions[$arPrice["PRODUCT_ID"]]["STARAYA_TSENA"];
					}

				}

				if($minPrice !== false) {
					$arProps=array(
						"MINIMUM_PRICE" => $minPrice,
						"HAS_PICTURE" => ($hasPicture ? 20017 : false),
					);
					if ( isset($arAction["AKTSIYA"]) ) {
						$arProps["MIN_ACTION_NAME"] =$arAction["AKTSIYA"];
						$arProps["MIN_ACTION_PRICE"] =$arAction["PRICE"];
						$arProps["MIN_ACTION_OLD_PRICE"] =$arAction["STARAYA_TSENA"];
					} else {
						$arProps["MIN_ACTION_NAME"] = '';
						$arProps["MIN_ACTION_PRICE"] =false;
						$arProps["MIN_ACTION_OLD_PRICE"] =false;
					}
					CIBlockElement::SetPropertyValuesEx(
						$ELEMENT_ID,
						$IBLOCK_ID,
						$arProps
					);

					// CCatalogProduct::Update(
					// 	$ELEMENT_ID,
					// 	array(
					// 		"QUANTITY" => $minQuantity
					// 	)
					// );
				}
			}
		}
	}
}

//обработчик письма
AddEventHandler("sale", "OnOrderNewSendEmail", "OnOrderNewSendEmailHandler");
function OnOrderNewSendEmailHandler($ORDER_ID,&$eventName,&$arFields){

	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");

	// получаем название службы доставки
	if($arOrder = CSaleOrder::GetByID($ORDER_ID)){
		$arFields['ORDER_COMMENT'] = $arOrder['USER_DESCRIPTION'];
		if($arDelivery = CSaleDelivery::GetByID($arOrder['DELIVERY_ID'])){
			$arFields['DELIVERY'] = $arDelivery['NAME'];
		}elseif($arDelivery = CSaleDeliveryHandler::GetBySID($arOrder['DELIVERY_ID'])){
			if ($deliv = $arDelivery->GetNext())
			{
				$arFields['DELIVERY'] = $deliv['NAME'];
			}
		}
		$arFields['DELIVERY_PRICE'] = number_format($arOrder['PRICE_DELIVERY'], 0, ',', ' ')." руб.";
	}
	//


	$arOrder['BASKET_ITEMS'] = getProductListInfo($ORDER_ID);

	if($arOrder['BASKET_ITEMS']){
		$arFields['ORDER_LIST'] = "
			<table class=\"order_list_table\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"text-align: center; vertical-align: middle;border:1px solid #000;\">
				<thead>
					<tr>
						<td style=\"border:1px solid; width:120px; \">
						</td>
						<td style=\"border:1px solid; \">
							Изделие
						</td>
						<td style=\"border:1px solid; width:100px;\">
							Цена
						</td>
						<td style=\"border:1px solid; width:40px;\">
							Кол.
						</td>
						<td style=\"border:1px solid; width:100px;\">
							Сумма
						</td>
					</tr>
					</thead>
					<tbody>";
		foreach($arOrder['BASKET_ITEMS'] as $basket){
			$arFields['ORDER_LIST'] .= "
				<tr style=\"border:1px solid;\">
					<td style=\"border:1px solid;\">
						<img width=\"100px\" alt='".$basket['NAME']."' src='".$basket['PICTURE']."'>
					</td>
					<td style=\"border:1px solid; padding: 0 5px; text-align: left;\">
						<b>".$basket['NAME']."</b>
						<br>
						";
						foreach($basket['PROPS'] as $prop){
							if(!empty($prop['VALUE'])){
								$arFields['ORDER_LIST'] .= $prop['NAME'].": ".$prop['VALUE']."<br>";
							}
						}
			$arFields['ORDER_LIST'] .= "
					</td>
					<td style=\"border:1px solid;\">
						".$basket['PRICE']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['QUANTITY']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['SUM']."
					</td>
				</tr>";
		}
		$arFields['ORDER_LIST'] .= "
				</tbody>
			</table>";
	}
}

function OnOrderNewSendEmailHandler_20172508($ORDER_ID,&$eventName,&$arFields){
	$ORDER_ID = $arFields['ORDER_ID'];

	$basketList = CSaleBasket::GetList(array(),array('ORDER_ID'=>$ORDER_ID));

	//
	// получаем название службы доставки
	if($arOrder = CSaleOrder::GetByID($ORDER_ID)){
		$arFields['ORDER_COMMENT'] = $arOrder['USER_DESCRIPTION'];
		if($arDelivery = CSaleDelivery::GetByID($arOrder['DELIVERY_ID'])){
			$arFields['DELIVERY'] = $arDelivery['NAME'];
		}elseif($arDelivery = CSaleDeliveryHandler::GetBySID($arOrder['DELIVERY_ID'])){
			if ($deliv = $arDelivery->GetNext())
			{
				$arFields['DELIVERY'] = $deliv['NAME'];
			}
		}
		$arFields['DELIVERY_PRICE'] = number_format($arOrder['PRICE_DELIVERY'], 0, ',', ' ')." руб.";
	}
	//

	$arBasket = array();
	while($arRes = $basketList->GetNext()){
		$arBasketEl = array();
		$arOffers = CIBlockElement::GetByID($arRes['PRODUCT_ID']);
		if($offersEl = $arOffers->Fetch()){
			// получаем цену товара
			$urPrice = CPrice::GetList(
				array(),
				array(
					'PRODUCT_ID' => $offersEl['ID']
				)
			);
			if($arPrice = $urPrice->Fetch()){
				$price = number_format($arPrice['PRICE'], 0, ',', ' ')." р.";
				$sum =  number_format($arPrice['PRICE'] * $arRes['QUANTITY'], 0, ',', ' ')." р.";
			}

			$mxResult = CCatalogSku::GetProductInfo($offersEl['ID']);
			$tovar = CIBlockElement::GetByID($mxResult['ID']);
			if($arTovar = $tovar->GetNext()){
				// название товара
				$name = $arTovar['NAME'];

				// путь к картинке
				$temp = !empty($arTovar['PREVIEW_PICTURE']) ? $arTovar['PREVIEW_PICTURE'] : $arTovar['DETAIL_PICTURE'];
				$picture = CFile::GetPath($temp);
			}

			// получаем артикул товара
			$articul = "";
			$arTovarProps = CIBlockElement::GetProperty(
				$mxResult['IBLOCK_ID'],
				$mxResult['ID'],
				array("sort"=>"asc"),
				array()
			);
			while ($ob = $arTovarProps->GetNext())
			{
				if($ob['CODE'] == 'ARTNUMBER'){
					$articul = $ob['VALUE'];
				}
			}

			// получаем свойства торгового предложения
			$props = CIBlockElement::GetProperty(
				$offersEl['IBLOCK_ID'],
				$offersEl['ID'],
				array("sort"=>"asc"),
				array()
			);
			$arProps = array();
			$fl = false;
			$arProdVstavka = array();
			$prodVstavka = '';
			while ($ob = $props->GetNext())
			{
				if($ob['CODE'] == 'VSTAVKA'){
					$arProdVstavka[] = $ob['VALUE_ENUM'];
					$fl = true;
				}else{
					$prodVstavka = $ob['VALUE_ENUM'];
					$fl = false;
				}

				$arProps[$ob['CODE']] = array(
					'ID' => $ob['ID'],
					'NAME' => $ob['NAME'],
					'VALUE' => $fl ? implode('<br>', $arProdVstavka) : $prodVstavka
				);
			}
		}

		$arBasket[] = array(
			'NAME' => $name,
			'QUANTITY' => $arRes['QUANTITY'],
			'PICTURE' => $picture,
			'PROPS' => $arProps,
			'PRICE' => $price,
			'SUM' => $sum,
			'ARTICUL' => $articul
		);
	}

	if($arBasket){
		$arFields['ORDER_LIST'] = "
			<table class=\"order_list_table\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"text-align: center; vertical-align: middle;border:1px solid #000;\">
				<thead>
					<tr>
						<td style=\"border:1px solid; width:120px; \">
						</td>
						<td style=\"border:1px solid; \">
							Изделие
						</td>
						<td style=\"border:1px solid; width:100px;\">
							Цена
						</td>
						<td style=\"border:1px solid; width:40px;\">
							Кол.
						</td>
						<td style=\"border:1px solid; width:100px;\">
							Сумма
						</td>
					</tr>
					</thead>
					<tbody>";
		foreach($arBasket as $basket){
			$arFields['ORDER_LIST'] .= "
				<tr style=\"border:1px solid;\">
					<td style=\"border:1px solid;\">
						<img width=\"100px\" alt=".$basket['NAME']." src=".$basket['PICTURE'].">
					</td>
					<td style=\"border:1px solid; padding: 0 5px; text-align: left;\">
						<b>".$basket['NAME']."</b>
						<br>
						Артикул: ".$basket['ARTICUL']."
						<br>
						Вставка: ".$basket['PROPS']['VSTAVKA']['VALUE']."
						<br>
						Размер: ".$basket['PROPS']['RAZMER']['VALUE']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['PRICE']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['QUANTITY']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['SUM']."
					</td>
				</tr>";
		}
		$arFields['ORDER_LIST'] .= "
				</tbody>
			</table>";
	}
/*
	if($arBasket){
		$arFields['ORDER_LIST'] = "
			<table class=\"order_list_table\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" style=\"text-align: center; vertical-align: middle;\">
				<thead>
					<tr>
						<td style=\"border:1px solid;\" colspan=\"2\">
						</td>
						<td style=\"border:1px solid;\">
							Артикул
						</td>
						<td style=\"border:1px solid;\">
							Вставка
						</td>
						<td style=\"border:1px solid;\">
							Размер
						</td>
						<td style=\"border:1px solid;\">
							Цена
						</td>
						<td style=\"border:1px solid;\">
							Кол.
						</td>
						<td style=\"border:1px solid;\">
							Сумма
						</td>
					</tr>
					</thead>
					<tbody>";
		foreach($arBasket as $basket){
			$arFields['ORDER_LIST'] .= "
				<tr style=\"border:1px solid;\">
					<td style=\"border:1px solid;\">
						<img width=\"100px\" alt=".$basket['NAME']." src=".$basket['PICTURE'].">
					</td>
					<td style=\"border:1px solid;\">
						".$basket['NAME']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['ARTICUL']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['PROPS']['VSTAVKA']['VALUE']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['PROPS']['RAZMER']['VALUE']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['PRICE']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['QUANTITY']."
					</td>
					<td style=\"border:1px solid;\">
						".$basket['SUM']."
					</td>
				</tr>";
		}
		$arFields['ORDER_LIST'] .= "
				</tbody>
			</table>";
	}
*/
}

/*
Поиск по заголовкам (добавление свойства к заголовку для создания индекса)
https://dev.1c-bitrix.ru/api_help/search/events/beforeindex.php
*/
AddEventHandler("search", "BeforeIndex", "USBeforeIndexHandler");
function USBeforeIndexHandler($arFields)
{

	if(!CModule::IncludeModule("iblock")) // подключаем модуль
    	return $arFields;
	if($arFields["MODULE_ID"] == "iblock") {
		$db_props = CIBlockElement::GetProperty( // Запросим свойства индексируемого элемента
				$arFields["PARAM2"],        // BLOCK_ID индексируемого свойства
				$arFields["ITEM_ID"],       // ID индексируемого свойства
				Array("sort" => "asc"),     // Сортировка (можно упустить)
				Array("CODE"=>"ARTNUMBER")	// CODE свойства (в данном случае артикул)
			);
		if($ar_props = $db_props->Fetch()){
			if(!empty($ar_props["VALUE"])){
				$arFields["TITLE"] .= " (Арт.".$ar_props["VALUE"].")";   // Добавим свойство в конец заголовка индексируемого элемента
			}
		}
	}
	return $arFields;
}

/*
 	События по изменению веса (исправление глюка стандартной конфигурации)
*/
AddEventHandler("catalog", "OnBeforeProductUpdate", "FillTheWeightUpdate");

function FillTheWeightUpdate($id, &$arFields){

	if($arFields["QUANTITY"]){ // событие срабатывает несколько раз и чтобы сократить кол-во  добавлено это условие
		$arItem = CIBlockElement::GetList( false, array( 'ID' => $id ), false, false, array( 'ID', 'PROPERTY_VES' ) )->fetch();
		if( $arItem['PROPERTY_VES_VALUE'] ){
			$arFields["WEIGHT"] = $arItem['PROPERTY_VES_VALUE'];
		}
	}
}

AddEventHandler("catalog", "OnBeforeProductAdd", "FillTheWeightAdd");

function FillTheWeightAdd(&$arFields){
	$arItem = CIBlockElement::GetList( false, array( 'ID' => $arFields["ID"]), false, false, array( 'ID', 'PROPERTY_VES' ) )->fetch();
	if( $arItem['PROPERTY_VES_VALUE'] ){
		$arFields["WEIGHT"] = $arItem['PROPERTY_VES_VALUE'];
	}
}


AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandler");
//AddEventHandler("iblock", "OnStartIBlockElementAdd",  "OnBeforeIBlockElementUpdateHandler");
AddEventHandler("iblock", "OnAfterIBlockElementAdd",  "OnBeforeIBlockElementAddHandler");

function OnBeforeIBlockElementAddHandler(&$arFields){
	if($arFields['IBLOCK_ID'] == 1){
		$el = new CIBlockElement;
		$arLoadProductArray = Array(
			"NAME" => $arFields["NAME"],
			"CODE" => $arFields["CODE"],
		);
		$PRODUCT_ID = $arFields['ID'];  // изменяем элемент с кодом (ID) 2
		$el->Update($PRODUCT_ID, $arLoadProductArray);
	}


}

function OnBeforeIBlockElementUpdateHandler(&$arFields){
	if($arFields['IBLOCK_ID'] == 1){
		$arParams = array("replace_space"=>"-","replace_other"=>"-");
		$transliter = Cutil::translit($arFields['NAME']."-".$arFields['ID'], "ru", $arParams);
		$arFields["CODE"] = $transliter;
	}
}

//создаем обработчик события "OnBeforeIBlockElementUpdate"
/*
function OnBeforeIBlockElementUpdateHandler(&$arFields)
{
	// Замена "_" на "-" в символьном коде элемента каталога
	if($arFields["CODE"] != ""){
		return;
	}
	if($arFields['IBLOCK_ID'] == 1){
		
		//var_dump($arFields);
		$arParams = array("replace_space"=>"-","replace_other"=>"-");
		$transliter = Cutil::translit($arFields['NAME'], "ru", $arParams);

		$arSelect = Array("ID", "NAME", "CODE");
		$arFilter = Array("IBLOCK_ID"=> 1, "NAME"=> $arFields['NAME']);
		$res = CIBlockElement::GetList(Array("CODE"=>"DESC"), $arFilter, false, false, $arSelect);
		
		while($ob = $res->Fetch()){
			$codes = explode("-", $ob['CODE']);			
			$counter = $codes[count($codes) - 1];
			$arraymas[$ob['ID']] = $counter;
			$arOb[$ob['ID']] = $ob;
			//var_dump($ob['CODE']);
			
		}
		arsort($arraymas);
		$i = 0;
		foreach($arraymas as $key2=>$masitem2){
			$arFirstEl[] = $masitem2;
			$i++;
			if($i > 2)
				break;
		}
		$j = 0;
		foreach($arraymas as $key=>$masitem){
			$first_el = $key;
			$co = explode("-", $transliter);
			$cou = $co[count($co) - 1];
					
			if($masitem != $cou){
				if($j > 0 && $masitem ==""){
					foreach($arraymas as $key1=>$masitem1){
						$first_el = $key1;
							break;
				}
				break;
				}
				else{
					break;
				}

			}
			else{
				if($arFirstEl[0] == $cou && $arFirstEl[1] == $cou-1 || $arFirstEl[1] == $cou){
					$first_el = $key;
					$moreCour = true;
					break;
				}
			}
			$j++;
		}
		$first_el = $arOb[$first_el];
		
		
		$ob = $first_el;
		$found = false;
		if($ob)
			{
				if($ob['CODE'] == ""){
					//$found = true;
				}
				else{
					$codes = explode("-", $ob['CODE']);
				
					$counter = $codes[count($codes) - 1];
					$arParams = array("replace_space"=>"-","replace_other"=>"-");
					
					if($counter > 0){
						$counter = $counter + 1;
						
						if($transliter == $ob['CODE']){
							if($moreCour){
								$counter = $counter + 1;
								// var_dump($counter );
								// die();
							}
							else{
								$counter = 1;
							}
						}
	
						$arFields['CODE'] = Cutil::translit($arFields['NAME'].'-'.$counter, "ru", $arParams);
					}
					else{
						if($moreCour){
							$arFields['CODE'] = Cutil::translit($arFields['NAME'].'-'.$counter + 1, "ru", $arParams);
						}
						else
							$arFields['CODE'] = Cutil::translit($arFields['NAME'].'-1', "ru", $arParams);
					}
					
					$found = true;
				}
				
				//break;
			}

			if(!$found){
				
				$arParams = array("replace_space"=>"-","replace_other"=>"-");
				$arFields['CODE'] = Cutil::translit($arFields['NAME'], "ru", $arParams);
				// foreach($arOb as $item){
				// 	if($item['CODE'] == $arFields['CODE']){
				// 		$arFields['CODE'] = $arFields['CODE']."-1";
				// 		break;
				// 	}
				// }
			}	
			//var_dump($arFields['CODE']);
			//die();

	}
	
	//die();
}   
*/

