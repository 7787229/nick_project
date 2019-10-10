<?php

#not_admin 1 - вывод только при авторизации админа
#trace 1 - краткий вариант
#trace 2 - расширенный
function printvar( $var, $value, $not_admin=1, $trace=0) {
	global $USER;
	if (!$USER->IsAdmin() && $not_admin) return false;

	if($trace){
		echo "<pre>";
		switch ($trace) {
			case 1:
				debug_print_backtrace();
				break;
			case 2:
				print_r( debug_backtrace() );
				break;
		}
		echo "</pre>";
	}
	if($trace!=2){
		$t = gettype($value);
		if( $t == "array" || $t == "object") {
			echo "<b>".$var."</b>";
			echo "<hr>";
			echo "<pre>";
			print_r( $value );
			echo "</pre>";
		}
		else {
			echo "<b>".$var." = </b>".$value;
			echo "<hr>";
		}
	}
}



function makeFilterTag($ITEMS) {

    $out = "";

    foreach ($ITEMS as $keyItem => $valueItem) {
        foreach ($valueItem['VALUES'] as $valueProp) {
            if($valueProp["CHECKED"]== 1){
                $out.= "<span style='border:1px solid #cccccc;padding:4px;margin:2px;'>";
                //$out.= $valueItem["NAME"].": ";
                $out.= $valueProp["VALUE"];
                $out.= "<span style='padding:4px'>X</span></span>";
            }
        }
    }
    return $out;
}

/*
 * Функция возвращает идентификторы текущех отложенных товаров
 */
function listDelay(){
        $arDelay = array();
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
    return $arDelay;
}


function makeSocialButton($url,$title,$img,$text){


    // используется для страниц когда необходимо к каждому товару приделать кнопку
    $url    = urlencode($url);
    $title  = urlencode(html_entity_decode(htmlspecialchars_decode($title), ENT_QUOTES, 'UTF-8'));
    $text   = urlencode(strip_tags(htmlspecialchars_decode($text)));
    $img    = urlencode($img);

    $outpost = '<div class="my-social-buttons-block"><ul class="my-social-buttons-list">';
        $outpost.= '<li class="social-vkontakte-block"><a href="javascript:void(0)" onclick="open_social_share_window(\'http://vk.com/share.php?url='.$url.'&title='.$title.'&description='.$text.'&image='.$img.'&utm_source=share2\')" rel="nofollow" target="_blank" title="ВКонтакте"><span class="social-vkontakte-icon"></span></a></li>';
        $outpost.= '<li class="social-facebook-block"><a href="javascript:void(0)" onclick="open_social_share_window(\'https://www.facebook.com/sharer.php?src=sp&u='.$url.'&utm_source=share2\')" rel="nofollow" target="_blank" title="Facebook"><span class="social-facebook-icon"></span></a></li>';
        $outpost.= '<li class="social-odnoklassniki-block"><a href="javascript:void(0)" onclick="open_social_share_window(\'https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl='.$url.'&utm_source=share2\')" rel="nofollow" target="_blank" title="Одноклассники"><span class="social-odnoklassniki-icon"></span></a></li>';
        $outpost.= '<li class="social-moimir-block"><a href="javascript:void(0)" onclick="open_social_share_window(\'https://connect.mail.ru/share?url='.$url.'&title='.$title.'&description='.$text.'&utm_source=share\')" rel="nofollow" target="_blank" title="Мой Мир"><span class="social-moimir-icon"></span></a></li>';
        $outpost.= '<li class="social-gplus-block"><a href="javascript:void(0)" onclick="open_social_share_window(\'https://plus.google.com/share?url='.$url.'&utm_source=share2\')" rel="nofollow" target="_blank" title="Google+"><span class="social-gplus-icon"></span></a></li>';
        $outpost.= '<li class="social-twitter-block"><a href="javascript:void(0)" onclick="open_social_share_window(\'https://twitter.com/intent/tweet?text='.$title.'&url='.$url.'&utm_source=share2\')" rel="nofollow" target="_blank" title="Twitter"><span class="social-twitter-icon"></span></a></li>';
    $outpost.= '</ul></div>';

    /*
    social-vkontakte-icon
    social-facebook-icon
    social-moimir-icon
    social-odnoklassniki-icon
    social-gplus-icon
    social-twitter-icon

    social-vkontakte-block
    social-facebook-block
    social-odnoklassniki-block
    social-moimir-block
    social-gplus-block
    social-twitter-block

    */

    return $outpost;

}

