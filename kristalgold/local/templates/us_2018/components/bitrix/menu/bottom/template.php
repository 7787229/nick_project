<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<ul>
	<?if(in_array($arParams['ROOT_MENU_TYPE'], array('bottom_help', 'bottom_info', 'bottom_service'))){?>
	<!-- <li>
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "inc",
				"COMPOSITE_FRAME_MODE" => "A",
				"COMPOSITE_FRAME_TYPE" => "AUTO",
				"EDIT_TEMPLATE" => "",
				"PATH" => "/include/".$arParams['ROOT_MENU_TYPE'].".php"
			)
		);?>
	</li> -->
	<?}?>
<?
foreach($arResult as $arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	
<?endforeach?>

</ul>
<?endif?>