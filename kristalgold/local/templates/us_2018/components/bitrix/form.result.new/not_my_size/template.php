<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if ($arResult["isFormErrors"] == "Y"):?>
	<span class="error-mess"><?=$arResult["FORM_ERRORS_TEXT"];?></span>
	<script>
		showPopupNoSize();
	</script>
<?else:?>
	<span class="error-mess"></span>
<?endif;?>
<?if ($arResult["isFormNote"] != "Y")
{
?>
<?
// добавляем идентификатор к тегу FORM
echo str_replace('<form', '<form id="'.$arResult['arForm']['SID'].'"',$arResult["FORM_HEADER"]);
?>

<?
/***********************************************************************************
					form header
***********************************************************************************/
?>
<?if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y"){?>
	<?if ($arResult["isFormTitle"]){?>
		<h3><?=$arResult["FORM_TITLE"]?></h3>
	<?}?>
<?}?>
<p>
	<label class='full_size'>
		Если в нашем каталоге Вам понравилось определённое украшение, но не оказалось нужного размера,
		или Вы хотели бы видеть его в другом металле, или в другом цвете золота, или с другими вставками,
		то Вы можете оставить свой заказ по телефону +7-499-257-47-11, +7-903-507-14-30.
	</label>
</p>
<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
	<input type="hidden" name='USE_AJAX' value='Y'>
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion){?>
		<p>
			<?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'hidden' && $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'checkbox'){?>
				<span><?=$arQuestion['CAPTION'];?><?if($arQuestion['REQUIRED'] == 'Y'){?><span class="starrequired">*</span><?}?></span><br>
			<?}?>
			<?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'checkbox' or $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'){
				echo $arQuestion["HTML_CODE"];
			}else{
				$form_name='form_'.$arQuestion['STRUCTURE'][0]['FIELD_TYPE']."_".$arQuestion['STRUCTURE'][0]['FIELD_ID'];
				$val = '';
				if ( is_array($arResult["arrVALUES"]) && count($arResult["arrVALUES"]) ) {
					$val = (isset($arResult["arrVALUES"][$form_name]) ? $arResult["arrVALUES"][$form_name] : '');
				} else {
					if ($USER->IsAuthorized()) {
						switch ($FIELD_SID) {
							case 'nms_name':
								$val = trim($USER->GetFullName());
								break;
							case 'nms_phone':
								$resUser = CUser::GetByID($USER->GetID());
								$arUser = $resUser->Fetch();
								$val = $arUser["PERSONAL_PHONE"];
								unset($arUser);
								unset($resUser);
								break;
							case 'nms_email':
								$val = trim($USER->GetEmail());
								break;
						}
					}
					switch ($FIELD_SID) {
						case 'nms_id_tovara':
							$val = $arParams['PRODUCT_ID'];
							break;
						case 'nms_tovar_articul':
							$val = $arParams['PRODUCT_ARTICLE'];
							break;
						case 'nms_tovar_name':
							$val = $arParams['PRODUCT_NAME'];
							break;
						case 'nms_tovar_image':
							$val = $arParams['PRODUCT_PICTURE'];
							break;
						case 'nms_link_to_tovar':
							$val = $arParams['PRODUCT_LINK'];
							break;
					}
				}
			?>
				<input
					id='<?=$FIELD_SID;?>'
					name='<?=$form_name;?>'
					type='<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE']?>'
					value='<?=$val?>'
					<?if($arQuestion['REQUIRED'] == 'Y'){?>
						onblur='tryInputValue_nms($(this))'
						data-required="required"
					<?}?>
				/>
			<?}?>
		</p>
	<?}?>
	<p>
		<input
			id='submitNoSize_try'
			class='btn btn-subscribe'
			type="button"
			name="web_form_submit-try"
			onclick='trySubmit_nms()'
			value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>"
		/>
		<input
			id='submitNoSize'
			class='btn btn-subscribe hide'
			<?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?>
			type="submit"
			name="web_form_submit"
			value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>"
		/>
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
		showPopupNoSize();
	</script>
	<?
}
?>
<script>
	$(document).ready(function(){
		$('#nms_phone').mask('7 (000) 000-0000', {placeholder: "7 (___) ___-____", clearIfNotMatch: true});
	});

	function testEmail_nms(email){
		var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

		return pattern.test(email);
	}

	// проверка инпутов, обязательных  для заполнения
	function tryInputValue_nms(input){
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
						if(testEmail_nms(input.val())){
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
			var arRequired = $('#popupRazmerNoSize [data-required="required"]'),
				count = 0;

			for(var i = 0; i < arRequired.length; i++){
				count += tryInputValue_nms(arRequired.eq(i));
			}

			return count;
		}
	}

	function trySubmit_nms(){
		var input = false,
			result = tryInputValue_nms(input);

		if(result > 0){
			var message = 'Необходимо заполнить все обязательные поля!';
			$('#popupRazmerNoSize .error-mess').html(message);
		}else{
			$('#popupRazmerNoSize .error-mess').html('');

			$('#submitNoSize').trigger('click');
		}
	}
</script>
