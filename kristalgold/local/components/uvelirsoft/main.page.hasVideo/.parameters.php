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

// array of parameters
$arComponentParameters = array(   
	"GROUPS" => array(
		"GENERAL" => array(
			"NAME" => GetMessage("General settings"),
		),	
		"MAIN_BANNER" => array(
			"NAME" => GetMessage("Main banner") 
		),		
		"MAIN_VIDEO" => array(
			"NAME" => GetMessage("Main video") 
		),
		"MAIN_SLIDER" => array(
			"NAME" => GetMessage("Main slider"),
		),
 		"MAIN_NEW" => array(
			"NAME" => GetMessage("What's New Block"),
		),
 		"MAIN_BESTSELLER" => array(
			"NAME" => GetMessage("Bestseller Block"),
                ),
 		"MAIN_SALE" => array(
			"NAME" => GetMessage("Sale Block"),
		),            
	)
);	

$arComponentParameters['PARAMETERS']["MAIN_ARTICLE_FIELD"] = array(
	"PARENT" => "GENERAL",
	"NAME" => GetMessage("SKU field"),
	"TYPE" => "STRING",
	"DEFAULT" => "ARTNUMBER",
);

$arComponentParameters['PARAMETERS']["MAIN_BANNER_TYPE"] = array(
		"PARENT" => "MAIN_BANNER",
		"NAME" => GetMessage("Type of main banner"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"SLIDER" => GetMessage("slider"),
			"VIDEO" => GetMessage("video"),
		),
		"REFRESH" => "Y",
		"MULTIPLE"  =>  "N",
		"DEFAULT" => "SLIDER"
	);
	
