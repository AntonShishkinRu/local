<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$ret = array('status'=>'error');


if ($_REQUEST['mode'] == 'auth') {
    global $USER;
    if (!is_object($USER)) $USER = new CUser;
	
	//// удаление товаров - начало
	/*if (isset($_REQUEST['EMAIL']) && $_REQUEST['EMAIL'] && isset($_REQUEST['PASSWORD']) && $_REQUEST['PASSWORD']) {
		$rsUser = CUser::GetByLogin( $_REQUEST['EMAIL'] );
	    if ($arUser = $rsUser->Fetch())
	    {
			  if(strlen($arUser["PASSWORD"]) > 32)
			  {
				 $salt = substr($arUser["PASSWORD"], 0, strlen($arUser["PASSWORD"]) - 32);
				 $db_password = substr($arUser["PASSWORD"], -32);
			  }
			  else
			  {
				 $salt = "";
				 $db_password = $arUser["PASSWORD"];
			  }

			  $user_password =  md5($salt.$_REQUEST['PASSWORD']);
			  if ( $user_password == $db_password )
			  {
				  if (CModule::IncludeModule("sale")) {
					  CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
					  unset($_SESSION["CATALOG_COMPARE_LIST"][1]["ITEMS"]);
				  }
			  }
	    }
			
	} */
	//// удаление товаров - окончание
	$arAuthResult = false;
   if ($_REQUEST['EMAIL'])
		$arAuthResult = $USER->Login($_REQUEST['EMAIL'],$_REQUEST['PASSWORD'], "Y");
   
   if ($arAuthResult === true) {
		$ret['status'] = 'success';
	} else {
		$ret['error_text'] = 'E-mail или пароль указан неверно';
		/*
		if ($phone = preg_replace("/[^0-9\+]/", '', $_REQUEST['PHONE'])) {
			$dbUsers = CUser::GetList($sort_by, $sort_ord, array('PERSONAL_PHONE' => $phone));
			$ok = 0;
			while ($arUser = $dbUsers->Fetch()) { 
				$arAuthResult = $USER->Login($arUser['EMAIL'],$_REQUEST['PASSWORD'], "Y");
				if ($arAuthResult === true) {
					$ret['status'] = 'success';
					$ok = 1; 
					break;
				} 
			} 
			
			if (!$ok) $ret['error_text'] = 'E-mail/номер телефона или пароль указан неверно';
		} else $ret['error_text'] = 'E-mail/номер телефона или пароль указан неверно';
		*/
	}

}

if ($_REQUEST['mode'] == 'lost') {
    global $USER;
    $arResult = $USER->SendPassword($_REQUEST['EMAIL'], $_REQUEST['EMAIL']);
    if($arResult["TYPE"] == "OK")
        $ret['status'] = 'success';
    else
        $ret['error_text'] = "Введенные логин (email) не найдены.";
}

if ($_REQUEST['mode'] == 'reg') {
    $user = new CUser;
    $arFields = $_REQUEST['user'];
    $arFields['LOGIN'] = $arFields['EMAIL'];
	$arFields['GROUP_ID'] = array(3,4);
    $ID = $user->Add($arFields);
    if (($id = intval($ID)) > 0) {
        $ret['status'] = 'success';
		
		$rsSites = CSite::GetByID("s1");
		$arSite = $rsSites->Fetch();

		$arEventFields= array(
			"USER_ID" => $id,
			"NAME" => $arFields["NAME"],
			"LAST_NAME" => $arFields["PERSONAL_PHONE"],
			"EMAIL" => $arFields['EMAIL'],
			"SITE_NAME" => $arSite['SITE_NAME'],
			"ACTIVE" => "N",
			);
		CEvent::Send("USER_INFO", $arSite['LID'], $arEventFields, "N");
    } else
        $ret['error_text'] = $user->LAST_ERROR;
} 


echo json_encode($ret);
