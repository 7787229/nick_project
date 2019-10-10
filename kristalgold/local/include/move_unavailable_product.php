<?php
/*
перемещение товара без активных предложений в спец.раздел
*/

// # перемещение товаров без активных предложений в спец.раздел
// 0 10,18 * * * root cd /var/www/html/local/include/ && php -f ./move_unavailable_product.php >/dev/null 2>&1
// # перемещение товаров без активных предложений в спец.раздел - проверка ручного запуска
// */5 * * * * apache /usr/bin/php -f /var/www/html/local/include/cron_job.php >/dev/null 2>&1

// ini_set('memory_limit', '2048M');
ini_set('display_errors','On');
error_reporting('E_ALL');

$_SERVER["DOCUMENT_ROOT"] = "/var/www/html";
$dir = $_SERVER["DOCUMENT_ROOT"]."/local/include";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

use Bitrix\Main,
	Bitrix\Iblock\PropertyIndex\Manager;

file_put_contents($dir."/move_unavailable_product.log.txt", date("d.m.Y H:i:s",time())."  START: запуск перемещения товаров в спец.раздел"."\n", FILE_APPEND);

//$CATALOG_IBLOCK_ID = 1;
$OFFERS_IBLOCK_ID = 2;
$PROP_TABLE_NAME = 'b_iblock_element_prop_s2';
$SECTION_ID = 228;

if(
    !CModule::IncludeModule("iblock")
    || !CModule::IncludeModule("catalog")
) return;

// SKU_PROPERTY_ID
$offer = CCatalogSKU::GetInfoByOfferIBlock($OFFERS_IBLOCK_ID);
if (!$offer) return;

$arSettings = array(
    'IBLOCK_ID' => $offer['PRODUCT_IBLOCK_ID'],
    'SKU_IBLOCK_ID' => $OFFERS_IBLOCK_ID,
    'SKU_PROPERTY_ID' => $offer['SKU_PROPERTY_ID'],
    'PRODUCT_TYPE' => 3, // тип товара (3 - товар с предложениями)
    'VERSION' => $offer["VERSION"],
    'TNAME' => ( $offer["VERSION"]==2 ? $PROP_TABLE_NAME : ''), // Имя таблицы для хранения свойств в отдельной таблице
    'CNAME_SKU' => ( $offer["VERSION"]==2 ? 'PROPERTY_'.$offer["SKU_PROPERTY_ID"]  : ''),// Поле со свойством "ID товара" в отдельной таблице
);

// ----- получить ID товаров, без активных предложений -----
// при хранении свойств в общей таблице
if ($arSettings["VERSION"] == 1) {
	$sql = "SELECT c.ID
		FROM b_catalog_product c
		WHERE c.TYPE=$arSettings[PRODUCT_TYPE]
			AND EXISTS(
				SELECT 1 FROM b_iblock_element e2
				WHERE e2.id=c.id AND e2.IBLOCK_ID=$arSettings[IBLOCK_ID] AND e2.active = 'Y'
			)
			AND NOT EXISTS(
				SELECT 1 FROM b_iblock_element e, b_iblock_element_property prod
				WHERE e.id=c.id AND e.active = 'Y'
					AND prod.VALUE=e.id AND prod.IBLOCK_PROPERTY_ID=$arSettings[SKU_PROPERTY_ID]
					AND EXISTS(
						SELECT 1 FROM b_iblock_element e1
						WHERE e1.id = prod.IBLOCK_ELEMENT_ID AND e1.active='Y'
					)
			)
		";

}
//  при хранении свойств в отдельной таблице
else {
	$sql = "SELECT c.ID
		FROM b_catalog_product c
		WHERE c.TYPE=$arSettings[PRODUCT_TYPE]
			AND EXISTS(
				SELECT 1 FROM b_iblock_element e2
				WHERE e2.id=c.id AND e2.IBLOCK_ID=$arSettings[IBLOCK_ID] AND e2.active = 'Y'
			)
            AND NOT EXISTS(
				SELECT 1 FROM b_iblock_element e, {$arSettings[TNAME]} prod
				WHERE e.id=c.id AND e.active = 'Y'
					AND prod.{$arSettings[CNAME_SKU]}=e.id
					AND EXISTS(
						SELECT 1 FROM b_iblock_element e1
						WHERE e1.id = prod.IBLOCK_ELEMENT_ID AND e1.active='Y'
					)
			)
		";
}
$arIDs = $DB->Query($sql, false, $err_mess.__LINE__);

$IDs = array();
while($ID = $arIDs->Fetch()){
	$IDs[$ID['ID']] = $ID['ID'];
}

// активируем раздел Под заказ (страховка на связкий случай)
try{
	$bs = new CIBlockSection;
	$bs->Update($SECTION_ID, array('ACTIVE' => 'Y'));
}
catch(Exception $e){}

$unavailable = 0;
if(!empty($IDs)){
    foreach ($IDs as $ID) {
        $ar_groups = array();
		$is_pod_zakaz_group = false;
		$db_groups = CIBlockElement::GetElementGroups($ID, true);
		while($group = $db_groups->Fetch()) {
			if ($group["ID"] == $SECTION_ID) {
				$is_pod_zakaz_group = true;
			}
		    $ar_groups[] = $group["ID"];
		}
		unset($db_groups, $group);

        if(!$is_pod_zakaz_group){
            $unavailable++;

            // переносим товар в спец.раздел
            CIBlockElement::SetElementSection($ID, array($SECTION_ID));

            // фасетный индекс
			Manager::updateElementIndex($arSettings["IBLOCK_ID"], $ID);
        }
    }
}
file_put_contents($dir."/move_unavailable_product.log.txt", date("d.m.Y H:i:s",time())."  Товаров с неактивными предложения: ".count($IDs)."\n",FILE_APPEND);
file_put_contents($dir."/move_unavailable_product.log.txt", date("d.m.Y H:i:s",time())."  Товаров перемещенных в спец.раздел: ".$unavailable."\n",FILE_APPEND);
file_put_contents($dir."/move_unavailable_product.log.txt", date("d.m.Y H:i:s",time())."  DONE: запуск перемещения товаров в спец.раздел"."\n",FILE_APPEND);

echo 'done';

exit;
