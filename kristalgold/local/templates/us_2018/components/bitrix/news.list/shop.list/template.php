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
<?//printvar("",$arResult);?>
<div class="news-list">
	<h1 class="hide-h1"><?=$APPLICATION->GetTitle()?></h1>
	<?
		$firstPoint = array(
			"LAT" => $arResult['ITEMS'][0]["PROPERTIES"]["LAT"]["VALUE"],
			"LON" => $arResult['ITEMS'][0]["PROPERTIES"]["LON"]["VALUE"]
		);
		$placemarks = array();
		foreach($arResult['ITEMS'] as $arItemKey => $arItemVal){
			$text = "<div class='date-store'>";
			if(!empty($arItemVal["PROPERTIES"]["METRO_COLOR"]["VALUE_XML_ID"]) && !empty($arItemVal["PROPERTIES"]["METRO"]["VALUE"])){
				$text .= "<p class='name ".$arItemVal["PROPERTIES"]["METRO_COLOR"]["VALUE_XML_ID"]."'>".$arItemVal["PROPERTIES"]["METRO"]["VALUE"].",</p><br>";
			}
			$text .= $arItemVal["NAME"];
			if(!empty($arItemVal["PROPERTIES"]["TOWN"]["VALUE"]) or !empty($arItemVal["PROPERTIES"]["ADRESS"]["VALUE"])){
				$text .= "<br>Адрес: ";
				if(!empty($arItemVal["PROPERTIES"]["TOWN"]["VALUE"])){
					$text .= $arItemVal["PROPERTIES"]["TOWN"]["VALUE"].",<br>";
				}
				if(!empty($arItemVal["PROPERTIES"]["ADRESS"]["VALUE"])){
					$text .= $arItemVal["PROPERTIES"]["ADRESS"]["VALUE"]."<br>";
				}
			}
			if(!empty($arItemVal["PROPERTIES"]["PHONE"]["VALUE"])){
				$text .= "Телефон:".$arItemVal["PROPERTIES"]["PHONE"]["VALUE"]."<br>";
			}
			if(!empty($arItemVal["PROPERTIES"]["WORK_TIME"]["VALUE"])){
				$text .= "Время работы:".$arItemVal["PROPERTIES"]["WORK_TIME"]["VALUE"];
			}
			$text .= "</div>";

			$placemarks[] = array(
				"TEXT" => $text,
				"LON" => $arItemVal["PROPERTIES"]["LON"]["VALUE"],
				"LAT" => $arItemVal["PROPERTIES"]["LAT"]["VALUE"],
				"INDEX" => $arItemKey + 1
			);
		}

		$MAP_DATA = Array(
		   "yandex_lat" => $firstPoint['LAT'],
		   "yandex_lon" => $firstPoint['LON'],
		   "yandex_scale" => "9",
		   "PLACEMARKS" => $placemarks
		);
	?>

	<div class="list-stores" >
		<div class='row'>
		<?foreach($arResult['ITEMS'] as $arItemKey => $arItemVal){?>
			<div class='col-md-4 col-sm-6 col-sx-12'>
				<a class='store_block' href="#top" onclick="activeBaloon(this);">
					<div class="store" data-count="<?=$arItemKey + 1;?>">
						<div class="date-store">
							<?if(!empty($arItemVal["PROPERTIES"]["METRO_COLOR"]["VALUE_XML_ID"]) && !empty($arItemVal["PROPERTIES"]["METRO"]["VALUE"])){?>
								<p class="name <?=$arItemVal["PROPERTIES"]["METRO_COLOR"]["VALUE_XML_ID"];?>"><?=$arItemVal["PROPERTIES"]["METRO"]["VALUE"];?>,</p>
							<?}?>
							<b><?=$arItemVal["NAME"];?></b><br>
							<?if(!empty($arItemVal["PROPERTIES"]["TOWN"]["VALUE"]) or !empty($arItemVal["PROPERTIES"]["ADRESS"]["VALUE"])){?>
								Адрес:
								<?if(!empty($arItemVal["PROPERTIES"]["TOWN"]["VALUE"])){?>
									<?=$arItemVal["PROPERTIES"]["TOWN"]["VALUE"];?>,<br>
								<?}?>
								<?if(!empty($arItemVal["PROPERTIES"]["ADRESS"]["VALUE"])){?>
									<?=$arItemVal["PROPERTIES"]["ADRESS"]["VALUE"];?><br>
								<?}?>
							<?}?>
							<?if(!empty($arItemVal["PROPERTIES"]["PHONE"]["VALUE"])){?>
								Телефон: <?=$arItemVal["PROPERTIES"]["PHONE"]["VALUE"];?><br>
							<?}?>
							<?if(!empty($arItemVal["PROPERTIES"]["WORK_TIME"]["VALUE"])){?>
								Время работы: <?=$arItemVal["PROPERTIES"]["WORK_TIME"]["VALUE"];?>
							<?}?>
						</div>
					</div>
				</a>
			</div>
		<?}?>
		</div>
	</div>
	<p><a name="top"></a></p>
	<div class="map" >
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
				"MAP_DATA" => serialize($MAP_DATA),
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
		);?>
	</div>
</div>

<script>
	function activeBaloon(athis){
		var id =  Number($(athis).children('.store').attr('data-count')) - 1;
		var center = arObjects.PLACEMARKS[id].geometry.getCoordinates();
		arObjects.PLACEMARKS[id].balloon.open();
		map.setCenter(center);
		map.setZoom(17);
	}
</script>
