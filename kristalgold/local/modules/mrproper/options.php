<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'mrproper');

if(!CModule::IncludeModule("fileman")) die("fileman!!!");
if(!CModule::IncludeModule("iblock")) die("iblock!!!");

require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/mrproper/include.php");

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

if (!$USER->isAdmin()) {
	$APPLICATION->authForm('Nope');
}

?>
	<h1>Clean the database from unnecessary information</h1>
<?

// ajax process
if($_REQUEST['process']=="offers" && check_bitrix_sessid()){
	$GLOBALS['APPLICATION']->RestartBuffer();
	define("NO_AGENT_STATISTIC", true);
	define("NO_KEEP_STATISTIC", true);
	include_once('ajax_offers_cleaner.php');
	die();
}

//  save options
if(isset($_POST["save"]) and !empty($_POST["save"])){
	COption::SetOptionString(ADMIN_MODULE_NAME, "cnt_by_step", $_POST["cnt_by_step"]);
	COption::SetOptionString(ADMIN_MODULE_NAME, "abandoned_basket_days", $_POST["abandoned_basket_days"]);
}


$arPar = array();
$arPar["cnt_by_step"] = COption::GetOptionString(ADMIN_MODULE_NAME, "cnt_by_step", "10");
$arPar["abandoned_basket_days"] = COption::GetOptionString(ADMIN_MODULE_NAME, "abandoned_basket_days", "30");

/////////////////////////////////////////////////////////////////////

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

$tabControl = new CAdminTabControl("tabControl", array(
	array(
		"DIV" => "offers",
		"TAB" => "Inactive offers",
		"TITLE" => "Deleting inactive offers",
	),
		array(
		"DIV" => "baskets",
		"TAB" => "Abandoned baskets",
		"TITLE" => "Removing abandoned baskets",
	),
		array(
		"DIV" => "setting",
		"TAB" => "Setting",
		"TITLE" => "Setting",
	),
));

?>
<script type="text/javascript">

	var bWorkFinished = false;
	var bSubmit;
	//----------------------------------------------
	function set_start(val){
		if (val){
			// debugger;
			ShowWaitWindow();
			document.getElementById('status').innerHTML = 'Process, please wait...';
			CHttpRequest.Action = work_onload;
			CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?process='+val+'&mid=<?=urlencode(ADMIN_MODULE_NAME)?>&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>');

		}else{
			CloseWaitWindow();
		}
	}

	//----------------------------------------------
	function work_onload(result){

			try {JSON.parse(result)
					var CurrentStatus= JSON.parse(result);
					console.log(CurrentStatus.percent.toFixed(2) + "%");

			}catch (e) {
				document.getElementById('status').innerHTML = "<font color='red'><b>ERROR:</b><br>the result returned the following output and we could not convert it to an array of data:<hr>"+result+"</font>";
				CloseWaitWindow();
				return;
			}

			document.getElementById('status').innerHTML = "Done " + CurrentStatus.percent.toFixed(2) + "%";

			if(CurrentStatus.status != 'process' || CurrentStatus.percent >= 100 ){
				CloseWaitWindow();
				document.getElementById('status').innerHTML  = "All done!";
				return;
			}
			if (CurrentStatus.next){
				CHttpRequest.Send('<?= $_SERVER["PHP_SELF"]?>?process='+CurrentStatus.process+'&mid=<?=urlencode(ADMIN_MODULE_NAME)?>&lang=<?=LANGUAGE_ID?>&<?=bitrix_sessid_get()?>&next=' + CurrentStatus.next);
			}else{
				set_start(0);
				bWorkFinished = true;
			}
	}
</script>

<div id='status' class='process-status' style='margin: 4px 0 12px 0; color:green;'></div>

	<form method='POST'>
	<?
	$tabControl->begin();
 
		$tabControl->beginNextTab();
			?>
			<tr>
				<td width="40%" style='text-align:right;'>Inactive offers</td>
				<td width="60%"><a href='javascript:void(0)' onclick='set_start("offers")'>[delete]</a></td>
			</tr>   
			<tr>
			 <tr>
				<td width="40%">Attention!</td>    
				<td width="60%">
					<em>It is recommended that you first remove the abandoned baskets or repeat the procedure for deleting offers later.</em>
				</td>
			</tr>

		<?
		$tabControl->beginNextTab();
			?>
			<tr>
				<td colspan='2'>
					<h3>To delete an abandoned basket, you need:</h3>
					1) add a module connection to the file <b>init.php</b><br>
					<blockquote><pre>CModule::IncludeModule("mrproper");</pre></blockquote>
					2) create an agent in the system, an agent example is shown below (see screenshot)<br><br>
					<img src='/local/modules/mrproper/screenshot.png'>
				</td>	
			</tr>
			<?     
		$tabControl->beginNextTab();
			?>
			<tr>
				<td width="40%">How much to process in one step (delete offers)</td>    
				<td width="60%"><input type='text' name='cnt_by_step' value='<?=$arPar["cnt_by_step"]?>'></td>
			</tr> 
			 <tr>
				<td width="40%">How many days can there be an abandoned basket</td>    
				<td width="60%"><input type='text' name='abandoned_basket_days' value='<?=$arPar["abandoned_basket_days"]?>'></td>
			</tr>         
			 <tr>
				<td width="40%">Attention!</td>    
				<td width="60%">The ID of the information block with trade offers must be specified in the code, the file ajax_offers_cleaner.php </td>
			</tr>  


	<?
	echo bitrix_sessid_post();
	$tabControl->buttons();
	?>
	<input type="submit"
		   name="save"
		   value="Save"
		   title="Save"
		   class="adm-btn-save"
		   />
	</form>
	<?
	$tabControl->end();
	?>

