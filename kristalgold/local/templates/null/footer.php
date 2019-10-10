<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
</div>
</main>
<?

CUtil::InitJSCore();
CJSCore::Init(array("fx","currency","ajax", "window", "jquery"));


IncludeTemplateLangFile(__FILE__);

$APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/js/jquery.min.js");
$APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/bootstrap/js/bootstrap.js");
$APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/js/script.js");

$APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/bootstrap/css/bootstrap.min.css");

$APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/css/font-awesome.css");
$APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/css/social.css");
CJSCore::Init(array("ajax", "window"));	
?>
</body>
</html>
