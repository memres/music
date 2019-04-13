<?php
/* Hello, this is a music application to listen to some great sounds especially on mobile devices.
*
You should firstly type your YouTube Data API key here which you can obtain at: https://console.developers.google.com/apis/api/youtube.googleapis.com/ */
$key_youtube = '';
/* and your Last.fm API key here which you can obtain at: https://www.last.fm/api/account/create */
$key_lastfm = '';
/* thanks and have fun! */
$id = '';
$query = isset($_GET['q']) ? $_GET['q'] : '';
$token = isset($_GET['t']) ? $_GET['t'] : '';
if ($query) {
	/* let's get the necessary informations via YouTube Data API */
	$api = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=1&videoSyndicated=true&prettyPrint=false&fields=nextPageToken,items(id(videoId),snippet(title))&key=$key_youtube&q=".urlencode($query);
	if ($token) $api .= "&pageToken=$token";
	if (strtolower($query) != 'rammstein') $api .= '&videoCategoryId=10'; /* interestingly Rammstein's music videos are not in music category */
	$opts = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false));
	$json = json_decode(@file_get_contents($api, false, stream_context_create($opts)), true);
	if (isset($json['items'][0])) {
		$id = $json['items'][0]['id']['videoId'];
		$title = $json['items'][0]['snippet']['title'];
		$page = isset($json['nextPageToken']) ? $json['nextPageToken'] : '';
	}
}
else {
	/* let's get top artists via Last.fm API */
	$user = 'memres';
	/* you can change the username with yours */
	$api = "http://ws.audioscrobbler.com/2.0/?method=user.getTopArtists&user=$user&api_key=$key_lastfm&format=json";
	$json = json_decode(@file_get_contents($api), true);
	$suggestions = '			<h4>Some awesome suggestions:</h4>';
	foreach ($json['topartists']['artist'] as $item) {
		$artists[] = '<a href="?q='.urlencode($item['name']).'">'.$item['name'].'</a>';
	}
	shuffle($artists);
	$artists = array_slice($artists, 0, 24, true);
	$suggestions .= implode("\n			", $artists)."\n		</main>\n";
}
?>
<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php if ($id) echo $title; else echo 'Music'; ?></title>
		<style>
			body {
				margin: 0;
				display: grid;
				height: 100vh;
				place-items: center center;
				background-color: #fff;
<?php if ($id) { ?>
				background-image: url(https://i.ytimg.com/vi/<?= $id; ?>/hqdefault.jpg);
				background-size: cover;
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-position: center center;
<?php } ?>
			}
			main {
				width: 75%;
				margin: 2em 0;
				padding: 2em;
				text-align: center;
				font: 400 1em/1.5 Arial;
				color: #fff;
				border-radius: 30px;
				box-shadow: 2px 2px 10px #246756;
				background: radial-gradient(farthest-side at bottom left, rgba(255, 0, 255, 0.5), #246756), radial-gradient(farthest-corner at bottom right, rgba(255, 50, 50, 0.5), #246756 400px);
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
				padding: 14px;
				background: #201c29;
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
			a, a.alt:hover {
				outline: 0;
				text-decoration: none;
				display: inline-block;
				margin: 0 2px 8px;
				padding: 8px;
				border-radius: 8px;
				color: #fff;
				text-shadow: 2px 2px 4px #201c29;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			a:hover, a.alt, input[type=submit]:hover {
				background-image: linear-gradient(to right, #da1b60, #ff8a00);
			}
			audio {
				outline: 0;
				width: 100%;
				margin-top: 1em;
			}
			aside, img {
				display: none;
			}
			iframe {
				border: 0;
				outline: 0;
				width: 100%;
				height: 180px;
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
				<input type="search" name="q" id="search" placeholder="Search music&hellip;" value="<?= htmlspecialchars($query, ENT_QUOTES); ?>"/><input type="submit" value="&#9654;"/>
			</form>
<?php if ($id) { ?>
			<h4><?= $title; ?></h4>
			<a href="<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>">Main</a>
			<a class="alt" href="javascript:stop();">Stop</a>
<?php if ($page) { ?>
			<a href="javascript:next();">Next</a>
<?php } ?>
			<img src="https://i.ytimg.com/vi/<?= $id; ?>/maxresdefault.jpg" alt=""/>
			<audio autoplay controls></audio>
			<aside id="youtube"></aside>
		</main>
		<script>
			/* based on the answer by 350D at https://stackoverflow.com/a/45375023 */
			var vid = '<?= $id; ?>', audios = {}, audio = document.querySelector('audio');
			fetch("https://images" + ~~(Math.random() * 33) + "-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url=https%3A%2F%2Fwww.youtube.com%2Fget_video_info%3Fvideo_id%3D" + vid).then(response => {
				if (response.ok) {
					response.text().then(data => {
						var data = parsestr(data),
						streams = (data.url_encoded_fmt_stream_map + ',' + data.adaptive_fmts).split(',');
						streams.forEach(function(s, n) {
							var stream = parsestr(s),
							itag = stream.itag * 1,
							quality = false;
							console.log(stream);
							switch (itag) {
								case 140:
								quality = 'mp3';
								break;
								/*
								< alternative audio option >
								case 171:
								quality = 'webm';
								break;
								*/
							}
							if (quality) audios[quality] = stream.url;
						});
						/* console.log(audios); <- for testing purposes */
						audio.src = audios['mp3'];
						audio.play();
					})
				}
			});
			function parsestr(str) {
				return str.split('&').reduce(function(params, param) {
					var paramSplit = param.split('=').map(function(value) {
						return decodeURIComponent(value.replace('+', ' '));
					});
					params[paramSplit[0]] = paramSplit[1];
					return params;
				}, {});
			}
			/* if youtube forbides the direct link, compulsorily put embed player */
			audio.onerror = function() {
				audio.style.display = 'none';
				var ytplayer = document.querySelector('aside');
				ytplayer.style.display = 'inline';
				ytplayer.focus(); /* to prevent the screen from turning off on mobile browsers */
				var tag = document.createElement('script');
				tag.src = "https://www.youtube.com/iframe_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
				var player;
			}
			function onYouTubeIframeAPIReady() {
				player = new YT.Player('youtube', {
					host: 'https://www.youtube-nocookie.com',
					videoId: '<?= $id; ?>',
					playerVars: {
						'autoplay': 1,
						'iv_load_policy': 3,
						'modestbranding': 1,
						'rel': 0,
						'showinfo': 0
					},
					events: {
						'onReady': onPlayerReady,
						'onStateChange': onPlayerStateChange
					}
				});
			}
<?php if ($page) { ?>
			audio.onended = function() {
				next();
			}
			function onPlayerStateChange(event) {
				if (event.data == YT.PlayerState.ENDED) next();
			}
			function next() {
				window.location.href = '?q=<?= urlencode($query).'&t='.$page; ?>';
			}
<?php } ?>
			function onPlayerReady(event) {
				event.target.playVideo();
			}
			function stop() {
				if (audio.style.display == 'none') {
					player.stopVideo();
				} else {
					audio.pause();
					audio.currentTime = 0;
				}
			}
			/* if the max-sized image is available, change the background with it */
			document.querySelector('img').onload = function() {
				if (this.naturalWidth == 1280) document.body.style.backgroundImage = 'url(' + this.getAttribute('src') + ')';
			}
		</script>
<?php } else { echo $suggestions; } ?>
	</body>
</html>
