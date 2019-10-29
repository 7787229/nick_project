<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);
// printvar('', $arResult['JS_DATA']);

if(!array_key_exists('via_ajax', $_REQUEST) && !array_key_exists('soa-action', $_REQUEST)){
    if(array_key_exists(DEFAULT_DELIVERY_ID, $arResult['JS_DATA']['DELIVERY'])){
        foreach($arResult['JS_DATA']['DELIVERY'] as $deliveryID => $arDelivery){
            if($arDelivery['ID'] == DEFAULT_DELIVERY_ID){
                $arResult['JS_DATA']['DELIVERY'][$deliveryID]['CHECKED'] = 'Y';
            }else{
                unset($arResult['JS_DATA']['DELIVERY'][$deliveryID]['CHECKED']);
            }
        }
    }
}
