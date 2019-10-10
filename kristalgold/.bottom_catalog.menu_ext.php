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
	//"left_margin" => 'ASC',
	"UF_SHOW_FULL_BOTTOM" => 'desc',
	//"NAME" => "ASC",
);

$arFilter = array(
	"IBLOCK_ID" => $arParam["IBLOCK_ID"],
	//"SECTION_ID" => $arParam["SECTION_ID"],
	"GLOBAL_ACTIVE" => "Y",
	"IBLOCK_ACTIVE" => "Y",
	"<="."DEPTH_LEVEL" => $arParam["DEPTH_LEVEL"],
	"UF_SHOW_IN_BOTTOM" => 1,
);

$arSelect = array(
	"ID",
	"DEPTH_LEVEL",
	"NAME",
	"SECTION_PAGE_URL",
	"UF_SHOW_IN_BOTTOM",
	"UF_SHOW_FULL_BOTTOM"
);

$rsSections = CIBlockSection::GetList(
	$arOrder,
	$arFilter,
	false,
	$arSelect
);

$arResult["SECTIONS"] = array();

$levelInc = 1;
$depthLevel = false;

while($arSection = $rsSections->GetNext())
{
	if($arSection["DEPTH_LEVEL"] == 1) continue;

	$arResult["SECTIONS"][] = array(
		"ID" => $arSection["ID"],
		// "DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"] + $levelInc,
		"DEPTH_LEVEL" => 1,
		"~NAME" => $arSection["~NAME"],
		"~SECTION_PAGE_URL" => $arSection["~SECTION_PAGE_URL"],
		'UF_SHOW_FULL_BOTTOM'=> $arSection["UF_SHOW_FULL_BOTTOM"]
	);

	if($arSection["UF_SHOW_FULL_BOTTOM"] == 1){
		$resSubsections = CIblockSection::GetList(
			array("left_margin" => 'ASC'),
			array(
				"IBLOCK_ID" => $arParam["IBLOCK_ID"],
				"=SECTION_ID" => $arSection["ID"],
				"GLOBAL_ACTIVE" => "Y",
				"IBLOCK_ACTIVE" => "Y",
				"UF_SHOW_IN_BOTTOM" => "" // для избежания дублей
			),
			false,
			array(
				"ID",
				"DEPTH_LEVEL",
				"NAME",
				"SECTION_PAGE_URL",
				"UF_SHOW_IN_BOTTOM",
				"UF_SHOW_FULL_BOTTOM"
			),
			false
		);
		while($arSubsection = $resSubsections->GetNext()){
			$arResult["SECTIONS"][] = array(
				"ID" => $arSubsection["ID"],
				// "DEPTH_LEVEL" => $arSubsection["DEPTH_LEVEL"] + $levelInc,
				"DEPTH_LEVEL" => 2,
				"~NAME" => $arSubsection["~NAME"],
				"~SECTION_PAGE_URL" => $arSubsection["~SECTION_PAGE_URL"],

			);
		}
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
			'UF_SHOW_FULL_BOTTOM' => $arSection["UF_SHOW_FULL_BOTTOM"]
		),
		
	);
}

array_splice($aMenuLinks, 0, 0, $aMenuLinksNew);
