<?
define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);
define("STATISTIC_SKIP_ACTIVITY_CHECK", true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
$cpt = new CCaptcha();
$captchaPass = COption::GetOptionString("main", "captcha_password", "");
if(strlen($captchaPass) <= 0){
	$captchaPass = randString(10);
	COption::SetOptionString("main", "captcha_password", $captchaPass);
}
$cpt->SetCodeCrypt($captchaPass);

$obUser = CUser::GetByID($USER->GetID());
$arUser = $obUser->Fetch();

$usernameValue	= (htmlspecialchars($_POST["reviewname"]) ? htmlspecialchars($_POST["reviewname"]):(htmlspecialchars(trim($USER->GetFirstName()." ".$USER->GetLastName()))));
$useremailValue = (htmlspecialchars($_POST["reviewmail"]) ? htmlspecialchars($_POST["reviewmail"]):(htmlspecialchars(trim($USER->GetEmail()))));
$userphoneValue = (htmlspecialchars($_POST["reviewphone"]) ? htmlspecialchars($_POST["reviewphone"]): ($arUser['PERSONAL_MOBILE']?$arUser['PERSONAL_MOBILE']:$arUser['PERSONAL_PHONE']) );
$usercityValue	= (htmlspecialchars($_POST["reviewcity"]) ? htmlspecialchars($_POST["reviewcity"]): $arUser['PERSONAL_CITY']);
$reviewValue	= (htmlspecialchars($_POST["reviewtext"]) ? htmlspecialchars($_POST["reviewtext"]):"");

$PRODUCT_ID = (!empty($_POST['product']) ? (int)$_POST['product'] : '');
?>
<div class='new-review-form'>
	<a href='javascript:void(0)' onclick="closeFormReview()" class="btn-close-review">закрыть [X]</a>
	<a name="formreview" id="formreview"></a>
	<h3 id='form_title'></h3>
    <form class="validate-this" name='reviewForm' id='reviewForm' method='POST' >
		<input type='hidden' name='AJAX' value='Y'>
		<input type='hidden' id='parent_element_id' name='parent' value=''>
		<input type='hidden' id='product_id' name='product' value='<?=$PRODUCT_ID?>'>
		<input type="hidden" id='show_review_items' name="show_review_items" value="N">
        <div class='row'>
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class='form-input'>
					<label for="star">Оцените качество обслуживания</label><br>
					<div class="stars">
						<input class="star star-5" id="quality_star-5" type="radio" name="quality_star" value="5"<?=((isset($_POST["quality_star"]) and $_POST["quality_star"]==5) ? " checked":"")?>/>
						<label class="star star-5" for="quality_star-5"></label>
						<input class="star star-4" id="quality_star-4" type="radio" name="quality_star" value="4"<?=((isset($_POST["quality_star"]) and $_POST["quality_star"]==4) ? " checked":"")?>/>
						<label class="star star-4" for="quality_star-4"></label>
						<input class="star star-3" id="quality_star-3" type="radio" name="quality_star" value="3"<?=((isset($_POST["quality_star"]) and $_POST["quality_star"]==3) ? " checked":"")?>/>
						<label class="star star-3" for="quality_star-3"></label>
						<input class="star star-2" id="quality_star-2" type="radio" name="quality_star" value="2"<?=((isset($_POST["quality_star"]) and $_POST["quality_star"]==2) ? " checked":"")?>/>
						<label class="star star-2" for="quality_star-2"></label>
						<input class="star star-1" id="quality_star-1" type="radio" name="quality_star" value="1"<?=((isset($_POST["quality_star"]) and $_POST["quality_star"]==1) ? " checked":"")?>/>
						<label class="star star-1" for="quality_star-1"></label>
					</div>
				</div>
				<!--выберите мышкой нужное кол-во звезд-->
			</div>
		</div>
		<div class='row'>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class='form-input'>
					<label for="reviewname">Имя</label><br>
					<input type='text' id='reviewname' name='reviewname' placeholder='Ваше имя' data-error="Укажите ваше имя!" value="<?=$usernameValue?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class='form-input'>
					<label for="reviewphone">Телефон</label><br>
					<input type='text' id='reviewphone' name='reviewphone' data-error="Укажите телефон!" value="<?=$userphoneValue?>">
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class='form-input'>
					<label for="reviewmail">Email</label><br>
					<input type='mail' id='reviewmail' name='reviewmail' placeholder='user@mail.ru'  data-error="Укажите e-mail!" value="<?=htmlspecialchars(trim($USER->GetEmail()))?>">
				</div>
			</div>
		</div>
        <div class='row'>
			<div class="col-md-12 col-sm-12">
				<div class='form-input'>
					<label for="reviewtext">Содержание отзыва</label><br>
					<textarea id='reviewtext' name='reviewtext' placeholder='' data-error="Вы не написали отзыв!"><?=$reviewValue?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class='pers-agreemen'>
				<input type="checkbox" id="callback_PERSONAL" name="checkbox_PERSONAL" value="0" data-error="Вы не подтвердили согласие на обработку персональных данных"><label for="callback_PERSONAL">Я подтверждаю согласие <a href="/confidentiality/">с политикой конфиденциальности</a> и даю согласие на обработку персональных данных.</label>
			</div>
		</div>
		<div class='row'>
			<div class="col-md-12 col-sm-12">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.file.input",
					"drag_n_drop",
					Array(
						"ALLOW_UPLOAD" => "I",
						"ALLOW_UPLOAD_EXT" => "",
						"INPUT_NAME" => "review_images",
						"MAX_FILE_SIZE" => "",
						"MODULE_ID" => "iblock",
						"MULTIPLE" => "Y"
					)
				);?>
			</div>
		</div>
		<? if (!$USER->IsAuthorized()) : ?>
        <div class='form-input'>
			<label>Введите текст с картинки</label>
		</div>
		<div class='form-input'>
			<input id="captcha_word" name="captcha_word" type="text" data-error="Укажите проверочный код!">
			<div id="captchaBlock">
			   <input id="captchaSid" type="hidden" name="captcha_code" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" />
			   <img id="captchaImg" src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialchars($cpt->GetCodeCrypt());?>" width="180" height="40" alt="CAPTCHA" />
			   <a href='javascript:void(0)' onclick="reloadCaptachaReview()" id="reloadCaptcha"><i class="fa fa-refresh" aria-hidden="true"></i></a>
			</div>
		</div>
		<?endif;?>
		<p></p>
        <input type="button" onclick='submitForm()' id='reviewFormSubmitButton' class="btn btn-review" name='btnsubmit' value='Отправить отзыв'>
    </form>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>
