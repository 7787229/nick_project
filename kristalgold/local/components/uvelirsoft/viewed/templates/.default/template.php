<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>

<?

$this->setFrameMode(true);

// VIEWED
if(is_array($arResult["VIEWED"]["ITEMS"]) and count($arResult["VIEWED_IDS"])>0){
	?>
		<div class="row">
			<div class="col-md-12 main_tab_block">
				<div class="title"><span><?=$arParams["TITLE"]?></span></div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 main_tab_block">
				<div id="viewed" class="owl-carousel owl-theme">
				<?
				foreach ($arResult["VIEWED_IDS"] as $keyNewID => $valueNewID) {
					$keyNew = $valueNewID;
					$valueNew = $arResult["VIEWED"]["ITEMS"][$keyNew];
					if(empty($valueNew)) continue;
					?>
					<div class="item item_kart">
						<div class="img">
							<?if(empty($valueNew["PICTURE"])){
								$valueNew["PICTURE"] = $templateFolder.'/images/no_photo.png';
							}?>
							<img src='<?=$valueNew["PICTURE"]?>' alt='<?=$valueNew["NAME"]?>'>
							<div class="item_mark">
							<?
								// показываем иконку акции
								if(in_array($valueNew["ACTIONS"],$arResult["AR_ACTIONS"]["IDS_LIST"])){
								?>
									<div class="item-actions">
										<!-- <svg>
											<polygon points="0 0 34 0 34 19 17 30 0 19" fill="#d9ad16"/>
										</svg> -->
										<span>
											<a href="<?=$arResult["AR_ACTIONS"][$valueNew["ACTIONS"]]["PROPERTY_BANNER_LINK_VALUE"]?>" title="<?=$arResult["AR_ACTIONS"][$valueNew["ACTIONS"]]["NAME"]?>">
												<img class='item-actions-img' alt="" src="<?=$arResult["AR_ACTIONS"][$valueNew["ACTIONS"]]["PREVIEW_PICTURE"]?>">
											</a>
										</span>
									</div>
								<?
								}
								
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
							<span class="item_kart-caption"><?=(strlen($valueNew["NAME"])>300 ? substr($valueNew["NAME"],0,300)."...":$valueNew["NAME"])?></span>
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
							<span class="favorite-<?=$valueNew["PRICE_ID"]?> news-favorite">
								<i class="fa fa-heart-o" aria-hidden="true"></i>
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

<script>
/*
BX.ready(function(){
	$('#viewed').ready(function() {
		$('#viewed').owlCarousel({
			loop:<?=(count($arResult["VIEWED"]["ITEMS"])>1 ? "true":"false")?>,
			nav:true,
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
	});
});
*/
</script>
