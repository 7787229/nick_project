<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if($arParams['ITEM_COUNT'] > 0){
    array_unshift($arResult, array(
        'TEXT' => '%SALE',
        'LINK' => '/magazin/catalog/rasprodazha/',
        'SELECTED' => '',
        'PERMISSION' => 'X',
        'ADDITIONAL_LINKS' => array(
            $arParams["LINK_$i"]
        ),
        'PARAMS' => array(
            'FROM_IBLOCK' => CATALOG_ID,
            'IS_PARENT' => '',
            'DEPTH_LEVEL' => 1
        ),
        'IS_PARENT' => '',
        'DEPTH_LEVEL' => 1
    ));
    ?>
<?


    for ($i = 1; $i <= intval($arParams['ITEM_COUNT']); $i++){
        $arResult[] = array(
            'TEXT' => $arParams["NAME_$i"],
            'LINK' => $arParams["LINK_$i"],
            'SELECTED' => '',
            'PERMISSION' => 'X',
            'ADDITIONAL_LINKS' => array(
                $arParams["LINK_$i"]
            ),
            'PARAMS' => array(
                'FROM_IBLOCK' => CATALOG_ID,
                'IS_PARENT' => '',
                'DEPTH_LEVEL' => 1
            ),
            'IS_PARENT' => '',
            'DEPTH_LEVEL' => 1
        );
    }

}