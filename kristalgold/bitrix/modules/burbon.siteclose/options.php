<?
CJSCore::Init(array("jquery"));
$module_id = 'burbon.siteclose'; 

$date_today = date("d.m.Y");
$sites_list = array();
$sites_arr = CSite::GetList($by="def", $order="desc", array("ACTIVE"=>"Y"));
while ($site = $sites_arr->Fetch())
{
	$sites_list[] = array($site["LID"] => $site["NAME"]);
}
?>

<?
global $MESS;

IncludeModuleLangFile(__FILE__);

CModule::IncludeModule($module_id);
$MOD_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($MOD_RIGHT>="R"):
    $arAllOptions = array();
    for($i=0;$i<count($sites_list);$i++){
        $keys = array_keys($sites_list[$i]);
        $arAllOptions[$i] = array(
            Array("BU_SC_checkbox_".$keys[0], GetMessage("BU_SC_CHECKBOX"), "N", Array("checkbox", "Y")),
			Array("BU_SC_admin_".$keys[0], GetMessage("BU_SC_ADMIN"), "N", Array("checkbox", "Y")),
			Array("selectbox_type_".$keys[0], GetMessage("BU_SC_TYPE"), "simple", Array("selectbox", array('simple'=>GetMessage("BU_SC_TYPE_SIMPLE")))),
			Array("selectbox_theme_".$keys[0], GetMessage("BU_SC_THEME"), "blue", Array("selectbox", array('dark'=>GetMessage("BU_SC_THEME_DARK"), 'white'=>GetMessage("BU_SC_THEME_WHITE"), 'blue'=>GetMessage("BU_SC_THEME_BLUE"), 'pink'=>GetMessage("BU_SC_THEME_PINK")))),
            Array("BU_SC_header_".$keys[0], GetMessage("BU_SC_HEADER"), GetMessage("BU_SC_HEADER_EX"), Array("text", "")),
            Array("BU_SC_logo_".$keys[0], GetMessage("BU_SC_LOGO"), "/bitrix/themes/.default/".$module_id."/simple/images/logo.jpg", Array("text", "")),
            Array("BU_SC_text_".$keys[0], GetMessage("BU_SC_TEXT"), GetMessage("BU_SC_TEXT_EX"), Array("textarea", "4","40")),
            //Array("BU_SC_contacts_".$keys[0], GetMessage("BU_SC_CONTACTS"), GetMessage("BU_SC_CONTACTS_EX"), Array("textarea", "3","40")),
			Array("BU_SC_phone_".$keys[0], GetMessage("BU_SC_PHONE"), GetMessage("BU_SC_PHONE_EX"), Array("text", "")),
			Array("BU_SC_email_".$keys[0], GetMessage("BU_SC_EMAIL"), GetMessage("BU_SC_EMAIL_EX"), Array("text", "")),
			Array("BU_SC_address_".$keys[0], GetMessage("BU_SC_ADDRESS"), GetMessage("BU_SC_ADDRESS_EX"), Array("text", "")),
            Array("BU_SC_chk_contacts_".$keys[0], GetMessage("BU_SC_SHOW_CONTACTS"), "Y", Array("checkbox", "Y")),
            Array("BU_SC_chk_logo_".$keys[0], GetMessage("BU_SC_SHOW_LOGO"), "Y", Array("checkbox", "Y")),
            Array("BU_SC_chk_counter_".$keys[0], GetMessage("BU_SC_SHOW_COUNT"), "Y", Array("checkbox", "Y")),
            Array("BU_SC_date_".$keys[0], GetMessage("BU_SC_DATE"), $date_today, Array("text", "")),
        );
		
		$arAllOptions_part1[$i] = array(
            Array("BU_SC_checkbox_".$keys[0], GetMessage("BU_SC_CHECKBOX"), "N", Array("checkbox", "Y")),
            Array("BU_SC_admin_".$keys[0], GetMessage("BU_SC_ADMIN"), "N", Array("checkbox", "Y")),
			Array("selectbox_type_".$keys[0], GetMessage("BU_SC_TYPE"), "simple", Array("selectbox", array('simple'=>GetMessage("BU_SC_TYPE_SIMPLE")))),
			Array("selectbox_theme_".$keys[0], GetMessage("BU_SC_THEME"), "blue", Array("selectbox", array('dark'=>GetMessage("BU_SC_THEME_DARK"), 'white'=>GetMessage("BU_SC_THEME_WHITE"), 'blue'=>GetMessage("BU_SC_THEME_BLUE"), 'pink'=>GetMessage("BU_SC_THEME_PINK")))),
            Array("BU_SC_header_".$keys[0], GetMessage("BU_SC_HEADER"), GetMessage("BU_SC_HEADER_EX"), Array("text", "")),
			Array("BU_SC_chk_logo_".$keys[0], GetMessage("BU_SC_SHOW_LOGO"), "Y", Array("checkbox", "Y")),
		);
		$arAllOptions_part2[$i] = array(
			Array("BU_SC_text_".$keys[0], GetMessage("BU_SC_TEXT"), GetMessage("BU_SC_TEXT_EX"), Array("textarea", "4","40")),
            Array("BU_SC_chk_contacts_".$keys[0], GetMessage("BU_SC_SHOW_CONTACTS"), "Y", Array("checkbox", "Y")),
			Array("BU_SC_phone_".$keys[0], GetMessage("BU_SC_PHONE"), GetMessage("BU_SC_PHONE_EX"), Array("text", "")),
			Array("BU_SC_email_".$keys[0], GetMessage("BU_SC_EMAIL"), GetMessage("BU_SC_EMAIL_EX"), Array("text", "")),
			Array("BU_SC_address_".$keys[0], GetMessage("BU_SC_ADDRESS"), GetMessage("BU_SC_ADDRESS_EX"), Array("text", "")),
			//Array("BU_SC_contacts_".$keys[0], GetMessage("BU_SC_CONTACTS"), GetMessage("BU_SC_CONTACTS_EX"), Array("textarea", "3","40")),
            Array("BU_SC_chk_counter_".$keys[0], GetMessage("BU_SC_SHOW_COUNT"), "Y", Array("checkbox", "Y")),
		);
		
    }
    if($MOD_RIGHT>="W"):
        if ($REQUEST_METHOD=="GET" && strlen($RestoreDefaults)>0)
        {
			COption::RemoveOption($module_id);
            reset($arGROUPS);
            while(list(,$value)=each($arGROUPS))
                $APPLICATION->DelGroupRight($module_id, array($value["ID"]));
			?>
			<?
			 for($i=0;$i<1;$i++){
				$keys = array_keys($sites_list[$i]);?>
				<script>
				$(document).ready(function() {
					$('#BU_SC_checkbox_<?=$keys[0]?>').attr('checked', 'checked').prop('checked', 'checked');
				});
				</script>
				<?
			 }
			?>
			<script>
				$('form[name="siteclose"]').ready(function() {
					$('form[name="siteclose"]').submit();
					/*$.ajax({
						url:    	"<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?echo LANG?>",
						type:     	"POST",
						dataType: 	"html",
						data: 		$('form[name="siteclose"]').serialize()
					});
					*/
				});
			</script>
			<?
        }
		
        if($REQUEST_METHOD=="POST" && strlen($Update)>0)
        {
			if(count($_FILES) > 0) {
				foreach($_FILES as $in_name=>$arFile) {
					$error_im = 1;
					if($arFile['type'] != '') {
						$error_im = 0;
						$parts=explode("/", $arFile['type']);
						$type = $parts[0];
						if ($type != "image"){$arError = GetMessage("BU_SC_FILE_ERROR"); $error_im = 1;}
						if(!$arError){
							$arFile_name_1 = explode('.',$arFile['name']);
							$img_type = $arFile_name_1[count($arFile_name_1)-1];
							unset($arFile_name_1[count($arFile_name_1)-1]);
							$File_name_1 = implode('.',$arFile_name_1);
							$arParams = array("replace_space"=>"_","replace_other"=>"-");
							$file_name = Cutil::translit($File_name_1, "ru", $arParams);
							$file_name = $file_name.'.'.$img_type;
							$uploadfile = $_SERVER['DOCUMENT_ROOT']."/bitrix/themes/.default/".$module_id."/".$file_name;
							move_uploaded_file($arFile['tmp_name'], $uploadfile);
						}
					}
					if($error_im != 1)
						$_POST[str_replace('file_', '', $in_name)] = "/bitrix/themes/.default/".$module_id."/".$file_name;
					else
						$_POST[$in_name] = '';
				}
			}
            foreach($arAllOptions as $key=>$option){
                $keys = array_keys($sites_list[$key]);
                $nameChkBox="BU_SC_checkbox_".$keys[0];
				
				//echo $keys[0].'<br>';
				$loggi = "BU_SC_logo_".$keys[0];
				$$loggi = $_POST["BU_SC_logo_".$keys[0]];
               
			    $path = $_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/'.$keys[0].'/';
                
                CheckDirPath($path);

                $string = '<?include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.$module_id.'/inc_init.php");?>';
                $header_str = '<?header("Content-Type: text/html; charset='.LANG_CHARSET.'");?>';
				if(file_exists($path.'init.php')) {
					$text = file_get_contents($path.'init.php');
				}
				else {
					$text = '';
				}
				
                for($i=0; $i<count($option); $i++) {
                    $name=$option[$i][0];
                    $val=$$name;

                    if($option[$i][3][0]=="checkbox" && $name==$nameChkBox){
                        #var_dump($nameChkBox);
                        if($val!="Y") {						   
						    $val="N";
                            $file = fopen($path.'init.php', 'w');
							$new_text = str_replace("\r\n".$string."\r\n", '', $text);
							$new_text = str_replace("\r".$string."\r", '', $text);
							$new_text = str_replace($string, '', $new_text);
							$new_text = str_replace($header_str."\r\n\r\n", '', $new_text);
							$new_text = str_replace($header_str."\r\r", '', $new_text);
							$new_text = str_replace($header_str."\r\n", '', $new_text);
							$new_text = str_replace($header_str."\r", '', $new_text);
							$new_text = str_replace($header_str, '', $new_text);
							fwrite($file, $header_str."\r".str_replace($string, '', $new_text));
                            fclose($file);
                        }
                        else {
							$file = fopen($path.'init.php', 'w');
							$new_text = str_replace("\r\n".$string."\r\n", '', $text);
							$new_text = str_replace("\r".$string."\r", '', $text);
							$new_text = str_replace($string, '', $new_text);
							$new_text = str_replace($header_str."\r\n\r\n", '', $new_text);
							$new_text = str_replace($header_str."\r\r", '', $new_text);
							$new_text = str_replace($header_str."\r\n", '', $new_text);
							$new_text = str_replace($header_str."\r", '', $new_text);
							$new_text = str_replace($header_str, '', $new_text);
							$new_text = str_replace($new_text."\r\r", $new_text, $new_text);
							$new_text = str_replace($new_text."\r", $new_text, $new_text);
							fwrite($file, $header_str."\r".$new_text.$string);
                            fclose($file);
							echo $new_text;
                        }
                    }
                    COption::SetOptionString($module_id, $name, $val, $option[$i][1]);
                }
            }
        }
    endif; //if($MOD_RIGHT>="W"):?>
