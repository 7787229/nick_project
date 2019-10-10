<?
$SHOW_NAV_CHAIN = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "N");
?>
<?
#$APPLICATION->IncludeFile("iblock/photo/section_top.php", Array(
#	"IBLOCK_TYPE"			=>	"photo",						# Тип инфо-блока
#	"IBLOCK_ID"			=>	'54',							# Инфо-блок
#	"SECTION_ID"			=>	$_REQUEST["SECTION_ID"],		# ID раздела
#	"PAGE_ELEMENT_COUNT"	=>	"1",							# Количество элементов на странице
#	"LINE_ELEMENT_COUNT"	=>	"2",							# Количество фотографий выводимых в одной строке таблицы
#	"ELEMENT_SORT_FIELD"	=>	"sort",						# По какому полю сортируем фотографии
#	"ELEMENT_SORT_ORDER"	=>	"asc",						# Порядок сортировки фотографий в разделе
#	"FILTER_NAME"			=>	"arrFilter",					# Имя массива со значениями фильтра для фильтрации элементов
#	"CACHE_FILTER"		=>	"N",							# Кэшировать при установленом фильтре
#	"CACHE_TIME"			=>	"0",							# Время кэширования (сек.)
#	"DISPLAY_PANEL"		=>	"Y",							# Добавлять в админ. панель кнопки для данного компонента
#	)
#);
global $USER;
if ($USER->IsAdmin()) {
?>
<form id="form1" name="form1" method="GET" action="">
<input name="DIR" type="text" id="DIR" size="100" maxlength="255"  value="/temp/"/>
<br />
<input type="hidden" name="SECTION_ID" value="<?=$_REQUEST["SECTION_ID"]?>" />
<label>
-----------
<input name="U" type="radio" checked="checked" />
</label>
<br />
Удалить 
<input name="U" type="radio" value="D" />
<br />
Добавить 
<input name="U" type="radio" value="Y" />


<label>
<input type="submit" name="Submit" value="Submit" />
</label>
</form><br>
<a href=
"/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=54&type=gallery&lang=ru&find_section_section=<?=$_GET["SECTION_ID"]?>">Редактировать раздел</a> | <a href=
"/bitrix/admin/iblock_list_admin.php?type=gallery&lang=ru&IBLOCK_ID=54&find_section_section=0">Редактировать разделы </a>

<?}
?>
<?php

					function makeIcons_MergeCenter($src, $dst, $dstx, $dsty){
@unlink($dst);
//$src = original image location
//$dst = destination image location
//$dstx = user defined width of image
//$dsty = user defined height of image

$allowedExtensions = 'JPG jpg jpeg gif png JPG JPEG GIF PNG';

$name = explode(".", $src);
$currentExtensions = $name[count($name)-1];
$extensions = explode(" ", $allowedExtensions);

for($i=0; count($extensions)>$i; $i=$i+1){
if($extensions[$i]==$currentExtensions)
{ $extensionOK=1; 
$fileExtension=$extensions[$i]; 
break; }
}

if($extensionOK){

$size = getImageSize($src);
$width = $size[0];
$height = $size[1];
if ($width< $height)  
{$dstxtemp=$dstx;
$dstx=$dsty;
$dsty=$dstxtemp;
}

print_r($size);
if($width >= $dstx AND $height >= $dsty){

$proportion_X = $width / $dstx;
$proportion_Y = $height / $dsty;

if($proportion_X > $proportion_Y ){
$proportion = $proportion_Y;
}else{
$proportion = $proportion_X ;
}
$target['width'] = $dstx * $proportion;
$target['height'] = $dsty * $proportion;

$original['diagonal_center'] = 
round(sqrt(($width*$width)+($height*$height))/2);
$target['diagonal_center'] = 
round(sqrt(($target['width']*$target['width'])+
($target['height']*$target['height']))/2);

$crop = round($original['diagonal_center'] - $target['diagonal_center']);

if($proportion_X < $proportion_Y ){
$target['x'] = 0;
$target['y'] = round((($height/2)*$crop)/$target['diagonal_center']);
}else{
$target['x'] =  round((($width/2)*$crop)/$target['diagonal_center']);
$target['y'] = 0;
}

if($fileExtension == "jpg" OR $fileExtension=='jpeg'){ 
$from = ImageCreateFromJpeg($src); 
}elseif ($fileExtension == "gif"){ 
$from = ImageCreateFromGIF($src); 
}elseif ($fileExtension == 'png'){
 $from = imageCreateFromPNG($src);
}

$new = ImageCreateTrueColor ($dstx,$dsty);

imagecopyresampled ($new,  $from,  0, 0, $target['x'], 
$target['y'], $dstx, $dsty, $target['width'], $target['height']);

 if($fileExtension == "jpg" OR $fileExtension == 'jpeg'){ 
imagejpeg($new, $dst, 70); 
}elseif ($fileExtension == "gif"){ 
imagegif($new, $dst); 
}elseif ($fileExtension == 'png'){
imagepng($new, $dst);
}
}
}
}

if ($_REQUEST["U"]=='D') {


if(CModule::IncludeModule("iblock"))
{
 
   $items = GetIBlockElementList(8,    $_REQUEST["SECTION_ID"]  , Array("SORT"=>"ASC") , 30);
 
   while($arItem = $items->GetNext())
   {
      echo "Удалена фотка : ".$arItem["NAME"]."<br>";
  $DB->StartTransaction();	
  if(!CIBlockElement::Delete($arItem["ID"]))	{		$strWarning .= 'Error!';		$DB->Rollback();	}	else		$DB->Commit();
	  
	  
   }
   
}

}



