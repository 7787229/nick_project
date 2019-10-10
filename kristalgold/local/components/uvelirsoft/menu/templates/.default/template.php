<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>
<div id="topmenucustom">
    <div class="menu">
        <div class="cont">
            <ul class="main_menu">
                <li><a href="/magazin/">КАТАЛОГ</a></li>
                <?
                foreach ($arResult as $section) {
                    if ($section['USE_MENU_MORE'] == '0') {
                        ?>
                        <li class="sections">
                            <a href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?></a>
                            <div class="sub_menu">
                                <div class="col left">
                                    <div class="title">ВИД ИЗДЕЛИЯ</div>
                                    <ul>
                                        <?
                                        foreach ($section['SUB_SECTIONS'] as $key => $value) {
                                            ?><li><a href="<?= $value ?>"><?= $key ?></a></li><?
                                        }
                                        ?>
                                        <li><a href="<?= $section['SECTION_PAGE_URL'] ?>"><b>Все <span class="small_text"><?= $section['NAME'] ?></span></b></a></li>
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
                        </li>
                        <?
                    }
                }
                ?>
                <li>
                    <a href="/">ЕЩЕ</a>
                    <div class="sub_menu">
                        <div class="col left">
                            <ul>
                                <?
                                foreach ($arResult as $section) {
                                    if ($section['USE_MENU_MORE'] == '1') {
                                        ?><li><a class="title" href="<?= $section['SECTION_PAGE_URL'] ?>"><?= $section['NAME'] ?></a></li><?
                                    }
                                }
                                ?>

                            </ul>
                        </div>								
                    </div>
                </li>
                <?
                for ($i = 1; $i <= intval($arParams['ITEM_COUNT']); $i++){
                    ?> <li><a href="<?= $arParams["LINK_$i"] ?>" class="<?= $arParams["CLASS_$i"] ?>"><?= $arParams["NAME_$i"] ?></a></li> <?
                }
                ?>
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</div>