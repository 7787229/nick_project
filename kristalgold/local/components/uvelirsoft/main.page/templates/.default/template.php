<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
</div>

<?
$this->addExternalJS(DEFAULT_TEMPLATE."/owl.carousel/owl.carousel.js");
$this->addExternalCss(DEFAULT_TEMPLATE."/owl.carousel/css/owl.carousel.css");
?>

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
					<?
						if($valueSlider["URL"]){
							$url = $valueSlider["URL"];
						}
						else{
							$url = 'javascript:void(0)';
						}
					?>
					<a href="<?=$url?>">
						<img src='/upload/<?=$valueSlider["PICTURE"]["SUBDIR"]."/".$valueSlider["PICTURE"]["FILE_NAME"]?>' alt='<?=$valueSlider["NAME"]?>'>
					</a>
					
				</div>
				<?
			}
			?>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$('#banner').ready(function(){
				$('#banner').owlCarousel({
					loop:<?=(count($arResult["SLIDER"]) > 1 ? "true" : "false")?>,
					nav:true,
					navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
					dots:true,
					center:true,
					items:1,
					autoplay:true,
					autoplayTimeout:4000,
					autoplayHoverPause:true,
					smartSpeed:1000,
					animateOut: 'fadeOut'
				});
			});
		});
	</script>
	<?
}
?>
<div class="container">
<div class="main-actions">
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"actions_main",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "actions",
		"DETAIL_URL" => "/aktsii/#ELEMENT_CODE#/",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"DETAIL_PICTURE",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "7",
		"IBLOCK_TYPE" => "content",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"BANNER_LINK",1=>"BANNER_TITLE",2=>"BANNER_WIDTH",3=>"",),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?>
</div>


    <!-- <h1 class="hide-h1"><?$APPLICATION->ShowTitle()?></h1> -->
    <?
    // include area #1
    /*$APPLICATION->IncludeComponent(
    	"bitrix:main.include",
    	"",
    	Array(
    		"AREA_FILE_SHOW" => "file",
    		"AREA_FILE_SUFFIX" => "inc",
    		"COMPONENT_TEMPLATE" => ".default",
    		"EDIT_TEMPLATE" => "",
    		"PATH" => "/include/main1.php"
    	)
    );*/?>

