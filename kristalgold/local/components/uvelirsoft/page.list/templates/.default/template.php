<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>

<?
$arParams["COUNT"] = 5;

?>

<div class="container_contakts">	
    <?
    for ($index = 1; $index <= 5 ? $arParams["COUNT"]:5); $index++) {
    ?>
    
    
    <div class="row row_about">
        <div class="col-md-1 col-sm-1 col-xs-2 ">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                        "AREA_FILE_SHOW" => "page",
                        "AREA_FILE_SUFFIX" => "img_".$index,
                        "COMPONENT_TEMPLATE" => ".default",
                        "EDIT_TEMPLATE" => ""
                )
                );?>
        </div>
        <div class="col-md-11 col-sm-11 col-xs-10 row_about-text">
            <div class="title_news">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "title_".$index,
                            "COMPONENT_TEMPLATE" => ".default",
                            "EDIT_TEMPLATE" => ""
                    )
                    );?>
            </div>
            <div class="text_about">
                <p>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                        "AREA_FILE_SHOW" => "page",
                        "AREA_FILE_SUFFIX" => "content_".$index,
                        "COMPONENT_TEMPLATE" => ".default",
                        "EDIT_TEMPLATE" => ""
                )
                );?>                       
                </p>
            </div>
        </div>
    </div>

    <?
    }
    ?>
 
				
    <div class="row row_about-work">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h1>
                <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                        "AREA_FILE_SHOW" => "page",
                        "AREA_FILE_SUFFIX" => "footer_header",
                        "COMPONENT_TEMPLATE" => ".default",
                        "EDIT_TEMPLATE" => ""
                )
                );?> 
                </h1>
            </div>
    </div>

    <div class="row ">
            <div class="col-md-12 col-sm-12 col-xs-12 about-block">
                    <div class="about-block_work">
                        <span>
                        <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                        "AREA_FILE_SHOW" => "page",
                                        "AREA_FILE_SUFFIX" => "footer_counter_1",
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "EDIT_TEMPLATE" => ""
                                )
                        );?> 
                        </span>
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        Array(
                                                "AREA_FILE_SHOW" => "page",
                                                "AREA_FILE_SUFFIX" => "footer_content_1",
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "EDIT_TEMPLATE" => ""
                                        )
                                );?>                                 
                            </p>
                    </div>						
                    <div class="about-block_work">
                        <span>
                        <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                        "AREA_FILE_SHOW" => "page",
                                        "AREA_FILE_SUFFIX" => "footer_counter_2",
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "EDIT_TEMPLATE" => ""
                                )
                        );?> 
                        </span>
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        Array(
                                                "AREA_FILE_SHOW" => "page",
                                                "AREA_FILE_SUFFIX" => "footer_content_2",
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "EDIT_TEMPLATE" => ""
                                        )
                                );?> 
                            </p>
                    </div>
                    <div class="about-block_work">
                        <span>
                        <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                        "AREA_FILE_SHOW" => "page",
                                        "AREA_FILE_SUFFIX" => "footer_counter_3",
                                        "COMPONENT_TEMPLATE" => ".default",
                                        "EDIT_TEMPLATE" => ""
                                )
                        );?>                             
                        </span>
                            <p>
                                <?$APPLICATION->IncludeComponent(
                                        "bitrix:main.include",
                                        "",
                                        Array(
                                                "AREA_FILE_SHOW" => "page",
                                                "AREA_FILE_SUFFIX" => "footer_content_3",
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "EDIT_TEMPLATE" => ""
                                        )
                                );?> 
                            </p>
                    </div>
            </div>
    </div>
				
</div>