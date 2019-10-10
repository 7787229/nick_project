<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");

if (!isset($arParams['FILTER_VIEW_MODE']) || (string)$arParams['FILTER_VIEW_MODE'] == '')
	$arParams['FILTER_VIEW_MODE'] = 'VERTICAL';
$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');

$isVerticalFilter = ('Y' == $arParams['USE_FILTER'] && $arParams["FILTER_VIEW_MODE"] == "VERTICAL");
$isSidebar = ($arParams["SIDEBAR_SECTION_SHOW"] == "Y" && isset($arParams["SIDEBAR_PATH"]) && !empty($arParams["SIDEBAR_PATH"]));
$isFilter = ($arParams['USE_FILTER'] == 'Y');

if ($isFilter)
{
	$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ACTIVE" => "Y",
		"GLOBAL_ACTIVE" => "Y",
	);
	if (0 < intval($arResult["VARIABLES"]["SECTION_ID"]))
		$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
	elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"])
		$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];

	$obCache = new CPHPCache();
	if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog"))
	{
		$arCurSection = $obCache->GetVars();
	}
	elseif ($obCache->StartDataCache())
	{
		$arCurSection = array();
		if (Loader::includeModule("iblock"))
		{
			$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache("/iblock/catalog");

				if ($arCurSection = $dbRes->Fetch())
					$CACHE_MANAGER->RegisterTag("iblock_id_".$arParams["IBLOCK_ID"]);

				$CACHE_MANAGER->EndTagCache();
			}
			else
			{
				if(!$arCurSection = $dbRes->Fetch())
					$arCurSection = array();
			}
		}
		$obCache->EndDataCache($arCurSection);
	}
	if (!isset($arCurSection))
		$arCurSection = array();
}


global $APPLICATION;
$dir = $APPLICATION->GetCurDir();

$arSEO=array();
if (defined("SEO_FILTER_IBLOCK_ID")) {

	$obCache  = new CPHPCache();
	$cacheLifetime  = 43200;  // 12 часов
	$cacheID        = SEO_FILTER_IBLOCK_ID.'_'.$dir;
	$cachePath      = '/'.SITE_ID.'/SEO_IBLOCK/';
	if($obCache->InitCache($cacheLifetime, $cacheID, $cachePath) ) {
	   $rsResult = $obCache->GetVars();
	   $arSEO = $rsResult[$cacheID];
	}
		elseif( $obCache->StartDataCache()  )
	{

		$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_TITLE_SEO", "PROPERTY_DESCRIPTION_SEO", "PROPERTY_CONTENT", "PROPERTY_KEYWORDS_SEO", "PROPERTY_H1_SEO");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
		$arFilter = Array(
			"IBLOCK_ID" => SEO_FILTER_IBLOCK_ID,
			"ACTIVE_DATE" => "Y",
			"ACTIVE" => "Y",
			"=NAME" => $dir
		);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 1), $arSelect);
		if ($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arSEO=array(
				'TITLE_SEO_VALUE'  => $arFields['PROPERTY_TITLE_SEO_VALUE'],
				'KEYWORDS_SEO_VALUE' => $arFields['PROPERTY_KEYWORDS_SEO_VALUE'],
				'DESCRIPTION_SEO_VALUE' => $arFields['PROPERTY_DESCRIPTION_SEO_VALUE'],
				'H1_SEO_VALUE' => $arFields['PROPERTY_H1_SEO_VALUE'],
				'CONTENT_VALUE' => $arFields['PROPERTY_CONTENT_VALUE']
			);
		}

		$obCache->EndDataCache(array($cacheID => $arSEO));

	}
}

$elems_seo=false;
if ( count($arSEO) ) {
	// $APPLICATION->SetPageProperty('og:title', $arSEO['TITLE_SEO_VALUE']);
	// $APPLICATION->SetPageProperty('og:keywords', $arSEO['KEYWORDS_SEO_VALUE']);
	// $APPLICATION->SetPageProperty('og:description', $arSEO['DESCRIPTION_SEO_VALUE']);
	$elems_seo=true;
	$h1 = $arSEO['H1_SEO_VALUE'];
	$seo_text = $arSEO['CONTENT_VALUE'];
}
						

?>
<div class="row">
<?
if ($isVerticalFilter)
	include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_vertical.php");
else
	include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/section_horizontal.php");
?>
</div>
