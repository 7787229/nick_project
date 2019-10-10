<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

global $APPLICATION, $USER;

if(!$USER->IsAuthorized() && !$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"])){
	echo 0;
}else{
	echo 1;
}