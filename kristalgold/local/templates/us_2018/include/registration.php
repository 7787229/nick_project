<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>


<div id="registration" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Основное содержимое модального окна -->
				<span class="close_form"><i class="fa fa-times" aria-hidden="true"></i></span>
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.register",
					"modal",
					Array(
						"AUTH" => "Y",
						"REQUIRED_FIELDS" => array(0=>"EMAIL",),
						"SET_TITLE" => "N",
						"SHOW_FIELDS" => array("EMAIL","NAME","LAST_NAME"),
						"SUCCESS_PAGE" => "",
						//"USER_PROPERTY" => "UF_PERSONAL_INFO",
						"USER_PROPERTY" => "",
						"USER_PROPERTY_NAME" => "",
						"USE_BACKURL" => "Y",
						"USER_CONSENT" => "Y",
						"USER_CONSENT_ID" => "1",
						"USER_CONSENT_IS_CHECKED" => "Y",
						"USER_CONSENT_IS_LOADED" => "Y",
					)
				);?>
      <!-- Футер модального окна -->
      <div class="modal-footer">
		  <a href="javascript:void(0)" onclick='$("#registration").modal("toggle").ready(function(){$("#auth_form").modal("toggle");});'>Войти</a>
      </div>
    </div>
  </div>
</div>


