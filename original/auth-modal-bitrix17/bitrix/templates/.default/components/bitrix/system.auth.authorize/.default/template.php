<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>
<button class="auth-link uk-button uk-button-primary uk-button-large">Вход / Регистрация</button>
<script type="text/javascript">
	$(function () {

		setTimeout(function(){
			$('.auth-link').trigger('click');
		},1000)

	});
</script>
