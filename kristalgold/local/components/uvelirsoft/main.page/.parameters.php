<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Iblock;
use Bitrix\Сatalog;
//use Bitrix\Currency;

if (!Loader::includeModule('iblock')){
	return;
}

// infoblock type
$arIBlockType = CIBlockParameters::GetIBlockTypes();
// price type
if (CModule::IncludeModule("catalog")){
        $arPrice[0] = GetMessage("Do not show the price");
        $dbPriceType = CCatalogGroup::GetList(array("SORT" => "ASC"));
        while ($arPriceType = $dbPriceType->Fetch())
        {
            $arPrice[$arPriceType["ID"]] = "(".$arPriceType["NAME"].") ".$arPriceType["NAME_LANG"];
        }
}else{
    $arPrice = array();
}



$arIBlockSlider = array();
$arIBlockNew = array();
$arIBlockBestseller = array();
$arIBlockSale = array();
$arIBlockBanners = array();

///////////////////////////////////////////////////////////////////////////////////////
// slider infoblock
$iblockFilterSlider = (
	!empty($arCurrentValues['MAIN_SLIDER_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['MAIN_SLIDER_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilterSlider);
while ($arr = $rsIBlock->Fetch()){
	$arIBlockSlider[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilterSlider);

///////////////////////////////////////////////////////////////////////////////////////
// slider infoblock
$arIBlockAdditionalSlider = array();
$iblockFilterAdditionalSlider = (
	!empty($arCurrentValues['MAIN_SLIDER_ADDITIONAL_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['MAIN_SLIDER_ADDITIONAL_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilterAdditionalSlider);
while ($arr = $rsIBlock->Fetch()){
	$arIBlockAdditionalSlider[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilterAdditionalSlider);

///////////////////////////////////////////////////////////////////////////////////////
// NEW infoblock
$iblockFilterNew = (
	!empty($arCurrentValues['MAIN_NEW_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['MAIN_NEW_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);

$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilterNew);
while ($arr = $rsIBlock->Fetch()){
	$arIBlockNew[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock ,$iblockFilterNew);

///////////////////////////////////////////////////////////////////////////////////////
//bestseller block
$iblockFilterBestseller = (
	!empty($arCurrentValues['MAIN_BESTSELLER_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['MAIN_BESTSELLER_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilterBestseller);
while ($arr = $rsIBlock->Fetch()){
	$arIBlockBestseller[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock ,$iblockFilterBestseller);
///////////////////////////////////////////////////////////////////////////////////////
//sale block
$iblockFilterSale = (
	!empty($arCurrentValues['MAIN_SALE_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['MAIN_SALE_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilterSale);
while ($arr = $rsIBlock->Fetch()){
	$arIBlockSale[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock ,$iblockFilterSale);

///////////////////////////////////////////////////////////////////////////////////////
//small banners
$iblockBannersSale = (
	!empty($arCurrentValues['MAIN_BANNERS_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['MAIN_BANNERS_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockBannersSale);
while ($arr = $rsIBlock->Fetch()){
	$arIBlockBanners[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock ,$iblockBannersSale);

// array of parameters
$arComponentParameters = array(
	"GROUPS" => array(
		"GENERAL" => array(
			"NAME" => GetMessage("General settings"),
		),
		"MAIN_SLIDER" => array(
			"NAME" => GetMessage("Main slider"),
		),
 		"MAIN_SLIDER1" => array(
			"NAME" => GetMessage("block with slider1"),
		),
 		"MAIN_SLIDER2" => array(
			"NAME" => GetMessage("block with slider2"),
                ),
 		"MAIN_SLIDER3" => array(
			"NAME" => GetMessage("block with slider3"),
		),
		"MAIN_BANNERS" => array(
			"NAME" => GetMessage("Banners Block"),
		),
		"MAIN_TABS" => array(
			"NAME" => GetMessage("Tabs Block"),
		),
		"MAIN_SLIDER_ADDITIONAL" => array(
			"NAME" => GetMessage("Additional Slider Block"),
		),
		"MAIN_VIEWED_PRODUCTS" => array(
			"NAME" => GetMessage("Viewed propducts"),
		),
	),
	'PARAMETERS' => array(
		"CACHE_TIME" => array('DEFAULT' => 3600),
		"MAIN_SLIDER_IBLOCK_TYPE" => array(
			"PARENT" => "MAIN_SLIDER",
			"NAME" => GetMessage("Type of information block"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
			),
		"MAIN_SLIDER_IBLOCK_ID" => array(
			"PARENT" => "MAIN_SLIDER",
			"NAME" => GetMessage("Information block"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockSlider,
			"REFRESH" => "Y",
		),
		"MAIN_SLIDER_HEIGHT" => array(
			"PARENT" => "MAIN_SLIDER",
			"NAME" => GetMessage("Height slider (px)"),
			"TYPE" => "STRING",
			"DEFAULT" => "500",
		),
		"MAIN_SLIDER_PICTURE_FIELD" => array(
			"PARENT" => "MAIN_SLIDER",
			"NAME" => GetMessage("The field with the image"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"MAIN_SLIDER_TITLE_FIELD" => array(
			"PARENT" => "MAIN_SLIDER",
			"NAME" => GetMessage("The field with the title"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"MAIN_SLIDER_TITLE_URL" => array(
			"PARENT" => "MAIN_SLIDER",
			"NAME" => GetMessage("The field with the URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"MAIN_ARTICLE_FIELD" => array(
			"PARENT" => "GENERAL",
			"NAME" => GetMessage("SKU field"),
			"TYPE" => "STRING",
			"DEFAULT" => "ARTNUMBER",
        ),
		// SLIDER #1
		"SHOW_SLIDER1" => array(
			"PARENT" => "MAIN_SLIDER1",
			"NAME" => GetMessage("Show this block"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
		),
		// SLIDER #2
		"SHOW_SLIDER2" => array(
			"PARENT" => "MAIN_SLIDER2",
			"NAME" => GetMessage("Show this block"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
		),
		// SLIDER #3
		"SHOW_SLIDER3" => array(
			"PARENT" => "MAIN_SLIDER3",
			"NAME" => GetMessage("Show this block"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y",
		),
		// BANNERS
		"SHOW_BANNERS" => array(
			"PARENT" => "MAIN_BANNERS",
			"NAME" => GetMessage("Show this block"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		// TABS
		"SHOW_TABS" => array(
			"PARENT" => "MAIN_TABS",
			"NAME" => GetMessage("Show this tabs"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		// ADDITIONAL SLIDER
		"SHOW_ADDITIONAL_SLIDER" => array(
			"PARENT" => "MAIN_SLIDER_ADDITIONAL",
			"NAME" => GetMessage("Show this additional slider"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		// VIEWED PRODUCTS
		"SHOW_VIEWED_PRODUCTS" => array(
			"PARENT" => "MAIN_VIEWED_PRODUCTS",
			"NAME" => GetMessage("Show this viewed products"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
    )
);

// SLIDER #1
if($arCurrentValues["SHOW_SLIDER1"] == "Y"){

    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Type of information block"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_IBLOCK_ID"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Information block"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlockNew,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_CODE"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Prop code"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_TITLE"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Title"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_FIELD_VALUE"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Field value"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_PRICE_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Type of price"),
		"TYPE" => "LIST",
		"VALUES" => $arPrice,
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_COUNT"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Element count"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "10",
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_SORT"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Sort"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "500",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_IMAGE_QUALITY"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Image quality"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "90",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_IMAGE_WIDTH"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Image widht"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "300",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER1_IMAGE_HEIGHT"] = array(
		"PARENT" => "MAIN_SLIDER1",
		"NAME" => GetMessage("Image height"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "300",
	);
}

// SLIDER #2
if($arCurrentValues["SHOW_SLIDER2"] == "Y"){

    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_IBLOCK_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Type of information block"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_IBLOCK_ID"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Information block"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlockBestseller,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_CODE"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Prop code"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_TITLE"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Title"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_FIELD_VALUE"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Field value"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_PRICE_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Type of price"),
		"TYPE" => "LIST",
		"VALUES" => $arPrice,
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_COUNT"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Element count"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "10",
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_SORT"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Sort"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "500",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_IMAGE_QUALITY"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Image quality"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "90",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_IMAGE_WIDTH"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Image widht"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "300",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER2_IMAGE_HEIGHT"] = array(
		"PARENT" => "MAIN_SLIDER2",
		"NAME" => GetMessage("Image height"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "300",
	);
}


// SLIDER #3
if($arCurrentValues["SHOW_SLIDER3"] == "Y"){
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_IBLOCK_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Type of information block"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_IBLOCK_ID"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Information block"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlockSale,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_CODE"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Prop code"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_TITLE"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Title"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_FIELD_VALUE"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Field value"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_PRICE_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Type of price"),
		"TYPE" => "LIST",
		"VALUES" => $arPrice,
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_COUNT"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Element count"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "10",
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_SORT"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Sort"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "500",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_IMAGE_QUALITY"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Image quality"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "90",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_IMAGE_WIDTH"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Image widht"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "300",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER3_IMAGE_HEIGHT"] = array(
		"PARENT" => "MAIN_SLIDER3",
		"NAME" => GetMessage("Image height"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "300",
	);
}

// BANNERS BLOCK
if($arCurrentValues["SHOW_BANNERS"] == "Y"){

    $arComponentParameters["PARAMETERS"]["MAIN_BANNERS_IBLOCK_TYPE"] = array(
		"PARENT" => "MAIN_BANNERS",
		"NAME" => GetMessage("Type of information block"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_BANNERS_IBLOCK_ID"] = array(
		"PARENT" => "MAIN_BANNERS",
		"NAME" => GetMessage("Information block"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlockBanners,
		"REFRESH" => "Y",
	);
    $arComponentParameters["PARAMETERS"]["MAIN_BANNERS_TITLE"] = array(
		"PARENT" => "MAIN_BANNERS",
		"NAME" => GetMessage("Title"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_BANNERS_HEIGHT"] = array(
		"PARENT" => "MAIN_BANNERS",
		"NAME" => GetMessage("Banners height"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "375",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_BANNERS_SORT"] = array(
		"PARENT" => "MAIN_BANNERS",
		"NAME" => GetMessage("Sort"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "500",
	);
}

// TABS BLOCK
if($arCurrentValues["SHOW_TABS"] == "Y"){
	$arComponentParameters["PARAMETERS"]["MAIN_TABS_SORT"] = array(
		"PARENT" => "MAIN_TABS",
		"NAME" => GetMessage("Sort"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "500",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_TABS_COUNT"] = array(
		"PARENT" => "MAIN_TABS",
		"NAME" => GetMessage("Tabs count"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "0",
		"REFRESH" => "Y"
	);

	if($arCurrentValues["MAIN_TABS_COUNT"] > 0){
		for($i = 0; $i < $arCurrentValues["MAIN_TABS_COUNT"]; $i++){
			$index = $i + 1;
			$arTabIBlock[$i] = array();
			$resIblockFilter = (
				!empty($arCurrentValues['MAIN_TAB_TYPE_'.$i])
				? array('TYPE' => $arCurrentValues['MAIN_TAB_TYPE_'.$i], 'ACTIVE' => 'Y')
				: array('ACTIVE' => 'Y')
			);
			$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $resIblockFilter);
			while ($arr = $rsIBlock->Fetch()){
				$arTabIBlock[$i][$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
			}
			unset($arr, $rsIBlock ,$resIblockFilter);

			$arComponentParameters["GROUPS"]["MAIN_TABS_".$i] = array(
				"NAME" => GetMessage("MAIN_TAB_INFO")." #".$index
			);

			$arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_TYPE"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Type of information block")." #".$index,
				"TYPE" => "LIST",
				"VALUES" => $arIBlockType,
				"REFRESH" => "Y",
			);
		    $arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_IBLOCK_ID"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Information block")." #".$index,
				"TYPE" => "LIST",
				"ADDITIONAL_VALUES" => "Y",
				"VALUES" => $arTabIBlock[$i],
				"REFRESH" => "Y",
			);
		    $arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_CODE"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Prop code")." #".$index,
				"TYPE" => "STRING",
				"DEFAULT" => "",
			);
		    $arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_TITLE"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Title")." #".$index,
				"TYPE" => "STRING",
				"DEFAULT" => "",
			);
		    $arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_FIELD_VALUE"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Field value")." #".$index,
				"TYPE" => "STRING",
				"DEFAULT" => "",
			);
		    $arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_PRICE_TYPE"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Type of price")." #".$index,
				"TYPE" => "LIST",
				"VALUES" => $arPrice,
				"REFRESH" => "Y",
			);
			$arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_COUNT"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Element count")." #".$index,
				"TYPE" => "NUMBER",
				"DEFAULT" => "10",
				"REFRESH" => "Y",
			);
			$arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_SORT"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Sort")." #".$index,
				"TYPE" => "NUMBER",
				"DEFAULT" => "500",
			);
			$arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_IMAGE_QUALITY"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Image quality")." #".$index,
				"TYPE" => "NUMBER",
				"DEFAULT" => "90",
			);
			$arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_IMAGE_WIDTH"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Image widht")." #".$index,
				"TYPE" => "NUMBER",
				"DEFAULT" => "300",
			);
			$arComponentParameters["PARAMETERS"]["MAIN_TAB_".$i."_IMAGE_HEIGHT"] = array(
				"PARENT" => "MAIN_TABS_".$i,
				"NAME" => GetMessage("Image height")." #".$index,
				"TYPE" => "NUMBER",
				"DEFAULT" => "300",
			);
		}
	}
}

// ADDITIONAL SLIDER
if($arCurrentValues["SHOW_ADDITIONAL_SLIDER"] == "Y"){
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER_ADDITIONAL_SORT"] = array(
		"PARENT" => "MAIN_SLIDER_ADDITIONAL",
		"NAME" => GetMessage("Sort"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "500",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER_ADDITIONAL_IBLOCK_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER_ADDITIONAL",
		"NAME" => GetMessage("Type of information block"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER_ADDITIONAL_IBLOCK_ID"] = array(
		"PARENT" => "MAIN_SLIDER_ADDITIONAL",
		"NAME" => GetMessage("Information block"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlockAdditionalSlider,
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER_ADDITIONAL_HEIGHT"] = array(
		"PARENT" => "MAIN_SLIDER_ADDITIONAL",
		"NAME" => GetMessage("Height slider (px)"),
		"TYPE" => "STRING",
		"DEFAULT" => "500",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER_ADDITIONAL_PICTURE_FIELD"] = array(
		"PARENT" => "MAIN_SLIDER_ADDITIONAL",
		"NAME" => GetMessage("The field with the image"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER_ADDITIONAL_TITLE_FIELD"] = array(
		"PARENT" => "MAIN_SLIDER_ADDITIONAL",
		"NAME" => GetMessage("The field with the title"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
	$arComponentParameters["PARAMETERS"]["MAIN_SLIDER_ADDITIONAL_TITLE_URL"] = array(
		"PARENT" => "MAIN_SLIDER_ADDITIONAL",
		"NAME" => GetMessage("The field with the URL"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
}

// VIEWED PRODUCTS
if($arCurrentValues["SHOW_VIEWED_PRODUCTS"] == "Y"){
	$arComponentParameters["PARAMETERS"]["MAIN_VIEWED_SORT"] = array(
		"PARENT" => "MAIN_VIEWED_PRODUCTS",
		"NAME" => GetMessage("Sort"),
		"TYPE" => "NUMBER",
		"DEFAULT" => "500",
	);
}
