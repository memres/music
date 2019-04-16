<?php
/* This application needs some API keys which you can obtain for free to work properly. */
/* YouTube Data API key: https://console.developers.google.com/apis/api/youtube.googleapis.com/ */
$key_youtube = '';
/* Last.fm API key: https://www.last.fm/api/account/create */
$key_lastfm = '';
/* Thanks and have fun! */
$id = '';
$query = isset($_GET['q']) ? $_GET['q'] : '';
$token = isset($_GET['t']) ? $_GET['t'] : '';
if ($query) {
	$api = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=1&videoSyndicated=true&prettyPrint=false&fields=nextPageToken,items(id(videoId),snippet(title))&
	=$key_youtube&q=".urlencode($query);
	if ($token) $api .= "&pageToken=$token";
	if (strtolower($query) != 'rammstein') $api .= '&videoCategoryId=10';
	$opts = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false));
	$json = json_decode(@file_get_contents($api, false, stream_context_create($opts)), true);
	if (isset($json['items'][0])) {
		$id = $json['items'][0]['id']['videoId'];
		$title = trim(str_ireplace(array('official', 'video', 'audio', 'music', 'lyrics', 'lyric', '(', ')', '[', ']'), '', $json['items'][0]['snippet']['title']));
		$page = isset($json['nextPageToken']) ? $json['nextPageToken'] : '';
	}
}
else {
	$ip = json_decode(@file_get_contents('https://www.iplocate.io/api/lookup/'.$_SERVER['REMOTE_ADDR']), true);
	$country = isset($ip['country']) ? $ip['country'] : 'United States';
	$number = rand(1, 20);
	$api = "http://ws.audioscrobbler.com/2.0/?method=geo.getTopArtists&api_key=$key_lastfm&format=json&limit=24&page=$number&country=".urlencode($country);
	$suggestions = "			<h4 data-country=\"$country\" data-page=\"$number\">Featured artists:</h4>\n";
	$json = json_decode(@file_get_contents($api), true);
	foreach ($json['topartists']['artist'] as $item) {
		$artists[] = '			<a href="?q='.urlencode($item['name']).'">'.$item['name']."</a>\n";
	}
	shuffle($artists);
	$suggestions .= implode($artists)."		</main>\n";
}
?>
<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="description" content="Listen to music hassle free."/>
		<title><?php if ($id) echo $title; else echo 'Music'; ?></title>
		<style>
			@import url(https://use.fontawesome.com/releases/v5.7.2/css/all.css);
			::selection {
				color: #ff8a00;
			}
			body {
				margin: 0;
				display: grid;
				height: 100vh;
				place-items: center center;
				background-color: #fff;
				background-image: url(https://i.ytimg.com/vi/<?php if ($id) echo $id.'/hq'; else { $bg = array('wPzYJu1j1bI', '_zSXFUcQ7qA', 'gmU9PBDS-0k', 'OeHLHNKQCXA', 'fNh2yB0w8gU', 'hlWiI4xVXKY', 'BaMBQSsdnxo', '58GQx4xEdlY', 'CVvdAWp37jw', '6MztEmTkvqo', 'FkWHg3lvZXw', 'xwQ5YpcCDHk'); echo $bg[array_rand($bg)].'/maxres'; } #FkWHg3lvZXw ?>default.jpg);
				background-size: cover;
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-position: center center;
			}
			main {
				width: 75%;
				margin: 2em 0;
				padding: 2em;
				cursor: default;
				text-align: center;
				font: 400 1em/1.5 Arial;
				color: #fff;
				border-radius: 30px;
				box-shadow: 2px 2px 10px #246756;
				background-image: radial-gradient(farthest-side at bottom left, rgba(255, 0, 255, 0.5), #246756), radial-gradient(farthest-corner at bottom right, rgba(255, 50, 50, 0.5), #246756 400px);
				opacity: 0.9;
			}
			form {
				margin: 0;
			}
			input {
				margin: 0;
				border: 0;
				outline: 0;
				color: #fff;
				font-size: 1.2em;
				line-height: 26px;
			}
			input[type=search] {
				width: 85%;
				padding: 14px 24px;
				background-color: #201c29;
				border-top-left-radius: 30px;
				border-bottom-left-radius: 30px;
			}
			input[type=submit] {
				width: 15%;
				padding: 14px 0;
				cursor: pointer;
				border-top-right-radius: 30px;
				border-bottom-right-radius: 30px;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			h4 {
				margin: 1em;
				text-shadow: 2px 2px 4px #201c29;
			}
			a, .fas {
				outline: 0;
				cursor: pointer;
				text-decoration: none;
				display: inline-block;
				margin: 0 2px 8px;
				padding: 8px;
				border-radius: 8px;
				color: #fff;
				text-shadow: 2px 2px 4px #201c29;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			.fas {
				width: 24px;
			}
			a:hover, .fas:hover, input[type=submit]:hover {
				background-image: linear-gradient(to right, #da1b60, #ff8a00);
			}
			audio {
				outline: 0;
				width: 100%;
				margin-top: 1em;
			}
			img, .fa-pause, .fa-play, .fa-video, .fa-video-slash, .fa-history {
				display: none;
			}
			iframe {
				border: 0;
				outline: 0;
				width: 100%;
				height: 200px;
				margin-top: 1em;
			}
			@media only screen and (max-width: 480px) {
				iframe {
					height: calc((9/16)*75vw);
				}
			}
		</style>
	</head>
	<body <?php if (!$id) echo 'onload="document.querySelector(\'input[type=search]\').focus();"'; ?>>
		<main>
			<form>
				<input type="search" name="q" id="search" placeholder="Search music&hellip;" value="<?= htmlspecialchars($query, ENT_QUOTES); ?>"/><input type="submit" value="►"/>
			</form>
<?php if ($id) { ?>
			<h4><?= $title; ?></h4>
			<i class="fas fa-home"></i>
			<i class="fas fa-video"></i>
			<i class="fas fa-video-slash"></i>
			<i class="fas fa-pause"></i>
			<i class="fas fa-play"></i>
			<i class="fas fa-undo-alt"></i>
			<i class="fas fa-history"></i>
<?php if ($page) { ?>
			<i class="fas fa-forward" onclick="javascript:next();"></i>
<?php } ?>
			<img src="https://i.ytimg.com/vi/<?= $id; ?>/maxresdefault.jpg" alt=""/>
			<audio controls></audio>
			<div id="youtube"></div>
		</main>
		<script>
			var vid = '<?= $id; ?>',
			audios = {},
			audio = document.querySelector('audio'),
			video = document.querySelector('.fa-video'),
			videoff = document.querySelector('.fa-video-slash'),
			play = document.querySelector('.fa-play'),
			pause = document.querySelector('.fa-pause'),
			loop = document.querySelector('.fa-undo-alt'),
			looped = document.querySelector('.fa-history');
			fetch("https://images" + ~~(Math.random() * 33) + "-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url=https%3A%2F%2Fwww.youtube.com%2Fget_video_info%3Fvideo_id%3D" + vid).then(response => {
				if (response.ok) {
					response.text().then(data => {
						var data = parse(data),
						streams = (data.url_encoded_fmt_stream_map + ',' + data.adaptive_fmts).split(',');
						streams.forEach(function(s, n) {
							var stream = parse(s),
							itag = stream.itag * 1,
							quality = false;
							switch (itag) {
								case 140:
								quality = 'mp3';
								break;
							}
							if (quality) audios[quality] = stream.url;
						});
						audio.src = audios['mp3'];
						audio.play();
					})
				}
			});
			function parse(str) {
				return str.split('&').reduce(function(params, param) {
					var paramSplit = param.split('=').map(function(value) {
						return decodeURIComponent(value.replace('+', ' '));
					});
					params[paramSplit[0]] = paramSplit[1];
					return params;
				}, {});
			}
			audio.onerror = function() {
				audio.style.display = 'none';
				video.style.display = 'inline-block';
				var tag = document.createElement('script');
				tag.src = "https://www.youtube.com/iframe_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
				var player;
			}
			function onYouTubeIframeAPIReady() {
				player = new YT.Player('youtube', {
					videoId: '<?= $id; ?>',
					playerVars: {
						'autoplay': 1,
						'iv_load_policy': 3,
						'modestbranding': 1,
						'playsinline': 1,
						'rel': 0,
						'showinfo': 0
					},
					events: {
						'onReady': onPlayerReady,
						'onStateChange': onPlayerStateChange
					}
				});
				player.getIframe().style.display = 'none';
			}
			loop.onclick = function() {
				loop.style.display = 'none';
				looped.style.display = 'inline-block';
			}
			looped.onclick = function() {
				looped.style.display = 'none';
				loop.style.display = 'inline-block';
			}
<?php if ($page) { ?>
			audio.onended = function() {
				if (loop.style.display == 'none') audio.play();
				else next();
			}
			function onPlayerStateChange(event) {
				if (event.data == YT.PlayerState.ENDED) {
					if (loop.style.display == 'none') player.playVideo();
					else next();
				}
				if (event.data == YT.PlayerState.PAUSED) {
					pause.style.display = 'none';
					play.style.display = 'inline-block';
				}
				if (event.data == YT.PlayerState.PLAYING) {
					play.style.display = 'none';
					pause.style.display = 'inline-block';
				}
			}
			function next() {
				window.location.href = '?q=<?= urlencode($query).'&t='.$page; ?>';
			}
<?php } ?>
			function onPlayerReady(event) {
				event.target.playVideo();
			}
			video.onclick = function() {
				player.getIframe().style.display = 'inline-block';
				video.style.display = 'none';
				videoff.style.display = 'inline-block';
			}
			videoff.onclick = function() {
				player.getIframe().style.display = 'none';
				videoff.style.display = 'none';
				video.style.display = 'inline-block';
			}
			pause.onclick = function() {
				player.pauseVideo();
			}
			play.onclick = function() {
				player.playVideo();
			}
			document.querySelector('.fa-home').onclick = function() {
				window.location.href = '<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>';
			}
			document.querySelector('img').onload = function() {
				if (this.naturalWidth == 1280) document.body.style.backgroundImage = 'url(' + this.getAttribute('src') + ')';
			}
			document.onkeypress = function(event) {
				if (event.target.tagName != 'INPUT') {
					if (event.keyCode == 32) {
						if (audio.style.display == 'none') {
							if (play.style.display == 'none')  player.pauseVideo();
								else player.playVideo();
						} else {
							if (audio.paused) audio.play();
							else audio.pause();
						}
					}
<?php if ($page) { ?>
					if (event.keyCode == 13) next();
<?php } ?>
				}
			}
		</script>
<?php } else echo $suggestions; ?>
	</body>
</html>
