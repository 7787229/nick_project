$(document).ready(function(){

	// кнопка ВВЕРХ
	$("#scroll_top").click(function(e) {
		e = e || window.event;
		e.preventDefault();

		$("html, body").animate({
			scrollTop: 0
		}, "slow");
	});


	$('#search_form .search').click(function(){
		$('#search_form .search-form-input').toggleClass('open');
	});

	/*-----------------------overflow text ...-----------------------------*/

        /*
        var size=40,


			caption=$('.item_kart-caption'),
			captionText=caption.text();
	if(captionText.length > size){
		caption.text(captionText.slice(0, size) + ' ...');
	}
        */


	/* авторизация/регистрация или ссылка на профиль */
	$.ajax({
		method: "post",
		url: "/ajax/getHeaderLinks.php",
		data: {},
		success: function(data){
			$('#auth_and_profile').html(data);
		}
	});
	/* !авторизация/регистрация или ссылка на профиль */

	/* вывод попапа с авторизацией-регистрацией */
	// $.ajax({
	// 	method: "post",
	// 	url: "/ajax/auth_and_register.php",
	// 	data: {},
	// 	success: function(data){
	// 		$('#auth_and_register__container').html(data);
	//
	// 	}
	// });
	/* вывод попапа с авторизацией-регистрацией */


    /*change*/
    $('.show_cat').click(function () {
        $(this).find('.mob-cat').toggle();
    });
    /*change*/
});



function gotoDelay(id){
        DelayUrl = "/ajax/add2delay.php";
        var basketParams = {};
        basketParams["price_id"] = id;
        $.ajax({
            type: "GET",
            url: DelayUrl,
            data: basketParams,
            success: function(data){

		console.log(data);

                  if(data === "deleted"){
                      $(".favorite-"+id).removeClass('active');
                      $(".favorite-"+id+" i.fa").addClass('fa-heart-o');
                      $(".favorite-"+id+" i.fa").removeClass('fa-heart');
                  }else{
                      $(".favorite-"+id).addClass('active');
                      $(".favorite-"+id+" i.fa").removeClass('fa-heart-o');
                      $(".favorite-"+id+" i.fa").addClass('fa-heart');
                  }

                  $.post("/ajax/delay_line.php", function(data) {
                          $(".favorites").html(data);
                  });
            }
          });
 }

function open_social_share_window(link){
     window.open(link, 'share', "width=600,height=400");
}

/*
 * Функция чтения куки
 *
 * @param {string} name - имя куки
 * @returns {unresolved} - возвращается значение куки
 */
function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

/*
 * Функция установка куков
 *
 * @param {string} name - наименование куки
 * @param {string} value - значение куки
 * @param {int} days - время жизни куки в днях
 * @returns {undefined}
 */
function createCookie(name, value, days) {
    var expires;

    if (days) {
        var date = new Date();
        //date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        date.setTime(date.getTime() + (days * 1000));
        expires = "; expires=" + date.toGMTString();
    } else {
        expires = "";
    }
    document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
}
