<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!-- <?print_r($arResult);?>-->
<?if (!empty($arResult)):?>
<ul class="about_menu">

<?
$i=0;
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<li<?php echo ($i==0?' class="first"':''); ?>><a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a></li>
	<?else:?>
		<li<?php echo ($i==0?' class="first"':''); ?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?endif?>
	<? $i++; ?>
<?endforeach?>

</ul>
<?endif?>