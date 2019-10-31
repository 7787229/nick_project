<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);

        require_once($_SERVER['DOCUMENT_ROOT'].DEFAULT_TEMPLATE.'/include/footer.php');

        $APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/js/jquery.min.js");
        $APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/bootstrap/js/bootstrap.js");
        $APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/js/script.js");
        $APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/owl.carousel/owl.carousel.js");

        $APPLICATION->AddHeadScript(DEFAULT_TEMPLATE."/js/jquery.fancybox.js");
        $APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/css/jquery.fancybox.css");

        $APPLICATION->SetAdditionalCSS("/bitrix/css/main/bootstrap.css");

        //$APPLICATION->SetAdditionalCSS("/bitrix/css/main/font-awesome.css");
        $APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/css/font-awesome.css");
        $APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/css/social.css");


        // подключим модификаторы стиля
        $currentCssMod = COption::GetOptionString("uvelirsoft", "SITE_CSS_MOD","default");
        $APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/css_mod/".$currentCssMod.".css");

        $APPLICATION->SetAdditionalCSS(DEFAULT_TEMPLATE."/owl.carousel/css/owl.carousel.css");
        CJSCore::Init(array("ajax", "window"));
        ?>


        <!-- <div id="auth_and_register__container"></div> -->
        <?
        if(!$USER->IsAuthorized()){
            require_once($_SERVER['DOCUMENT_ROOT'].'/ajax/auth_and_register.php');
        }
        ?>
        <?
        global $USER;
        if ($USER->IsAdmin()){
        ?>
        <!-- Start of uvelirsoft Zendesk Widget script -->
        <script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="jav * ascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(e){n=document.domain,r.src='jav * ascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var e=this.createElement("script");n&&(this.domain=n),e.id="js-iframe-async",e.src="https://assets.zendesk.com/embeddable_framework/main.js",this.t=+new Date,this.zendeskHost="uvelirsoft.zendesk.com",this.zEQueue=a,this.body.appendChild(e)},o.write('<body onload="document._l();">'),o.close()}();
        /*]]>*/</script>
        <!-- End of uvelirsoft Zendesk Widget script -->
        <?}?>

        <!-- BEGIN JIVOSITE CODE {literal} -->
        <script type='text/javascript'>
        (function(){ var widget_id = '81965';
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);})();</script>
        <!-- {/literal} END JIVOSITE CODE -->
    </body>
</html>
