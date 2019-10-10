<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
<?

//printvar("",$arParams['MAIN_BANNER_TYPE']);
//printvar("",$arResult);

// найдем все отложенные товары и будем выделять их при выводе
$arDelay = listDelay();	

if($arParams['MAIN_BANNER_TYPE'] == "SLIDER"){
	// slider output
	if(count($arResult["SLIDER"])>0){
    ?>
        <div class="row">
            <div class="col-md-12">
                <div id="banner" class="owl-carousel owl-theme">    
                <?
                foreach ($arResult["SLIDER"] as $keySlider => $valueSlider) {
                    ?>
                    <div class="item item_kart">
                        <img src='/upload/<?=$valueSlider["PICTURE"]["SUBDIR"]."/".$valueSlider["PICTURE"]["FILE_NAME"]?>' alt='<?=$valueSlider["NAME"]?>'>
                        <?=($valueSlider["URL"] ? "<a href='".$valueSlider["URL"]."'>":"").$valueSlider["TITLE"].($valueSlider["URL"] ? "<span>&gt;</span></a>":"")?>
                    </div>
                    <?
                }
                ?>
                </div>
            </div>
        </div>            
        <?
	}
}else{?>
	<div class='row'>
		<div class='col-md-12'>
			<div class='banner_video'>
				<?if($arResult['VIDEO']['YOUTUBE_VIDEO'] == "Y"){?>
					<?if(!empty($arResult['VIDEO']['VIDEO_ID'])){?>
						 <section class="big-background" style='background:url(<?=$arResult['VIDEO']['POSTER']?>)'>
							<a id="bgndVideo" class="player" data-property="{videoURL:'https://www.youtube.com/watch?v=<?=$arResult['VIDEO']['VIDEO_ID']?>',containment:'.big-background',autoPlay:true, mute:true, startAt:0, opacity:1}"></a>        
						</section>
						
						<script>
							$(function(){
								$('.player').mb_YTPlayer();
							});
						</script>
					<?}?>
				<?}else{?>
					<?if(!empty($arResult['VIDEO']['VIDEO_MP4']) || !empty($arResult['VIDEO']['VIDEO_WEBM']) || !empty($arResult['VIDEO']['VIDEO_OGG'])){?>
						<video autoPlay loop controls='no' muted poster='<?=$arResult['VIDEO']['POSTER']?>'>
							<source src="<?=$arResult['VIDEO']['VIDEO_MP4']?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
							<source src="<?=$arResult['VIDEO']['VIDEO_WEBM']?>" type='video/webm; codecs="vp8, vorbis"' />
							<source src="<?=$arResult['VIDEO']['VIDEO_OGG']?>" type='video/ogg; codecs="theora, vorbis"' />
						 <video>
					<?}?>
				<?}?>
			</div>
		</div>
	</div>
<?}?>

<h1 class="hide-h1"><?$APPLICATION->ShowTitle()?></h1>
<?
// include area #1
$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/main1.php"
	)
);?>

<?       

