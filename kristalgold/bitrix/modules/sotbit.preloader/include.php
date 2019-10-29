<?   
Class CSotbitPreloader
{
    function getDemo()
    {
        $module_id = "sotbit.preloader";
        $status = CModule::IncludeModuleEx($module_id);
        //$status = 3;
        if($status==3)
        {
            $module_id = "sotbit.preloader";
            unlink($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/themes/.default/".$module_id.".style.css");
            return false;
        }
        else return true;
    }

    function OnEndBufferContent(&$content)
    {
        $module_id = "sotbit.preloader";
        if(!$_REQUEST['__wiz_siteID'] && !$_REQUEST["bxsender"] && !$_REQUEST["edname"] && !$_REQUEST["bxajaxid"] && !defined("ADMIN_SECTION") && file_exists($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/themes/.default/".$module_id.".style.css") && self::getDemo())
        {
            $link = '<link href="'.BX_ROOT.'/themes/.default/'.$module_id.'.style.css" type="text/css" rel="stylesheet" />';
            $head = "<head>".$link;
            $content = preg_replace("/(\<head\>){1}/", $head, $content);

        }
    } 
}
?>
