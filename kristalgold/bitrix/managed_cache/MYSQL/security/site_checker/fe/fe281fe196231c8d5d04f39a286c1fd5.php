<?
if($INCLUDE_FROM_CACHE!='Y')return false;
$datecreate = '001572350126';
$dateexpire = '001574942126';
$ser_content = 'a:2:{s:7:"CONTENT";s:0:"";s:4:"VARS";a:2:{s:7:"results";a:7:{i:0;a:5:{s:5:"title";s:58:"Поиск phpinfo() завершился неудачей";s:8:"critical";s:6:"MIDDLE";s:6:"detail";s:117:"Произошла ошибка обработки запроса, тест не может быть завершен";s:14:"recommendation";s:172:"Попробуйте отключить контроль активности или повысить производительность генерации страниц";s:15:"additional_info";s:1337:"Причина: Непредвиденный код состояния HTTP<br>Адрес: <a href="http://mag.uvelirsoft.ru/test/phpInfo.php?rnd=0.176714519566" target="_blank">http://mag.uvelirsoft.ru/test/phpInfo.php?rnd=0.176714519566</a><br>Запрос/Ответ: <pre>GET /test/phpInfo.php?rnd=0.176714519566 HTTP/1.1
host: mag.uvelirsoft.ru
accept: */*
user-agent: BitrixCloud BitrixSecurityScanner/Robin-Scooter

HTTP/1.1 500 Internal Server Error
Server: nginx
Date: Mon, 21 Nov 2016 05:59:43 GMT
Content-Type: text/html; charset=iso-8859-1
Content-Length: 605
Connection: keep-alive

&lt;!DOCTYPE HTML PUBLIC &quot;-//IETF//DTD HTML 2.0//EN&quot;&gt;
&lt;html&gt;&lt;head&gt;
&lt;title&gt;500 Internal Server Error&lt;/title&gt;
&lt;/head&gt;&lt;body&gt;
&lt;h1&gt;Internal Server Error&lt;/h1&gt;
&lt;p&gt;The server encountered an internal error or
misconfiguration and was unable to complete
your request.&lt;/p&gt;
&lt;p&gt;Please contact the server administrator,
 sokd@uvelirsoft.ru and inform them of the time the error occurred,
and anything you might have done that may have
caused the error.&lt;/p&gt;
&lt;p&gt;More information about this error may be available
in the server error log.&lt;/p&gt;
&lt;hr&gt;
&lt;address&gt;Apache Server at mag.uvelirsoft.ru Port 80&lt;/address&gt;
&lt;/body&gt;&lt;/html&gt;
</pre>";}i:1;a:5:{s:5:"title";s:113:"Разрешено отображение сайта во фрейме с произвольного домена";s:8:"critical";s:6:"MIDDLE";s:6:"detail";s:307:"Запрет отображения фреймов сайта со сторонних доменов способен предотвратить целый класс атак, таких как <a href="https://www.owasp.org/index.php/Clickjacking" target="_blank">Clickjacking</a>, Framesniffing и т.д.";s:14:"recommendation";s:1875:"Скорее всего, вам будет достаточно разрешения на просмотр сайта в фреймах только на страницах текущего сайта.
Сделать это достаточно просто, достаточно добавить заголовок ответа "X-Frame-Options: SAMEORIGIN" в конфигурации вашего frontend-сервера.
</p><p>В случае использования nginx:<br>
1. Найти секцию server, отвечающую за обработку запросов нужного сайта. Зачастую это файлы в /etc/nginx/site-enabled/*.conf<br>
2. Добавить строку:
<pre>
add_header X-Frame-Options SAMEORIGIN;
</pre>
3. Перезапустить nginx<br>
Подробнее об этой директиве можно прочесть в документации к nginx: <a href="http://nginx.org/ru/docs/http/ngx_http_headers_module.html" target="_blank">Модуль ngx_http_headers_module</a>
</p><p>В случае использования Apache:<br>
1. Найти конфигурационный файл для вашего сайта, зачастую это файлы /etc/apache2/httpd.conf, /etc/apache2/vhost.d/*.conf<br>
2. Добавить строки:
<pre>
&lt;IfModule headers_module&gt;
	Header set X-Frame-Options SAMEORIGIN
&lt;/IfModule&gt;
</pre>
3. Перезапустить Apache<br>
4. Убедиться, что он корректно обрабатывается Apache и этот заголовок никто не переопределяет<br>
Подробнее об этой директиве можно прочесть в документации к Apache: <a href="http://httpd.apache.org/docs/2.2/mod/mod_headers.html" target="_blank">Apache Module mod_headers</a>
</p>";s:15:"additional_info";s:1846:"Адрес: <a href="http://mag.uvelirsoft.ru/" target="_blank">http://mag.uvelirsoft.ru/</a><br>Запрос/Ответ: <pre>GET / HTTP/1.1
host: mag.uvelirsoft.ru
accept: */*
user-agent: BitrixCloud BitrixSecurityScanner/Robin-Scooter

