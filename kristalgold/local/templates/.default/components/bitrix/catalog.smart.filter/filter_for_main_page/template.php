<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);




$arNewFilter = array("TIPIZDELIYA","METALL","VSTAVKA","CENA");
$arNewFilterIDs = array();

// уберем лишние свойства из массива и заменим тип
foreach ($arResult["ITEMS"] as $idItem => $arItem) {
	if(!in_array($arItem["CODE"],$arNewFilter)){
		unset($arResult["ITEMS"][$idItem]);
		continue;
	}
	$arResult["ITEMS"][$idItem]["DISPLAY_TYPE"] = "P";
}

//printvar("s",$arResult["ITEMS"]);
foreach ($arNewFilter as $codeFilter) {
	foreach ($arResult["ITEMS"] as $idItem => $arItem) {
		if($arItem["CODE"] == $codeFilter){
			$arResult["NEW_ITEMS"][$arItem["ID"]] = $arItem;
			break;	
		}
	}
}

unset($arResult["ITEMS"]);



$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/colors.css',
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME']
);

if (isset($templateData['TEMPLATE_THEME']))
{
	// $this->addExternalCss($templateData['TEMPLATE_THEME']);
}
// $this->addExternalCss("/bitrix/css/main/bootstrap.css");
$this->addExternalCss("/bitrix/css/main/font-awesome.css");
?>
<div class="bx-filter <?=$templateData["TEMPLATE_CLASS"]?> bx-filter-horizontal">
	<div class="bx-filter-section container-fluid">
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<?endforeach;?>
			<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-2 small-filter-title">Быстрый подбор</div>
				<?
				//not prices
				foreach($arResult["NEW_ITEMS"] as $key=>$arItem)
				{
					if(
						empty($arItem["VALUES"])
						|| isset($arItem["PRICE"])
					)
						continue;

					if (
						$arItem["DISPLAY_TYPE"] == "A"
						&& (
							$arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0
						)
					)
						continue;
					?>
					<div class="col-xs-12 col-sm-4 col-md-2 bx-filter-parameters-box bx-active">
						<span class="bx-filter-container-modef"></span>
						<div class="bx-filter-block" data-role="bx_filter_block">
							<div class="row bx-filter-parameters-box-container">
							<?
							$arCur = current($arItem["VALUES"]);
							$checkedItemExist = false;
							?>
									<div class="col-xs-12">
										<div class="bx-filter-select-container">
											<div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
												<div class="bx-filter-select-text" data-role="currentOption">
													<?
													foreach ($arItem["VALUES"] as $val => $ar)
													{
														if ($ar["CHECKED"])
														{
															echo $ar["VALUE"];
															$checkedItemExist = true;
														}
													}
													if (!$checkedItemExist)
													{
														echo $arItem["NAME"];
													}
													?>
												</div>
												<div class="bx-filter-select-arrow"></div>
												<input
													style="display: none"
													type="radio"
													name="<?=$arCur["CONTROL_NAME_ALT"]?>"
													id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
													value=""
												/>
												<?foreach ($arItem["VALUES"] as $val => $ar):?>
													<input
														style="display: none"
														type="radio"
														name="<?=$ar["CONTROL_NAME_ALT"]?>"
														id="<?=$ar["CONTROL_ID"]?>"
														value="<? echo $ar["HTML_VALUE_ALT"] ?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
													/>
												<?endforeach?>
											</div>	
										<div class="bx-filter-select-popup" id='selecter_<?=CUtil::JSEscape($key)?>' data-role="dropdownContent" style="display: none;">
											<ul>
												<li>
													<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
														<? echo $arItem["NAME"]." (".GetMessage("CT_BCSF_FILTER_ALL").")"; ?>
													</label>
												</li>
											<?
											$sort = array();
											foreach($arItem["VALUES"] as $key=>$value){
												$sort[$key] = $value["VALUE"];
											}
											
											array_multisort($arItem["VALUES"], SORT_NUMERIC, $sort);
											unset($sort);

											foreach ($arItem["VALUES"] as $val => $ar):
												$class = "";
												if ($ar["CHECKED"])
													$class.= " selected";
												if ($ar["DISABLED"])
													$class.= " disabled";
											?>
												<li>
													<label for="<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
												</li>
											<?endforeach?>
											</ul>
										</div>

										</div>
									</div>
							</div>
							<div style="clear: both"></div>
						</div>
					</div>
				<?
				}
				?>
				<div class="col-xs-12 col-sm-4 col-md-2 bx-filter-button-box">
					<div class="bx-filter-block">
						<div class="bx-filter-parameters-box-container" style='white-space: nowrap;'>
							<input
								class="btn btn-primary"
								type="submit"
								id="set_filter"
								name="set_filter"
								value="Подобрать"
								style="display: inline-block;padding: 4px 25px;"
							/>
							<div class="bx-filter-popup-result" <?=($arParams["DISPLAY_ELEMENT_COUNT"]=="N" ? 'style="display:none;"':'style="display: inline-block;"')?>>
								<div class="bx-filter-popup-result" id="modef">
									<a href="<?echo $arResult["FILTER_URL"]?>" target=""><span id="modef_num"></span></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clb"></div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>

