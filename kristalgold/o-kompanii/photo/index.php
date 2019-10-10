<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Эксклюзивные драгоценности собственного производства со специальной скидкой при заказе в интернет-магазине!");
$APPLICATION->SetPageProperty("title", "Кристалл Мечты | Фотогалерея");
$APPLICATION->SetTitle("Фотогалерея");
?><div class="menu_company">
	 <?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"about",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_THEME" => "site",
		"ROOT_MENU_TYPE" => "zapas",
		"USE_EXT" => "N"
	)
);?>
</div>
<?$APPLICATION->IncludeFile("iblock/photo/sections_top.php", Array(
	'IBLOCK_TYPE'	=>	'photo',	// Тип инфо-блока
	'IBLOCK_ID'	=>	'54',
	'PARENT_SECTION_ID'	=>	'0',
	'SECTION_SORT_FIELD'	=>	'date',
	'SECTION_SORT_ORDER'	=>	'desc',
	'SECTION_COUNT'	=>	'20',
	'SECTION_URL'	=>	'/o-kompanii/gallery/section.php?',
	'ELEMENT_COUNT'	=>	'20',
	'SECTION_COUNT'	=>	'20',
	'LINE_ELEMENT_COUNT'	=>	'3',
	'LINE_SECTION_COUNT'	=>	'10',
	'ELEMENT_SORT_FIELD'	=>	'sort',
	'ELEMENT_SORT_ORDER'	=>	'asc',
	'FILTER_NAME'	=>	'arrFilter',
	'CACHE_FILTER'	=>	'N',
	'CACHE_TIME'	=>	'0',
	'DISPLAY_PANEL'	=>	'Y',
));?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>