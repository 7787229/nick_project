<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
	<?$currentItemsCount = COption::GetOptionString("uvelirsoft", "MAIN_TOPMENU_LEFT_COUNT", "6");?>
	<?$menuPosition = (in_array($arParams["MENU_POSITION"], array('LEFT', 'RIGHT')) ? " ".strtolower($arParams["MENU_POSITION"]) : "");?>

	<ul class="nav navbar-nav topmenu<?=$menuPosition?>">
	<?
	$previousLevel = 0;
	$index = 1;
	foreach($arResult as $arItem):?>
		<?if ($arItem["DEPTH_LEVEL"] == 1){
			if($arParams["MENU_POSITION"] == "LEFT" && $index > $currentItemsCount) {
				break;
			}elseif($arParams["MENU_POSITION"] == "RIGHT" && $index <= $currentItemsCount){
				$index++;
				continue;
			}else{
				$index++;
			}
		}
		?>


		<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
			<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
		<?endif?>

		<?if ($arItem["IS_PARENT"]):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a>
					<ul>
			<?else:?>
				<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>" class="parent"><?=$arItem["TEXT"]?></a>
					<ul>
			<?endif?>

		<?else:?>

			<?if ($arItem["PERMISSION"] > "D"):?>

				<?if ($arItem["DEPTH_LEVEL"] == 1):?>
					<li><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a></li>
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
	</ul>
<?endif?>
