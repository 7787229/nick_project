<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


$arNoPath = array(
	'/personal/_order/',
	'/personal/_cart/',
	'/personal/_order/make/',
	'/personal/_order/payment/',
	'/personal/order/make/',
	'/personal/order/payment/',
	'/about/awards/',
	'/about/employees/',
	'/about/history/',
	'/about/partners/',
	'/about/responses/',
	'/about/vacancies/',
	'/personal/subscribe/'
);



$arFilter = array(		
    'ACTIVE' => 'Y',
    'IBLOCK_ID' => 1,
    'GLOBAL_ACTIVE'=>'Y',
);
$arSelect = array(
	'IBLOCK_ID',
	'ID',
	'NAME',
	'DEPTH_LEVEL',
	'IBLOCK_SECTION_ID',
	"NAME",
	"SECTION_PAGE_URL",
	"PICTURE"
);
$arOrder = array('DEPTH_LEVEL'=>'ASC','SORT'=>'ASC');
$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
$sectionLinc = array();
$arResult['ROOT'] = array();
$sectionLinc[0] = &$arResult['ROOT'];
while($arSection = $rsSections->GetNext()) {
	if($arSection['NAME'] == 'Каталог'){
		$arSection['NAME'] = 'Магазин';
	}
    $sectionLinc[intval($arSection['IBLOCK_SECTION_ID'])]['CHILD'][$arSection['ID']] = $arSection;
    $sectionLinc[$arSection['ID']] = &$sectionLinc[intval($arSection['IBLOCK_SECTION_ID'])]['CHILD'][$arSection['ID']];
}
unset($sectionLinc);

//printvar('', $arResult['ROOT'] );


foreach ($arResult['IBLOCKS'] as $key => $value) {

	$arFilter = array(
		"IBLOCK_ID" => $key,
		"GLOBAL_ACTIVE" => "Y",
		"IBLOCK_ACTIVE" => "Y",
		"<=" . "DEPTH_LEVEL" => 4,
	);

	$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, array(
		"ID",
		"DEPTH_LEVEL",
		"NAME",
		"SECTION_PAGE_URL",
		"PICTURE"
	));

	while ($arSection = $rsSections->GetNext()) {
		
		
		$arResult["SECTIONS"][$key]['ITEMS'][] = array(
			"ID" => $arSection["ID"],
			"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
			"NAME" => $arSection["~NAME"],
			"DETAIL_PAGE_URL" => $arSection["~SECTION_PAGE_URL"],
			"PICTURE" => $arSection["PICTURE"]
		);

		$arResult["ELEMENT_LINKS"][$arSection["ID"]] = array();
	}
	#'IBLOCK_SECTION_ID'






}

foreach ($arResult["FOLDERS"] as $key => $value) {

	if (!in_array($value["PATH"], $arNoPath)) {
		$Result[$key] = $value;
		//printvar('',$value["PATH"]);
	}
}

if ($arParams['INCLUDE_SEO'] == 'Y') {
	$arSelect = array("ID", "NAME", "PROPERTY_ITEM_URL");
	$arFilter = array("IBLOCK_ID" => "56");
	$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	while ($ob = $res->Fetch()) {
		$arResult["SEO"][] = array(
			'NAME' => $ob["NAME"],
			'URL' => $ob["PROPERTY_ITEM_URL_VALUE"],
		);
	}
}
$arResult["FOLDERS"] = $Result;
