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
<div class="bx-system-auth-changepasswd">
	<?
	ShowMessage($arParams["~AUTH_RESULT"]);
	?>
	<form name="bform"
	      method="post"
	      class="uk-form  uk-form-stacked"
	      action="<?=$arResult["AUTH_FORM"]?>">

		<? if(strlen($arResult["BACKURL"]) > 0): ?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
		<? endif ?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="CHANGE_PWD">

		<div class="uk-form-row">
			<div class="uk-form-controls">
				<input type="text"
				       name="USER_LOGIN"
				       maxlength="50"
				       value="<?=$arResult["LAST_LOGIN"]?>"
				       placeholder="<?=GetMessage("AUTH_LOGIN")?>"
				       class="uk-form-large uk-width-1-1">
			</div>
		</div>

		<div class="uk-form-row">
			<div class="uk-form-controls">
				<input type="text"
				       name="USER_CHECKWORD"
				       maxlength="50"
				       value="<?=$arResult["USER_CHECKWORD"]?>"
				       placeholder="<?=GetMessage("AUTH_CHECKWORD")?>"
				       class="uk-form-large uk-width-1-1">
			</div>
		</div>

		<div class="uk-form-row">
			<div class="uk-form-controls">
				<input type="password"
				       name="USER_PASSWORD"
				       maxlength="50"
				       value="<?=$arResult["USER_PASSWORD"]?>"
				       autocomplete="off"
				       placeholder="<?=GetMessage("AUTH_NEW_PASSWORD")?>"
				       class="uk-form-large uk-width-1-1">
				<div class="uk-text-small"><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></div>
			</div>
		</div>

		<div class="uk-form-row">
			<div class="uk-form-controls">
				<input type="password"
				       name="USER_CONFIRM_PASSWORD"
				       maxlength="50"
				       value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>"
				       autocomplete="off"
				       placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?>"
				       class="uk-form-large uk-width-1-1">
			</div>
		</div>

		<? if($arResult["USE_CAPTCHA"]): ?>
			<div class="uk-form-row">
				<div class="uk-form-controls">
					<div class="bx-captcha">
						<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA"/>
					</div>
					<input type="text"
					       name="captcha_word"
					       maxlength="50"
					       value=""
					       class="uk-width-1-1"
					       autocomplete="off"
					       placeholder="<?=GetMessage("SYSTEM_AUTH_CAPTCHA")?>">
				</div>
			</div>
		<? endif ?>

		<div class="uk-form-row">
			<div class="uk-text-small uk-margin"><?=GetMessage("AUTH_REQ")?></div>
			<button type="submit"
			       name="change_pwd"
			       class="uk-button uk-button-large uk-button-primary  uk-width-1-1"
			       value="<?=GetMessage("AUTH_CHANGE")?>"><?=GetMessage("AUTH_CHANGE")?></button>
		</div>
<!--		<div>-->
<!--			<a href="--><?//=$arResult["AUTH_AUTH_URL"]?><!--"><b>--><?//=GetMessage("AUTH_AUTH")?><!--</b></a>-->
<!--		</div>-->
	</form>
</div>