<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>

<div id="auth_form" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
				<?$APPLICATION->IncludeComponent(
					"bitrix:system.auth.form",
					"modal",
					Array(
						"COMPONENT_TEMPLATE" => ".default",
						"FORGOT_PASSWORD_URL" => "/auth/forgot/",
						"PROFILE_URL" => "/magazin/personal/profile/",
						"REGISTER_URL" => "/auth/register/",
						"SHOW_ERRORS" => "Y"
					)
				);?>
    </div>
  </div>
</div>