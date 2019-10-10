<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock") or !CModule::IncludeModule("sale") or !CModule::IncludeModule("main"))
	return;

/* Ювелирные украшения */
$arResult = array();
$arParam = array();

$arParam["SECTION_ID"] = CATALOG_ROOT_SECTION_ID;
$arParam["IBLOCK_ID"] = CATALOG_ID;
$arParam["DEPTH_LEVEL"] = 4;

$arOrder = array(
	"left_margin" => 'ASC'
);

$arFilter = array(
	"IBLOCK_ID" => $arParam["IBLOCK_ID"],
	//"SECTION_ID" => $arParam["SECTION_ID"],
	"GLOBAL_ACTIVE" => "Y",
	"IBLOCK_ACTIVE" => "Y",
	"<="."DEPTH_LEVEL" => $arParam["DEPTH_LEVEL"],
);

$arSelect = array(
	"ID",
	"DEPTH_LEVEL",
	"NAME",
	"SECTION_PAGE_URL",
	"PICTURE",
	"DETAIL_PICTURE"
);

$rsSections = CIBlockSection::GetList(
	$arOrder,
	$arFilter,
	false,
	$arSelect
);

/*
$arResult["SECTIONS"][0] = array(
	"ID" => "",
	"DEPTH_LEVEL" => 1,
	"~NAME" => "Ювелирные украшения",
	"~SECTION_PAGE_URL" => "/catalog/",
	"PICTURE" => "",
	"ICON" => "",
	"HAS_PICTURE" => true
);
*/

$arCatalogSections = array();

while($arSection = $rsSections->GetNext())
{
	if($arSection["DEPTH_LEVEL"] == 1) continue;
	
	//$arResult["SECTIONS"][] = array(
	$arCatalogSections[] = array(
		"ID" => $arSection["ID"],
		"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
		"~NAME" => $arSection["~NAME"],
		"~SECTION_PAGE_URL" => $arSection["~SECTION_PAGE_URL"],
		"PICTURE" => $arSection["PICTURE"],
		"ICON" => $arSection["DETAIL_PICTURE"]
	);	
}

//printvar('',$arCatalogSections);

/* Структура меню */
$arParam = array();

$arParam["IBLOCK_ID"] = MENU_ID;
$arParam["DEPTH_LEVEL"] = 4;

$arOrder = array(
	"left_margin" => 'ASC'
);

$arFilter = array(
	"IBLOCK_ID" => $arParam["IBLOCK_ID"],
	"GLOBAL_ACTIVE" => "Y",
	"IBLOCK_ACTIVE" => "Y",
	"<="."DEPTH_LEVEL" => $arParam["DEPTH_LEVEL"],
);

$arSelect = array(
	"ID",
	"DEPTH_LEVEL",
	"NAME",
	"SECTION_PAGE_URL",
	"DESCRIPTION",
	"PICTURE",
	"DETAIL_PICTURE",
	"UF_SHOW_CATALOG"
);

$rsSections = CIBlockSection::GetList(
	$arOrder,
	$arFilter,
	false,
	$arSelect
);

$hasCatalog = false;


while($arSection = $rsSections->GetNext())
{
	$bool = ($arSection["UF_SHOW_CATALOG"] && !$hasCatalog && $arSection["DEPTH_LEVEL"] == 1);
	
	$arResult["SECTIONS"][] = array(
		"ID" => $arSection["ID"],
		"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
		"~NAME" => $arSection["~NAME"],
		"~SECTION_PAGE_URL" => trim($arSection["~DESCRIPTION"]),
		"PICTURE" => $arSection["PICTURE"],
		"ICON" => $arSection["DETAIL_PICTURE"],
		//"HAS_PICTURE" => $bool
	);	
	
	if($bool){
		foreach($arCatalogSections as $section){
			$arResult["SECTIONS"][] = $section;
		}
		$hasCatalog = true;
	}
}

$aMenuLinksNew = array();
$menuIndex = 0;
$previousDepthLevel = 1;
foreach($arResult["SECTIONS"] as $arSection)
{
	if ($menuIndex > 0)
		$aMenuLinksNew[$menuIndex - 1][3]["IS_PARENT"] = $arSection["DEPTH_LEVEL"] > $previousDepthLevel;
	$previousDepthLevel = $arSection["DEPTH_LEVEL"];

	$arResult["ELEMENT_LINKS"][$arSection["ID"]][] = urldecode($arSection["~SECTION_PAGE_URL"]);
	$aMenuLinksNew[$menuIndex++] = array(
		htmlspecialcharsbx($arSection["~NAME"]),
		$arSection["~SECTION_PAGE_URL"],
		$arResult["ELEMENT_LINKS"][$arSection["ID"]],
		array(
			"FROM_IBLOCK" => true,
			"IS_PARENT" => false,
			"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
			"PICTURE" => !empty($arSection["PICTURE"]) ? CFile::GetPath($arSection["PICTURE"]) : "",
			"ICON" => !empty($arSection["ICON"]) ? CFile::GetPath($arSection["ICON"]) : "",
			//"HAS_PICTURE" => $arSection["HAS_PICTURE"]
		),
	);
}

//printvar('',$aMenuLinksNew);
array_splice($aMenuLinks, 0, 0, $aMenuLinksNew);
//printvar('',$aMenuLinks);