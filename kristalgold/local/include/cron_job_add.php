<?php
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

define("IMPORT_JOB_FN", __DIR__.'/cron_job.json');

if ( CSite::InGroup(array(1)) ) { // проверка на группу =  администраторы

    $fp = fopen(IMPORT_JOB_FN, "r"); // Открываем файл в режиме чтения
	// printvar('',$fp);    
	$arStatus = array();
	if ( $fp ) {
	    $str = fread($fp, 1024);
	    $arStatus = json_decode($str,true);
	}
    if ( $arStatus && $arStatus["new_job"] ) {
    	// задание уже есть
    	echo "Задание уже сформировано. Дата создания: $arStatus[new_job]";
    } elseif ( $arStatus && $arStatus["runtime"] && (time()-strtotime($arStatus["runtime"]))< 60*60 ) {
    	// задание выполняется
    	echo "Задание выполняется или прошло еще мало времени. Повторите задание позже.";
    } else {
	    fclose($fp); //Закрытие файла
	    $fp = fopen(IMPORT_JOB_FN, "w+"); // Открываем файл в режиме записи 
	    $newjob = json_encode(array("new_job"=>date("Y-m-d H:i:s")))."\r\n"; // Исходная строка
    	fwrite($fp, $newjob); // Запись в файл
    	echo "Задание сформировано.";
    }
	// var_dump($arStatus)    ;
    fclose($fp); //Закрытие файла

} else {
    echo "Доступ закрыт";
}
exit;