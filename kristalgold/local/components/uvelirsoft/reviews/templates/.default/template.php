<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$sComponentFolder = $this->__component->__path;

global $USER;

CJSCore::Init(array('fx', 'ajax', 'dd'));
$APPLICATION->AddHeadScript('/bitrix/js/main/file_upload_agent.js');
$APPLICATION->AddHeadScript(DEFAULT_TEMPLATE.'/components/bitrix/main.file.input/drag_n_drop/script.js');
$APPLICATION->AddHeadScript(DEFAULT_TEMPLATE.'/js/jquery.mask.min.js');
$APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE.'/components/bitrix/main.file.input/drag_n_drop/style.css');

$APPLICATION->AddHeadScript(DEFAULT_TEMPLATE.'/fancybox/jquery.fancybox.js');
$APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE.'/fancybox/jquery.fancybox.css');
	//$this->addExternalJS("/local/lib/maskinput/jquery.maskedinput.min.js"); // https://itchief.ru/lessons/javascript/input-mask-for-html-input-element
$ajax = $_POST['AJAX'];
?>
<div id="review-row">
	<?if($ajax == 'Y'){
		$APPLICATION->RestartBuffer();
	}?>
		<div class="catalog-block-header title_otzivi">Отзывы ( <?echo count($arResult["ITEMS"]);?> )</div>
		<div id='user_reviwe_or_comment_block'>

			<?
			$i = 1;
			foreach ($arResult["ITEMS"] as $arItem) {
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				if(htmlspecialchars($arItem["PREVIEW_TEXT"]))
				?>
				<div class='row review-block col-md-6<?=($i > $arParams["COUNT_PAGE"] && $_POST['show_review_items'] != 'Y' ? " hide" : "")?>' id='<?=$this->GetEditAreaId($arItem['ID']);?>'>
					<div class="col-md-12 review-personal-info">

						<div class="review-autor"><?=$arItem["PROPS"]["USER_NAME"]["VALUE"]?></div>
						<?/*<time datetime="<?=$arItem["DATE_CREATE_CHORT"]?>"><?=$arItem["DATE_CREATE_CHORT"]?></time>*/?>
						<time datetime="<?=$arItem["DATE_ACTIVE_FROM_CHORT"]?>"><?=$arItem["DATE_ACTIVE_FROM_CHORT"]?></time>
						<div class="review-stars">
							<img src="<?=SITE_TEMPLATE_PATH?>/images/stars.png" alt="Оценка">
							<div class="bg-active" style='width:<?=(20*$arItem["PROPS"]["RATING"]["VALUE"])?>%'></div>
						</div>
						<?
						$arStatus = array();
						if($arItem["ACTIVE"]=="N"){$arStatus[] = "Не активный";}
						if($arItem["PROPS"]["MODERATION"]["VALUE"]=="Да"){$arStatus[] = "На модерации";}
						?>
						<div class="review-status"><?=implode(" | ",$arStatus)?></div>
					</div>
					<div class='col-md-12 review-item' id='<?=$arParams["IBLOCK_ID"]?>_review_<?=$arItem["ID"]?>'>
						<?if(htmlspecialchars($arItem["PROPS"]["REVIEW_TITLE"]["VALUE"])){?>
							<div class='review-item-title'>
								<?=htmlspecialchars($arItem["PROPS"]["REVIEW_TITLE"]["VALUE"])?>
							</div>
						<?}?>
						<div class='review-item-text'>
							<?=$arItem["PREVIEW_TEXT"]?>
						</div>
						<?if(!empty($arItem['PROPS']['IMAGES'])){?>
							<div class='row_images'>
								<?foreach($arItem['PROPS']['IMAGES']['VALUE'] as $imageID){?>
									<?$src = CFile::GetPath($imageID);?>
									<div class='image'>
										<a data-fancybox="gallery_<?=$arItem['ID']?>" href='<?=$src?>'>
											<img src='<?=$src?>' alt='Фото <?=$imageID?>'>
										</a>
									</div>
								<?}?>
								<script>
									$(document).ready(function(){
										$('[data-fancybox="gallery__<?=$arItem['ID']?>"]').fancybox();
									});
								</script>
							</div>
						<?}?>
						<?if($arItem["DETAIL_TEXT"]){?>
							<blockquote class='review-item-replays'>
								<span>ответ с сайта:</span>
								<?=$arItem["DETAIL_TEXT"]?>
							</blockquote>
						<?}?>

						<?
						foreach ($arItem["REPLAYS"] as $arItemCH) {
							$this->AddEditAction($arItemCH['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItemCH["IBLOCK_ID"], "ELEMENT_EDIT"));
							$this->AddDeleteAction($arItemCH['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItemCH["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

							if(empty(htmlspecialchars($arItemCH["PREVIEW_TEXT"]))) continue;
							?>
							<div class='row review-block' id='<?=$this->GetEditAreaId($arItemCH['ID']);?>'>
								<div id='<?=$arParams["IBLOCK_ID"]?>_review_<?=$arItemCH["ID"]?>' class="col-md-12">
									<?=$arItemCH["PROPS"]["USER_NAME"]["VALUE"]?>
									<?/*<time datetime="<?=$arItemCH["DATE_CREATE_CHORT"]?>"><?=$arItemCH["DATE_CREATE_CHORT"]?></time>*/?>
									<time datetime="<?=$arItemCH["DATE_ACTIVE_FROM_CHORT"]?>"><?=$arItemCH["DATE_ACTIVE_FROM_CHORT"]?></time>
									<div class="review-stars">
										<img src="<?=SITE_TEMPLATE_PATH?>/images/stars.png" alt="Оценка">
										<div class="bg-active" style='width:<?=(20*$arItemCH["PROPS"]["RATING"]["VALUE"])?>%'></div>
									</div>
									<?
									$arStatus = array();
									if($arItemCH["ACTIVE"]=="N"){$arStatus[] = "Не активный";}
									if($arItemCH["PROPS"]["MODERATION"]["VALUE"]=="Да"){$arStatus[] = "На модерации";}
									?>
									<div class="review-status"><?=implode(" | ",$arStatus)?></div>
									<?if(htmlspecialchars($arItemCH["PROPS"]["REVIEW_TITLE"]["VALUE"])){?>
										<div class='review-item-title'>
											<?=htmlspecialchars($arItemCH["PROPS"]["REVIEW_TITLE"]["VALUE"])?>
										</div>
									<?}?>
									<div class='review-item-text'>
										<?=htmlspecialchars($arItemCH["PREVIEW_TEXT"])?>
									</div>
									<?if(!empty($arItemCH['PROPS']['IMAGES'])){?>
										<div class='row_images'>
											<?foreach($arItemCH['PROPS']['IMAGES']['VALUE'] as $imageID){?>
												<?$src = CFile::GetPath($imageID);?>
												<div class='image'>
													<a data-fancybox="gallery_<?=$arItemCH['ID']?>" href='<?=$src?>'>
														<img src='<?=$src?>' alt='Фото <?=$imageID?>'>
													</a>
												</div>
											<?}?>
											<script>
												$(document).ready(function(){
													$('[data-fancybox="gallery__<?=$arItemCH['ID']?>"]').fancybox();
												});
											</script>
										</div>
									<?}?>
									<div class='review-item-replay'>
										  <?=$arItemCH["DETAIL_TEXT"]?>
									</div>
								</div>
							</div>

						<?}?>
						<div id='replay_block_<?=$arItem["ID"]?>'></div>

					</div>
					<?/*?>
					<div class="col-md-2 favorite-item">
						<div class="favorite-item-like like_block_<?=$arItem["ID"]?>" onclick='reviewlike("l","<?=$arItem["ID"]?>")'>
							<i class="fa fa-thumbs-o-up"></i>
							<span class="count"><?=($arItem["PROPS"]["LIKES"]["VALUE"]!=="" ? $arItem["PROPS"]["LIKES"]["VALUE"]:0)?></span>
						</div>
						<div class="favorite-item-dislike like_block_<?=$arItem["ID"]?>" onclick='<?=($USER->IsAuthorized() ? "reviewlike(\"d\",\"".$arItem["ID"]."\")":"alert(\"Для установки такого голосования необходимо быть зарегистрированным пользователем!\")")?>'>
							<i class="fa fa-thumbs-o-down"></i>
							<span class="count"><?=($arItem["PROPS"]["DESLIKES"]["VALUE"]!=="" ? $arItem["PROPS"]["DESLIKES"]["VALUE"]:0)?></span>
						</div>
						<div class='replay-btn'><a data-parent="<?=$arItem["ID"]?>" class='replay-btn-link' onclick='addNewReviewAnswer(this);' href='javascript:void(0)'>ответить</a></div>
					</div>
					<?*/?>
				</div>
			<?$i++;?>
			<?}?>



			<script>
				function reloadCaptachaReview(){
				   $.getJSON('<?=$this->__folder?>/reload_captcha.php', function(data) {
					  $('#captchaImg').attr('src','/bitrix/tools/captcha.php?captcha_sid='+data);
					  $('#captchaSid').val(data);
				   });
				   return false;
				}

				function reviewlike(t,i){
					$.post('<?=$this->__folder?>/like.php', { t: t, i: i })
					  .done(function( data ) {
						$((t==="l" ? ".favorite-item-like":".favorite-item-dislike")+".like_block_"+i+" .count").html(data);
						$((t==="l" ? ".favorite-item-like":".favorite-item-dislike")+".like_block_"+i).attr('onclick', '' );
					  });

				}

			function addNewReview(athis){
				$(athis).css( "display", "none");

				var parent = '';

				showForm('replay_block_top', parent,"Новый отзыв");
			}

			function addNewReviewAnswer(athis){
				$('#makeReviewButton').css('display', '');

				var data = athis.dataset;
				var parent = data.parent
				console.log("replay to " + parent);

				showForm('replay_block_', parent, "Ответить");
			}

			function validateForm(){
				var form = BX("reviewForm"),
					inputs = form.querySelectorAll("[data-error]"),
					result = 0;

				clear(inputs, function() {
					checkIsEmpty(inputs);
				});

				function checkIsEmpty(inputs) {
					var isEmpty = false;

					for (var i = 0; i < inputs.length; i++) {
						var input = inputs[i];

						if (input.name == "checkbox_PERSONAL") {
							if(input.checked === false) {
								isEmpty = true;
								markInput(input);
							}
						}


						if (input.value.trim() === "") {
							isEmpty = true;
							markInput(input);
						}
					}

					if (!isEmpty) {
						result = 0;
					}else{
						result = 1;
					}
				}

				function markInput(input) {
					input.classList.add("reviewerror");
					var text = input.getAttribute("data-error");

					if (!text) return;

					var div = document.createElement("div");

					div.textContent = text;
					div.className = "error-text";
					input.parentNode.appendChild(div);
				}

				function clear(inputsItem, callback) {
					for (var i = 0; i < inputsItem.length; i++) {
						var input = inputsItem[i],
							parent = input.parentNode,
							message = parent.querySelector(".error-text");

						input.classList.remove("reviewerror");
						if (message) parent.removeChild(message);
				  }

				  if (callback) callback();
				}

				return result;
			}

			function submitForm(){
				if(validateForm() <= 0){
				 	//BX("reviewForm").submit();

					BX.ajax.post(
						"<?=$templateFolder?>/try_captcha.php",
						{
							captcha_word: $('#captcha_word').val(),
							captcha_code: $('#captchaSid').val()
						},
						function(data){
							if(data == 1){
								if($('#review-row .review-block.hide').length == 0){
									$('#show_review_items').val('Y');
								}

								BX.adjust(BX('h3_error'), {html: ""});
								BX.prepend(BX.create('INPUT', {'attrs':{name:'NEED_TO_TRY_CAPTCHA', value: 'N', type: 'hidden'}}), BX('reviewForm'));
								//BX("reviewForm").submit();
								BX.ajax.submit(BX("reviewForm"), function(result){
									BX.adjust(BX("review-row"), {html: result});
								});
							}else{
								reloadCaptachaReview();
								BX.adjust(BX('h3_error'), {html: "Проверочный код капчи не совпадает!"});
							}
						}
					);
				}
			}

			function closeFormReview() {
				$('.new-review-form').remove();
				BX.show(BX('makeReviewButton'));
			}

			function showForm(form_container, parent, title){
				$.post(
					"<?=$templateFolder?>/form.php",
					{
						product: '<?=$arParams['ELEMENT_ID']?>',
						reviewname: '<?=(htmlspecialchars($_POST["reviewname"]) ? htmlspecialchars($_POST["reviewname"]):(htmlspecialchars(trim($USER->GetFirstName()." ".$USER->GetLastName()))))?>',
						reviewmail: '<?=(htmlspecialchars($_POST["reviewmail"]) ? htmlspecialchars($_POST["reviewmail"]):(htmlspecialchars(trim($USER->GetEmail()))))?>',
						reviewphone: '<?=(htmlspecialchars($_POST["reviewphone"]) ? htmlspecialchars($_POST["reviewphone"]): ($arUser['PERSONAL_MOBILE']?$arUser['PERSONAL_MOBILE']:$arUser['PERSONAL_PHONE']))?>',
						reviewcity: '<?=(htmlspecialchars($_POST["reviewcity"]) ? htmlspecialchars($_POST["reviewcity"]): $arUser['PERSONAL_CITY'])?>',
						reviewtext: '<?=(htmlspecialchars($_POST["reviewtext"]) ? htmlspecialchars($_POST["reviewtext"]):"")?>',
						url: '<?=$sComponentFolder?>/component.php'
					},
					function( data ) {
						$('.new-review-form').remove();
						$( "#" + form_container + parent).html( data );

						$('#reviewphone').mask('7 (000) 000-0000', {placeholder: "7 (___) ___-____", clearIfNotMatch: true});

						$('.new-review-form').slideDown("slow");

						$('#parent_element_id').val(parent);

						$("#form_title").html(title);

						$('html, body').animate({scrollTop: $("#formreview").offset().top - 50 }, 800);
					}
				);
			}
			</script>
		<div style="clear:both;" class ='clearboth'></div>
		</div>
		<div  style="display: flex;justify-content: center;">
			<a class='new-review' href='javascript:void(0)' id='makeReviewButton' onclick="addNewReview(this);">Написать отзыв</a>
			<?php if ( $arParams["COUNT_PAGE"] < count($arResult["ITEMS"])): ?>
				<a class="new-review" href="javascript:void(0)" id="review-all" onclick="ShowReviews()"><?if($_POST['show_review_items'] != "Y"){?>Показать все<?}else{?>Скрыть<?}?></a>
			<?php endif ?>
		</div>

		<?if($arResult["MESSAGE"]){?>
			<h3><?=$arResult["MESSAGE"]?></h3>
		<?}else{?>
			<h3 id='h3_error'></h3>
		<?}?>
		<?if($arResult["MESSAGE"])
			$_POST = array();
		?>
		<div id='replay_block_top'></div>

		<script type="text/javascript">

			var flag_rev = <?=($_POST['show_review_items'] != 'Y' ? "false" : "true")?>;
			function ShowReviews(){
				if(!flag_rev){
					$('div#user_reviwe_or_comment_block > div').removeClass('hide');
					$('#review-all').text('Скрыть');
					flag_rev = true;
				}
				else{
					var review_block = 	$('div#user_reviwe_or_comment_block > .review-block');

					for(var i = <?=($arParams["COUNT_PAGE"])?>; i < review_block.length; i++){
						review_block.eq(i).addClass('hide');
					}

					$('#review-all').text('Показать все');
					flag_rev = false;
				}
			}

		</script>
	<?if($ajax == 'Y'){
		die();
	}?>
</div>
