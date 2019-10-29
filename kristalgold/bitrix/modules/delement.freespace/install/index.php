<?

IncludeModuleLangFile(__FILE__);

Class delement_freespace extends CModule 
{

    var $MODULE_ID = "delement.freespace";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    
    function delement_freespace() {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = GetMessage("delement.freespace_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("delement.freespace_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = GetMessage("delement.freespace_COMPANY_NAME");
        $this->PARTNER_URI = "http://d-element.ru";
    }

    function DoInstall() {
        global $DOCUMENT_ROOT, $APPLICATION;
        RegisterModule($this->MODULE_ID);
        COption::SetOptionString($this->MODULE_ID,"enabled","N");
        
        $this->InstallFiles();
        
        $this->InstallEvents();
        $APPLICATION->IncludeAdminFile(GetMessage("delement.freespace_INSTALL_MODULE"), $DOCUMENT_ROOT . "/bitrix/modules/".$this->MODULE_ID."/install/step.php");
    }
    
    function InstallEvents() {
        include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/events.php");
        return true;
    }

    function InstallFiles() {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".$this->MODULE_ID."/install/gadgets", $_SERVER['DOCUMENT_ROOT']."/bitrix/gadgets/delement",true,true);
        return true;
    }

    function DoUninstall() {
        global $DOCUMENT_ROOT, $APPLICATION;
        CAgent::RemoveAgent("do_free_space();", $this->MODULE_ID);
        
        $this->UnInstallFiles();
        
        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(GetMessage("delement.freespace_UNINSTALL_MODULE"), $DOCUMENT_ROOT . "/bitrix/modules/".$this->MODULE_ID."/install/unstep.php");
    }
    
    function UnInstallFiles() {
        DeleteDirFilesEx("/bitrix/gadgets/delement/freespace");
        return true;
    }

}

?>