if(CModule::IncludeModule("iblock"))
if ($_REQUEST["U"]=='Y') {
	$dir = $_SERVER["DOCUMENT_ROOT"]."/photo/temp/";
	if ($_REQUEST["DIR"]!='') 	$dir =  $_SERVER["DOCUMENT_ROOT"]."/photo/".$_REQUEST["DIR"];
	// Открыть заведомо существующий каталог и начать считывать его содержимое
	$count=0;
	if (is_dir($dir))
	{
		if ($dh = opendir($dir))
		{
			while (($file = readdir($dh)) !== false)
			{
				$count++;
				if ($count>12) 
				die('Обновить страницу<script language="JavaScript" type="text/javascript">location.reload(true);</script>');
				if ($count>2)
				{
				if (strpos($file,'humb_')==0) {
				
				//	print "Файл: $file : тип: " . filetype($dir . $file) . "\n";
					$img_path = $dir . $file;

$new_img_file="FOO".md5(rand(1000, 10000)).".jpg";
			
$new_img_path = $_SERVER["DOCUMENT_ROOT"]."/tmp/".$new_img_file;
makeIcons_MergeCenter($img_path,	$new_img_path, 145, 108);
	
		sleep(1);			
					
					
					$el = new CIBlockElement;


					
					$arLoadProductArray = Array(
						"MODIFIED_BY"		=>	$USER->GetID(),				# элемент изменен текущим пользователем
						"IBLOCK_SECTION"	=>	array($_REQUEST["SECTION_ID"]),	# элемент лежит в корне раздела
						"IBLOCK_ID"		=>	54,
						"PROPERTY_VALUES"	=>	$PROP,
						"NAME"			=>	date("d.m.Y H:i:s"),
						"ACTIVE"			=>	"Y",							# активен
						"PREVIEW_TEXT"	=>	"",
						"DETAIL_TEXT"		=>	"",
						"PREVIEW_PICTURE"	=>	CFile::MakeFileArray($new_img_path),
						"DETAIL_PICTURE"	=>	CFile::MakeFileArray($img_path)
						
					);

					echo "<pre>";
					echo CFile::ShowImage("/tmp/".$new_img_file);
					print_r($arLoadProductArray["PREVIEW_PICTURE"]);
echo "</pre>";	

echo "<pre>";
					print_r($arLoadProductArray["DETAIL_PICTURE"]);
echo "</pre><hr>";	
sleep(1);
			
					if($PRODUCT_ID = $el->Add($arLoadProductArray,false,false)) 
					{	echo "New ID: ".$PRODUCT_ID;
					@unlink($img_path);
					}
					else
					{
						echo "Error: ".$el->LAST_ERROR;
}

echo 
			//	@unlink($new_img_path);		  
				//	@unlink($img_path);	
sleep(1);					

				}
				}
			}
			closedir($dh);
		}
	}
}
?> 
<?
$APPLICATION->IncludeFile("iblock/photo/sections_top.php", Array(
	"IBLOCK_TYPE"			=>	"photo",						# Тип инфо-блока
	"IBLOCK_ID"			=>	'54',							# Инфо-блок
	"PARENT_SECTION_ID"	=>	"0",							# ID родительского раздела
	"SECTION_SORT_FIELD"	=>	"sort",						# По какому полю сортируем разделы
	"SECTION_SORT_ORDER"	=>	"asc",						# Порядок сортировки разделов
	"SECTION_COUNT"		=>	"20",						# Максимальное количество выводимых разделов
	"SECTION_URL"			=>	"/about/gallery/section.php?",	# URL ведущий на страницу с содержимым раздела
	"ELEMENT_COUNT"		=>	"9",							# Максимальное количество фотографий выводимых в каждом разделе
	"SECTION_COUNT"		=>	"9",							# Количество выводимых разделов на страницу
	"LINE_ELEMENT_COUNT"	=>	"3",							# Количество фотографий выводимых в одной строке таблицы
	"LINE_SECTION_COUNT"	=>	"3",							# Количество разделов выводимых в одной строке таблицы
	"ELEMENT_SORT_FIELD"	=>	"sort",						# По какому полю сортируем фотографии
	"ELEMENT_SORT_ORDER"	=>	"asc",						# Порядок сортировки фотографий в разделе
	"FILTER_NAME"			=>	"arrFilter",					# Имя выходящего массива для фильтрации
	"CACHE_FILTER"		=>	"N",							# Кэшировать при установленом фильтре
	"CACHE_TIME"			=>	"0",							# Время кэширования (сек.)
	"DISPLAY_PANEL"		=>	"Y",							# Добавлять в админ. панель кнопки для данного компонента
	)
);
?>
<?$APPLICATION->IncludeFile("iblock/photo/section.php", Array(
	"IBLOCK_TYPE"			=>	"photo",						# Тип инфо-блока
	"IBLOCK_ID"			=>	'54',							# Инфо-блок
	"SECTION_ID"			=>	$_REQUEST["SECTION_ID"],		# ID раздела
	"PAGE_ELEMENT_COUNT"	=>	"125",						# Количество элементов на странице
	"LINE_ELEMENT_COUNT"	=>	"4",							# Количество фотографий выводимых в одной строке таблицы
	"ELEMENT_SORT_FIELD"	=>	"sort",						# По какому полю сортируем фотографии
	"ELEMENT_SORT_ORDER"	=>	"asc",						# Порядок сортировки фотографий в разделе
	"FILTER_NAME"			=>	"arrFilter",					# Имя массива со значениями фильтра для фильтрации элементов
	"CACHE_FILTER"		=>	"N",							# Кэшировать при установленом фильтре
	"CACHE_TIME"			=>	"0",							# Время кэширования (сек.)
	"DISPLAY_PANEL"		=>	"Y",							# Добавлять в админ. панель кнопки для данного компонента
	)
);?>&nbsp;

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>