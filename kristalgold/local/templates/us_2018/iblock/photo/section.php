<!-- <script type="text/javascript" src="/bitrix/templates/kristall/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script> -->

<script type="text/javascript">
$(function() {
	$('a.popup_f').fancybox({
		'overlayShow': true,
		'padding': 0,
		'margin' : 0,
		'scrolling' : 'no',
		'titleShow': false
	});
});

</script>
<!--  <link rel="stylesheet" type="text/css" href="http://www.kristallgold.ru/bitrix/templates/kristall/js/fancybox/jquery.fancybox-1.3.4.css" media="screen"></link> -->

 <script type="text/javascript">
function PhotoShow(ID, width, height, alt)
{
	var scroll = "no";
	var top=0, left=0;
	if(width > screen.width-10 || height > screen.height-28) scroll = "yes";
	if(height < screen.height-28) top = Math.floor((screen.height - height)/2-14);
	if(width < screen.width-10) left = Math.floor((screen.width - width)/2-5);
	width = Math.min(width, screen.width-10);
	height = Math.min(height, screen.height-28);
	var wnd = window.open("","","scrollbars="+scroll+",resizable=yes,width="+width+",height="+height+",left="+left+",top="+top);
	wnd.document.write(
		"<html><head>"+
		"<"+"script type=\"text/javascript\">"+
		"function KeyPress()"+
		"{"+
		"	if(window.event.keyCode == 27) "+
		"		window.close();"+
		"}"+
		"</"+"script>"+
		"<title>"+(alt == ""? "Картинка":alt)+"</title></head>"+
		"<body topmargin=\"0\" bgcolor=\"#000255\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onKeyPress=\"KeyPress()\">"+
		"<img src=\""+ID+"\" border=\"0\" alt=\""+alt+"\" /><br><center style=\"font-size:13px; color:white;\">"+(alt == ""? "Картинка":alt)
		+
		"</center></body></html>"
	);
	wnd.document.close();
}
</script>

<?
$MESSAGE="Раздел находится в стадии наполнения";
$MESSAGE_ENG="";
//$MESSAGE=$MESSAGE_ENG;
/**************************************************************************
Component "Photos of the group".

This component is intended for displaying photos for one of the group in table order. Mainly used on the page that displays conent of some photogallery groups.

Sample of usage:

$APPLICATION->IncludeFile("iblock/photo/section.php", Array(
	"IBLOCK_TYPE"			=> "photo",
	"IBLOCK_ID"				=> "8",
	"SECTION_ID"			=> $_REQUEST["SECTION_ID"],
	"PAGE_ELEMENT_COUNT"	=> "50",
	"LINE_ELEMENT_COUNT"	=> "3",
	"ELEMENT_SORT_FIELD"	=> "sort",
	"ELEMENT_SORT_ORDER"	=> "asc",
	"FILTER_NAME"			=> "arrFilter",
	"CACHE_FILTER"			=> "N",
	"CACHE_TIME"			=> "3600",
	));

Parameters:

IBLOCK_TYPE - Information Block type
IBLOCK_ID - Inf. block ID
SECTION_ID - Group ID
PAGE_ELEMENT_COUNT - Number of photos on the page
LINE_ELEMENT_COUNT - Number of photos displayed in each table row
ELEMENT_SORT_FIELD - by which field the elements will be sorted, the following values can be used:

	shows - average number of photo views (popularity)
	sort - by sorting index
	timestamp_x - by modification date
	name - by title
	id - by element ID
	active_from - by activity date FROM
	active_to - by activity date TILL

ELEMENT_SORT_ORDER - element sorting order, following values can be used:

	asc - in ascending order
	desc - in descending order

arrPROPERTY_CODE - array of mnemonic codes for the information block properties
PRICE_CODE- mnemonic code of the price type
BASKET_URL - URL to the page with the customer's basket
FILTER_NAME - name of an array with values for filtering of the photos
CACHE_FILTER - [Y|N] cache or not cache the values selected from the database if filter was set with the use of them?
CACHE_TIME - (sec.) time for caching of the values selected from database

***************************************************************************/

global $USER, $APPLICATION;
if (CModule::IncludeModule("iblock")):

	IncludeTemplateLangFile(__FILE__);

// Обработка поворота карткартинки..
function rotateImage($src, $dst, $count = 1, $quality = 95)
{
   if (!file_exists($src)) {

	   return false;
   }

   //$dst = substr($src, 0, strrpos($src, ".")) . "_ROT" . $count  . substr($src, strrpos($src, "."), strlen($src));

switch ($count) {
case 0:
   $degrees = 0;
   break;
case 1:
   $degrees = 90;
   break;
case 2:
   $degrees = 180;
   break;
case 3:
   $degrees = 270;
   break;
}

// Load
$source = imagecreatefromjpeg($src);
// Rotate
$rotate = imagerotate($source, 360 - $degrees, 0);
// Output
imagejpeg($rotate, $dst, $quality);

   imageDestroy($rotate);
   imageDestroy($source);

   return true;
}

