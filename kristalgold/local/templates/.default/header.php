 <?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?$APPLICATION->ShowTitle()?></title>
	<?
	//CJSCore::Init(array("ajax", "window"));
	$APPLICATION->ShowHead();?>
</head>
<body itemscope itemtype="http://schema.org/WebPage">
<?$APPLICATION->ShowPanel();?>
<main>
