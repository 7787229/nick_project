<?
Class uvelirsoft extends CModule
{
    var $MODULE_ID = "uvelirsoft";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function uvelirsoft()
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
        $this->MODULE_NAME = "Ювелирный интернет-магазин";
        $this->MODULE_DESCRIPTION = "Настройка интернет-магазина";
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        // Install events
        RegisterModuleDependences("iblock","OnAfterIBlockElementUpdate","uvelirsoft","cMainUS","onBeforeElementUpdateHandler");
        RegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Установка модуля настройки для интернет магазина", $DOCUMENT_ROOT."/local/modules/uvelirsoft/install/step.php");
        return true;
    }

    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        UnRegisterModuleDependences("iblock","OnAfterIBlockElementUpdate","uvelirsoft","cMainUS","onBeforeElementUpdateHandler");
        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля dull", $DOCUMENT_ROOT."/local/modules/uvelirsoft/install/unstep.php");
        return true;
    }
}