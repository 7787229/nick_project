<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$template = $arParams['TEMPLATE_THEME'];

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$arParams['TEMPLATE_THEME'] = $template;

$arSelect = array('ID','IBLOCK_ID','UF_ACTIONS');
$arResult["ACTIONS"]=false;
$rsSection = CIBlockSection::GetList(array(), array('IBLOCK_ID'=>$arParams["IBLOCK_ID"],'ID' => $arParams["SECTION_ID"]), false, $arSelect);
if ($arSection = $rsSection->GetNext()) {
	$arResult["ACTIONS"]=$arSection["UF_ACTIONS"]==1;
}
