<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main,
	Bitrix\Iblock,
	Bitrix\Catalog;

CModule::IncludeModule('iblock');
CModule::IncludeModule('sale');
CModule::IncludeModule('catalog');

// //формирование карточек по типам: BESTSELLER, NEWPRODUCT, DISCOUNT
// function createElementCard($blockName, $arParams, $IDS){
// 	global $USER;
//
// 	switch($blockName){
// 		case 'SALE':
// 			$prop2filter = "PROPERTY_DISCOUNT_VALUE";
// 			break;
// 		case 'NEW':
// 			$prop2filter = "PROPERTY_NEWPRODUCT_VALUE";
// 			break;
// 		case 'BESTSELLER':
// 			$prop2filter = "PROPERTY_BESTSELLER_VALUE";
// 			break;
// 	}
//
// 	$tempResult["TITLE"] = $arParams['TITLE'];
//
// 	$arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE",  "DETAIL_PAGE_URL", "CATALOG_GROUP_".$arParams['MAIN_'.$blockName.'_PRICE_TYPE'], "PROPERTY_".$arParams['MAIN_ARTICLE_FIELD'], "PROPERTY_NEWPRODUCT", "PROPERTY_DISCOUNT", "PROPERTY_BESTSELLER", "PROPERTY_ACTIONS", "PROPERTY_MAXIMUM_PRICE");
//
//
// 	$arFilter = Array(
// 		"IBLOCK_ID"=>(int)$arParams["IBLOCK_ID"],
// 		"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y",
// 		"ID" => $IDS
// 	);
//
// 	$res = CIBlockElement::GetList(Array("sort"=>"asc"), $arFilter, false, Array("nPageSize"=>20),$arSelect);
//
// 	while($ob = $res->GetNextElement()){
// 		$ar = $ob->GetFields();
// 		$ar["PROPERTY"] = $ob->GetProperties();
//
// printvar('', $ar);
//
// 		$arDiscounts = CCatalogDiscount::GetDiscountByProduct($ar["ID"], $USER->GetUserGroupArray(), "N");
// 		// найдем максимальную скидку
// 		$tmpDiscount = array();
// 		$maxDiscount = "";
// 		foreach ($arDiscounts as $arDiscount) {
// 				$tmpDiscount[] = intval($arDiscount["VALUE"]);
// 		}
// 		if(count($tmpDiscount)>0){
// 				$maxDiscount = max($tmpDiscount);
// 		}
//
// 		// get PRICE /////////////////////
// 		$Price = "";
// 		if($arParams["PRICE_TYPE"]>0){
// 			$Price = number_format ($ar["CATALOG_PRICE_".$arParams['PRICE_TYPE']], 0, '.', ' ' ).'р.';
// 			$typeItem = ""; // тип товара простой или с предложнеиями
//
//
// 			// if(!empty($ar["PREVIEW_PICTURE"])){
// 			// 	$rsFile = CFile::GetByID($ar["PREVIEW_PICTURE"]);
// 			// 	$arFile = $rsFile->Fetch();
// 			// }else{
// 			// 	$rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
// 			// 	$arFile = $rsFile->Fetch();
// 			// }
//
// 			if(!empty($arOffer["PREVIEW_PICTURE"])){
// 				$idFile = $arOffer["PREVIEW_PICTURE"];
// 			}elseif(!empty($arOffer["DETAIL_PICTURE"])){
// 				$idFile = $arOffer["DETAIL_PICTURE"];
// 			}elseif(!empty($ar["PREVIEW_PICTURE"])){
// 				$idFile = $ar["PREVIEW_PICTURE"];
// 			}elseif(!empty($ar["DETAIL_PICTURE"])){
// 				$idFile = $ar["DETAIL_PICTURE"];
// 			}
//
// 			if(CCatalogSKU::IsExistOffers($ar["ID"],(int)$arParams["IBLOCK_ID"])){
// 				$mxResult = CCatalogSKU::GetInfoByProductIBlock((int)$arParams["IBLOCK_ID"]);
//
// 				if (is_array($mxResult)){
//
// 					$rsOffers = CIBlockElement::GetList(
// 						array("CATALOG_PRICE_".$arParams['PRICE_TYPE']=>"DESC"),
// 						array('ACTIVE' => 'Y', 'IBLOCK_ID' => $mxResult["IBLOCK_ID"], 'PROPERTY_'.$mxResult['SKU_PROPERTY_ID'] => $ar["ID"]), //"PROPERTY_STATUS_PREDLOZHENIYA"=>STATUS_PREDLOZHENIYA,">CATALOG_QUANTITY"=>0
// 						false,
// 						false,
// 						array("ID","IBLOCK_ID","PRICE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "CATALOG_GROUP_".$arParams['PRICE_TYPE'],"PROPERTY_STATUS_PREDLOZHENIYA","CATALOG_QUANTITY"));
//
// 					$node = 1;
//
// 					$price = "";
// 					$priceFormat = "";
//
// 					while ($arOffer = $rsOffers->GetNext()){
//
// 						$Price_ID = $arOffer["CATALOG_PRICE_ID_".$arParams['PRICE_TYPE']];
//
//
// 						if($arOffer["PROPERTY_STATUS_PREDLOZHENIYA_ENUM_ID"] == STATUS_PREDLOZHENIYA and $arOffer["CATALOG_QUANTITY"]>0){
// 							$price = $arOffer["CATALOG_PRICE_".$arParams['PRICE_TYPE']];
// 							$priceFormat = CurrencyFormat($arOffer["CATALOG_PRICE_".$arParams['PRICE_TYPE']], $arOffer["CATALOG_CURRENCY_".$arParams['PRICE_TYPE']]);
// 							$Price_ID = $arOffer["CATALOG_PRICE_ID_".$arParams['PRICE_TYPE']];
// 							$typeItem = "offers";
// 							$itemID = $arOffer["ID"];
// 							break;
// 						}
// 					}
// 				}
// 			}else{
//
// 				 // товар простой и берем идентификатор ценового предложения из товара
// 				$price = $ar["CATALOG_PRICE_".$arParams['PRICE_TYPE']];
// 				if($price>0){
//
// 					$priceFormat = CurrencyFormat($ar["CATALOG_PRICE_".$arParams['PRICE_TYPE']], $ar["CATALOG_CURRENCY_".$arParams['PRICE_TYPE']]);
// 				}else{
// 					$priceFormat = "";
// 				}
// 				$Price_ID = $ar["CATALOG_PRICE_ID_".$arParams['PRICE_TYPE']];
// 				$typeItem = "simple";
// 				$itemID = $ar["ID"];
// 			}
// 		}
// 		// end get Price /////////////////////
//
// 		$tempResult["ITEMS"][$ar["ID"]] = array(
// 				"ID"                => $itemID,
// 				"NAME"              => $ar["NAME"],
// 				"DETAIL_PAGE_URL"   => $ar["DETAIL_PAGE_URL"],
// 				// "PICTURE"           => "/upload/".$arFile["SUBDIR"]."/".$arFile["FILE_NAME"],
// 				"PICTURE"           => CFile::GetPath($idFile),
// 				"PRICE"             => $price,
// 				"PRICE_FORMAT"      => $priceFormat,
// 				"PRICE_ID"          => $Price_ID,
// 				"NEWPRODUCT"		=> $ar["PROPERTY"]["NEWPRODUCT"]["VALUE"],
// 				"BESTSELLER"		=> $ar["PROPERTY"]["BESTSELLER"]["VALUE"],
// 				"DISCOUNT"			=> $ar["PROPERTY"]["DISCOUNT"]["VALUE"],
// 				"DISCOUNT_PERCENT"	=> $maxDiscount,
// 				"ACTIONS"			=> $ar["PROPERTY"]["ACTIONS"]["VALUE_XML_ID"],
// 				);
// 		unset($ar);
// 	}
// 	return $tempResult;
// }

