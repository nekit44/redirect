<?php
//header( 'Refresh: 0; url=/test.php' );
?>
<?php
//Ваша ссылка, на которую осуществляется редирект
$target_link = 'http://redirect.it/test.php';

if(!isset($_SERVER['HTTP_REFERER']))
{
    //Если нам никто реферер не передал, то достаточно
    //просто совершить 301 редирект - браузер при этом реферера навешивать
    //не будет

    //Неплохо бы еще проверить, если у нас редирект с HTTPS на HTTP, то также
    //осуществляем 301 редирект через header(), так как браузер
    //в таком случае не передаст реферер, но эту простую задачку
    //я оставлю вам.
    header('Location: ' . $target_link, true, 301);
    exit();
}

//А иначе всё не так просто
$target_link = htmlspecialchars($target_link);

//Будем делать редирект через тег <meta>.
//Это срабатывает в Firefox и IE, однако, в других браузерах не работает.

//В то же время, браузеры на движке WebKit
//(Safari, Chrome, новая Opera) поддерживают
//атрибут rel="noreferrer" у тегов <a>
$is_webkit = isset($_SERVER['HTTP_USER_AGENT'])
    && strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'webkit') !== false;
if($is_webkit)
{
    //Ставим таймаут редиректа через тег <meta> в 3 секунды
    //(на тот случай, если в браузере выключен JavaScript).
    //Это, конечно, не удалит реферера, зато заменит его
    //на URL этого скрипта и переадресует пользователя
    //на целевую страницу.
    $meta_timeout = 3;
    //Пробуем осуществить редирект с помощью JavaScript.
    //(Это очищает Referer).
    $onload = 'onload="redirect();"';
}
else
{
    //Firefox и IE и так не шлют реферер при редиректе через
    //тег <meta>, поэтому нам не нужны извращения через JavaScript.
    $meta_timeout = 0;
    $onload = '';
}

    echo '<!doctype html>';
    echo '<html>	<head> 		<meta http-equiv="X-UA-Compatible" content="IE=edge" />';
    echo '<meta http-equiv="cache-control" content="max-age=0" />';
    echo '<meta http-equiv="cache-control" content="no-cache" />';
    echo '<meta http-equiv="expires" content="0" />';
    echo '<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />';
    echo '<meta http-equiv="pragma" content="no-cache" />';
	echo '<meta http-equiv="refresh" content="' . $meta_timeout . 'url='.$target_link.'">"';
    echo '	<title></title>';
    echo '		<script type="text/javascript">';
    echo '		<!--';
    echo '		var redirect = function()';
    echo '		{';
    echo '			document.getElementById("link").click();';
    echo '		}';
    echo '		-->';
    echo '		</script>';
    echo '	</head>';
    echo '	<body' .$onload.'>';
    echo '		<a href="$target_link" rel="noreferrer" id="link"></a>';
    echo '	</body>';
    echo '</html>';