if ($_GET["Rotate_imgs"]>1) {
$new_img_rot_s = tempnam($_SERVER["DOCUMENT_ROOT"]."/tmp/", "Rot").".jpg";
$new_img_rot_b = tempnam($_SERVER["DOCUMENT_ROOT"]."/tmp/", "Rot").".jpg";
$simg_id=$_GET["Rotate_imgs"];
$srcimg=$_SERVER["DOCUMENT_ROOT"].CFile::GetPath($simg_id);

$bimg_id=$_GET["Rotate_imgb"];
$brcimg=$_SERVER["DOCUMENT_ROOT"].CFile::GetPath($bimg_id);

rotateImage($srcimg, $new_img_rot_s, 1, 95);

rotateImage($brcimg, $new_img_rot_b, 1, 95);

$el = new CIBlockElement;


$arLoadProductArray = Array(
  "MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
"DETAIL_PICTURE"	=>	CFile::MakeFileArray($new_img_rot_b),
	"PREVIEW_PICTURE"	=>	CFile::MakeFileArray($new_img_rot_s)
  );




if($res = $el->Update($_GET["id"], $arLoadProductArray))
  echo "New ID: ".$PRODUCT_ID;
else
  echo "Error: ".$el->LAST_ERROR;




@unlink($new_img_rot_s);
@unlink($new_img_rot_b);
}

// --------------------------------------


	/*************************************************************************
						Processing of received parameters
	*************************************************************************/

	$LINE_ELEMENT_COUNT = intval($LINE_ELEMENT_COUNT);
	$bDisplayPanel = ($DISPLAY_PANEL == "Y") ? True : False;

	$SECTION_ID = (intval($SECTION_ID)>0 ? intval($SECTION_ID) : false);
	global $$FILTER_NAME;
	$arrFilter = ${$FILTER_NAME};
	$CACHE_FILTER = ($CACHE_FILTER=="Y") ? "Y" : "N";
	if ($CACHE_FILTER=="N" && count($arrFilter)>0) $CACHE_TIME = 0;

	/*************************************************************************
								Work with cache
	*************************************************************************/

	$CACHE_ID = __FILE__.md5(serialize($arParams).serialize($arrFilter).$USER->GetGroups().CDBResult::NavStringForCache($PAGE_ELEMENT_COUNT));
	$obCache = new CPHPCache;
	if($obCache->InitCache($CACHE_TIME, $CACHE_ID, "/"))
	{
		$arVars = $obCache->GetVars();

		$SECTION_ID		= $arVars["SECTION_ID"];
		$SECTION_NAME		= $arVars["SECTION_NAME"];
		$IBLOCK_ID		= $arVars["IBLOCK_ID"];
		$IBLOCK_TYPE	= $arVars["IBLOCK_TYPE"];
		$ELEMENT_NAME	= $arVars["ELEMENT_NAME"];
	}
	else
	{
		if ($SECTION_ID>0) {

		$arSection = GetIBlockSection($SECTION_ID);
		$SECTION_ID = $arSection["ID"];
		$SECTION_CODE = $arSection["CODE"];
		//$IBLOCK_ID = $arSection["IBLOCK_ID"];

		$arIBlock = GetIBlock($IBLOCK_ID);
		$IBLOCK_TYPE = $arIBlock["IBLOCK_TYPE_ID"];

		$arrPath = array();
		$rsPath = GetIBlockSectionPath($IBLOCK_ID, $SECTION_ID);
		while($arPath=$rsPath->GetNext()) $arrPath[] = array("ID" => $arPath["ID"], "NAME" => $arPath["NAME"]);

		$arIblockType = CIBlockType::GetByIDLang($IBLOCK_TYPE, LANGUAGE_ID);
		$ELEMENT_NAME = $arIblockType["ELEMENT_NAME"];
		}
		else
		{ //echo "rest";
	//	print_r($arVars);
		$SECTION_ID		= $arVars["SECTION_ID"];
		//$IBLOCK_ID		= $arVars["IBLOCK_ID"];
		$ELEMENT_NAME	= $arVars["ELEMENT_NAME"];
		$IBLOCK_TYPE	= $arVars["IBLOCK_TYPE"];
		$arrPath		= $arVars["arrPath"];
		}

	}
	if (intval($SECTION_ID)>0) :
		if ($bDisplayPanel)
			CIBlock::ShowPanel($IBLOCK_ID, 0, $SECTION_ID, $IBLOCK_TYPE);
		if (is_array($arrPath))
		{
			while(list($key, $arS) = each($arrPath))
			{
				if ($SECTION_ID==$arS["ID"]) $SECTION_NAME = $arS["NAME"];
				$APPLICATION->AddChainItem($arS["NAME"], $APPLICATION->GetCurPage()."?SECTION_ID=".$arS["ID"]);
			}
		}
		//$APPLICATION->SetTitle($SECTION_NAME);


		if($obCache->StartDataCache()):

			/************************************
						Elements
			************************************/

			// list of the element fields that will be used in selection
			$arSelect = array(
				"ID",
				"IBLOCK_ID",
				"IBLOCK_SECTION_ID",
				"NAME",
				"PREVIEW_PICTURE",
				"DETAIL_PICTURE",
				"DETAIL_PAGE_URL"
				);

			// adding the filter with some values
			$arrFilter["ACTIVE"] = "Y";
			$arrFilter["SECTION_ID"] = $SECTION_ID;

			if ($rsElements = GetIBlockElementListEx($IBLOCK_TYPE, $IBLOCK_ID, false, array($ELEMENT_SORT_FIELD => $ELEMENT_SORT_ORDER, "ID" => "ASC"), false, $arrFilter, $arSelect)):
				$rsElements->NavStart($PAGE_ELEMENT_COUNT);
				$count = intval($rsElements->SelectedRowsCount());

				/****************************************************************
										HTML form
				****************************************************************/
