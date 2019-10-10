<?
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	$filter = Array
	(
	
	);
	$rsUsers = CUser::GetList(($by="id"), ($order="desc"), $filter);
	$user = htmlspecialchars($_GET["name"]);
	$ajax = htmlspecialchars($_GET["ajax"]);
	if($ajax == "Y")
	while($arItem = $rsUsers->GetNext())
	{
		if($arItem['LOGIN'] == $user)	
	 	{
	 		echo '1';
	 		return;
	 	}	
	}

?>