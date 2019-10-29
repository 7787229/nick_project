<?
/**
 * Company developer: ALTASIB
 * Developer: Toporikov Sergey
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2016 ALTASIB
 */

global $DBType;
IncludeModuleLangFile(__FILE__);

$arClassesList = array(
	// main classes
	"CAltasibReAuthBase" => "classes/mysql/class.php",
	// API classes
);
// fix strange update bug
if(method_exists(CModule, "AddAutoloadClasses"))
{
	CModule::AddAutoloadClasses(
		"altasib.reauth",
		$arClassesList
	);
}
else
{
	foreach($arClassesList as $sClassName => $sClassFile)
	{
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/".$sClassFile);
	}
}
Class CAltasibReauth
{
	Function DoReauth()
	{
		global $USER;
		if($USER->IsAuthorized())
		{
			if(CModule::IncludeModule("altasib.reauth"))
			{
				$USER_ID = $USER->GetID();
				$res = CAltasibReAuthBase::Get($USER_ID);
				if($res !== false)
				{
					if($res === 0)
					{
						$USER->Logout($USER_ID);
					}
					elseif($res === 1)
					{
						$USER->Authorize($USER_ID);
					}
					CAltasibReAuthBase::Delete($USER_ID, $res);
				}
			}
		}
	}

	// Events
	Function OnAdminListDisplay(&$Object)
	{
		global $APPLICATION;
		$page = $APPLICATION->GetCurPage();
		if($page == "/bitrix/admin/user_admin.php")
		{
			$Object->arActions["reauth_reauth"] = GetMessage("ALTASIB_REAUTH_ACT_REAUTH");
			$Object->arActions["reauth_logout"] = GetMessage("ALTASIB_REAUTH_ACT_LOGOUT");
		}
	}
	Function OnAdminContextMenuShow(&$arItems)
	{
		global $APPLICATION, $USER;
		$page = $APPLICATION->GetCurPage();
		if($page == "/bitrix/admin/user_edit.php"
			&& ($USER->CanDoOperation('edit_all_users') || $USER->CanDoOperation('edit_subordinate_users'))
		)
		{
			$arItems[] = array("NEWBAR"=>true);

			$arItems[] = Array(
				"TEXT" => GetMessage("ALTASIB_REAUTH_BTN_REAUTH"),
				"TITLE" => GetMessage("ALTASIB_REAUTH_BTN_REAUTH_TITLE"),
				"LINK" => $APPLICATION->GetCurPageParam("REAUTH_REAUTH=Y"),
				"ICON" => "btn_reauth",
			);
			$arItems[] = Array(
				"TEXT" => GetMessage("ALTASIB_REAUTH_BTN_LOGOUT"),
				"TITLE" => GetMessage("ALTASIB_REAUTH_BTN_LOGOUT_TITLE"),
				"LINK" => $APPLICATION->GetCurPageParam("REAUTH_LOGOUT=Y"),
				"ICON" => "btn_reauth",
			);
		}
	}
	Function OnProlog()
	{
		global $USER, $APPLICATION;
		// Add to reauth

		// List
		if(!empty($_REQUEST["ID"]) && is_array($_REQUEST["ID"]) && count($_REQUEST["ID"])>0
			&& ($USER->CanDoOperation('edit_all_users') || $USER->CanDoOperation('edit_subordinate_users'))
		)
		{
			foreach($_REQUEST["ID"] as $ID)
			{
				$ID = IntVal($ID);
				if($ID <= 0)
					continue;
				switch($_REQUEST['action'])
				{
					case "reauth_reauth":
						CAltasibReAuthBase::Add($ID, 1);
						break;
					case "reauth_logout":
						CAltasibReAuthBase::Add($ID, 0);
						break;
				}
			}
		}
		// Detail
		if($_REQUEST["REAUTH_REAUTH"] == "Y")
		{
			if(intval($_REQUEST["ID"])>0)
				CAltasibReAuthBase::Add(intval($_REQUEST["ID"]), 1);
			LocalRedirect($APPLICATION->GetCurPageParam("", array("REAUTH_REAUTH")));
		}
		if($_REQUEST["REAUTH_LOGOUT"] == "Y")
		{
			if(intval($_REQUEST["ID"])>0)
				CAltasibReAuthBase::Add(intval($_REQUEST["ID"]));
			LocalRedirect($APPLICATION->GetCurPageParam("", array("REAUTH_LOGOUT")));
		}
		// Get Methods
		CAltasibReauth::DoReauth();
	}

	Function OnBeforeUserUpdate(&$arFields)
	{
		global $USER;
		$arUser = CUser::GetByID($arFields["ID"])->Fetch();
		if(!empty($arFields["ACTIVE"]) && $arFields["ACTIVE"] != "Y"/* && $arUser["ACTIVE"] == "Y"*/)
		{
			CAltasibReAuthBase::Add(intval($arFields["ID"]), 0);
		}
		$arNewGroup = array();

		if(!empty($arFields["GROUP_ID"]) && is_array($arFields["GROUP_ID"]))
		{
			foreach($arFields["GROUP_ID"] as $aUG)
			{
				$arNewGroup[] = $aUG["GROUP_ID"];
			}
		}

		if(!empty($arNewGroup))
		{
			$arOldGroup = CUser::GetUserGroup($arFields["ID"]);
			$arNewGroup[] = 2;
			sort($arOldGroup);
			sort($arNewGroup);
			if($arOldGroup != $arNewGroup)
			{
				$rsAdd = CAltasibReAuthBase::Add(intval($arFields["ID"]), 1);
			}
		}
	}
}
?>