<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>

	<?if($USER->IsAuthorized()){?>

	<p class='success'><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>

	<?}else{?>

		<?/*$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"tab_modal_short",
			Array(
				"COMPOSITE_FRAME_MODE" => "A",
				"COMPOSITE_FRAME_TYPE" => "AUTO",
				"FORGOT_PASSWORD_URL" => "/auth/forgot/",
				"PROFILE_URL" => "/personal/profile/",
				"REGISTER_URL" => "/auth/register/",
				"SHOW_ERRORS" => "Y"
			)
		);*/?>

	<?
	if (count($arResult["ERRORS"]) > 0){
		foreach ($arResult["ERRORS"] as $key => $error){
			if (intval($key) == 0 && $key !== 0){
				$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);
			}
		}

		?>
		<ul class="errortext">
		<?

		foreach ($arResult["ERRORS"] as $errorValue) {
			?>
				<li><?=$errorValue?></li>
			<?
		}
		?>
		</ul>
		<?


		}elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"){
			?><p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p><?
		}
	?>

<form method="POST" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" id='registration__form'>

		<?
		if($arResult["BACKURL"] <> '')
		{
			?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?
		}
		?>
		<input type="hidden" name="REGISTER[LOGIN]" value="<?=md5(time())?>">

		<div class='input_row'>
			<input
				type="text"
				id='reg_name'
				name="REGISTER[NAME]"
				value="<?=$arResult["VALUES"]["NAME"]?>"
				placeholder='<?echo GetMessage("REGISTER_FIELD_NAME")?>'
				data-required="required"
			>
		</div>
		<div class='input_row'>
			<input
				type="email"
				id='reg_email'
				name="REGISTER[EMAIL]"
				value="<?=$arResult["VALUES"]["EMAIL"]?>"
				placeholder='<?echo GetMessage("REGISTER_FIELD_EMAIL")?>'
				data-required="required"
			>
		</div>

		<?foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>
			<?if($FIELD == "LOGIN" or $FIELD == "EMAIL" or $FIELD == "NAME") continue;?>
			<?if($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true):?>
				<div class='input_row'>
					<span><?echo GetMessage("main_profile_time_zones_auto")?><?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><?/*<span class="starrequired">*</span>*/?><?endif?></span>

					<select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
						<option value=""><?echo GetMessage("main_profile_time_zones_auto_def")?></option>
						<option value="Y"<?=$arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_yes")?></option>
						<option value="N"<?=$arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_no")?></option>
					</select>
				</div>
				<div class='input_row'>
					<span><?echo GetMessage("main_profile_time_zones_zones")?></span>
					<select name="REGISTER[TIME_ZONE]"<?if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"'?>>
						<?foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
							<option value="<?=htmlspecialcharsbx($tz)?>"<?=$arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>><?=htmlspecialcharsbx($tz_name)?></option>
						<?endforeach?>
					</select>
				</div>
			<?else:?>
				<div class='input_row'>
					<?if($FIELD != 'PASSWORD' && $FIELD != 'CONFIRM_PASSWORD'){?>
						<span><?=GetMessage("REGISTER_FIELD_".$FIELD)?>:<?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><?/*<span class="starrequired">*</span>*/?><?endif?></span>
					<?}?>
					<?$required = ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? ' data-required="required"' : '');?>

					<?switch ($FIELD){
						case "PASSWORD":
							?>
								<input
									size="30"
									id='input_passwd'
									type="password"
									name="REGISTER[<?=$FIELD?>]"
									value="<?=$arResult["VALUES"][$FIELD]?>"
									placeholder="<?=GetMessage("REGISTER_FIELD_".$FIELD)?>"
									autocomplete="off" class="bx-auth-input"
									<?=$required?>
								/>
								<span id='show_hide_passwd' onclick='togglePasswdEye(this)' class='fa fa-eye'></span> <!-- fa-eye-slash -->
							<?
							break;
						case "CONFIRM_PASSWORD":
							?>
								<input
									size="30"
									id='input_confirm_passwd'
									type="password"
									name="REGISTER[<?=$FIELD?>]"
									value="<?=$arResult["VALUES"][$FIELD]?>"
									placeholder="<?=GetMessage("REGISTER_FIELD_".$FIELD)?>"
									autocomplete="off" class="bx-auth-input"
									<?=$required?>
								/>
							<?
							break;
						case "PERSONAL_GENDER":
							?><select name="REGISTER[<?=$FIELD?>]"<?=$required?>>
								<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
								<option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
								<option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
							</select><?
							break;

						case "PERSONAL_COUNTRY":
						case "WORK_COUNTRY":
							?><select name="REGISTER[<?=$FIELD?>]"<?=$required?>><?
							foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value)
							{
								?><option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
							<?
							}
							?></select><?
							break;

						case "PERSONAL_PHOTO":
						case "WORK_LOGO":
							?><input size="30" type="file" name="REGISTER_FILES_<?=$FIELD?>" <?=$required?>/><?
							break;

						case "PERSONAL_NOTES":
						case "WORK_NOTES":
							?><textarea cols="30" rows="5" name="REGISTER[<?=$FIELD?>]"<?=$required?>><?=$arResult["VALUES"][$FIELD]?></textarea><?
							break;
						default:
							if ($FIELD == "PERSONAL_BIRTHDAY"):?><small><?=$arResult["DATE_FORMAT"]?></small><br /><?endif;?>
							<input size="30" type="text" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" />
							<?
							if ($FIELD == "PERSONAL_BIRTHDAY")
								$APPLICATION->IncludeComponent(
									'bitrix:main.calendar',
									'',
									array(
										'SHOW_INPUT' => 'N',
										'FORM_NAME' => 'regform',
										'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
										'SHOW_TIME' => 'N'
									),
									null,
									array("HIDE_ICONS"=>"Y")
								);
							?>
							<?break;?>
					<?}?>
				</div>
			<?endif;?>

			<?// ********************* User properties ***************************************************?>
			<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
				<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
					<div class='input_row'>
					<?if($FIELD_NAME == 'UF_PERSONAL_INFO'){?>
							<input type='checkbox' name='<?=$FIELD_NAME?>' value='1'>
							<label for='<?=$FIELD_NAME?>'>
								Я подтверждаю согласие <a href="/confidentiality/">с политикой конфиденциальности</a> и даю согласие на обработку персональных данных.
								<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?>
							</label>
					<?}else{?>
							<span><?=$arUserField["EDIT_FORM_LABEL"]?>:<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?></span>
							<?$APPLICATION->IncludeComponent(
								"bitrix:system.field.edit",
								$arUserField["USER_TYPE"]["USER_TYPE_ID"],
								array(
									"bVarsFromForm" => $arResult["bVarsFromForm"],
									"arUserField" => $arUserField,
									"form_name" => "regform"
								),
								null,
								array("HIDE_ICONS"=>"Y")
							);?>
					<?}?>
					</div>
				<?endforeach;?>
			<?endif;?>
			<?// ******************** /User properties ***************************************************?>
		<?endforeach;?>
