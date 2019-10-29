<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Карта сайта");
?>

<div class="container">
	<h1>Карта сайта</h1>
<?$APPLICATION->IncludeComponent("alittlebit.ru:sitemap.html", "site_map1", Array(
	"COMPONENT_TEMPLATE" => "site_map",
		"IBLOCK_ID" => "",	// Выберите инфоблоки, элементы которых попадут в карту сайта
		"INCLUDE_ELEMENTS" => "Y",	// Если в инфоблоке есть разделы, включить в карту сайта элементы этих разделов
		"EXCLUDED_FOLDERS" => array(	// Укажите папки, которые не будут участвовать при построении карты сайта
			0 => "bitrix",
			1 => "upload",
			2 => "search",
			3 => "cgi-bin",
			4 => "images",
			5 => "auth",
			6 => "temp",
			7 => "local",
			8 => "",
		),
		"INCLUDE_SEO" => "N",	// Включить SEO инфоблок
		"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
		"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
	),
	false
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>