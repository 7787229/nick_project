#!/usr/bin/php -q
<?
$_SERVER["DOCUMENT_ROOT"] = "/var/www/html";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arParams["IBLOCK_ID_OFFERS"] = 2;  // offers IBLOCK_ID

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
CModule::IncludeModule("mrproper");

global $DB;

if(!CCatalogSKU::GetInfoByOfferIBlock($arParams["IBLOCK_ID_OFFERS"])){
    echo "Not Offers Iblock ID!!!!!";
    exit;
}

$time = microtime(true);

$cntStep = COption::GetOptionString("mrproper", "cnt_by_step", "10");

// all cnt for percent stat
$ALLCNT = CIBlockElement::GetList(false, array("IBLOCK_ID"=>$arParams["IBLOCK_ID_OFFERS"], "ACTIVE"=>"N"), array());
$pageNum = ceil($ALLCNT/$cntStep);
$next = 1;

do{
    $nav = array(
        "nPageSize" => $cntStep,
        "iNumPage" => $next,
        "bShowAll" => false
    );

    // search offers IDs
    $res = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arParams["IBLOCK_ID_OFFERS"], "ACTIVE"=>"N"), false, $nav, array("ID"));

    $selectedRows = 0;
    while($ob = $res->fetch()){
        $arIDs[] = $ob["ID"];
        $selectedRows++;
    }

    // search this ID in orders
    $results = $DB->Query("SELECT `PRODUCT_ID` from b_sale_basket WHERE `PRODUCT_ID` in (".implode(",",$arIDs).")");
    $arIDinOrders = array();
    while ($row = $results->Fetch()){
        $arIDinOrders[] = $row["PRODUCT_ID"];
    }

    $strWarning = "";

    // delete unnecessary offers
    $deleted = 0;
    $inorder = 0;
    foreach ($arIDs as $offerID) {
        if(!in_array($offerID,$arIDinOrders)){
            $deleted++;
            $arDeleted[]=$offerID;
            // delete element
            $DB->StartTransaction();
            if(!CIBlockElement::Delete($offerID)){
                $strWarning .= 'Error!';
                $DB->Rollback();
                echo $strWarning;
                exit;
            }else{
                $DB->Commit();
            }
        }else{
            $inorder++;
        }
    }

    if(count($selectedRows)>0){
        $processFlag = true;
    }else{
        $processFlag = false;
    }

    $next++;
}while($next <= $pageNum);

$time2 = microtime(true) - $time;
echo $time2;

CEventLog::Add(array(
	'AUDIT_TYPE_ID' => 'INACTIVE_OFFERS_CLEANER',
	'MODULE_ID' => 'catalog',
	'DESCRIPTION' => 'Удаление неактивных предлжений без заказов завершено: '.round($time2, 4).'сек.'
));
