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
	jQuery.each(ORDER_PARAMS.SHOPS, function(key, item) {
		myCollection.add(new ymaps.Placemark([item.LAT, item.LON], {
			balloonContent: ORDER_PARAMS.SHOP_TEMPLATE_BALLOON[key]+'<a href="javascript:void(0)" onclick="ShopSelectorHandler('+key+')" class="shop-selector">выбрать</a>'
		}, {
			preset: 'islands#icon',
			iconColor: '#0095b6'
		}));
	});
	shopMap = new ymaps.Map('shopMapBlock', {
		center: [ORDER_PARAMS.SHOPS[$("#select-shop").val()]["LAT"], ORDER_PARAMS.SHOPS[$("#select-shop").val()]["LON"]], 
		zoom: 12
	}, {
		searchControlProvider: 'yandex#search'
	});

	shopMap.geoObjects.add(myCollection);
}

function ShopSelectorHandler(id){
	$("#select-shop").val(id);
	$('#mapOrderShopSelect').modal('toggle');
}

function DeliveryTypeChange(delivery){CheckDeliveryMethod();}
function DeliveryTypeChange4Radio(delivery){CheckDeliveryMethod4Radio();}

function setCoupon(mode,value,id) {
	BX.ajax({
	  url: ORDER_PARAMS.ORDER_ADD_DISCOUNT_PATH,
	  method: 'POST',
	  data: {'mode': mode, 'value': value, 'id': id},
	  dataType: "json",
	  timeout: 60,
	  async: false,
	  onsuccess: function(data){
		if( data.result=='Y') {
			$('#mess_coupon').hide();
			$('#mess_coupon').removeClass('mess-error');
			$('#mess_coupon').addClass('mess-success');
			$('#mess_coupon').html(data.mess);
			reloadBasketItems();
		}else{
			$('#mess_coupon').show();
			$('#mess_coupon').removeClass('mess-success');
			$('#mess_coupon').addClass('mess-error');
			$('#mess_coupon').html(data.error);
			return false;
		}
	  },
	  onfailure: function(){
			$('#mess_coupon').show();
			$('#mess_coupon').removeClass('mess-success');
			$('#mess_coupon').addClass('mess-error');
			$('#mess_coupon').html('ERROR: при добавлении купона на скидку.');
			// console.log("ERROR: при добавлении купона на скидку.");
			return false;
	  }
  });

}
function checkDeliveryList(param) {
	jQuery.each( ORDER_PARAMS.DELIVERY_RELATION, function( id, val ) {
		if (param=='Y') {
			$("#DELIVERY_TYPE_"+id).parent().hide();
			$("#DELIVERY_TYPE_"+val.RELATION).parent().show();
			if ($("#DELIVERY_TYPE_"+id).prop('checked') ) {
				$("#DELIVERY_TYPE_"+id).prop('checked', false);
				$("#DELIVERY_TYPE_"+val.RELATION).click();
			}
		} else {
			$("#DELIVERY_TYPE_"+val.RELATION).parent().hide();
			$("#DELIVERY_TYPE_"+id).parent().show();
			if ($("#DELIVERY_TYPE_"+val.RELATION).prop('checked') ) {
				$("#DELIVERY_TYPE_"+val.RELATION).prop('checked', false);
				$("#DELIVERY_TYPE_"+id).click();
			}
		}
	});
	// jQuery.each( DELIVERY_PRICE, function( id, val ) {
	// 	$("#DELIVERY_TYPE_"+id).click();
	// 	return false;
	// });

	return false;
}

function reloadBasketItems() {
	BX.ajax.get(
		"?AJAX=Y&mode=reloadBasketItems",
		'',
		function (postdata) {
        	BX.adjust(BX('basket_content_table'), {'html': postdata});
        	ORDER_PARAMS.PRICE=$("#PRICE").val();
        	$("#order-price").html($("#PRICE_FORMATED").val());
        	// debugger;
        	// console.log($("#DISCOUNT_PRICE").val());
        	if ( $("#DISCOUNT_PRICE").val()>0 ){
        		$("#order-discount-price").html($("#DISCOUNT_PRICE_FORMATED").val());
        		$("#order_discount").show();
        	} else {
        		$("#order-discount-price").html('');
				$("#order_discount").hide();
        	}

			CheckDeliveryMethod4Radio();
	    }
	);
}
function roundToTwo(num) {    
   return +(Math.round(num + "e+2")  + "e-2");
}

function createOrder() {
	// проверка на заполненность полей
	if(CheckFieldsEmpty() === 0){
		return false;
	}
// debugger;
	BX.showWait("order-message","Создаем заказ");
	console.log("Создаем заказ");
	BX.closeWait("order-message");
	BX.ajax.submit(
		BX("order-make-simple"), 
		function(data){
			console.log("Заказ создан");
			BX.closeWait();
			// debugger;
			if(data>0){
				BX('order-make-block-outer').innerHTML = "<h2>Заказ №" + data + " создан!</h2>";
				// $("#basket_block_2").html("<div class='row text-center'><h3><a href='/personal/order/detail/" + parseInt(postdata) + "/'>Заказ № " + parseInt(postdata) + "</a> создан успешно.</h3><br><br><a href='/catalog/'>Каталог</a> | <a href='/personal/order/'>Список заказов</a> | <a href='/personal/'>Личные данные</a></div><br><br><br>");
				// переходим к оплате
				window.location.href = '/personal/order/detail/' + parseInt(data) + '/';
			}else{
				BX('order-message').innerHTML = "ОШИБКА: " + data;
			}
		}
	);
	return false;
}
