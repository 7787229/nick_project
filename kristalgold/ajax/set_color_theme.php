<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($USER->IsAdmin() and isset($_POST['theme']) and !empty($_POST['theme'])){
	$res = COption::SetOptionString('uvelirsoft', 'SITE_CSS_MOD', htmlspecialchars($_POST['theme']));
	echo ($res ? "ok!":"!ok");
}