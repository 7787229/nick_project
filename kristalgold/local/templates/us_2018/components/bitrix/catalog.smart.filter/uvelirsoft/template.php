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

$tmp_modFields = explode(",",COption::GetOptionString("uvelirsoft", "CATALOG_FILTER_FIELDS_MOD"));
// зачистим пробелы
foreach ($tmp_modFields as $arField) {
  $modFields[] = trim($arField);
}

$tmp_modFieldsNoTitle = explode(",",COption::GetOptionString("uvelirsoft", "CATALOG_FILTER_FIELDS_MOD_NOTITLE"));
// зачистим пробелы
foreach ($tmp_modFieldsNoTitle as $arField) {
  $modFieldsNoTitle[] = trim($arField);
}

//////////////////////////////////////////////////////////////////////////////
// применим отложенную функцию для помещения тегов в каталог

    $checkedItems = array();
    foreach ($arResult["ITEMS"] as $keyItem => $valueItem) {
        foreach ($valueItem['VALUES'] as $valueProp) {
            if($valueProp["CHECKED"]== 1){
                $checkedItems[] = array("NAME" => (in_array($valueItem['CODE'],$modFieldsNoTitle) ? $valueItem["NAME"]:$valueProp["VALUE"]), "ID" => $valueProp["CONTROL_ID"]);
            }
        }
    }

    if(count($checkedItems)>0){
        $this->SetViewTarget('filter_tags');
        ?>
        <!--
        <div class="row choise">
            <div class="col-md-12">
                <span id="set_filter"><?=GetMessage("Your choice")?></span> <a href="<?echo $arResult["SEF_DEL_FILTER_URL"]?>"><?=GetMessage("Reset")?></a>
            </div>
        </div>
        -->
        <div class="row filter_choise">
            <div class="col-md-12">
                <?
                    foreach ($checkedItems as $checkedItemsValue) {
                        ?>
                            <div class='filter-tag' id='tag_<?=$checkedItemsValue["ID"]?>'><?=$checkedItemsValue["NAME"]?> <i class="fa fa-close fa-lg" onclick="smartFilter.click($('#<?=$checkedItemsValue["ID"]?>')[0]);$('#<?=$checkedItemsValue["ID"]?>').attr('checked', false);$('#tag_<?=$checkedItemsValue["ID"]?>').remove();"></i></div>
                        <?
                    }
                ?>
                <div class='filter-tag'><a href="<?echo $arResult["SEF_DEL_FILTER_URL"]?>">(<?=GetMessage("Reset")?>)</a></div>
                <div id="tag_result_change"></div>
            </div>
        </div>
        <?
        $this->EndViewTarget();
    }
//////////////////////////////////////////////////////////////////////////////

require_once('Mobile_Detect.php');  // Подключаем скрипт Mobile_Detect.php
$detect = new Mobile_Detect; // Инициализируем копию класса

if ( $detect->isMobile() ) {
    $isMobile = true;
}

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/colors.css',
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME']
);



if (isset($templateData['TEMPLATE_THEME']))
{
	$this->addExternalCss($templateData['TEMPLATE_THEME']);
}
//$this->addExternalCss("/bitrix/css/main/bootstrap.css");
//$this->addExternalCss("/bitrix/css/main/font-awesome.css");
?>
<div class="show-smart-filter-block" onclick="FilterShow()" style="display: none;">
	<span id="smart-filter-block-opener" class="opener-close">Показать параметры отбора</span>
  <span id="smart-filter-block-opener-label">+</span>
</div>
<script>
	function FilterShow(){
		if(!$('#smart-filter-block-opener').hasClass('opener-close')){
			
			$('#smart-filter-block-opener').html("Показать параметры отбора");
			$('#smart-filter-block-opener').addClass('opener-close');
			$('#smart-filter-block-opener-label').html("+");
		}
		else{
			$('#smart-filter-block-opener').html("Скрыть параметры отбора");
			$('#smart-filter-block-opener').removeClass('opener-close');
			$('#smart-filter-block-opener-label').html("-");
		}
		$('.bx-filter').slideToggle();
	}
