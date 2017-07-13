$(function () {
	var auth_url = '/bitrix/templates/.default/auth/auth.php';
	var reg_url = '/bitrix/templates/.default/auth/auth.php?register=yes';
	var auth_title = 'Вход на сайт';
	var reg_title = 'Регистрация';
	var forgot_title = 'Вспомнить пароль';
	var auth_timeout = 10000;
	var auth_error_timeout = 'Внимание! Время ожидания ответа сервера истекло';
	var auth_error_default = 'Внимание! Произошла ошибка, попробуйте отправить информацию еще раз';

	$('#auth-modal').on('submit', 'form', function () {

		$.ajax({
			type: "POST",
			url: auth_url,
			data: $(this).serializeArray(),
			timeout: auth_timeout,
			error: function (request, error) {
				if (error == "timeout") {
					alert(auth_error_timeout);
				}
				else {
					alert(auth_error_default);
				}
			},
			success: function (data) {
				$('#auth-modal .uk-modal-content').html(data);
				$('#auth-modal .backurl').val(window.location.pathname);
			}
		});

		return false;
	});
	$('#auth-modal').on('click', '.ajax-link', function () {

		var href = $(this).attr('href');
		var form_title = '';

		if (href.indexOf('register=yes') > 0)
			form_title = reg_title;
		else if (href.indexOf('forgot_password=yes') > 0)
			form_title = forgot_title;
		else if (href.indexOf('login=yes') > 0)
			form_title = auth_title;
		else
			form_title = auth_title;

		$.ajax({
			type: "GET",
			url: href,
			timeout: auth_timeout,
			error: function (request, error) {
				if (error == "timeout") {
					alert(auth_error_timeout);
				}
				else {
					alert(auth_error_default);
				}
			},
			success: function (data) {
				$('#auth-modal .uk-modal-header').html(form_title);
				$('#auth-modal .uk-modal-content').html(data);
				$('#auth-modal .backurl').val(window.location.pathname);
			}
		});

		return false;
	});
	$('#auth-modal').on('click', '.reload-captcha', function () {

		var reload_captcha = $(this);
		reload_captcha.find('.uk-icon-refresh').addClass('uk-icon-spin');

		$.ajax({
			type: "GET",
			url: $(this).attr('href'),
			timeout: auth_timeout,
			error: function (request, error) {
				if (error == "timeout") {
					alert(auth_error_timeout);
				}
				else {
					alert(auth_error_default);
				}
			},
			success: function (data) {
				$('#auth-modal .bx-captcha').html(data);
				reload_captcha.find('.uk-icon-refresh').removeClass('uk-icon-spin');
			}
		});

		return false;
	});

	$('.auth-link').on('click', function () {

		UIkit.modal("#auth-modal").show();

		$.ajax({
			type: "GET",
			url: auth_url,
			timeout: auth_timeout,
			error: function (request, error) {
				if (error == "timeout") {
					alert(auth_error_timeout);
				}
				else {
					alert(auth_error_default);
				}
			},
			success: function (data) {
				$('#auth-modal .uk-modal-header').html(auth_title);
				$('#auth-modal .uk-modal-content').html(data);
				$('#auth-modal .backurl').val(window.location.pathname);
			}
		});
		return false;
	});

	$('.reg-link').on('click', function () {

		UIkit.modal("#auth-modal").show();

		$.ajax({
			type: "GET",
			url: reg_url,
			timeout: auth_timeout,
			error: function (request, error) {
				if (error == "timeout") {
					alert(auth_error_timeout);
				}
				else {
					alert(auth_error_default);
				}
			},
			success: function (data) {
				$('#auth-modal .uk-modal-header').html(reg_title);
				$('#auth-modal .uk-modal-content').html(data);
				$('#auth-modal .backurl').val(window.location.pathname);
			}
		});
		return false;
	});
});