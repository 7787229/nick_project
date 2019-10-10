<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('iblock');
CModule::IncludeModule('uvelirsoft');

// get settings from system module
$arResult["SITE_CSS_MOD"] = COption::GetOptionInt("uvelirsoft", "SITE_CSS_MOD");
$arResult["CATALOG_BANER_LINE_HEIGHT"] = COption::GetOptionInt("uvelirsoft", "CATALOG_BANER_LINE_HEIGHT");
$arResult["CATALOG_FILTER_VISIBLE_ELEMENTS_COUNT"] = COption::GetOptionInt("uvelirsoft", "CATALOG_FILTER_VISIBLE_ELEMENTS_COUNT");
$arResult["CATALOG_FILTER_FIELDS_MOD"] = COption::GetOptionInt("uvelirsoft", "CATALOG_FILTER_FIELDS_MOD");
$arResult["CATALOG_FILTER_FIELDS_MOD_NOTITLE"] = COption::GetOptionInt("uvelirsoft", "CATALOG_FILTER_FIELDS_MOD_NOTITLE");

// изменение цветового оформления

/*
 
 1) в каталоге css_mod на каждый вариант отдельный css
 2) название файла длжно состоять из трех частей разделенных точкой
	- первая часть название темы, достаточно уникальное
	- 6 знаков цвета в HEX формате (для дифференсации на админ панели)
	- расшиение файла
 

 
 */

$arResult["CSS_MODS"] = array();


if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].DEFAULT_TEMPLATE."/css_mod")) {
    while ($entry = readdir($handle)) {
        if (substr($entry,(strlen($entry)-4),4) == ".css") {
			$fileName = explode(".",$entry);
			$arResult["CSS_MODS"][] = array("ID" => $fileName[0].".".$fileName[1], "NAME" => $fileName[0], "COLOR" => "#".$fileName[1], "ACTIVE" => ($fileName[0]==$arResult["SITE_CSS_MOD"] ? "Y":"N"));
        }
    }
    closedir($handle);
}


$this->IncludeComponentTemplate();