<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("main");
CModule::IncludeModule("perfmon");

echo json_encode(
	array(
		"UPDSYSCH" => Bitrix\Main\Config\Option::get("main", "update_system_check"),
		"SUPFINDAT" => Bitrix\Main\Config\Option::get("main", "~support_finish_date"),
		"PHPRAT" => Bitrix\Main\Config\Option::get("perfmon", "mark_php_page_rate"),
		"PHPRATDAT" => Bitrix\Main\Config\Option::get("perfmon", "mark_php_page_date"),
		"DBOPT" => date("d.m.Y H:i:s", Bitrix\Main\Config\Option::get("main", "LAST_DB_OPTIMIZATION_TIME"))
	)
);
