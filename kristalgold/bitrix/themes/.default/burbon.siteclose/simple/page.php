<?php
/**
* $Revision:  Id$
* theme: simple
*/
$MODULE_ID = 'burbon.siteclose'; 
$header = COption::GetOptionString($MODULE_ID, "BU_SC_header_".SITE_ID);
$color = COption::GetOptionString($MODULE_ID, "selectbox_theme_".SITE_ID);
$chk_contacts = COption::GetOptionString($MODULE_ID,"BU_SC_chk_contacts_".SITE_ID);
$chk_logo = COption::GetOptionString($MODULE_ID,"BU_SC_chk_logo_".SITE_ID);
$chk_counter = COption::GetOptionString($MODULE_ID,"BU_SC_chk_counter_".SITE_ID);
$theme = COption::GetOptionString($MODULE_ID, "selectbox_type_".SITE_ID);
$logo = COption::GetOptionString($MODULE_ID, "BU_SC_logo_".SITE_ID);
$text = COption::GetOptionString($MODULE_ID, "BU_SC_text_".SITE_ID);
//$contacts = COption::GetOptionString($MODULE_ID, "BU_SC_contacts_".SITE_ID);
$phone = COption::GetOptionString($MODULE_ID, "BU_SC_phone_".SITE_ID);
$email = COption::GetOptionString($MODULE_ID, "BU_SC_email_".SITE_ID);
$address = COption::GetOptionString($MODULE_ID, "BU_SC_address_".SITE_ID);
$finish_time = COption::GetOptionString($MODULE_ID, "BU_SC_date_".SITE_ID);
$adminka = COption::GetOptionString($MODULE_ID, "BU_SC_admin_".SITE_ID);

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$MODULE_ID."/page.php");

if($finish_time) {
	$arTime = explode(' ', $finish_time);
	$hour = $arTime[1];
	if($hour != '') {
		$arHour = explode(':', $hour);
		if(!isset($arHour[0])) {
			$arHour[0] = '00';
		}
		if(!isset($arHour[1])) {
			$arHour[1] = '00';
		}
		if(!isset($arHour[2])){
			$arHour[2] = '00';
		}
		$hour = implode(':', $arHour);
		$finish_time = $arTime[0].' '.$hour;
	}
	else {
		$finish_time = $finish_time.' '.'00:00:00';
	}
	
	$date_finish = DateTime::createFromFormat('d.m.Y H:i:s', $finish_time);

	$fY = $date_finish->format("Y");
	$fM = ($date_finish->format("m")-1);
	$fD = $date_finish->format("d");
	$fH = $date_finish->format("H");
	$fI = $date_finish->format("i");
	$fS = $date_finish->format("s");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
    <meta charset="<?echo LANG_CHARSET?>">
    <title><?=$header;?></title>
    <link href="/bitrix/themes/.default/<?=$MODULE_ID;?>/<?=$theme;?>/css/style.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="/bitrix/themes/.default/<?=$MODULE_ID;?>/<?=$theme;?>/css/fonts.css" />
	<link rel="stylesheet" type="text/css" href="/bitrix/themes/.default/<?=$MODULE_ID;?>/<?=$theme;?>/css/<?=$color?>.css" />
	<!--[if IE 8]><link rel="stylesheet" type="text/css" href="/bitrix/themes/.default/<?=$MODULE_ID;?>/<?=$theme;?>/css/ie8.css" /><![endif]-->
    <!--[if gte IE 9]>
	  <style type="text/css">
		.counter .block {
		   filter: none;
		}
	  </style>
	<![endif]-->
	<script type="text/javascript" src="/bitrix/themes/.default/<?=$MODULE_ID;?>/<?=$theme;?>/js/jquery-1.10.0.min.js"></script>
    <script type="text/javascript">
		function zero_lead(num)
		{
			if (num <10)
				return '0'+num;
			else 
				return num;
		}
		$(document).ready(function(){
			jQuery.fn.anim_progressbar = function (aOptions) {
				var iCms = 1000;
				var iMms = 60 * iCms;
				var iHms = 3600 * iCms;
				var iDms = 24 * 3600 * iCms;

				var aOpts = jQuery.extend(aOptions);
				var vPb = this;

				return this.each(
					function() {
						var vInterval = setInterval(
							function(){
								var iLeftMs = aOpts.finish - new Date();
									iDays = parseInt(iLeftMs / iDms);
									iHours = parseInt((iLeftMs - (iDays * iDms)) / iHms);
									iMin = parseInt((iLeftMs - (iDays * iDms) - (iHours * iHms)) / iMms);
									iSec = parseInt((iLeftMs - (iDays * iDms) - (iMin * iMms) - (iHours * iHms)) / iCms);
									iPerc = new Date();
								if (iDays>999) iDays=999;
								//$(vPb).children('.counter').html('<div class="block">'+zero_lead(iDays)+'</div><div class="digit-after"></div><div class="notes" id="days" ></div><div class="digit" >'+zero_lead(iHours)+'</div><div class="digit-after"></div><div class="notes" id="hours"></div><div class="digit">'+zero_lead(iMin)+'</div><div class="digit-after"></div><div class="notes" id="minutes"></div> <div class="digit-last">'+zero_lead(iSec)+'</div> <div class="notes" id="seconds"></div>');
								$(vPb).children('.counter').html('<div class="block"><div class="text"><?=GetMessage("DAYS")?></div><div class="value">'+zero_lead(iDays)+'</div></div><div class="block"><div class="text"><?=GetMessage("HOURS")?></div><div class="value">'+zero_lead(iHours)+'</div></div><div class="block"><div class="text"><?=GetMessage("MINUTES")?></div><div class="value">'+zero_lead(iMin)+'</div></div><div class="block"><div class="text"><?=GetMessage("SECOUNDS")?></div><div class="value">'+zero_lead(iSec)+'</div></div>');

								if ((iPerc/1000) >= (aOpts.finish/1000)-1) {
									clearInterval(vInterval);
									$(vPb).children('.counter').html($('#timeend').val());
								}
								
							} ,aOpts.interval
						);
					}
				);
			}
			$('#progress-bar').anim_progressbar({'finish':new Date(<?=$fY;?>, <?=($fM);?>, <?=$fD;?>, <?=$fH;?>, <?=$fI;?>, <?=$fS;?>), 'interval':100});  
		});
    </script>
</head>
<body>
	<div id="wrap">
		<div class="content">
			<div class="logo">
				<?if(!empty($chk_logo) && !empty($logo)):?>
					<img src="<?=$logo;?>" />
				<?endif;?>
				<?if(!empty($adminka)):?>
					<div class="enter">
						<a href="/bitrix/admin/">
							<div class="img"></div>
							<span><?=GetMessage("ENTER")?></span>
						</a>
						<div class="hint"><?=GetMessage("ENTER_PANEL")?></div>
					</div>
				<?endif;?>
			</div>
			<div id="progress-bar">
				<?if($chk_counter && $finish_time):?>
					<div class="counter"></div>
					<input type="hidden" id="timeend" value="<p><?=GetMessage("TIME_END")?></p>" />
				<?endif;?>
			</div>
			<div class="description">
				<?=$text;?>
			</div>
			<?if(!empty($chk_contacts)):?>    
				<div class="info">
					<?if(!empty($phone)):?>
						<div class="phone"><?=$phone;?></div>
					<?endif;?>
					<?if(!empty($email)):?>
						<?=$email;?><br />
					<?endif;?>
					<?if(!empty($address)):?>
						<?=$address;?>
					<?endif;?>
				</div>
			<?endif;?>
		</div>
	</div>
</body>
</html>