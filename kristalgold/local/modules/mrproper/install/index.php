<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;


Loc::loadMessages(__FILE__);

if (class_exists('mrproper')) {
    return;
}

class mrproper extends CModule
{
    /** @var string */
    public $MODULE_ID;

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    public function __construct()
    {
        $this->MODULE_ID = 'mrproper';
        $this->MODULE_VERSION = '0.0.1';
        $this->MODULE_VERSION_DATE = '2018-02-30 10:00:00';
        $this->MODULE_NAME = 'mr.Proper';
        $this->MODULE_DESCRIPTION = 'mr.Proper - cleans to shine, special for '.$_SERVER['HTTP_HOST'];
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = "uvelirsoft.ru";
        $this->PARTNER_URI = "https://uvelirsoft.ru";
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            ///
        }
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID)) {
            ///
        }
    }
}
