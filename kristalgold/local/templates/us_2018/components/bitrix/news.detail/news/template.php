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

<div class="row order title-news">
    <div class="col-md-12">
        <div class="profile"><?=$arResult["NAME"]?></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <?
            if($arResult["DETAIL_PICTURE"]["SRC"]){
                ?>
                <img
                    class="detail_picture"
                    border="0"
                    src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
                    alt="<?=$arResult["NAME"]?>"
                    title="<?=$arResult["NAME"]?>"
                    />
                <?
            }
        ?>        

                
        <?echo $arResult["DETAIL_TEXT"];?>     
        <br><br>                 
        <?
            echo makeSocialButton("http://".$_SERVER['SERVER_NAME'].$arResult["DETAIL_PAGE_URL"],$arResult["NAME"],"http://".$_SERVER['SERVER_NAME'].($arResult["PREVIEW_PICTURE"]["SRC"] ? $arResult["PREVIEW_PICTURE"]["SRC"]:$arResult["DETAIL_PICTURE"]["SRC"]),"");
        ?>        
        <br>  
       	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
	<?endif;?>         
                
                
    </div>
</div>