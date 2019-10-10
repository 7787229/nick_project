<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "интернет магазин ювелирный, на заказ изделия, драгоценные украшения, золото, серебро, купить выбрать заказать онлайн кольца серьги броши с камнями алмазы, москва");
$APPLICATION->SetPageProperty("title", "Ювелирный интернет-магазин \"Кристалл Мечты\" | Купить ювелирные изделия от производителя в Москве");
$APPLICATION->SetPageProperty("description", "Ювелирные украшения по отличным ценам от Ювелирного Дома «Кристалл мечты». Предлагаем большой выбор украшений, сочетающих в себе уникальный дизайн и высочайшее качество исполнения. Действуют акции и спецпредложения!");
$APPLICATION->SetTitle("Ювелирный интернет-магазин 'Кристалл Мечты' | Купить ювелирные изделия от производителя в Москве недорого");
?><? $APPLICATION->IncludeComponent(
		"uvelirsoft:main.page",
		".default",
		array(
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"MAIN_ARTICLE_FIELD" => "ARTNUMBER",
			"MAIN_BANNERS_HEIGHT" => "375",
			"MAIN_BANNERS_IBLOCK_ID" => "13",
			"MAIN_BANNERS_IBLOCK_TYPE" => "content",
			"MAIN_BANNERS_SORT" => "500",
			"MAIN_BANNERS_TITLE" => "",
			"MAIN_SLIDER1_CODE" => "DISCOUNT",
			"MAIN_SLIDER1_COUNT" => "10",
			"MAIN_SLIDER1_FIELD_VALUE" => "да",
			"MAIN_SLIDER1_IBLOCK_ID" => "1",
			"MAIN_SLIDER1_IMAGE_HEIGHT" => "300",
			"MAIN_SLIDER1_IMAGE_QUALITY" => "90",
			"MAIN_SLIDER1_IMAGE_WIDTH" => "300",
			"MAIN_SLIDER1_PRICE_TYPE" => "1",
			"MAIN_SLIDER1_SORT" => "500",
			"MAIN_SLIDER1_TITLE" => "РАСПРОДАЖА",
			"MAIN_SLIDER1_TYPE" => "content",
			"MAIN_SLIDER2_CODE" => "NEWPRODUCT",
			"MAIN_SLIDER2_COUNT" => "10",
			"MAIN_SLIDER2_FIELD_VALUE" => "да",
			"MAIN_SLIDER2_IBLOCK_ID" => "1",
			"MAIN_SLIDER2_IBLOCK_TYPE" => "content",
			"MAIN_SLIDER2_IMAGE_HEIGHT" => "300",
			"MAIN_SLIDER2_IMAGE_QUALITY" => "90",
			"MAIN_SLIDER2_IMAGE_WIDTH" => "300",
			"MAIN_SLIDER2_PRICE_TYPE" => "1",
			"MAIN_SLIDER2_SORT" => "500",
			"MAIN_SLIDER2_TITLE" => "Новинки",
			"MAIN_SLIDER3_CODE" => "BESTSELLER",
			"MAIN_SLIDER3_COUNT" => "10",
			"MAIN_SLIDER3_FIELD_VALUE" => "да",
			"MAIN_SLIDER3_IBLOCK_ID" => "1",
			"MAIN_SLIDER3_IBLOCK_TYPE" => "content",
			"MAIN_SLIDER3_IMAGE_HEIGHT" => "300",
			"MAIN_SLIDER3_IMAGE_QUALITY" => "90",
			"MAIN_SLIDER3_IMAGE_WIDTH" => "300",
			"MAIN_SLIDER3_PRICE_TYPE" => "1",
			"MAIN_SLIDER3_SORT" => "500",
			"MAIN_SLIDER3_TITLE" => "ХИТ ПРОДАЖ",
			"MAIN_SLIDER_ADDITIONAL_HEIGHT" => "500",
			"MAIN_SLIDER_ADDITIONAL_IBLOCK_ID" => "48",
			"MAIN_SLIDER_ADDITIONAL_IBLOCK_TYPE" => "content",
			"MAIN_SLIDER_ADDITIONAL_PICTURE_FIELD" => "DETAIL_PICTURE",
			"MAIN_SLIDER_ADDITIONAL_SORT" => "500",
			"MAIN_SLIDER_ADDITIONAL_TITLE_FIELD" => "PROPERTY_SLIDER_TITLE",
			"MAIN_SLIDER_ADDITIONAL_TITLE_URL" => "PROPERTY_SLIDER_URL",
			"MAIN_SLIDER_HEIGHT" => "500",
			"MAIN_SLIDER_IBLOCK_ID" => "4",
			"MAIN_SLIDER_IBLOCK_TYPE" => "content",
			"MAIN_SLIDER_PICTURE_FIELD" => "DETAIL_PICTURE",
			"MAIN_SLIDER_TITLE_FIELD" => "PROPERTY_SLIDER_TITLE",
			"MAIN_SLIDER_TITLE_URL" => "PROPERTY_SLIDER_URL",
			"MAIN_TABS_COUNT" => "3",
			"MAIN_TABS_SORT" => "500",
			"MAIN_TAB_0_CODE" => "DISCOUNT",
			"MAIN_TAB_0_COUNT" => "10",
			"MAIN_TAB_0_FIELD_VALUE" => "да",
			"MAIN_TAB_0_IBLOCK_ID" => "1",
			"MAIN_TAB_0_IMAGE_HEIGHT" => "300",
			"MAIN_TAB_0_IMAGE_QUALITY" => "90",
			"MAIN_TAB_0_IMAGE_WIDTH" => "300",
			"MAIN_TAB_0_PRICE_TYPE" => "1",
			"MAIN_TAB_0_SORT" => "100",
			"MAIN_TAB_0_TITLE" => "Распродажа",
			"MAIN_TAB_0_TYPE" => "catalog",
			"MAIN_TAB_1_CODE" => "NEWPRODUCT",
			"MAIN_TAB_1_COUNT" => "10",
			"MAIN_TAB_1_FIELD_VALUE" => "да",
			"MAIN_TAB_1_IBLOCK_ID" => "1",
			"MAIN_TAB_1_IMAGE_HEIGHT" => "300",
			"MAIN_TAB_1_IMAGE_QUALITY" => "90",
			"MAIN_TAB_1_IMAGE_WIDTH" => "300",
			"MAIN_TAB_1_PRICE_TYPE" => "1",
			"MAIN_TAB_1_SORT" => "500",
			"MAIN_TAB_1_TITLE" => "Новинки",
			"MAIN_TAB_1_TYPE" => "catalog",
			"MAIN_TAB_2_CODE" => "BESTSELLER",
			"MAIN_TAB_2_COUNT" => "10",
			"MAIN_TAB_2_FIELD_VALUE" => "да",
			"MAIN_TAB_2_IBLOCK_ID" => "1",
			"MAIN_TAB_2_IMAGE_HEIGHT" => "300",
			"MAIN_TAB_2_IMAGE_QUALITY" => "90",
			"MAIN_TAB_2_IMAGE_WIDTH" => "300",
			"MAIN_TAB_2_PRICE_TYPE" => "1",
			"MAIN_TAB_2_SORT" => "300",
			"MAIN_TAB_2_TITLE" => "Хит продаж",
			"MAIN_TAB_2_TYPE" => "catalog",
			"MAIN_VIEWED_SORT" => "500",
			"SHOW_ADDITIONAL_SLIDER" => "N",
			"SHOW_BANNERS" => "N",
			"SHOW_SLIDER1" => "N",
			"SHOW_SLIDER2" => "N",
			"SHOW_SLIDER3" => "N",
			"SHOW_TABS" => "N",
			"SHOW_VIEWED_PRODUCTS" => "Y",
			"COMPONENT_TEMPLATE" => ".default",
			"COMPOSITE_FRAME_MODE" => "A",
			"COMPOSITE_FRAME_TYPE" => "AUTO"
		),
		false
	); ?>
