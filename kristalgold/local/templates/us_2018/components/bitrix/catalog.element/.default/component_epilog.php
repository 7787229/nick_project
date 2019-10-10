<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

/**
 * @var array $templateData
 * @var array $arParams
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

global $APPLICATION;

if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;

	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);
	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

if (isset($templateData['JS_OBJ']))
{
	?>
	<script>
		BX.ready(BX.defer(function(){
			if (!!window.<?=$templateData['JS_OBJ']?>)
			{
				window.<?=$templateData['JS_OBJ']?>.allowViewedCount(true);
			}
		}));
	</script>

	<?
	// check compared state
	if ($arParams['DISPLAY_COMPARE'])
	{
		$compared = false;
		$comparedIds = array();
		$item = $templateData['ITEM'];

		if (!empty($_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]))
		{
			if (!empty($item['JS_OFFERS']))
			{
				foreach ($item['JS_OFFERS'] as $key => $offer)
				{
					if (array_key_exists($offer['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
					{
						if ($key == $item['OFFERS_SELECTED'])
						{
							$compared = true;
						}

						$comparedIds[] = $offer['ID'];
					}
				}
			}
			elseif (array_key_exists($item['ID'], $_SESSION[$arParams['COMPARE_NAME']][$item['IBLOCK_ID']]['ITEMS']))
			{
				$compared = true;
			}
		}

		if ($templateData['JS_OBJ'])
		{
			?>
			<script>
				BX.ready(BX.defer(function(){
					if (!!window.<?=$templateData['JS_OBJ']?>)
					{
						window.<?=$templateData['JS_OBJ']?>.setCompared('<?=$compared?>');

						<? if (!empty($comparedIds)): ?>
						window.<?=$templateData['JS_OBJ']?>.setCompareInfo(<?=CUtil::PhpToJSObject($comparedIds, false, true)?>);
						<? endif ?>
					}
				}));
			</script>
			<?
		}
	}

	// select target offer
	$request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();
	$offerNum = false;
	$offerId = (int)$this->request->get('OFFER_ID');
	$offerCode = $this->request->get('OFFER_CODE');

	if ($offerId > 0 && !empty($templateData['OFFER_IDS']) && is_array($templateData['OFFER_IDS']))
	{
		$offerNum = array_search($offerId, $templateData['OFFER_IDS']);
	}
	elseif (!empty($offerCode) && !empty($templateData['OFFER_CODES']) && is_array($templateData['OFFER_CODES']))
	{
		$offerNum = array_search($offerCode, $templateData['OFFER_CODES']);
	}

	if (!empty($offerNum))
	{
		?>
		<script>
			BX.ready(function(){
				if (!!window.<?=$templateData['JS_OBJ']?>)
				{
					window.<?=$templateData['JS_OBJ']?>.setOffer(<?=$offerNum?>);
				}
			});
		</script>
		<?
	}
}

if(empty($arResult['PROPS_TO_POPUP']['PICTURE'])){
	$arResult['PROPS_TO_POPUP']['PICTURE'] = $templateFolder."/images/no_photo.png";
}

?>

<?if($arParams['USE_ORDER_ONE_CLICK'] == 'Y'){?>
	<div id='popupToOrderInOneClick_background'>
		<div id='popupToOrderInOneClick' class='modal-content'>
			<div class='modal-body'>
				<span class='glyphicon glyphicon-remove' onclick='hidePopup();'></span>
				<?$APPLICATION->IncludeComponent(
					"bitrix:form.result.new",
					"order_in_one_click",
					Array(
						"CACHE_TIME" => "3600",
						"CACHE_TYPE" => "N",
						"CHAIN_ITEM_LINK" => "",
						"CHAIN_ITEM_TEXT" => "",
						"COMPOSITE_FRAME_MODE" => "A",
						"COMPOSITE_FRAME_TYPE" => "AUTO",
						"EDIT_URL" => "",
						"IGNORE_CUSTOM_TEMPLATE" => "N",
						"LIST_URL" => "",
						"SEF_MODE" => "N",
						"SUCCESS_URL" => "",
						"USE_EXTENDED_ERRORS" => "N",
						"VARIABLE_ALIASES" => array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID",),
						"WEB_FORM_ID" => "1",

						"PRODUCT_ID" => $arResult['PROPS_TO_POPUP']['ID'],
						"PRODUCT_NAME" => $arResult['PROPS_TO_POPUP']['NAME'],
						"PRODUCT_ARTICLE" => $arResult['PROPS_TO_POPUP']['ARTNUMBER'],
						"PRODUCT_LINK" => $arResult['PROPS_TO_POPUP']['LINK'],
						"PRODUCT_PICTURE" => $arResult['PROPS_TO_POPUP']['PICTURE'],
					)
				);?>
			</div>
		</div>
	</div>

	<script>
	function showPopup(){
		$("#popupToOrderInOneClick_background").show();
	}
	function hidePopup(){
		$("#popupToOrderInOneClick_background").hide();
	}
	</script>
<?}?>

<?if($arParams['USE_NO_SIZE'] == 'Y'){?>
	<div id='popupRazmerNoSize_background'>
	    <div id='popupRazmerNoSize' class='modal-content'>
	        <div class='modal-body'>
	            <span class='glyphicon glyphicon-remove' onclick='hidePopupNoSize();'></span>
			    <?$APPLICATION->IncludeComponent(
			        "bitrix:form.result.new",
			        "not_my_size",
			        Array(
			            "CACHE_TIME" => "3600",
			            "CACHE_TYPE" => "N",
			            "CHAIN_ITEM_LINK" => "",
			            "CHAIN_ITEM_TEXT" => "",
			            "COMPOSITE_FRAME_MODE" => "A",
			            "COMPOSITE_FRAME_TYPE" => "AUTO",
			            "EDIT_URL" => "",
			            "IGNORE_CUSTOM_TEMPLATE" => "N",
			            "LIST_URL" => "",
			            "SEF_MODE" => "N",
			            "SUCCESS_URL" => "",
			            "USE_EXTENDED_ERRORS" => "N",
			            "VARIABLE_ALIASES" => array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID",),
			            "WEB_FORM_ID" => "3",

						"PRODUCT_ID" => $arResult['PROPS_TO_POPUP']['ID'],
						"PRODUCT_NAME" => $arResult['PROPS_TO_POPUP']['NAME'],
						"PRODUCT_ARTICLE" => $arResult['PROPS_TO_POPUP']['ARTNUMBER'],
						"PRODUCT_LINK" => $arResult['PROPS_TO_POPUP']['LINK'],
						"PRODUCT_PICTURE" => $arResult['PROPS_TO_POPUP']['PICTURE'],
			        ),
					$component
			    );?>
			</div>
		</div>
	</div>

	<script>
		function showPopupNoSize(){
			$("#popupRazmerNoSize_background").show();
		}
		function hidePopupNoSize(){
			$("#popupRazmerNoSize_background").hide();
		}
	</script>
<?}?>

<?if($arResult['NEED_ORDER_PRODUCT'] == 'Y'){?>
	<div id='popupOrderProduct_background'>
	    <div id='popupOrderProduct' class='modal-content'>
	        <div class='modal-body'>
	            <span class='glyphicon glyphicon-remove' onclick='hidePopupOrderProduct();'></span>
			    <?$APPLICATION->IncludeComponent(
			        "bitrix:form.result.new",
			        "order_product",
			        Array(
			            "CACHE_TIME" => "3600",
			            "CACHE_TYPE" => "N",
			            "CHAIN_ITEM_LINK" => "",
			            "CHAIN_ITEM_TEXT" => "",
			            "COMPOSITE_FRAME_MODE" => "A",
			            "COMPOSITE_FRAME_TYPE" => "AUTO",
			            "EDIT_URL" => "",
			            "IGNORE_CUSTOM_TEMPLATE" => "N",
			            "LIST_URL" => "",
			            "SEF_MODE" => "N",
			            "SUCCESS_URL" => "",
			            "USE_EXTENDED_ERRORS" => "N",
			            "VARIABLE_ALIASES" => array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID",),
			            "WEB_FORM_ID" => "4",

						"PRODUCT_ID" => $arResult['PROPS_TO_POPUP']['ID'],
						"PRODUCT_NAME" => $arResult['PROPS_TO_POPUP']['NAME'],
						"PRODUCT_ARTICLE" => $arResult['PROPS_TO_POPUP']['ARTNUMBER'],
						"PRODUCT_LINK" => $arResult['PROPS_TO_POPUP']['LINK'],
						"PRODUCT_PICTURE" => $arResult['PROPS_TO_POPUP']['PICTURE'],
			        ),
					$component
			    );?>
			</div>
		</div>
	</div>

	<script>
		function showPopupOrderProduct(){
			$("#popupOrderProduct_background").show();
		}
		function hidePopupOrderProduct(){
			$("#popupOrderProduct_background").hide();
		}
	</script>
<?}?>
