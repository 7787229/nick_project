<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<div id='auth_and_register' class="modal fade">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<span class="close_form" data-dismiss="modal" aria-label="Close"><span class="popup__close arcticmodal-close"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 224.512 224.512" style="enable-background:new 0 0 224.512 224.512;" xml:space="preserve"><g><polygon style="fill:#010002;" points="224.507,6.997 217.521,0 112.256,105.258 6.998,0 0.005,6.997 105.263,112.254    0.005,217.512 6.998,224.512 112.256,119.24 217.521,224.512 224.507,217.512 119.249,112.254  "/></g></svg></span></span>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs">
				  <li class="active"><a data-toggle="tab" href="#auth">Вход</a></li>
				  <li style='display:none !important;'><a data-toggle="tab" href="#forgot"></a></li>
				  <li><a data-toggle="tab" href="#register">Регистрация</a></li>
				</ul>

				<div class="tab-content">
					<div id="auth" class="tab-pane fade in active">
						<?$APPLICATION->IncludeComponent(
							"bitrix:system.auth.form",
							"tab",
							Array(
								"COMPONENT_TEMPLATE" => "tab",
								"FORGOT_PASSWORD_URL" => "/auth/forgot/",
								"PROFILE_URL" => "/magazin/personal/profile/",
								"REGISTER_URL" => "/auth/register/",
								"SHOW_ERRORS" => "Y"
							)
						);?>
					</div>
					<div id='forgot' class='tab-pane fade'>
						<?$APPLICATION->IncludeComponent(
							"bitrix:system.auth.forgotpasswd",
							"tab",
							Array()
						);?>
					</div>
					<div id="register" class="tab-pane fade">
						<?$APPLICATION->IncludeComponent(
							"bitrix:main.register",
							"tab",
							Array(
								"AUTH" => "Y",
								"COMPONENT_TEMPLATE" => "tab",
								"COMPOSITE_FRAME_MODE" => "A",
								"COMPOSITE_FRAME_TYPE" => "AUTO",
								"REQUIRED_FIELDS" => array("NAME", "EMAIL"),
								"SET_TITLE" => "N",
								"SHOW_FIELDS" => array("NAME", "EMAIL"),
								"SUCCESS_PAGE" => "",
								"USER_PROPERTY" => array(),
								"USER_PROPERTY_NAME" => "",
								"USE_BACKURL" => "Y"
							)
						);?>
						<!-- <p class='personal_agreement'>
							Выполняя вход через соц.сети, я даю своё согласие на обработку моих персональных данных в соответствии с <a href='/confidentiality/' target='_blank'>Офертой</a>.
						</p> -->
					</div>
				</div>
			</div>
			<!-- <div class="modal-footer"></div> -->
		</div>
	</div>
</div>
