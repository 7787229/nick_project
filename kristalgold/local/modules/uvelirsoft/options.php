<h1>Настройка интернет магазина</h1>

<?
$module_id = "uvelirsoft";
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/local/modules/uvelirsoft/options.php");

$RIGHT = $APPLICATION->GetGroupRight($module_id);

if($RIGHT >= "R") :
///// Читаем данные и формируем для вывода
$arAllOptions = Array(
	array("SITE_CSS_MOD", GetMessage("Current color scheme"), array("text"), "200"),
	array("MAIN_TOPMENU_LEFT_COUNT", GetMessage("How many items on the top main menu to show on the left"), array("text"), "6"),
	array("MAIN_MENU_LEFT_COUNT", GetMessage("How many items on the main menu to show on the left"), array("text"), "6"),
	array("CATALOG_BANNER_LINE_HEIGHT", GetMessage("Height banner line (px)"), array("text"), "200"),
    array("CATALOG_ACTIONS_LINE_HEIGHT", GetMessage("Height banner line for actions (px)"), array("text"), "300"),
    array("CATALOG_FILTER_VISIBLE_ELEMENTS_COUNT", GetMessage("Count visible elements in filter parameters"), array("text"), "5"),
    array("CATALOG_FILTER_FIELDS_MOD", GetMessage("Special modified fields for smart filter"), array("text"), array()),
    array("CATALOG_FILTER_FIELDS_MOD_NOTITLE", GetMessage("Special modified fields for smart filter (no title)"), array("text"), array()),
	array("CATALOG_PROPS_AS_DROPDOWN", GetMessage("Catalog sku props which have to show as dropdown"), array("text"), array()),
	array("CATALOG_UPDATE_REST_OFFER_LABEL", GetMessage("Update the rest of the offers from 1C"), array("label"), array()),
	array("CATALOG_UPDATE_REST_OFFER", GetMessage("What to do with a trade offer if the available quantity is equal to or less than zero?"), array("list"), array("deactivate"=>GetMessage("deactivate"),"zero"=>GetMessage("zero"))),
	array("CATALOG_UPDATE_REST_OFFER_PRODUCT", GetMessage("What to do with the product, if all offers are deactivated?"), array("list"), array("deactivate"=>GetMessage("deactivate"),"nothing"=>GetMessage("nothing"))),
);

$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
    array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "perfmon_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

CModule::IncludeModule($module_id);

if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/perfmon/prolog.php");

    if(strlen($RestoreDefaults)>0)
        COption::RemoveOption("CATALOG_BANNER_LINE_HEIGHT");
    else
    {
        //printvar('$arAllOptions',$arAllOptions);

        foreach($arAllOptions as $arOption)
        {
            $name=$arOption[0];
            $val=$_REQUEST[$name];
            // @todo: проверка безопасности должна быть тут!

            printvar($name,$val);

            COption::SetOptionString($module_id, $name, $val);
        }
    }

    ob_start();
    $Update = $Update.$Apply;
    //require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
    ob_end_clean();
}



?>

<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
    <?
    $tabControl->Begin();
    $tabControl->BeginNextTab();
    $arNotes = array();


    foreach($arAllOptions as $arOption):

        $val = COption::GetOptionString($module_id, $arOption[0], $arOption[3]);
        $type = $arOption[2];

		if($type[0]=="label"){
			?>
				<tr><td colspan=2 style='text-align:center'><b><?=$arOption[1]?></b></td></tr>
			<?
			continue;
		}

        if(isset($arOption[4]))
            $arNotes[] = $arOption[4];
        ?>
        <tr>
            <td width="40%" nowrap <?if($type[0]=="textarea") echo 'class="adm-detail-valign-top"'?>>
                <?if(isset($arOption[4])):?>
                    <span class="required"><sup><?echo count($arNotes)?></sup></span>
                <?endif;?>
                <label for="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo $arOption[1]?>:</label>
            <td width="60%">
                <?if($type[0]=="checkbox"):?>
                    <input type="checkbox" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>" value="Y"<?if($val=="Y")echo" checked";?>>
                <?elseif($type[0]=="text"):?>
                    <input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialcharsbx($val)?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>"><?if($arOption[0] == "slow_sql_time") echo GetMessage("PERFMON_OPTIONS_SLOW_SQL_TIME_SEC")?>
				<?elseif($type[0]=="list"):?>
					<select name="<?=htmlspecialcharsbx($arOption[0])?>">
						<?php
						foreach ($arOption[3] as $keyOption => $valueOption){
							?>
								<option value="<?=$keyOption?>"<?=($keyOption==htmlspecialcharsbx($val) ? " selected":"")?>><?=$valueOption?></option>
							<?
						}
						?>
					</select>
                <?elseif($type[0]=="textarea"):?>
                    <textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialcharsbx($arOption[0])?>" id="<?echo htmlspecialcharsbx($arOption[0])?>"><?echo htmlspecialcharsbx($val)?></textarea>
                <?elseif($type[0]=="mlist"):
                    $mSelect = array();
                    if(is_array($val)){
                        foreach ($val as $arSelect) {
                           $mSelect[$arSelect] = " selected";
                        }
                    }

                    ?>


                    <select multiple id="<?echo htmlspecialcharsbx($arOption[0])?>" name="<?echo htmlspecialcharsbx($arOption[0])?>[]">
                        <option val="123">123</option>
                        <option val="567">567</option>
                    </select>




                <?endif?>
            </td>
        </tr>
    <?endforeach?>

    <?$tabControl->BeginNextTab();?>
    <?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
    <?$tabControl->Buttons();?>


    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
    <input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
    <?if(strlen($_REQUEST["back_url_settings"])>0):?>
        <input <?if ($RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
        <input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
    <?endif?>
    <input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
    <?=bitrix_sessid_post();?>
    <?$tabControl->End();?>
</form>
<?
if(!empty($arNotes))
{
    echo BeginNote();
    foreach($arNotes as $i => $str)
    {
        ?><span class="required"><sup><?echo $i+1?></sup></span><?echo $str?><br><?
    }
    echo EndNote();
}
?>
<?endif;?>
