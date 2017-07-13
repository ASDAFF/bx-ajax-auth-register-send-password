<?
//---------- Регистрация ----------//
AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
AddEventHandler("main", "OnSendUserInfo", "OnSendUserInfoHandler");
AddEventHandler("main", "OnAfterUserAdd", "OnAfterUserAddHandler");
function OnBeforeUserRegisterHandler(&$arFields)
{
	global $APPLICATION;

	//CSS ANTIBOT
	if(!$_REQUEST['order']) {

		if(isset($_REQUEST['ANTIBOT']) && is_array($_REQUEST['ANTIBOT'])) {
			foreach($_REQUEST['ANTIBOT'] as $k => $v)
				if(empty($v))
					unset($_REQUEST['ANTIBOT'][ $k ]);
		}

		if($_REQUEST['ANTIBOT'] || !isset($_REQUEST['ANTIBOT'])) {

			//Раскомментировать для логирования при необходимости
			//$_REQUEST['DATE'] = date('d-m-Y H:i:s');
			//$tttfile          = dirname(__FILE__) . '/log_OnBeforeUserRegister.log';
			//file_put_contents($tttfile, "<pre>" . print_r($_REQUEST, 1) . "</pre>\n");

			$APPLICATION->ThrowException('Ошибка регистрации');
			return false;
		}
	}
	//\\CSS ANTIBOT

}
function OnAfterUserRegisterHandler(&$arFields)
{
	if($USER_ID = intval($arFields['USER_ID'])) {
		$lid = ($arFields['LID'] ? $arFields['LID'] : SITE_ID);

		//---------- Создаем профиль покупателя после регистрации ----------//
		if($arFields['ACTIVE'] == 'Y' && \Bitrix\Main\Loader::includeModule('sale')) {
			//Тут коды свойств магазина, в них надо подставить соответствующие значения из формы регистрации
			$arSaleProps = array(
				 'LOCATION' => 1,
				 'NAME'     => trim($arFields['NAME']),
				 'EMAIL'    => trim($arFields['EMAIL']),
			);

			$arProfileFields = array(
				 "NAME"           => $arSaleProps['EMAIL'],
				 "USER_ID"        => $USER_ID,
				 "PERSON_TYPE_ID" => 1,
			);

			//Добавит профиль покупателя без свойств
			if($PROFILE_ID = CSaleOrderUserProps::Add($arProfileFields)) {
				$dbUserProps = CSaleOrderUserProps::GetList(
					 array("DATE_UPDATE" => "DESC"),
					 array(
							"ID"      => $PROFILE_ID,
							"USER_ID" => $USER_ID,
					 ),
					 false,
					 false,
					 array("ID", "PERSON_TYPE_ID", "DATE_UPDATE")
				);

				//Добавит свойства в профиль
				if($arUserProps = $dbUserProps->GetNext()) {
					$dbOrderProps = CSaleOrderProps::GetList(
						 array("SORT" => "ASC", "NAME" => "ASC"),
						 array(
								"PERSON_TYPE_ID" => $arUserProps["PERSON_TYPE_ID"],
								"USER_PROPS"     => "Y",
						 ),
						 false,
						 false,
						 array("ID", "NAME", "CODE")
					);
					while($arOrderProps = $dbOrderProps->GetNext()) {
						$curVal = htmlspecialcharsEx($arSaleProps[ $arOrderProps["CODE"] ]);
						if(isset($curVal)) {
							$arPropFields = array(
								 "USER_PROPS_ID"  => $PROFILE_ID,
								 "ORDER_PROPS_ID" => $arOrderProps["ID"],
								 "NAME"           => $arOrderProps["NAME"],
								 "VALUE"          => $curVal,
							);
							CSaleOrderUserPropsValue::Add($arPropFields);
						}
					}
				}
			}
		}

		//---------- Отправим письмо пользователю после регистрации со всеми данными ----------//
		$arUserFields = array(
			 "USER_ID"   => $arFields['USER_ID'],
			 "STATUS"    => ($arFields["ACTIVE"] == "Y" ? 'Активен' : 'Не активен'),
			 "MESSAGE"   => '',
			 "LOGIN"     => $arFields["LOGIN"],
			 "PASSWORD"  => $arFields["PASSWORD"],
			 "URL_LOGIN" => urlencode($arFields["LOGIN"]),
			 "CHECKWORD" => $arFields["CHECKWORD"],
			 "NAME"      => $arFields["NAME"],
			 "LAST_NAME" => $arFields["LAST_NAME"],
			 "EMAIL"     => $arFields["EMAIL"],
		);

		if(!$_REQUEST['order']) {
			CEvent::SendImmediate('USER_INFO', $lid, $arUserFields, 'N');
		}

		//Если отправить стандартную информацию пользователю, но без пароля,
		//тогда отправку выше CEvent::SendImmediate() надо закомментить, а эту раскомментить
		//CUser::SendUserInfo($USER->GetID(), SITE_ID, 'Вы успешно зарегистрированы.', true);
	}
}
function OnSendUserInfoHandler(&$arParams)
{
	//---------- Подставим пароль в поля почтового шаблона ----------//
	$bPassword = ($_REQUEST['USER_PASSWORD'] && $_REQUEST['USER_PASSWORD'] == $_REQUEST['USER_CONFIRM_PASSWORD']);

	if(!$bPassword && $_SESSION['SESS_AUTH']['CONFIRM_PASSWORD']) {
		$bPassword                 = true;
		$_REQUEST['USER_PASSWORD'] = $_SESSION['SESS_AUTH']['CONFIRM_PASSWORD'];
	}

	if($arParams['FIELDS'] && $bPassword) {
		$arParams['FIELDS']['PASSWORD'] = $_REQUEST['USER_PASSWORD'];
	}
}
function OnAfterUserAddHandler(&$arFields)
{
	//---------- Подставим пароль в сессию для метода OnSendUserInfoHandler ----------//
	if($_SESSION['SESS_AUTH'])
		$_SESSION['SESS_AUTH']['CONFIRM_PASSWORD'] = $arFields['CONFIRM_PASSWORD'];
}
?>