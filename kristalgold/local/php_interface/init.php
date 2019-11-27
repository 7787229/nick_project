<?php

	if(file_exists(__DIR__.'/include/const.php'))
		require_once(__DIR__.'/include/const.php');
	if(file_exists(__DIR__.'/include/functions.php'))
		require_once(__DIR__.'/include/functions.php');
	if(file_exists(__DIR__.'/include/events.php'))
		require_once(__DIR__.'/include/events.php');
        
define("PREFIX_PATH_404", "/404.php");

CModule::IncludeModule("mrproper");

function print_arr($arr){
    echo "<pre>";
    print_r($arr);
    echo "</pre>";
}

function getSectionList($filter, $select)
{
    $dbSection = CIBlockSection::GetList(
        Array(
            'LEFT_MARGIN' => 'ASC',
        ),
        array_merge(
            Array(
                'ACTIVE' => 'Y',
                'GLOBAL_ACTIVE' => 'Y'
            ),
            is_array($filter) ? $filter : Array()
        ),
        false,
        array_merge(
            Array(
                'ID',
                'IBLOCK_SECTION_ID'
            ),
            is_array($select) ? $select : Array()
        )
    );

    while( $arSection = $dbSection-> GetNext(true, false) ){

        $SID = $arSection['ID'];
        if($SID==138) continue;
        $PSID = (int) $arSection['IBLOCK_SECTION_ID'];

        $arLincs[$PSID]['CHILDS'][$SID] = $arSection;

        $arLincs[$SID] = &$arLincs[$PSID]['CHILDS'][$SID];
    }

    return array_shift($arLincs);
}
