<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

// height block banner
// $heightBannerBlock = COption::GetOptionString("uvelirsoft", "CATALOG_ACTIONS_LINE_HEIGHT","300");
$heightBannerBlock = "240";


if(count($arResult["ITEMS"]) < 1)
	return;?>

<h1 class="hide-h1">Акции</h1>
<div class="row flex-aktsii">
<?$width = 0;
    foreach($arResult["ITEMS"] as $arItem):	
        ?>
            <div class="col-md-<?=$arItem["DISPLAY_PROPERTIES"]["BANNER_WIDTH"]["VALUE_XML_ID"]?> col-sm-6 col-xs-12 collection-banner" style="background-image:url(<?=$arItem['PREVIEW_PICTURE']['SRC']?>);height:<?=$heightBannerBlock?>px">
                    <?
                       if(empty($arItem['DISPLAY_PROPERTIES']['BANNER_LINK'])){
                        $link = $arItem['DETAIL_PAGE_URL'];
                       }
                       else{
                        $link = $arItem['DISPLAY_PROPERTIES']['BANNER_LINK']['VALUE']; 
                       }

                    ?>
                    <a href="<?=$link?>" class="usbanner">
                            <p class="banner-name"><?=$arItem['DISPLAY_PROPERTIES']['BANNER_TITLE']['VALUE']?></p>
                    </a>
            </div>                
        <?
    endforeach;
?>
</div>
<?


        