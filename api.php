<?php
if (isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['SERVER_NAME'] && !empty($_GET['v'])) {
	$ch = curl_init('https://dev.ytapi.com/w/'.$_GET['v']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)');
	$htm = curl_exec($ch);
	curl_close($ch);
	$dom = @DOMDocument::loadHTML($htm);
	if ($dom->getElementsByTagName('a')->item(7)) echo '<source src="'.scheme($dom->getElementsByTagName('a')->item(7)->attributes->getNamedItem('href')->value).'" type="audio/webm"/><source src="'.scheme($dom->getElementsByTagName('a')->item(8)->attributes->getNamedItem('href')->value).'" type="audio/mp4"/>';
}
function scheme($s) {
	return 'https:'.str_replace('https:', '', $s);
}