// NEW        
if($arParams["SHOW_NEW"]=="Y" and is_array($arResult["NEW"]["ITEMS"])){
    ?>
        <div class="row">
            <div class="col-md-12">
                    <div class="title"><?=$arResult["NEW"]["TITLE"]?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="news" class="owl-carousel owl-theme">
                <? 
                foreach ($arResult["NEW"]["ITEMS"] as $keyNew => $valueNew) {
                    // пометим отложенные товары
                    $favorite = false;
                    if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}

                    ?>
                    <div class="item item_kart">
                        <div class="img">
                            <img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
                            <div class="item_mark"> 
								<?
									// скидка
									if(intval($valueNew['DISCOUNT_PERCENT'])>0){
										?>
											<div class="item-discount">
												<svg>
													<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  
												</svg>
												<span>-<?=$valueNew['DISCOUNT_PERCENT']?>%</span>
											</div>
										<?
									}
									// скидка из свойства
									if(in_array($valueNew["DISCOUNT"],array('Y','y','Да','да','true'))){
											?>
											<div class="item-discount-prop" title="<?=GetMessage("Discounts")?>">
												<svg>
													<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  	  
												</svg>
												<span><?=GetMessage("Discount")?></span>
											</div>
											<?
									}                               
									// хит продаж
									if(in_array($valueNew["BESTSELLER"],array('Y','y','Да','да','true'))){
											?>
											<div class="item-bestseller">
													<svg>
															<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  	  
													</svg>
											   <span> <?=GetMessage("Bestseller")?></span>
											</div>
											<?
									} 
								?>
                            </div>
						</div>
                        <div class="title-news">
							<?/*
                                <span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
                                <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span>
							*/?>
                                <?
                                if(intval($valueNew['DISCOUNT_PERCENT'])>0){
                                    ?>  
                                        <span class="price sale"><?=$valueNew["PRICE_FORMAT"]?></span>
                                        <span class="price sale-new"><?=number_format(round($valueNew["PRICE"] - $valueNew["PRICE"]*intval($valueNew['DISCOUNT_PERCENT'])/100), 0, '.', ' ' ).'р.';?></span>
                                    <?
                                    }else{
                                    ?>
                                        <span class="price"><?=$valueNew["PRICE_FORMAT"]?></span>
                                    <?
                                    }
                                ?>
                            </div>
                            <a href="<?=$valueNew["DETAIL_PAGE_URL"]?>"></a>
							<?/*
                            <div class="news-icon" onclick='gotoDelay("<?=$valueNew["PRICE_ID"]?>")'>
								<span class="favorite-<?=$valueNew["PRICE_ID"]?> news-favorite<?=($favorite ? " active":"")?>">
									<i class="fa fa-<?=($favorite ? "heart":"heart-o")?>" aria-hidden="true"></i>
								</span>
                            </div>
							*/?>
                    </div>
                    <?
                }
                ?>
                </div>                        
            </div>
        </div>
    <?
}
?>        
        
        
<?
// include area #2
$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/main2.php"
	)
);?>

<?$APPLICATION->IncludeComponent("bitrix:news.list", "banners_on_main", Array(
	"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
		"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
		"AJAX_MODE" => "N",	// Включить режим AJAX
		"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
		"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
		"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
		"AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
		"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
		"CACHE_GROUPS" => "Y",	// Учитывать права доступа
		"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
		"CACHE_TYPE" => "A",	// Тип кеширования
		"CHECK_DATES" => "Y",	// Показывать только активные на данный момент элементы
		"COMPONENT_TEMPLATE" => "actions",
		"DETAIL_URL" => "",	// URL страницы детального просмотра (по умолчанию - из настроек инфоблока)
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"FIELD_CODE" => array(	// Поля
			0 => "DETAIL_PICTURE",
			1 => "",
		),
		"FILTER_NAME" => "",	// Фильтр
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",	// Скрывать ссылку, если нет детального описания
		"IBLOCK_ID" => "10",	// Код информационного блока
		"IBLOCK_TYPE" => "content",	// Тип информационного блока (используется только для проверки)
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",	// Включать инфоблок в цепочку навигации
		"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
		"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
		"NEWS_COUNT" => "20",	// Количество новостей на странице
		"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => ".default",	// Шаблон постраничной навигации
		"PAGER_TITLE" => "Новости",	// Название категорий
		"PARENT_SECTION" => "",	// ID раздела
		"PARENT_SECTION_CODE" => "",	// Код раздела
		"PREVIEW_TRUNCATE_LEN" => "",	// Максимальная длина анонса для вывода (только для типа текст)
		"PROPERTY_CODE" => array(	// Свойства
			0 => "BANNER_LINK",
			1 => "BANNER_TITLE",
			2 => "BANNER_WIDTH",
			3 => "",
		),
		"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
		"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
		"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
		"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
		"SET_STATUS_404" => "N",	// Устанавливать статус 404
		"SET_TITLE" => "N",	// Устанавливать заголовок страницы
		"SHOW_404" => "N",	// Показ специальной страницы
		"SORT_BY1" => "SORT",	// Поле для первой сортировки новостей
		"SORT_BY2" => "SORT",	// Поле для второй сортировки новостей
		"SORT_ORDER1" => "ASC",	// Направление для первой сортировки новостей
		"SORT_ORDER2" => "ASC",	// Направление для второй сортировки новостей
	),
	false
);?> 		
<br>		
<br>

