<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<ul id="bottom-catalog-menu">

<?
$previousLevel = 0;
	
$arLeft = '';


foreach($arResult as $arItem):?>






	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<li class="main"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a>
				<ul>
		<?else:?>
			<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>" class="parent"><?=$arItem["TEXT"]?></a>
				<ul>
		<?endif?>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>
			
			<?if ($arItem["DEPTH_LEVEL"] == 1):?>

				<?if ($arParams['POSITION_LEFT'] == "Y" && $arItem['UF_SHOW_FULL_BOTTOM'] == 0):
					$class = $arItem["SELECTED"] ? 'root-item-selected' : 'root-item';
					$arLeft .= '<a href = "'.$arItem["LINK"].'" class = "'.$class.'">'.$arItem["TEXT"].'</a>';
					?>
				<?else:?>
			
					<li><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a></li>
				<?endif?>
			<?else:?>
				<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li><a href="" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
			<?else:?>
				<li><a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
			<?endif?>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
	
<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>

	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

<?if ($arParams['POSITION_LEFT'] == 'Y'):?>
	<li class="main main_left">
		
			<?echo $arLeft;?>
		
	</li>
<?endif?>

</ul>
<div class="menu-clear-left"></div>
<?endif?>
