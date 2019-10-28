<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;


/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */

$APPLICATION->SetPageProperty("og:image",'https://kristallgold.ru//upload/iblock/a1c/Kolco_Serebro_925_102101845.JPG');
$APPLICATION->SetPageProperty("twitter:image",'https://kristallgold.ru//upload/iblock/a1c/Kolco_Serebro_925_102101845.JPG');

if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
{
	$basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
}
else
{
	$basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
}



$changeContent=false;
switch ($arResult["VARIABLES"]["SECTION_ID"]) {
			case 326:
			$GLOBALS['arrFilter'] = array(
				"PROPERTY_406_VALUE"=>array("Изумруд гидротермальный","Изумруд природный уральский")
			);
			$cat_id=$arResult["VARIABLES"]["SECTION_ID"];
			$changeContent=true;
			break;
	}

	if ($changeContent) {
		$SECTION_ID =2;
		$SECTION_CODE = 'catalog';
	} else {
		$SECTION_ID =$arResult["VARIABLES"]["SECTION_ID"];
		$SECTION_CODE = $arResult["VARIABLES"]["SECTION_CODE"];
	}


	if ($changeContent) {

		?>
		<style media="screen">
			.wr-menu-us {
				list-style-type: none;
		    margin-top: 87px;
		    font-size: 14px;
			}
			ul.wr-menu-us li {
	    margin-top: 4px;
	}
		.wr-menu-us div {
			color: #459e80;
			font-size: 16px;
			font-weight: bold;
			margin-bottom: 10px;
		}
		@media screen and (max-width: 770px) {
			.wr-menu-us {
				list-style-type: none;
    font-size: 14px;
    padding: 0px;
    display: flex;
    flex-wrap: wrap;
		margin-top: 40px;

			}
    .wr-menu-us div {
    	display: none;
    }
		ul.wr-menu-us li {
    margin-left: 9px;
    margin-top: 4px;
}

		}
		</style>


		<div class="col-md-3 col-sm-4">

			<ul class="wr-menu-us">

				<div class="prop-title">Каталог</div>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/rasprodazha/">Распродажа</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/rings/">Кольца</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/sergi/">Серьги</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/braslety/">Браслеты</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/kole/">Колье</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/broshi/">Броши</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/pendants/">Подвески</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/suveniry/">Сувениры</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/filter/kollektsiya_dlya_sayta-is-kollekciya-ukrashenij-s-redkimi-prirodnymi-kamnyami/apply/">Редкие драгоценные камни</a></li>
				<li><a href="https://www.kristallgold.ru/magazin/catalog/filter/kollektsiya_dlya_sayta-is-kollekciya-krestikov-s-dragocennymi-kamnyami/apply/">Крестики</a></li>
			</ul>
		</div>
		<script type="text/javascript">
			$('.bx-sidebar-block').css('display','none');
		</script>

		<?

	}

if ($isFilter || $isSidebar): ?>
	<div class="col-md-3 col-sm-4<?=(isset($arParams['FILTER_HIDE_ON_MOBILE']) && $arParams['FILTER_HIDE_ON_MOBILE'] === 'Y' ? ' hidden-xs' : '')?>">
		<? if ($isFilter): ?>
			<div class="bx-sidebar-block">
				<?$APPLICATION->ShowViewContent('filter_tags');?>
				<?
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.smart.filter",
					"uvelirsoft",
					array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"SECTION_ID" => $arCurSection['ID'],
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"PRICE_CODE" => $arParams["~PRICE_CODE"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SAVE_IN_SESSION" => "N",
						"FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
						"XML_EXPORT" => "N",
						"SECTION_TITLE" => "NAME",
						"SECTION_DESCRIPTION" => "DESCRIPTION",
						'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
						"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID'],
						"SEF_MODE" => $arParams["SEF_MODE"],
						"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
						"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],


						"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
						"INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
					),
					$component,
					array('HIDE_ICONS' => 'Y')
				);
			//	echo $arResult["VARIABLES"]["SMART_FILTER_PATH"].'<br>';
				?>
			</div>
		<? endif ?>
		<? if ($isSidebar): ?>
			<div class="hidden-xs">
				<?
				$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"PATH" => $arParams["SIDEBAR_PATH"],
						"AREA_FILE_RECURSIVE" => "N",
						"EDIT_MODE" => "html",
					),
					false,
					array('HIDE_ICONS' => 'Y')
				);
				?>
			</div>
		<?endif?>
	</div>
