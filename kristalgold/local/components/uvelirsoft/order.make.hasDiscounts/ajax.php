<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require(dirname(__FILE__)."/lang/".LANGUAGE_ID."/ajax.php");

use Bitrix\Main,
    Bitrix\Sale,
    Bitrix\Sale\Internals\DiscountCouponTable,
    Bitrix\Sale\DiscountCouponsManager;

$mode=(int)$_POST["mode"];
$arResult=array();
$recalculateBasket=false;

switch ($mode) {
	case 1: //скидка за 100% предоплату
		$arResult['result']="N";
		$arResult['error']='';
		$couponeCode=trim($_POST["value"]);
		$discount_id=(int)$_POST["id"];

		if (!CModule::IncludeModule("catalog")) exit;
		if (!CModule::IncludeModule("sale")) exit;

		// купон
		$arFilter = array('=DISCOUNT_ID' => $discount_id);
		$arSelect = array('ID', 'COUPON', 'DISCOUNT_ID', 'ACTIVE', 'DISCOUNT_NAME' => 'DISCOUNT.NAME');
		$arCoupon = DiscountCouponTable::getList(array( 'select' => $arSelect, 'filter' => $arFilter ));
		if ( $existCoupon = $arCoupon->fetch() ) {
			if ($couponeCode=="Y") { //добавление
				if ($existCoupon["ACTIVE"]=="Y") {
					// добавление купона на скидку
					$couponeCode=$existCoupon["COUPON"];
					if ( CCatalogDiscountCoupon::SetCoupon($couponeCode) ) {
						$arResult['mess']='Скидка применена.';
						$recalculateBasket=true;
					} else {
						$arResult['error']="Ошибка при добавлении скидки.";
					}
				} else {
					$arResult['error']='Купон дективирован.';
				}
			} else { //удаление
				$couponeCode=$existCoupon["COUPON"];
				if ( DiscountCouponsManager::delete($couponeCode) ) {
					$arResult['mess']='Скидка отменена.';
					$recalculateBasket=true;
				} else {
					$arResult['error']="Ошибка при отменене скидки.";
				}
			}

		} else {
			$arResult['error']='Купон на скидку не найден.';
		}
		break;
}

if ($recalculateBasket) {
   // пересчет корзины
	$arFilter=array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL"
    	);
	$dbBasketItems = CSaleBasket::GetList(array(),$arFilter,false,false,array()); 
	while ($arItems = $dbBasketItems->Fetch()) { 
	   $arOrder["BASKET_ITEMS"][] = $arItems; 
	} 
	$arOrder['SITE_ID'] = SITE_ID; 
	$arOrder['USER_ID'] = $USER->GetID(); 
	CSaleDiscount::DoProcessOrder($arOrder,array(),$arErrors);

	$arResult['result']="Y";
}

$GLOBALS['APPLICATION']->RestartBuffer(); 
header('Content-Type: application/json');
echo json_encode($arResult);
die();
