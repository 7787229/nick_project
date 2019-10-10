<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

CModule::IncludeModule('iblock');

$arResult = array();

if(
	$_POST['AJAX'] == 'Y'
	&& isset($_POST["reviewtext"]) && !empty($_POST["reviewtext"])
){

	global $USER;
	if(
		!$_POST['NEED_TO_TRY_CAPTCHA'] == 'N'
		&& !$USER->IsAuthorized()
		&& !$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"])
	){
		// Неправильное значение
		$arResult["MESSAGE"] = "Проверочный код капчи не совпадает!";

	}else{

		// добавляем отзыв
		$el = new CIBlockElement;

		$needModeration = true;
		$productID = '';
		$message = "Ваш отзыв добавлен! Отзыв будет проверен и появится в общем списке!";;
		$active = 'N';
		if(intval($_POST['product']) > 0){
			$needModeration = false;
			$productID = (int)$_POST['product'];
			$message = 'Ваш отзыв добавлен!';
			$active = 'Y';
		}

		$PROP = array(
			"PARENT" => (intval($_POST["parent"]) > 0 ? intval($_POST["parent"]) : ""),
			"RATING" => ((intval($_POST["quality_star"]) <= 5 and intval($_POST["quality_star"]) > 0) ? intval($_POST["quality_star"]) : ""), // рейтинг
			"USER_NAME" => htmlspecialchars($_POST["reviewname"]),
			"USER_MAIL" => htmlspecialchars($_POST["reviewmail"]),
			"USER_PHONE" => htmlspecialchars($_POST["reviewphone"]),
			"MODERATION" => ($needModeration ? array(219) : ''), // 219 идентификатор значения свойства
			"IMAGES" => (!empty($_POST['review_images']) ? $_POST['review_images'] : array()),
			"ELEMENT_ID" => $productID
		);

		$arLoadProductArray = Array(
			  "MODIFIED_BY"         => $USER->GetID(), // элемент изменен текущим пользователем
			  "DATE_ACTIVE_FROM"	=> ConvertTimeStamp(MakeTimeStamp(time()), 'FULL'),
			  "IBLOCK_SECTION_ID"   => false,          // элемент лежит в корне раздела
			  "IBLOCK_ID"           => $arParams["IBLOCK_ID"],
			  "PROPERTY_VALUES"     => $PROP,
			  "NAME"                => "отзыв_".date("Ymdhis"),
			  "ACTIVE"              => $active,            // НЕ активен - требуется модерация
			  "PREVIEW_TEXT"        => htmlspecialchars(strip_tags($_POST["reviewtext"]))
		 );

		if($REVIEW_ID = $el->Add($arLoadProductArray)){
			$arResult["MESSAGE"] = $message;

			// event
			if($needModeration){
				$arFieldsEmail = array(
					"REVIEW_LINK" => "http://".SITE_SERVER_NAME."/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=".$arParams['IBLOCK_ID']."&type=content&ID=".$REVIEW_ID."&lang=ru&WF=Y&find_section_section=0",
					"REVIEW_TEXT" => htmlspecialchars(strip_tags($_POST["reviewtext"])),
					"REVIEWER_NAME" => htmlspecialchars($_POST["reviewname"])
				);
				// отправляем письма списку менеджеров
				/*$idEvent = CEvent::Send(
					"NEW_SITE_REVIEW",
					SITE_ID,
					$arFieldsEmail,
					"N",
					"",
					$PROP['IMAGES']
				);*/
			}
		  }else{
			$arResult["MESSAGE"] =  "Error: ".$el->LAST_ERROR;
		}
	}
}


global $USER;

if ($USER->IsAuthorized()){
	$moderationGroup = ($arParams["MODERATION_GROUP"] ? intval($arParams["MODERATION_GROUP"]):"1");
	$allUserGroups = $USER->GetUserGroupArray();
}else{
	$moderationGroup = false;
	$allUserGroups = array();
}

