<?
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
	
	CModule::IncludeModule("iblock");
	CModule::IncludeModule("main");
	global $APPLICATION;
	$arIblocks = $arParams["IBLOCK_ID"];
	$arExcludedFoldersParams = $arParams["EXCLUDED_FOLDERS"];
	
	foreach ($arExcludedFoldersParams as $folder) {
		if(empty($folder)){continue;}
		$arExcludedFolders[] = trim($folder);
	}
	function mapTree($dataset, $Params) {
		$tree = array();
		foreach ($dataset as $id=>&$node) {
			if ($node['IBLOCK_SECTION_ID'] == '') { // root node
				$tree[$id] = &$node;
				
				} else { // sub node
				unset($allElements);
                if (!isset($dataset[$node['IBLOCK_SECTION_ID']]['CHILD']))
                {
                    $dataset[$node['IBLOCK_SECTION_ID']]['CHILD'] = array();
				}
				$dataset[$node['IBLOCK_SECTION_ID']]['CHILD'][$id] = &$node;
                if($Params["INCLUDE_ELEMENTS"] == "Y"){
                	$active = "Y";
					$getElements = CIBlockElement::GetList(Array('left_margin' => 'ASC'), Array("IBLOCK_ID"=> &$node["IBLOCK_ID"], "SECTION_ID" => $id, "ACTIVE" => $active), false, false, Array("NAME","DETAIL_PAGE_URL"));
					while ($arrElements = $getElements->GetNext())
					{
						$allElements[$arrElements['ID']] = $arrElements;
					}
					$dataset[$node['IBLOCK_SECTION_ID']]['CHILD'][$id]["ITEMS"] = $allElements;
				}
				
				
			}
		}
		
		return $tree;
	}
	
	foreach($arIblocks as $IblockID){
		$res = CIBlockSection::GetList(Array('left_margin' => 'ASC'),Array('IBLOCK_ID'=>$IblockID,'ACTIVE'=>'Y'),false,Array());
		while ($arr = $res->GetNext())
		{
			if(!empty($arr["ID"])){
				$allArr[0]["HAS_SECTIONS"] = "Y";				
				$allArr[$arr["ID"]] = $arr;
			}
			
		}
		
		if(empty($allArr[0]["HAS_SECTIONS"])){
			//if($Params["INCLUDE_ELEMENTS"] == "Y"){
			$active = "Y";
			unset($allElements);
			$getElements = CIBlockElement::GetList(Array('left_margin' => 'ASC'), Array("IBLOCK_ID"=> $IblockID, "ACTIVE" => $active), false, false, Array("NAME","DETAIL_PAGE_URL"));
			while ($arrElements = $getElements->GetNext())
			{
				$allElements[$arrElements['ID']] = $arrElements;
			}
			$data["ITEMS"] = $allElements;
			//}
			}else{
			$data = mapTree($allArr, $arParams);
		}
		
		
		$arResult["IBLOCKS"][$IblockID] = $data;
		unset($data);
		unset($allArr);
		
	}
	function getFoldersList($arExcludedFolders){
		
		$directory = $_SERVER["DOCUMENT_ROOT"];
		$root_dir = scandir($directory);
		foreach($root_dir as $dir){
			if($dir == "." || $dir == ".." || in_array($dir, $arExcludedFolders) || is_file($dir) || strpos($dir, ".") !== false){
				continue;
				}else{
				
				foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($_SERVER["DOCUMENT_ROOT"]."/".$dir)) as $file) {
					$full_pathname = $file->getPathname();
					if(strpos($full_pathname, "index.php") === false){
						continue;
						}else{
						$url_path = str_replace($_SERVER["DOCUMENT_ROOT"], "", str_replace("index.php", "", $full_pathname));
						$arFolders[$url_path]["ROOT_PATH"] = str_replace("index.php", "", $full_pathname);
						$arFolders[$url_path]["PATH"] = str_replace($_SERVER["DOCUMENT_ROOT"], "", str_replace("index.php", "", $full_pathname));
						if(file_exists(str_replace("index.php", "", $full_pathname).".section.php")){
							include_once str_replace("index.php", "", $full_pathname).".section.php";
							if($sSectionName){
								$arFolders[$url_path]["NAME"] = $sSectionName;
							}elseif($arDirProperties["TITLE"]){
								$arFolders[$url_path]["NAME"] = $arDirProperties["TITLE"];
							}else{
								$arFolders[$url_path]["NAME"] = "Без названия";
							}						
						}else{
							$arFolders[$url_path]["NAME"] = "Без названия";
						}
					}
					
				}
				
			}
		}
		return $arFolders;   
	}
	
	$arResult["FOLDERS"] = getFoldersList($arExcludedFolders);
	
	$this->IncludeComponentTemplate();
?>