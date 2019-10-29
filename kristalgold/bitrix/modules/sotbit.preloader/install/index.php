<?
IncludeModuleLangFile(__FILE__);
Class sotbit_preloader extends CModule
{
	const MODULE_ID = 'sotbit.preloader';
	var $MODULE_ID = 'sotbit.preloader'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("sotbit.preloader_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("sotbit.preloader_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("sotbit.preloader_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("sotbit.preloader_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		return true;
	}

	function InstallEvents()
	{
        RegisterModuleDependences("main", "OnEndBufferContent", "sotbit.preloader", "CSotbitPreloader", "OnEndBufferContent");
        return true;
	}

	function UnInstallEvents()
	{
        UnRegisterModuleDependences("main", "OnEndBufferContent", "sotbit.preloader", "CSotbitPreloader", "OnEndBufferContent");
        COption::RemoveOption(self::MODULE_ID);
        return true;
	}

	function InstallFiles($arParams = array())
	{

		return true;
	}

	function UnInstallFiles()
	{
        unlink($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/themes/.default/".self::MODULE_ID.".style.css") ;
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
        $this->InstallEvents();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();

		$this->UnInstallFiles();
        $this->UnInstallEvents();
	}
}
?>
