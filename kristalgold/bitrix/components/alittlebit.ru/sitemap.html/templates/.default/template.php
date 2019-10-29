<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
function buildList($section,  $col_md_4 = false){
	
	if(!isset($section["CHILD"]) && !isset($section["ITEMS"])){
		echo '<ul>';
			foreach($section as $item_id => $arItem){
				echo '<li><a href="'.$arItem["DETAIL_PAGE_URL"].'">'.$arItem["NAME"].'</a></li>';
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
				echo '<li><a href="'.$arItem["DETAIL_PAGE_URL"].'">'.$arItem["NAME"].'</a></li>';
			}
		echo "</ul>";
	}
	
}
?>
<ul class="sitemap_html">
	<li><a href="/">Главная</a></li>
	<? $i1 = 0;
	
	$param = array();
	foreach ($arResult['FOLDERS'] as $k => $v) {
		$param[$k] = $v['PATH'];
	}
	array_multisort($param, SORT_ASC, SORT_STRING, $arResult['FOLDERS']);

	
	$arResult["FOLDERS"][] = array();
	foreach($arResult["FOLDERS"] as $root => $arFolder){
		$arFolder["count"] = count(explode('/', trim($arFolder["PATH"], '/')));
		// Начинать со второго индекса, смещение
		if ($i1 > 0) { ?>
			<li><a href="<?=$arrPrev["PATH"];?>"><?=$arrPrev["NAME"];?></a>
			
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
							foreach($arResult["IBLOCKS"][$match[1]] as $sect_id => $arSection) {
								//print_r($arSection);
								//if($sect_id !== 0) 
								buildList($arSection);
							}
							//echo '</ul>';
							
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
				echo '</li></ul></li>';
			} else {
				echo '</li>';
			}
		}
		$arrPrev = $arFolder;
		$i1++;
	} 

	// Инфоблоки, которые остались
	foreach($arResult["IBLOCKS"] as $ib_id => $arIblock){
		if(!empty($arIblock["ITEMS"])) { 
			foreach($arIblock["ITEMS"] as $arItem) { ?>
				<li><a href="<?=$arItem["DETAIL_PAGE_URL"];?>"><?=$arItem["NAME"];?></a></li>
			<? }
		} else {
			foreach($arIblock as $sect_id => $arSection) {
				if($sect_id == 0){
					continue;
				}else{
					buildList($arSection);
				}
			}
		}
	}
	
	//print_r($arResult["IBLOCKS"]);
	?>
</ul>