/* получение списка товаров и их свойств по ID заказа*/
function getProductListInfo($ORDER_ID){
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");

	$basketList = array();

	AddMessage2Log($rsBasket, 'rsBasket');
	/* список товаров в заказе */
	$rsBasket = CSaleBasket::GetList(
		array(),
		array('ORDER_ID' => $ORDER_ID),
		false,
		false,
		array(
             "ID", "NAME", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY",
             "PRICE", "WEIGHT", "DETAIL_PAGE_URL", "NOTES", "CURRENCY", "VAT_RATE", "CATALOG_XML_ID",
             "PRODUCT_XML_ID", "SUBSCRIBE", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS", "TYPE", "SET_PARENT_ID"
           )
	);

	AddMessage2Log($rsBasket, 'rsBasket');
	while($arBasket = $rsBasket->Fetch()){
		$name = '';
		$picture = '';
		$articul = '';

		$ID = 0;
		$IBLOCK_ID = 0;

		/* список свойств для товара */
		$rsBasketProps = CSaleBasket::GetPropsList(
			array(
				"SORT" => "ASC",
				"NAME" => "ASC"
			),
			array("BASKET_ID" => $arBasket['ID'])
		);
		$arProps = array();
		while ($arBasketProps = $rsBasketProps->Fetch())
		{
			switch($arBasketProps['CODE']){
				case "CATALOG.XML_ID":
				case "PRODUCT.XML_ID":
					break;
				default:
					$arProps[$arBasketProps['CODE']] = $arBasketProps;
					break;
			}
		}
		/* родительский товар для предложения */
		$mxResult = array();
		$mxResult = CCatalogSku::GetProductInfo($arBasket['PRODUCT_ID']);
		if(!$mxResult){
			$ID =  $arBasket['PRODUCT_ID'];
			$IBLOCK_ID = $arBasket['IBLOCK_ID'];
		}else{
			$ID = $mxResult['ID'];
			$IBLOCK_ID = $mxResult['IBLOCK_ID'];
		}

		/* информация о товаре */
		$rsElement = CIBlockElement::GetList(
			array("SORT"=>"ASC"),
			array(
				'ID' => $ID,
				'IBLOCK_ID' => $IBLOCK_ID
			),
			false,
			false,
			array('ID', 'NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_ARTNUMBER')
		);
		if($arElement = $rsElement->Fetch()){
			$name = $arElement['NAME'];
			$picture = !empty($arElement['PREVIEW_PICTURE']) ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'];
			$articul = $arElement['PROPERTY_ARTNUMBER_VALUE'];
		}

		$basketList[$arBasket['ID']] = array(
			'ARTICUL' => $articul,
			'NAME' => $name,
			'PICTURE' => CFile::GetPath($picture),
			'URL' => $arBasket['DETAIL_PAGE_URL'],
			'PRICE' => CurrencyFormat($arBasket['PRICE'], $arBasket['CURRENCY']),
			'QUANTITY' => $arBasket['QUANTITY']." ".$arBasket['MEASURE_NAME'],
			'SUM' => CurrencyFormat($arBasket['PRICE'] * $arBasket['QUANTITY'], $arBasket['CURRENCY']),
			'PROPS' => $arProps
		);
	}
	AddMessage2Log($basketList, 'basketList');
	return $basketList;
}

function plural_form($number, $after) {
	$cases = array (2, 0, 1, 1, 1, 2);
	return $after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}
