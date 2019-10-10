<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponentTemplate $templateFolder
 * @var CatalogElementComponent $component
 */

$templateTheme = $arParams['TEMPLATE_THEME'];

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

/*ugrm@ivelirsoft.ru*/

    // переопределяем цветовую схему на ту, которая выбрана в настройках
    // (после $component->applyTemplateModifications() возвращается TEMPLATE_THEME = blue)
    $arParams['TEMPLATE_THEME'] = $templateTheme;

    /* добавляем в кеш массив для форм "Быстрый заказ" и "нет моего размера" */
    $picture = "";
    //printvar("", $arResult, 0);
    if(!empty($arResult['PREVIEW_PICTURE']['SRC'])){
        $picture = $arResult['PREVIEW_PICTURE']['SRC'];
    }elseif(!empty($arResult['DETAIL_PICTURE']['SRC'])){
        $picture = $arResult['DETAIL_PICTURE']['SRC'];
    }

    $arResult['PROPS_TO_POPUP'] = array(
        'ID' => $arResult['ID'],
        'ARTNUMBER' => $arResult['PROPERTIES']['ARTNUMBER']['VALUE'],
        'NAME' => $arResult['NAME'],
        'LINK' => $arResult['DETAIL_PAGE_URL'],
        'PICTURE' => $picture
    );

    $arResult['NEED_ORDER_PRODUCT'] = ($arResult['IBLOCK_SECTION_ID'] == 228 ? 'Y' : 'N');

    $cp = $this->__component;
    if(is_object($cp)){
        $cp->SetResultCacheKeys(array('PROPS_TO_POPUP', 'NEED_ORDER_PRODUCT'));
    }
    /* добавляем в кеш массив для форм "Купить в 1 клик" и "Нет моего размера" */

    /* записываем в массив JS значения свойств для отбора ТП (для формы "Купить в 1 клик") */
    // $offerTreeProps = array();
    // foreach ($arResult['OFFERS'] as $key => $arOffer) {
    //     foreach ($arParams['OFFER_TREE_PROPS'] as $skuPropCode) {
    //         $offerTreeProps[$arOffer['ID']][$skuPropCode] = $arOffer['PROPERTIES'][$skuPropCode]['VALUE'];
    //     }
    // }
    //
    // foreach ($arResult['JS_OFFERS'] as $key => $arJSOffer) {
    //     $arResult['JS_OFFERS'][$key]['TREE_PROPS_VALUES'] = $offerTreeProps[$arJSOffer['ID']];
    // }

    /* записываем в массив JS значения свойств для отбора ТП (для формы "Купить в 1 клик") */
    $offerTreeProps = array();
    $shop = array();
    $arActions = array();
    // поля для сортировки предложений
    $sort = array(
        'field1' => array(),
        'field2' => array()
    );
