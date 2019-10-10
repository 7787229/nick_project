 <br><br><br><?
 $SECTION_COUNT=10000;
$CACHE_TIME=0;

global $USER, $APPLICATION;
if (CModule::IncludeModule("iblock")):
	
	IncludeTemplateLangFile(__FILE__);
	$bDisplayPanel = ($DISPLAY_PANEL == "Y") ? True : False;

	if ($bDisplayPanel)
		CIBlock::ShowPanel($IBLOCK_ID, 0, 0, $IBLOCK_TYPE);
	
	/*************************************************************************
						Processing of received parameters
	*************************************************************************/

	$LINE_ELEMENT_COUNT = intval($LINE_ELEMENT_COUNT);
	global $$FILTER_NAME;
	$arrFilter = ${$FILTER_NAME};
	$CACHE_FILTER = ($CACHE_FILTER=="Y") ? "Y" : "N";
	if ($CACHE_FILTER=="N" && count($arrFilter)>0) $CACHE_TIME = 0;

	/*************************************************************************
								Work with cache
	*************************************************************************/

	$CACHE_ID = __FILE__.md5(serialize($arParams).serialize($arrFilter).$USER->GetGroups());
	$obCache = new CPHPCache;
	if($obCache->StartDataCache($CACHE_TIME, $CACHE_ID, "/")):

		/************************************
						Groups
		************************************/

		$arrSectFilter = Array();
		$arrSectFilter["ACTIVE"] ="Y";
			//$arrSectFilter["PROPERTY"] =array("FAV_MAIN_VALUE"=>"%");
	
		
	$arrSectFilter["SECTION_ID"] =$_GET["SECTION_ID"];
		$rsSections = GetIBlockSectionList($IBLOCK_ID, $PARENT_SECTION_ID, array($SECTION_SORT_FIELD => $SECTION_SORT_ORDER, "ID" => "ASC"), $SECTION_COUNT, $arrSectFilter);
		$count_sct = intval($rsSections->SelectedRowsCount());
		
		$arrProp = "";
		if ($count_sct>0) {
		$rsSections->NavStart($SECTION_COUNT);
		
		?>
	
		<?
		$cell = 0;
		$n = 1;
		?>
		<table width="100%" cellpadding="3" cellspacing="0" border="0" align="center">
			<!--<tr>
			  <td colspan="3" height="16" align="left" valign="top" style="background-color:#ffffff;background-image: url('/bitrix/templates/ruexp/images/cadr.gif'); background-repeat: repeat-x;"></td>
			</tr>-->
			<tr>
		<?
		while ($arSection = $rsSections->GetNext()) :

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

			// adding values to the filter
			$arrFilter["ACTIVE"] = "Y";
			$arrFilter["IBLOCK_ID"] = $IBLOCK_ID;
			$arrFilter["SECTION_ID"] = $arSection["ID"];
		
if ($arSection["DEPTH_LEVEL"]==1){ $PROPERTY_FAV_NAME=PROPERTY_FAV_MAIN_VALUE;
}
else 
{$PROPERTY_FAV_NAME=PROPERTY_FAV_VALUE;
}

 
			if ($rsElements = GetIBlockElementList($IBLOCK_ID, $arSection["ID"],Array("RAND"),20 , array("INCLUDE_SUBSECTIONS"=>"Y" , $PROPERTY_FAV_NAME=>'Y'))):
				$count = intval($rsElements->SelectedRowsCount());
				if ($count<=0) 	$rsElements = GetIBlockElementList($IBLOCK_ID, $arSection["ID"],Array("RAND"),20 , array("INCLUDE_SUBSECTIONS"=>"Y" ));
					$count = intval($rsElements->SelectedRowsCount());
				 $arElements  = $rsElements->GetNext();
				// if (strlen($arSection["PICTURE"])>0) $SECT_IMG=$arSection["PICTURE"]; else

				
				$SECT_IMG=$arElements["PREVIEW_PICTURE"];
				/*
				$size=(getImageSize($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($SECT_IMG)));
				$width = $size[0];
				$height = $size[1];
				while ($height>$width)
				{ $arElements  = $rsElements->GetNext();
					$SECT_IMG=$arElements["PREVIEW_PICTURE"];
				$size=(getImageSize($_SERVER["DOCUMENT_ROOT"].CFile::GetPath($SECT_IMG)));
				$width = $size[0];
				$height = $size[1];
				}
				*/
				if ($SECT_IMG<=0) $SECT_IMG=6540;
				$conutbit=0;
				if ($USER->IsAdmin()) $conutbit='-1';
				if ($count>$conutbit):
					$cell++;
				
					?>
			  <td width="<?=(100/$LINE_ELEMENT_COUNT)?>%" align="center" valign="middle">
				<table cellpadding="0" cellspacing="0" border="0"   height="160" >
				
					<tr>
					  <td width="100%"  height="105" align="center" valign="middle"><img src="/bitrix/templates/ruexp/images/dot.gif" width="0" height="100" alt="" title="" border="0"><?echo ShowImage( $SECT_IMG, 150, 150, "hspace='0' vspace='2' style=\"border:1px solid #264E80;\"", '/o-kompanii'.$arSection["SECTION_PAGE_URL"], false, GetMessage("CATALOG_ENLARGE"));?></td>
					</tr>
					<tr>
					  <td width="200" align="center" valign="top"><div style="width:135px; padding-bottom:25px;"><a class="subtitletext" style=" " href="/o-kompanii<?=$arSection["SECTION_PAGE_URL"]?>"><?echo htmlspecialcharsBack($arSection["NAME"])?></a><br></div></td> 
					</tr>
				</table>
			  </td>
			  
			  	<? if (($count_sct==2)&&($n==$count_sct)) {?>
					<td width="<?=(100/$LINE_ELEMENT_COUNT)?>%" align="center" valign="top"> </td>	
									<?}?>
					<?
					if ($count_sct==1) {?>
					<td width="<?=(100/$LINE_ELEMENT_COUNT)?>%" align="center" valign="top"></td><td width="<?=(100/$LINE_ELEMENT_COUNT)?>%" align="center" valign="top"> </td>
					<?}

				
					if($n%$LINE_ELEMENT_COUNT == 0)
					{
									
						$cell = 0;
					?>
						
			</tr>
			
		
			
			<!--<tr>
			  <td colspan="3" height="16" align="left" valign="top" style="background-color:#ffffff;background-image: url('/bitrix/templates/ruexp/images/cadr.gif'); background-repeat: repeat-x;"></td>
			</tr>
			<tr>
			  <td colspan="3" height="16" align="left" valign="top" style="background-color:#ffffff;"><img src="/bitrix/templates/ruexp/images/dot.gif" width="0" height="16" alt="" title="" border="0"></td>
			</tr>
			<tr>
			  <td colspan="3" height="16" align="left" valign="top" style="background-color:#ffffff;background-image: url('/bitrix/templates/ruexp/images/cadr.gif'); background-repeat: repeat-x;"></td>
			</tr>-->
			<tr>
					<?
					} // if($n%$LINE_ELEMENT_COUNT == 0):
					$n++;
			#endif; // if ($count>0):
				endif;
				endif; // if ($rsElements = GetIBlockElementListEx
		endwhile;
		?>
		<?
		?>
			</tr>
			<!--<tr>
			  <td colspan="3" height="16" align="left" valign="top" style="background-color:#ffffff;background-image: url('/bitrix/templates/ruexp/images/cadr.gif'); background-repeat: repeat-x;"></td>
			</tr>-->
	
		<table width="100%" cellpadding="5" cellspacing="10" border="0">
			<tr>
			  <td width="100%" align="left" valign="top"><?=$rsSections->NavPrint("",false);?></td>
			</tr>
		</table>
		
		<?}
		$obCache->EndDataCache();
	endif;
endif;
?>