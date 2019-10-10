<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
<?

// найдем все отложенные товары и будем выделять их при выводе
$arDelay = listDelay();	

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
?>
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
                                <span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
                                <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span>
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
                            <div class="news-icon" onclick='gotoDelay("<?=$valueNew["PRICE_ID"]?>")'>
                                    <span class="favorite-<?=$valueNew["PRICE_ID"]?> news-favorite<?=($favorite ? " active":"")?>">
					<i class="fa fa-<?=($favorite ? "heart":"heart-o")?>" aria-hidden="true"></i>
                                    </span>
                            </div>
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
                                <span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
                                <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span>
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
                            <div class="news-icon" onclick='gotoDelay("<?=$valueNew["PRICE_ID"]?>")'>
                                    <span class="favorite-<?=$valueNew["PRICE_ID"]?> news-favorite<?=($favorite ? " active":"")?>">
					<i class="fa fa-<?=($favorite ? "heart":"heart-o")?>" aria-hidden="true"></i>
                                    </span>
                            </div>
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
                                <span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
                                <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span>
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
                            <div class="news-icon" onclick='gotoDelay("<?=$valueNew["PRICE_ID"]?>")'>
                                    <span class="favorite-<?=$valueNew["PRICE_ID"]?> news-favorite<?=($favorite ? " active":"")?>">
					<i class="fa fa-<?=($favorite ? "heart":"heart-o")?>" aria-hidden="true"></i>
                                    </span>
                            </div>
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
                                    items:4
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
                                    items:4
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
                                    items:4
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