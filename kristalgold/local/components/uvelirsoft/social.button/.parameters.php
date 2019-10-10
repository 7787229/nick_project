<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Iblock;


if (!Loader::includeModule('iblock')){
	return;
}

// infoblock type
$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlock = array();
$iblockFilter = (
	!empty($arCurrentValues['SOCIAL_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['SOCIAL_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch()){
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilter);

$propertyIterator = Iblock\PropertyTable::getList(array(
        'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE'),
        'filter' => array('=IBLOCK_ID' => $arCurrentValues['SOCIAL_IBLOCK_ID'], '=ACTIVE' => 'Y'),
        'order' => array('SORT' => 'ASC', 'NAME' => 'ASC')
));

while ($property = $propertyIterator->fetch())
{
    $propertyCode = (string)$property['CODE'];
    if ($propertyCode == ''){
        $propertyCode = $property['ID'];
    }
    $propertyName = '['.$propertyCode.'] '.$property['NAME'];

    if($property['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE){
        $arProperty[$propertyCode] = $propertyName;
    }
}

//icon size - http://fontawesome.ru/examples/

$arIconSize["fa-lg"] = "1";
$arIconSize["fa-2x"] = "2";
$arIconSize["fa-3x"] = "3";
$arIconSize["fa-4x"] = "4";
$arIconSize["fa-5x"] = "5";


// array of parameters
$arComponentParameters = array(   
	"GROUPS" => array(
		"MAIN" => array(
			"NAME" => GetMessage("Main"),
		)          
	),    
	'PARAMETERS' => array(
	    	"SOCIAL_IBLOCK_TYPE" => array(
			"PARENT" => "MAIN",
			"NAME" => GetMessage("Type of information block"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
			), 
	    	"SOCIAL_IBLOCK_ID" => array(
			"PARENT" => "MAIN",
			"NAME" => GetMessage("Information block"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),
                "SOCIAL_ICON" => array(
                        "PARENT" => "MAIN",
                        "NAME" => GetMessage("Icon class (Font-Awesome)"),
                        "TYPE" => "LIST",
                        "MULTIPLE" => "N",
                        "VALUES" => $arProperty,
                ),
                "SOCIAL_LINK" => array(
                        "PARENT" => "MAIN",
                        "NAME" => GetMessage("URL"),
                        "TYPE" => "LIST",
                        "MULTIPLE" => "N",
                        "VALUES" => $arProperty,
                ),
                "SOCIAL_COLOR" => array(
                        "PARENT" => "MAIN",
                        "NAME" => GetMessage("Icon color"),
                        "TYPE" => "LIST",
                        "MULTIPLE" => "N",
                        "VALUES" => $arProperty,
                ),
	    	"SOCIAL_COLOR_DEFAULT" => array(
			"PARENT" => "MAIN",
			"NAME" => GetMessage("Default icon color"),
			"TYPE" => "STRING",
			"DEFAULT" => "#dddddd",
		),             
                "SOCIAL_COLOR_HOVER" => array(
                        "PARENT" => "MAIN",
                        "NAME" => GetMessage("Icon color:hover"),
                        "TYPE" => "LIST",
                        "MULTIPLE" => "N",
                        "VALUES" => $arProperty,
                ),
	    	"SOCIAL_COLOR_HOVER_DEFAULT" => array(
			"PARENT" => "MAIN",
			"NAME" => GetMessage("Default icon color on hover"),
			"TYPE" => "STRING",
			"DEFAULT" => "#ff0000",
		),    
                "SOCIAL_ICON_SIZE" => array(
                        "PARENT" => "MAIN",
                        "NAME" => GetMessage("Icon size"),
                        "TYPE" => "LIST",
                        "MULTIPLE" => "N",
                        "VALUES" => $arIconSize,
                ),            
        )
);
