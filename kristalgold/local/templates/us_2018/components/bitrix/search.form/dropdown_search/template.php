<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);?>

<div class="search-form">
	<div id="search_btn"  class="search-form-btn">
		<i class='fa fa-search fa-2x'></i>
	</div>

	<div class="search-form-block hide">
		<form action="<?=$arResult["FORM_ACTION"]?>">
			<?$APPLICATION->IncludeComponent(
				"bitrix:search.suggest.input",
				"",
				array(
					"NAME" => "q",
					"VALUE" => "",
					"INPUT_SIZE" => 28,
					"DROPDOWN_SIZE" => 10,
				),
				$component, array("HIDE_ICONS" => "Y")
			);?>
			<button class='search-form-search' type='submit' name='s'>
				<i class='fa fa-search'></i>
			</button>
			<!-- <input name="s" type="submit" value="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>" /> -->
		</form>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#search_btn').on('click', function(){
			$('.search-form-block').toggleClass('hide');
		});
	});
</script>
