<?php
if (isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['SERVER_NAME'] && !empty($_GET['v'])) {
	$ch = curl_init('https://api-piped.mha.fi/streams/'.$_GET['v']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)');
	$result = curl_exec($ch);
	curl_close($ch);
	$json = json_decode($result, true);
	//$json = json_decode(file_get_contents('https://api-piped.mha.fi/streams/'.$_GET['v']), true);
	echo '<source src="'.$json['audioStreams'][4]['url'].'" type="audio/webm"/><source src="'.$json['audioStreams'][1]['url'].'" type="audio/mp4"/>';
}
