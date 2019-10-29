<?
if (!$USER->IsAdmin())
    return;

IncludeModuleLangFile(__FILE__);

$MODULE_ID = "delement.freespace";

$type_filesystem = array(
    0 => GetMessage("TYPE_FILESYSTEM_1"),
    1 => GetMessage("TYPE_FILESYSTEM_2"),
);

$arAllOptions = array(
    array("enabled", GetMessage("ENABLED"), "N", array("checkbox", "Y")),
    array("delete_cache", GetMessage("DELETE_CACHE"), "Y", array("checkbox", "Y")),
    array("remove_backups", GetMessage("REMOVE_BACKUPS"), "N", array("checkbox", "N")),
    array("email_notifer", GetMessage("EMAIL_NOTIFER"), "Y", array("checkbox", "Y")),
    array("email_for_norifer", GetMessage("EMAIL_FOR_NOTIFER"), "info@site.ru", array("text", 50)),
    array("agent_time", GetMessage("AGENT_TIME"), "3600", array("text", 50)),
    getMessage("SPACE"),
    array("type_filesystem", GetMessage("TYPE_FILESYSTEM"),"0",Array("selectbox", $type_filesystem)),
    array("free_space", GetMessage("FREE_SPACE"), "300", array("text", 3)),
    array("all_space", GetMessage("ALL_SPACE"), "0", array("text", 3)),
    getMessage("NOT_CRON"),
    array("time_out", GetMessage("TIME_OUT"), "5", array("text", 50)),
);
$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "ib_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($REQUEST_METHOD == "POST" && strlen($Update . $Apply . $RestoreDefaults) > 0 && check_bitrix_sessid()) {
    if (strlen($RestoreDefaults) > 0) {
        COption::RemoveOption($MODULE_ID);
    } else {
        foreach ($arAllOptions as $arOption) {
            $name = $arOption[0];
            $val = $_REQUEST[$name];
            if (isset($arOption[3]) && isset($arOption[3][0]) && $arOption[3][0] == "checkbox" && $val != "Y")
                $val = "N";
            COption::SetOptionString($MODULE_ID, $name, $val, $arOption[1]);
        }
    }
    CAgent::RemoveAgent("do_free_space();", $MODULE_ID);
    CAgent::AddAgent(
            "do_free_space();", $MODULE_ID, "N", COption::GetOptionString($MODULE_ID, "agent_time")
    );

    if (strlen($Update) > 0 && strlen($_REQUEST["back_url_settings"]) > 0)
        LocalRedirect($_REQUEST["back_url_settings"]);
    else
        LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . urlencode($mid) . "&lang=" . urlencode(LANGUAGE_ID) . "&back_url_settings=" . urlencode($_REQUEST["back_url_settings"]) . "&" . $tabControl->ActiveTabParam());
}



$tabControl->Begin();
?>
<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= urlencode($mid) ?>&amp;lang=<? echo LANGUAGE_ID ?>">
<? $tabControl->BeginNextTab(); ?>
<?
foreach ($arAllOptions as $arOption) {
    if (count($arOption) == 1) { ?>
        <tr class="heading">
            <td colspan="2"><?=$arOption?></td>
        </tr>
    <? } else {
        $val = COption::GetOptionString($MODULE_ID, $arOption[0], $arOption[2]);
        $type = $arOption[3];
        ?>
            <tr>
                <td width="40%" nowrap <? if ($type[0] == "textarea") echo 'class="adm-detail-valign-top"' ?>>
                    <label for="<? echo htmlspecialcharsbx($arOption[0]) ?>"><? echo $arOption[1] ?>:</label>
                </td>
                <td width="60%">
            <? if ($type[0] == "checkbox"): ?>
                        <input type="checkbox" id="<? echo htmlspecialcharsbx($arOption[0]) ?>" name="<? echo htmlspecialcharsbx($arOption[0]) ?>" value="Y"<? if ($val == "Y") echo" checked"; ?>>
            <? elseif ($type[0] == "text"): ?>
                        <input type="text" size="<? echo $type[1] ?>" maxlength="255" value="<? echo htmlspecialcharsbx($val) ?>" name="<? echo htmlspecialcharsbx($arOption[0]) ?>">
            <? elseif ($type[0] == "textarea"): ?>
                        <textarea rows="<? echo $type[1] ?>" cols="<? echo $type[2] ?>" name="<? echo htmlspecialcharsbx($arOption[0]) ?>"><? echo htmlspecialcharsbx($val) ?></textarea>
            <? elseif ($type[0] == "selectbox"): ?>
                        <select name="<? echo htmlspecialcharsbx($arOption[0]) ?>">
                            <? foreach ($type[1] as $t_key=>$t_val) { ?>
                                <option value="<?=htmlspecialcharsbx($t_key);?>" <?=(htmlspecialcharsbx($val) == htmlspecialcharsbx($t_key)) ? "selected" : "" ?>>
                                    <?=htmlspecialcharsbx($t_val);?>
                                </option>
                            <? } ?>
                        </select>
            <? endif ?>
                </td>
            </tr>
        <?
    }
}
?>
            <? $tabControl->Buttons(); ?>
    <input type="submit" name="Update" value="<?= GetMessage("SAVE") ?>" title="<?= GetMessage("SAVE") ?>" class="adm-btn-save">
    <input type="submit" name="Apply" value="<?= GetMessage("APPLY") ?>" title="<?= GetMessage("APPLY") ?>">
            <? if (strlen($_REQUEST["back_url_settings"]) > 0): ?>
        <input type="button" name="Cancel" value="<?= GetMessage("CANCEL") ?>" title="<?= GetMessage("CANCEL") ?>" onclick="window.location = '<? echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"])) ?>'">
        <input type="hidden" name="back_url_settings" value="<?= htmlspecialcharsbx($_REQUEST["back_url_settings"]) ?>">
    <? endif ?>
    <input type="submit" name="RestoreDefaults" title="<? echo GetMessage("RESTORE_DEFAULTS") ?>" OnClick="return confirm('<? echo AddSlashes(GetMessage("RESTORE_DEFAULTS_WARNING")) ?>')" value="<? echo GetMessage("RESTORE_DEFAULTS") ?>">
    <?= bitrix_sessid_post(); ?>
    <? $tabControl->End(); ?>
</form>