<div class="text_bottom">
	<div class="mobile_category_home" style = "display: none;">
	<div class = 'banners_top_catalog'>
				<?$APPLICATION->IncludeComponent(
					"bitrix:news.list",
					"banners_top_catalog",
					Array(
						"ACTIVE_DATE_FORMAT" => "d.m.Y",
						"ADD_SECTIONS_CHAIN" => "N",
						"AJAX_MODE" => "N",
						"AJAX_OPTION_ADDITIONAL" => "",
						"AJAX_OPTION_HISTORY" => "N",
						"AJAX_OPTION_JUMP" => "N",
						"AJAX_OPTION_STYLE" => "Y",
						"CACHE_FILTER" => "N",
						"CACHE_GROUPS" => "Y",
						"CACHE_TIME" => "36000000",
						"CACHE_TYPE" => "A",
						"CHECK_DATES" => "Y",
						"COMPONENT_TEMPLATE" => "banners_top_catalog",
						"DETAIL_URL" => "",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"DISPLAY_DATE" => "Y",
						"DISPLAY_NAME" => "Y",
						"DISPLAY_PICTURE" => "Y",
						"DISPLAY_PREVIEW_TEXT" => "Y",
						"DISPLAY_TOP_PAGER" => "N",
						"FIELD_CODE" => array(0=>"DETAIL_PICTURE",1=>"",),
						"FILTER_NAME" => "",
						"HIDE_LINK_WHEN_NO_DETAIL" => "N",
						"IBLOCK_ID" => "3",
						"IBLOCK_TYPE" => "content",
						"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
						"INCLUDE_SUBSECTIONS" => "Y",
						"MESSAGE_404" => "",
						"NEWS_COUNT" => "20",
						"PAGER_BASE_LINK_ENABLE" => "N",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "N",
						"PAGER_SHOW_ALWAYS" => "N",
						"PAGER_TEMPLATE" => ".default",
						"PAGER_TITLE" => "Новости",
						"PARENT_SECTION" => "",
						"PARENT_SECTION_CODE" => "",
						"PREVIEW_TRUNCATE_LEN" => "",
						"PROPERTY_CODE" => array(0=>"BANNER_LINK",1=>"BANNER_WIDTH",2=>"BANNER_TITLE",3=>"",),
						"SET_BROWSER_TITLE" => "N",
						"SET_LAST_MODIFIED" => "N",
						"SET_META_DESCRIPTION" => "N",
						"SET_META_KEYWORDS" => "N",
						"SET_STATUS_404" => "N",
						"SET_TITLE" => "N",
						"SHOW_404" => "N",
						"SORT_BY1" => "SORT",
						"SORT_BY2" => "SORT",
						"SORT_ORDER1" => "ASC",
						"SORT_ORDER2" => "ASC"
					)
				);?>
				</div>
	</div>
	<h1>Ювелирный интернет-магазин "Кристалл Мечты"</h1>
	<h2>Украшения изысканного дизайна</h2>
	<p>
		Роскошные ювелирные украшения – это настоящие произведения искусства. Драгоценности являются не только удачным вложением средств, но и достойным подарком близким людям. Если вы ищете, где купить качественные украшения по приемлемым ценам – вы попали туда, куда нужно.
	</p>
	<p>
		Ювелирный Дом «Кристалл мечты» на протяжении многих лет изготавливает и реализует украшения. Нам доверяют как простые покупатели, так и знаменитые личности, звезды кино и эстрады, представители fashion-индустрии.
	</p>
	<p>
		Компания имеет прямые поставки алмазов с якутских месторождений. Команда профессиональных сотрудников и собственное производство позволяют создавать эксклюзивные ювелирные коллекции. Осуществляется изготовление изделий на заказ. Наши мастера искусно подчеркивают уникальность материалов, цвет и сияние камней, чистоту линий, превращая обычные на первый взгляд изделия в настоящие произведения искусства, которые хранят историю, эмоции и чувства.
	</p>
	<a style="font-size: 12px;" onclick="document.getElementById('maintext').style.display = document.getElementById('maintext').style.display == 'block' ? 'none':'block'; return false;" href="javascript:void();">
		<h2>Преимущества приобретения ювелирных украшений в интернет-магазине «Кристалл мечты»</h2>
	</a>
	<div class="maintext" id="maintext" style="display: block;">
		<ul>
			<li>Большой ассортимент авторских украшений и эксклюзивных моделей. Каждое ювелирное изделие можно назвать действительно элитным, ведь мастера подходят к производству моделей со всей ответственностью и при этом используют высококлассное сырье.
			</li>
			<li>Выгодные цены на ювелирные изделия от производителя.</li>
			<li>Специальные предложения, акции и скидки постоянным покупателям: можно купить ювелирное украшение по стоимости ниже до 90% от номинальной!</li>
			<li>Распространяется гарантия. Через несколько лет приобретенные изделия будут радовать вас своей красотой, сохраняя первоначальный вид.</li>
		</ul>
		<p>
			Каталог ювелирного магазина в Москве обновляется каждую неделю. Индивидуальный подход к каждому клиенту и удобный способ заказа через интернет позволяют с максимальным комфортом выбрать ювелирное изделие и купить его в нашем интернет-магазине с бесплатной доставкой.
		</p>
		<p>
			Наши элитные украшения смотрятся стильно и изысканно. Они обязательно будут привлекать к вам посторонние взгляды.
		</p>
		<h2>Каталог элитных ювелирных украшений</h2>
		<p>
			Мы всегда рады своим гостям и приглашаем вас в путешествие по страничкам интернет-магазина, во время которого обязательно стоит заглянуть в каталог элитных ювелирных украшений, где представлены изысканный шик и очаровательная утонченность, сливающиеся в истинное искусство.
		</p>
		<p>
			Наши мастера изготавливают также изделия на заказ для любого события – мы воплотим в жизнь любой стиль и формы. Ведь день рождения, юбилей, годовщина со дня бракосочетания, либо простое желание сделать приятный сюрприз любимому человеку – это именно тот предлог, который мотивирует преподнести особый драгоценный подарок.
		</p>
		<p>
			В нашем интернет-магазине можно найти ювелирные украшения на любой вкус. Каталог включает в себя:
		</p>
		<ul>
			<li>серьги,</li>
			<li>броши,</li>
			<li>браслеты,</li>
			<li>кольца,</li>
			<li>запонки,</li>
			<li>колье,</li>
			<li>подвески.</li>
		</ul>
		<p>
			Модели изделий отличаются неповторимым дизайном. Ювелирный Дом «Кристалл мечты» реализует мужские и женские коллекции. Весь ассортимент, представленный в каталоге, отличается высоким качеством

		</p>
		<ul>
			<li>бриллианты;</li>
			<li>изумруды;</li>
			<li>сапфиры;</li>
			<li>рубины;</li>
			<li>топазы;</li>
			<li>фацетированные камни.</li>
		</ul>
		<p>
			В современном обществе внешний вид и его детали играют важную роль при составлении первого впечатления. Эксклюзивные ювелирные украшения помогут блистать и оставаться при этом не такой, как все. Но восхищение вызывают не только украшения для представительниц прекрасного пола. Наша компания специально работала над созданием мужской коллекции ювелирных изделий, которые обладают способностью выделить аристократичность вкуса и придать образу индивидуальность.
		</p>
		<p>
			Одним из главных наших преимуществ является не только многообразие модельного ряда, но и то, что стоимость элитного украшения неизменно лояльна.
		</p>
		<p>
			Уточнить стоимость того или иного изделия можно по телефону: +7-495-625-19-45. В нашем интернет-магазине клиенты имеют возможность выбрать совершенно любые элитные ювелирные украшения, или заказать уникальное драгоценное изделие.
		</p>
	</div>
	<script type="text/javascript">
		// <![CDATA[
		document.getElementById('maintext').style.display = 'none';
		// ]]&gt;
	</script>
</div>
<br>




<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>