function createElementCard($blockName, $arParams, $IDS){
	global $USER;

	switch($blockName){
		case 'SALE':
			$prop2filter = "PROPERTY_DISCOUNT_VALUE";
			break;
		case 'NEW':
			$prop2filter = "PROPERTY_NEWPRODUCT_VALUE";
			break;
		case 'BESTSELLER':
			$prop2filter = "PROPERTY_BESTSELLER_VALUE";
			break;
	}

	$tempResult["TITLE"] = $arParams['TITLE'];
	$arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE",  "DETAIL_PAGE_URL", "CATALOG_GROUP_".$arParams['PRICE_TYPE'], "PROPERTY_".$arParams['ARTICLE_FIELD'], "PROPERTY_NEWPRODUCT", "PROPERTY_DISCOUNT", "PROPERTY_BESTSELLER");
	$arFilter = Array(
		"IBLOCK_ID" => (int)$arParams["IBLOCK_ID"],
		"ACTIVE_DATE" => "Y",
		"ACTIVE"=>"Y",
		"ID" => $IDS
	);
	$res = CIBlockElement::GetList(Array("sort"=>"asc"), $arFilter, false, Array("nPageSize"=>20), $arSelect);

	while($ar = $res->GetNext()) {
		// get PRICE /////////////////////
		$Price = "";

		if($arParams["PRICE_TYPE"] > 0){
			$Price = number_format ($ar["CATALOG_PRICE_".$arParams["PRICE_TYPE"]], 0, '.', ' ' ).'р.';
			$typeItem = ""; // тип товара простой или с предложнеиями

			if(CCatalogSKU::IsExistOffers($ar["ID"],(int)$arParams["IBLOCK_ID"])){
				$mxResult = CCatalogSKU::GetInfoByProductIBlock((int)$arParams["IBLOCK_ID"]);
				if (is_array($mxResult))
				{
					$rsOffers = CIBlockElement::GetList(
						array(
							"CATALOG_PRICE_".$arParams['PRICE_TYPE'] => "ASC"
						),
						array(
							'ACTIVE' => 'Y',
							'IBLOCK_ID' => $mxResult["IBLOCK_ID"],
							'PROPERTY_'.$mxResult['SKU_PROPERTY_ID'] => $ar["ID"]
						),
						false,
						false,
						array(
							"ID",
							"IBLOCK_ID",
							"PRICE",
							"PREVIEW_PICTURE",
							"DETAIL_PICTURE",
							"CATALOG_GROUP_".$arParams['PRICE_TYPE']
						)
					);
					while ($arOffer = $rsOffers->GetNext())
					{
						$arDiscounts = CCatalogDiscount::GetDiscountByProduct($ar["ID"], $USER->GetUserGroupArray(), "N");
						// найдем максимальную скидку
						$tmpDiscount = array();
						$maxDiscount = "";
						foreach ($arDiscounts as $arDiscount) {
								$tmpDiscount[] = intval($arDiscount["VALUE"]);
						}
						if(count($tmpDiscount)>0){
								$maxDiscount = max($tmpDiscount);
						}

						if(!empty($arOffer["PREVIEW_PICTURE"])){
							$idFile = $arOffer["PREVIEW_PICTURE"];
						}elseif(!empty($arOffer["DETAIL_PICTURE"])){
							$idFile = $arOffer["DETAIL_PICTURE"];
						}elseif(!empty($ar["PREVIEW_PICTURE"])){
							$idFile = $ar["PREVIEW_PICTURE"];
						}elseif(!empty($ar["DETAIL_PICTURE"])){
							$idFile = $ar["DETAIL_PICTURE"];
						}

						$photoQuality = !empty($arParams['IMAGE_QUALITY']) ? $arParams['IMAGE_QUALITY'] : 90;
						$imageWidth = !empty($arParams['IMAGE_WIDTH']) ? $arParams['IMAGE_WIDTH'] : 300;
						$imageHeight = !empty($arParams['IMAGE_HEIGHT']) ? $arParams['IMAGE_HEIGHT'] : 300;

						$arFile = CFile::ResizeImageGet(
							$idFile,
							array('width'=>$imageWidth, 'height'=>$imageHeight),
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true,
							false,
							false,
							$photoQuality
						);

						$price = $arOffer["CATALOG_PRICE_".$arParams['PRICE_TYPE']];
						$priceFormat = CurrencyFormat($arOffer["CATALOG_PRICE_".$arParams['PRICE_TYPE']], $arOffer["CATALOG_CURRENCY_".$arParams['PRICE_TYPE']]);
						$Price_ID = $arOffer["CATALOG_PRICE_ID_".$arParams['PRICE_TYPE']];
						$typeItem = "offers";
						$itemID = $arOffer["ID"];
						break;
					}
				}
			}else{
				$arDiscounts = CCatalogDiscount::GetDiscountByProduct($ar["ID"], $USER->GetUserGroupArray(), "N");
				// найдем максимальную скидку
				$tmpDiscount = array();
				$maxDiscount = "";
				foreach ($arDiscounts as $arDiscount) {
						$tmpDiscount[] = intval($arDiscount["VALUE"]);
				}
				if(count($tmpDiscount)>0){
						$maxDiscount = max($tmpDiscount);
				}

				// картинка для простого товара
				if(!empty($ar["PREVIEW_PICTURE"])){
					$idFile = $ar["PREVIEW_PICTURE"];
				}else{
					$idFile = $ar["DETAIL_PICTURE"];
				}

				$photoQuality = !empty($arParams['IMAGE_QUALITY']) ? $arParams['IMAGE_QUALITY'] : 90;
				$imageWidth = !empty($arParams['IMAGE_WIDTH']) ? $arParams['IMAGE_WIDTH'] : 300;
				$imageHeight = !empty($arParams['IMAGE_HEIGHT']) ? $arParams['IMAGE_HEIGHT'] : 300;

				$arFile = CFile::ResizeImageGet(
					$idFile,
					array('width'=>$imageWidth, 'height'=>$imageHeight),
					BX_RESIZE_IMAGE_PROPORTIONAL,
					true,
					false,
					false,
					$photoQuality
				);

				 // товар простой и берем идентификатор ценового предложения из товара
				$price = $ar["CATALOG_PRICE_".$arParams['PRICE_TYPE']];
				$priceFormat = CurrencyFormat($ar["CATALOG_PRICE_".$arParams['PRICE_TYPE']], $ar["CATALOG_CURRENCY_".$arParams['PRICE_TYPE']]);
				$Price_ID = $ar["CATALOG_PRICE_ID_".$arParams['PRICE_TYPE']];
				$typeItem = "simple";
				$itemID = $ar["ID"];
			}
		}
		// end get Price /////////////////////
		$tempResult["ITEMS"][$ar['ID']] = array(
			"ID"                => $itemID,
			"NAME"              => $ar["NAME"],
			"ARTICLE"           => $ar["PROPERTY_".$arParams['ARTICLE_FIELD']."_VALUE"],
			"DETAIL_PAGE_URL"   => $ar["DETAIL_PAGE_URL"],
			"PICTURE"           => $arFile["src"],
			"PRICE"             => $price,
			"PRICE_FORMAT"      => $priceFormat,
			"PRICE_ID"          => $Price_ID,
			"NEWPRODUCT"	=> $ar["PROPERTY_NEWPRODUCT_VALUE"],
			"BESTSELLER"	=> $ar["PROPERTY_BESTSELLER_VALUE"],
			"DISCOUNT"		=> $ar["PROPERTY_DISCOUNT_VALUE"],
			"DISCOUNT_PERCENT"	=> $maxDiscount
		);
		unset($ar, $itemID, $arFile, $idFile, $price, $priceFormat, $Price_ID, $maxDiscount);
	}
	return $tempResult;
}

// Найдем ПРОСМОТРЕННЫЕ товары

$emptyProducts = array();
$siteId = $this->getSiteId();
$basketUserId = (int)CSaleBasket::GetBasketUserID(false);

$filter = array('=FUSER_ID' => $basketUserId, '=SITE_ID' => $siteId);

$viewedIterator = Catalog\CatalogViewedProductTable::getList(array(
	'select' => array('PRODUCT_ID', 'ELEMENT_ID', 'DATE_VISIT'),
	'filter' => $filter,
	'order' => array('DATE_VISIT' => 'DESC'),
	'limit' => 10
));

unset($filter);

while ($viewedProduct = $viewedIterator->fetch()){
	$arResult["VIEWED_IDS"][(int)$viewedProduct['ELEMENT_ID']] = (int)$viewedProduct['ELEMENT_ID'];
}

$arResult["VIEWED"] = createElementCard($blockName = 'VIEWED', $arParams, $arResult["VIEWED_IDS"]);

$this->IncludeComponentTemplate();
