<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?//printvar('',$arResult)?>
<?if (!empty($arResult)):?>

<div id="container-multilevel-menu2">
<ul id="horizontal-multilevel-menu2">

<div class="row">
	<div class="col-xs-2">
		<div class="close-icon" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="true">
			<i class="fa fa-times" aria-hidden="true"></i>
		</div>
	</div>
	<div class="col-xs-10">
		<?$APPLICATION->IncludeComponent(
		    "bitrix:main.include", 
		    ".default", 
		    array(
		        "AREA_FILE_SHOW" => "file",
		        "AREA_FILE_SUFFIX" => "inc",
		        "COMPONENT_TEMPLATE" => ".default",
		        "EDIT_TEMPLATE" => "standard.php",
		        "PATH" => "/include/logo.php"
		    ),
		    false
		);?>	
	</div>	
</div>
<?
$previousLevel = 0;

foreach($arResult as $arItem):?>
	<?//printvar('',$arItem['PARAMS']['PICTURE']);?>
	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<li class="li-parent <?/*if(!$arItem['PARAMS']['HAS_PICTURE']):?>not-has-picture<?endif;*/?>"><a href="<?=$arItem["LINK"]?>"  aria-haspopup="true" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?> "><span><?=$arItem["TEXT"]?></span><i class="fa fa-angle-right" aria-hidden="true"></i></a>
				<?//if($arItem['PARAMS']['HAS_PICTURE']):?><div class="inner-multilevel">
					<!--<span class="back-menu"><i class="fa fa-angle-left" aria-hidden="true"></i> Назад</span>-->
					<div class="inner-inner-multilevel" style='background-image: url(<?=$arItem['PARAMS']['PICTURE']?>)'><div class="container">
				<?//endif;?>
				<ul>
		<?else:?>
			<li class="li-parent<?if ($arItem["SELECTED"]):?> item-selected<?endif?><?if ($arItem["PARAMS"]["BOLD"]):?> bold<?endif?><?if($arItem['PARAMS']['HIDE4MOBILE']):?> hide_for_mobile<?endif;?>">
				<?if(!empty($arItem["LINK"])){?>
					<a href="<?=$arItem["LINK"]?>" class="parent" aria-haspopup="true" data-url="<?=$arItem['PARAMS']['PICTURE']?>"><? if($arItem['PARAMS']["ICON"]): ?> <img src="<?=$arItem['PARAMS']["ICON"]?>" class="icon"> <? endif; ?><span><?=$arItem["TEXT"]?></span><i class="fa fa-angle-right" aria-hidden="true"></i></a>
				<?}else{?>
					<a><span><?=$arItem["TEXT"]?></span><i class="fa fa-angle-right" aria-hidden="true"></i></a>
				<?}?>
				<div class="<?=($arItem["DEPTH_LEVEL"] >= 2 ? "two_colums" : "")?><?=($arItem['PARAMS']['HAS_SUBSECTIONS'] ? " subsections" : "")?>" ><ul>
		<?endif?>

	<?else:?>
	
			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li><a href="<?=$arItem["LINK"]?>" data-url="<?=$arItem['PARAMS']['PICTURE']?>" aria-haspopup="true" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><span><?=$arItem["TEXT"]?></span></a></li>
			<?else:?>
				<li <?if($arItem['PARAMS']['HIDE4MOBILE']):?>class='hide_for_mobile'<?endif;?>><a href="<?=$arItem["LINK"]?>" data-url="<?=$arItem['PARAMS']['PICTURE']?>" aria-haspopup="true" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><span><?=$arItem["TEXT"]?></span></a></li>
			<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></div></div></div></li>", ($previousLevel-1) );?>
<?endif?>

</ul>
<div class="menu-clear-left"></div>
</div>
<?endif?>



<script>

	$(document).ready(function(){
		$('#horizontal-multilevel-menu2 .inner-multilevel>.inner-inner-multilevel ul li a').hover(function(){
			if($(this).attr('data-url')){
				bgUrl=$(this).attr('data-url');
			} else{
				bgUrl=$(this).parents('.two_colums').siblings('.parent').attr('data-url');
			}
			//console.log(bgUrl);
			$(this).parents('.inner-inner-multilevel').children('.container').css({'background-image': 'url('+bgUrl+')'});
		},
		function(){	 
			//$(this).parents('.inner-inner-multilevel').css({'background-image': 'url('+$(this).attr('data-url')+')'});
		});
	});

$(document).ready(function(){
	if($(document).width()>767){
		$('.inner-multilevel .inner-inner-multilevel>ul>li>a').hover(
			function(){
				$('.hide-block-li').remove();
				$(this).parent('li').append('<div class="hide-block-li"></div>');
				if($('.inner-multilevel .inner-inner-multilevel>ul>li').children()){
					
				}
				$('.hide-block-li').fadeOut(900);
			}
			)
			$('.hide-block-li').hover(function(){
				$('.hide-block-li').fadeOut(300);
			}
		)
	}
});
	$(document).ready(function(){
		if($(document).width()<=767){
			$('.banner-mobile').on('click', function(){
				$('#horizontal-multilevel-menu2>.li-parent').first().children('.inner-multilevel').addClass('active');
			});
			$('.li-parent>a').removeAttr('href');
			$('#horizontal-multilevel-menu2 li').on('click', function(e){
				/*e.preventDefault();*/
				e.stopPropagation();
				$(this).toggleClass('active');
				$(this).children('.inner-multilevel').toggleClass('active');
				/*$(this).children('ul').toggleClass('active');*/
				$(this).children('.two_colums').toggleClass('active');
			});
			/*$('.back-menu').on('click', function(e){
				e.stopPropagation();
				$('#horizontal-multilevel-menu2 .active').last().removeClass('active');
			});*/
			$('.navbar-toggle').on('click', function(){
				$('#horizontal-multilevel-menu2 .inner-multilevel').removeClass('active');
				$('#horizontal-multilevel-menu2 ul').removeClass('active');
			});
			$('.close-icon').on('click', function(){
				$('#horizontal-multilevel-menu2 .inner-multilevel').removeClass('active');
				$('#horizontal-multilevel-menu2 ul').removeClass('active');
			});
		};
	});
</script>