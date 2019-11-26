<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
<?$currentItemsCount = COption::GetOptionString("uvelirsoft", "MAIN_MENU_LEFT_COUNT", "6");?>
<?$menuPosition = (in_array($arParams["MENU_POSITION"], array('LEFT', 'RIGHT')) ? strtolower($arParams["MENU_POSITION"]) : "");?>

<div id="topmenucustom<?=(!empty($menuPosition) ? "_".$menuPosition : "")?>">
    <div class="menu<?//=(!empty($menuPosition) ? " ".$menuPosition : "")?>">
        <div class="cont">
            <ul class="main_menu">
                <?if(
                    $arParams["MENU_POSITION"] != "LEFT"
                    || ($arParams["MENU_POSITION"] == "LEFT" && $currentItemsCount > 0)
                ){
                    if(
                        !in_array($arParams["MENU_POSITION"], array('LEFT', 'RIGHT'))
                        || ($arParams["MENU_POSITION"] == "LEFT" && $index < $currentItemsCount)
                        || ($arParams["MENU_POSITION"] == "RIGHT")
                    ){?>
                        <?
                        if($arParams["MENU_POSITION"] == "RIGHT"):?>
                                <li class="desctop_hide show_cat"><a >КАТАЛОГ</a>
                                <ul style="display: none" class="mob-cat desctop_hide">
                                    <?php

                                    $arSections = getSectionList(
                                        Array(
                                            'IBLOCK_ID' => 1
                                        ),
                                        Array(
                                            'NAME',
                                            'SECTION_PAGE_URL'
                                        )
                                    )['CHILDS'][2]['CHILDS'];

                                    foreach ($arSections as $sec)
                                    {
                                    ?>
                                        <li class="desctop_hide"><a href="<?=$sec['SECTION_PAGE_URL']?>" ><?=$sec['NAME']?></a></li>
                                   <?php }?>
                                </ul>
                                </li>
                                <li class="desctop_hide"><a href="/new/" >Новости</a></li>
                                <li class="desctop_hide"><a href="/o-kompanii/">О компании</a></li>
                                <li class="desctop_hide"><a href="/o-kompanii/comments/">Отзывы</a></li>
                                <li class="desctop_hide"><a href="/magazin/delivery/">Доставка</a></li>
                                <li><a href="/magazini/" >Магазины</a></li>
                                <li><a href="/blog/">Блог</a></li>
                                <li><a href="/na-zakaz/">На заказ</a></li>
                            <?else:?>
                                <li class=""><a href="/magazin/">КАТАЛОГ</a></li>
                                <li><a href="/new/" >Новости</a></li>
                                <li><a href="/o-kompanii/">О компании</a></li>
                                <li><a href="/o-kompanii/comments/">Отзывы</a></li>
                                <li><a href="/magazin/delivery/">Доставка</a></li>
                        <?endif
                        ?>
                    <?}?>
                    <?$index = 0;?>
                    <?
                    foreach ($arResult as $section) {
                        $mobile = '';
                        if ($section['USE_MENU_MORE'] == '0') {
                            if(
                                ($arParams["MENU_POSITION"] == "LEFT" && $index >= $currentItemsCount)
                                || ($arParams["MENU_POSITION"] == "RIGHT" && $index < $currentItemsCount)
                            ) {
                                $index++;
                                if ($arParams["MENU_POSITION"] == "RIGHT") {
                                    $mobile = 'desctop_hide';
                                }
                                else
                                    continue;
                            }else{
                                $index++;
                            }
                            ?>
                            <li class="sections <? echo $mobile;?>">
                                <?
                                $isCatalog = false;
                                if($section['SECTION_PAGE_URL'] == '/magazin/catalog/'){
                                    $isCatalog = true;
                                    $section['SECTION_PAGE_URL'] = '/magazin/';
                                }?>
                                <a href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?></a>
                                <div class="sub_menu">
                                    <div class="sub_menu_chains">
                                        <div class="col left">
                                            <div class="title">ВИД ИЗДЕЛИЯ</div>
                                            <ul>
                                                <?
                                                foreach ($section['SUB_SECTIONS'] as $key => $value) {
                                                    /*?><li><a href="<?= $value ?>"><?= $key ?></a></li><?*/
                                                    if(
                                                        ($isCatalog && stripos($value['URL'], '/vse_tovary/') !== false)
                                                        || (stripos($value['URL'], '/pod-zakaz/') !== false)
                                                    ){
                                                        continue;
                                                    }
                                                    ?><li><a href="<?=$value['URL']?>" data-img="<?=$value['PICTURE']?>"><?=$value['NAME']?></a></li><?
                                                }
                                                ?>
                                                <?if(!$isCatalog){?>
                                                    <li><a href="<?= $section['SECTION_PAGE_URL'] ?>"><b>Все <span class="small_text"><?= $section['NAME'] ?></span></b></a></li>
                                                <?}?>
                                            </ul>
                                        </div>

                                        <?
                                        foreach ($section['LISTING'] as $key => $list_section) {
                                            ?>
                                            <div class="col left">
                                                <div class="title big_text"><?= $key ?></div>
                                                <ul>
                                                    <?
                                                    foreach ($list_section as $value) {
                                                        ?><li><a href="<?= $value['LINK']; ?>"><?= $value['NAME']; ?></a></li><?
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        <? } ?>
                                    </div>
                                    <div class="sub_menu_pictures">
                                        <?if(!empty($section['PICTURE'])){?>
                                            <img src="<?=$section['PICTURE']?>" alt="<?=$section['NAME']?>">
                                        <?}?>
                                    </div>
                                </div>
                            </li>
                            <?
                        }
                    }
                    ?>
                    <?if(
                        ($arParams["MENU_POSITION"] == "LEFT" && $index < $currentItemsCount)
                        || ($arParams["MENU_POSITION"] == "RIGHT" && $index >= $currentItemsCount)
                    ){
                        $index++;
                        ?>
                        <?/*<li>
                            <a href="/">ЕЩЕ</a>
                            <div class="sub_menu">
                                <div class="sub_menu_chains">
                                    <div class="col left">
                                        <ul>
                                            <?
                                            foreach ($arResult as $section) {
                                                if ($section['USE_MENU_MORE'] == '1') {
                                                    ?><li><a class="title" href="<?= $section['SECTION_PAGE_URL'] ?>"  data-img="<?=$section['PICTURE']?>"><?= $section['NAME'] ?></a></li><?
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="sub_menu_pictures">

                                </div>
                            </div>
                        </li>
                        */?>
                        <?
                    }else{
                        $index++;
                    }

                    for ($i = 1; $i <= intval($arParams['ITEM_COUNT']); $i++){
                        if(
                            ($arParams["MENU_POSITION"] == "LEFT" && $index >= $currentItemsCount)
                            || ($arParams["MENU_POSITION"] == "RIGHT" && $index < $currentItemsCount)
                        ) {
                            $index++;
                            continue;
                        }else{
                            $index++;
                        }
                        ?>
                        <li><a href="<?= $arParams["LINK_$i"] ?>" class="<?= $arParams["CLASS_$i"] ?>"><?= $arParams["NAME_$i"] ?></a></li>
                        <?
                    }
                    ?>
                <?}?>

                <?if($arParams["MENU_POSITION"] == "RIGHT"){?>
                    <!-- <li><a href="/na-zakaz/">На заказ</a></li> -->
                <?}?>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</div>
