<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$IDs = array();
foreach($arResult["SEARCH"] as $arKey => $arItem):
	//$rs_item = CIBlockElement::GetByID($arItem['ITEM_ID']);
	// if($ar_res = $rs_item->GetNext())
	// 	$temp = !empty($ar_res['PREVIEW_PICTURE']) ? $ar_res['PREVIEW_PICTURE'] : $ar_res['DETAIL_PICTURE'];
	// 	$arResult["SEARCH"][$arKey]['PICTURE'] = CFile::GetPath($temp);

	$IDs[$arKey] = $arItem['ITEM_ID'];
endforeach;

if(!empty($IDs)){
	$rs_item = CIBlockElement::GetList(
		array(),
		array(
			'ID' => $IDs
		),
		false,
		false,
		array('ID', 'NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_NAIMENOVANIE_DLYA_SAYTA')
	);
	while($ar_res = $rs_item->GetNext()){
		$arKey = array_search($ar_res['ID'], $IDs);

		$picture = !empty($ar_res['PREVIEW_PICTURE']) ? $ar_res['PREVIEW_PICTURE'] : $ar_res['DETAIL_PICTURE'];
		$name = ($ar_res['PROPERTY_NAIMENOVANIE_DLYA_SAYTA_VALUE'] ? $ar_res['PROPERTY_NAIMENOVANIE_DLYA_SAYTA_VALUE'] : $ar_res['NAME']);

		$arResult["SEARCH"][$arKey]['PICTURE'] = CFile::GetPath($picture);
		$arResult["SEARCH"][$arKey]['TITLE'] = $name;
	}
}