</script>
<div class="bx-filter <?=$templateData["TEMPLATE_CLASS"]?> <?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL") echo "bx-filter-horizontal"?>">
	<div class="bx-filter-section container-fluid">
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<?endforeach;?>
			<div class="row">
				<?
					// echo "<pre>";
					// 	var_dump($arResult["ITEMS"]);
					// echo "</pre>";
				?>
				<?






				//not prices
				foreach($arResult["ITEMS"] as $key=>$arItem)
				{


					if(is_array($arItem["VALUES"])){
						//------------------ СОРТИРОВКА -----------------------
						   $sort = array();
						   foreach ($arItem['VALUES'] as $arOneValue)
						   {
                                $arOneValue['VALUE'] = trim($arOneValue['VALUE']);
                                $arOneValue['VALUE'] = str_replace(chr(0xC2).chr(0xA0), '', $arOneValue['VALUE']);
		                        $sort[] = mb_strtolower(trim($arOneValue['VALUE']));
						   }
						   array_multisort($arItem['VALUES'],SORT_STRING,$sort);
						   //-----------------------------------------------------
					}

					/*
					проверяем блок фильтров на пустоту
					*/
					$disabledFlag = true; //свойств для вывода в данном блоке нет
					foreach($arItem['VALUES'] as $keyVal => $valVal){
						if(!$valVal['DISABLED']){
							$disabledFlag = false;
							break;
						}
					}

                                    //printvar("dd",$arItem);


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

                                        <?

                                        // отдельная верстка для мобильных платформ

                                        if($isMobile){
                                            ?>
                                                <style>
                                                    .bx-filter-parameters-box {
                                                        border:none!important;
                                                    }
                                                </style>
                <div class="section-filter <?=$arItem['CODE']?>  <?=($arItem['NAME'] =='Вставки' ? 'vstavki' : '')?> <?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?else:?>col-lg-12<?endif?> bx-filter-parameters-box <?=($disabledFlag ? 'disabled' : '');?> <?if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>bx-active<?endif?>">

                                            <?
                                        }else{
                                            ?>
                                                <div class="section-filter <?=$arItem['CODE']?>  <?=($arItem['NAME'] =='Вставки' ? 'vstavki' : '')?> <?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?else:?>col-lg-12<?endif?> bx-filter-parameters-box <?=($disabledFlag ? 'disabled' : '');?> <?if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>bx-active<?endif?>">
                                            <?
                                        }
                                        ?>



						<span class="bx-filter-container-modef"></span>
                                                    <div class="prop-title">
                                                        <?
                                                            if(!in_array($arItem['CODE'],$modFieldsNoTitle)){
                                                            ?>
                                                                <?=$arItem["NAME"]?>
                                                            <?
                                                            }
                                                        ?>

                                                    </div>
						<div class="">
							<div class="bx-filter-parameters-box-container">
							<?

                                                        $countElementsFilter = COption::GetOptionString("uvelirsoft", "CATALOG_FILTER_VISIBLE_ELEMENTS_COUNT","5");


							$arCur = current($arItem["VALUES"]);

							if(in_array($arItem['CODE'],$modFields)){
								$arItem["DISPLAY_TYPE"] = "M";
							}



							// если на мобильном, делаем все элементы выпадающим списком
							if($isMobile && !in_array($arItem['CODE'],$modFieldsNoTitle)){
								//$arItem["DISPLAY_TYPE"] = "P";
							}





							switch ($arItem["DISPLAY_TYPE"])
							{
                                                                case "M":
                                                                        // модифицированный вывод
                                                                        ?>
                                                                            <div class="filter-option modified">
                                                                        <?
                                                                        foreach($arItem["VALUES"] as $val => $ar):?>

                                                                                <p class='<?=strtolower($arItem['CODE'])?><? echo $ar["DISABLED"] ? ' disabled': '' ?>'>
                                                                                    <input type="checkbox" name="<? echo $ar["CONTROL_NAME"] ?>" value="<? echo $ar["HTML_VALUE"] ?>" id="<? echo $ar["CONTROL_ID"] ?>" <? echo $ar["CHECKED"]? 'checked="checked"': '' ?> onclick="smartFilter.click(this)" />
                                                                                    <label  data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <? echo $ar["DISABLED"] ? 'disabled': '' ?>" for="<? echo $ar["CONTROL_ID"] ?>"><?=$ar["VALUE"];?></label>
                                                                                </p>
									<?endforeach;?>
                                                                            </div>
                                                                        <?
                                                                    break;
                                                                case "A"://NUMBERS_WITH_SLIDER
									?>
									<div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
										<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_FROM")?></i>
										<div class="bx-filter-input-container">
											<input
												class="min-price"
												type="text"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
											/>
										</div>
									</div>
									<div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
										<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_TO")?></i>
										<div class="bx-filter-input-container">
											<input
												class="max-price"
												type="text"
												name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
											/>
										</div>
									</div>

									<div class="col-xs-10 col-xs-offset-1 bx-ui-slider-track-container">
										<div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
											<?
											$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
											$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
											$value1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
											$value2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
											$value3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
											$value4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
											$value5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
											?>
											<div class="bx-ui-slider-part p1"><span><?=$value1?></span></div>
											<div class="bx-ui-slider-part p2"><span><?=$value2?></span></div>
											<div class="bx-ui-slider-part p3"><span><?=$value3?></span></div>
											<div class="bx-ui-slider-part p4"><span><?=$value4?></span></div>
											<div class="bx-ui-slider-part p5"><span><?=$value5?></span></div>

											<div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
											<div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
											<div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
											<div class="bx-ui-slider-range" 	id="drag_tracker_<?=$key?>"  style="left: 0;right: 0;">
												<a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
												<a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
											</div>
										</div>
									</div>
									<?
									$arJsParams = array(
										"leftSlider" => 'left_slider_'.$key,
										"rightSlider" => 'right_slider_'.$key,
										"tracker" => "drag_tracker_".$key,
										"trackerWrap" => "drag_track_".$key,
										"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
										"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
										"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
										"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
										"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
										"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
										"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
										"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
										"precision" => $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0,
										"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
										"colorAvailableActive" => 'colorAvailableActive_'.$key,
										"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
									);
									?>
									<script type="text/javascript">
										BX.ready(function(){
											window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
										});
									</script>
									<?
									break;
								case "B"://NUMBERS
									?>
									<div class="col-xs-6 bx-filter-parameters-box-container-block bx-left">
										<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_FROM")?></i>
										<div class="bx-filter-input-container">
											<input
												class="min-price"
												type="text"
												name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
												/>
										</div>
									</div>
									<div class="col-xs-6 bx-filter-parameters-box-container-block bx-right">
										<i class="bx-ft-sub"><?=GetMessage("CT_BCSF_FILTER_TO")?></i>
										<div class="bx-filter-input-container">
											<input
												class="max-price"
												type="text"
												name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
												id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
												value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
												size="5"
												onkeyup="smartFilter.keyup(this)"
												/>
										</div>
									</div>
									<?
									break;
								case "G"://CHECKBOXES_WITH_PICTURES
									?>
									<div class="bx-filter-param-btn-inline">
									<?foreach ($arItem["VALUES"] as $val => $ar):?>
										<input
											style="display: none"
											type="checkbox"
											name="<?=$ar["CONTROL_NAME"]?>"
											id="<?=$ar["CONTROL_ID"]?>"
											value="<?=$ar["HTML_VALUE"]?>"
											<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
										/>
										<?
										$class = "";
										if ($ar["CHECKED"])
											$class.= " bx-active";
										if ($ar["DISABLED"])
											$class.= " disabled";
										?>
										<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
											<span class="bx-filter-param-btn bx-color-sl">
												<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
												<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
												<?endif?>
											</span>
										</label>
									<?endforeach?>
									</div>
									<?
									break;
								case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
									?>
									<div class="bx-filter-param-btn-block">
									<?foreach ($arItem["VALUES"] as $val => $ar):?>
										<input
											style="display: none"
											type="checkbox"
											name="<?=$ar["CONTROL_NAME"]?>"
											id="<?=$ar["CONTROL_ID"]?>"
											value="<?=$ar["HTML_VALUE"]?>"
											<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
										/>
										<?
										$class = "";
										if ($ar["CHECKED"])
											$class.= " bx-active";
										if ($ar["DISABLED"])
											$class.= " disabled";
										?>
										<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'bx-active');">
											<span class="bx-filter-param-btn bx-color-sl">
												<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
													<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
												<?endif?>
											</span>
											<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
											if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
												?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
											endif;?></span>
										</label>
									<?endforeach?>
									</div>
									<?
									break;
								case "P"://DROPDOWN
									$checkedItemExist = false;
									?>
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
													echo GetMessage("CT_BCSF_FILTER_ALL");
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
											<div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none;">
												<ul>
													<li>
														<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
															<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
														</label>
													</li>
												<?
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
									<?
									break;
								case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
									?>
									<div class="bx-filter-select-container">
										<div class="bx-filter-select-block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
											<div class="bx-filter-select-text fix" data-role="currentOption">
												<?
												$checkedItemExist = false;
												foreach ($arItem["VALUES"] as $val => $ar):
													if ($ar["CHECKED"])
													{
													?>
														<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
															<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
														<?endif?>
														<span class="bx-filter-param-text">
															<?=$ar["VALUE"]?>
														</span>
													<?
														$checkedItemExist = true;
													}
												endforeach;
												if (!$checkedItemExist)
												{
													?><span class="bx-filter-btn-color-icon all"></span> <?
													echo GetMessage("CT_BCSF_FILTER_ALL");
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
													value="<?=$ar["HTML_VALUE_ALT"]?>"
													<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
												/>
											<?endforeach?>
											<div class="bx-filter-select-popup" data-role="dropdownContent" style="display: none">
												<ul>
													<li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
														<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx-filter-param-label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
															<span class="bx-filter-btn-color-icon all"></span>
															<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
														</label>
													</li>
												<?
												foreach ($arItem["VALUES"] as $val => $ar):
													$class = "";
													if ($ar["CHECKED"])
														$class.= " selected";
													if ($ar["DISABLED"])
														$class.= " disabled";
												?>
													<li>
														<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
															<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
																<span class="bx-filter-btn-color-icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
															<?endif?>
															<span class="bx-filter-param-text">
																<?=$ar["VALUE"]?>
															</span>
														</label>
													</li>
												<?endforeach?>
												</ul>
											</div>
										</div>
									</div>
									<?
									break;
								case "K"://RADIO_BUTTONS
									?>
									<div class="radio">
										<label class="bx-filter-param-label" for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
											<span class="bx-filter-input-checkbox">
												<input
													type="radio"
													value=""
													name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
													id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
													onclick="smartFilter.click(this)"
												/>
												<span class="bx-filter-param-text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
											</span>
										</label>
									</div>
									<?foreach($arItem["VALUES"] as $val => $ar):?>
										<div class="radio">
											<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label" for="<? echo $ar["CONTROL_ID"] ?>">
												<span class="radio-btn bx-filter-input-checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>">
													<input
														type="radio"
														value="<? echo $ar["HTML_VALUE_ALT"] ?>"
														name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
														id="<? echo $ar["CONTROL_ID"] ?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
														onclick="smartFilter.click(this)"
													/>
													<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>"><?=$ar["VALUE"];?><?
													if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
														?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
													endif;?></span>
												</span>
											</label>
										</div>
									<?endforeach;?>
									<?
									break;
								case "U"://CALENDAR
									?>
									<div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
										<?$APPLICATION->IncludeComponent(
											'bitrix:main.calendar',
											'',
											array(
												'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
												'SHOW_INPUT' => 'Y',
												'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MIN"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
												'INPUT_NAME' => $arItem["VALUES"]["MIN"]["CONTROL_NAME"],
												'INPUT_VALUE' => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
												'SHOW_TIME' => 'N',
												'HIDE_TIMEBAR' => 'Y',
											),
											null,
											array('HIDE_ICONS' => 'Y')
										);?>
									</div></div>
									<div class="bx-filter-parameters-box-container-block"><div class="bx-filter-input-container bx-filter-calendar-container">
										<?$APPLICATION->IncludeComponent(
											'bitrix:main.calendar',
											'',
											array(
												'FORM_NAME' => $arResult["FILTER_NAME"]."_form",
												'SHOW_INPUT' => 'Y',
												'INPUT_ADDITIONAL_ATTR' => 'class="calendar" placeholder="'.FormatDate("SHORT", $arItem["VALUES"]["MAX"]["VALUE"]).'" onkeyup="smartFilter.keyup(this)" onchange="smartFilter.keyup(this)"',
												'INPUT_NAME' => $arItem["VALUES"]["MAX"]["CONTROL_NAME"],
												'INPUT_VALUE' => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
												'SHOW_TIME' => 'N',
												'HIDE_TIMEBAR' => 'Y',
											),
											null,
											array('HIDE_ICONS' => 'Y')
										);?>
									</div></div>

									<?
									break;
								default://CHECKBOXES
									?>
									<?

									$i = 0;
									$hideArea = false;


									foreach($arItem["VALUES"] as $val => $ar):?>
										<div class="checkbox<?=($hideArea==true ? ' prop_'.$ar['CONTROL_NAME_ALT']:'')?>"<?=($hideArea==true ? ' style="display:none;"':'')?>>
											<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx-filter-param-label <? echo $ar["DISABLED"] ? 'disabled': '' ?>" for="<? echo $ar["CONTROL_ID"] ?>">
												<span class="bx-filter-input-checkbox">
                                                    <input name="filter_url" type="hidden" value="<?=$arResult["FILTER_URL"]?>">
                                                    <input name="name_group_filter" type="hidden" value="<?=$arItem['CODE']?>">
                                                    <input name="name_filter" type="hidden" value="<?=$ar['URL_ID']?>">
													<input class="click_filter"
														type="checkbox"
														value="<? echo $ar["HTML_VALUE"] ?>"
														name="<? echo $ar["CONTROL_NAME"] ?>"
														id="<? echo $ar["CONTROL_ID"] ?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
														onclick="smartFilter.click(this)"
													/>

													<span class="bx-filter-param-text" title="<?=$ar["VALUE"];?>">
														<?=(in_array($arItem['CODE'],$modFieldsNoTitle) ? $arItem["NAME"]:$ar["VALUE"]);?><?
														if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($ar["ELEMENT_COUNT"])):
																?> (<span data-role="count_<?=$ar["CONTROL_ID"]?>"><? echo $ar["ELEMENT_COUNT"]; ?></span>)<?
														endif;?>
													</span>
												</span>
											</label>
										</div>

										<?
										/*$i++;
										if($countElementsFilter == $i and count($arItem["VALUES"])>$countElementsFilter) {
											$hideArea = true;
											?>
												<a href="javascript:void(0)" onclick="$('.prop_<? echo $ar["CONTROL_NAME_ALT"] ?>').css('display','block');$(this).remove();"><?=GetMessage("More...")?></a>
											<?
										}*/
										?>

									<?endforeach;?>

                                <?
                                if($arItem["NAME"] =="Вставки"  ){


                                ?>

                                <script>


                                    var filter_url =$(document).find('[name = filter_url]').eq(0).val();

                                    function simpleFilter(blockClassElement,heading, newClassElement, newClassFilter, filterUrl){

                                        var checkboxes =$(document.getElementsByClassName(blockClassElement)).find('.checkbox');
                                        var contextElements = [];
                                        for(var i=0; i<checkboxes.length; i++){
                                            var txt= $(checkboxes[i]).find('.bx-filter-param-text').text();
                                           // console.log(txt);
                                            if (txt.search(heading)!=-1) {
                                                contextElements[i]=checkboxes[i];
                                                $(contextElements[i]).css('display','none');
                                                $(contextElements[i]).addClass("oldFilter");
                                            }

                                        }
                                        var newElement = $(checkboxes[1]).clone();
                                        newElement.addClass(newClassElement).addClass("newFilter").css("display","block");
                                        if(filter_url.search(filterUrl)!==-1){
                                            $(newElement).find(".click_filter").attr("checked","checked");
                                        }
                                        newElement.appendTo("."+blockClassElement+" .bx-filter-parameters-box-container");


                                        $(newElement).find(".click_filter").click(function (e) {
                                            $(this).attr("checked","checked");
                                            for (var i=0; i<$(contextElements).length;i++){
                                                $(contextElements[i]).find(".click_filter").click();
                                            }

                                        }).attr("id",newClassFilter).attr("name",newClassFilter);

                                        $(newElement).find(".bx-filter-param-text").text(heading).attr("title",heading);
                                        $(newElement).find("label").attr("for",newClassFilter);
                                        $(newElement).find("label").attr("data-role",newClassFilter);
                                    }

                                    simpleFilter("vstavki","Изумруд","newIzumrud","newIzumrudFilter","izumrud");
                                    simpleFilter("vstavki","Фенакит","newFenakit","newFenakitFilter","fenakit");
                                    simpleFilter("vstavki","Агат","newAgat","newAgatFilter","agat");
                                    simpleFilter("vstavki","Аквамарин","newakvamarin","newakvamarinFilter","akvamarin");
                                    simpleFilter("Александрит","newaleksandrit","newaleksandritFilter","aleksandrit");
                                    simpleFilter("vstavki","Бирюза","newbiryuza","newbiryuzaFilter","biryuza");
                                    simpleFilter("vstavki","Гранат","newgranat","newgranatFilter","granat");
                                    simpleFilter("vstavki","Жемчуг","newzhemchug","newzhemchugFilter","zhemchug");
                                    simpleFilter("vstavki","Кварц","newkvarc","newkvarcFilter","kvarc");
                                    simpleFilter("vstavki","Коралл","newkorall","newkorallFilter","korall");
                                    simpleFilter("vstavki","Лазурит","newlazurit","newlazuritFilter","lazurit");
                                    simpleFilter("vstavki","Сапфир","newsapfir","newsapfirFilter","sapfir");
                                    simpleFilter("vstavki","Топаз","newtopaz","newtopazFilter","topaz");
                                    simpleFilter("vstavki","Флогопит","newflogopit","newflogopitFilter","flogopit");
                                    simpleFilter("vstavki","Хризолит","newxrizolit","newxrizolitFilter","xrizolit");

                                    simpleFilter("TIPIZDELIYA","Берилл","newBeril","newBerilFilter","berill");
                                    simpleFilter("TIPIZDELIYA","Браслет","newBraslet","newBrasletFilter","braslet");
                                    simpleFilter("TIPIZDELIYA","Кольцо","newKoltso","newKoltsoFilter","kolco");
                                    simpleFilter("TIPIZDELIYA","Серьги","newserdi","newSergiFilter","sergi");
                                    simpleFilter("TIPIZDELIYA","Сотуар","newSotuar","newSotuarFilter","sotuar");
                                    simpleFilter("TIPIZDELIYA","Четки","newChetki","newChetkiFilter","chetki");


                                    $(".bx-filter-section.container-fluid").append('<div class="wr-show-allfilters"><button id="showAllFilters">Расширенный фильтр</button></div>');

                                    $("#showAllFilters").click(function () {
                                        $(".newFilter").hide();
                                        $(".oldFilter").show();

                                        $(".wr-show-allfilters").hide();

                                        $(".section-filter.OSNOVNAYA_VSTAVKA").show();
                                        $(".section-filter.PROBA").show();
                                        $(".section-filter.KOLLEKTSIYA_DLYA_SAYTA").show();
                                        $(".section-filter.RAZMER").show();


                                    });
                                </script>
                                <?}?>

							<?
							}
							?>
							</div>
							<div style="clear: both"></div>
						</div>
					</div>
				<?
				if($arItem['CODE'] == "TIPIZDELIYA"){
					foreach($arResult["ITEMS"] as $key=>$arItem)//prices
					{
						$key = $arItem["ENCODED_ID"];
						if(isset($arItem["PRICE"])):
							if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
								continue;

							$precision = 2;
							if (Bitrix\Main\Loader::includeModule("currency"))
							{
								$res = CCurrencyLang::GetFormatDescription($arItem["VALUES"]["MIN"]["CURRENCY"]);
								$precision = $res['DECIMALS'];
							}

                            if($isMobile){
                                ?>
                                    <style>
                                        .bx-filter-parameters-box {
                                            border:none!important;
                                        }
                                    </style>
                                    <div class="col-xs-6 col-sm-12 col-md-12 col-lg-12 bx-filter-parameters-box <?=($disabledFlag ? 'disabled' : '');?> bx-active">
                                <?
                            }else{
                                ?>
                                    <div class="<?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?else:?>col-lg-12<?endif?> bx-filter-parameters-box bx-active">
                                <?
                            }
							?>
							<?/*<div class="<?if ($arParams["FILTER_VIEW_MODE"] == "HORIZONTAL"):?>col-sm-6 col-md-4<?else:?>col-lg-12<?endif?> bx-filter-parameters-box bx-active">*/?>
								<span class="bx-filter-container-modef"></span>
															<div class="prop-title"><?=$arItem["NAME"]?></div>
								<div class="bx-filter-block" data-role="bx_filter_block">
									<div class="row bx-filter-parameters-box-container">
										<div class="input_prices">
											<div class=" bx-filter-parameters-box-container-block bx-left">
												<div class="bx-filter-input-container">
													<input
														class="min-price"
														type="text"
														name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
														id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
														value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
														size="5"
														onkeyup="smartFilter.keyup(this)"
																										placeholder="<?=GetMessage("CT_BCSF_FILTER_FROM")?>"
													/>
												</div>
											</div> <span class="defis">-</span>
											<div class=" bx-filter-parameters-box-container-block bx-right">
												<div class="bx-filter-input-container">
													<input
														class="max-price"
														type="text"
														name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
														id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
														value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
														size="5"
														onkeyup="smartFilter.keyup(this)"
																										placeholder="<?=GetMessage("CT_BCSF_FILTER_TO")?>   "
													/>
												</div>
											</div>
											<span class="currency"><?=substr($arItem['CURRENCIES'][$arItem["VALUES"]["MIN"]['CURRENCY']], 0, -2);?>.</span>
										</div>

										<div class="col-xs-12 bx-ui-slider-track-container">
											<div class="bx-ui-slider-track" id="drag_track_<?=$key?>">
												<?
												$precision = $arItem["DECIMALS"]? $arItem["DECIMALS"]: 0;
												$step = ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"]) / 4;
												$price1 = number_format($arItem["VALUES"]["MIN"]["VALUE"], $precision, ".", "");
												$price2 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step, $precision, ".", "");
												$price3 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 2, $precision, ".", "");
												$price4 = number_format($arItem["VALUES"]["MIN"]["VALUE"] + $step * 3, $precision, ".", "");
												$price5 = number_format($arItem["VALUES"]["MAX"]["VALUE"], $precision, ".", "");
												?>
												<div class="bx-ui-slider-part p1"><span><?=$price1?></span></div>
												<!-- <div class="bx-ui-slider-part p2"><span><?=$price2?></span></div> -->
												<div class="bx-ui-slider-part p3"><span><?=$price3?></span></div>
												<!-- <div class="bx-ui-slider-part p4"><span><?=$price4?></span></div> -->
												<div class="bx-ui-slider-part p5"><span><?=$price5?></span></div>

												<div class="bx-ui-slider-pricebar-vd" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
												<div class="bx-ui-slider-pricebar-vn" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
												<div class="bx-ui-slider-pricebar-v"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>

																							<a class="bx-ui-slider-handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
												<a class="bx-ui-slider-handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>


																							<div class="bx-ui-slider-range" id="drag_tracker_<?=$key?>"  style="left: 0%; right: 0%;">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?
							$arJsParams = array(
								"leftSlider" => 'left_slider_'.$key,
								"rightSlider" => 'right_slider_'.$key,
								"tracker" => "drag_tracker_".$key,
								"trackerWrap" => "drag_track_".$key,
								"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
								"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
								"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
								"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
								"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
								"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
								"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
								"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
								"precision" => $precision,
								"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
								"colorAvailableActive" => 'colorAvailableActive_'.$key,
								"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
							);
							?>
							<script type="text/javascript">
								BX.ready(function(){
									window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
								});
							</script>
						<?endif;
					}
				}
				}
				?>
			</div><!--//row-->
			<div class="row">
				<div class="col-xs-12 bx-filter-button-box">
					<div class="bx-filter-block">
						<div class="bx-filter-parameters-box-container">
							<input
								class="btn btn-themes"
								type="submit"
								id="set_filter"
								name="set_filter"
								value="<?=GetMessage("CT_BCSF_SET_FILTER")?>"
							/>
							<input
								class="btn btn-link"
								type="submit"
								id="del_filter"
								name="del_filter"
								value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>"
							/>
							<div class="bx-filter-popup-result <?if ($arParams["FILTER_VIEW_MODE"] == "VERTICAL") echo $arParams["POPUP_POSITION"]?>" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
								<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
								<span class="arrow"></span>
								<br/>
								<a href="<?echo $arResult["FILTER_URL"]?>" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clb"></div>
		</form>
        <div class="wr-apply-filter" style="display: none;">
            <button>Отфильтровать</button>
        </div>
	</div>
</div>

<script>

    $('form.smartfilter').click(function(){


                $('.wr-apply-filter').css('display','block');


    });


        $('.wr-apply-filter button').click(function () {

            if($('#modef a').attr('href')!=="<?=$arResult['FORM_ACTION']?>"){
                location=$('#modef a').attr('href');
            }
        });



</script>

<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
</script>