if($arCurrentValues["MAIN_BANNER_TYPE"] != 'VIDEO'){
	$arComponentParameters['PARAMETERS']["MAIN_SLIDER_IBLOCK_TYPE"] = array(
		"PARENT" => "MAIN_SLIDER",
		"NAME" => GetMessage("Type of information block"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
	$arComponentParameters['PARAMETERS']["MAIN_SLIDER_IBLOCK_ID"] = array(
		"PARENT" => "MAIN_SLIDER",
		"NAME" => GetMessage("Information block"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlockSlider,
		"REFRESH" => "Y",
	);
	$arComponentParameters['PARAMETERS']["MAIN_SLIDER_HEIGHT"] = array(
		"PARENT" => "MAIN_SLIDER",
		"NAME" => GetMessage("Height slider (px)"),
		"TYPE" => "STRING",
		"DEFAULT" => "500",
	);
	$arComponentParameters['PARAMETERS']["MAIN_SLIDER_PICTURE_FIELD"] = array(
		"PARENT" => "MAIN_SLIDER",
		"NAME" => GetMessage("The field with the image"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	); 
	$arComponentParameters['PARAMETERS']["MAIN_SLIDER_TITLE_FIELD"] = array(
		"PARENT" => "MAIN_SLIDER",
		"NAME" => GetMessage("The field with the title"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	); 
	$arComponentParameters['PARAMETERS']["MAIN_SLIDER_TITLE_URL"] = array(
		"PARENT" => "MAIN_SLIDER",
		"NAME" => GetMessage("The field with the URL"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	);
}else{
	$arComponentParameters['PARAMETERS']["VIDEO_POSTER"] = array(
		"PARENT" => "MAIN_VIDEO",
		"NAME" => GetMessage('Path to tmp-image'),
		"TYPE" => "STRING",
		"MULTIPLE" => "N",
		"DEFAULT" => "",
		"REFRESH" => "Y",			
	);	
	$arComponentParameters['PARAMETERS']["VIDEO_FROM_YOUTUBE"] = array(
		"PARENT" => "MAIN_VIDEO",
		"NAME" => GetMessage('Video from youtube'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
		"REFRESH" => "Y",	
	);
	
	if($arCurrentValues["VIDEO_FROM_YOUTUBE"] == "N"){
		$arComponentParameters["PARAMETERS"]["VIDEO_LINK_WEBM"] = array(
			"PARENT" => "MAIN_VIDEO",
			"NAME" => GetMessage('Path to WEBM'),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"REFRESH" => "Y",		
		);		
		$arComponentParameters["PARAMETERS"]["VIDEO_LINK_MP4"] = array(
			"PARENT" => "MAIN_VIDEO",
			"NAME" => GetMessage('Path to MP4'),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"REFRESH" => "Y",		
		);		
		$arComponentParameters["PARAMETERS"]["VIDEO_LINK_OGG"] = array(
			"PARENT" => "MAIN_VIDEO",
			"NAME" => GetMessage('Path to OGG'),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"REFRESH" => "Y",		
		);	
	}else{
		$arComponentParameters["PARAMETERS"]["VIDEO_ID"] = array(
			"PARENT" => "MAIN_VIDEO",
			"NAME" => GetMessage('Youtube video id'),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "",
			"REFRESH" => "Y",		
		);
	}	
}	

		// NEW
$arComponentParameters['PARAMETERS']["SHOW_NEW"] = array(
	"PARENT" => "MAIN_NEW",
	"NAME" => GetMessage("Show this block"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
	"REFRESH" => "Y",
);
		// BESTSELLER
$arComponentParameters['PARAMETERS']["SHOW_BESTSELLER"] = array(
	"PARENT" => "MAIN_BESTSELLER",
	"NAME" => GetMessage("Show this block"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
	"REFRESH" => "Y",
);
		// NEW
$arComponentParameters['PARAMETERS']["SHOW_SALE"] = array(
	"PARENT" => "MAIN_SALE",
	"NAME" => GetMessage("Show this block"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "Y",
	"REFRESH" => "Y",
);           

if($arCurrentValues["SHOW_NEW"] == "Y"){
    
    $arComponentParameters["PARAMETERS"]["MAIN_NEW_IBLOCK_TYPE"] = array(
			"PARENT" => "MAIN_NEW",
			"NAME" => GetMessage("Type of information block"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		); 
    $arComponentParameters["PARAMETERS"]["MAIN_NEW_IBLOCK_ID"] = array(
			"PARENT" => "MAIN_NEW",
			"NAME" => GetMessage("Information block"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockNew,
			"REFRESH" => "Y",
		);
    $arComponentParameters["PARAMETERS"]["MAIN_NEW_TITLE"] = array(
			"PARENT" => "MAIN_NEW",
			"NAME" => GetMessage("Title"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);    
    $arComponentParameters["PARAMETERS"]["MAIN_NEW_FIELD_VALUE"] = array(
			"PARENT" => "MAIN_NEW",
			"NAME" => GetMessage("Field value"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);  
    $arComponentParameters["PARAMETERS"]["MAIN_NEW_PRICE_TYPE"] = array(
			"PARENT" => "MAIN_NEW",
			"NAME" => GetMessage("Type of price"),
			"TYPE" => "LIST",
                        "VALUES" => $arPrice,
			"REFRESH" => "Y",
		);      
}

// BESTSELLER BLOCK
if($arCurrentValues["SHOW_BESTSELLER"] == "Y"){

    $arComponentParameters["PARAMETERS"]["MAIN_BESTSELLER_IBLOCK_TYPE"] = array(
			"PARENT" => "MAIN_BESTSELLER",
			"NAME" => GetMessage("Type of information block"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		);
    $arComponentParameters["PARAMETERS"]["MAIN_BESTSELLER_IBLOCK_ID"] = array(
			"PARENT" => "MAIN_BESTSELLER",
			"NAME" => GetMessage("Information block"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockBestseller,
			"REFRESH" => "Y",
		);            
    $arComponentParameters["PARAMETERS"]["MAIN_BESTSELLER_TITLE"] = array(
			"PARENT" => "MAIN_BESTSELLER",
			"NAME" => GetMessage("Title"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);    
    $arComponentParameters["PARAMETERS"]["MAIN_BESTSELLER_FIELD_VALUE"] = array(
			"PARENT" => "MAIN_BESTSELLER",
			"NAME" => GetMessage("Field value"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);  
    $arComponentParameters["PARAMETERS"]["MAIN_BESTSELLER_PRICE_TYPE"] = array(
			"PARENT" => "MAIN_BESTSELLER",
			"NAME" => GetMessage("Type of price"),
			"TYPE" => "LIST",
                        "VALUES" => $arPrice,
			"REFRESH" => "Y",
		);   
}    
    
    
// BESTSELLER BLOCK
if($arCurrentValues["SHOW_SALE"] == "Y"){
    
    $arComponentParameters["PARAMETERS"]["MAIN_SALE_IBLOCK_TYPE"] = array(
			"PARENT" => "MAIN_SALE",
			"NAME" => GetMessage("Type of information block"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		);
    $arComponentParameters["PARAMETERS"]["MAIN_SALE_IBLOCK_ID"] = array(
			"PARENT" => "MAIN_SALE",
			"NAME" => GetMessage("Information block"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockSale,
			"REFRESH" => "Y",
		);   
    $arComponentParameters["PARAMETERS"]["MAIN_SALE_TITLE"] = array(
			"PARENT" => "MAIN_SALE",
			"NAME" => GetMessage("Title"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);    
    $arComponentParameters["PARAMETERS"]["MAIN_SALE_FIELD_VALUE"] = array(
			"PARENT" => "MAIN_SALE",
			"NAME" => GetMessage("Field value"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		);    
    $arComponentParameters["PARAMETERS"]["MAIN_SALE_PRICE_TYPE"] = array(
			"PARENT" => "MAIN_SALE",
			"NAME" => GetMessage("Type of price"),
			"TYPE" => "LIST",
                        "VALUES" => $arPrice,
			"REFRESH" => "Y",
		);             
} 

