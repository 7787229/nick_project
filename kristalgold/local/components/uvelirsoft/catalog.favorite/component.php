<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */

$arDelay = array();
// идентификторы всех отложенных товаров по пользователю
$dbBasketItems = CSaleBasket::GetList(
	array("NAME" => "ASC","ID" => "ASC"),
	array("FUSER_ID" => CSaleBasket::GetBasketUserID(),"LID" => SITE_ID,"ORDER_ID" => "NULL","DELAY" => "Y"),
	false,
	false,
	array("ID", "DELAY", "PRODUCT_ID")
);
while ($arItems = $dbBasketItems->Fetch())
{
   $arDelay[$arItems["PRODUCT_ID"]] = $arItems["DELAY"];
}

$arResult["FAVORITES"]=$arDelay;

$arParams['DELAY_LINK'] = $arParams['JS_ID'].'_delay_link';  //отложенные товары

$this->IncludeComponentTemplate();
?>