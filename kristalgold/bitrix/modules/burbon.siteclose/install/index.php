<?
/**
*
* @version  $Id: index.php 51 2013-05-31 09:56:31Z production $
* @author   $Author: production $
* @Date     $Date: 2013-05-31 13:56:31 +0400 (ѕт, 31 май 2013) $
*
*/

IncludeModuleLangFile(__FILE__);

Class burbon_siteclose extends CModule
{
	var $MODULE_ID = 'burbon.siteclose'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';
	var $logON = false;

	function burbon_siteclose()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("BU_SC_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("BU_SC_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("BU_SC_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("BU_SC_PARTNER_URI");
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
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
		// копируем темы 
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/{$this->MODULE_ID}/install/themes/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default/{$this->MODULE_ID}/", true, true);
 		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx("/bitrix/themes/.default/{$this->MODULE_ID}");
		$sites_list = array();
		$sites_arr = CSite::GetList($by="def", $order="desc", array("ACTIVE"=>"Y"));
		while ($site = $sites_arr->Fetch())
		{
			$sites_list[] = array($site["LID"] => $site["NAME"]);
		}
		for($i=0;$i<count($sites_list);$i++){
			$keys = array_keys($sites_list[$i]);
			
			$path = $_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/'.$keys[0].'/';
			$string = '<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.$this->MODULE_ID.'/inc_init.php");?>';
			$header_str = '<?header("Content-Type: text/html; charset='.LANG_CHARSET.'");?>';
			if(file_exists($path.'init.php')) {
				$text = file_get_contents($path.'init.php');
				$file = fopen($path.'init.php', 'w');
				$new_text = str_replace("\r\n".$string."\r\n", '', $text);
				$new_text = str_replace($string, '', $new_text);
				$new_text = str_replace($header_str."\r\n\r\n", '', $new_text);
				$new_text = str_replace($header_str."\r\n", '', $new_text);
				$new_text = str_replace($header_str, '', $new_text);
				fwrite($file, str_replace($string, '', $header_str.$new_text));
				fclose($file);
			}
		}
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$sites_ids = array();
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule($this->MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		COption::RemoveOption($this->MODULE_ID);
		UnRegisterModule($this->MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}
?>
