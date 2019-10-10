<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

ob_clean();
?>
<?$APPLICATION->IncludeComponent(
	"uvelirsoft:viewed",
	"",
Array(
"TITLE" => "Вы недавно смотрели",
"IBLOCK_ID" => 1,
"PRICE_TYPE" => 1
)
);?>