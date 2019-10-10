<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?><p>
</p>
<div class="row order">
	<div class="col-md-12">
		<div class="profile">
			 Персональный раздел
		</div>
	</div>
</div>
 <br>
<p>
</p>
<p>
 <span style="font-size: 12pt;">В личном кабинете Вы можете проверить текущее состояние корзины, ход выполнения Ваших заказов, просмотреть или изменить личную информацию, а также подписаться на новости и другие информационные рассылки.</span>
</p>
<h3>Личная информация </h3>
<ul>
	<li><a href="/magazin/personal/profile/">Изменить регистрационные данные</a></li>
</ul>
<h3>Заказы</h3>
<ul>
	<li><a href="/magazin/personal/order/">Ознакомиться с состоянием заказов</a></li>
	<li><a href="/magazin/personal/cart/">Посмотреть содержимое корзины</a></li>
	<li><a href="/magazin/personal/cart/?delay=yes/">Посмотреть отложенные товары</a></li>
	<li><a href="/magazin/personal/order/?filter_history=Y">Посмотреть историю заказов</a></li>
</ul><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>