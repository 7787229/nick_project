<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Iblock;

if (!Loader::includeModule('iblock')){return;}

// array of parameters
$arComponentParameters = array(   
	"GROUPS" => array(
		"MAIN" => array(
			"NAME" => GetMessage("General settings"),
		)          
	),    
	'PARAMETERS' => array(
	    	"COUNT" => array(
			"PARENT" => "MAIN",
			"NAME" => GetMessage("Number of units"),
			"TYPE" => "STRING",
                        "DEFAULT" => 5
			)
        )
);
