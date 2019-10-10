<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<div class="error">
	<h1 style="font-size: 200px; display:none;">404</h1>
	<img src='<?=SITE_TEMPLATE_PATH?>/images/404.jpg' alt='404'>
	<p class="error-text">Данная страница не найдена! (ошибка 404)</p>
	<p>Сожалеем, но такой страницы нет.</p>
	<div>
		<p>Почему?</p>
		<p>Страница была удалена с сервера.</p>
		<p>Страница врменно недоступна.</p>
		<p>Возможно, Вы неправильно ввели адрес в адресной строке браузера.</p>
	</div>
	<div>
		<p>Что возможно сделать?</p>
		<p>Вы можете посетить другие разделы ресурса, воспользовавшись основным меню сайта или перейти <a href="/" title="На главную">На главную</a> страницу сайта.</p>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>