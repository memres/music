<?php
if (!empty($_GET['v'])) {
	$ch = curl_init('https://dev.ytapi.com/w/'.$_GET['v']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)');
	$htm = curl_exec($ch);
	curl_close($ch);
	$dom = @DOMDocument::loadHTML($htm);
	if ($dom->getElementsByTagName('a')->item(5)) echo '<source src="'.scheme($dom->getElementsByTagName('a')->item(5)->attributes->getNamedItem('href')->value).'" type="audio/mp4"/><source src="'.scheme($dom->getElementsByTagName('a')->item(4)->attributes->getNamedItem('href')->value).'" type="audio/webm"/>';
	else echo '<source src="https://invidious.snopyta.org/latest_version?local=true&itag=251&id='.$_GET['v'].'" type="audio/webm"/>';
}
function scheme($s) {
	return 'https:'.str_replace('https:', '', $s);
}
