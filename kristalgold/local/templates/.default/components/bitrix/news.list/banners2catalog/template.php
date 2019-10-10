<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

// height block banner
$heightBannerBlock = COption::GetOptionString("uvelirsoft", "CATALOG_ACTIONS_LINE_HEIGHT","300");


if(count($arResult["ITEMS"]) < 1)
	return;?>

<h1 class="hide-h1"><?=$APPLICATION->GetTitle()?></h1>
<div class="row action_banners">
<?$width = 0;
    foreach($arResult["ITEMS"] as $arItem):
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
            <div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="col-md-<?=$arItem["PROPERTIES"]["BANNER_WIDTH"]["VALUE_XML_ID"]?> col-sm-<?=$arItem["PROPERTIES"]["BANNER_WIDTH"]["VALUE_XML_ID"]?> col-xs-12 collection-banner<?=($arItem['PROPERTIES']['ALIGN']['VALUE_XML_ID'] ? " ".$arItem['PROPERTIES']['ALIGN']['VALUE_XML_ID']:"")?>" style="height:<?=($arItem["PROPERTIES"]["BANNER_WIDTH"]["VALUE_XML_ID"]!=12 ? ($arItem["PROPERTIES"]["BANNER_HEIGHT"]["VALUE"] ? $arItem["PROPERTIES"]["BANNER_HEIGHT"]["VALUE"]:$heightBannerBlock):"")?>px">
                    <a href="<?=(!empty($arItem['PROPERTIES']['BANNER_LINK']['VALUE'])) ? $arItem['PROPERTIES']['BANNER_LINK']['VALUE'] : ($arItem["CODE"] ? $arItem["CODE"]:'javascript:void(0)')?>" class="usbanner">
                    <img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" alt="<?=$arItem['PROPERTIES']['BANNER_TITLE']['VALUE']?>">
							<?
							if($arItem['PROPERTIES']['BANNER_TITLE']['VALUE']){
								?>
								<p class="banner-name"><?=$arItem['PROPERTIES']['BANNER_TITLE']['VALUE']?></p>
								<?
							}
							?>
                    </a>
            </div>
        <?
    endforeach;
?>
</div>