<?endif?>
<div class="<?=(($isFilter || $isSidebar) ? "col-md-9 col-sm-8" : "col-xs-12")?> catalog_main">
	<div class="row">
		<div class="col-xs-12">
			<?
			if (ModuleManager::isModuleInstalled("sale"))
			{
				$arRecomData = array();
				$recomCacheID = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
				$obCache = new CPHPCache();
				if ($obCache->InitCache(36000, serialize($recomCacheID), "/sale/bestsellers"))
				{
					$arRecomData = $obCache->GetVars();
				}
				elseif ($obCache->StartDataCache())
				{
					if (Loader::includeModule("catalog"))
					{
						$arSKU = CCatalogSku::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
						$arRecomData['OFFER_IBLOCK_ID'] = (!empty($arSKU) ? $arSKU['IBLOCK_ID'] : 0);
					}
					$obCache->EndDataCache($arRecomData);
				}

				if (!empty($arRecomData) && $arParams['USE_GIFTS_SECTION'] === 'Y')
				{
					?>
					<div data-entity="parent-container">
						<?
						if (!isset($arParams['GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE']) || $arParams['GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE'] !== 'Y')
						{
							?>
							<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
								<?=($arParams['GIFTS_SECTION_LIST_BLOCK_TITLE'] ?: \Bitrix\Main\Localization\Loc::getMessage('CT_GIFTS_SECTION_LIST_BLOCK_TITLE_DEFAULT'))?>
							</div>
							<?
						}

						CBitrixComponent::includeComponentClass('bitrix:sale.products.gift.section');
						$APPLICATION->IncludeComponent(
							'bitrix:sale.products.gift.section',
							'.default',
							array(
								'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
								'IBLOCK_ID' => $arParams['IBLOCK_ID'],

								'SECTION_ID' => $arResult['VARIABLES']['SECTION_ID'],
								'SECTION_CODE' => $arResult['VARIABLES']['SECTION_CODE'],
								'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],

								'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
								'ACTION_VARIABLE' => (!empty($arParams['ACTION_VARIABLE']) ? $arParams['ACTION_VARIABLE'] : 'action').'_spgs',

								'PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
									SaleProductsGiftSectionComponent::predictRowVariants(
										$arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'],
										$arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT']
									)
								),
								'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT'],
								'DEFERRED_PRODUCT_ROW_VARIANTS' => '',
								'DEFERRED_PAGE_ELEMENT_COUNT' => 0,

								'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
								'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
								'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
								'PRODUCT_DISPLAY_MODE' => 'Y',
								'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
								'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
								'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
								'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

								'TEXT_LABEL_GIFT' => $arParams['GIFTS_DETAIL_TEXT_LABEL_GIFT'],

								'LABEL_PROP_'.$arParams['IBLOCK_ID'] => array(),
								'LABEL_PROP_MOBILE_'.$arParams['IBLOCK_ID'] => array(),
								'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

								'ADD_TO_BASKET_ACTION' => $basketAction,
								'MESS_BTN_BUY' => $arParams['~GIFTS_MESS_BTN_BUY'],
								'MESS_BTN_ADD_TO_BASKET' => $arParams['~GIFTS_MESS_BTN_BUY'],
								'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
								'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],

								'PROPERTY_CODE' => $arParams['LIST_PROPERTY_CODE'],
								'PROPERTY_CODE_MOBILE' => $arParams['LIST_PROPERTY_CODE_MOBILE'],
								'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],

								'OFFERS_FIELD_CODE' => $arParams['LIST_OFFERS_FIELD_CODE'],
								'OFFERS_PROPERTY_CODE' => $arParams['LIST_OFFERS_PROPERTY_CODE'],
								'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
								'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
								'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],

								'HIDE_NOT_AVAILABLE' => 'Y',
								'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
								'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
								'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
								'PRICE_CODE' => $arParams['~PRICE_CODE'],
								'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
								'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
								'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
								'BASKET_URL' => $arParams['BASKET_URL'],
								'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
								'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
								'PARTIAL_PRODUCT_PROPERTIES' => $arParams['PARTIAL_PRODUCT_PROPERTIES'],
								'USE_PRODUCT_QUANTITY' => 'N',
								'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
								'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

								'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
								'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
								'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),
							),
							$component,
							array("HIDE_ICONS" => "Y")
						);
						?>
					</div>
					<?
				}
			}
			?>
		</div>
		<?
 		?>
		<div class="col-xs-12">
			<?
			$APPLICATION->IncludeComponent(
				"bitrix:catalog.section.list",
				"",
				array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
					"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
					"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
					"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
					"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
					"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
					'SEO_FILTER'=>$arSEO
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
			?>
			<div class="sort clearfix">
			<?
				/***SORT***/

				// массив значений для выбора сортировки
				$arAvailableSort = array(
					//"artnum"	=> Array("PROPERTY_ARTNUMBER", "asc"), /* по умолчанию */
					"date" 		=> Array("DATE_CREATE", "desc"),
					"price_min" => Array("PROPERTY_MINIMUM_PRICE", "asc"),
					"price_max" => Array("PROPERTY_MINIMUM_PRICE", "desc"),
				);

				$firstSortKey = '';
				foreach($arAvailableSort as $sortKey => $arSort){
					$firstSortKey = $sortKey;
					break;
				}

				$sort = $APPLICATION->get_cookie("sort") ? $APPLICATION->get_cookie("sort") : $arAvailableSort[$firstSortKey][0];
				$sort_order = $APPLICATION->get_cookie("order") ? $APPLICATION->get_cookie("order") : $arAvailableSort[$firstSortKey][1];
				if( $_REQUEST["sort"] ) {
					if ( $val = $arAvailableSort[$_REQUEST["sort"]] ) {
						$sort = $val[0];
						$sort_order = $val[1];
						$APPLICATION->set_cookie("sort", $sort);
						$APPLICATION->set_cookie("order", $sort_order);
					}
				}

				?>
				<div class="sort-item">
					<?=GetMessage("SECT_SORT_LABEL_FULL")?>
					<select id="w0-filter-sortable" class="style-select style-select--mod1" name="w0-filter-sortable">
					<?foreach($arAvailableSort as $key => $val):
						$selected = ( ($sort == $val[0] && $sort_order == $val[1]) ? 'selected="selected"' : '' );
						?><option value="<?=$key?>" <?=$selected?> data-url="<?=$APPLICATION->GetCurPageParam("sort=".$key, array("sort","bxajaxid"))?>"><?=GetMessage("SECT_SORT_".$key)?></option>
					<?endforeach;?>
					</select>
				</div>
				<script type="text/javascript">
					$( document ).ready(function() {
						$('#w0-filter-sortable').change(function(){
							if ( url = $('#w0-filter-sortable :selected').attr('data-url') ) {
								window.location.href = url;
								return false;
							}
						});
					});
				</script>
				<div class="sort-line"></div>
				<?

				/***LIMIT***/
				$arAvailableLimit = array(16, 32, 64);
				$limit = $APPLICATION->get_cookie("limit") ? $APPLICATION->get_cookie("limit") : $arAvailableLimit[0];

				if( (int)$_REQUEST["limit"] ) {
					if ( in_array( $_REQUEST["limit"], $arAvailableLimit ) ) {
						$limit = (int)$_REQUEST["limit"];
					} else {
						$limit = $arAvailableLimit[0];
					}
					$APPLICATION->set_cookie("limit", $limit);
				}
				?>
				<?ob_start();?>
				<div class="sort-item">
					<?=GetMessage("SECT_COUNT_LABEL_FULL")?>
					<div class="sort-btns">
						<span class="bx_item_section_name_gray"> <span><? echo $limit ?></span></span>
						<div class="bx_size_scroller_container">
							<div class="bx_size wrapper-dropdown">
								<span class="ul_name"></span>
								<ul class="dropdown">
								<?//printvar('',$arAvailableLimit);
								foreach($arAvailableLimit as $val)
								{

									?>
									<li>
										<a href="<?=$APPLICATION->GetCurPageParam("limit=".$val, array("limit","bxajaxid"))?>"  class="sort-btn<?if($limit==$val) echo ' active';?>" rel="nofollow"><?if($val=="900"): echo GetMessage("SECT_COUNT_ALL"); else: echo '<span>'.$val.'</span>'; endif;?></a>
									</li>
									<?
								}
								?>
								</ul>
							</div>
							<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
							<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
						</div>
					</div>
				</div>
				<?
				$itemCounter = ob_get_contents();
				ob_end_flush();
				?>
			</div>
			<?
			if ($arParams["USE_COMPARE"]=="Y")
			{
				$APPLICATION->IncludeComponent(
					"bitrix:catalog.compare.list",
					"",
					array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"NAME" => $arParams["COMPARE_NAME"],
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
						"ACTION_VARIABLE" => (!empty($arParams["ACTION_VARIABLE"]) ? $arParams["ACTION_VARIABLE"] : "action"),
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						'POSITION_FIXED' => isset($arParams['COMPARE_POSITION_FIXED']) ? $arParams['COMPARE_POSITION_FIXED'] : '',
						'POSITION' => isset($arParams['COMPARE_POSITION']) ? $arParams['COMPARE_POSITION'] : ''
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);
			}

			$intSectionID = $APPLICATION->IncludeComponent(
				"bitrix:catalog.section",
				"",
				array(
					"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],

					"ITEM_COUNTER" => $itemCounter,

					//"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
					//"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
					//"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
					//"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],

					// "ELEMENT_SORT_FIELD" => $sort,
					// "ELEMENT_SORT_ORDER" => $sort_order,
					// "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD"],
					// "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER"],

					"ELEMENT_SORT_FIELD" => "PROPERTY_HAS_PICTURE",
					"ELEMENT_SORT_ORDER" => "DESC",
					"ELEMENT_SORT_FIELD2" => $sort,
					"ELEMENT_SORT_ORDER2" => $sort_order,

					//"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
					"PAGE_ELEMENT_COUNT" => (isset($limit) ? $limit : $arParams["PAGE_ELEMENT_COUNT"]),

					"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
					"PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
					"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
					"SET_TITLE" => "N", // устанавливаем в component_epilog.php (/local/templates/us_2018/components/bitrix/catalog.section/.default)
					"SET_BROWSER_TITLE" => "N", // устанавливаем в component_epilog.php (/local/templates/us_2018/components/bitrix/catalog.section/.default)
					"SET_META_DESCRIPTION"=> "N", // устанавливаем в component_epilog.php (/local/templates/us_2018/components/bitrix/catalog.section/.default)
					"SET_META_KEYWORDS"=> "N", // устанавливаем в component_epilog.php (/local/templates/us_2018/components/bitrix/catalog.section/.default)
					"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
					"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
					"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
					"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
					"BASKET_URL" => $arParams["BASKET_URL"],
					"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
					"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
					"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
					"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
					"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
					"FILTER_NAME" => $arParams["FILTER_NAME"],
					"CACHE_TYPE" => $arParams["CACHE_TYPE"],
					"CACHE_TIME" => $arParams["CACHE_TIME"],
					"CACHE_FILTER" => $arParams["CACHE_FILTER"],
					"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
					"SET_TITLE" => $arParams["SET_TITLE"],
					"MESSAGE_404" => $arParams["~MESSAGE_404"],
					"SET_STATUS_404" => $arParams["SET_STATUS_404"],
					"SHOW_404" => $arParams["SHOW_404"],
					"FILE_404" => $arParams["FILE_404"],
					"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
					"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
					"PRICE_CODE" => $arParams["~PRICE_CODE"],
					"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
					"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

					"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
					"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
					"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
					"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
					"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

					"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
					"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
					"PAGER_TITLE" => $arParams["PAGER_TITLE"],
					"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
					"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
					"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
					"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
					"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
					"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
					"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
					"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
					"LAZY_LOAD" => $arParams["LAZY_LOAD"],
					"MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
					"LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

					"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
					"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
					"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
					"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
					"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
					"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
					"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
					"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

					//"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
				//	"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
				"SECTION_ID" => $SECTION_ID,
				"SECTION_CODE" => $SECTION_CODE,

					"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
					"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
					"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
					'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
					'CURRENCY_ID' => $arParams['CURRENCY_ID'],
					'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
					'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

					'LABEL_PROP' => $arParams['LABEL_PROP'],
					'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
					'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
					'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
					'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
					'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
					'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
					'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
					'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
					'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
					'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
					'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

					'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
					'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
					'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
					'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
					'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
					'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
					'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
					'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
					'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
					'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
					'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
					'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
					'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
					'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
					'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
					'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
					'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

					'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
					'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
					'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

					'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
					"ADD_SECTIONS_CHAIN" => "N",
					'ADD_TO_BASKET_ACTION' => $basketAction,
					'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
					'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
					'COMPARE_NAME' => $arParams['COMPARE_NAME'],
					'USE_COMPARE_LIST' => 'Y',
					'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
					'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
					'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),

					'USE_VOTE_RATING' => $arParams['LIST_USE_VOTE_RATING'],
					'VOTE_DISPLAY_AS_RATING' => (isset($arParams['LIST_VOTE_DISPLAY_AS_RATING']) ? $arParams['LIST_VOTE_DISPLAY_AS_RATING'] : ''),
					'SEO_FILTER'=>$arSEO
				),
				$component
			);
			?>
		</div>
		<?

		$GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID;

		if (ModuleManager::isModuleInstalled("sale"))
		{
			if (!empty($arRecomData))
			{
				if (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N')
				{
					?>
					<div class="col-xs-12" data-entity="parent-container">
						<div class="catalog-block-header" data-entity="header" data-showed="false" style="display: none; opacity: 0;">
							<?=GetMessage('CATALOG_PERSONAL_RECOM')?>
						</div>
						<?
						$APPLICATION->IncludeComponent(
							"bitrix:catalog.section",
							"",
							array(
								"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
								"IBLOCK_ID" => $arParams["IBLOCK_ID"],
								"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
								"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
								"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
								"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
								"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
								"PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
								"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
								"BASKET_URL" => $arParams["BASKET_URL"],
								"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
								"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
								"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
								"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
								"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_FILTER" => $arParams["CACHE_FILTER"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
								"PAGE_ELEMENT_COUNT" => 0,
								"PRICE_CODE" => $arParams["~PRICE_CODE"],
								"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
								"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

								"SET_BROWSER_TITLE" => "N",
								"SET_META_KEYWORDS" => "N",
								"SET_META_DESCRIPTION" => "N",
								"SET_LAST_MODIFIED" => "N",
								"ADD_SECTIONS_CHAIN" => "N",

								"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
								"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
								"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
								"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
								"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

								"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
								"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
								"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
								"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
								"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
								"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
								"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
								"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

								"SECTION_ID" => $intSectionID,
								"SECTION_CODE" => "",
								"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
								"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
								"USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
								'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
								'CURRENCY_ID' => $arParams['CURRENCY_ID'],
								'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
								'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

								'LABEL_PROP' => $arParams['LABEL_PROP'],
								'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
								'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
								'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
								'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
								'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
								'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'3','BIG_DATA':true}]",
								'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
								'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
								'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
								'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
								'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

								"DISPLAY_TOP_PAGER" => 'N',
								"DISPLAY_BOTTOM_PAGER" => 'N',
								"HIDE_SECTION_DESCRIPTION" => "Y",

								"RCM_TYPE" => isset($arParams['BIG_DATA_RCM_TYPE']) ? $arParams['BIG_DATA_RCM_TYPE'] : '',
								"SHOW_FROM_SECTION" => 'Y',

								'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
								'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
								'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
								'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
								'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
								'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
								'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
								'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
								'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
								'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
								'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
								'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
								'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
								'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
								'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
								'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
								'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

								'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
								'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
								'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

								'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
								'ADD_TO_BASKET_ACTION' => $basketAction,
								'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
								'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
								'COMPARE_NAME' => $arParams['COMPARE_NAME'],
								'USE_COMPARE_LIST' => 'Y',
								'BACKGROUND_IMAGE' => '',
								'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : '')
							),
							$component
						);
						?>
					</div>
					<?
				}
			}
		}
		?>
	</div>
</div>

<?

	$rsResult = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ID" => $arCurSection), false, $arSelect = array("UF_*"));

	if($arSec = $rsResult->GetNext()) {

		if($arSec['IBLOCK_SECTION_ID']){
			$rsResultParent = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ID" => $arSec['IBLOCK_SECTION_ID']), false, $arSelect = array("UF_*"));


			if($arSecParent = $rsResultParent -> GetNext()) {
				$APPLICATION->SetPageProperty("og:title", $arSecParent['UF_OG_TITLE']);
				$APPLICATION->SetPageProperty("og:description", $arSecParent['UF_OG_DESCRIPTION']);
				$APPLICATION->SetPageProperty("og:image", $arSecParent['UF_OG_IMAGE']);
				$APPLICATION->SetPageProperty("twitter:title", $arSecParent['UF_TWITTER_TITLE']);
				$APPLICATION->SetPageProperty("twitter:description", $arSecParent['UF_TWITTER_DESCR']);
				$APPLICATION->SetPageProperty("twitter:hashtags", $arSecParent['UF_TWITTER_HASHTAGS']);
			}
		}


		//printvar('', $arSec['DESCRIPTION']);
		if($arSec['DESCRIPTION']){
			?>
			<div class="col-xs-12">
				<div class="bx-section-desc">
					<p class="bx-section-desc-post"><?=$arSec['DESCRIPTION']?></p>
				</div>
			</div>
			<?
		}
		// UF_OG_TITLE
		// UF_OG_DESCRIPTION
		// UF_OG_IMAGE
		// UF_TWITTER_TITLE
		// UF_TWITTER_DESCR
		// UF_TWITTER_HASHTAGS

		$APPLICATION->SetPageProperty("og:title", $arSec['UF_OG_TITLE']);
		$APPLICATION->SetPageProperty("og:description", $arSec['UF_OG_DESCRIPTION']);
		$APPLICATION->SetPageProperty("og:image", $arSec['UF_OG_IMAGE']);
		$APPLICATION->SetPageProperty("twitter:title", $arSec['UF_TWITTER_TITLE']);
		$APPLICATION->SetPageProperty("twitter:description", $arSec['UF_TWITTER_DESCR']);
		$APPLICATION->SetPageProperty("twitter:hashtags", $arSec['UF_TWITTER_HASHTAGS']);


	}

if ( count($arSEO) ) {
	$APPLICATION->SetPageProperty('og:title', $arSEO['TITLE_SEO_VALUE']);
	$APPLICATION->SetPageProperty('og:description', $arSEO['DESCRIPTION_SEO_VALUE']);
}


/*change*/

/*

switch ($arResult['VARIABLES']['SMART_FILTER_PATH']) {
			case "tipizdeliya-is-kolco/metall-is-zoloto/tsvet_metalla-is-belyj":
			$resultFields = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => 1, "ID" => 325), false, $arSelect = array("UF_*"));
			break;

			case "osnovnaya_vstavka-is-izumrud-prirodnyj-uralskij-or-izumrud-gidrotermalnyj/vstavki-is-izumrud-or-izumrud-gidrotermalnyj-or-izumrud*-or-izumrud-gt-or-izumrud-prirodnyj-uralskij":
			$resultFields = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => 1, "ID" => 326), false, $arSelect = array("UF_*"));
			break;

}
if ($resultFields)   {
	$result=$resultFields -> GetNext();





?>

<script type="text/javascript">
	$('.bx_catalog_text a').text("<?=$result['NAME']?>");
</script>
<div class="col-xs-12">
	<div class="bx-section-desc">
		<p class="bx-section-desc-post"><?=$result['DESCRIPTION'];?></p>
	</div>
</div>

<?


	if (count($resultFields)> 0 ) {

		if (strlen($mtitle=$result['UF_BROWSER_TITLE'])>0)
				$APPLICATION->SetPageProperty("title", $mtitle);

		if (strlen($mkey=$result['UF_KEYWORDS'])>0)
				$APPLICATION->SetPageProperty("keywords", $mkey);

		if (strlen($mdesc=$result['UF_META_DESCRIPTION'])>0)
				$APPLICATION->SetPageProperty("description", $mdesc);





		if (strlen($ogtitle=$result['UF_OG_TITLE'])>0)
				$APPLICATION->SetPageProperty("og:title", $ogtitle);

		if (strlen($ogdesc=$result ['UF_OG_DESCRIPTION'])>0)
		{
			$APPLICATION->SetPageProperty("og:description", $ogdesc);
		}


		if (strlen($ogimg=$result ['UF_OG_IMAGE'])>0)
				$APPLICATION->SetPageProperty("og:image", $ogimg);

		if (strlen($twtitle=$result ['UF_TWITTER_TITLE'])>0)
				$APPLICATION->SetPageProperty("twitter:title", $twtitle);

		if (strlen($twdesc=$result ['UF_TWITTER_DESCR'])>0)
				$APPLICATION->SetPageProperty("twitter:description", $twdesc);

		if (strlen($twhash=$result ['UF_TWITTER_HASHTAGS'])>0)
				$APPLICATION->SetPageProperty("twitter:hashtags", $twhash);

		if (strlen($twimg=$result ['UF_TWITTER_IMG'])>0)
				$APPLICATION->SetPageProperty("twitter:img", $twimg);
	}




}*/

/*change*/

if ($changeContent) {
	//$APPLICATION->SetPageProperty("title", "Украшения с изумрудами");
	$ipropSectionValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(1, $cat_id);
	$arSEOmain = $ipropSectionValues->getValues();

	if ($arSEOmain['SECTION_META_TITLE'] != false) {
			$APPLICATION->SetPageProperty("title", $arSEOmain['SECTION_META_TITLE']);
		}
		if ( $arSEOmain['SECTION_META_KEYWORDS'] != false) {
			$APPLICATION->SetPageProperty("keywords",  $arSEOmain['SECTION_META_KEYWORDS']);
		}
		if ( $arSEOmain['SECTION_META_DESCRIPTION'] != false) {
			$APPLICATION->SetPageProperty("description",  $arSEOmain['SECTION_META_DESCRIPTION']);
		}
}
?>
