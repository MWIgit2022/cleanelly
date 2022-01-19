$(document).ready(function(){
	$("form.subscribe-form").validate({
		rules:{ "EMAIL": {email: true} }
	});
})

function reCaptcha(selector) {
	var $wg = $(selector); // Обращаемся к селектору (описан ниже в "вызове", в нашем случае это form-captcha)
	$wg.each(function() { // Делаем проход по этому селектору
		$form = $('form.subscribe-form'); // Проходим по всей форме
		$form.find('input[type="submit"]').prop('disabled', true);// Изначально даем кнопке disabled (нельзя нажать)
		grecaptcha.render('recaptcha', { // Используем функции самой рекапчи
			sitekey: $(this).find('.g-recaptcha').data('sitekey') || '', // Находим элемент с нашим дата-атрибутом
			callback: function(response) {
				var check = $form.find('input[name="checked"]').val();
				if (!!response && !!!check) { 
					$form.find('input[name="recaptcha"]').val('');
					$form.find('input[type="submit"]').prop('disabled', false); // Если условие (нажатие галочки) выполнено, убираем disabled у кнопки
				}
			}
		});
	});
}; 
//Вызов  
var reCaptchaOnLoadCallback = function() {
	reCaptcha('form.subscribe-form');
};		