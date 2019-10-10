<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

// CJSCore::Init(array('currency'));
$currencyFormat = CCurrencyLang::GetFormatDescription($arResult['CURRENCY']);

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

if(count($arResult["BASKET"]) == 0){
	?>
		<h2>Ваша корзина пуста!</h2>
	<?
}else{

$sComponentFolder = $this->__component->__path;
$this->addExternalJS($sComponentFolder."/maskinput/jquery.maskedinput.min.js"); // https://itchief.ru/lessons/javascript/input-mask-for-html-input-element
$this->addExternalJS("https://api-maps.yandex.ru/2.1/?lang=ru_RU"); // https://tech.yandex.ru/maps/jsbox/2.1/mapbasics

$requaredTemplate = " <span style='color:#f9c7bd;font-size:14px;'>*</span>";

?>
<div id='order-make-block-outer'>
<form id='order-make-simple' action='' method='POST'>
	<input type='hidden' name="CREATE_ORDER" value="AJAX">
	<input type='hidden' id="DELIVERY_PRICE" name="DELIVERY_PRICE" value="">
	<div class="row">
		<div class='col-md-6 col-sm-6 col-xs-12 order-form-block'>
			<div class="row">
			<?
			// формируем персональные данные
			foreach ($arResult["ORDER_PROPS"]  as $propID => $arProp){

				if(!in_array($propID,$arParams["ORDER_PROPS"]) or $arProp["PROPS_GROUP_ID"] <> $arParams["PERSONAL_PROPS_GROUP"]) continue;

				switch ($arProp["TYPE"]) {
					case "TEXT":
						?>
							<div class='col-md-4 col-sm-12 order-form-block'>
								<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? $requaredTemplate:"")?>
							</div>
							<div class='col-md-8 col-sm-12 order-form-block'>
								<input data-requared='<?=$arProp["REQUIED"]?>' class="form-control" type='text' id="PROP-<?=$arProp["CODE"]?>" name="<?=$arProp["CODE"]?>" value="<?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?>">
							</div>
						<?
						break;
					case "TEXTAREA":
						?>
							<div class='col-md-4 col-sm-12 order-form-block'>
								<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? $requaredTemplate:"")?>
							</div>
							<div class='col-md-8 col-sm-12 order-form-block'>
								<textarea data-requared='<?=$arProp["REQUIED"]?>'  class="form-control" id="PROP-<?=$arProp["CODE"]?>" name="<?=$arProp["CODE"]?>" rows="<?=($arProp["SIZE2"] ? $arProp["SIZE2"]:3)?>"><?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?></textarea>
							</div>
						<?
						break;

					default:
						break;
				}
			}
			?>
			</div>
			<? /* доставка */ ?>
			<div class="row">
				<div class='col-md-12 col-sm-12 order-form-block'>
					<?=$arParams["DELIVERY_TITLE"]?>
				</div>
				<div class='col-md-12 col-sm-12 order-form-block'>
					<select id='DELIVERY_TYPE'  class="form-control" name='DELIVERY'  onchange="DeliveryTypeChange(this)">
					<?
						foreach ($arParams["DELIVERY_TYPE"] as $deliveryID) {
							?>
								<option value='<?=$deliveryID?>'><?=$arResult["DELIVERY"][$deliveryID]["NAME"]?></option>
							<?
						}

					?>
					</select>
				</div>
				<div id='select-shop-block' <div id='select-shop-block'>
					<div class='col-md-8 col-sm-8 col-xs-12 order-form-block'>
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
					<div class='col-md-4 col-sm-4 col-xs-12 order-form-block'>
						<input id='mapOrderShopSelectButton'  type='button' value='На карте' class="btn btn-default">
					</div>
					<div id="mapOrderShopSelect" class="modal">
						<div class="modal-dialog">
						  <div class="modal-content">
							  <span class="close_form"><i class="fa fa-times" aria-hidden="true"></i></span>
								<div class="modal-body">
									<div class='row'>
										<div class="col-md-6 col-sm-6 col-xs-12" id='shopMapBlock'>

										</div>
										<div class="col-md-6 col-sm-6 col-xs-12">
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

				switch ($arProp["TYPE"]) {
					case "TEXT":
						?>
							<div class='col-md-12 col-sm-12 order-form-block'>
								<input data-requared='<?=$arProp["REQUIED"]?>'  class="form-control" type='text' id="PROP-<?=$arProp["CODE"]?>" name="<?=$arProp["CODE"]?>" value="<?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?>" placeholder='<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? " *":"")?>'>
							</div>
						<?
						break;
					case "TEXTAREA":
						?>
							<div class='col-md-12 col-sm-12 order-form-block'>
								<textarea data-requared='<?=$arProp["REQUIED"]?>'  class="form-control" id="PROP-<?=$arProp["CODE"]?>" name="<?=$arProp["CODE"]?>" rows="<?=($arProp["SIZE2"] ? $arProp["SIZE2"]:3)?>" placeholder='<?=$arProp["NAME"]?><?=($arProp["REQUIED"]=="Y" ? " *":"")?>'><?=($arProp["VALUE"] ? $arProp["VALUE"]:"")?></textarea>
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
		<div class='col-md-6 col-sm-6 col-xs-12 order-form-block'>
			<div class='price-block-outer'>
				<table class="price-block">
					<tr>
						<td class='text-left'>
							Товаров на:
						</td>
						<td class='text-right'>
							<?=$arResult["PRICE_FORMATED"]?>
						</td>
					</tr>
					<tr>
						<td class='text-left'>
							Доставка:
						</td>
						<td class='text-right' id="delivery-price">

						</td>
					</tr>
					<tr>
						<td class='text-left'>
							Итого:
						</td>
						<td class='text-right' id="order-summa-price">

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
					<td class='cell-img'><img src="<?=($arItem["PICTURE"]["src"] ? $arItem["PICTURE"]["src"]:$sComponentFolder."/img/noimg.png")?>" title="<?=$arItem["NAME"]?>" alt="<?=$arItem["NAME"]?>"></td>
					<td class='cell-name'><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a><br><?=implode(", ",$arProps)?></td>
					<td class='cell-price'><?=$arItem["PRICE"]?></td>
					<td class='cell-quan'><?=$arItem["QUANTITY"]?></td>
					<td class='cell-summa'><?=$arItem["SUMMA_FORMAT"]?></td>
				</tr>
			<?
			if($i==2 and count($arResult["BASKET"])>2){
				$addClass = "hidden-row";
				?>
					<tr>
						<td colspan=5 id="more-more">Развернуть</td>
					</tr>

				<?
			}
			$i++;
		}
		?>
	</table>

