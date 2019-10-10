<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");
?><div class="row order">
	<div class="col-md-12">
		<div class="profile">
			 Контакты
		</div>
	</div>
</div>
<div class="container_contakts">
	<div class="row row_kontakts">
		<div class="col-md-6 col-sm-6 col-sx-12">
			<p>
				 Офис г.Кострома (центральный)
			</p>
			<p>
				 Тел.:<a href="tel:(4942) 39-20-37">(4942) 39-20-37</a>
			</p>
			<p>
				 Адрес: г.Кострома, ул.Заволжская д.219 оф.146
			</p>
		</div>
		<div class="col-md-6 col-sn-6 col-sx-12">
			<p>
				 Офис г.Москва (продажи и внедрения)
			</p>
			<p>
				 Тел.:<a href="tel:(499) 217-57-57">(499) 217-57-57</a>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="karta">
			</div>
		</div>
	</div>
</div>
<?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CONTROLS" => array(
			0 => "ZOOM",
			1 => "SMALLZOOM",
			2 => "TYPECONTROL",
			3 => "SCALELINE",
			4 => "SEARCH",
		),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:57.74131555980978;s:10:\"yandex_lon\";d:40.91504815863099;s:12:\"yandex_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:40.915444177855;s:3:\"LAT\";d:57.741598240416;s:4:\"TEXT\";s:36:\"ООО \"НПП ЮвелирСофт\"\";}}}",
		"MAP_HEIGHT" => "500",
		"MAP_ID" => "XV5wojYVX1nNTBrcIHjQTQqGjRYHssXA",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_RIGHT_MAGNIFIER",
			2 => "ENABLE_DRAGGING",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>