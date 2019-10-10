<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("catalog") || !CModule::IncludeModule("iblock")) {
	return;
}

$PROP_SHOP_CODE = 'SKLAD';

$result = array();

$PRODUCT_ID = intval($_POST['ID']);

$productPropsSelect = array(
	'ARTNUMBER',
	'TIPIZDELIYA',
	'METALL',
	'PROBA',
	'OSNOVNAYA_VSTAVKA'
);
$offerPropSelect = array(
	$PROP_SHOP_CODE,
	'RAZMER',
	'OPISANIE_KAMNEY',
	'VSTAVKA',
	'VES',
	'AKTSIYA',
	'AKTSIYA_AKTIVNA',
	'STARAYA_TSENA'
);

if($PRODUCT_ID > 0){
	// информация о товаре
	$result['PRODUCT_INFO'] = array();

	$resProduct = CIBlockElement::GetList(
		array(),
		array(
			'ACTIVE' => 'Y',
			'=ID' => $PRODUCT_ID
		),
		false,
		false,
		array()
	);
	if($arProd = $resProduct->GetNextElement()){
		$arFields = $arProd->GetFields();
		$arProps = $arProd->GetProperties();

		$result['PRODUCT_INFO'] = array(
			'ID' => $arFields['ID'],
			'NAME' => ($arProps['NAIMENOVANIE_DLYA_SAYTA']['VALUE'] ? $arProps['NAIMENOVANIE_DLYA_SAYTA']['VALUE'] : $arFields['NAME']),
			'PICTURE' => (
				$arFields['PREVIEW_PICTURE'] ? CFile::GetPath($arFields['PREVIEW_PICTURE']) : (
					$arFields['DETAIL_PICTURE'] ? CFile::GetPath($arFields['DETAIL_PICTURE']) : ''
				)
			)
		);

		foreach($productPropsSelect as $propCode){
			if(!$arProps[$propCode]['VALUE']){
				$result['PRODUCT_INFO']['PROPS'][$propCode] = '';
			}else{
				$result['PRODUCT_INFO']['PROPS'][$propCode] = (
					is_array($arProps[$propCode]['VALUE'])
					? implode(' / ', $arProps[$propCode]['VALUE'])
					: $arProps[$propCode]['VALUE']
				);
			}
		}
	}

	// список магазинов
	$arShopList=array();
	$rsStores = CIBlockElement::GetList(
		array("SORT"=>"ASC"),
		array(
			'IBLOCK_CODE' => "points_on_map",
		),
		false,
		false,
		array('ID','XML_ID','SORT','NAME','PROPERTY_TOWN','PROPERTY_ADRESS','PROPERTY_WORK_TIME','PROPERTY_PHONE','PROPERTY_LAT','PROPERTY_LON',)
	);
	while($arStore = $rsStores->Fetch()){
        $address = array();
        if(!empty($arStore['PROPERTY_TOWN_VALUE'])){
            $address[] = 'г.'.$arStore['PROPERTY_TOWN_VALUE'];
        }
        if(!empty($arStore['PROPERTY_ADRESS_VALUE'])){
            $address[] = $arStore['PROPERTY_ADRESS_VALUE'];
        }
		$arShopList[$arStore['XML_ID']] = array(
                'ID' => $arStore['ID'],
                'NAME' => $arStore['NAME'],
                'SORT' => $arStore['SORT'],
                'ADRESS' => !empty($address) ? implode(', ', $address) : '',
                'WORK_TIME' => $arStore['PROPERTY_WORK_TIME_VALUE'] ? $arStore['PROPERTY_WORK_TIME_VALUE'] : '',
                'PHONE' => $arStore['PROPERTY_PHONE_VALUE'] ? $arStore['PROPERTY_PHONE_VALUE'] : '',
                'COORDINATES'=>array("LAT"=>$arStore['PROPERTY_LAT_VALUE'],"LON"=>$arStore['PROPERTY_LON_VALUE']),
                'OFFERS'=>array()
            );

	}
    $arFilter = array(
        'ACTIVE' => 'Y',
        '!PROPERTY_'.$PROP_SHOP_CODE => false,
        '>CATALOG_QUANTITY' => 0
    );
    $RAZMER='';
    foreach ($_POST['TREE_PROPS'] as $PROP_CODE => $PROP_VAL) {
        $PROP_CODE = htmlspecialchars($PROP_CODE);
        $arFilter['PROPERTY_'.$PROP_CODE.'_VALUE'] = $PROP_VAL;
        if ($PROP_CODE=='RAZMER') {
        	$RAZMER=$PROP_VAL;
        }
    }

    $res_offers = CCatalogSKU::getOffersList(
		$PRODUCT_ID,
		0,
		$arFilter,
		array(),
		array(
			// 'CODE' => array($PROP_SHOP_CODE,'RAZMER'),
			'CODE' => $offerPropSelect,
		)
	);
// printvar('$RAZMER',$RAZMER);
	$arSklad = array();
	foreach($res_offers[$PRODUCT_ID] as $keyOffer => $valOffer){

        $xml_id=$valOffer['PROPERTIES'][$PROP_SHOP_CODE]['VALUE_XML_ID'][0];
		unset($valOffer['PROPERTIES'][$PROP_SHOP_CODE]);
        if( !isset($arShopList[$xml_id]) ) continue;

        // акции 
        if (isset($valOffer['PROPERTIES']['AKTSIYA_AKTIVNA']) && $valOffer['PROPERTIES']['AKTSIYA_AKTIVNA']['VALUE']=='Да' ) {
        	$valOffer['AKTSIYA']=$valOffer['PROPERTIES']['AKTSIYA']['VALUE'];
        	$valOffer['STARAYA_TSENA']=$valOffer['PROPERTIES']['STARAYA_TSENA']['VALUE'];
        }
		unset($valOffer['PROPERTIES']['AKTSIYA_AKTIVNA']);
		unset($valOffer['PROPERTIES']['AKTSIYA']);
		unset($valOffer['PROPERTIES']['STARAYA_TSENA']);


		$arPrice = CPrice::GetBasePrice($keyOffer);
        if($arPrice['PRICE'] <= 0) continue;

       	$size=$valOffer['PROPERTIES']['RAZMER']['VALUE'];

        if( $valOffer["CATALOG_QUANTITY"]>0 ){
            $valOffer['PRICE'] = array(
				'VALUE' => $arPrice['PRICE'],
				'CURRENCY' => $arPrice['CURRENCY'],
				'FORMAT_VALUE' => CurrencyFormat($arPrice['PRICE'], $arPrice['CURRENCY'])
			);
            $arShopList[$xml_id]["OFFERS"]["RAZMER"][$size][] = $valOffer;
            $arShopList[$xml_id]["QUANTITY"]+=$valOffer["CATALOG_QUANTITY"];
        }
   	}
	$result['SHOP_LIST'] = $arShopList;
}else{
    $result['ERROR'] = 'Некорректный ID товара';
}


$GLOBALS['APPLICATION']->RestartBuffer();
echo json_encode($result, JSON_UNESCAPED_UNICODE);

die();
