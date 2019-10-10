<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Отзывы о компании \"Кристалл Мечты\"");
$APPLICATION->SetTitle("Отзывы покупателей");
?><div class="col-xs-12">
	<p>
 <a href="/o-kompanii/comments/">Звёзды о нас</a> <span style="margin-left:20px;font-weight:bold">Отзывы покупателей</span>
	</p>
</div>
<?$APPLICATION->IncludeComponent(
	"uvelirsoft:reviews",
	"template_main",
	Array(
		"COMPONENT_TEMPLATE" => "template_main",
		"COUNT_PAGE" => "10",
		"ELEMENT_ID" => "",
		"IBLOCK_ID" => "55",
		"MODERATION_GROUP" => "1"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>