// $arParams['OFFERS_SORT_FIELD']='PROPERTY_AKTSIYA_AKTIVNA';
// $arParams['OFFERS_SORT_FIELD2']='catalog_price_1';
    foreach ($arResult['OFFERS'] as $key => $arOffer) {
        // foreach($arOffer['PROPERTIES']['SHOP']['VALUE_XML_ID'] as $shopKey => $shopXmlID){
        //     $shop[$arOffer['ID']][$shopXmlID] = $arOffer['PROPERTIES']['SHOP']['VALUE'][$shopKey];
        // }
        // foreach ($arParams['OFFER_TREE_PROPS'] as $skuPropCode) {
        //     $offerTreeProps[$arOffer['ID']][$skuPropCode] = array(
        //         'NAME' => $arOffer['PROPERTIES'][$skuPropCode]['NAME'],
        //         'CODE' => $skuPropCode,
        //         'VALUE' => $arOffer['PROPERTIES'][$skuPropCode]['VALUE'],
        //         'SORT' => $arOffer['PROPERTIES'][$skuPropCode]['SORT']
        //     );
        // }

        if ( $arOffer["PROPERTIES"]['AKTSIYA_AKTIVNA']["VALUE"]=='Да' ) {
            $arActions[$arOffer['ID']]=array(
                'AKTSIYA'=>$arOffer["PROPERTIES"]['AKTSIYA']["VALUE"],
                'STARAYA_TSENA'=>$arOffer["PROPERTIES"]['STARAYA_TSENA']["VALUE"]
            );
        }

        foreach ($arParams['OFFER_TREE_PROPS'] as $skuPropCode) {
            $offerTreeProps[$arOffer['ID']][$skuPropCode] = $arOffer['PROPERTIES'][$skuPropCode]['VALUE'];
        }

        // поля для сортировки предложений (массив OFFERS)
        if(!empty($arOffer[strtoupper($arParams['OFFERS_SORT_FIELD'])]))
            $sort['field1'][$arOffer['ID']] = $arOffer[strtoupper($arParams['OFFERS_SORT_FIELD'])];
        elseif (!empty($arOffer["PROPERTIES"][strtoupper($arParams['OFFERS_SORT_FIELD'])]["ID"]))
            $sort['field1'][$arOffer['ID']] = $arOffer["PROPERTIES"][strtoupper($arParams['OFFERS_SORT_FIELD'])]["VALUE"]=='Да'?1:0;

        if(!empty($arOffer[strtoupper($arParams['OFFERS_SORT_FIELD2'])]))
            $sort['field2'][$arOffer['ID']] = $arOffer[strtoupper($arParams['OFFERS_SORT_FIELD2'])];
    }
    // printvar('',$offerTreeProps);    
    // printvar('', $sort);

    $sort_order1 = strtoupper($arParams['OFFERS_SORT_ORDER']) == 'ASC' ? SORT_ASC : SORT_DESC;
    $sort_order2 = strtoupper($arParams['OFFERS_SORT_ORDER2']) == 'ASC' ? SORT_ASC : SORT_DESC;

    // поля для сортировки предложений
    $js_sort = array(
        'field1' => array(),
        'field2' => array()
    );
    foreach ($arResult['JS_OFFERS'] as $key => $arJSOffer) {
        $arResult['JS_OFFERS'][$key]['TREE_PROPS_VALUES'] = $offerTreeProps[$arJSOffer['ID']];
        $arResult['JS_OFFERS'][$key]['SHOPS'] = $shop[$arJSOffer['ID']];
        $arResult['JS_OFFERS'][$key]['ACTIONS'] = $arActions[$arJSOffer['ID']];

        // поля для сортировки предложений (массив JS_OFFERS)
        if(!empty($sort['field1'][$arJSOffer['ID']]))
            $js_sort['field1'][$key] = $sort['field1'][$arJSOffer['ID']];
        else
            $js_sort['field1'][$key] = 0;
        if(!empty($sort['field2'][$arJSOffer['ID']]))
            $js_sort['field2'][$key] = $sort['field2'][$arJSOffer['ID']];
        else
            $js_sort['field2'][$key] = max($sort['field2']);
    }
    // printvar($sort_order1, $js_sort);
    //printvar('', $arResult['ITEM_PRICES']);
    array_multisort($js_sort['field1'], $sort_order1, SORT_NUMERIC, $js_sort['field2'], $sort_order2, SORT_NUMERIC, $arResult['JS_OFFERS']);
    $arResult['OFFERS_SELECTED'] = 0;

    /* !записываем в массив JS значения свойств для отбора ТП (для формы ""Купить в 1 клик) */

    if(is_string($arParams['PRODUCT_INFO_AND_PAYMENT_BLOCK_ORDER'])){
        $arParams['PRODUCT_INFO_AND_PAYMENT_BLOCK_ORDER'] = explode(',', $arParams['PRODUCT_INFO_AND_PAYMENT_BLOCK_ORDER']);

        foreach ($arParams['PRODUCT_INFO_AND_PAYMENT_BLOCK_ORDER'] as $key => $value) {
            $arParams['PRODUCT_INFO_AND_PAYMENT_BLOCK_ORDER'][$key] = trim($value);
        }
    }
/*ugrm@ivelirsoft.ru*/
