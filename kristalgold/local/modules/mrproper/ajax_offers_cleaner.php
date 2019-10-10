<? defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();


$arParams["IBLOCK_ID_OFFERS"] = 2;  // offers IBLOCK_ID

global $DB;
CModule::IncludeModule("catalog");

if(!CCatalogSKU::GetInfoByOfferIBlock($arParams["IBLOCK_ID_OFFERS"])){
	echo "Not Offers Iblock ID!!!!!";
	exit;
}

$next = ((isset($_REQUEST['next']) and $_REQUEST['next']>0) ? $_REQUEST['next']:0);


$cntStep = COption::GetOptionString(ADMIN_MODULE_NAME, "cnt_by_step", "10");

// all cnt for percent stat


$next = ((isset($_REQUEST["next"]) and (int)$_REQUEST["next"]>0) ? (int)$_REQUEST["next"]:1);    
$nav = array(
	"nPageSize" => $cntStep,
	"iNumPage" => $next,
	"bShowAll" => false
);

$ALLCNT = CIBlockElement::GetList(false, array("IBLOCK_ID"=>$arParams["IBLOCK_ID_OFFERS"], "ACTIVE"=>"N"), array()); 
// search offers IDs
$res = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>$arParams["IBLOCK_ID_OFFERS"], "ACTIVE"=>"N"), false, $nav, array("ID"));

$selectedRows = 0;
while($ob = $res->fetch()){
	$arIDs[] = $ob["ID"];
	$selectedRows++;
}

// search this ID in orders
$results = $DB->Query("SELECT `PRODUCT_ID` from b_sale_basket WHERE `PRODUCT_ID` in (".implode(",",$arIDs).")");
$arIDinOrders = array();
while ($row = $results->Fetch()){
	$arIDinOrders[] = $row["PRODUCT_ID"];
}

$strWarning = "";

// delete unnecessary offers 
$deleted = 0;
$inorder = 0;
foreach ($arIDs as $offerID) {
	if(!in_array($offerID,$arIDinOrders)){
		$deleted++;
		$arDeleted[]=$offerID;
		// delete element
			
			$DB->StartTransaction();
			if(!CIBlockElement::Delete($offerID)){
				$strWarning .= 'Error!';
				$DB->Rollback();
				echo $strWarning;
				exit;
			}else{
				$DB->Commit();
			}
	}else{
		$inorder++;
	}
}


	  
if(count($selectedRows)>0){
	$processFlag = true;
}else{
	$processFlag = false;
}

//if($next > 10) exit; 

echo json_encode(
	array(
		'process' => htmlspecialchars($_REQUEST["process"]),
		'percent' => ($next*$cntStep*100)/$ALLCNT,
		'next' => ($next+1),
		'status' => ($processFlag ? 'process':''),
		'all' => $ALLCNT,
		'done' => $next*$cntStep,
		'inorder' => $inorder,
		'deleted' => $deleted,
		'arDeleted' => serialize($arDeleted)
	)
);

