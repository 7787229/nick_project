<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('iblock');
CModule::IncludeModule('uvelirsoft');

// инициализируем кеш
$obCache  = new CPHPCache();
// определяем переменные
$cacheLifetime  = 3600*24; 
$cacheID        = 'SOCIAL'; 
$cachePath      = '/UVELIRSOFT/';
    
if($obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
    {
       $rsResult = $obCache->GetVars();   
       $arResult = $rsResult[$cacheID];
    }
        elseif( $obCache->StartDataCache()  )
    {    
        $arResult = array();

        $arSelect = Array("ID", "NAME", "PROPERTY_".$arParams["SOCIAL_ICON"],"PROPERTY_".$arParams["SOCIAL_LINK"],"PROPERTY_".$arParams["SOCIAL_COLOR"],"PROPERTY_".$arParams["SOCIAL_COLOR_HOVER"]);
        $arFilter = Array("IBLOCK_ID"=>IntVal($arParams["SOCIAL_IBLOCK_ID"]), "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array(), $arSelect);
        while($ob = $res->Fetch())
        {
            $arResult["ITEMS"][] = array(
                "ICON" => $ob["PROPERTY_".$arParams["SOCIAL_ICON"]."_VALUE"],
                "LINK" => $ob["PROPERTY_".$arParams["SOCIAL_LINK"]."_VALUE"],
                "COLOR" => $ob["PROPERTY_".$arParams["SOCIAL_COLOR"]."_VALUE"],
                "COLOR_HOVER" => $ob["PROPERTY_".$arParams["SOCIAL_COLOR_HOVER"]."_VALUE"],
            );
        }
        
        $obCache->EndDataCache(array($cacheID => $arResult));
    }   

$this->IncludeComponentTemplate();