<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('iblock');
CModule::IncludeModule('sale');

global $USER;
/*
TODO:
 *  1) Вынести дублируемый код в функцию или в класс формирование карточек по типам: BESTSELLER, NEWPRODUCT, DISCOUNT
 * 
*/
// инициализируем кеш
$obCache  = new CPHPCache();
// определяем переменные
$cacheLifetime  = 3600; 
$cacheID        = 'MAINPAGE'; 
$cachePath      = '/UVELIRSOFT/';
    
if($obCache->InitCache($cacheLifetime, $cacheID, $cachePath) )
    {
       $rsResult = $obCache->GetVars();   
       $arResult = $rsResult[$cacheID];
    }
        elseif( $obCache->StartDataCache()  )
    {    
        $arResult = array();
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
				
				// main slider
				$arSelect = Array("ID", "NAME", "IBLOCK_ID", $arParams['MAIN_SLIDER_PICTURE_FIELD'],$arParams['MAIN_SLIDER_TITLE_FIELD'],$arParams['MAIN_SLIDER_TITLE_URL']);
				$arFilter = Array("IBLOCK_ID"=>(int)$arParams['MAIN_SLIDER_IBLOCK_ID'], "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>50), $arSelect);
				while($ar = $res->GetNext()) {

					if(!empty($ar["DETAIL_PICTURE"])){
						$rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
						$slide["PICTURE"] = $rsFile->Fetch();
					}else{
						continue;
					}
					$slide["TITLE"] = $ar[$arParams['MAIN_SLIDER_TITLE_FIELD']."_VALUE"];
					$slide["URL"] = $ar[$arParams['MAIN_SLIDER_TITLE_URL']."_VALUE"];
					$slide["NAME"] = $ar["NAME"];
					$arResult["SLIDER"][] = $slide;
				}
			
				// main video
				$arResult['VIDEO'] = array(
					"POSTER" => $arParams["VIDEO_POSTER"],
					"YOUTUBE_VIDEO" => $arParams["VIDEO_FROM_YOUTUBE"],
					"VIDEO_ID" => $arParams["VIDEO_ID"],
					"VIDEO_WEBM" => $arParams["VIDEO_LINK_WEBM"],
					"VIDEO_MP4" => $arParams["VIDEO_LINK_MP4"],
					"VIDEO_OGG" => $arParams["VIDEO_LINK_OGG"],
				);
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                // NEW
                if($arParams["SHOW_NEW"]){

                        $arResult["NEW"]["TITLE"] = $arParams['MAIN_NEW_TITLE'];
                        $arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "CATALOG_GROUP_".$arParams['MAIN_NEW_PRICE_TYPE'], "PROPERTY_".$arParams['MAIN_ARTICLE_FIELD'], "PROPERTY_NEWPRODUCT", "PROPERTY_DISCOUNT", "PROPERTY_BESTSELLER");
                        $arFilter = Array(
                            "IBLOCK_ID"=>(int)$arParams["MAIN_NEW_IBLOCK_ID"], 
                            "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", 
                            "PROPERTY_NEWPRODUCT_VALUE"=>$arParams["MAIN_NEW_FIELD_VALUE"]
                        );
                        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>50),$arSelect);

                        while($ar = $res->GetNext()) {

                            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($ar["ID"], $USER->GetUserGroupArray(), "N");
                            // найдем максимальную скидку
                            $tmpDiscount = array();
                            $maxDiscount = "";
                            foreach ($arDiscounts as $arDiscount) {
                                    $tmpDiscount[] = intval($arDiscount["VALUE"]);
                            }
                            if(count($tmpDiscount)>0){
                                    $maxDiscount = max($tmpDiscount);
                            }
                            // 							

                            // get PRICE /////////////////////
                            $Price = "";
                            if($arParams["MAIN_NEW_PRICE_TYPE"]>0){
                                $Price = number_format ($ar["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']], 0, '.', ' ' );
                                    
                                $typeItem = ""; // тип товара простой или с предложнеиями
                                
                                    if(CCatalogSKU::IsExistOffers($ar["ID"],(int)$arParams["MAIN_NEW_IBLOCK_ID"])){
                                        
                                        $mxResult = CCatalogSKU::GetInfoByProductIBlock((int)$arParams["MAIN_NEW_IBLOCK_ID"]); 
                                        if (is_array($mxResult)) 
                                        { 
                                            $rsOffers = CIBlockElement::GetList(array("CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']=>"ASC"),array('IBLOCK_ID' => $mxResult["IBLOCK_ID"], 'PROPERTY_'.$mxResult['SKU_PROPERTY_ID'] => $ar["ID"]),false,false,array("ID","IBLOCK_ID","PRICE","PREVIEW_PICTURE","DETAIL_PICTURE","CATALOG_GROUP_".$arParams['MAIN_NEW_PRICE_TYPE'])); 
                                            unset($Price_ID);
                                            while ($arOffer = $rsOffers->GetNext()) 
                                            { 
                                                if(!empty($arOffer["PREVIEW_PICTURE"])){
                                                    $rsFile = CFile::GetByID($arOffer["PREVIEW_PICTURE"]);
                                                    $arFile = $rsFile->Fetch();
                                                }elseif(!empty($arOffer["DETAIL_PICTURE"])){
                                                    $rsFile = CFile::GetByID($arOffer["DETAIL_PICTURE"]);
                                                    $arFile = $rsFile->Fetch();
                                                }elseif(!empty($ar["PREVIEW_PICTURE"])){
                                                    $rsFile = CFile::GetByID($ar["PREVIEW_PICTURE"]);
                                                    $arFile = $rsFile->Fetch(); 
                                                }elseif(!empty($ar["DETAIL_PICTURE"])){
                                                    $rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
                                                    $arFile = $rsFile->Fetch(); 
                                                }
                                                
                                                $price = $arOffer["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                                $priceFormat = number_format ($arOffer["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']], 0, '.', ' ' );
                                                $Price_ID = $arOffer["CATALOG_PRICE_ID_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                                $typeItem = "offers";
                                                $itemID = $arOffer["ID"];
                                                break; 
                                            } 
                                        } 
 
                                    }else{
                                        // картинка для простого товара
                                        if(!empty($ar["PREVIEW_PICTURE"])){
                                            $rsFile = CFile::GetByID($ar["PREVIEW_PICTURE"]);
                                            $arFile = $rsFile->Fetch();
                                        }else{
                                            $rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
                                            $arFile = $rsFile->Fetch();
                                        }
                                         // товар простой и берем идентификатор ценового предложения из товара
                                        $price = $ar["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                        $priceFormat = number_format ($ar["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']], 0, '.', ' ' );
                                        $Price_ID = $ar["CATALOG_PRICE_ID_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                        $typeItem = "simple";
                                        $itemID = $ar["ID"];
                                    }
                            }    
                            // end get Price /////////////////////
                            $arResult["NEW"]["ITEMS"][] = array(
                                "ID"                => $itemID,
                                "NAME"              => $ar["NAME"],
                                "ARTICLE"           => $ar["PROPERTY_".$arParams['MAIN_ARTICLE_FIELD']."_VALUE"],
                                "DETAIL_PAGE_URL"   => $ar["DETAIL_PAGE_URL"],
                                "PICTURE"           => "/upload/".$arFile["SUBDIR"]."/".$arFile["FILE_NAME"],
                                "PRICE"             => $price,
                                "PRICE_FORMAT"      => $priceFormat,
                                "PRICE_ID"          => $Price_ID,
                                "NEWPRODUCT"        => $ar["PROPERTY_NEWPRODUCT_VALUE"],
                                "BESTSELLER"        => $ar["PROPERTY_BESTSELLER_VALUE"],
                                "DISCOUNT"          => $ar["PROPERTY_DISCOUNT_VALUE"],
                                "DISCOUNT_PERCENT"  => $maxDiscount
                                    );
                            unset($ar);
                        }

                }
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                // BESTSELLER
                if($arParams["SHOW_BESTSELLER"]){

                        $arResult["BESTSELLER"]["TITLE"] = $arParams['MAIN_BESTSELLER_TITLE'];
                        $arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "CATALOG_GROUP_".$arParams['MAIN_BESTSELLER_PRICE_TYPE'], "PROPERTY_".$arParams['MAIN_ARTICLE_FIELD'], "PROPERTY_NEWPRODUCT", "PROPERTY_DISCOUNT", "PROPERTY_BESTSELLER");
                        $arFilter = Array(
                            "IBLOCK_ID"=>(int)$arParams["MAIN_BESTSELLER_IBLOCK_ID"], 
                            "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", 
                            "PROPERTY_BESTSELLER_VALUE"=>$arParams["MAIN_BESTSELLER_FIELD_VALUE"]
                        );
                        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>50),$arSelect);

                        while($ar = $res->GetNext()) {
							
                            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($ar["ID"], $USER->GetUserGroupArray(), "N");
                            // найдем максимальную скидку
                            $tmpDiscount = array();
                            $maxDiscount = "";
                            foreach ($arDiscounts as $arDiscount) {
                                    $tmpDiscount[] = intval($arDiscount["VALUE"]);
                            }
                            if(count($tmpDiscount)>0){
                                    $maxDiscount = max($tmpDiscount);
                            }

                            // get PRICE /////////////////////
                            $Price = "";
                            if($arParams["MAIN_BESTSELLER_PRICE_TYPE"]>0){
                                $Price = number_format ($ar["CATALOG_PRICE_".$arParams['MAIN_BESTSELLER_PRICE_TYPE']], 0, '.', ' ' );
                                    $typeItem = ""; // тип товара простой или с предложнеиями
                                    
                                    if(CCatalogSKU::IsExistOffers($ar["ID"],(int)$arParams["MAIN_BESTSELLER_IBLOCK_ID"])){
                                        $mxResult = CCatalogSKU::GetInfoByProductIBlock((int)$arParams["MAIN_BESTSELLER_IBLOCK_ID"]); 
                                        if (is_array($mxResult)) 
                                        { 
                                            unset($Price_ID);
                                            $rsOffers = CIBlockElement::GetList(array("CATALOG_PRICE_".$arParams['MAIN_BESTSELLER_PRICE_TYPE']=>"ASC"),array('IBLOCK_ID' => $mxResult["IBLOCK_ID"], 'PROPERTY_'.$mxResult['SKU_PROPERTY_ID'] => $ar["ID"]),false,false,array("ID","IBLOCK_ID","PRICE","PREVIEW_PICTURE", "DETAIL_PICTURE", "CATALOG_GROUP_".$arParams['MAIN_BESTSELLER_PRICE_TYPE'])); 
                                            while ($arOffer = $rsOffers->GetNext()) 
                                            { 
                                                
                                                if(!empty($arOffer["PREVIEW_PICTURE"])){
                                                    $rsFile = CFile::GetByID($arOffer["PREVIEW_PICTURE"]);
                                                    $arFile = $rsFile->Fetch();
                                                }elseif(!empty($arOffer["DETAIL_PICTURE"])){
                                                    $rsFile = CFile::GetByID($arOffer["DETAIL_PICTURE"]);
                                                    $arFile = $rsFile->Fetch();
                                                }elseif(!empty($ar["PREVIEW_PICTURE"])){
                                                    $rsFile = CFile::GetByID($ar["PREVIEW_PICTURE"]);
                                                    $arFile = $rsFile->Fetch(); 
                                                }elseif(!empty($ar["DETAIL_PICTURE"])){
                                                    $rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
                                                    $arFile = $rsFile->Fetch(); 
                                                }
                                                $price = $arOffer["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                                $priceFormat = number_format ($arOffer["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']], 0, '.', ' ' );
                                                $Price_ID = $arOffer["CATALOG_PRICE_ID_".$arParams['MAIN_BESTSELLER_PRICE_TYPE']];
                                                $typeItem = "offers";
                                                $itemID = $arOffer["ID"];
                                                break; 
                                            } 
                                        } 
                                    }else{
                                        // картинка для простого товара
                                        if(!empty($ar["PREVIEW_PICTURE"])){
                                            $rsFile = CFile::GetByID($ar["PREVIEW_PICTURE"]);
                                            $arFile = $rsFile->Fetch();
                                        }else{
                                            $rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
                                            $arFile = $rsFile->Fetch();
                                        }                                        
                                         // товар простой и берем идентификатор ценового предложения из товара
                                        $price = $ar["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                        $priceFormat = number_format ($ar["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']], 0, '.', ' ' );
                                        $Price_ID = $ar["CATALOG_PRICE_ID_".$arParams['MAIN_BESTSELLER_PRICE_TYPE']];
                                        $typeItem = "simple";
                                        $itemID = $ar["ID"];
                                    }
                            }    
                            // end get Price /////////////////////
                            $arResult["BESTSELLER"]["ITEMS"][] = array(
                                    "ID"                => $itemID,
                                    "NAME"              => $ar["NAME"],
                                    "ARTICLE"           => $ar["PROPERTY_".$arParams['MAIN_ARTICLE_FIELD']."_VALUE"],
                                    "DETAIL_PAGE_URL"   => $ar["DETAIL_PAGE_URL"],
                                    "PICTURE"           => "/upload/".$arFile["SUBDIR"]."/".$arFile["FILE_NAME"],
                                    "PRICE"             => $price,
                                    "PRICE_FORMAT"      => $priceFormat,
                                    "PRICE_ID"          => $Price_ID,
                                    "NEWPRODUCT"        => $ar["PROPERTY_NEWPRODUCT_VALUE"],
                                    "BESTSELLER"        => $ar["PROPERTY_BESTSELLER_VALUE"],
                                    "DISCOUNT"          => $ar["PROPERTY_DISCOUNT_VALUE"],
                                    "DISCOUNT_PERCENT"  => $maxDiscount
                                    );
                            unset($ar);
                        }

                }
                /////////////////////////////////////////////////////////////////////////////////////////////////////////
                // SALE
                if($arParams["SHOW_SALE"]){

                        $arResult["SALE"]["TITLE"] = $arParams['MAIN_SALE_TITLE'];
                        $arSelect = Array("ID", "NAME", "CODE", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE",  "DETAIL_PAGE_URL", "CATALOG_GROUP_".$arParams['MAIN_SALE_PRICE_TYPE'], "PROPERTY_".$arParams['MAIN_ARTICLE_FIELD'], "PROPERTY_NEWPRODUCT", "PROPERTY_DISCOUNT", "PROPERTY_BESTSELLER");
                        $arFilter = Array(
                            "IBLOCK_ID"=>(int)$arParams["MAIN_SALE_IBLOCK_ID"], 
                            "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", 
                            "PROPERTY_DISCOUNT_VALUE"=>$arParams["MAIN_SALE_FIELD_VALUE"]
                        );
                        $res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, Array("nPageSize"=>50),$arSelect);

                        while($ar = $res->GetNext()) {
							
                            $arDiscounts = CCatalogDiscount::GetDiscountByProduct($ar["ID"], $USER->GetUserGroupArray(), "N");
                            // найдем максимальную скидку
                            $tmpDiscount = array();
                            $maxDiscount = "";
                            foreach ($arDiscounts as $arDiscount) {
                                    $tmpDiscount[] = intval($arDiscount["VALUE"]);
                            }
                            if(count($tmpDiscount)>0){
                                    $maxDiscount = max($tmpDiscount);
                            }

                            // get PRICE /////////////////////
                            $Price = "";
                            
                            if($arParams["MAIN_SALE_PRICE_TYPE"]>0){
                                $Price = number_format ($ar["CATALOG_PRICE_".$arParams['MAIN_SALE_PRICE_TYPE']], 0, '.', ' ' );
                                    $typeItem = ""; // тип товара простой или с предложнеиями
                                    
                                    if(CCatalogSKU::IsExistOffers($ar["ID"],(int)$arParams["MAIN_SALE_IBLOCK_ID"])){                                    
                                        $mxResult = CCatalogSKU::GetInfoByProductIBlock((int)$arParams["MAIN_SALE_IBLOCK_ID"]); 
                                        if (is_array($mxResult)) 
                                        { 
                                            $rsOffers = CIBlockElement::GetList(array("CATALOG_PRICE_".$arParams['MAIN_SALE_PRICE_TYPE']=>"ASC"),array('IBLOCK_ID' => $mxResult["IBLOCK_ID"], 'PROPERTY_'.$mxResult['SKU_PROPERTY_ID'] => $ar["ID"]),false,false,array("ID","IBLOCK_ID","PRICE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "CATALOG_GROUP_".$arParams['MAIN_SALE_PRICE_TYPE'])); 
                                            while ($arOffer = $rsOffers->GetNext()) 
                                            {
                                                if(!empty($arOffer["PREVIEW_PICTURE"])){
                                                    $rsFile = CFile::GetByID($arOffer["PREVIEW_PICTURE"]);
                                                    $arFile = $rsFile->Fetch();
                                                }elseif(!empty($arOffer["DETAIL_PICTURE"])){
                                                    $rsFile = CFile::GetByID($arOffer["DETAIL_PICTURE"]);
                                                    $arFile = $rsFile->Fetch();
                                                }elseif(!empty($ar["PREVIEW_PICTURE"])){
                                                    $rsFile = CFile::GetByID($ar["PREVIEW_PICTURE"]);
                                                    $arFile = $rsFile->Fetch(); 
                                                }elseif(!empty($ar["DETAIL_PICTURE"])){
                                                    $rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
                                                    $arFile = $rsFile->Fetch(); 
                                                }
                                                
                                                $price = $arOffer["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                                $priceFormat = number_format($arOffer["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']], 0, '.', ' ' );
                                                $Price_ID = $arOffer["CATALOG_PRICE_ID_".$arParams['MAIN_SALE_PRICE_TYPE']];
                                                $typeItem = "offers";
                                                $itemID = $arOffer["ID"];                                                
                                                break; 
                                            } 
                                        } 
                                    }else{
                                        // картинка для простого товара
                                        if(!empty($ar["PREVIEW_PICTURE"])){
                                            $rsFile = CFile::GetByID($ar["PREVIEW_PICTURE"]);
                                            $arFile = $rsFile->Fetch();
                                        }else{
                                            $rsFile = CFile::GetByID($ar["DETAIL_PICTURE"]);
                                            $arFile = $rsFile->Fetch();
                                        }                                        
                                         // товар простой и берем идентификатор ценового предложения из товара
                                        $price = $ar["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']];
                                        $priceFormat = number_format($ar["CATALOG_PRICE_".$arParams['MAIN_NEW_PRICE_TYPE']], 0, '.', ' ' );
                                        $Price_ID = $ar["CATALOG_PRICE_ID_".$arParams['MAIN_SALE_PRICE_TYPE']];
                                        $typeItem = "simple";
                                        $itemID = $ar["ID"];
                                    }
                            }    
                            // end get Price /////////////////////
                            $arResult["SALE"]["ITEMS"][] = array(
                                    "ID"                => $itemID,
                                    "NAME"              => $ar["NAME"],
                                    "ARTICLE"           => $ar["PROPERTY_".$arParams['MAIN_ARTICLE_FIELD']."_VALUE"],
                                    "DETAIL_PAGE_URL"   => $ar["DETAIL_PAGE_URL"],
                                    "PICTURE"           => "/upload/".$arFile["SUBDIR"]."/".$arFile["FILE_NAME"],
                                    "PRICE"             => $price,
                                    "PRICE_FORMAT"      => $priceFormat,
                                    "PRICE_ID"          => $Price_ID,
                                    "NEWPRODUCT"	=> $ar["PROPERTY_NEWPRODUCT_VALUE"],
                                    "BESTSELLER"	=> $ar["PROPERTY_BESTSELLER_VALUE"],
                                    "DISCOUNT"		=> $ar["PROPERTY_DISCOUNT_VALUE"],
                                    "DISCOUNT_PERCENT"	=> $maxDiscount								
                                    );
                            unset($ar);
                        }

                }

    $obCache->EndDataCache(array($cacheID => $arResult));
} 

$this->IncludeComponentTemplate();