<?
// BESTSELLER
if($arParams["SHOW_BESTSELLER"]=="Y" and is_array($arResult["BESTSELLER"]["ITEMS"])){
    ?>
        <div class="row">
            <div class="col-md-12">
                    <div class="title"><?=$arResult["BESTSELLER"]["TITLE"]?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="best" class="owl-carousel owl-theme">
                <? 
                foreach ($arResult["BESTSELLER"]["ITEMS"] as $keyNew => $valueNew) {
                    // пометим отложенные товары
                    $favorite = false;
                    if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}
                    ?>
                    <div class="item item_kart">
                        <div class="img">
                            <img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
                            <div class="item_mark"> 
                            <?
									// скидка
									if(intval($valueNew['DISCOUNT_PERCENT'])>0){
										?>
											<div class="item-discount">
												<svg>
													<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  
												</svg>
												<span>-<?=$valueNew['DISCOUNT_PERCENT']?>%</span>
											</div>
										<?
									}
									// новинка
									if(in_array($valueNew["NEWPRODUCT"],array('Y','y','Да','да','true'))){
											?>
													<div class="item-newproduct" title='<?=GetMessage("New")?>'>
														<svg>
																<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  	  
														</svg>
														  <span class="fa-stack fa-lg">
															<i class="fa fa-star fa-stack-1x"></i>
															<i class="fa fa-circle-thin fa-stack-2x"></i>
														  </span>
													</div>
											<?
									}  									
									// скидка из свойства
									if(in_array($valueNew["DISCOUNT"],array('Y','y','Да','да','true'))){
											?>
											<div class="item-discount-prop" title="<?=GetMessage("Discounts")?>">
												<svg>
													<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  	  
												</svg>
												<span><?=GetMessage("Discount")?></span>
											</div>
											<?
									}                               					
							
                            ?>
                            </div>
                            </div>
                            <div class="title-news">
                                <?/*
								<span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
                                <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span>
								*/?>
                                <?
                                if(intval($valueNew['DISCOUNT_PERCENT'])>0){
                                    ?>  
                                        <span class="price sale"><?=$valueNew["PRICE_FORMAT"]?></span>
                                        <span class="price sale-new"><?=number_format(round($valueNew["PRICE"] - $valueNew["PRICE"]*intval($valueNew['DISCOUNT_PERCENT'])/100), 0, '.', ' ' ).'р.';?></span>
                                    <?
                                    }else{
                                    ?>
                                        <span class="price"><?=$valueNew["PRICE_FORMAT"]?></span>
                                    <?
                                    }
                                ?>
                            </div>
                            <a href="<?=$valueNew["DETAIL_PAGE_URL"]?>"></a>
                            <?/*
							<div class="news-icon" onclick='gotoDelay("<?=$valueNew["PRICE_ID"]?>")'>
								<span class="favorite-<?=$valueNew["PRICE_ID"]?> news-favorite<?=($favorite ? " active":"")?>">
									<i class="fa fa-<?=($favorite ? "heart":"heart-o")?>" aria-hidden="true"></i>
								</span>
                            </div>
							*/?>
                    </div>
                    <?
                }
                ?>
                </div>                        
            </div>
        </div>
   <?
}
?>
            
            
<?
// include area #3
$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => ".default",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/include/main3.php"
	)
);?>
        
		
<?
// SALE
if($arParams["SHOW_SALE"]=="Y" and is_array($arResult["SALE"]["ITEMS"])){
    
    // пометим отложенные товары
    $favorite = false;
    if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}
    ?>
        <div class="row">
            <div class="col-md-12">
                <div class="title"><?=$arResult["SALE"]["TITLE"]?></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="sale" class="owl-carousel owl-theme">
                <? 
                foreach ($arResult["SALE"]["ITEMS"] as $keyNew => $valueNew) {
                    // пометим отложенные товары
                    $favorite = false;
                    if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}                    			
                    ?>
                    <div class="item item_kart">
                        <div class="img">
						<img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
                        <div class="item_mark">   
							 <?
										// скидка
										if(intval($valueNew['DISCOUNT_PERCENT'])>0){
											?>
												<div class="item-discount">
													<svg>
															<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  
													</svg>
													<span>-<?=$valueNew['DISCOUNT_PERCENT']?>%</span>
												</div>
											<?
										}
                                        // новинка
                                        if(in_array($valueNew["NEWPRODUCT"],array('Y','y','Да','да','true'))){
                                                ?>
														<div class="item-newproduct" title='<?=GetMessage("New")?>'>
															<svg>
																	<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  	  
															</svg>
															  <span class="fa-stack fa-lg">
																<i class="fa fa-star fa-stack-1x"></i>
																<i class="fa fa-circle-thin fa-stack-2x"></i>
															  </span>
                                                        </div>
                                                <?
                                        }                            
                                        // хит продаж
                                        if(in_array($valueNew["BESTSELLER"],array('Y','y','Да','да','true'))){
                                                ?>
                                                        <div class="item-bestseller">
                                                                <svg>
                                                                        <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>		  	  
                                                                </svg>
                                                           <span> <?=GetMessage("Bestseller")?></span>
                                                        </div>
                                                <?
                                        } 								
                            ?>	
                            </div>
                            </div>
                            <div class="title-news">
								<?/*
                                <span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
                                <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span>
								*/?>
                                <?
									if(intval($valueNew['DISCOUNT_PERCENT'])>0){
										?>  
											<span class="price sale"><?=$valueNew["PRICE_FORMAT"]?></span>
											<span class="price sale-new"><?=number_format(round($valueNew["PRICE"] - $valueNew["PRICE"]*intval($valueNew['DISCOUNT_PERCENT'])/100), 0, '.', ' ' ).'р.';?></span>
										<?
										}else{
										?>
											<span class="price"><?=$valueNew["PRICE_FORMAT"]?></span>
										<?
										}                            								
                                ?>
                            </div>
                            <a href="<?=$valueNew["DETAIL_PAGE_URL"]?>"></a>
							<?/*
							<div class="news-icon" onclick='gotoDelay("<?=$valueNew["PRICE_ID"]?>")'>
								<span class="favorite-<?=$valueNew["PRICE_ID"]?> news-favorite<?=($favorite ? " active":"")?>">
									<i class="fa fa-<?=($favorite ? "heart":"heart-o")?>" aria-hidden="true"></i>
								</span>
                            </div>
							*/?>
                    </div>
                    <?
                }
                ?>
                </div>                        
            </div>
        </div>
   <?
}

$APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/owl.carousel/owl.carousel.js");
$APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/owl.carousel/css/owl.carousel.css");

?>

	<script>	
            $(document).ready(function() {	 
                $('#banner').ready(function(){	 
                        $('#banner').owlCarousel({
                            loop:<?=(count($arResult["SLIDER"])>1 ? "true":"false")?>,
                            nav:true,
                            items:1,
							autoplay:true,
							autoplayTimeout:4000,
							autoplayHoverPause:true,
							smartSpeed:1000,
							animateOut: 'fadeOut'
                        });
                    });
                $('#news').ready(function(){	 
                    $('#news').owlCarousel({
                        loop:<?=(count($arResult["NEW"]["ITEMS"])>1 ? "true":"false")?>,
                        nav:true,
                         responsive:{
                                0:{
                                    items:1
                                },
                                400:{
                                    items:2
                                },
								600:{
                                    items:3
                                },
                                1000:{
                                    items:5
                                }
                        }
                    });
                    $('#news .owl-item .img').height($('#news .owl-item .img').width());
                });  
                
                $('#best').ready(function(){	 
                    $('#best').owlCarousel({
                        loop:<?=(count($arResult["BESTSELLER"]["ITEMS"])>1 ? "true":"false")?>,
                        nav:true,
                         responsive:{
                                0:{
                                    items:1
                                },
                                400:{
                                    items:2
                                },
								600:{
                                    items:3
                                },
                                1000:{
                                    items:5
                                }
                        }
                    });
                    $('#best .owl-item .img').height($('#best .owl-item .img').width());                        
                }); 
                
                $('#sale').ready(function() {	 
                    $('#sale').owlCarousel({
                        loop:<?=(count($arResult["SALE"]["ITEMS"])>1 ? "true":"false")?>,
                        nav:true,
                         responsive:{
                                0:{
                                    items:1
                                },
                                400:{
                                    items:2
                                },
								600:{
                                    items:3
                                },
                                1000:{
                                    items:5
                                }
                        }
                    });
                    $('#sale .owl-item .img').height($('#sale .owl-item .img').width());                        
                });  

		$(window).resize(function(){
			$('#news .owl-item .img, #best .owl-item .img, #sale .owl-item .img').height($('#news .owl-item .img').width());
		})
	});
</script>            