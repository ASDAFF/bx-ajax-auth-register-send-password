<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/**
 * Bitrix vars
 *
 * @var CBitrixComponent         $component
 * @var CBitrixComponentTemplate $this
 * @var array                    $arParams
 * @var array                    $arResult
 * @var array                    $arLangMessages
 * @var array                    $templateData
 *
 * @var string                   $templateFile
 * @var string                   $templateFolder
 * @var string                   $parentTemplateFolder
 * @var string                   $templateName
 * @var string                   $componentPath
 *
 * @var CDatabase                $DB
 * @var CUser                    $USER
 * @var CMain                    $APPLICATION
 */

/* Битрикс идею не поддержал, пусть пока будет тут, код не рабочий...
$bCaptchaValid = false;

$captcha_word = htmlspecialcharsbx(trim($_REQUEST['captcha_word']));
$captcha_sid  = htmlspecialcharsbx(trim($_REQUEST['captcha_sid']));
if($captcha_word && $captcha_sid)
{
	if($APPLICATION->CaptchaCheckCode($captcha_word, $captcha_sid))
		$bCaptchaValid = true;
}

//Если есть ошибки, мыло не прячем
if(!$bCaptchaValid)
	$arResult['USER_EMAIL'] = htmlspecialcharsbx(trim($_REQUEST['USER_EMAIL']));
else
	$arResult['HIDE_FORM'] = true;


$arPrint = array(
	'$_REQUEST' => $_REQUEST,
	'$arResult' => $arResult,
	'$bCaptchaValid' => $bCaptchaValid,
);
$tttfile = dirname(__FILE__) . '/1_txt.php';
file_put_contents($tttfile, "<pre>" . print_r($arPrint, 1) . "</pre>\n");

*/