// будем показывать отзывы, которые доступны пользователям или группе модераторов (поумолчанию админам с группой = 1)
if (!$USER->IsAuthorized()){
	$arrFilter = array("PROPERTY_PARENT" => false, "PROPERTY_MODERATION_VALUE" => false, "IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y");
}else{
	$moderationGroup = ($arParams["MODERATION_GROUP"] ? intval($arParams["MODERATION_GROUP"]):"1");
	$allUserGroups = $USER->GetUserGroupArray();

	if(in_array($moderationGroup,$allUserGroups)){
		$arrFilter = array("PROPERTY_PARENT" => false, "IBLOCK_ID" => $arParams["IBLOCK_ID"]);
	}else{
		$arrFilter = array(
			"PROPERTY_PARENT" => false,
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				array(
					"LOGIC" => "OR",
						array("ACTIVE" => "Y", "PROPERTY_MODERATION_VALUE" => false),
						array("CREATED_USER_ID" => $USER->GetID())
				));
	}
}

if(intval($arParams['ELEMENT_ID']) > 0){
	$arrFilter['PROPERTY_ELEMENT_ID'] = (int)$arParams['ELEMENT_ID'];
}else{
	$arrFilter['PROPERTY_ELEMENT_ID'] = false;
}


// найдем все отзывы
$rsElement = CIBlockElement::GetList(
    // $arOrder  = array('date_active_from' => 'DESC'),
    $arOrder  = array('DATE_CREATE' => 'DESC', 'NAME' => 'ASC'),
    $arrFilter,
    false,
    Array(),
    array("ID","IBLOCK_ID","ACTIVE","NAME","PREVIEW_TEXT","DETAIL_TEXT", "DATE_CREATE", "DATE_ACTIVE_FROM", "PROPERTY_*")
);

$rsElement->NavStart(0);
$arResult["NAVSTR"] = $rsElement->GetPageNavStringEx($navComponentObject, "Страницы:", "modern");

while($ob = $rsElement->GetNextElement()){
    $arElement = $ob->GetFields();
	//printvar('',$arElement);
	$createDate = explode(" ",$arElement["DATE_CREATE"]);
	$activeDate = explode(" ",$arElement["DATE_ACTIVE_FROM"]);
	$arElement["DATE_CREATE_CHORT"] = $createDate[0];
	$arElement["DATE_ACTIVE_FROM_CHORT"] = $activeDate[0];
    $arElement["PROPS"] = $ob->GetProperties();
    $arButtons = CIBlock::GetPanelButtons(
                    $arElement["IBLOCK_ID"],
                    $arElement["ID"],
                    0,
                    array("SECTION_BUTTONS"=>false, "SESSID"=>false)
                );
    $arElement["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
    $arElement["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

    $arResult["ITEMS"][$arElement["ID"]] = $arElement;
    $arResult["IDS"][] = $arElement["ID"];

}

// найдем дочерние элементы (отзывы)
if(count($arResult["IDS"])>0){
	unset($arrFilter["PROPERTY_PARENT"]);
	$rsElementCh = CIBlockElement::GetList(
	    $arOrder  = array('date_active_from' => 'DESC'),
	    array_merge($arrFilter,array("PROPERTY_PARENT.ID"=>$arResult["IDS"])),
	    false,
	    false,
	    array("ID","IBLOCK_ID","ACTIVE","NAME","PREVIEW_TEXT","DETAIL_TEXT", "DATE_CREATE", "DATE_ACTIVE_FROM", "PROPERTY_*")
	);

	while($ob = $rsElementCh->GetNextElement()){
	    $arElementCh = $ob->GetFields();
		$createDate = explode(" ",$arElementCh["DATE_CREATE"]);
		$activeDate = explode(" ",$arElementCh["DATE_ACTIVE_FROM"]);
		$arElementCh["DATE_CREATE_CHORT"] = $createDate[0];
		$arElementCh["DATE_ACTIVE_FROM_CHORT"] = $activeDate[0];
	    $arElementCh["PROPS"] = $ob->GetProperties();
	    $arButtons = CIBlock::GetPanelButtons(
	                    $arElementCh["IBLOCK_ID"],
	                    $arElementCh["ID"],
	                    0,
	                    array("SECTION_BUTTONS"=>false, "SESSID"=>false)
	                );
	    $arElementCh["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
	    $arElementCh["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

	    $arResult["ITEMS"][$arElementCh["PROPS"]["PARENT"]["VALUE"]]["REPLAYS"][$arElementCh["ID"]] = $arElementCh;
	}

}


$this->IncludeComponentTemplate();
