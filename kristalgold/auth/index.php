<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
?>
	<?$APPLICATION->IncludeComponent("bitrix:main.profile", "us", Array(
	"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"CHECK_RIGHTS" => "N",	// Проверять права доступа
		"COMPONENT_TEMPLATE" => ".default",
		"SEND_INFO" => "N",	// Генерировать почтовое событие
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"USER_PROPERTY" => "",	// Показывать доп. свойства
		"USER_PROPERTY_NAME" => "",	// Название закладки с доп. свойствами
	),
	false
);?>
	
	<?
	/*
	$APPLICATION->IncludeComponent(
	"bitrix:system.auth.confirmation",
	"flat",
	Array(
		"CONFIRM_CODE" => "confirm_code",
		"LOGIN" => "login",
		"USER_ID" => "confirm_user_id"
	)
);
	*/
	?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>