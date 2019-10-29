<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

<?
$MODULE_ID = "delement.freespace";
if (CModule::IncludeModule($MODULE_ID)) {
    $type_filesystem = COption::GetOptionString($MODULE_ID, "type_filesystem");
    $want_space = COption::GetOptionString($MODULE_ID, "free_space");
    
    if ($type_filesystem) {
        $busy_place = COption::GetOptionString($MODULE_ID, "busy_place");
        $total_space = COption::GetOptionString($MODULE_ID, "all_space");
        $free_space = $total_space - $busy_place;
    } else {
        $total_space = disk_total_space($_SERVER["DOCUMENT_ROOT"]) / (1024 * 1024);
        $free_space = disk_free_space($_SERVER["DOCUMENT_ROOT"]) / (1024 * 1024);
        $busy_place = $total_space- $free_space;
    }
    
    $total_space = number_format($total_space, 2, '.', ' ');
    $free_space = number_format($free_space, 2, '.', ' ');
    $busy_place = number_format($busy_place, 2, '.', ' ');
    $want_space = number_format($want_space, 2, '.', ' ');
    
    $td_col = 2;
    if ($busy_place) $td_col++;
    if ($want_space) $td_col++;
    ?>
    <div class="bx-gadgets-info">

        <div class="bx-gadgets-content-padding-rl">
            <table class="bx-gadgets-info-site-table" cellspacing="0">
                <tbody>
                    <tr>	
                        <td class="bx-gadget-gray">
                            <?= GetMessage("GD_ALL_SPACE") ?>:
                        </td>	
                        <td>
                            <?= $total_space ?> <?= GetMessage("GD_MB") ?>
                        </td>	
                        <td rowspan="<?=$td_col?>" style="width: 100px">
                            <a href="http://www.d-element.ru/" target="_blank">
                                <img src="/bitrix/gadgets/delement/freespace/img/logo_de.png" />
                            </a>
                        </td>
                    </tr>
                    <? if ($busy_place) { ?>
                        <tr>	
                            <td class="bx-gadget-gray">
                                <?= GetMessage("GD_BUSY_SPACE") ?>:
                            </td>	
                            <td>
                                <?= $busy_place ?> <?= GetMessage("GD_MB") ?>
                            </td>	
                        </tr>
                    <? } ?>
                    <tr>	
                        <td class="bx-gadget-gray">
                            <?= GetMessage("GD_FREE_SPACE") ?>:
                        </td>	
                        <td>
                            <?= $free_space ?> <?= GetMessage("GD_MB") ?>
                        </td>	
                    </tr>
                    <? if ($want_space) { ?>
                        <tr>	
                            <td class="bx-gadget-gray">
                                <?= GetMessage("GD_WANT_SPACE") ?>:
                            </td>	
                            <td>
                                <?= $want_space ?> <?= GetMessage("GD_MB") ?>
                            </td>	
                        </tr>
                    <? } ?>
                </tbody>
            </table>
        </div>	
    </div>
    <?
}