<?foreach($arResult['BLOCKS_SORT'] as $sortKey => $sort){?>
	<?
		switch($sortKey){
			case 'SLIDER1':
			?>
				<?

				// SLIDER №1
				if($arParams["SHOW_SLIDER1"]=="Y" and is_array($arResult["SLIDER1"]["ITEMS"])){
				?>
					<div class="row">
						<div class="col-md-12 main_tab_block">
							<div class="title"><span><?=$arResult["SLIDER1"]["TITLE"]?></span></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 main_tab_block">
							<div id="main_small_slider1" class="owl-carousel owl-theme">
							<?
							foreach ($arResult["SLIDER1"]["ITEMS"] as $keyNew => $valueNew) {
								// пометим отложенные товары
								$favorite = false;
								if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}

								?>
								<div class="item item_kart">
									<div class="img">
                                        <?
                                        if(empty($valueNew["PICTURE"])){
                                            $valueNew["PICTURE"] = $templateFolder."/images/no_photo.png";
                                        }
                                        ?>
										<img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
										<div class="item_mark">
											<?
												// скидка
												if(intval($valueNew['DISCOUNT_PERCENT'])>0){
													?>
													<div class="item-discount">
														<!-- <svg>
															<polygon points="0 0 34 0 34 19 17 30 0 19"/>
														</svg> -->
														<span>-<?=$valueNew['DISCOUNT_PERCENT']?>%</span>
													</div>
													<?
												}
                                                // скидка из свойства
                                                elseif(in_array($valueNew["DISCOUNT"],array('Y','y','Да','да','true'))){
													?>
													<div class="item-discount-prop" title="<?=GetMessage("Discounts")?>">
														<!-- <svg>
															<polygon points="0 0 34 0 34 19 17 30 0 19"/>
														</svg> -->
														<span><?=GetMessage("Discount")?></span>
													</div>
													<?
												}
												// хит продаж
												if(in_array($valueNew["BESTSELLER"],array('Y','y','Да','да','true'))){
													?>
													<div class="item-bestseller">
														<!-- <svg>
															<polygon points="0 0 34 0 34 19 17 30 0 19"/>
														</svg> -->
													   <span> <?=GetMessage("Bestseller")?></span>
													</div>
													<?
												}
											?>
										</div>
									</div>
									<div class="title-news">
										<span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
										<!-- <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span> -->
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

					<script>
						$(document).ready(function() {
							 $('#main_small_slider1').ready(function(){
								$('#main_small_slider1').owlCarousel({
									loop:<?=(count($arResult["SLIDER1"]["ITEMS"]) > 1 ? "true" : "false")?>,
									nav:true,
									navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
									margin:5,
									dots:false,
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
								$('#main_small_slider1 .owl-item .img').height($('#main_small_slider1 .owl-item .img').width());
							});
							$(window).resize(function(){
								$('#main_small_slider1 .owl-item .img').height($('#main_small_slider1 .owl-item .img').width());
							});
						});
					</script>
				<?
				}
				?>

				<?
				// include area #2
				/*$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"COMPONENT_TEMPLATE" => ".default",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/main2.php"
					)
				);*/?>
	             <?
				break;
			case 'SLIDER2':
			?>
				<?
				// SLIDER #2
				if($arParams["SHOW_SLIDER2"]=="Y" and is_array($arResult["SLIDER2"]["ITEMS"])){

					// пометим отложенные товары
					$favorite = false;
					if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}
					?>
						<div class="row">
							<div class="col-md-12 main_tab_block">
								<div class="title"><span><?=$arResult["SLIDER2"]["TITLE"]?></span></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 main_tab_block" >
								<div id="main_small_slider2" class="owl-carousel owl-theme">
								<?
								foreach ($arResult["SLIDER2"]["ITEMS"] as $keyNew => $valueNew) {
									// пометим отложенные товары
									$favorite = false;
									if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}
									?>
									<div class="item item_kart">
										<div class="img">
                                            <?
                                            if(empty($valueNew["PICTURE"])){
                                                $valueNew["PICTURE"] = $templateFolder."/images/no_photo.png";
                                            }
                                            ?>
                                            <img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
											<div class="item_mark">
                                                <?
                                                // скидка
                                                if(intval($valueNew['DISCOUNT_PERCENT'])>0){
                                                    ?>
                                                        <div class="item-discount">
                                                            <!-- <svg>
                                                                <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                            </svg> -->
                                                            <span>-<?=$valueNew['DISCOUNT_PERCENT']?>%</span>
                                                        </div>
                                                    <?
                                                }
                                                // скидка из свойства
                                                elseif(in_array($valueNew["DISCOUNT"],array('Y','y','Да','да','true'))){
                                                    ?>
                                                    <div class="item-discount-prop" title="<?=GetMessage("Discounts")?>">
                                                        <!-- <svg>
                                                        <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                    </svg> -->
                                                    <span><?=GetMessage("Discount")?></span>
                                                </div>
                                                <?
                                                }
                                                // новинка
                                                if(in_array($valueNew["NEWPRODUCT"],array('Y','y','Да','да','true'))){
                                                ?>
                                                    <div class="item-newproduct" title='<?=GetMessage("New")?>'>
                                                        <!-- <svg>
                                                                <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                        </svg> -->
                                                          <span><?=GetMessage("New")?></span>
                                                    </div>
                                                <?
                                                }
                                                // хит продаж
                                                if(in_array($valueNew["BESTSELLER"],array('Y','y','Да','да','true'))){
                                                    ?>
                                                    <div class="item-bestseller">
                                                        <!-- <svg>
                                                            <polygon points="0 0 34 0 34 19 17 30 0 19"/>
                                                        </svg> -->
                                                       <span> <?=GetMessage("Bestseller")?></span>
                                                    </div>
                                                    <?
                                                }
												?>
											</div>
										</div>
										<div class="title-news">
											<span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
											<!-- <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span> -->
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

						<script>
							$(document).ready(function() {
								 $('#main_small_slider2').ready(function(){
									$('#main_small_slider2').owlCarousel({
										loop:<?=(count($arResult["SLIDER2"]["ITEMS"]) > 1 ? "true" : "false")?>,
										nav:true,
										navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
										dots:false,
										margin:5,
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
									$('#main_small_slider2 .owl-item .img').height($('#main_small_slider2 .owl-item .img').width());
								});
								$(window).resize(function(){
									$('#main_small_slider2 .owl-item .img').height($('#main_small_slider2 .owl-item .img').width());
								});
							});
						</script>
				   <?
				}
				?>


			<?
				break;
			case 'SLIDER3':
			?>
				<?
				// SLIDER #3
				if($arParams["SHOW_SLIDER3"]=="Y" and is_array($arResult["SLIDER3"]["ITEMS"])){
					?>
						<div class="row">
							<div class="col-md-12 main_tab_block">
								<div class="title"><span><?=$arResult["SLIDER3"]["TITLE"]?></span></div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 main_tab_block">
								<div id="main_small_slider3" class="owl-carousel owl-theme">
								<?
								foreach ($arResult["SLIDER3"]["ITEMS"] as $keyNew => $valueNew) {
									// пометим отложенные товары
									$favorite = false;
									if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}
									?>
									<div class="item item_kart">
										<div class="img">
                                            <?
                                            if(empty($valueNew["PICTURE"])){
                                                $valueNew["PICTURE"] = $templateFolder."/images/no_photo.png";
                                            }
                                            ?>
											<img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
											<div class="item_mark">
                                                <?
                                                // скидка
                                                if(intval($valueNew['DISCOUNT_PERCENT'])>0){
                                                    ?>
                                                        <div class="item-discount">
                                                            <!-- <svg>
                                                                <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                            </svg> -->
                                                            <span>-<?=$valueNew['DISCOUNT_PERCENT']?>%</span>
                                                        </div>
                                                    <?
                                                }
                                                // скидка из свойства
                                                elseif(in_array($valueNew["DISCOUNT"],array('Y','y','Да','да','true'))){
                                                    ?>
                                                    <div class="item-discount-prop" title="<?=GetMessage("Discounts")?>">
                                                        <!-- <svg>
                                                        <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                    </svg> -->
                                                    <span><?=GetMessage("Discount")?></span>
                                                </div>
                                                <?
                                                }
                                                // новинка
                                                if(in_array($valueNew["NEWPRODUCT"],array('Y','y','Да','да','true'))){
                                                ?>
                                                    <div class="item-newproduct" title='<?=GetMessage("New")?>'>
                                                        <!-- <svg>
                                                                <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                        </svg> -->
                                                          <span><?=GetMessage("New")?></span>
                                                    </div>
                                                <?
                                                }
                                                // хит продаж
                                                if(in_array($valueNew["BESTSELLER"],array('Y','y','Да','да','true'))){
                                                    ?>
                                                    <div class="item-bestseller">
                                                        <!-- <svg>
                                                            <polygon points="0 0 34 0 34 19 17 30 0 19"/>
                                                        </svg> -->
                                                       <span> <?=GetMessage("Bestseller")?></span>
                                                    </div>
                                                    <?
                                                }
                                                ?>
											</div>
										</div>
										<div class="title-news">
											<span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
											<!-- <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span> -->
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
						<script>
							$(document).ready(function() {
								 $('#main_small_slider3').ready(function(){
									$('#main_small_slider3').owlCarousel({
										loop:<?=(count($arResult["SLIDER3"]["ITEMS"]) > 1 ? "true" : "false")?>,
										nav:true,
										navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
										margin:5,
										dots:false,
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
									$('#main_small_slider3 .owl-item .img').height($('#main_small_slider3 .owl-item .img').width());
								});
								$(window).resize(function(){
									$('#main_small_slider3 .owl-item .img').height($('#main_small_slider3 .owl-item .img').width());
								});
							});
						</script>
				   <?
				}
				?>


				<?
				// include area #3
				/*$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					Array(
						"AREA_FILE_SHOW" => "file",
						"AREA_FILE_SUFFIX" => "inc",
						"COMPONENT_TEMPLATE" => ".default",
						"EDIT_TEMPLATE" => "",
						"PATH" => "/include/main3.php"
					)
				);*/?>
			<?
				break;
			case 'BANNERS':
			?>
				<?
				// BANNERS
				if($arParams["SHOW_BANNERS"] == "Y" and is_array($arResult["BANNERS"]["ITEMS"])){
				?>
					<div class="row">
						<div class="col-md-12">
							<div class="title"><span><?=$arResult["BANNERS"]["TITLE"]?></span></div>
						</div>
					</div>
					<div class="row">
						<?
						$width = 0;
						$height = !empty($arResult['BANNERS']['HEIGHT']) ? $arResult['BANNERS']['HEIGHT'] : '375';
						foreach($arResult["BANNERS"]["ITEMS"] as $arItem):
							?>
								<?$picture = !empty($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE'] : $arItem['DETAIL_PICTURE'];?>
								<div class="col-md-<?=$arItem["BANNER_WIDTH"]?> col-sm-6 col-xs-12 main-block-banner" style="background-image:url(<?=$picture?>);height: <?=$height?>px;">
									<a href="<?=(!empty($arItem['BANNER_LINK'])) ? $arItem['BANNER_LINK'] : 'javascript:void(0)'?>" class="usbanner">
										<?if(!empty($arItem['BANNER_TITLE'])){?>
											<p class="banner-name"><?=$arItem['BANNER_TITLE']?></p>
										<?}?>
									</a>
								</div>
							<?
						endforeach;
						?>
					</div>
				<?
				}
				?>
			<?
				break;
            case 'TAB_BLOCK':
                ?>
                <div class="row">
                  <div class="col-md-12 main_tab_block">
                    <ul class="nav nav-tabs" style="display:none;">
                            <?$is_active = false;?>
                            <?foreach ($arResult['TAB_BLOCK']['SORTS'] as $key => $sort) {?>
                                <?
                                $tabClass = "";
                                if($is_active === false){
                                    $is_active = $key;
                                    $tabClass = ' class="active"';
                                }
                                ?>
                                <li<?=$tabClass?>><a href="#tab_<?=$key?>" data-toggle="tab"><?=$arResult['TAB_BLOCK']['TABS'][$key]['TITLE']?></a></li>
                            <?}?>
                        </ul>
                        <div class="tab-content">
                            <?foreach ($arResult['TAB_BLOCK']['SORTS'] as $key => $sort) {?>
                                <div id="tab_<?=$key?>" class="tab-pane fade in<?=($is_active == $key ? " active" : "")?>">
                                    <div id="main_tab_<?=$key?>" class="owl-carousel owl-theme">
                                        <?foreach ($arResult['TAB_BLOCK']['TABS'][$key]["ITEMS"] as $keyNew => $valueNew) {
                                            // пометим отложенные товары
                                            $favorite = false;
                                            if($arDelay[$valueNew["ID"]]=="Y"){$favorite = true;}
                                            ?>
                                            <div class="item item_kart"><!-- col-md-3  -->
                                                <div class="img">
                                                    <?
                                                    if(empty($valueNew["PICTURE"])){
                                                        $valueNew["PICTURE"] = $templateFolder."/images/no_photo.png";
                                                    }
                                                    ?>
                                                    <img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
                                                    <div class="item_mark">
                                                        <?
                                                        // скидка
                                                        if(intval($valueNew['DISCOUNT_PERCENT'])>0){
                                                            ?>
                                                                <div class="item-discount">
                                                                    <!-- <svg>
                                                                        <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                                    </svg> -->
                                                                    <span>-<?=$valueNew['DISCOUNT_PERCENT']?>%</span>
                                                                </div>
                                                            <?
                                                        }
                                                        // скидка из свойства
                                                        elseif(in_array($valueNew["DISCOUNT"],array('Y','y','Да','да','true'))){
                                                            ?>
                                                            <div class="item-discount-prop" title="<?=GetMessage("Discounts")?>">
                                                                <!-- <svg>
                                                                <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                            </svg> -->
                                                            <span><?=GetMessage("Discount")?></span>
                                                        </div>
                                                        <?
                                                        }
                                                        // новинка
                                                        if(in_array($valueNew["NEWPRODUCT"],array('Y','y','Да','да','true'))){
                                                        ?>
                                                            <div class="item-newproduct" title='<?=GetMessage("New")?>'>
                                                                <!-- <svg>
                                                                        <polygon points="0 0 34 0 34 19 17 30 0 19" fill="#f46c60"/>
                                                                </svg> -->
                                                                  <span><?=GetMessage("New")?></span>
                                                            </div>
                                                        <?
                                                        }
                                                        // хит продаж
                                                        if(in_array($valueNew["BESTSELLER"],array('Y','y','Да','да','true'))){
                                                            ?>
                                                            <div class="item-bestseller">
                                                                <!-- <svg>
                                                                    <polygon points="0 0 34 0 34 19 17 30 0 19"/>
                                                                </svg> -->
                                                               <span> <?=GetMessage("Bestseller")?></span>
                                                            </div>
                                                            <?
                                                        }
        												?>
                                                    </div>
                                                </div>
                                                <div class="title-news">
                                                    <span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>38 ? substr($valueNew["NAME"],0,38)."...":$valueNew["NAME"])?></span>
                                                    <!-- <span class="articul"><?=GetMessage("SKU")?>: <?=$valueNew["ARTICLE"]?></span> -->
                                                    <?if(intval($valueNew['DISCOUNT_PERCENT'])>0){?>
                                                        <span class="price sale"><?=$valueNew["PRICE_FORMAT"]?></span>
                                                        <span class="price sale-new"><?=number_format(round($valueNew["PRICE"] - $valueNew["PRICE"]*intval($valueNew['DISCOUNT_PERCENT'])/100), 0, '.', ' ' ).'р.';?></span>
                                                        <?}else{?>
                                                            <span class="price"><?=$valueNew["PRICE_FORMAT"]?></span>
                                                            <?}?>
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
                                            <script>
                                            $(document).ready(function() {
                                                <?if($is_active == $key){?>
                                                    // $('#main_tab_<?=$key?>').ready(function(){
                                                    $('#main_tab_<?=$key?>').owlCarousel({
                                                        loop:<?=(count($arResult['TAB_BLOCK']['TABS'][$key]["ITEMS"]) > 1 ? "true" : "false")?>,
                                                        nav:true,
                                                        navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
                                                        dots:false,
                                                        margin:5,
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
                                                    $('#main_tab_<?=$key?> .owl-item .img').height($('#main_tab_<?=$key?> .owl-item .img').width());
                                                    // });
                                                    $(window).resize(function(){
                                                        $('#main_tab_<?=$key?> .owl-item .img').height($('#main_tab_<?=$key?> .owl-item .img').width());
                                                    });
                                                    <?
                                                }
                                                ?>

                                                $('a[data-toggle="tab"][href="#tab_<?=$key?>"]').on('shown.bs.tab', function (e) {
                                                    // $('#main_tab_<?=$key?>').ready(function(){
                                                    $('#main_tab_<?=$key?>').owlCarousel({
                                                        loop:<?=(count($arResult['TAB_BLOCK']['TABS'][$key]["ITEMS"]) > 1 ? "true" : "false")?>,
                                                        nav:true,
                                                        navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
                                                        dots:false,
                                                        margin:5,
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
                                                    $('#main_tab_<?=$key?> .owl-item .img').height($('#main_tab_<?=$key?> .owl-item .img').width());
                                                    // });
                                                    $(window).resize(function(){
                                                        $('#main_tab_<?=$key?> .owl-item .img').height($('#main_tab_<?=$key?> .owl-item .img').width());
                                                    });
                                                });
                                            });
                                            </script>
                                        </div>
                                        <?
                                    }?>
                                </div>
                            </div>
                        </div>
                        <?
                        break;

                    case 'ADDITIONAL_SLIDER':
                    // slider output
                    if(count($arResult["ADDITIONAL_SLIDER"])>0)
                    {
                        ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="additional_slider" class="owl-carousel owl-theme">
                                    <?
                                    foreach ($arResult["ADDITIONAL_SLIDER"] as $keySlider => $valueSlider) {
                                        ?>
                                        <div class="item item_kart">
                                            <img src='/upload/<?=$valueSlider["PICTURE"]["SUBDIR"]."/".$valueSlider["PICTURE"]["FILE_NAME"]?>' alt='<?=$valueSlider["NAME"]?>'>
                                            <?=($valueSlider["URL"] ? "<a href='".$valueSlider["URL"]."'>":"")
                                                .$valueSlider["TITLE"]
                                                .($valueSlider["URL"]
                                                    ? ($valueSlider["TITLE"] ? "<span>&gt;</span>" : "")."</a>"
                                                    :"")?>
                                        </div>
                                        <?
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#additional_slider').ready(function(){
                                    $('#additional_slider').owlCarousel({
                                        loop:<?=(count($arResult["ADDITIONAL_SLIDER"]) > 1 ? "true" : "false")?>,
                                        nav:true,
                                        navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
                                        dots:false,
                                        center:true,
                                        items:1,
                                        autoplay:true,
                                        autoplayTimeout:4000,
                                        autoplayHoverPause:true,
                                        smartSpeed:1000,
                                        animateOut: 'fadeOut'
                                    });
                                });
                            });
                        </script>
                      <?
                    }
                    ?>
                    <div class="container">
                    <?
                break;

                // VIEVED
                case "VIEVED":
                    ?>
                    <span id='viewedajax'>...</span>

                    <script type="text/javascript">
                        $(document).ready(function(){
                            $.ajax({
                                url: "/ajax/viewed.php",
                                cache: false,
                                success: function(viewed){
                                    $("#viewedajax").html(viewed);
                                    $('#viewed').owlCarousel({
                                        loop: false,
                                        nav:true,
                                        navText: ['<i class="fa fa-chevron-left" aria-hidden="true"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
                                        dots:false,
                                        margin:5,
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
                                }
                            });
                        });
                  </script>
                  <?
                  break;
		}
	?>
<?}?>
