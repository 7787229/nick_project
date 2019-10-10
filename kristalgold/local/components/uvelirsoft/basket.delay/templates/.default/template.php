<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

    	<?$frame = $this->createFrame("delay")->begin();?>
            <a href="<?=$arParams["PATH_TO_DELAY"]?>" class="basket_href" title="<?=GetMessage("MY_DELAY")?>" rel="nofollow"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
            <a href="<?=$arParams["PATH_TO_DELAY"]?>" class="count" title="<?=GetMessage("MY_DELAY")?>" rel="nofollow" data-count='<?=(isset($arResult["QUANTITY"]) && $arResult["QUANTITY"] > 0) ? $arResult["QUANTITY"] : "0";?>'></a>
	<?$frame->end();?>