</form>
</div>

<script>
var DELIVERY_PRICE = <? echo CUtil::PhpToJSObject($arResult["DELIVERY_PRICE"], false, true); ?>;
var SHOPS = <? echo CUtil::PhpToJSObject($arResult["SHOPS"], false, true); ?>;
var PRICE = <?=$arResult["PRICE"]?>;


function DeliveryTypeChange(delivery){
	CheckDeliveryMethod();
}
/*
 * Delivery Check Function
 */
function CheckDeliveryMethod() {
	if($("#DELIVERY_TYPE").val() === "<?=$arParams['DELIVERY_SHOP_TYPE']?>"){
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
	<?
	// заполним скрытое поле со стоимостью доставки
	?>$("#DELIVERY_PRICE").val(DELIVERY_PRICE[$("#DELIVERY_TYPE").val()]);<?
	// заполним ячейку со стоимостью доставки
	?>$("#delivery-price").html((DELIVERY_PRICE[$("#DELIVERY_TYPE").val()]==="0" ? "<span class='freepay'>бесплатно</span>":BX.Currency.currencyFormat(DELIVERY_PRICE[$("#DELIVERY_TYPE").val()], '<?=$arResult["CURRENCY"]?>', true)));<?
	// пересчитаем общую стоимость
	?>$("#order-summa-price").html(BX.Currency.currencyFormat((1*DELIVERY_PRICE[$("#DELIVERY_TYPE").val()]+1*PRICE), '<?=$arResult["CURRENCY"]?>', true));<?
	?>

}
/*
 * Field Completeness Check Function
 * return: 1 - all required fields are filled out, 0 - not all fields are filled
 */
function CheckFieldsEmpty(){
	var inputField =  $("form#order-make-simple :input");
	var req = "N";

	for (var i=0, len=inputField.length; i<len; i++) {
		if($(inputField[i]).data("requared")==="Y" && $(inputField[i]).val()===""){
			$(inputField[i]).addClass("requared-input");
			req = "Y";
		}else{
			$(inputField[i]).removeClass("requared-input");
		}
	}

	if(req === "Y"){
		$("#order-message").html("Необходимо заполнить все обязательные поля!");
		return 0;
	}else{
		$("#order-message").html("");
		return 1;
	}
}
// end checkFieldsEmpty()
// create map and collection points
function shopMapCreate(){
	myCollection = new ymaps.GeoObjectCollection({},{});
	<?
		foreach ($arResult["SHOPS"] as $idShop => $arShop) {
			?>
			myCollection.add(new ymaps.Placemark([<?=$arShop["LAT"]?>, <?=$arShop["LON"]?>], {
				balloonContent: '<?=htmlspecialchars_decode(str_replace("'",'"',$arResult["SHOP_TEMPLATE_BALLOON"][$idShop])).'<a href="javascript:void(0)" onclick="ShopSelectorHandler('.$idShop.')" class="shop-selector">выбрать</a>'?>'
			}, {
				preset: 'islands#icon',
				iconColor: '#0095b6'
			}));
			<?
		}
	?>
	shopMap = new ymaps.Map('shopMapBlock', {
		center: [SHOPS[$("#select-shop").val()]["LAT"], SHOPS[$("#select-shop").val()]["LON"]], // Москва
		zoom: 12
	}, {
		searchControlProvider: 'yandex#search'
	});

	shopMap.geoObjects.add(myCollection);
}

////////////////////////////////////////////////////////////////////
$("#mapOrderShopSelectButton").on("click",function(){
	$('#mapOrderShopSelect').modal('show').ready(function(){
		if(typeof shopMap == "undefined"){
			ymaps.ready(shopMapCreate);
		}else{
			// позиционируемся к точке
			shopMap.setCenter([SHOPS[$("#select-shop").val()]["LAT"], SHOPS[$("#select-shop").val()]["LON"]],12);
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
	shopMap.setCenter([SHOPS[id]["LAT"], SHOPS[id]["LON"]],12);
	shopMap.balloon.close();
});
// shop selector
$(".shop-selector").on("click",function(){
	id = $(this).data("shop");
	$("#select-shop").val(id);
	$('#mapOrderShopSelect').modal('toggle');
});
function ShopSelectorHandler(id){
	$("#select-shop").val(id);
	$('#mapOrderShopSelect').modal('toggle');
}
// position to point
$("#more-more").on("click",function(){
	$(".hidden-row").toggle( "slow", function() {
		if($(".hidden-row").css("display") == "none"){
			$("#more-more").html("Развернуть");
		}else{
			$("#more-more").html("Свернуть");
		}
	 });
});
// close modal
$('#mapOrderShopSelect .close_form').bind('click',function(){
	$('#mapOrderShopSelect').modal('toggle');
});

$("#order-make-simple-submit").on("click",function(){
	// проверка на заполненность полей
	if(CheckFieldsEmpty() === 0){
		return;
	}

	BX.showWait();
	console.log("Создаем заказ");

	BX.onCustomEvent('my-event-name', []);

    if (!BX.UserConsent){return;}

    var control = BX.UserConsent.load(BX('order-make-block-outer'));
    if (!control){return;}

    BX.addCustomEvent(
        control,
        BX.UserConsent.events.save,
        function (data) {
					BX.ajax.submit(BX("order-make-simple"), function(data){
						if(data>0){
							BX('order-make-block-outer').innerHTML = "<h2>Заказ №" + data + " создан!</h2>";
						}else{
							BX('order-message').innerHTML = "ОШИБКА: " + data;
						}
					});
			}
	);

	BX.closeWait();
});

$(document).ready(function(){
	BX.Currency.setCurrencyFormat('RUB', <? echo CUtil::PhpToJSObject($currencyFormat, false, true); ?>);
	CheckDeliveryMethod();
	$("#PROP-PHONE").mask("+7(999) 999-9999");
	//$('#PROP-PHONE').inputmask({mask:"+7 (999) 9999999"});
});

</script>
<?
}
//printvar("sd",$arParams);
//printvar("sd",$arResult);
