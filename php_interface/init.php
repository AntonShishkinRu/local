<?
AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");
function OnBeforeUserUpdateHandler(&$arFields)
{
	//AddMessage2Log($arFields);
	if ($arFields["EMAIL"]);
	else if ($arFields["ID"]){
		$rsUsers = CUser::GetList(($by = "NAME"), ($order = "desc"), array("ID" => $arFields["ID"]));
		if ($arUser = $rsUsers->Fetch()) {
			$arFields["EMAIL"] = $arUser['EMAIL'];
		}
		
	}
	
	if ($arFields["EMAIL"]) $arFields["LOGIN"] = $arFields["EMAIL"];
    return $arFields;
}

