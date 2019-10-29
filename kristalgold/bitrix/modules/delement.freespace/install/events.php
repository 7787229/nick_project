<?
$EVENT_NAME = "FREE_SPACE";
        
$em = new CEventMessage;
$langs = CLanguage::GetList(($b = ""), ($o = ""));
while ($lang = $langs->Fetch()) {
    IncludeModuleLangFile(__FILE__, $lang["LID"]);
    $arSites = array();
    $sites = CLang::GetList($by, $order, Array("LANGUAGE_ID" => $lang["LID"]));
    while ($site = $sites->Fetch()) {
        $arSites[] = $site["LID"];
    }

    $fres = CEventType::GetList(array("EVENT_NAME" => $EVENT_NAME, "LID" => $lang["LID"]));
    if (!$fres->Fetch()) {
        $str = "";
        $str .= "#EMAIL_TO# - ".GetMessage("delement.freespace_EMAIL_TO")."\n";
        $str .= "#FREE_SPACE# - ".GetMessage("delement.freespace_FREE_SPACE")."\n";
        $str .= "#TOTAL_SPACE# - ".GetMessage("delement.freespace_ALL_SPACE")."\n";
        $name = 
        $et = new CEventType;
        if (!$et->Add(
            Array(
                "LID" => $lang["LID"],
                "EVENT_NAME" => $EVENT_NAME,
                "NAME" => GetMessage("delement.freespace_MAIL_TITLE"),
                "DESCRIPTION" => $str,
            )
        ) ) {
            //print_R($et->LAST_ERROR);
        }
        if (is_array($arSites) && count($arSites) > 0) {
            $em->Add(Array(
                "ACTIVE" => "Y",
                "EVENT_NAME" => $EVENT_NAME,
                "LID" => $arSites,
                "EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
                "EMAIL_TO" => "#EMAIL_TO#",
                "SUBJECT" => GetMessage("delement.freespace_SUBJECT_MAIL") . " #SITE_NAME#",
                "BODY_TYPE" => "text",
                "MESSAGE" => GetMessage("delement.freespace_TEXT_MAIL"),
            ));
        }
    }
}
?>