<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = Array(
	"PARAMETERS" => Array(
		"PATH_TO_DELAY" => Array(
			"NAME" => GetMessage("Delay list path"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "/personal/cart/?delay=yes",
			"COLS" => 25,
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
	)
);
?>