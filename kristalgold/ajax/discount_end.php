<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ( !CModule::IncludeModule("catalog") ) 
{
	return;
}

// define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

$cache_time = 0*3600; // 1 час
$cache_path = 'catalog/discount_end';

$result = 0;

if ( 
	isset($_REQUEST["selected_id"])  && (int)$_REQUEST["selected_id"] 
	&&
	isset($_REQUEST["price_id"]) && (int)$_REQUEST["price_id"] 	
)
{
	$selected_id = (int)$_REQUEST["selected_id"];
	$price_id = (int)$_REQUEST["price_id"];

	$cache = new CPHPCache();
	$cache_id = 'selected_id_'.$selected_id;

	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path))
	{
	   $result = $cache->GetVars();
// AddMessage2Log($result, "ajax (cached)");
	} else {
// AddMessage2Log($result, "ajax (db select)");

	    $discountInfo = CCatalogDiscount::GetDiscountByProduct(
	    	$selected_id, $USER->GetUserGroupArray(), "N", $price_id, SITE_ID, array()
	    );
	    $arResult['DISCOUNT_END'] = false;
	    if ($discountInfo)
	    {
	        usort($discountInfo, 
	        	function($a, $b) 
	        	{
	            	if ($a['PRIORITY'] === $b['PRIORITY']) return 0;
	            	return $a['PRIORITY'] > $b['PRIORITY'] ? -1 : 1;
	        	}
	        );
	        foreach ($discountInfo as $discount)
	        {
	            if ($discount['ACTIVE']  && !is_null($discount['ACTIVE_FROM']) && !is_null($discount['ACTIVE_TO']))
	            {
	                $arResult['DISCOUNT_END'] = $discount['ACTIVE_TO'];
	                break;
	            }
	        }
	    }

	    if ( $arResult['DISCOUNT_END'] ) 
	    {
		    $result = strtotime($arResult['DISCOUNT_END']);
	    }

	   	if ($cache_time > 0)
	   	{
	        $cache->StartDataCache($cache_time, $cache_id, $cache_path);
	        $cache->EndDataCache($result);
	   	}
	}

}
// очистить буфер
ob_clean();
// вывод - время окончания акций (в секундах)
echo $result;
exit;