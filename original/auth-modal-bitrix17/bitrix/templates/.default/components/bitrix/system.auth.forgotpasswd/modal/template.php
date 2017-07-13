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
?>
<div class="bx-system-auth-forgotpasswd">
	<?
	ShowMessage($arParams["~AUTH_RESULT"]);
	?>
	<form name="bform"
	      method="post"
	      target="_top"
	      class="uk-form  uk-form-stacked"
	      action="<?=$arResult["AUTH_URL"]?>">

		<? if(strlen($arResult["BACKURL"]) > 0): ?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
		<? endif; ?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="SEND_PWD">

		<div class="uk-form-row">
			<div class="uk-form-controls">
				<input type="text"
				       name="USER_EMAIL"
				       value="<?=$arResult['USER_EMAIL']?>"
				       maxlength="255"
				       class="uk-form-large uk-width-1-1"
				       placeholder="<?=GetMessage("AUTH_EMAIL")?>">
				<input name="USER_LOGIN" type="hidden">
			</div>
		</div>
		<? if($arResult["USE_CAPTCHA"]): ?>
			<div class="uk-form-row">
				<div class="uk-form-controls">
					<div class="bx-captcha">
						<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA"/>
					</div>
					<a href="<?=$APPLICATION->GetCurPage()?>?reload_captcha=yes"
					   class="reload-captcha"
					   title="<?=GetMessage('AUTH_RELOAD_CAPTCHA_TITLE')?>"><i class="uk-icon-refresh uk-link-muted"></i></a>
					<input type="text" name="captcha_word"
					       maxlength="50"
					       value=""
					       class="uk-width-1-1"
					       autocomplete="off"
					       placeholder="<?=GetMessage("AUTH_CAPTCHA")?>">
				</div>
			</div>
		<? endif ?>
		<div class="uk-form-row">
			<button type="submit"
			        name="send_account_info"
			        class="uk-button uk-button-large uk-button-primary  uk-width-1-1"
			        value="<?=GetMessage("AUTH_SEND")?>"><?=GetMessage("AUTH_SEND")?></button>
		</div>
		<div class="uk-form-row">
			<div class="uk-form-controls">
					<a href="<?=$arResult["AUTH_AUTH_URL"]?>"
					   rel="nofollow"
					   class="ajax-link"><?=GetMessage("AUTH_AUTH")?></a>
			</div>
		</div>
	</form>
</div>