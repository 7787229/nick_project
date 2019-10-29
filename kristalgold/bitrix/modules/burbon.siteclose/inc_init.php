<?
$module_id = 'burbon.siteclose';
if(COption::GetOptionString($module_id, "BU_SC_checkbox_".SITE_ID) == 'Y') {
	AddEventHandler("main", "OnBeforeProlog", Array("BurbonSiteClose", "OnProlog")); 
	if (!class_exists("BurbonSiteClose")) {
		class BurbonSiteClose {
			function OnProlog() {  
				global $USER; 
				if (!$USER->IsAdmin() && !(substr($_SERVER["REQUEST_URI"],1,24)=="bitrix/tools/captcha.php") )
				{ 
					$module_id = 'burbon.siteclose';
					if (CModule::IncludeModule($module_id)) {
						$type = COption::GetOptionString($module_id, "selectbox_type_".SITE_ID);
						include($_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default/".$module_id."/".$type."/page.php");
						die();
					}
				} 
			}
		}    
	}
}
?>