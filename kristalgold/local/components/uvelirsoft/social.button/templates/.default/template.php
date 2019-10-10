<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>

<style>    
<?
foreach ($arResult["ITEMS"] as $key => $arItem) {
    ?>
        i.fa-<?=$arItem["ICON"]?> {
            color:<?=$arItem["COLOR"]?>;
        }
        i.fa-<?=$arItem["ICON"]?>:hover {
            color:<?=$arItem["COLOR_HOVER"]?>;
        }        
    <?
}
?>
</style>        
<?        
foreach ($arResult["ITEMS"] as $key => $arItem) {
    ?>
        <a href="<?=$arItem["LINK"]?>" target="_blank"><i class="fa fa-<?=$arItem["ICON"]?> <?=$arParams["SOCIAL_ICON_SIZE"]?>"></i></a>
        
    <?
}
