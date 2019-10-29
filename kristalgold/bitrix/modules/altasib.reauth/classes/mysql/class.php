<?
/**
 * Company developer: ALTASIB
 * Developer: Toporikov Sergey
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2016 ALTASIB
 */

IncludeModuleLangFile(__FILE__);
class CAltasibReAuthBase
{
	Function Add($USER_ID, $NEED_AUTH = false)
	{
		if(!$USER_ID)
			return;
		if(CAltasibReAuthBase::Get($USER_ID, $NEED_AUTH))
			return;

		$table_name = 'altasib_reauth';
		$arFields = array(
			"USER_ID" => intval($USER_ID),
			"NEED_AUTH" => intval($NEED_AUTH),
		);
		global $DB;
		$res = $DB->Add($table_name, $arFields);
		return $res;
	}

	Function Delete($USER_ID, $NEED_AUTH = null)
	{
		if(!$USER_ID)
			return;

		$table_name = 'altasib_reauth';
		$strSql = "DELETE FROM ".$table_name." WHERE USER_ID = ".intval($USER_ID);
		if(!is_null($NEED_AUTH))
		{
			$strSql .= " AND NEED_AUTH = ".intval($NEED_AUTH);
		}
		global $DB;
		$DB->Query($strSql);
		return true;
	}

	Function Get($USER_ID, $NEED_AUTH = null)
	{
		if(!$USER_ID)
			return false;

		$res = false;
		$table_name = 'altasib_reauth';
		$strSql = "SELECT NEED_AUTH FROM ".$table_name." WHERE USER_ID = ".intval($USER_ID);
		if(!is_null($NEED_AUTH))
		{
			$strSql .= " AND NEED_AUTH = ".intval($NEED_AUTH);
		}
		global $DB;
		if($res = $DB->Query($strSql)->Fetch())
		{
			if(!is_null($NEED_AUTH))
				return $res;
			else
				return intval($res["NEED_AUTH"]);
		}
		else
			return $res;
	}
}
?>