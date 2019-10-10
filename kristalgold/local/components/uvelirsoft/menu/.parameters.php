<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
unset($arComponentParameters);
$arComponentParameters = array(
    "GROUPS" => array(
        "EXT_SETTINGS" => array(
            'NAME' => "Дополнительные пункты",
            "SORT" => 500
        ),
    ),
    "PARAMETERS" => array(
        "ITEM_COUNT" => array(
            "PARENT" => "EXT_SETTINGS",
            "NAME" => "Количество дополнительных пунктов меню",
            "TYPE" => "STRING",
            "DEFAULT" => "2",
            "REFRESH" => "Y"
        ),
    )
);
for ($i = 1; $i <= $arCurrentValues['ITEM_COUNT']; $i++) {
    $arComponentParameters['PARAMETERS']['NAME_'.$i] = array(
        "PARENT" => "EXT_SETTINGS",
        "NAME" => "Название #$i",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
    $arComponentParameters['PARAMETERS']['LINK_'.$i] = array(
        "PARENT" => "EXT_SETTINGS",
        "NAME" => "URL #$i",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
    $arComponentParameters['PARAMETERS']['CLASS_'.$i] = array(
        "PARENT" => "EXT_SETTINGS",
        "NAME" => "CSS class #$i",
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
}

?>
