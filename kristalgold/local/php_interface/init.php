<?php

	if(file_exists(__DIR__.'/include/const.php'))
		require_once(__DIR__.'/include/const.php');
	if(file_exists(__DIR__.'/include/functions.php'))
		require_once(__DIR__.'/include/functions.php');
	if(file_exists(__DIR__.'/include/events.php'))
		require_once(__DIR__.'/include/events.php');
        
define("PREFIX_PATH_404", "/404.php");

CModule::IncludeModule("mrproper");
