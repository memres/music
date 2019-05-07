<?php
$related = isset($_GET['r']) ? $_GET['r'] : '';
while ($related) {
	$tokens = array('CAEQAA', 'CAIQAA', 'CAMQAA', 'CAQQAA', 'CAUQAA', 'CAYQAA', 'CAcQAA', 'CAgQAA', 'CAkQAA', 'CAoQAA', 'CAsQAA', 'CAwQAA', 'CA0QAA', 'CA4QAA', 'CA8QAA', 'CBAQAA', 'CBEQAA', 'CBIQAA', 'CBMQAA', 'CBQQAA', 'CBUQAA', 'CBYQAA', 'CBcQAA', 'CBgQAA', 'CBkQAA', 'CBoQAA', 'CBsQAA', 'CBwQAA', 'CB0QAA', 'CB4QAA', 'CB8QAA', 'CCAQAA', 'CCEQAA', 'CCIQAA', 'CCMQAA', 'CCQQAA', 'CCUQAA', 'CCYQAA', 'CCcQAA', 'CCgQAA', 'CCkQAA');
	$api = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&videoCategoryId=10&maxResults=2&prettyPrint=false&fields=items(id(videoId),snippet(title))&key=&relatedToVideoId=$related&pageToken=".$tokens[mt_rand(0, 40)];
	$json = json_decode(@file_get_contents($api), true);
	if (isset($json['items'][0])) echo json_encode(array('id' => $json['items'][0]['id']['videoId'], 'title' => $json['items'][0]['snippet']['title']));
	exit;
}
$id = '';
$query = isset($_GET['q']) ? $_GET['q'] : '';
if ($query) {
	$api = 'https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&videoCategoryId=10&maxResults=1&prettyPrint=false&fields=items(id(videoId),snippet(title))&key=&q='.urlencode($query);
	$json = json_decode(@file_get_contents($api), true);
	if (isset($json['items'][0])) {
		$id = $json['items'][0]['id']['videoId'];
		$title = $json['items'][0]['snippet']['title'];
	}
}
else {
	$ip = json_decode(@file_get_contents('https://www.iplocate.io/api/lookup/'.$_SERVER['REMOTE_ADDR']), true);
	$country = isset($ip['country']) ? $ip['country'] : 'United Kingdom';
	$api = 'http://ws.audioscrobbler.com/2.0/?method=geo.getTopArtists&api_key=&format=json&limit=24&page='.mt_rand(1, 24).'&country='.urlencode($country);
	$featured = "			<h3 data-country=\"$country\">Top Artists</h3>\n";
	$json = json_decode(@file_get_contents($api), true);
	foreach ($json['topartists']['artist'] as $item) $artists[] = '			<a href="?q='.urlencode($item['name']).'">'.$item['name']."</a>\n";
	shuffle($artists);
	$featured .= implode($artists)."			<h3>Hit Tracks</h3>\n";
	$api = 'http://ws.audioscrobbler.com/2.0/?method=geo.getTopTracks&api_key=&format=json&limit=20&page='.mt_rand(1, 28).'&country='.urlencode($country);
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
		<meta name="description" content="This is the 'single track' version. Since no data is stored in any array, there is not a playlist or a 'previous track' button. Contains the native HTML5 audio player of browser. As soon as current track ends and/or user jumps to next track, script makes an AJAX request for the next random one related to the current track."/>
		<meta name="author" content="music@emresanli.com"/>
		<title><?php if ($id) echo htmlspecialchars($title); else echo 'Music &#9835;'; ?></title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext"/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.8.1/css/all.min.css"/>
		<style>
			::selection {
				color: #ff8a00;
			}
			body {
				margin: 0;
				display: grid;
				height: 100vh;
				place-items: center center;
				background-color: #fff;
				background-size: cover;
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-position: center center;
			}
			main {
				width: 75%;
				margin: 30px 0 100px;
				padding: 30px;
				cursor: default;
				text-align: center;
				font: 400 16px/1.4 'Open Sans', sans-serif;
				color: #fff;
				border-radius: 30px;
				box-shadow: 2px 2px 10px #246756;
				background-image: radial-gradient(farthest-side at bottom left, rgba(255, 0, 255, 0.5), #246756), radial-gradient(farthest-corner at bottom right, rgba(255, 50, 50, 0.5), #246756 400px);
				opacity: 0.95;
			}
			form:after {
				content: '';
				display: table;
				clear: both;
			}
			input, button {
				display: inline-block;
				float: left;
				border: 0;
				outline: 0;
				margin: 0;
				color: #fff;
				font-size: 18px;
				line-height: 26px;
			}
			input {
				width: 70%;
				text-align: center;
				padding: 14px;
				background-color: #201c29;
				font-family: 'Open Sans', sans-serif;
			}
			button {
				width: 15%;
				padding: 14px 0;
				cursor: pointer;
				text-shadow: 2px 2px 4px #201c29;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			button[type=button] {
				border-top-left-radius: 30px;
				border-bottom-left-radius: 30px;
			}
			button[type=submit] {
				border-top-right-radius: 30px;
				border-bottom-right-radius: 30px;
			}
			h3, h5 {
				margin: 16px 0;
				letter-spacing: 1px;
				text-shadow: 2px 2px 4px #201c29;
			}
			h5 {
				font-weight: normal;
			}
			b {
				color: #ff8a00;
				text-transform: uppercase;
			}
			a {
				display: inline-block;
				text-decoration: none;
				outline: 0;
				margin: 4px 2px;
				padding: 11px;
				color: #fff;
				cursor: pointer;
				border-radius: 10px;
				text-shadow: 2px 2px 4px #201c29;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			a .fas {
				width: 48px;
				margin: 0 4px;
			}
			a:hover, button:hover {
				background-image: linear-gradient(to right, #da1b60, #ff8a00);
			}
			.fa-spin {
				pointer-events: none;
			}
			audio {
				outline: 0;
				width: 100%;
			}
			footer {
				position: fixed;
				bottom: 0;
				left: 50%;
			}
			.adsbygoogle {
				display: inline-block;
				position: relative;
				left: -50%;
				width: 320px;
				height: 100px;
			}
			@media(min-width: 468px) {
				.adsbygoogle {
					width: 468px;
					height: 60px;
				}
			}
			@media(min-width: 728px) {
				.adsbygoogle {
					width: 728px;
					height: 90px;
				}
			}
			@media(min-width: 970px) {
				.adsbygoogle {
					width: 970px;
					height: 90px;
				}
			}
		</style>
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
			<audio controls id="<?= $id; ?>"></audio>
		</main>
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.0/dist/jquery.min.js"></script>
		<script>
			$(function(){
				var audio = $('audio')[0], track = $('audio').attr('id');
				play(track);
				function next() {
					$.ajax({
						data: 'r=' + track,
						dataType: 'json',
						beforeSend: function() {
							$('body').css('background-image', 'url(https://cdn.dribbble.com/users/563824/screenshots/3633228/untitled-5.gif)');
							$('.fa-forward').removeClass('fa-forward').addClass('fa-spinner fa-spin');
						},
						success: function(data) {
							$('audio').attr('id', data.id);
							$('h3').text(data.title);
							$(document).attr('title', data.title);
							play(data.id);
							$('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-forward');
						}
					});
				}
				function play(id) {
					//https://invidio.us/latest_version?itag=251&local=true&id=
					$('audio').attr('src', 'get.php?v=' + id);
					audio.play();
					bg(id);
				}
				function bg(id) {
					var hd = 'https://i.ytimg.com/vi/' + id + '/maxresdefault.jpg',
						hq = 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
					$.ajax({
						url: 'https://images' + ~~(Math.random() * 33) + '-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url=' + encodeURIComponent(hd),
						type: 'HEAD',
						success: function() {
							$('body').css('background-image', 'url(' + hd + ')');
						},
						error: function() {
							$('body').css('background-image', 'url(' + hq + ')');
						}
					});
					if (!audio.playing) audio.play();
				}
				$('audio').on('ended', function() {
					if ($('.fa-history').length) {
						audio.play();
						$('.fa-history').removeClass('fa-history').addClass('fa-sync-alt fa-spin');
						setTimeout(function(){$('.fa-sync-alt').removeClass('fa-sync-alt fa-spin').addClass('fa-history');}, 500);
					}
					else next();
				});
				$('audio').on('play', function() {
					$('.fa-play').removeClass('fa-play').addClass('fa-pause');
				});
				$('audio').on('pause', function() {
					$('.fa-pause').removeClass('fa-pause').addClass('fa-play');
				});
				$(document).on('click', '.fa-pause', function() {
					$(this).removeClass('fa-pause').addClass('fa-play');
					audio.pause();
				});
				$(document).on('click', '.fa-play', function() {
					$(this).removeClass('fa-play').addClass('fa-pause');
					audio.play();
				});
				$(document).on('click', '.fa-undo-alt', function() {
					$(this).removeClass('fa-undo-alt').addClass('fa-history');
				});
				$(document).on('click', '.fa-history', function() {
					$(this).removeClass('fa-history').addClass('fa-undo-alt');
				});
				$(document).on('click', '.fa-forward', function() {
					next();
				});
				$(document).on('keyup', function(event) {
					if (event.target.tagName != 'INPUT') {
						if (event.which == 32) {
							if (!audio.paused) audio.pause();
							else audio.play();
						}
						if (event.which == 13) next();
					}
				});
				if (/Mobi|Android/i.test(navigator.userAgent)) {
					$('h5').html('<b>Shake</b> your device for next track.');
					var script = document.createElement('script');
					script.src = 'https://cdn.jsdelivr.net/npm/shake.js@1.2.2/shake.min.js';
					document.body.appendChild(script);
					script.onload = function() {
						var shakeEvent = new Shake({threshold: 17});
						shakeEvent.start();
						window.addEventListener('shake', function() {
							next();
						}, false);
					}
				}
			});
		</script>
<?php } else echo $featured; ?>
		<footer>
			<ins class="adsbygoogle" data-ad-client="ca-pub-0656015397250476" data-ad-slot="1441806093"></ins>
		</footer>
		<script>var sc_invisible = 1, sc_project = 5408945, sc_security = 'f75ba4c3';window.ga = window.ga || function(){(ga.q = ga.q || []).push(arguments)};ga.l = +new Date;ga('create', 'UA-28085788-1', 'auto');ga('send', 'pageview');(adsbygoogle = window.adsbygoogle || []).push({});</script>
		<script src="https://statcounter.com/counter/counter.js"></script>
		<script src="https://www.google-analytics.com/analytics.js" async></script>
		<script src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" async></script>
	</body>
</html>
