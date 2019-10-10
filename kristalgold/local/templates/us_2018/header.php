<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>" prefix="og: http://ogp.me/ns#">
    <head>
	<script data-skip-moving='true'>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-55124117-1', 'auto');
        ga('send', 'pageview');

    </script>

    <?
        $protocol = (CMain::IsHTTPS()) ? "https://" : "http://";
        $servername = $protocol . $_SERVER['SERVER_NAME'];
    ?>

    <meta property="og:title" content="<?$APPLICATION->ShowProperty("og:title","Ювелирный дом 'Кристалл Мечты'");?>" />
    
    <meta property="og:description" content="<?=$APPLICATION->ShowProperty("og:description","Заказать украшения в интернет магазине с бесплатной доставкой!
Эксклюзивные элитные изделия с драгоценными камнями!
 Контакты отдела продаж: +7 (495) 788-77-22");?>" />
    <meta property="og:image" content="<?$APPLICATION->ShowProperty("og:image", $servername . "/upload/iblock/374/long03.jpg");?>" />
    <?php
            $currPage = $APPLICATION->GetCurPage();
            if($currPage == '/'){
                $currPage = '';
            }
            $page_url = $servername . $_SERVER['REQUEST_URI'];
            ?>
    <meta property="og:url" content="<?= $page_url ?>"/>
    <meta name="twitter:card" content="<? echo $currPage == '' ? 'summary' : 'summary_large_image'; ?>" />
    <meta name="twitter:title" content="<?$APPLICATION->ShowProperty("twitter:title","Интернет-магазин ювелирных украшений 'Кристалл Мечты'");?>" />
    <meta name="twitter:description" content="<?$APPLICATION->ShowProperty("twitter:description","Специальные цены на уникальные украшения для клиентов ювелирного магазина!");?>" />
    <meta name="twitter:image" content="<?$APPLICATION->ShowProperty("twitter:image", $servername . "/upload/iblock/22d/q09a6194.jpg");?>" />
    <meta name="twitter:hashtags" content="<?$APPLICATION->ShowProperty("twitter:hashtags","");?>" />
    <meta property="fb:admins" content="787136036"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:type" content="product" />

<!-- Yandex.Metrika counter -->
    <script type="text/javascript" data-skip-moving='true'>
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter35615585 = new Ya.Metrika({
                        id:35615585,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/35615585" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <?
        $curPage = $APPLICATION->GetCurPage(true);
        $curDir = $APPLICATION->GetCurDir();
        if (preg_match('/^\/magazin/', $curPage)) {
            echo '<link rel="canonical" href="https://www.kristallgold.ru'. $curDir . '" />';
        }
        ?>
    	<title><?$APPLICATION->ShowTitle()?></title>
    	<?$APPLICATION->ShowHead();?>
<meta name="yandex-verification" content="31dcb8ef4dd395b2" />
    </head>
    <body>
        <div itemscope itemtype="http://schema.org/WebPage">
            <meta itemprop="name" content="<?$APPLICATION->ShowTitle()?>"/>
            <meta itemprop="description" content="<?$APPLICATION->ShowProperty("description")?>"/>
            <?php
            $currPage = $APPLICATION->GetCurPage();
            if($currPage == '/'){
                $currPage = '';
            }
            $page_url = $servername . $_SERVER['REQUEST_URI'];
            ?>
            <link itemprop="url" href="<?=$page_url?>"/>
        </div>
        <?$APPLICATION->ShowPanel();?>

        <?require_once($_SERVER['DOCUMENT_ROOT'].DEFAULT_TEMPLATE.'/include/header.php');?>
