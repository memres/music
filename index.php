<?php
/////////////////////////////////////////////////////////////////////////////////////////////
//				GET YOUR API KEYS FIRST
/////////////////////////////////////////////////////////////////////////////////////////////
// YouTube Data API ► https://console.developers.google.com/
$key_youtube = '';
// Last.fm API ► https://www.last.fm/api/
$key_lastfm = '';
/////////////////////////////////////////////////////////////////////////////////////////////
//					HAVE FUN!
/////////////////////////////////////////////////////////////////////////////////////////////
$related = isset($_GET['r']) ? $_GET['r'] : '';
while ($related) {
	$tokens = array('CAEQAA', 'CAIQAA', 'CAMQAA', 'CAQQAA', 'CAUQAA', 'CAYQAA', 'CAcQAA', 'CAgQAA', 'CAkQAA', 'CAoQAA', 'CAsQAA', 'CAwQAA', 'CA0QAA', 'CA4QAA', 'CA8QAA', 'CBAQAA', 'CBEQAA', 'CBIQAA', 'CBMQAA', 'CBQQAA', 'CBUQAA', 'CBYQAA', 'CBcQAA', 'CBgQAA', 'CBkQAA', 'CBoQAA', 'CBsQAA', 'CBwQAA', 'CB0QAA', 'CB4QAA', 'CB8QAA', 'CCAQAA', 'CCEQAA', 'CCIQAA', 'CCMQAA', 'CCQQAA', 'CCUQAA', 'CCYQAA', 'CCcQAA', 'CCgQAA', 'CCkQAA');
	$api = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&videoCategoryId=10&maxResults=2&prettyPrint=false&fields=items(id(videoId),snippet(title))&key=$key_youtube&relatedToVideoId=$related&pageToken=".$tokens[mt_rand(0, 40)];
	$json = json_decode(@file_get_contents($api), true);
	if (isset($json['items'][0])) echo json_encode(array('track' => $json['items'][0]['id']['videoId'], 'title' => $json['items'][0]['snippet']['title']));
	exit;
}
$id = '';
$query = isset($_GET['q']) ? $_GET['q'] : '';
if ($query) {
	$api = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&videoCategoryId=10&maxResults=1&prettyPrint=false&fields=items(id(videoId),snippet(title))&key=$key_youtube&q=".urlencode($query);
	$json = json_decode(@file_get_contents($api), true);
	if (isset($json['items'][0])) {
		$id = $json['items'][0]['id']['videoId'];
		$title = $json['items'][0]['snippet']['title'];
	}
}
else {
	$ip = json_decode(@file_get_contents('https://www.iplocate.io/api/lookup/'.$_SERVER['REMOTE_ADDR']), true);
	$country = isset($ip['country']) ? $ip['country'] : 'United Kingdom';
	$api = "http://ws.audioscrobbler.com/2.0/?method=geo.getTopArtists&api_key=$key_lastfm &format=json&limit=24&page=".mt_rand(1, 24).'&country='.urlencode($country);
	$featured = "			<h3 data-country=\"$country\">Top Artists</h3>\n";
	$json = json_decode(@file_get_contents($api), true);
	foreach ($json['topartists']['artist'] as $item) $artists[] = '			<a href="?q='.urlencode($item['name']).'">'.$item['name']."</a>\n";
	shuffle($artists);
	$featured .= implode($artists)."			<h3>Hit Tracks</h3>\n";
	$api = "http://ws.audioscrobbler.com/2.0/?method=geo.getTopTracks&api_key=$key_lastfm&format=json&limit=20&page=".mt_rand(1, 28).'&country='.urlencode($country);
	$json = json_decode(@file_get_contents($api), true);
	foreach ($json['tracks']['track'] as $item) $tracks[] = '			<a href="?q='.urlencode($item['artist']['name'].' '.$item['name']).'">'.$item['name']."</a>\n";
	shuffle($tracks);
	$featured .= implode($tracks)."			<h3>Featured Tags</h3>\n";
	$arr = array('Alternative', 'Ambient', 'Audio', 'Audiobook', 'Chillout', 'Classical', 'Concert', 'Country', 'Electronic', 'Full Album', 'Funk', 'Hip-Hop', 'Deep House', 'Indie', 'Instrumental', 'Jazz', 'Lyrics', 'Metal', 'New Wave', 'Official', 'Podcast', 'Pop', 'Psychedelic', 'Punk', 'Rock', 'Sleep', 'Soundtrack', 'Study', 'Trance', 'Video');
	foreach ($arr as $val) $tags[] = '			<a href="?q='.urlencode($val).'">'.$val."</a>\n";
	shuffle($tags);
	$featured .= implode($tags)."		</main>\n";
}
?>
<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="description" content="Listen to anything random."/>
		<meta name="author" content="hello@emresanli.com"/>
		<title><?php if ($id) echo $title; else echo 'Music &#9835;'; ?></title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext"/>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/memres/music@1.0/style.min.css"/>

	</head>
	<body <?php if (!$id) { $bg = array('FkWHg3lvZXw', 'wPzYJu1j1bI', '_zSXFUcQ7qA', 'gmU9PBDS-0k', 'OeHLHNKQCXA', 'fNh2yB0w8gU', 'hlWiI4xVXKY', 'BaMBQSsdnxo', '58GQx4xEdlY', 'CVvdAWp37jw', '6MztEmTkvqo', 'xwQ5YpcCDHk'); echo 'style="background-image: url(https://i.ytimg.com/vi/'.$bg[array_rand($bg)].'/maxresdefault.jpg);"'; } ?>>
		<main>
			<form>
				<button type="button" onclick="location.href='<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>';"><i class="fas fa-home"></i></button>
				<input type="search" name="q" value="<?= htmlspecialchars($query, ENT_QUOTES); ?>"/>
				<button type="submit"><i class="fas fa-search"></i></button>
			</form>
<?php if ($id) { ?>
			<h3><?= $title; ?></h3>
			<a><i class="fas fa-2x fa-play"></i></a>
			<a><i class="fas fa-2x fa-undo-alt"></i></a>
			<a><i class="fas fa-2x fa-forward"></i></a>
			<h5>Press <b>space</b> to play/pause, and <b>enter</b> for next track.</h5>
			<audio controls data-track="<?= $id; ?>"></audio>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.0/dist/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/gh/memres/music@1.0/script.min.js"></script>
		<script>
		</script>
<?php } else echo $featured; ?>
	</body>
</html>
