<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

/*

ОГРАНИЧЕНИЯ

1) Компонент работает только с настраиваемыми способами доставки
2) При оформлении заказа не производится выбор и создание оплаты
3) Список магазинов в инфоблоке, минимальный набор полей(свойств инфоблока): NAME, LAT, LON
4) Обязательные свойства заказа: NAME, PHONE, COMMENT, ADDRESS, MAIL
5) При создании заказа проверяется пользователь, если авторизован - заказ на пользователя,
если не авторизован - производится поиск по email в порядке сортировки ID по DESC первому встречному с указанным емейлом создатся заказ (без авторизации),
если пользователь не найден по email - пользователь создается, у пользователя заполняется свойство UF_PERSONAL_INFO (ФЗ-152)

*/
?>
<div id='order_make_container'>
	<?
	if($_POST['AJAX_UPDATE'] == 'Y'){
		$APPLICATION->RestartBuffer();
	}
	//printvar('$_POST',$_POST);
	//printvar('', $arResult['PAYSYSTEMS'][$arResult['DEFAULT']['PAYSYSTEM']]);
	//printvar('', $arResult['DELIVERIES'][$arResult['DEFAULT']['DELIVERY']]);
	?>
		<?
		if(count($arResult["BASKET"]) == 0){
			?>
				<h2>Ваша корзина пуста!</h2>
			<?
		}else{

		$sComponentFolder = $this->__component->__path;

		//$this->addExternalJs($templateFolder.'/script.js');
		// $this->addExternalJS($sComponentFolder."/maskinput/jquery.maskedinput.min.js"); // https://itchief.ru/lessons/javascript/input-mask-for-html-input-element
		// $this->addExternalJS("https://api-maps.yandex.ru/2.1/?lang=ru_RU"); // https://tech.yandex.ru/maps/jsbox/2.1/mapbasics

		CJSCore::Init(array('currency'));
		$currencyFormat = CCurrencyLang::GetFormatDescription($arResult['CURRENCY']);
		// printvar('$currencyFormat',$currencyFormat);

		$requaredTemplate = " <span style='color:#f9c7bd;font-size:14px;'>*</span>";

		?>
		<div id='order-make-block-outer'>
		<form id='order-make-simple' action='' method='POST'>
			<input type='hidden' name="CREATE_ORDER" value="AJAX">
			<input type='hidden' id="DELIVERY_PRICE" name="DELIVERY_PRICE" value="">
			<div class="row">
				<div class='col-md-15 col-sm-15 col-xs-30 order-form-block'>
					<div class="row">
					<?
					// формируем персональные данные
					foreach ($arResult["ORDER_PROPS"]  as $propID => $arProp){

						if(!in_array($propID,$arParams["ORDER_PROPS"]) or $arProp["PROPS_GROUP_ID"] <> $arParams["PERSONAL_PROPS_GROUP"]) continue;

						$arProp["VALUE"] = (!empty($_POST[$arProp["CODE"]]) ? $_POST[$arProp["CODE"]] : $arProp["VALUE"]);
						
						switch ($arProp["TYPE"]) {
							case "TEXT":
								?>
									<div class='col-md-10 col-sm-30 order-form-block'>
										<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? $requaredTemplate:"")?>
									</div>
									<div class='col-md-20 col-sm-30 order-form-block'>
										<input 
											data-requared='<?=$arProp["REQUIED"]?>' 
											class="form-control" 
											type='text' 
											id="PROP-<?=$arProp["CODE"]?>" 
											name="<?=$arProp["CODE"]?>" 
											value="<?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?>"
										>
									</div>
								<?
								break;
							case "TEXTAREA":
								?>
									<div class='col-md-10 col-sm-30 order-form-block'>
										<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? $requaredTemplate:"")?>
									</div>
									<div class='col-md-20 col-sm-30 order-form-block'>
										<textarea 
											data-requared='<?=$arProp["REQUIED"]?>' 
											class="form-control" 
											id="PROP-<?=$arProp["CODE"]?>" 
											name="<?=$arProp["CODE"]?>" 
											rows="<?=($arProp["SIZE2"] ? $arProp["SIZE2"]:3)?>"
										><?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?></textarea>
									</div>
								<?
								break;

							default:
								break;
						}
					}
					?>
					</div>
					<div class="row">
						<?
						/* оплата */
						$default_pay=0;
						?>
						<div class='col-md-30 col-sm-30 order-form-block'>
							<ul class='order-payment-select' ><?
							// оплата на сайте
							foreach($arResult["PAYSYSTEMS"] as $paySystem){
								if($paySystem['PAY_ONLINE'] == 'Y'){
									$paySystem['NAME'] .= ' (+'.$arResult["DISCOUNT"]["DISCOUNT_NAME"].')';
								}
								?>
								<li data-payment='<?=$paySystem["ID"]?>' class='order-payment-select-outer<?=($paySystem['ID'] == $arResult['DEFAULT']['PAYSYSTEM'] ? ' is_selected' : '')?>' onclick='ChangeDeliveryOrPayment(this)'><?=$paySystem['NAME']?></li>
								<?
							}
							?>
							</ul>
							<span class="order-promo-mess" id="mess_coupon"></span>
							<input type='hidden' name='PAYMENT_ID' id='paymentSelector' value='<?=$arResult['DEFAULT']['PAYSYSTEM']?>'>

						</div>
					</div>
					<? /* доставка */ ?>
					<div class="row">
						<div class='col-md-30 col-sm-30 order-form-block'>
							<?=$arParams["DELIVERY_TITLE"]?>
						</div>
						<div class='col-md-30 col-sm-30 order-form-block deliveries'>
							<div class='row'>
							<?foreach($arResult["PAYSYSTEMS"][$arResult['DEFAULT']['PAYSYSTEM']]['DELIVERY'] as $delivery){?>
								<div class='radio-container col-md-15'>
									<input<?=($delivery == $arResult['DEFAULT']['DELIVERY'] ? ' checked' : '')?> id='DELIVERY_TYPE_<?=$delivery?>' type='radio' name='DELIVERY' value='<?=$delivery?>' onchange="ChangeDeliveryOrPayment(this)">
									<label for='DELIVERY_TYPE_<?=$delivery?>'><?=$arResult["DELIVERIES"][$delivery]["NAME"]?></label>
								</div>								
							<?}?>
							</div>
						</div>
						<div id='select-shop-block'<?=($arResult['DEFAULT']['DELIVERY'] != $arParams['DELIVERY_SHOP_TYPE'] ? "style='display: none;'" : "")?>>
							<div class='col-md-20 col-sm-20 col-xs-30 order-form-block'>
								<select name='SHOP' class="form-control" id='select-shop'>
									<?
									// выведем список магазинов из $arResult["SHOPS"]
									if(is_array($arResult["SHOPS"])){
										foreach ($arResult["SHOP_TEMPLATE_SELECT"] as $idShop => $nameShop){
											?>
												<option value="<?=$idShop?>"><?=$nameShop?></option>
											<?
										}
									}
									?>
								</select>
							</div>
							<div class='col-md-10 col-sm-10 col-xs-30 order-form-block'>
								<input id='mapOrderShopSelectButton'  type='button' value='На карте' class="btn btn-default">
							</div>
							<div id="mapOrderShopSelect" class="modal">
								<div class="modal-dialog">
								  <div class="modal-content">
									  <span class="close_form"><i class="fa fa-times" aria-hidden="true"></i></span>
										<div class="modal-body">
											<div class='row'>
												<div class="col-md-15 col-sm-15 col-xs-30" id='shopMapBlock'>

												</div>
												<div class="col-md-15 col-sm-15 col-xs-30">
													<a href="javascript:void(0)" id="show-all-points">все показать на карте</a>
													<?
													// выведем список магазинов
													foreach ($arResult["SHOP_TEMPLATE_LIST"] as $idShop => $nameShop){
														?><div class="map-shop-list text-left" data-shop="<?=$idShop?>">
															<a href="javascript:void(0)" data-shop="<?=$idShop?>" class="shop-selector">выбрать</a>
															<?=htmlspecialchars_decode($nameShop)?>
														</div><?
													}
													?>
												</div>
											</div>
										</div>
								  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
					<?
					// формируем данные по доставке
					foreach ($arResult["ORDER_PROPS"]  as $propID => $arProp){

						if(!in_array($propID,$arParams["ORDER_PROPS"]) or $arProp["PROPS_GROUP_ID"] <> $arParams["ADDRESS_PROPS_GROUP"]) continue;

						$arProp["VALUE"] = (!empty($_POST[$arProp["CODE"]]) ? $_POST[$arProp["CODE"]] : $arProp["VALUE"]);
						
						switch ($arProp["TYPE"]) {
							case "TEXT":
								?>
									<div class='col-md-30 col-sm-30 order-form-block'>
										<input data-requared='<?=$arProp["REQUIED"]?>'  class="form-control" type='text' id="PROP-<?=$arProp["CODE"]?>" name="<?=$arProp["CODE"]?>" value="<?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?>" placeholder='<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? " *":"")?>'>
									</div>
								<?
								break;
							case "TEXTAREA":
								?>
									<div class='col-md-30 col-sm-30 order-form-block'>
										<textarea data-requared='<?=$arProp["REQUIED"]?>'  class="form-control" id="PROP-<?=$arProp["CODE"]?>" name="<?=$arProp["CODE"]?>" rows="<?=($arProp["SIZE2"] ? $arProp["SIZE2"]:3)?>" placeholder='<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? " *":"").($arProp["CODE"]=="ADDRESS" ? " - 123456 Уфа ул.Революционная дом 39 кв. 4":"")?>'><?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?></textarea>
									</div>
								<?
								break;

							default:
								break;
						}
					}
					?>
					</div>
				</div>
				<div class='col-md-15 col-sm-15 col-xs-30 order-form-block'>
					<div class='price-block-outer'>
						<table class="price-block">
							<tr>
								<td class='text-left'>
									Товаров на:
								</td>
								<td class='text-right' id="order-price">
									<?=$arResult["PRICE_FORMATED"]?>
								</td>
							</tr>
							<tr id="order_discount"<?=($arResult["DISCOUNT_PRICE"] <= 0 ? ' style="display:none"' : '')?>>
								<td class='text-left'>
									Скидка:
								</td>
								<td class='text-right discount' id="order-discount-price">
									<?=$arResult["DISCOUNT_PRICE_FORMATED"]?>
								</td>
							</tr>
							<tr>
								<td class='text-left'>
									Доставка:
								</td>
								<td class='text-right' id="delivery-price">
									<?=($arResult['DELIVERIES'][$arResult['DEFAULT']['DELIVERY']]['PRICE'] == 0 
										? "<span class='freepay'>бесплатно</span>" 
										: CurrencyFormat($arResult['DELIVERIES'][$arResult['DEFAULT']['DELIVERY']]['PRICE'], $arResult['DELIVERIES'][$arResult['DEFAULT']['DELIVERY']]['CURRENCY']))?>
								</td>
							</tr>
							<tr>
								<td class='text-left'>
									Итого:
								</td>
								<td class='text-right' id="order-summa-price">
									<?=$arResult['PRICE_WITH_DISCOUNT_FORMATED']?>
								</td>
							</tr>
						</table>
						<table class='price-block-control'>
							<tr>
								<td>
									<input type='button' id='order-make-simple-submit' class="btn btn-primary" value='<?=$arParams['BUTTON_TEXT']?>'>
									<span id='order-message'></span>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<?if ($arParams['USER_CONSENT'] == 'Y'):?>
				 <?$APPLICATION->IncludeComponent(
				  "bitrix:main.userconsent.request",
				  "",
				  array(
					  "ID" => $arParams["USER_CONSENT_ID"],
					  "IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
					  "AUTO_SAVE" => "Y",
					  "IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
					  'SUBMIT_EVENT_NAME' => 'my-event-name',
					  "REPLACE" => array(
					   'button_caption' => $arParams['BUTTON_TEXT'],
					   'fields' => array('Email', 'Телефон', 'Имя', 'Адрес')
					  ),
				  )
				 );?>
			<?endif;?>
			<div id="basket_content_table">
				<?
				// проверим, был ли аджакс запрос на перезагрузку списка 
				$reloadBasket = isset($_GET['AJAX']) && $_GET['AJAX']=="Y" && isset($_GET['mode']) && $_GET['mode']=='reloadBasketItems';
				if ($reloadBasket) {
					$APPLICATION->RestartBuffer();
					header("Content-type: text/plain; charset=utf-8");
				}
				?>
				<table class='basket-content-table'>
					<?
					$i = 1;
					$addClass = "";
					foreach ($arResult["BASKET"] as $arItem) {
						$arProps = array();
						foreach ($arItem["PROPS"] as $arProperty) {
							if(!stripos($arProperty["CODE"],".XML")){
								$arProps[] = $arProperty["NAME"].": ".$arProperty["VALUE"];
							}
						}
						?>
							<tr class='<?=$addClass?>'>
								<td class='cell-img'><img src="<?=($arItem["PICTURE"]["src"] ? $arItem["PICTURE"]["src"]:$sComponentFolder."/images/noimg.png")?>" title="<?=$arItem["NAME"]?>" alt="<?=$arItem["NAME"]?>"></td>
								<td class='cell-name'><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a><br><?=implode(", ",$arProps)?></td>
								<td class='cell-price'><?=$arItem["PRICE_FORMATED"]?></td>
								<td class='cell-quan'><?=$arItem["QUANTITY"]?></td>
								<td class='cell-summa'><?=$arItem["SUMMA_FORMAT"]?></td>
							</tr>
						<?
						if($i==2 and count($arResult["BASKET"])>2){
							$addClass = "hidden-row";
							?>
								<tr>
									<td colspan=5 id="more-more" onclick='toggleHiddenRow()'>Развернуть</td>
								</tr>

							<?
						}
						$i++;
					}
					?>
				</table>
				<input type="hidden" name="_PRICE" id="PRICE" value="<?=$arResult["PRICE"]?>" />
				<input type="hidden" name="_PRICE_FORMATED" id="PRICE_FORMATED" value="<?=$arResult["PRICE_FORMATED"]?>" />
				<input type="hidden" name="_DISCOUNT_PRICE" id="DISCOUNT_PRICE" value="<?=$arResult["DISCOUNT_PRICE"]?>" />
				<input type="hidden" name="_DISCOUNT_PRICE_FORMATED" id="DISCOUNT_PRICE_FORMATED" value="<?=$arResult["DISCOUNT_PRICE_FORMATED"]?>" />
				<?if($reloadBasket) die();?>
			</div>
		</form>
		</div>
		<?

		/* параметры для JS */
		$arJSParams["PRICE"] = $arResult["PRICE"];
		$arJSParams["PRICE_WITH_DISCOUNT"] = $arResult["PRICE_WITH_DISCOUNT"];
		$arJSParams["DELIVERY_PRICE"]=$arResult["DELIVERY_PRICE"];
		$arJSParams["DELIVERIES"]=$arResult["DELIVERIES"];
		$arJSParams["SHOPS"]=$arResult["SHOPS"];
		$arJSParams["DISCOUNT_ID"]=$arParams["DISCOUNT_ID"];
		$arJSParams["DISCOUNT_PAYMENT_ID"]=$arParams["PAYMENT_CARDS"];
		$arJSParams["ORDER_ADD_DISCOUNT_PATH"]=$sComponentFolder.'/ajax.php';
		// $arJSParams["SHOP_TEMPLATE_BALLOON"]=htmlspecialchars_decode($arResult["SHOP_TEMPLATE_BALLOON"]);
		foreach ($arResult["SHOP_TEMPLATE_BALLOON"] as $key => $val) {
			$arJSParams["SHOP_TEMPLATE_BALLOON"][$key]=htmlspecialchars_decode(str_replace("'",'"',$arResult["SHOP_TEMPLATE_BALLOON"][$key]));
		}

		?>
		<script>
		var ORDER_PARAMS = <?=CUtil::PhpToJSObject($arJSParams, false, true)?>;

		function toggleHiddenRow(){
			$(".hidden-row").toggle( "slow", function() {
				if($(".hidden-row").css("display") == "none"){
					$("#more-more").html("Развернуть");
				}else{
					$("#more-more").html("Свернуть");
				}
			 });
		}

		function ChangeDeliveryOrPayment(athis){
			if($(athis).hasClass('order-payment-select-outer')){
				var paymentVariant = $(athis).data('payment');
				var paymentVariantOld = $('.order-payment-select-outer').filter('.is_selected').data('payment');
				if ( paymentVariant != paymentVariantOld ) {
					$('#paymentSelector').val(paymentVariant);
					$(".order-payment-select-outer").removeClass("is_selected");
					$(athis).addClass('is_selected');
				}				
			}
			
			var arInput = $('#order-make-simple input, #order-make-simple textarea'),
				result = [];	
	
			result['AJAX_UPDATE'] = 'Y';
				
			for(var i = 0; i < arInput.length; i++){
				var val = arInput.eq(i).val(),
					code = arInput.eq(i).attr('name');

				if(arInput.eq(i).attr('type') == 'checkbox' || arInput.eq(i).attr('type') == 'radio'){
					if(!arInput.eq(i).is(':checked')){
						continue;
					}
				}
					
				if(code == 'CREATE_ORDER' || code == 'RELOAD_ALL'){
					continue;
				}
				
				result[code] = val;
			}
			
			BX.showWait();
			BX.ajax.post(
				"?AJAX_UPDATE=Y",
				result,
				function (postdata) {
					BX.closeWait();
					$('#order_make_container').html(postdata);
					CheckDeliveryMethod();
				}
			);
		}
		
		BX.ready(function(){
			BX.Currency.setCurrencyFormat('RUB', <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);

			$('#PROP-PHONE').inputmask({mask:"+7 (999) 9999999"});

			$("#mapOrderShopSelectButton").on("click",function(){
				$('#mapOrderShopSelect').modal('show').ready(function(){
					if(typeof shopMap == "undefined"){
						ymaps.ready(shopMapCreate);
					}else{
						// позиционируемся к точке
						shopMap.setCenter([ORDER_PARAMS.SHOPS[$("#select-shop").val()]["LAT"], ORDER_PARAMS.SHOPS[$("#select-shop").val()]["LON"]],12);
					}
				});
			});
			// show all points
			$("#show-all-points").on("click",function(){
				shopMap.setBounds(myCollection.getBounds(), {
					checkZoomRange: true,
					zoomMargin: 10
				});
				shopMap.balloon.close();
			});
			// position to point
			$(".map-shop-list").on("click",function(){
				id = $(this).data("shop");
				//shopMap.panTo([SHOPS[id]["LAT"], SHOPS[id]["LON"]],{flying:1});
				shopMap.setCenter([ORDER_PARAMS.SHOPS[id]["LAT"], ORDER_PARAMS.SHOPS[id]["LON"]],12);
				shopMap.balloon.close();
			});
			// shop selector
			$(".shop-selector").on("click",function(){
				id = $(this).data("shop");
				$("#select-shop").val(id);
				$('#mapOrderShopSelect').modal('toggle');
			});

			// close modal
			$('#mapOrderShopSelect .close_form').bind('click',function(){
				$('#mapOrderShopSelect').modal('toggle');
			});

			var submitBtn = BX('order-make-simple-submit');
			BX.bind(submitBtn, 'click', function(){
				BX.onCustomEvent('my-event-name', []);
			});
			if (!BX.UserConsent){return false;}
			var control = BX.UserConsent.load(BX('order-make-block-outer'));
			if (!control){return false;}
			BX.addCustomEvent(
				control,
				BX.UserConsent.events.save,
				function (data) {
					createOrder();
				}
			);
		});

		/*
		 * Delivery Check Function
		 */
		function CheckDeliveryMethod() {
			if($('input[name="DELIVERY"]:checked').val() === "<?=$arParams['DELIVERY_SHOP_TYPE']?>"){
				$("#select-shop-block").css("display","block");
				<?
					foreach ($arParams['HIDE_ORDER_PROPS'] as $hideProp) {
						// уберем у полей обязательность
						?>
						$("#PROP-<?=$arResult["ORDER_PROPS"][$hideProp]["CODE"]?>").data("requared","N");
						console.log("#PROP-<?=$arResult["ORDER_PROPS"][$hideProp]["CODE"]?>" + " = " + $("#PROP-<?=$arResult["ORDER_PROPS"][$hideProp]["CODE"]?>").data("requared"));
						<?
						// скроем не нужные поля
						?>
						$("#PROP-<?=$arResult["ORDER_PROPS"][$hideProp]["CODE"]?>").css("display","none");
						<?

					}

				?>}else{<?
					foreach ($arParams['HIDE_ORDER_PROPS'] as $hideProp) {
						// вернем у полей обязательность
						?>
						$("#PROP-<?=$arResult["ORDER_PROPS"][$hideProp]["CODE"]?>").data("requared","<?=$arResult["ORDER_PROPS"][$hideProp]["REQUIED"]?>");
						<?
						// вернем поля
						?>
						$("#PROP-<?=$arResult["ORDER_PROPS"][$hideProp]["CODE"]?>").css("display","initial");
						<?

					}
				?>
				$("#select-shop-block").css("display","none");
			}			
		}

		</script>
		<?
		}
		?>	
	<?
	if($_POST['AJAX_UPDATE'] == 'Y'){
		die();
	}
	?>
</div>
