<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arTemplateParameters = array(
    "ITEM_COUNT" => array(
        "PARENT" => "EXT_SETTINGS",
        "NAME" => "Количество дополнительных пунктов меню",
        "TYPE" => "STRING",
        "DEFAULT" => "0",
        "REFRESH" => "Y"
    ),
        "POSITION_LEFT" => array(
        'NAME' => 'Переносить пунты меню влево', 
        'TYPE' => 'CHECKBOX',
        'DEFAULT'=> "Y"
    ),
);
for ($i = 1; $i <= $arCurrentValues['ITEM_COUNT']; $i++) {
    $arTemplateParameters['NAME_'.$i] = array(
        "PARENT" => "EXT_SETTINGS",
        "NAME" => "Название #$i",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
    $arTemplateParameters['LINK_'.$i] = array(
        "PARENT" => "EXT_SETTINGS",
        "NAME" => "URL #$i",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
    $arTemplateParameters['CLASS_'.$i] = array(
        "PARENT" => "EXT_SETTINGS",
        "NAME" => "CSS class #$i",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
}
