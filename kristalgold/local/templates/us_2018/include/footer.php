<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
	</div>
</main>
<div class="pre_footer">
	<div class="container">
		<div class="col-md-12 col-sm-12">
			<div class="subscribe">
                <div class="title">Будьте в курсе наших новостей и скидок!</div>
				<div class="sender-container">
	                <?$APPLICATION->IncludeComponent(
	"bitrix:sender.subscribe", 
	".default", 
	array(
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"CONFIRMATION" => "Y",
		"SET_TITLE" => "N",
		"SHOW_HIDDEN" => "N",
		"USE_PERSONALIZATION" => "Y",
		"HIDE_MAILINGS" => "Y",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
				</div>
        	</div>
        </div>
	</div>
</div>
<footer>
	<div class="footer_top">
		<div class="container">
			<div class="col-md-2 col-sm-4 col-xs-6 hidden-xs">
				<div class="item_footer">
				<div class="menu_bottom">
					<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"bottom",
							Array(
								"ALLOW_MULTI_SELECT" => "N",
								"CHILD_MENU_TYPE" => "left",
								"COMPOSITE_FRAME_MODE" => "A",
								"COMPOSITE_FRAME_TYPE" => "AUTO",
								"DELAY" => "N",
								"MAX_LEVEL" => "1",
								"MENU_CACHE_GET_VARS" => array(0=>"",),
								"MENU_CACHE_TIME" => "3600",
								"MENU_CACHE_TYPE" => "A",
								"MENU_CACHE_USE_GROUPS" => "Y",
								"ROOT_MENU_TYPE" => "bottom_info",
								"USE_EXT" => "N"
							)
						);?>
						</div>
				</div>
			</div>
			<div class="col-md-6 hidden-sm hidden-xs">
				<div class="item_footer">
					<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"bottom_catalog", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "bottom_catalog",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "bottom_catalog",
		"USE_EXT" => "Y",
		"COMPONENT_TEMPLATE" => "bottom_catalog",
		"ITEM_COUNT" => "3",
		"POSITION_LEFT" => "Y",
		"NAME_1" => "Новинки",
		"LINK_1" => "/magazin/catalog/filter/newproduct-is-y/apply/",
		"CLASS_1" => "new",
		"NAME_2" => "Каталог",
		"LINK_2" => "/magazin/",
		"CLASS_2" => "catalog",
		"NAME_3" => "",
		"LINK_3" => "",
		"CLASS_3" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 hidden-mobile">
				<div class="item_footer">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"AREA_FILE_SUFFIX" => "inc",
							"COMPOSITE_FRAME_MODE" => "A",
							"COMPOSITE_FRAME_TYPE" => "AUTO",
							"EDIT_TEMPLATE" => "",
							"PATH" => "/include/bottom_paytype.php"
						)
					);?>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 width-100">
				<div class="item_footer">
					<div class="inform">
						<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/logo_footer.php"
	),
	false
);?>
						<div class = "company">
							<?$APPLICATION->IncludeComponent(
								 "bitrix:main.include",
								 ".default",
								 Array(
									 "AREA_FILE_SHOW" => "file",
									 "AREA_FILE_SUFFIX" => "inc",
									 "COMPONENT_TEMPLATE" => ".default",
									 "EDIT_TEMPLATE" => "",
									 "PATH" => "/include/company_name.php"
								 )
							 );?>
						</div>
						<div class = "addres">
							<?$APPLICATION->IncludeComponent(
								 "bitrix:main.include",
								 ".default",
								 Array(
									 "AREA_FILE_SHOW" => "file",
									 "AREA_FILE_SUFFIX" => "inc",
									 "COMPONENT_TEMPLATE" => ".default",
									 "EDIT_TEMPLATE" => "",
									 "PATH" => "/include/address.php"
								 )
							 );?>
						</div>
						<div class = "phone">
							<?$APPLICATION->IncludeComponent(
								 "bitrix:main.include",
								 ".default",
								 Array(
									 "AREA_FILE_SHOW" => "file",
									 "AREA_FILE_SUFFIX" => "inc",
									 "COMPONENT_TEMPLATE" => ".default",
									 "EDIT_TEMPLATE" => "",
									 "PATH" => "/include/phone.php"
								 )
							 );?>
						</div>
						<div class="socials">
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
									"SOCIAL_ICON_SIZE" => "fa-md",
									"SOCIAL_LINK" => "LINK"
								),
								false
							);?>
						</div>
					</div>
				</div>
			</div>
		</div>


		<a href="#top" class="scroll_top" id="scroll_top">
			<i class="fa fa-angle-double-up" aria-hidden="true"></i>
		</a>
	</footer>