HTTP/1.1 200 OK
Server: nginx
Date: Mon, 21 Nov 2016 05:59:45 GMT
Content-Type: text/html; charset=UTF-8
Content-Length: 80230
Connection: keep-alive
ETag: 4f57ee0629baaa232e1f8553e9c2ed63
Expires: Fri, 07 Jun 1974 04:00:00 GMT
X-Bitrix-Composite: Cache (200)
Last-Modified: Mon, 21 Nov 2016 05:23:59 GMT
X-Powered-By: PleskLin

&lt;!DOCTYPE html&gt;
&lt;html lang=&quot;ru&quot;&gt;
&lt;head&gt;
	&lt;link rel=&quot;shortcut icon&quot; type=&quot;image/x-icon&quot; href=&quot;/local/templates/ustemplate/favicon.ico&quot; /&gt;	
	&lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1.0&quot; /&gt;
	&lt;title&gt;ЮВЕЛИРМАГ - Ювелирный интернет-магазин. - Москва, Кострома&lt;/title&gt;
	&lt;meta http-equiv=&quot;Content-Type&quot; content=&quot;text/html; charset=UTF-8&quot; /&gt;
&lt;meta name=&quot;keywords&quot; content=&quot;ЮВЕЛИРМАГ, сайт, ювелирный, интернет-магазин, купить, шаблонный, Москва, Кострома.&quot; /&gt;
&lt;meta name=&quot;description&quot; content=&quot;ЮВЕЛИРМАГ - Ювелирный интернет-магазин. - Москва, Кострома. Шаблонный сайт для продажи, разработанный компанией ЮвелирСофт.&quot; /&gt;
&lt;script type=&quot;text/javascript&quot; data-skip-moving=&quot;true&quot;&gt;(function(w, d) {var v = w.frameCacheVars = {\'CACHE_MODE\':\'HTMLCACHE\',\'storageBlocks\':[],\'dynamicBlocks\':{\'iYzNr3\':{
----------Only 1Kb of body shown----------<pre>";}i:2;a:5:{s:5:"title";s:68:"Разрешено чтение файлов по URL (URL wrappers)";s:8:"critical";s:6:"MIDDLE";s:6:"detail";s:256:"Если эта, сомнительная, возможность PHP не требуется - рекомендуется отключить, т.к. она может стать отправной точкой для различного типа атак";s:14:"recommendation";s:89:"Необходимо в настройках php указать:<br>allow_url_fopen = Off";s:15:"additional_info";s:0:"";}i:3;a:5:{s:5:"title";s:119:"Временные файлы хранятся в пределах корневой директории проекта";s:8:"critical";s:6:"MIDDLE";s:6:"detail";s:271:"Хранение временных файлов, создаваемых при использовании CTempFile, в пределах корневой директории проекта не рекомендовано и несет с собой ряд рисков.";s:14:"recommendation";s:884:"Необходимо определить константу "BX_TEMPORARY_FILES_DIRECTORY" в "bitrix/php_interface/dbconn.php" с указанием необходимого пути.<br>
Выполните следующие шаги:<br>
1. Выберите директорию вне корня проекта. Например, это может быть "/home/bitrix/tmp/www"<br>
2. Создайте ее. Для этого выполните следующую комманду:
<pre>
mkdir -p -m 700 /полный/путь/к/директории
</pre>
3. В файле "bitrix/php_interface/dbconn.php" определите соответствующую константу, что бы система начала использовать эту директорию:
<pre>
define("BX_TEMPORARY_FILES_DIRECTORY", "/полный/путь/к/директории");
</pre>";s:15:"additional_info";s:81:"Текущая директория: /var/www/vhosts/uvelirsoft.ru/mag/upload/tmp";}i:4;a:5:{s:5:"title";s:44:"Включен Automatic MIME Type Detection";s:8:"critical";s:3:"LOW";s:6:"detail";s:248:"По умолчанию в Internet Explorer/FlashPlayer включен автоматический mime-сниффинг, что может служить источником XSS нападения или раскрытия информации.";s:14:"recommendation";s:1752:"Скорее всего, вам не нужна эта функция, поэтому её можно безболезненно отключить, добавив заголовок ответа "X-Content-Type-Options: nosniff" в конфигурации вашего веб-сервера.
</p><p>В случае использования nginx:<br>
1. Найти секцию server, отвечающую за обработку запросов нужного сайта. Зачастую это файлы в /etc/nginx/site-enabled/*.conf<br>
2. Добавить строку:
<pre>
add_header X-Content-Type-Options nosniff;
</pre>
3. Перезапустить nginx<br>
Подробнее об этой директиве можно прочесть в документации к nginx: <a href="http://nginx.org/ru/docs/http/ngx_http_headers_module.html" target="_blank">Модуль ngx_http_headers_module</a>
</p><p>В случае использования Apache:<br>
1. Найти конфигурационный файл для вашего сайта, зачастую это файлы /etc/apache2/httpd.conf, /etc/apache2/vhost.d/*.conf<br>
2. Добавить строки:
<pre>
&lt;IfModule headers_module&gt;
	Header set X-Content-Type-Options nosniff
&lt;/IfModule&gt;
</pre>
3. Перезапустить Apache<br>
4. Убедиться, что он корректно обрабатывается Apache и этот заголовок никто не переопределяет<br>
Подробнее об этой директиве можно прочесть в документации к Apache: <a href="http://httpd.apache.org/docs/2.2/mod/mod_headers.html" target="_blank">Apache Module mod_headers</a>
</p>";s:15:"additional_info";s:1818:"Адрес: <a href="http://mag.uvelirsoft.ru/bitrix/js/main/core/core.js?rnd=0.667786860087" target="_blank">http://mag.uvelirsoft.ru/bitrix/js/main/core/core.js?rnd=0.667786860087</a><br>Запрос/Ответ: <pre>GET /bitrix/js/main/core/core.js?rnd=0.667786860087 HTTP/1.1
host: mag.uvelirsoft.ru
accept: */*
user-agent: BitrixCloud BitrixSecurityScanner/Robin-Scooter

HTTP/1.1 200 OK
Server: nginx
Date: Mon, 21 Nov 2016 05:59:39 GMT
Content-Type: application/javascript
Content-Length: 117883
Last-Modified: Thu, 17 Nov 2016 12:28:30 GMT
Connection: keep-alive
ETag: &quot;582da26e-1cc7b&quot;
Expires: Mon, 28 Nov 2016 05:59:39 GMT
Cache-Control: max-age=604800
Accept-Ranges: bytes

/**********************************************************************/
/*********** Bitrix JS Core library ver 0.9.0 beta ********************/
/**********************************************************************/

;(function(window){

if (!!window.BX &amp;&amp; !!window.BX.extend)
	return;

var _bxtmp;
if (!!window.BX)
{
	_bxtmp = window.BX;
}

window.BX = function(node, bCache)
{
	if (BX.type.isNotEmptyString(node))
	{
		var ob;

		if (!!bCache &amp;&amp; null != NODECACHE[node])
			ob = NODECACHE[node];
		ob = ob || document.getElementById(node);
		if (!!bCache)
			NODECACHE[node] = ob;

		return ob;
	}
	else if (BX.type.isDomNode(node))
		return node;
	else if (BX.type.isFunction(node))
		return BX.ready(node);

	return null;
};

BX.debugEnableFlag = true;

// language utility
BX.message = function(mess)
{
	if (BX.type.isString(mess))
	{
		if (typeof BX.message[mess] == &quot;undefined&quot;)
		{
			BX.onCustomEvent(&quot;onBXMessageNotFound&quot;, [mess]);
			if (typeof BX.message[mess] == &quot;undefined&quot;)
			{
				BX.debug(&quot;message undef
----------Only 1Kb of body shown----------<pre>";}i:5;a:5:{s:5:"title";s:38:"Включен вывод ошибок";s:8:"critical";s:3:"LOW";s:6:"detail";s:202:"Вывод ошибок предназначен для разработки и тестовых стендов, он не должен использоваться на конечном ресурсе.";s:14:"recommendation";s:88:"Необходимо в настройках php указать:<br>display_errors = Off";s:15:"additional_info";s:0:"";}i:6;a:5:{s:5:"title";s:77:"Почтовые сообщения содержат UID PHP процесса";s:8:"critical";s:3:"LOW";s:6:"detail";s:356:"В каждом отправляемом письме добавляется заголовок X-PHP-Originating-Script, который содержит UID и имя скрипта отправляющего письмо. Это позволяет злоумышленнику узнать от какого пользователя работает PHP.";s:14:"recommendation";s:91:"Необходимо в настройках php указать:<br>mail.add_x_header = Off";s:15:"additional_info";s:0:"";}}s:9:"test_date";s:10:"21.11.2016";}}';
return true;
?>