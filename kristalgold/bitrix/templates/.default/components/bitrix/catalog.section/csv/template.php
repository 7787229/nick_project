<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



$strokaTITLE = '"ID","NAME","CODE","DETAIL_TEXT","DETAIL_PICTURE","QUANTITY","WEIGHT","ARTNUMBER","NEWPRODUCT","TYPE","METAL","PROBA","OFFER","RAZMER","VSTAVKA","PRICE","VES","COUNT"';

file_put_contents("import.csv",$strokaTITLE."\n");



foreach ($arResult["ITEMS"] as $arItem) {

if($arItem["ID"]==1839){
	printvar("fd",$arItem);
}


	$strokaHead = $arItem["ID"].
		',"'.htmlspecialchars(str_replace("\n", " ",$arItem["NAME"])).
		'","'.htmlspecialchars(str_replace("\n", " ",$arItem["CODE"])).
		'","'.htmlspecialchars(str_replace("\n", " ",$arItem["DETAIL_TEXT"])).
		'","'.$arItem["DETAIL_PICTURE"]["SRC"].
		'","'.$arItem["CATALOG_QUANTITY"].
		'","'.$arItem["CATALOG_WEIGHT"].
		'","'.$arItem["PROPERTIES"]["ARTNUMBER"]["VALUE"].
		'","'.$arItem["PROPERTIES"]["NEWPRODUCT"]["VALUE"].		
		'","'.$arItem["PROPERTIES"]["TYPE"]["VALUE"].	
		'","'.implode(", ",$arItem["PROPERTIES"]["METAL"]["VALUE"]).
		'","'.$arItem["PROPERTIES"]["PROBA"]["VALUE"].'"';
		
		foreach ($arItem["OFFERS"] as $key => $arOffer) {
			$stroka = $strokaHead.','.$arOffer["ID"].
				',"'.$arOffer["PROPERTIES"]["RAZMER"]["VALUE"].
				'","'.$arOffer["PROPERTIES"]["VSTAVKA"]["VALUE"].
				'","'.$arOffer["CATALOG_PRICE_1"].
				'","'.$arOffer["CATALOG_WEIGHT"].
				'","'.$arOffer["CATALOG_QUANTITY"].'"'."\n";

				file_put_contents("import.csv",$stroka,FILE_APPEND);
		}



}
?>
123