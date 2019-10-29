<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?

$arSort = array(
	'/about/',
	'/personal/',
);

$arNoPath = array(
	'/magazin/personal/order/',
	'/magazin/personal/order/payment/',
	'/magazin/personal/order/make/',
);


function buildList($section,   $col_md_4 = false, $arResult = false){

	$arNoPathCatalog = array(
		'/magazin/catalog/',
	);

	

	if(!isset($section["CHILD"]) && !isset($section["ITEMS"])){
		echo '<ul>';
			foreach($section as $item_id => $arItem){
				if (!in_array($arItem['DETAIL_PAGE_URL'], $arNoPathCatalog)) {
					echo '<li><a target="_blank" href="'.$arItem["DETAIL_PAGE_URL"].'">'.$arItem["NAME"].'</a></li>';
				}
				
			}
			
			
		echo "</ul>";
	}

	if(!empty($section["CHILD"])){
		echo '<ul class="row sm_row">';
			foreach($section["CHILD"] as $child_id => $arChild){

				buildList($arChild);
			}
		echo "</ul>";
	}
	if(!empty($section["ITEMS"])){
		echo '<ul>';
			foreach($section["ITEMS"] as $item_id => $arItem){
				echo '<li><a target="_blank" href="'.$arItem["DETAIL_PAGE_URL"].'">'.$arItem["NAME"].'</a></li>';
			}
		echo "</ul>";
	}

}

function buildCatalog($section){
	
	foreach($section as $arItem) { 
		/*
		if($arItem["ID"]==325){
			$url='/magazin/catalog/filter/osnovnaya_vstavka-is-izumrud-prirodnyj-uralskij-or-izumrud-gidrotermalnyj/vstavki-is-izumrud-or-izumrud-gidrotermalnyj-or-izumrud*-or-izumrud-gt-or-izumrud-prirodnyj-uralskij/apply/';
		} else {
			$url=$arItem["SECTION_PAGE_URL"];
}*/

		$url=$arItem["SECTION_PAGE_URL"];
		?>
		<li><a target="_blank" href="<?=$url;?>"><?=$arItem["NAME"];?></a>
		<?//printvar('', $arItem);?>
		<?
			if($arItem['CHILD']){
				//printvar('', $arItem);
				?>
				<ul>
				<?buildCatalog($arItem['CHILD']);?>
				</ul>
				<?
			}
		?>
		</li>
	<?
	}
	
}
?>
<ul class="sitemap_html list-unstyled large">
	<!-- <li><a href="/">Главная</a></li> -->
	<? $i1 = 0;

	$param = array();
	foreach ($arResult['FOLDERS'] as $k => $v) {
		$param[$k] = $v['PATH'];
	}
	array_multisort($param, SORT_ASC, SORT_STRING, $arResult['FOLDERS']);


	// printvar('FOLDERS', $arResult['FOLDERS']);
	// printvar('IBLOCKS', $arResult['IBLOCKS']);

	$arResult["FOLDERS"][] = array();
	foreach($arResult["FOLDERS"] as $root => $arFolder){
		$arFolder["count"] = count(explode('/', trim($arFolder["PATH"], '/')));
		// Начинать со второго индекса, смещение
		if ($i1 > 0) { ?>
				
			<?	
				
				if (in_array($arrPrev["PATH"], $arSort)) {
					$order = 0;
				}
				else{
					$order = 1;
				}
			?>
			<?
			if (!in_array($arrPrev["PATH"], $arNoPath)) {?>
			<?if($arrPrev["PATH"] == '/magazin/'){
				foreach($arResult["ROOT"] as $sectionId => $sectionEl){
					buildCatalog($sectionEl);
				}
			}?>
				<li style="order: <?=$order?>;"><a target="_blank" href="<?=$arrPrev["PATH"];?>"><?=$arrPrev["NAME"];?></a>
			<?}
			?>
			

			<? // Еcли это папка с компонентом инфоблока, то вывести категории этого инфоблока
			$filePath = $_SERVER['DOCUMENT_ROOT'].$arrPrev["PATH"].'index.php';
			if (file_exists($filePath)) {
				$fp = fopen($filePath, 'r');
				$filesize = (int)filesize($filePath);
				if ($fp && $filesize) {
					$text = fread($fp, $filesize);
					// Вытянуть IBLOCK_ID
					preg_match('#IncludeComponent\s*\(\s*"bitrix:(?:(?:catalog(?:\.section)?)|(?:news))".+?"IBLOCK_ID" => "(\d+)",#is', $text, $match);

					if (isset($match[1]) && $match[1]) {


						if (isset($arResult["IBLOCKS"][$match[1]])) {

							//echo '<ul class="row">';
							//printvar($match[1], $arResult["IBLOCKS"][$match[1]]);
							foreach ($arResult["SECTIONS"][$match[1]] as $key => $arSection) {
								//printvar('', $arResult);
								buildList($arSection);
							}
							// foreach($arResult["IBLOCKS"][$match[1]] as $sect_id => $arSection) {
							

							// 	buildList($arSection);
							// }

							// Удалить, чтоб это же не выводилось в основном обходе инфоблоков, что ниже
							unset($arResult["IBLOCKS"][$match[1]]);
						}
					}
				}
				fclose($fp);
			}
			// теги для формирования нужной структуры
			if ($arrPrev["count"] < $arFolder["count"]) {
				echo '<ul>';
			} elseif ($arrPrev["count"] > $arFolder["count"])	{
				// echo '</li></ul></li>';
				echo str_repeat('</li></ul></li>', ($arrPrev["count"] - $arFolder["count"]));
			} else {
				echo '</li>';
			}
		}
		$arrPrev = $arFolder;
		$i1++;
	}
	
	
	
	//printvar('', $arResult["ROOT"] );

	// Инфоблоки, которые остались
	foreach($arResult["IBLOCKS"] as $ib_id => $arIblock){
		if(!empty($arIblock["ITEMS"])) {
			foreach($arIblock["ITEMS"] as $arItem) { ?>
				<li><a target="_blank" href="<?=$arItem["DETAIL_PAGE_URL"];?>"><?=$arItem["NAME"];?></a></li>
			<? }
		} else {
			foreach($arIblock as $sect_id => $arSection) {
				if($sect_id == 0){
					continue;
				}else{
					buildList($arSection, $arResult['ROOT']);
				}
			}
		}
	}

	if ($arParams['INCLUDE_SEO'] == 'Y') {
		?>
		<!-- <ul> -->
			<?foreach($arResult["SEO"] as $arItem){?>
				<li>
					<a href="<?=$arItem['URL']?>" target="_blank"><?=$arItem['NAME']?></a>
				</li>
			<?}?>
		<!-- </ul> -->
		<?
	}

	//print_r($arResult["IBLOCKS"]);
	?>
</ul>
