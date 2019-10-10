<?php
$_SERVER["DOCUMENT_ROOT"] = "/var/www/html";

define("IMPORT_JOB_FN", __DIR__.'/cron_job.json');
define("IMPORT_JOB_LOG_FN", __DIR__.'/cron_job.log');

$fp = fopen(IMPORT_JOB_FN, "r"); // Открываем файл в режиме чтения
$arStatus = array();
if ( $fp ) {
    $str = fread($fp, 1024);
    $arStatus = json_decode($str,true);
}
if ( $arStatus && $arStatus["new_job"] ) {
    fclose($fp); //Закрытие файла
    $fp = fopen(IMPORT_JOB_FN, "w+"); // Открываем файл в режиме записи
    $newjob = json_encode(array("runtime"=>date("Y-m-d H:i:s")))."\r\n"; // Исходная строка
	fwrite($fp, $newjob); // Запись в файл

	$fp_log = fopen(IMPORT_JOB_LOG_FN, "a+"); // Открываем файл в режиме чтения
    $log_txt = "Запуск импорта ".date("Y-m-d H:i:s")."\r\n"; // Исходная строка
	fwrite($fp_log, $log_txt); // Запись в файл
    fclose($fp_log); //Закрытие файла

	// запуск пересчета цен
	include_once $_SERVER["DOCUMENT_ROOT"].'/local/include/move_unavailable_product.php';
}
// var_dump($arStatus)    ;
fclose($fp); //Закрытие файла

exit;
