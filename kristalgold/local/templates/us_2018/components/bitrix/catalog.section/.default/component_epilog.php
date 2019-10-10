<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'showMore' || $request->get('action') === 'deferredLoad'))
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer
	));
}


// $APPLICATION->SetTitle('title', $arParams["SEO_FILTER"]['TITLE_SEO_VALUE']);
if ($arParams["SEO_FILTER"]['TITLE_SEO_VALUE']) {
	$APPLICATION->SetPageProperty("title", $arParams["SEO_FILTER"]['TITLE_SEO_VALUE']);
} else {
	$APPLICATION->SetPageProperty("title", $arResult["IPROPERTY_VALUES"]['SECTION_META_TITLE']);
}
if ($arParams["SEO_FILTER"]['KEYWORDS_SEO_VALUE']) {
	$APPLICATION->SetPageProperty('keywords', $arParams["SEO_FILTER"]['KEYWORDS_SEO_VALUE']);
} else {
	$APPLICATION->SetPageProperty("keywords", $arResult["IPROPERTY_VALUES"]['SECTION_META_KEYWORDS']);
}
if ($arParams["SEO_FILTER"]['DESCRIPTION_SEO_VALUE']) {
	$APPLICATION->SetPageProperty('description', $arParams["SEO_FILTER"]['DESCRIPTION_SEO_VALUE']);
} else {
	$APPLICATION->SetPageProperty("description", $arResult["IPROPERTY_VALUES"]['SECTION_META_DESCRIPTION']);
}
