<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

function deleteAbandonedBaskets(){

    if ( CModule::IncludeModule("sale") && CModule::IncludeModule("catalog") && CModule::IncludeModule("mrproper") ){
        global $DB;
        $nDays = COption::GetOptionString("mrproper", "abandoned_basket_days", "30"); // life days
        $nDays = IntVal($nDays);

        $strSql =
            "SELECT f.ID ".
            "FROM b_sale_fuser f ".
            "LEFT JOIN b_sale_order o ON (o.USER_ID = f.USER_ID) ".
            "WHERE ".
            "   TO_DAYS(f.DATE_UPDATE)<(TO_DAYS(NOW())-".$nDays.") ".
            "   AND o.ID is null ".
            "   AND f.USER_ID is null ".
            "LIMIT 1000";

        $db_res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);

        while ($ar_res = $db_res->Fetch()){
			CSaleBasket::DeleteAll($ar_res["ID"], false);
            CSaleUser::Delete($ar_res["ID"]);
        }
    }
    return "deleteAbandonedBaskets();";
}