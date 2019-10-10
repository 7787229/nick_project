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
<div class="container">
	<div class="bottom-catalog-menu">
			<nav class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
							Каталог
                        </button>
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="sub-toggle-menu collapse navbar-collapse" id="bs-example-navbar-collapse-2">
						<?$APPLICATION->IncludeComponent(
							"bitrix:menu", 
							"top_multilevel_with_images", 
							array(
								"ALLOW_MULTI_SELECT" => "N",
								"CHILD_MENU_TYPE" => "main",
								"DELAY" => "N",
								"MAX_LEVEL" => "1",
								"MENU_CACHE_GET_VARS" => array(
								),
								"MENU_CACHE_TIME" => "3600",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"ROOT_MENU_TYPE" => "main",
								"USE_EXT" => "Y",
								"COMPONENT_TEMPLATE" => "top_multilevel_with_images"
							),
							$component,
							Array(
								'HIDE_ICONS' => 'N'
							)
						);?>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
	</div>
</div>
<div class="container">
			<?$APPLICATION->IncludeComponent(
				"bitrix:breadcrumb",
				".default",
				array(
					"COMPONENT_TEMPLATE" => ".default",
					"PATH" => "",
					"SITE_ID" => "-",
					"START_FROM" => "0"
				),
				false
			);?>
</div>
<div class="container">