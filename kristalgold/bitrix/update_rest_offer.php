<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

define("IBLOCK_ID","1");
// опредеяем группы пользователя проверяем на принадлежность к админской группе
if(!in_array(1, $USER->GetUserGroupArray())) die("access denied");

global $USER,$APPLICATION;

if(!CModule::IncludeModule("iblock")) die("api error #1");
if(!CModule::IncludeModule("catalog")) die("api error #2");

/*
заберем настройки из модуля
1) Что делать с торговым предложением если доступное колличество равно или меньше нуля, варианты deactivate|zerro
2) Что делать с товаром, если ВСЕ торговые предложения деактивированы, варианты deactivate|nothing
*/

$arCatalog = CCatalog::GetByID(IBLOCK_ID);
define("OFFERS_IBLOCK_ID", $arCatalog["OFFERS_IBLOCK_ID"]);

define("CATALOG_UPDATE_REST_OFFER", COption::GetOptionString("uvelirsoft", "CATALOG_UPDATE_REST_OFFER"));
define("CATALOG_UPDATE_REST_OFFER_PRODUCT", COption::GetOptionString("uvelirsoft", "CATALOG_UPDATE_REST_OFFER_PRODUCT"));

// проанализируем переменные в запросе, в запросе мы ждем 2 переменные: GUID(внешний код) и кол-во, которое необходимо сминусовать

$XML_ID = str_replace("_","#",htmlspecialcharsbx($_REQUEST["XML_ID"]));
$CNT = intval(htmlspecialcharsbx($_REQUEST["CNT"]));

if(empty($XML_ID)) die("XML_ID empty");
if(empty($CNT)) die("CNT empty");

$el = new CIBlockElement;

$rs = $el->GetList(array(),array("IBLOCK_ID" => (OFFERS_IBLOCK_ID ? OFFERS_IBLOCK_ID:IBLOCK_ID), "XML_ID" => $XML_ID),false,false,array("ID","IBLOCK_ID"));

if($arItem = $rs->Fetch()){

    // найдем текущее кол-во товара н складе
    $arItemCatalog = CCatalogProduct::GetByID($arItem['ID']);

    if($arItemCatalog["QUANTITY"]<=$CNT){
            $NEW_REST = 0;
        }else{
            $NEW_REST = $arItemCatalog["QUANTITY"] - $CNT;
    }

    // обновляем кол-во торгового предложения
    $arFieldsQuan = Array(
		"QUANTITY" => $NEW_REST
	);

	if(!CCatalogProduct::Update($arItem['ID'],$arFieldsQuan)){
        echo "error rest update";
        exit;
    }

    // деактивируем предложение, если есть такая настройка и вес == 0
    if(CATALOG_UPDATE_REST_OFFER == "deactivate" and $NEW_REST == 0){

        // деактивируем предложение
        if(!$el->Update($arItem['ID'],array("ACTIVE" => "N"))){
            echo "error deactivate offer";
        }
    }

    // деактивируем товар,если все предложения неактивные и есть такая настройка
    if(OFFERS_IBLOCK_ID and CATALOG_UPDATE_REST_OFFER_PRODUCT == "deactivate"){

        // найдем идентификатор товара
        $mxResult = CCatalogSku::GetProductInfo($arItem['ID']);
        if (!is_array($mxResult)){
            echo "offer error";
            exit;
        }

        // найдем все активные предложения товара
        $rsOffers = CCatalogSKU::getOffersList(
            $mxResult["ID"],
            IBLOCK_ID,
            array("ACTIVE" => "Y"),
            array(),
            array()
        );

        // деактивируем товар если нет активных торговых предложений
        if(count($rsOffers[$mxResult["ID"]])==0){
            if(!$el->Update($mxResult["ID"],array("ACTIVE" => "N"))){
                echo "error deactivate product";
            }
        }
    }

}else{
    die("not found");
}

die("ok");