CMain::SetTitle("Фото галерея: ".$SECTION_NAME);

$arFilter = Array(
		"IBLOCK_ID"=>$IBLOCK_ID,
		"SECTION_ID"=>$SECTION_ID
		);

	$section_in_count= CIBlockSection::GetCount($arFilter);



if (($count==0)&&($section_in_count==0)) echo $MESSAGE;
		?>

		<div align="left"  >

		<table class="gallery-photo" border=0  cellpadding="20" cellspacing="10"  >
			<tr valign="middle">
				<?
				$n=1;
				$cell = 0;
				while ($obElement = $rsElements->GetNextElement()):
					$cell++;
					$arElement = $obElement->GetFields();
					$image1 = intval($arElement["PREVIEW_PICTURE"])<=0 ? $arElement["DETAIL_PICTURE"] : $arElement["PREVIEW_PICTURE"];
					$image2 = intval($arElement["DETAIL_PICTURE"])<=0 ? $arElement["PREVIEW_PICTURE"] : $arElement["DETAIL_PICTURE"];

					$image1_setings=$_SERVER["DOCUMENT_ROOT"].CFile::GetPath($image1);
					$img_style ='';
					list($width_orig, $height_orig) = getimagesize($image1_setings);
					if ($width_orig==145)  $img_style = "height:108; width:145; border: 1px solid #465082;";
					if ($height_orig==145)  $img_style = "width:108; height:145; border: 1px solid #465082;";
	$image2_setings=$_SERVER["DOCUMENT_ROOT"].CFile::GetPath($image2);
	list($width_orig2, $height_orig2) = getimagesize($image2_setings);


				?>
				<td align="center"  >
					<table cellpadding="0" cellspacing="0" border="0" >
						<tr>
						  <td width="145" align="center" valign="top">
						 <?$page = $APPLICATION->GetCurPageParam('Rotate_imgs='.$image1.'&Rotate_imgb='.$image2.'&id='.$arElement["ID"], array("ROT", "Rotate_imgs", "Rotate_imgb" , "id"));   ?>
	<a href="<? echo CFile::GetPath($image2)?>" data-fancybox="fancy" class="popup_f"><img  alt="<?=$arElement["NAME"]?>" src="<? echo CFile::GetPath($image1)?>"></a>

						 <? global $USER;
if ($USER->IsAdmin()) echo '<br><a href="'.$page .'">></a> | <a href="/bitrix/admin/iblock_element_edit.php?WF=Y&ID='.$arElement["ID"].'&type=gallery&lang=ru&IBLOCK_ID=8&find_section_section=126">E</a>';
?>


						  </td>
						</tr>

			  </table></td>
					<?
					if($n%$LINE_ELEMENT_COUNT == 0):
						$cell = 0;
					?>
		  </tr>

			<?
					endif; // if($n%$LINE_ELEMENT_COUNT == 0):
					$n++;
				endwhile; // while ($obElement = $rsElements->GetNextElement()):


				?>

</table>
</div>
		<?if ($count>0):?><div style="padding-left:10px;"><?echo $rsElements->NavPrint($ELEMENT_NAME)?></div><?endif;?>
		<?
			endif; // if ($rsElements = GetIBlockElementListEx
			$obCache->EndDataCache(array(
				"SECTION_ID"	=> $SECTION_ID,
				"SECTION_NAME"	=> $SECTION_NAME,
				"IBLOCK_ID"		=> $IBLOCK_ID,
				"IBLOCK_TYPE"	=> $IBLOCK_TYPE,
				"ELEMENT_NAME"	=> $ELEMENT_NAME
				));
		endif; // if($obCache->StartDataCache()):

	else:
			ShowError(GetMessage("PHOTO_SECTION_NOT_FOUND"));
	endif;
endif;
?>