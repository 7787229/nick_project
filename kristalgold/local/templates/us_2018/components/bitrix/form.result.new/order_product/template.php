<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if ($arResult["isFormErrors"] == "Y"):?>
	<span class="error-mess"><?=$arResult["FORM_ERRORS_TEXT"];?></span>
	<script>
		showPopupOrderProduct();
	</script>
<?else:?>
	<span class="error-mess"></span>
<?endif;?>

<?if ($arResult["isFormNote"] != "Y")
{
?>
<?=$arResult["FORM_HEADER"]?>

<?// printvar('', $arParams, 0);
/***********************************************************************************
					form header
***********************************************************************************/
?>
<?if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y"){?>
	<?if ($arResult["isFormTitle"]){?>
		<h3><?=$arResult["FORM_TITLE"]?></h3>
	<?}?>
<?}?>

<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>

	<?//printvar("",$arResult["QUESTIONS"]);?>
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion){?>
<?//if($FIELD_SID == 'PERSONAL') continue;?>
		<p>
			<?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'hidden' && $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'checkbox'){?>
				<span><?=$arQuestion['CAPTION'];?><?if($arQuestion['REQUIRED'] == 'Y'){?><span class="starrequired">*</span><?}?></span><br>
			<?}?>
			<?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'checkbox' or $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'){
				echo $arQuestion["HTML_CODE"];
			} else {
				$form_name='form_'.$arQuestion['STRUCTURE'][0]['FIELD_TYPE']."_".$arQuestion['STRUCTURE'][0]['FIELD_ID'];
				$val = '';
				if ( is_array($arResult["arrVALUES"]) && count($arResult["arrVALUES"]) ) {
					$val = (isset($arResult["arrVALUES"][$form_name]) ? $arResult["arrVALUES"][$form_name] : '');
				} else {
					if ($USER->IsAuthorized()) {
						switch ($FIELD_SID) {
							case 'order_name':
								$val = trim($USER->GetFullName());
								break;
							case 'order_phone':
								$resUser = CUser::GetByID($USER->GetID());
								$arUser = $resUser->Fetch();
								$val = $arUser["PERSONAL_PHONE"];
								unset($arUser);
								unset($resUser);
								break;
							case 'order_email':
								$val = trim($USER->GetEmail());
								break;
						}
					}

					switch ($FIELD_SID) {
						case 'order_id_tovara':
							$val = $arParams['PRODUCT_ID'];
							break;
						case 'order_tovar_articul':
							$val = $arParams['PRODUCT_ARTICLE'];
							break;
						case 'order_tovar_name':
							$val = $arParams['PRODUCT_NAME'];
							break;
						case 'order_tovar_image':
							$val = $arParams['PRODUCT_PICTURE'];
							break;
						case 'order_link_to_tovar':
							$val = $arParams['PRODUCT_LINK'];
							break;
					}
				}
			?>
				<input
					id='<?=$FIELD_SID;?>'
					name='<?=$form_name?>'
					type='<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>'
					value='<?=$val?>'
					<?if($arQuestion['REQUIRED'] == 'Y'){?>
						onblur='tryInputValue_order($(this))'
						data-required="required"
					<?}?>
				/>
			<?}?>
		</p>
	<?}?>
	<p>
		<input class='btn btn-subscribe' onclick="trySubmit_order()" type="button" name="" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
		<input id='submitOrderProduct' class='btn btn-subscribe hide' <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
	</p>
<p>
<?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?>
</p>
<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
else{
	?>
	<div class='orderIsAdded'>
		<h4>
			<?=$arResult["FORM_NOTE"]?>
			<br><br>
			Наш менеджер свяжется с Вами в ближайшее время и ответит на все вопросы
		</h4>
	</div>
	<script>
		showPopupOrderProduct();
	</script>
	<?
}
?>
<script>
	$(document).ready(function(){
		$('#order_phone').mask('7 (000) 000-0000', {placeholder: "7 (___) ___-____", clearIfNotMatch: true});
	});

	function testEmail_order(email){
		var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

		return pattern.test(email);
	}

	// проверка инпутов, обязательных  для заполнения
	function tryInputValue_order(input){
		// проверяем конкретный инпут
		if(input){
			if(input.attr('type') == 'checkbox'){
				if(input.is(':checked')){
					input.removeClass('nullValue');
					return 0;
				}else{
					input.addClass('nullValue');
					return 1;
				}
			}else{
				if(input.val() == '' || typeof input.val() == undefined){
					input.addClass('nullValue');
					return 1;
				}else{
					if(input.attr('type') == "email"){
						if(testEmail_order(input.val())){
							input.removeClass('nullValue');
						}else{
							input.addClass('nullValue');
						}
					}
					return 0;
				}
			}
		}
		// проверяем все инпуты
		else{
			var arRequired = $('#popupOrderProduct [data-required="required"]'),
				count = 0;

			for(var i = 0; i < arRequired.length; i++){
				count += tryInputValue_order(arRequired.eq(i));
			}

			return count;
		}
	}

	function trySubmit_order(){
		var input = false,
			result = tryInputValue_order(input);

		if(result > 0){
			var message = 'Необходимо заполнить все обязательные поля!';
			$('#popupOrderProduct .error-mess').html(message);
		}else{
			$('#popupOrderProduct .error-mess').html('');

			$('#submitOrderProduct').trigger('click');
		}
	}
</script>
