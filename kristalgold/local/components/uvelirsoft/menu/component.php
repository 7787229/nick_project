<?
/**
    вывод меню каталога по выбранным разделам с фильтрами (Листинги для верхнего меню)
*/

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// ID инфоблока для каталога
define("CATALOG_IBLOCK_ID", 1);
// ID инфоблока для листинга
define("LISTING_IBLOCK_ID", 11);

global $USER;
$USER_ID = $USER->IsAuthorized() ? $USER->GetID() : 0;

$cacheLifetime = 3600 * 24; // кеш 24 часа
$cacheID = 'TOP_MENU_CUSTOM_'.$USER_ID;
$cachePath = '/UVELIRSOFT/';

$obCache = new CPHPCache();

if ($obCache->InitCache($cacheLifetime, $cacheID, $cachePath)) {

    $rsResult = $obCache->GetVars();
    $arResult = $rsResult[$cacheID];

} elseif ($obCache->StartDataCache()) {

    if (CModule::IncludeModule("iblock")) {
        // выбираем разделы каталога продукции для построения меню
        // UF_USE_IN_MENU - Отображать в верхнем меню (в видимой части)
        // UF_USE_MENU_MORE - Oтображать в верхнем меню (скрытым под "еще")
        $arFilter = array(
            'IBLOCK_ID' => CATALOG_IBLOCK_ID, 'GLOBAL_ACTIVE' => 'Y',
            array('LOGIC' => 'OR', 'UF_USE_IN_MENU' => '1', 'UF_USE_MENU_MORE' => '1'),
            'CHECK_PERMISSIONS' => 'Y',
            'MIN_PERMISSION' => 'R',
            'PERMISSIONS_BY' => $USER_ID
        );
        $db_list = CIBlockSection::GetList(Array("UF_TOP_MENU_SORT" => 'ASC'), $arFilter, true, array('UF_USE_MENU_MORE'));
        $db_list->NavStart(20);
        while ($ar_result = $db_list->GetNext()) {
            $subSects = array();
            $rsParentSection = CIBlockSection::GetByID($ar_result['ID']);
            // поиск и отображение подразделов каталога продукции
            if ($arParentSection = $rsParentSection->GetNext()) {
                $arFilter4 = array(
                    'IBLOCK_ID' => $arParentSection['IBLOCK_ID'],
                    '>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
                    '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
                    '>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL'],
                    'UF_USE_IN_SUBMENU' => '1'
                ); // выберет потомков из каталога продукции без учета активности
                $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilter4);
                while ($arSect = $rsSect->GetNext()) {
                    //$subSects[$arSect['NAME']] = $arSect['SECTION_PAGE_URL'];
                    $subSects[$arSect['ID']] = array(
                        "NAME" => $arSect['NAME'],
                        "URL" => $arSect['SECTION_PAGE_URL'],
                        "PICTURE" => (!empty($ar_result['PICTURE']) ? CFile::GetPath($arSect['PICTURE']) : ""),
                    );
                }
            }

            // поиск ID раздела листинга (ВЕРХНЕГО УРОВНЯ!) c учетом активности
            $par_list = 0;
            $arFilter3 = Array(
                'IBLOCK_ID' => LISTING_IBLOCK_ID, 'UF_SECT' => $ar_result['ID'],
                'SECTION_ID'=>0, 'GLOBAL_ACTIVE' => 'Y'
            );
            $db_list3 = CIBlockSection::GetList(Array(), $arFilter3, false);
            if ($ar_result3 = $db_list3->GetNext()) {
                $par_list = $ar_result3["ID"];
            }
            $listing = array();
            if ($par_list) {
                // выбираем все подразделы (секции) листинга с учетом активности
                $rsParentSection5 = CIBlockSection::GetByID($par_list);
                if ($arParentSection5 = $rsParentSection5->GetNext()) {
                    $arFilter5 = array(
                        'IBLOCK_ID' => $arParentSection5['IBLOCK_ID'],
                        '>LEFT_MARGIN' => $arParentSection5['LEFT_MARGIN'],
                        '<RIGHT_MARGIN' => $arParentSection5['RIGHT_MARGIN'],
                        '>DEPTH_LEVEL' => $arParentSection5['DEPTH_LEVEL'],
                        "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"
                    );
                    $rsSect5 = CIBlockSection::GetList( array('left_margin' => 'asc'), $arFilter5 );
                    // выбираем значения каждой секции с учетом активности
                    while ($arSect5 = $rsSect5->GetNext()) {
                        $arSelect6 = Array("ID", "NAME", "PROPERTY_LINK");
                        $arFilter6 = Array("SECTION_ID" => IntVal($arSect5['ID']), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
                        $res6 = CIBlockElement::GetList(Array(), $arFilter6, false, Array("nPageSize" => 50), $arSelect6);
                        while ($ob6 = $res6->GetNextElement()) {
                            $arFields6 = $ob6->GetFields();
                            $listing[$arSect5['NAME']][] = array(
                                'NAME' => $arFields6['NAME'],
                                'LINK' => $arFields6['PROPERTY_LINK_VALUE'],
                            );
                        }
                    }
                }
            }
            $arResult[] = array(
                'NAME' => $ar_result['NAME'],
                'SECTION_PAGE_URL' => $ar_result['SECTION_PAGE_URL'],
                'SUB_SECTIONS' => $subSects,
                'LISTING' => $listing,
                'USE_MENU_MORE' => $ar_result['UF_USE_MENU_MORE'],
                'PICTURE' => (!empty($ar_result['PICTURE']) ? CFile::GetPath($ar_result['PICTURE']) : '')
            );
        }
    }
 // printvar("", $arResult);
    $obCache->EndDataCache(array($cacheID => $arResult));
}
$this->IncludeComponentTemplate();
