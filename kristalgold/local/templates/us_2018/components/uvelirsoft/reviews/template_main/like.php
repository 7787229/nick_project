<?php
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

CModule::IncludeModule('iblock');

$GLOBALS['APPLICATION']->RestartBuffer();

$CODE = ($_POST["t"] == "d" ? "DESLIKES":"LIKES");

$db_props = CIBlockElement::GetProperty(REPLAY_IBLOCK_ID, intval($_POST["i"]), array("sort" => "asc"), Array("CODE"=>$CODE));

//print_r($db_props);

if($ar_props = $db_props->Fetch()){		
    $val = ($ar_props["VALUE"] > 0 ? $ar_props["VALUE"]:0);
}else{
    $val = false;
}

if($val !== false){
	$newVal = $val+1;
    CIBlockElement::SetPropertyValues(intval($_POST["i"]), REPLAY_IBLOCK_ID, $newVal, $CODE);	
	echo $newVal;
}
