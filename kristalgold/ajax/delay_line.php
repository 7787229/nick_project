<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

            <?$APPLICATION->IncludeComponent(
                    "uvelirsoft:basket.delay",
                    "",
                    Array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "PATH_TO_DELAY" => "/magazin/personal/cart/?delay=yes"
                    )
            );?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>