<?if($arError):?>
	<div class="adm-info-message-wrap adm-info-message-red">
	<div class="adm-info-message">
		<div class="adm-info-message-title"><?=GetMessage("BU_SC_ERROR")?></div>
			<?=$arError?>
		<div class="adm-info-message-icon"></div>
	</div>
</div>
<?endif;?>
	
<?
   $aTabs = array();
   foreach($sites_list as $site_arr){
      foreach($site_arr as $site_id=>$site_name){
          $aTabs[] = array('DIV' => 'set'.$site_id, 'TAB' => $site_name, 'ICON' => 'BU_SC_settings', 'TITLE' => GetMessage('BU_SC_MAIN_TITLE').' '.$site_name);
      }
   }

   $tabControl = new CAdminTabControl('tabControl', $aTabs);
   $tabControl->Begin();
?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?echo LANG?>" enctype="multipart/form-data" name="siteclose">
	<?/*<?for($i=0;$i<count($aTabs);$i++):?>
        <?$keys = array_keys($sites_list[$i]);?>
        <?$tabControl->BeginNextTab();?>
        <?__AdmSettingsDrawList($module_id, $arAllOptions[$i]);?>
        <span id="calendar_<?=$keys[0]?>"><?=Calendar("BU_SC_date_".$keys[0], "curform")?></span>
    <?endfor;?>
	*/?>
	<?for($i=0;$i<count($aTabs);$i++):?>
		<?$keys = array_keys($sites_list[$i]);?>
		<?$tabControl->BeginNextTab();?>
		<tr>
			<td colspan="2">
			<div class="adm-info-message-wrap" style="position: relative; top: -15px; text-align: center;">
				<div class="adm-info-message" style="width: 90%; text-align: left;">
					<?=GetMessage("BU_SC_MESSAGE")?>
				</div>
			</div>
			</td>
		</tr>
		<?__AdmSettingsDrawList($module_id, $arAllOptions_part1[$i]);?>
		<tr>
			<td><?=GetMessage("BU_SC_LOGO")?></td>
			<td>
				<?if(COption::GetOptionString($module_id, "BU_SC_logo_".$keys[0], '', $keys[0]) != ''):?>
					<input type="text" name="BU_SC_logo_<?=$keys[0]?>" value="<?echo COption::GetOptionString($module_id, "BU_SC_logo_".$keys[0], '', $keys[0])?>">
				<?endif;?>
				<input type="file" name="BU_SC_logo_file_<?=$keys[0]?>" value="<?//echo COption::GetOptionString($module_id, "BU_SC_logo_".$keys[0], '', $keys[0])?>">
			</td>
		</tr>
		<?__AdmSettingsDrawList($module_id, $arAllOptions_part2[$i]);?>
		<tr>
			<td><?=GetMessage("BU_SC_DATE")?></td>
			<td>
			<?$APPLICATION->IncludeComponent(
			   "bitrix:main.calendar",
			   "",
			   Array(
				  "SHOW_INPUT" => "Y",
				  "FORM_NAME" => "siteclose",               
				  "INPUT_NAME" => "BU_SC_date_".$keys[0],  
				  //"INPUT_NAME_FINISH" => "date_finish",   
															
															
				  "INPUT_VALUE" => COption::GetOptionString($module_id, "BU_SC_date_".$keys[0]),                    
				  //"INPUT_VALUE_FINISH" => $date_now_f,    
				  "SHOW_TIME" => "Y",                       
				  "HIDE_TIMEBAR" => "N"                     
			   ),
			false
			);?>
			</td>
		<tr>
	<?endfor;?>
	
    <?$tabControl->Buttons();?>
    <script type="text/javascript">
        function RestoreDefaults()
        {
            //if(confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
                window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANG?>&mid=<?echo urlencode($mid)?>";
        }
    </script>
    <input type="submit" name="Update" <?if ($MOD_RIGHT<'W') echo "disabled" ?> value="<?echo GetMessage('MAIN_SAVE')?>" class="adm-btn-save">
    <input type="hidden" name="Update" value="Y">
    <?=bitrix_sessid_post();?>
    <input type="button" <?if ($MOD_RIGHT<'W') echo "disabled" ?> title="<?echo GetMessage('MAIN_HINT_RESTORE_DEFAULTS')?>" OnClick="RestoreDefaults();" value="<?echo GetMessage('MAIN_RESTORE_DEFAULTS')?>">

    <?$tabControl->End();?>

</form>
<?endif;?>