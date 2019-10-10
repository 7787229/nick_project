<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?

if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>

<?/*if($arResult["AUTH_SERVICES"]){?>
	<div class='social_services_block'>
		<?
		$APPLICATION->IncludeComponent(
			"bitrix:socserv.auth.form",
			"",
			array(
				"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
				"AUTH_URL"=>$arResult["AUTH_URL"],
				"POST"=>$arResult["POST"],
				"POPUP"=>"Y",
				"SUFFIX"=>"form",
			),
			$component,
			array("HIDE_ICONS"=>"Y")
		);
		?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:socserv.auth.form",
			"icons_",
			array(
				"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
				"SUFFIX"=>"form",
			),
			$component,
			array("HIDE_ICONS"=>"Y")
		);?>
	</div>
<?}*/?>

<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" id='authorized__form'>
<?
    if($arResult["BACKURL"] <> ''){
        ?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?
    }

	unset($arResult["POST"]["register_submit_button"]); // хвосты из формы регистрации
    foreach ($arResult["POST"] as $key => $value){
        ?><input type="hidden" name="<?=$key?>" value="<?=$value?>" /><?
    }

    ?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />

	<div class='input_row'>
		<input
			placeholder="Логин"
			type="text"
			id='auth_login'
			data-required="required"
			name="USER_LOGIN"
			maxlength="50"
			value="<?=$arResult["USER_LOGIN"]?>"
			size="17"
		/>
	</div>
	<div class='input_row'>
		<input
			placeholder='Пароль'
			type="password"
			data-required="required"
			name="USER_PASSWORD"
			maxlength="50"
			size="17"
			autocomplete="off"
		/>
	</div>

	<div class='input_row'>
		<a class="forgot-link" href="javascript:void(0);" onclick='$("a[href=\"#forgot\"]").tab("show");' rel="nofollow">
			<?=GetMessage("AUTH_FORGOT_PASSWORD_2")?>
		</a>
	</div>

	<?if ($arResult["CAPTCHA_CODE"]){?>
		<div class='input_row captcha'>
			<p>
				<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
			</p>
			<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
			<input type="text" name="captcha_word" maxlength="50" value="" />
		</div>
	<?}?>

	<div class='input_row'>
		<button name="Login" id='auth_login_submit' type='submit' value='<?=GetMessage("AUTH_LOGIN_BUTTON")?>'><?=GetMessage("AUTH_LOGIN_BUTTON")?></button>
	</div>
</form>

<script>
	$(document).ready(function() {
		if($("#authorized__form .errortext").html() || $("#authorized__form .success").html()){
			$("#auth_and_register").modal("show");
			$("a[href=\"#auth\"]").tab("show");
		}

		$('#authorized__form [data-required="required"]').on('blur', function(){
			if($(this).val() == ''){
				$(this).addClass('bad_value');
			}else{
				$(this).removeClass('bad_value');
			}
		});

		// $('#authorized__form').keydown(function(event){
		// 	if(event.keyCode == 13) {
		// 		event.preventDefault();
		// 		return false;
		// 	}
		// });

		<?if($arResult['ERROR']){?>
			$('#auth_and_register').modal('show');
			$("a[href=\"#auth\"]").tab("show");
		<?}?>
	});
 </script>
