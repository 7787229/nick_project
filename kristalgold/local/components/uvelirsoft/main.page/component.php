<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('iblock');
CModule::IncludeModule('sale');

//формирование карточек по типам: SLIDER #1, SLIDER #2, SLIDER #3
function createElementCard($blockName, $sliderCode, $arParams){
	global $USER;

	$prop2filter = "PROPERTY_".$blockName."_VALUE";

	$tempResult["TITLE"] = $arParams['MAIN_'.$sliderCode.'_TITLE'];
	$arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE",  "DETAIL_PAGE_URL", "CATALOG_GROUP_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE'], "PROPERTY_".$arParams['MAIN_ARTICLE_FIELD'], "PROPERTY_NEWPRODUCT", "PROPERTY_DISCOUNT", "PROPERTY_BESTSELLER");
	$arFilter = Array(
		"IBLOCK_ID"=>(int)$arParams["MAIN_".$sliderCode."_IBLOCK_ID"],
		"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y",
		$prop2filter=>$arParams["MAIN_".$sliderCode."_FIELD_VALUE"]
	);
	$nPageSize = (!empty($arParams['MAIN_'.$sliderCode.'_COUNT']) ? $arParams['MAIN_'.$sliderCode.'_COUNT'] : 10);
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=> $nPageSize),$arSelect);

	while($ar = $res->GetNext()) {

		// get PRICE /////////////////////
		$Price = "";

		if($arParams["MAIN_".$sliderCode."_PRICE_TYPE"]>0){
			$Price = number_format ($ar["CATALOG_PRICE_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']], 0, '.', ' ' ).'р.';
			$typeItem = ""; // тип товара простой или с предложнеиями

			if(CCatalogSKU::IsExistOffers($ar["ID"],(int)$arParams["MAIN_".$sliderCode."_IBLOCK_ID"])){
				$mxResult = CCatalogSKU::GetInfoByProductIBlock((int)$arParams["MAIN_".$sliderCode."_IBLOCK_ID"]);
				if (is_array($mxResult))
				{
					$rsOffers = CIBlockElement::GetList(
						array(
							"CATALOG_PRICE_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']=>"ASC"
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
							"CATALOG_GROUP_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']
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

						$photoQuality = !empty($arParams['MAIN_'.$sliderCode.'_IMAGE_QUALITY']) ? $arParams['MAIN_'.$sliderCode.'_IMAGE_QUALITY'] : 90;
						$imageWidth = !empty($arParams['MAIN_'.$sliderCode.'_IMAGE_WIDTH']) ? $arParams['MAIN_'.$sliderCode.'_IMAGE_WIDTH'] : 300;
						$imageHeight = !empty($arParams['MAIN_'.$sliderCode.'_IMAGE_HEIGHT']) ? $arParams['MAIN_'.$sliderCode.'_IMAGE_HEIGHT'] : 300;

						$arFile = CFile::ResizeImageGet(
							$idFile,
							array('width'=>$imageWidth, 'height'=>$imageHeight),
							BX_RESIZE_IMAGE_PROPORTIONAL,
							true,
							false,
							false,
							$photoQuality
						);

						$price = $arOffer["CATALOG_PRICE_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']];
						$priceFormat = CurrencyFormat($arOffer["CATALOG_PRICE_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']], $arOffer["CATALOG_CURRENCY_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']]);
						$Price_ID = $arOffer["CATALOG_PRICE_ID_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']];
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

				$imageWidth = !empty($arParams['MAIN_'.$sliderCode.'_IMAGE_WIDTH']) ? $arParams['MAIN_'.$sliderCode.'_IMAGE_WIDTH'] : 300;
				$imageHeight = !empty($arParams['MAIN_'.$sliderCode.'_IMAGE_HEIGHT']) ? $arParams['MAIN_'.$sliderCode.'_IMAGE_HEIGHT'] : 300;

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
				$price = $ar["CATALOG_PRICE_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']];
				$priceFormat = CurrencyFormat($ar["CATALOG_PRICE_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']], $ar["CATALOG_CURRENCY_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']]);
				$Price_ID = $ar["CATALOG_PRICE_ID_".$arParams['MAIN_'.$sliderCode.'_PRICE_TYPE']];
				$typeItem = "simple";
				$itemID = $ar["ID"];
			}
		}
		// end get Price /////////////////////
		$tempResult["ITEMS"][] = array(
			"ID"                => $itemID,
			"NAME"              => $ar["NAME"],
			"ARTICLE"           => $ar["PROPERTY_".$arParams['MAIN_ARTICLE_FIELD']."_VALUE"],
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

//if($this->StartResultCache()){
$obCache  = new CPHPCache();
// определяем переменные
$cacheLifetime  = 3600;
$cacheID        = 'MAINPAGE_NEW'.SITE_ID;
$cachePath      = '/UVELIRSOFT/';

if($obCache->InitCache($cacheLifetime, $cacheID, $cachePath) ){
   $rsResult = $obCache->GetVars();
   $arResult = $rsResult[$cacheID];
}elseif( $obCache->StartDataCache()){
	$arResult = array();
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// main slider
	$arSelect = Array("ID", "NAME", "IBLOCK_ID", $arParams['MAIN_SLIDER_PICTURE_FIELD'],$arParams['MAIN_SLIDER_TITLE_FIELD'],$arParams['MAIN_SLIDER_TITLE_URL']);
	$arFilter = Array("IBLOCK_ID"=>(int)$arParams['MAIN_SLIDER_IBLOCK_ID'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>50), $arSelect);
	while($ar = $res->GetNext()) {

		if(!empty($ar["DETAIL_PICTURE"])){
			$rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
			$slide["PICTURE"] = $rsFile->Fetch();
		}else{
			continue;
		}
		$slide["TITLE"] = $ar[$arParams['MAIN_SLIDER_TITLE_FIELD']."_VALUE"];
		$slide["URL"] = $ar[$arParams['MAIN_SLIDER_TITLE_URL']."_VALUE"];
		$slide["NAME"] = $ar["NAME"];
		$arResult["SLIDER"][] = $slide;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// SLIDER #1
	if($arParams["SHOW_SLIDER1"]){
		$arResult["SLIDER1"] = createElementCard($blockName = trim($arParams['MAIN_SLIDER1_CODE']), $sliderCode = 'SLIDER1', $arParams);
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// SLIDER #2
	if($arParams["SHOW_SLIDER2"]){
		$arResult["SLIDER2"] = createElementCard($blockName = trim($arParams['MAIN_SLIDER2_CODE']), $sliderCode = 'SLIDER2', $arParams);
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// SLIDER #3
	if($arParams["SHOW_SLIDER3"]){
		$arResult["SLIDER3"] = createElementCard($blockName = trim($arParams['MAIN_SLIDER3_CODE']), $sliderCode = 'SLIDER3', $arParams);
	}

	///////////////////////////////////////////////////////////////////////////////////
	// BANNERS
	if($arParams['SHOW_BANNERS'] == 'Y'){
		$arResult["BANNERS"]["TITLE"] = $arParams['MAIN_BANNERS_TITLE'];
		$arResult["BANNERS"]["HEIGHT"] = $arParams['MAIN_BANNERS_HEIGHT'];
		$arSelect = Array(
			"ID",
			"NAME",
			"CODE",
			"IBLOCK_ID",
			"PREVIEW_PICTURE",
			"DETAIL_PICTURE",
			"PROPERTY_BANNER_LINK",
			"PROPERTY_BANNER_WIDTH",
			"PROPERTY_BANNER_TITLE",
		);
		$arFilter = Array(
			"IBLOCK_ID"=>(int)$arParams["MAIN_BANNERS_IBLOCK_ID"],
			"ACTIVE_DATE"=>"Y",
			"ACTIVE"=>"Y"
		);

		$props_list = array();
		$property_enums = CIBlockPropertyEnum::GetList(Array("DEF" => "DESC", "SORT" => "ASC"), Array( "IBLOCK_ID" => (int)$arParams["MAIN_BANNERS_IBLOCK_ID"], "CODE"=> "BANNER_WIDTH"));
		while($enum_fields = $property_enums->GetNext()){
			$props_list[$enum_fields['ID']] = $enum_fields['XML_ID'];
		}

		$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
		while($ar = $res->GetNext()) {
			$arResult["BANNERS"]["ITEMS"][] = array(
				"ID"                => $itemID,
				"NAME"              => $ar["NAME"],
				"PREVIEW_PICTURE"   => !empty($ar['PREVIEW_PICTURE']) ? CFile::GetPath($ar['PREVIEW_PICTURE']) : "",
				"DETAIL_PICTURE"    => !empty($ar['DETAIL_PICTURE']) ? CFile::GetPath($ar['DETAIL_PICTURE']) : "",
				"BANNER_LINK" 		=> $ar['PROPERTY_BANNER_LINK_VALUE'],
				"BANNER_WIDTH" 		=> $props_list[$ar['PROPERTY_BANNER_WIDTH_ENUM_ID']],
				"BANNER_TITLE" 		=> $ar['PROPERTY_BANNER_TITLE_VALUE'],
			);
			unset($ar);
		}

	}

	// TABS
	if($arParams['SHOW_TABS'] == 'Y' && $arParams['MAIN_TABS_COUNT'] > 0){
		$arResult['TAB_BLOCK'] = array(
			//"TITLES" => array(),
			"SORTS" => array(),
			"TABS" => array()
		);
		for($i = 0; $i < $arParams['MAIN_TABS_COUNT']; $i++){
			//$arResult['TAB_BLOCK']["TITLES"][$i] = $arParams["MAIN_TAB_".$i."_TITLE"];
			$arResult['TAB_BLOCK']["SORTS"][$i] = $arParams["MAIN_TAB_".$i."_SORT"];
			$arResult['TAB_BLOCK']["TABS"][$i] = createElementCard($blockName = trim($arParams['MAIN_TAB_'.$i.'_CODE']), $sliderCode = "TAB_".$i, $arParams);
			if(!isset($arResult['TAB_BLOCK']["TABS"][$i]['ITEMS']) || empty($arResult['TAB_BLOCK']["TABS"][$i]['ITEMS'])){
				unset($arResult['TAB_BLOCK']["SORTS"][$i], $arResult['TAB_BLOCK']["TABS"][$i]);
			}
		}

		asort($arResult['TAB_BLOCK']["SORTS"]);
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ADDITIONAL SLIDER
	if($arParams['SHOW_ADDITIONAL_SLIDER'] == 'Y'){
		$arSelect = Array("ID", "NAME", "IBLOCK_ID", $arParams['MAIN_SLIDER_ADDITIONAL_PICTURE_FIELD'],$arParams['MAIN_SLIDER_ADDITIONAL_TITLE_FIELD'],$arParams['MAIN_SLIDER_ADDITIONAL_TITLE_URL']);
		$arFilter = Array("IBLOCK_ID"=>(int)$arParams['MAIN_SLIDER_ADDITIONAL_IBLOCK_ID'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>50), $arSelect);
		while($ar = $res->GetNext()) {

			if(!empty($ar["DETAIL_PICTURE"])){
				$rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
				$slide["PICTURE"] = $rsFile->Fetch();
			}else{
				continue;
			}
			$slide["TITLE"] = $ar[$arParams['MAIN_SLIDER_ADDITIONAL_TITLE_FIELD']."_VALUE"];
			$slide["URL"] = $ar[$arParams['MAIN_SLIDER_ADDITIONAL_TITLE_URL']."_VALUE"];
			$slide["NAME"] = $ar["NAME"];
			$arResult["ADDITIONAL_SLIDER"][] = $slide;
		}
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	// VIEWED
	if($arParams['SHOW_VIEWED_PRODUCTS'] == 'Y'){
		$arResult['VIEVED'] = 'AJAX';
	}

	// SORT
	$arResult['BLOCKS_SORT'] = array(
		'SLIDER1' => $arParams['MAIN_SLIDER1_SORT'],
		'SLIDER2' => $arParams['MAIN_SLIDER2_SORT'],
		'SLIDER3' => $arParams['MAIN_SLIDER3_SORT'],
		'BANNERS' => $arParams['MAIN_BANNERS_SORT'],
		'TAB_BLOCK' => $arParams['MAIN_TABS_SORT'],
		'ADDITIONAL_SLIDER' => $arParams['MAIN_SLIDER_ADDITIONAL_SORT'],
		'VIEVED' => $arParams['MAIN_VIEWED_SORT']
	);

	asort($arResult['BLOCKS_SORT']);

	//$this->SetResultCacheKeys(array());
	$obCache->EndDataCache(array($cacheID => $arResult));
}
$this->IncludeComponentTemplate();
