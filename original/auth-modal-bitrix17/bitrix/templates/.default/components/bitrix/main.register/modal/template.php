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
<div class="bx-main-register">
	<? if($USER->IsAuthorized()): ?>
		<div class="p"><? ShowNote(GetMessage("MAIN_REGISTER_AUTH")) ?></div>
	<? else: ?>
		<?
		if(count($arResult["ERRORS"]) > 0):
			foreach($arResult["ERRORS"] as $key => $error)
				if(intval($key) == 0 && $key !== 0)
					$arResult["ERRORS"][ $key ] = str_replace("#FIELD_NAME#", "&quot;" . strip_tags(GetMessage("REGISTER_FIELD_" . $key)) . "&quot;", $error);

			ShowError(implode("<br />", $arResult["ERRORS"]));

		elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && strlen($_REQUEST['register_submit_button'])):
			?>
			<div class="p"><? ShowNote(GetMessage("REGISTER_EMAIL_WILL_BE_SENT")) ?></div>
			<? $arResult["VALUES"] = array(); ?>
		<? endif ?>
		<form method="POST" action="<?=POST_FORM_ACTION_URI?>" name="regform" autocomplete="off" class="uk-form uk-form-stacked">
			<input type="hidden" name="TYPE" value="REGISTRATION"/>
			<input type="hidden" name="register_submit_button" value="Y"/>
			<input type="text" class="api-mf-antibot" value="" name="ANTIBOT[NAME]">
			<input type="hidden" name="backurl" value="" class="backurl">

			<? foreach($arResult["SHOW_FIELDS"] as $FIELD): ?>
				<? if($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true): ?>
					<div class="uk-form-row">
						<label class="uk-form-label"><? echo GetMessage("main_profile_time_zones_auto") ?>:<? if($arResult["REQUIRED_FIELDS_FLAGS"][ $FIELD ] == "Y"): ?>
								<span class="asterisk">*</span><? endif ?></label>
						<div class="uk-form-controls">
							<select name="REGISTER[AUTO_TIME_ZONE]"
							        onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
								<option value=""><? echo GetMessage("main_profile_time_zones_auto_def") ?></option>
								<option
									 value="Y"<?=$arResult["VALUES"][ $FIELD ] == "Y" ? " selected=\"selected\"" : ""?>><? echo GetMessage("main_profile_time_zones_auto_yes") ?></option>
								<option
									 value="N"<?=$arResult["VALUES"][ $FIELD ] == "N" ? " selected=\"selected\"" : ""?>><? echo GetMessage("main_profile_time_zones_auto_no") ?></option>
							</select>
						</div>
					</div>
					<div class="uk-form-row">
						<label class="uk-form-label"><? echo GetMessage("main_profile_time_zones_zones") ?></label>
						<div class="uk-form-controls">
							<select name="REGISTER[TIME_ZONE]"<? if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"]))
								echo 'disabled="disabled"' ?>>
								<? foreach($arResult["TIME_ZONE_LIST"] as $tz => $tz_name): ?>
									<option value="<?=htmlspecialcharsbx($tz)?>"<?=$arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>><?=htmlspecialcharsbx($tz_name)?></option>
								<? endforeach ?>
							</select>
						</div>
					</div>
				<? else: ?>
					<div class="uk-form-row">
						<?
						$FIELD_NAME = GetMessage("REGISTER_FIELD_" . $FIELD) . ($arResult["REQUIRED_FIELDS_FLAGS"][ $FIELD ] == "Y" ? '*' : '');
						?>
						<div class="uk-form-controls">
							<?
							switch($FIELD)
							{
								case "PASSWORD":
									?>
									<div class="uk-form-password uk-width-1-1">
										<input size="30"
										       type="password"
										       name="REGISTER[<?=$FIELD?>]"
										       value="<?=$arResult["VALUES"][ $FIELD ]?>"
										       placeholder="<?=$FIELD_NAME?>"
										       class="uk-width-1-1 uk-form-large">
										<a data-uk-form-password='{lblShow: "<i class=\"uk-icon-eye-slash\"></i>", lblHide: "<i class=\"uk-icon-eye\"></i>"}' class="uk-form-password-toggle" href=""><i class='uk-icon-eye-slash'></i></a>
									</div>
									<?
									break;
								case "CONFIRM_PASSWORD":
									?>
									<div class="uk-form-password uk-width-1-1">
										<input size="30"
										       type="password"
										       name="REGISTER[<?=$FIELD?>]"
										       value="<?=$arResult["VALUES"][ $FIELD ]?>"
										       placeholder="<?=$FIELD_NAME?>"
										       class="uk-width-1-1 uk-form-large">
										<a data-uk-form-password='{lblShow: "<i class=\"uk-icon-eye-slash\"></i>", lblHide: "<i class=\"uk-icon-eye\"></i>"}' class="uk-form-password-toggle" href=""><i class='uk-icon-eye-slash'></i></a>
									</div>
									<?
									break;

								case "PERSONAL_GENDER":
									?><select name="REGISTER[<?=$FIELD?>]">
									<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
									<option
										 value="M"<?=$arResult["VALUES"][ $FIELD ] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
									<option
										 value="F"<?=$arResult["VALUES"][ $FIELD ] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
									</select><?
									break;

								case "PERSONAL_COUNTRY":
								case "WORK_COUNTRY":
									?><select name="REGISTER[<?=$FIELD?>]"><?
									foreach($arResult["COUNTRIES"]["reference_id"] as $key => $value)
									{
										?>
										<option value="<?=$value?>"<? if($value == $arResult["VALUES"][ $FIELD ]):?> selected="selected"<?endif ?>><?=$arResult["COUNTRIES"]["reference"][ $key ]?></option>
										<?
									}
									?></select><?
									break;

								/*case "PERSONAL_CITY":
								case "WORK_CITY":
									if(CModule::IncludeModule('sale'))
									{
										?>
										<?CSaleLocation::proxySaleAjaxLocationsComponent(
											array(
												"LOCATION_VALUE" => $arResult["VALUES"][$FIELD],
												"CITY_INPUT_NAME" => 'REGISTER['.$FIELD.']',
												"SITE_ID" => SITE_ID,
											),
											array(),
											'',
											true,
											'location-block-wrapper'
										)?>
										<?
									}
									else
									{
										ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
									}
									break;*/

								case "PERSONAL_PHOTO":
								case "WORK_LOGO":
									?><input size="30" type="file" name="REGISTER_FILES_<?=$FIELD?>" /><?
									break;

								case "PERSONAL_NOTES":
								case "WORK_NOTES":
									?><textarea cols="30" rows="5" class="uk-width-1-1"
									            name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][ $FIELD ]?></textarea><?
									break;

								default:
									if($FIELD == "PERSONAL_BIRTHDAY"):?>
										<small><?=$arResult["DATE_FORMAT"]?></small><br/>
									<?endif; ?>
									<input type="text"
									       name="REGISTER[<?=$FIELD?>]"
									       value="<?=$arResult["VALUES"][ $FIELD ]?>"
									       placeholder="<?=$FIELD_NAME?>"
									       class="uk-width-1-1 uk-form-large">
									<?
									if($FIELD == "PERSONAL_BIRTHDAY")
										$APPLICATION->IncludeComponent(
											 'bitrix:main.calendar',
											 '',
											 array(
													'SHOW_INPUT' => 'N',
													'FORM_NAME'  => 'regform',
													'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
													'SHOW_TIME'  => 'N',
											 ),
											 null,
											 array("HIDE_ICONS" => "Y")
										);
									?><?
							}
							?>
						</div>
					</div>
				<? endif ?>
			<? endforeach ?>

			<? if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
				<div class="uk-form-row">
					<h3><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></h3>
				</div>
				<? foreach($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): ?>
					<div class="uk-form-row">
						<label class="uk-form-label"><?=$arUserField["EDIT_FORM_LABEL"]?>:<? if($arUserField["MANDATORY"] == "Y"): ?>
								<span class="asterisk">*</span><? endif; ?></>
						<div class="uk-form-controls">
							<? $APPLICATION->IncludeComponent(
								 "bitrix:system.field.edit",
								 $arUserField["USER_TYPE"]["USER_TYPE_ID"],
								 array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"), null, array("HIDE_ICONS" => "Y")); ?>
						</div>
					</div>
				<? endforeach; ?>
			<? endif; ?>
			<?
			if($arResult["USE_CAPTCHA"] == "Y"):?>
				<div class="uk-form-row">
					<label class="uk-form-label"></label>
					<div class="uk-form-controls">
						<div class="bx-captcha">
							<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>"
							     width="180" height="40" alt="<?=GetMessage("AUTH_CAPTCHA_LOADING")?>"/>
						</div>
						<a href="<?=$APPLICATION->GetCurPage()?>?reload_captcha=yes"
						   class="reload-captcha"
						   title="<?=GetMessage('AUTH_RELOAD_CAPTCHA_TITLE')?>"><i class="uk-icon-refresh uk-link-muted"></i></a>
						<input type="text" name="captcha_word" maxlength="50" value=""
						       class="uk-width-1-1"
						       placeholder="<?=GetMessage("REGISTER_CAPTCHA_PROMT")?>">
					</div>
				</div>
			<? endif; ?>

			<div class="uk-form-row">
				<div class="uk-margin uk-text-small"><?=GetMessage("MAIN_REGISTER_REQUIRED_LABEL")?></div>
				<button type="submit"
				        name="register_submit_button"
				        class="uk-button uk-button-large uk-button-primary  uk-width-1-1"
				        value="<?=GetMessage("AUTH_REGISTER")?>"><?=GetMessage("AUTH_REGISTER")?></button>
			</div>
			<div class="uk-form-row">
				<noindex>
					<a href="<?=$APPLICATION->GetCurPage()?>?login=yes"
					   rel="nofollow"
					   class="ajax-link"><?=GetMessage("AUTH_AUTH")?></a>
				</noindex>
			</div>
		</form>
	<? endif ?>
</div>