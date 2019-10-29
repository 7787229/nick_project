<?
$module_id = 'sotbit.preloader';

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/include.php');
IncludeModuleLangFile(__FILE__);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/include/CModuleOptions.php');
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
CModule::IncludeModule("sotbit.preloader");
$showRightsTab = false;


$module_status = CModule::IncludeModuleEx($module_id);
if(!CSotbitPreloader::getDemo()){
    echo GetMessage('SOTBIT_PRELOADER_DEMO_MODULE');
}

 


if($REQUEST_METHOD=="POST" && strlen($RestoreDefaults)>0 && check_bitrix_sessid())
{
		COption::RemoveOption($module_id);
        unlink($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/themes/.default/".$module_id.".style.css");
		$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
		while($zr = $z->Fetch())
			$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));

        if((strlen($Apply) > 0) || (strlen($RestoreDefaults) > 0))
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"]));
		else
			LocalRedirect($_REQUEST["back_url_settings"]);
}




$arTabs = array(
   array(
      'DIV' => 'edit1',
      'TAB' => GetMessage('sotbit_preloader_edit1'),
      'ICON' => '',
      'TITLE' => GetMessage('sotbit_preloader_edit1'),
      'SORT' => '10'
   ),

     
);
                               
$arGroups = array(
   'OPTION_10' => array('TITLE'=>GetMessage('sotbit_preloader_edit1'), 'TAB' => 0),

);

$arPosition["REFERENCE_ID"] = array(0, "left-top", "center-top", "right-top", "left-middle", "center-middle", "right-middle", "left-bottom", "center-bottom", "right-bottom");
$arPosition["REFERENCE"][] = GetMessage('sotbit_preloader_noedit');
foreach($arPosition["REFERENCE_ID"] as $i=>$v)
{
    if($i>0) $arPosition["REFERENCE"][] = $v;
}

$arOptions = array(
    'ACTIVE' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage('sotbit_preloader_active'),
      'TYPE' => 'CHECKBOX',
      'DEFAULT' => 'N',
      'SORT' => '10',
      'REFRESH' => 'N',
      'SIZE' => '3'
   ),
    'IMAGE' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage('sotbit_preloader_image'),
      'TYPE' => 'FILE',
      'DEFAULT' => '',
      'SORT' => '10',
      'REFRESH' => 'N',
      'SIZE' => '3'
   ),
   'BACKGROUND' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage('sotbit_preloader_background'),
      'TYPE' => 'COLORPICKER',
      'DEFAULT' => '',
      'SORT' => '20',
      'REFRESH' => 'N',
      'SIZE' => '3'
   ),
   'BORDER' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage('sotbit_preloader_border'),
      'TYPE' => 'STRING',
      'DEFAULT' => '',
      'SORT' => '25',
      'REFRESH' => 'N',
      'SIZE' => '10'
   ),
   'WIDTH' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage('sotbit_preloader_width'),
      'TYPE' => 'STRING',
      'DEFAULT' => '',
      'SORT' => '30',
      'REFRESH' => 'N',
      'SIZE' => '3'
   ),
   'HEIGHT' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage('sotbit_preloader_height'),
      'TYPE' => 'STRING',
      'DEFAULT' => '',
      'SORT' => '40',
      'REFRESH' => 'N',
      "NOTES" => GetMessage('sotbit_preloader_height_descr'),
      'SIZE' => '3'
   ),
   'POSITION' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage('sotbit_preloader_position'),
      'TYPE' => 'SELECT',
      'DEFAULT' => '0',
      'SORT' => '50',
      'VALUES' => $arPosition,
      'REFRESH' => 'N',
      'SIZE' => '3'
   ),

       
);


/*
Конструктор класса CModuleOptions
$module_id - ID модуля
$arTabs - массив вкладок с параметрами
$arGroups - массив групп параметров
$arOptions - собственно сам массив, содержащий параметры
$showRightsTab - определяет надо ли показывать вкладку с настройками прав доступа к модулю ( true / false )
*/

$opt = new CModuleOptions($module_id, $arTabs, $arGroups, $arOptions, $showRightsTab);
$opt->ShowHTML();

?>