<?
/* CAPTCHA */
if ($arResult["USE_CAPTCHA"] == "Y")
{
	?>
		<p>
			<span><b><?=GetMessage("REGISTER_CAPTCHA_TITLE")?></b></span>
		</p>
		<p>
			<span></span>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
		</p>
		<p>
			<span><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></span>
			<input type="text" name="captcha_word" maxlength="50" value="" />
		</p>
	<?
}
/* !CAPTCHA */
?>

		<div id='try_phone' class='hide'>
			<p>
				<span><b>Подтверждение номера телефона</b></span>
			</p>
			<p>
				<input type="hidden" name="phone_sid" id='phone_sid' value="" />
				<span>
					Введите код подтверждения<sup><small>1</small></sup>:<br>
				</span>
				<input type="text" name="phone_word" id='phone_word' maxlength="4" value="" />
			</p>
			<div class='hide' id='try_phone__message' style='color: red'>Неверный код поддтверждения</div>
			<button name="try_submit_button" onclick='tryCode(this)' id='try_submit_button' type="button" value="Подтвердить телефон">Подтвердить телефон</button>
		</div>

		<button name="register_submit_button" id='reg_submit_button' type="submit" value="<?=GetMessage("AUTH_REGISTER")?>"><?=GetMessage("AUTH_REGISTER")?></button>
<?/*
		<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>

		<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>
*/?>
</form>


	<?
	$this->addExternalJS(DEFAULT_TEMPLATE.'/js/jquery.mask.min.js');
	?>
	<script>
		String.prototype.replaceAt = function(index, replacement) {
			return this.substr(0, index) + replacement+ this.substr(index + replacement.length);
		}

		function clearPhone(input){
			var output;

			output = input.replace(/[^0-9]/g, '').replaceAt(0, '7');

			return output;
		}

		function validateEmail(input){
			var output,
				rule = /^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i;

			output = rule.test(input);

			return output;
		}

		function validatePhone(input){
			var output,
				//rule = /^\d[\d\(\)\ -]{4,14}\d$/;
				rule = /^7\d{10}$/;

			clearInput = clearPhone(input);

			output = rule.test(clearInput);

			return output;
		}

		/* ПОКАЗАТЬ/СКРЫТЬ ПАРОЛЬ */
		function togglePasswdEye(athis){
			var delClass, addClass, inputType;
			if($(athis).hasClass('fa-eye-slash')){
				delClass = 'fa-eye-slash';
				addClass = 'fa-eye';
				inputType = 'password';
			}else{
				delClass = 'fa-eye';
				addClass = 'fa-eye-slash';
				inputType = 'text';
			}

			$(athis).removeClass(delClass);
			$(athis).addClass(addClass)

			$('#input_passwd').attr('type', inputType);
		}
		/* !ПОКАЗАТЬ/СКРЫТЬ ПАРОЛЬ */

		$(document).ready(function(){
			if(BX("reg_phone")){
				$('#reg_phone').mask('7 (000) 000-0000', {placeholder: "7 (___) ___-____", clearIfNotMatch: true});
			}

			$('#registration__form [data-required="required"]').on('blur', function(){
				if($(this).val() == ''){
					$(this).addClass('bad_value');
				}else{
					$(this).removeClass('bad_value');
				}
			});
		});
	</script>

<?}?>
    <script>
        $(document).ready(function() {
            if($("#registration__form .errortext").html() || $("#registration__form .success").html()){
				$("#auth_and_register").modal("show");
				$("a[href=\"#register\"]").tab("show");
            }
			<?if(count($arResult["ERRORS"]) > 0){?>
				$('#auth_and_register').modal('show');
				$("a[href=\"#register\"]").tab("show");
			<?}?>
        });
     </script>
