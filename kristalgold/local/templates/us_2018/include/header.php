<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<a name="top"></a>
<header>
	<div class="header_top">
		<div class="container">
			<div class="row header_menu">
				<div class="col-md-offset-1 col-md-10 col-sm-10 col-xs-12 menu_left top-text">
					<span>
						<span>ИНТЕРНЕТ-МАГАЗИН и ИЗДЕЛИЯ НА ЗАКАЗ <b><a href="tel:+7-495-788-77-22">+7-495-788-77-22</a></b> пн - пт с 10:00 до 18:00</span>
					</span> 
				</div>
				<div class="col-md-1 col-sm-1 col-xs-4 socials">
					<?$APPLICATION->IncludeComponent(
						"uvelirsoft:social.button",
						".default",
						array(
							"COMPONENT_TEMPLATE" => ".default",
							"SOCIAL_COLOR" => "COLOR",
							"SOCIAL_COLOR_DEFAULT" => "#dddddd",
							"SOCIAL_COLOR_HOVER" => "COLOR_HOVER",
							"SOCIAL_COLOR_HOVER_DEFAULT" => "#ff0000",
							"SOCIAL_IBLOCK_ID" => "5",
							"SOCIAL_IBLOCK_TYPE" => "content",
							"SOCIAL_ICON" => "ICON",
							"SOCIAL_ICON_CLASS" => "",
							"SOCIAL_ICON_SIZE" => "fa-lg",
							"SOCIAL_LINK" => "LINK"
						),
						false
					);?>
				</div>
			</div>
		</div>
	</div>
	<div class="header">
		<div class="container">
			<div class="row">
				<div class="col-md-5 col-sm-5 menu_left mobile_hide">
					<nav class="navbar navbar-default" role="navigation">
		                <div class="container-fluid">
		                    <!-- Brand and toggle get grouped for better mobile display -->
		                    <div class="navbar-header">
		                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-catalogmenu1">
		                            <span class="icon-bar"></span>
		                            <span class="icon-bar"></span>
		                            <span class="icon-bar"></span>
		                        </button>
		                    </div>
		                    <!-- Collect the nav links, forms, and other content for toggling -->
		                    <div class="sub-toggle-menu collapse navbar-collapse" id="navbar-collapse-catalogmenu1">
								<?$APPLICATION->IncludeComponent(
									"uvelirsoft:menu", 
									".default", 
									array(
										"COMPONENT_TEMPLATE" => ".default",
										"ITEM_COUNT" => "0",
										"NAME_1" => "SALE",
										"LINK_1" => "/magazin/catalog/filter/discount-is-y/apply/",
										"NAME_2" => "SALE",
										"LINK_2" => "/magazin/catalog/filter/discount-is-y/apply/",
										"CLASS_1" => "sale",
										"CLASS_2" => "sale",
										"NAME_3" => "test",
										"LINK_3" => "/magazin/catalog/",
										"CLASS_3" => "test",
										"COMPOSITE_FRAME_MODE" => "A",
										"COMPOSITE_FRAME_TYPE" => "AUTO",
										"MENU_POSITION" => "LEFT"
									),
									false
								);?>
							</div><!-- /.navbar-collapse -->
		                </div><!-- /.container-fluid -->
		            </nav>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-6 logo_block">
					<a href="/" class="logo">
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.include",
							".default",
							Array(
								"AREA_FILE_SHOW" => "file",
								"AREA_FILE_SUFFIX" => "inc",
								"COMPONENT_TEMPLATE" => ".default",
								"EDIT_TEMPLATE" => "",
								"PATH" => "/include/logo.php"
							)
						);?>
					</a>
				</div>
				<div class="col-md-3 col-sm-2 menu_right">
					<nav class="navbar navbar-default" role="navigation">
						<div class="container-fluid">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-catalogmenu2">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
							</div>
							<!-- Collect the nav links, forms, and other content for toggling -->
							<div class="sub-toggle-menu collapse navbar-collapse" id="navbar-collapse-catalogmenu2">
								<?$APPLICATION->IncludeComponent(
									"uvelirsoft:menu", 
									".default", 
									array(
										"COMPONENT_TEMPLATE" => ".default",
										"ITEM_COUNT" => "0",
										"NAME_1" => "SALE",
										"LINK_1" => "/magazin/catalog/filter/discount-is-y/apply/",
										"NAME_2" => "SALE",
										"LINK_2" => "/magazin/catalog/filter/discount-is-y/apply/",
										"CLASS_1" => "sale",
										"CLASS_2" => "sale",
										"NAME_3" => "test",
										"LINK_3" => "/magazin/catalog/",
										"CLASS_3" => "test",
										"COMPOSITE_FRAME_MODE" => "A",
										"COMPOSITE_FRAME_TYPE" => "AUTO",
										"MENU_POSITION" => "RIGHT"
									),
									false
								);?>
							</div><!-- /.navbar-collapse -->
						</div><!-- /.container-fluid -->
					</nav>
				</div>
				<div class="col-md-2 col-sm-3 search_and_profile">
					<?$APPLICATION->IncludeComponent(
						"bitrix:search.form",
						"dropdown_search",
						Array(
							"COMPONENT_TEMPLATE" => "suggest",
							"PAGE" => "#SITE_DIR#search/index.php",
							"USE_SUGGEST" => "N"
						)
					);?>
					<span id="auth_and_profile"></span>
					<?$APPLICATION->IncludeComponent(
						"bitrix:sale.basket.basket.line",
						"us",
						Array(
							"COMPONENT_TEMPLATE" => "us",
							"HIDE_ON_BASKET_PAGES" => "N",
							"PATH_TO_AUTHORIZE" => "/auth/",
							"PATH_TO_BASKET" => "/magazin/personal/cart/",
							"PATH_TO_ORDER" => "/magazin/personal/order/make/",
							"PATH_TO_PERSONAL" => "/magazin/personal/",
							"PATH_TO_PROFILE" => "/magazin/personal/",
							"PATH_TO_REGISTER" => "/auth/",
							"POSITION_FIXED" => "N",
							"SHOW_AUTHOR" => "N",
							"SHOW_EMPTY_VALUES" => "Y",
							"SHOW_NUM_PRODUCTS" => "Y",
							"SHOW_PERSONAL_LINK" => "N",
							"SHOW_PRODUCTS" => "N",
							"SHOW_TOTAL_PRICE" => "N"
						)
					);?>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="green-alert">
	<!-- <div class="container">
		<a href="/ural-emerald/" target="_blank">Мы продаём природные уральские александриты, изумруды, бериллы, аквамарины и фенакиты</a>
	</div> -->
	<div class="container">
		<div class="owl-carousel owl-alert">
			<div class="item">
				<span>Мы продаём украшения только с природными уральскими камнями: александритами, изумрудами, бериллами, аквамаринами и фенакитами</span>
			</div>
			<div class="item">
				<span>Мы продаём украшения только с природными якутскими бриллиантами</span>
			</div>
			<div class="item">
				<span>Мы продаём украшения только с природными сапфирами, рубинами и танзанитами</span>
			</div>
		</div>	
	</div>
	<script>
		$('.owl-alert').owlCarousel({
			items:1,
			loop: true,
			dots: false,
			margin:10,
			autoplay:true,
			autoplayTimeout:7000,
			autoplayHoverPause:true,
			smartSpeed:600,
		});
	</script>
</div>
<main>
	<?
		global $APPLICATION;
		$dir = $APPLICATION->GetCurDir();
	if($dir != '/'):?>
	<div class="container">
			<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0"
	),
	false
);?>
	<?endif?>
