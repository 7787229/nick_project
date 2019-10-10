<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?><?$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"modal",
	Array(
		"AUTH" => "Y",
		"REQUIRED_FIELDS" => array(0=>"EMAIL",),
		"SET_TITLE" => "N",
		"SHOW_FIELDS" => array("EMAIL","NAME","LAST_NAME"),
		"SUCCESS_PAGE" => "",
		"USER_PROPERTY" => "",
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>