<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("iblock")) {
	return;
}
if(isset($_REQUEST["price_id"])){
	if(intval($_REQUEST["price_id"])>0){
		$arFields = array("DELAY" => "Y");
			$resBasket = CSaleBasket::GetList(
				array(), 
				array(
					"PRODUCT_PRICE_ID" => intval($_REQUEST["price_id"]),
					"FUSER_ID" => CSaleBasket::GetBasketUserID(),
					"LID" => SITE_ID,
					"ORDER_ID" => "NULL"
				), 
				false, 
				false, 
				array("ID")
			);
			if($ar = $resBasket->Fetch()){
				// deleted
				CSaleBasket:: Delete($ar["ID"]);
				echo "deleted";
				return;
			}else{	
				// insert into delay block price_id
				$delayItem = Add2Basket(intval($_REQUEST["price_id"]),1,$arFields,array());
				echo "added";
				return;
			}
	}
}



$qnt = floatval($_REQUEST["CNT"]);
$arItemParams = array();


$iBlock = gzuncompress(base64_decode($_REQUEST["ibl"]));

if(isset($_REQUEST["arprops"])){
	$OFFERS_CART_PROPERTIES = unserialize(gzuncompress(base64_decode($_REQUEST["arprops"])));
}else{
	$OFFERS_CART_PROPERTIES = "";
}

$product_properties = array();


$skuAddProps = (isset($_REQUEST['basket_props']) && !empty($_REQUEST['basket_props']) ? $_REQUEST['basket_props'] : '');

if (!empty($OFFERS_CART_PROPERTIES) || !empty($skuAddProps))
{
	$product_properties = CIBlockPriceTools::GetOfferProperties(
		intval($_REQUEST["id"]),
		$iBlock,
		$OFFERS_CART_PROPERTIES,
		$skuAddProps
	);
}

$arFields = array("DELAY" => "Y");

$resBasket = CSaleBasket::GetList(
	array(), 
	array(
		"PRODUCT_ID" => intval($_REQUEST["id"]),
		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
		"LID" => SITE_ID,
		"ORDER_ID" => "NULL"
	), 
	false, 
	false, 
	array("ID")
);

if($ar = $resBasket->Fetch()){
		/* CSaleBasket::Update($ar["ID"], $arFields); */
		// удаление
		CSaleBasket:: Delete($ar["ID"]);
		echo "deleted";

}else{
	
	$delayItem = Add2BasketByProductID(intval($_REQUEST["id"]), $qnt, $product_properties);
	$filter = array(
			"PRODUCT_ID" => intval($_REQUEST["id"]),
			"FUSER_ID" => CSaleBasket::GetBasketUserID(),
			"LID" => SITE_ID,
			"ORDER_ID" => "NULL"
		);

	$resBasket2 = CSaleBasket::GetList(
		array(), 
		$filter, 
		false, 
		false, 
		array("ID")
	);

	while($ar2 = $resBasket2->Fetch()) {
		CSaleBasket::Update($ar2["ID"], $arFields);
	}

}


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");