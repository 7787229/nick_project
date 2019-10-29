<?
global $MESS, $APPLICATION;

IncludeModuleLangFile(__FILE__);



function do_free_space() {
    $MODULE_ID = "delement.freespace";
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/cache_files_cleaner.php");
    if (!CModule::IncludeModule('iblock')) {
        return "do_free_space();";
    }
    
    $id_sites = array();
        
    //PARAMETERS
    $event_name = "FREE_SPACE";
    $check = false;
    $want_space = COption::GetOptionString($MODULE_ID, "free_space");
    $time_out = COption::GetOptionString($MODULE_ID, "time_out");
    $email_to = COption::GetOptionString($MODULE_ID, "email_for_norifer");
    $module_free_space = COption::GetOptionString("main","disk_space");
    $enabled = (COption::GetOptionString($MODULE_ID, "enabled") == "Y") ? true : false;
    $delete_cache = (COption::GetOptionString($MODULE_ID, "delete_cache") == "Y") ? true : false;
    $email_notifer = (COption::GetOptionString($MODULE_ID, "email_notifer") == "Y") ? true : false;
    $type_filesystem = COption::GetOptionString($MODULE_ID, "type_filesystem");
    $all_space = COption::GetOptionString($MODULE_ID, "all_space");
    $remove_backups = (COption::GetOptionString($MODULE_ID, "remove_backups") == "Y") ? true : false;
    
    //BUSY SPACE
    if ($type_filesystem) {
        $busy_space = get_dir_size($_SERVER['DOCUMENT_ROOT'])/ (1024 * 1024);
        $total_space = $all_space;
        $free_space = $total_space - $busy_space;
    } else {
        $free_space = disk_free_space($_SERVER["DOCUMENT_ROOT"]) / (1024 * 1024);
        $total_space = disk_total_space($_SERVER["DOCUMENT_ROOT"]) / (1024 * 1024);
        $busy_space = $total_space - $free_space;
    }
    COption::SetOptionString($MODULE_ID, "busy_place",$busy_space);
    
    //ENABLED?    
    if (!$enabled) {
        return "do_free_space();";
    }
    
    //CHECK FREE SPACE
    if ($free_space <= $want_space) {
        //mail
        if ($email_notifer) {
            $arFields = array(
                "FREE_SPACE" => number_format($free_space, 2, '.', ' '),
                "TOTAL_SPACE" => number_format($total_space, 2, '.', ' '),
                "EMAIL_TO" => $email_to,
                "WANT_SPACE" => number_format($want_space,2, '.', ' ')
            );
            $rsSites = CSite::GetList($by = "sort", $order = "asc", array("ACTIVE" => "Y"));
            while ($arSite = $rsSites->Fetch()) {
                $sites_ids[] = $arSite['LID'];
            }
            CEvent::Send($event_name, $sites_ids, $arFields);
        }
        
        //check cron
        $cron = false;
        if (!defined('BX_CRONTAB')) {
            $cron = COption::GetOptionString("main", "agents_use_crontab", "N") == 'Y' || defined('BX_CRONTAB_SUPPORT') && BX_CRONTAB_SUPPORT === true || COption::GetOptionString("main", "check_agents", "Y") != 'Y';
        }
        
        //delete all types cache
        if ($delete_cache) {
            if (!defined("BX_CACHE_TYPE")) {
                $obCacheCleaner = new CFileCacheCleaner(false);
                if (!$obCacheCleaner->InitPath(false)) {
                    return "do_free_space();";
                }

                $delete_files = 0;
                $delete_space = 0;
                $endTime = time() + $time_out;

                $obCacheCleaner->Start();
                while ($file = $obCacheCleaner->GetNextFile()) {
                    if (is_string($file) && !preg_match("/(\\.enabled|.config\\.php)\$/", $file)) {
                        $file_size = filesize($file);
                        if (@unlink($file)) {
                            $delete_files++;
                            $delete_space += $file_size;
                        }
                    }
                    //if not cron - die
                    if (!$cron && time() >= $endTime) {
                        return "do_free_space();";
                    }
                }
            }
        }
        
        //delete backups
        if ($cron && $remove_backups) {
            $dir_backups = $_SERVER["DOCUMENT_ROOT"] . BX_PERSONAL_ROOT . "/backup";
            $dh = opendir($dir_backups);
            while(($f = readdir($dh)) !== false) {
                if($f == "." || $f == ".." || $f == "index.php" || $f == ".htaccess")
                    continue;
                $file_path = $dir_backups."/".$f;
                $file_size = filesize($file_path);
                if (@unlink($file_path)) {
                    $delete_files++;
                    $delete_space += $file_size;
                }
            }
            closedir($dh);        
        }
    } else {
        return "do_free_space();";
    }
    return "do_free_space();";
}

function get_dir_size($dir_name) {
    $dir_size = 0;
    if (is_dir($dir_name)) {
        if ($dh = opendir($dir_name)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir_name . "/" . $file)) {
                        $dir_size += filesize($dir_name . "/" . $file);
                    }
                    if (is_dir($dir_name . "/" . $file)) {
                        $dir_size += get_dir_size($dir_name . "/" . $file);
                    }
                }
            }
        }
    }
    closedir($dh);
    return $dir_size;
}

?>