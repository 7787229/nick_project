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
$this->setFrameMode(true);
?>
<?
// все отложенные товары
$arDelay = $arResult["FAVORITES"];
//printvar('',$arDelay);

//printvar('',$arParams["JS_ID"]);
// элемент каталога
$arItem = $arParams["PRODUCT"];

// пометим отложенные товары
$favorite = false;
if (!isset($arItem['OFFERS']) || empty($arItem['OFFERS'])){
	// простой товар
	if($arDelay[$arItem["ID"]]=="Y"){$favorite = true;}
}else{
	foreach ($arItem['OFFERS'] as $arOffer) {
		if($arDelay[$arOffer["ID"]]=="Y"){
			$favorite = true;
			break;
		}
	}
}
?>
<div class="fv-icon">
	<span  id="<?=$arParams["DELAY_LINK"];?>" class="fv-favorite<?=($favorite ? " active":"")?>">
		<i class="fa fa-<?=($favorite ? "heart":"heart-o")?>" aria-hidden="true"></i>
	</span>                
</div>
<?
//Массив параметров объекта класса JCCatalogFavorite
$arJSParams = array(
	'ID'=> $arParams['JS_ID'],
	'VISUAL' => array(
		'DELAY_ID' => $arParams["DELAY_LINK"]
	)
);

$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $arParams["DELAY_LINK"]);

?>
<!-- Создание Java Script объекта -->
<script type="text/javascript">
	var <?=$strObName;?> = new JCCatalogFavorite(<?=CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>