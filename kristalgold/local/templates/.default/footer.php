<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
</main>
<?

CUtil::InitJSCore();
CJSCore::Init(array("fx","currency","ajax", "window", "jquery"));

IncludeTemplateLangFile(__FILE__);
?>
</body>
</html>
