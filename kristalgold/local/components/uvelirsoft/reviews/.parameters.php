<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Iblock;
use Bitrix\Сatalog;
//use Bitrix\Currency;

if (!Loader::includeModule('iblock')){
	return;
}
// инфоблоки
$arIBlock = array();
$iBlockFilter = array('ACTIVE' => 'Y');
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iBlockFilter);
while ($arr = $rsIBlock->Fetch()){
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
// группы пользователей
$rsGroups = CGroup::GetList ($by = "c_sort", $order = "asc", Array ());
while($row = $rsGroups->fetch()) {
	$arGroups[$row["ID"]] = $row["NAME"]." (".$row["ID"].")";
}

unset($iBlockFilter, $arr, $rsIBlock);

// array of parameters
$arComponentParameters = array(
	"GROUPS" => array(
		"GENERAL" => array(
			"NAME" => GetMessage("General settings"),
		)
	),
	'PARAMETERS' => array(
	    	"IBLOCK_ID" => array(
				"PARENT" => "GENERAL",
				"NAME" => GetMessage("Information block"),
				"TYPE" => "LIST",
				"ADDITIONAL_VALUES" => "N",
				"VALUES" => $arIBlock,
				"REFRESH" => "Y",
			),
			"MODERATION_GROUP" => array(
				"PARENT" => "GENERAL",
				"NAME" => GetMessage("Moderation group"),
				"TYPE" => "LIST",
				"ADDITIONAL_VALUES" => "N",
				"VALUES" => $arGroups,
				"REFRESH" => "Y",
			),			
			"ELEMENT_ID" => array(
				"PARENT" => "GENERAL",
				"NAME" => GetMessage("ELEMENT_ID"),
				"TYPE" => "TEXT",
				"REFRESH" => "Y",
			),
			"COUNT_PAGE" => array(
				"PARENT" => "GENERAL",
				"NAME" => GetMessage("COUNT_PAGE"),
				"VALUE"=> 4,
				"DEFAULT"=> 4,
			),
		)
);
