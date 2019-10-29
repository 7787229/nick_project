<?
/**
 * Company developer: ALTASIB
 * Developer: Toporikov Sergey
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2016 ALTASIB
 */

global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
IncludeModuleLangFile(__FILE__);

Class altasib_reauth extends CModule
{
	var $MODULE_ID = "altasib.reauth";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
//	var $MODULE_GROUP_RIGHTS = "Y";

	function altasib_reauth()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		else
		{
			$this->MODULE_VERSION = "1.0.0";
			$this->MODULE_VERSION_DATE = "2011-08-25 12:00:00";
		}

		$this->MODULE_NAME = GetMessage("ALTASIB_REAUTH_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("ALTASIB_REAUTH_MODULE_DESCRIPTION");

		$this->PARTNER_NAME = "ALTASIB";
		$this->PARTNER_URI = "http://www.altasib.ru/";
	}

	function DoInstall()
	{
		global $DB, $APPLICATION, $step;
		$step = IntVal($step);
		$this->InstallFiles();
		$this->InstallDB();
		$this->InstallEvents();

		$GLOBALS["errors"] = $this->errors;
		$APPLICATION->IncludeAdminFile(GetMessage("ALTASIB_REAUTH_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/step1.php");
	}

	function DoUninstall()
	{
		global $DB, $APPLICATION, $step;
		$step = IntVal($step);
		$this->UnInstallDB();
		$this->UnInstallEvents();
		$this->UnInstallFiles();
		$APPLICATION->IncludeAdminFile(GetMessage("ALTASIB_REAUTH_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/unstep1.php");
	}

	function InstallDB()
	{
		global $DB, $DBType, $APPLICATION;
		$this->errors = false;

		if(!$DB->Query("SELECT 'x' FROM altasib_reauth", true))
		{
			$this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/db/".strtolower($DB->type)."/install.sql");
		}

		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		RegisterModule("altasib.reauth");
		RegisterModuleDependences("main","OnProlog","altasib.reauth","CAltasibReauth","OnProlog", "100");
		RegisterModuleDependences("main","OnAdminContextMenuShow","altasib.reauth","CAltasibReauth","OnAdminContextMenuShow", "100");
		RegisterModuleDependences("main","OnAdminListDisplay","altasib.reauth","CAltasibReauth","OnAdminListDisplay", "100");
		RegisterModuleDependences("main","OnBeforeUserUpdate","altasib.reauth","CAltasibReauth","OnBeforeUserUpdate", "100");
	}

	function UnInstallDB($arParams = array())
	{

		global $DB, $DBType, $APPLICATION;
		$this->errors = false;

		$this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/db/".$DBType."/uninstall.sql");

		if($this->errors !== false)
		{
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		UnRegisterModuleDependences("main","OnProlog","altasib.reauth","CAltasibReauth","OnProlog");
		UnRegisterModuleDependences("main","OnAdminContextMenuShow","altasib.reauth","CAltasibReauth","OnAdminContextMenuShow");
		UnRegisterModuleDependences("main","OnAdminListDisplay","altasib.reauth","CAltasibReauth","OnAdminListDisplay");
		UnRegisterModuleDependences("main","OnBeforeUserUpdate","altasib.reauth","CAltasibReauth","OnBeforeUserUpdate");

		COption::RemoveOption("altasib_reauth");
		UnRegisterModule("altasib.reauth");
		return true;
	}

	Function InstallEvents()
	{

	}

	Function UnInstallEvents()
	{

	}

	function InstallFiles()
	{
		// CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/themes", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.reauth/install/themes/.default", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default");
		DeleteDirFilesEx("/bitrix/themes/.default/icons/altasib.reauth");
		return true;
	}
}
?>