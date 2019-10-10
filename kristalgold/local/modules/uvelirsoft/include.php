<?php
//CModule::IncludeModule("uvelirsoft");
global $DBType;

$arClasses=array(
    'cMainUS'=>'classes/general/cMainUS.php'
);

CModule::AddAutoloadClasses("uvelirsoft",$arClasses);
