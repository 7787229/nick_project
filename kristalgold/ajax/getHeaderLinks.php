<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("main");
global $USER;
?>
<span class="bx-basket-block">
    <?if ($USER->IsAuthorized()):
        $name = trim($USER->GetFullName());
        if (! $name)
            $name = trim($USER->GetLogin());
        if (strlen($name) > 15)
            $name = substr($name, 0, 12).'...';
        ?>
        <a href="/magazin/personal/"><i class="fa fa-user" aria-hidden="true"></i></a>
        &nbsp;
        <a href="?logout=yes">Выйти</a>
    <?else:?>
        <a href="javascript:void(0)" onclick='$("#auth_and_register").modal("show");'>Войти</a><!-- ?login=yes -->
    <?endif?>
</span>
