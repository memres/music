<?php
$playlist = '';
$query = isset($_GET['q']) ? $_GET['q'] : '';
if ($query) {
	$yt = 'https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&videoCategoryId=10&prettyPrint=false&fields=items(id(videoId),snippet(title))&key=&maxResults';
	$api = "$yt=2&q=".urlencode($query);
	$json = json_decode(@file_get_contents($api), true);
	if (isset($json['items'][0])) {
		$rid1 = $json['items'][0]['id']['videoId'];
		$rid2 = $json['items'][1]['id']['videoId'];
		$first = '<li id="'.$rid1.'">'.titlize($json['items'][0]['snippet']['title']).'</li>';
		$second = '<li id="'.$rid2.'">'.titlize($json['items'][1]['snippet']['title']).'</li>';
		$api = "$yt=20&relatedToVideoId=$rid1";
		$json = json_decode(@file_get_contents($api), true);
		foreach ($json['items'] as $item) $ids[] = '<li id="'.$item['id']['videoId'].'">'.titlize($item['snippet']['title']).'</li>';
		$api = "$yt=50&relatedToVideoId=$rid2";
		$json = json_decode(@file_get_contents($api), true);
		foreach ($json['items'] as $item) $ids[] = '<li id="'.$item['id']['videoId'].'">'.titlize($item['snippet']['title']).'</li>';
		shuffle($ids);
		array_unshift($ids, $first, $second);
		$playlist = implode("\n				", array_unique($ids))."\n";
	}
}
else {
	$ip = json_decode(@file_get_contents('https://www.iplocate.io/api/lookup/'.$_SERVER['REMOTE_ADDR']), true);
	$country = isset($ip['country']) ? $ip['country'] : 'United Kingdom';
	$api = 'http://ws.audioscrobbler.com/2.0/?method=geo.getTopArtists&api_key=&format=json&limit=50&page='.mt_rand(1, 20).'&country='.urlencode($country);
	$featured = "			<h3 data-country=\"$country\">Random Artists</h3>\n";
	$json = json_decode(@file_get_contents($api), true);
	foreach ($json['topartists']['artist'] as $item) $artists[] = '			<a href="?q='.urlencode($item['name']).'">'.$item['name']."</a>\n";
	shuffle($artists);
	$featured .= implode($artists)."			<h3>Featured Tags</h3>\n";
	foreach (array('Alternative', 'Ambient', 'Audio', 'Audiobook', 'Chillout', 'Classical', 'Concert', 'Country', 'Electronic', 'Full Album', 'Funk', 'Hip-Hop', 'Deep House', 'Indie', 'Instrumental', 'Jazz', 'Lyrics', 'Metal', 'New Wave', 'Official', 'Podcast', 'Pop', 'Psychedelic', 'Punk', 'Rock', 'Sleep', 'Soundtrack', 'Study', 'Trance', 'Video') as $val) $tags[] = '			<a href="?q='.urlencode($val).'">'.$val."</a>\n";
	shuffle($tags);
	$featured .= implode($tags)."		</main>\n";
}
function titlize($val) {
	return trim(str_ireplace(array('official', 'music', 'video', 'lyrics', 'lyric', 'live', 'audio only', 'audio', '[audio only]', 'vevo presents', 'vevo', 'şarkı sözleri', '()', '( )', '(  )', '(   )', '[]', '[ ]', '[  ]', '[   ]'), '', $val));
}
?>
<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		<meta name="description" content="Listen to ad-free limitless music. Developed especially for mobile devices."/>
		<meta name="author" content="music@emresanli.com"/>
		<title><?php if ($playlist) echo $query; else echo 'Music &#9835;'; ?></title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext"/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.8.1/css/all.min.css"/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/memres/music/style.min.css"/>
	</head>
	<body <?php if (!$playlist) {
	$bg = array('FkWHg3lvZXw', 'wPzYJu1j1bI', 'BaMBQSsdnxo', '6MztEmTkvqo', 'xwQ5YpcCDHk'); echo 'style="background-image: url(https://i.ytimg.com/vi/'.$bg[array_rand($bg)].'/maxresdefault.jpg);"'; } ?>>
		<main>
			<form>
				<button type="button" onclick="location.href='<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>';"><i class="fas fa-home"></i></button>
				<input type="search" name="q" value="<?= htmlspecialchars($query, ENT_QUOTES); ?>"/>
				<button type="submit"><i class="fas fa-search"></i></button>
			</form>
<?php if ($playlist) { ?>
			<h3><?= $query; ?></h3>
			<nav>
				<i class="fas fa-step-backward"></i>
				<i class="fas fa-play"></i>
				<i class="fas fa-step-forward"></i>
			</nav>
			<h5>Press <b>space</b> to play/pause, <b>enter</b> for next track <i class="fas fa-question-circle"></i><span><b>B</b> for previous track, <b>P</b> for playlist, <b>L</b> to loop, <b>R</b> to random, <b>&#x2B05;</b> to rewind, <b>&#x27A1;</b> to fast forward, <b>H</b> for these hints.</span></h5>
			<ul>
				<?= $playlist; ?>
			</ul>
			<figure>
				<figcaption class="buffer"></figcaption>
				<figcaption class="seek"></figcaption>
			</figure>
			<aside>
				<time class="current"></time>
				<i class="fas fa-undo-alt"></i>
				<i class="fas fa-bars"></i>
				<i class="fas fa-random"></i>
				<time class="total"></time>
			</aside>
			<audio></audio>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.0/dist/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/gh/memres/music/script.min.js"></script>
<?php } else echo $featured; ?>
		<script>var sc_invisible = 1, sc_project = 5408945, sc_security = 'f75ba4c3';window.ga = window.ga || function(){(ga.q = ga.q || []).push(arguments)};ga.l = +new Date;ga('create', 'UA-28085788-1', 'auto');ga('send', 'pageview');</script>
		<script src="https://statcounter.com/counter/counter.js"></script>
		<script src="https://www.google-analytics.com/analytics.js" async></script>
	</body>
</html>
