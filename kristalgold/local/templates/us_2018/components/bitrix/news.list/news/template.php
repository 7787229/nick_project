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

<div class="row order">
    <div class="col-md-12 flex-container">
        <h1 class="profile"><?=$APPLICATION->GetTitle()?></h1>
        <div class="profile" style="margin-left: 10px;"><a href="/aktsii/">Акции</a></div>
    </div>
</div>


<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<?foreach($arResult["ITEMS"] as $arItem){?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>        
        
        <div class="row row_news" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                <?
                $imageExist = false;
                if($arItem["PREVIEW_PICTURE"]["SRC"] or $arItem["DETAIL_PICTURE"]["SRC"]){
                    $imageExist = true;
                }

                if($imageExist){
                    ?>
                    <div class="col-sm-3 col-md-3">
                            <div class="news_img" style="background-image:url(<?=($arItem["PREVIEW_PICTURE"]["SRC"] ? $arItem["PREVIEW_PICTURE"]["SRC"]:$arItem["DETAIL_PICTURE"]["SRC"])?>)"></div>

                    </div>
                    <?
                }
                ?>
                <div class="col-sm-<?=($imageExist ? "9":"12")?> col-md-<?=($imageExist ? "9":"12")?> news_info">
                        <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]){?>
                            <div class="title_news">
                                <?=$arItem["NAME"]?>
                            </div>
                        <?}?>
                        <?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
                                <div class="date_news"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></div>
                        <?endif?>                    
                        <div class="text_news">
                            <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]){?>
                                    <?=$arItem["PREVIEW_TEXT"]?>
                            <?}?>
                        </div>
                        <?
                            echo makeSocialButton("http://".$_SERVER['SERVER_NAME'].$arItem["DETAIL_PAGE_URL"],$arItem["NAME"],"http://".$_SERVER['SERVER_NAME'].($arItem["PREVIEW_PICTURE"]["SRC"] ? $arItem["PREVIEW_PICTURE"]["SRC"]:$arItem["DETAIL_PICTURE"]["SRC"]),"");
                        ?>
                        <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])){?>
                            <div class="readmore">
                                <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">Читать дальше</a>
                            </div>     
                        <?}?>  
                               
                </div>
        </div>

<?
}
?>


<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
