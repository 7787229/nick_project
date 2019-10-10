<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

// height block banner
$heightBannerBlock = COption::GetOptionString("uvelirsoft", "CATALOG_BANNER_LINE_HEIGHT","200");


if(count($arResult["ITEMS"]) < 1)
	return;?>


<div class="row">
<?$width = 0;
    foreach($arResult["ITEMS"] as $arItem):	
        ?>
            <div class="col-md-<?=$arItem["DISPLAY_PROPERTIES"]["BANNER_WIDTH"]["VALUE_XML_ID"]?> col-sm-6 col-xs-12" style="background-image:url(<?=$arItem['DETAIL_PICTURE']['SRC']?>);float:left;height:<?=$heightBannerBlock?>px">
                    <a href="<?=(!empty($arItem['DISPLAY_PROPERTIES']['BANNER_LINK'])) ? $arItem['DISPLAY_PROPERTIES']['BANNER_LINK']['VALUE'] : 'javascript:void(0)'?>" class="usbanner">
                            <!-- <p class="banner-name"><?=$arItem['DISPLAY_PROPERTIES']['BANNER_TITLE']['VALUE']?></p> -->
                    </a>
            </div>                
        <?
    endforeach;
?>
</div>
<?


        