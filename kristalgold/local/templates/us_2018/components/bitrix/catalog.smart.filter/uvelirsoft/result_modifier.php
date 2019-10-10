<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arParams["TEMPLATE_THEME"]) && !empty($arParams["TEMPLATE_THEME"]))
{
	$arAvailableThemes = array();
	$dir = trim(preg_replace("'[\\\\/]+'", "/", dirname(__FILE__)."/themes/"));
	if (is_dir($dir) && $directory = opendir($dir))
	{
		while (($file = readdir($directory)) !== false)
		{
			if ($file != "." && $file != ".." && is_dir($dir.$file))
				$arAvailableThemes[] = $file;
		}
		closedir($directory);
	}

	if ($arParams["TEMPLATE_THEME"] == "site")
	{
		$solution = COption::GetOptionString("main", "wizard_solution", "", SITE_ID);
		if ($solution == "eshop")
		{
			$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
			$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
			$theme = COption::GetOptionString("main", "wizard_".$templateId."_theme_id", "blue", SITE_ID);
			$arParams["TEMPLATE_THEME"] = (in_array($theme, $arAvailableThemes)) ? $theme : "blue";
		}
	}
	else
	{
		$arParams["TEMPLATE_THEME"] = (in_array($arParams["TEMPLATE_THEME"], $arAvailableThemes)) ? $arParams["TEMPLATE_THEME"] : "blue";
	}
}
else
{
	$arParams["TEMPLATE_THEME"] = "blue";
}

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";


/* объединение Скидки, Новинки, Хита в блок Категория */
$modFields = array();
$tmp_modFields = explode(",",COption::GetOptionString("uvelirsoft", "CATALOG_FILTER_FIELDS_MOD_NOTITLE"));
// зачистим пробелы
foreach ($tmp_modFields as $arField) {
	$arField = trim($arField);
  	$modFields[$arField] = $arField;
}

$arResult['modFields'] = array(
	'NAME' => 'Категории',
	'PROPERTY_TYPE' => 'L',
	'DISPLAY_EXPANDED' => 'Y',
	'IS_CATEGORII' => 'Y',
	'VALUES' => array()
);
foreach($arResult['ITEMS'] as $key => $arItem){
	// категории
	if(array_key_exists($arItem['CODE'], $modFields)){
		if(count($arItem['VALUES']) == 1){
			foreach($arItem['VALUES'] as $keyval => $arValue){
				$arResult['modFields']['VALUES'][$keyval] = $arValue;
				$arResult['modFields']['VALUES'][$keyval]['VALUE'] = $arItem['NAME'];
				$arResult['modFields']['VALUES'][$keyval]['UPPER'] = mb_strtoupper($arItem['NAME']);
			}
			unset($arResult['ITEMS'][$key]);
		}
	}
}
array_unshift($arResult['ITEMS'], $arResult['modFields']);
/* !объединение Скидки, Новинки, Хита в блок Категория */
