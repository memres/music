<?php
$playlist = '';
$query = isset($_GET['q']) ? $_GET['q'] : '';
if ($query) {
	$opts = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false));
	$yt = 'https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&videoSyndicated=true&prettyPrint=false&fields=items(id(videoId))&key=&maxResults';
	$api = "$yt=2&q=".urlencode($query);
	if (strtolower($query) != 'rammstein') $api .= '&videoCategoryId=10';
	$json = json_decode(@file_get_contents($api, false, stream_context_create($opts)), true);
	if (isset($json['items'][0])) {
		$first = $json['items'][0]['id']['videoId'];
		$second = $json['items'][1]['id']['videoId'];
		$api = "$yt=50&relatedToVideoId=$first";
		$json = json_decode(@file_get_contents($api, false, stream_context_create($opts)), true);
		foreach ($json['items'] as $item) {
			$ids[] = $item['id']['videoId'];
		}
		$api = "$yt=50&relatedToVideoId=$second";
		$json = json_decode(@file_get_contents($api, false, stream_context_create($opts)), true);
		foreach ($json['items'] as $item) {
			$ids[] = $item['id']['videoId'];
		}
		shuffle($ids);
		array_unshift($ids, $first, $second);
		$playlist = implode(',', array_unique($ids));
	}
}
else {
	$ip = json_decode(@file_get_contents('https://www.iplocate.io/api/lookup/'.$_SERVER['REMOTE_ADDR']), true);
	$country = isset($ip['country']) ? $ip['country'] : 'United Kingdom';
	$page = mt_rand(1, 20);
	$api = "http://ws.audioscrobbler.com/2.0/?method=geo.getTopArtists&api_key=&format=json&limit=24&page=$page&country=".urlencode($country);
	$featured = "			<h4 data-country=\"$country\" data-page=\"$page\">Featured artists:</h4>\n";
	$json = json_decode(@file_get_contents($api), true);
	foreach ($json['topartists']['artist'] as $item) {
		$artists[] = '			<a href="?q='.urlencode($item['name']).'">'.$item['name']."</a>\n";
	}
	shuffle($artists);
	$featured .= implode($artists)."		</main>\n";
}
?>
<!DOCTYPE html>
<html>
    <head>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="description" content="This is the 'YouTube Playlist' version. Contains the YouTube embed player with iFrame API enabled. Creates a random playlist each time according to the search query."/>
		<meta name="author" content="music@emresanli.com"/>
		<title><?php if ($playlist) echo $query; else echo 'Music &#127925;'; ?></title>
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
<?php if (!$playlist) { $bg = array('FkWHg3lvZXw', 'wPzYJu1j1bI', '_zSXFUcQ7qA', 'gmU9PBDS-0k', 'OeHLHNKQCXA', 'fNh2yB0w8gU', 'hlWiI4xVXKY', 'BaMBQSsdnxo', '58GQx4xEdlY', 'CVvdAWp37jw', '6MztEmTkvqo', 'xwQ5YpcCDHk'); ?>
				background-image: url(https://i.ytimg.com/vi/<?= $bg[array_rand($bg)]; ?>/maxresdefault.jpg);
<?php } ?>
				background-size: cover;
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-position: center center;
			}
			main {
				width: 75%;
				margin: 2em 0 6em;
				padding: 2em;
				cursor: default;
				text-align: center;
				font: 400 1em/1.5 Arial;
				color: #fff;
				border-radius: 30px;
				box-shadow: 2px 2px 10px #246756;
				background-image: radial-gradient(farthest-side at bottom left, rgba(255, 0, 255, 0.5), #246756), radial-gradient(farthest-corner at bottom right, rgba(255, 50, 50, 0.5), #246756 400px);
				opacity: 0.95;
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
			h4, h5 {
				margin: 1em;
				text-shadow: 2px 2px 4px #201c29;
			}
			h5 {
				margin-bottom: 0;
				font-weight: normal;
				letter-spacing: 1px;
			}
			b {
				color: #ff8a00;
				text-transform: uppercase;
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
			#player {
				border: 0;
				outline: 0;
				float: left;
				width: 100%;
				height: 1px;
				opacity: 0;
				pointer-events: none;
			}
			.see {
				height: calc((9/16)*75vw) !important;
				opacity: 1 !important;
				pointer-events: auto !important;
				margin-top: 1em;
			}
			@media(min-width: 480px) {
				.see {
					height: 200px !important;
				}
			}
			footer {
				position: fixed;
				bottom: 0;
				left: 50%;
				transform: translateX(-50%);
			}
			.adsbygoogle {
				display: inline-block;
				width: 100%;
				min-width: 320px;
				max-width: 980px;
				height: 90px;
				min-height: 50px;
				max-height: 120px;
			}
		</style>
	</head>
	<body <?php if (!$playlist) echo 'onload="document.querySelector(\'input[type=search]\').focus();"'; ?>>
		<main>
			<form>
				<input type="search" name="q" placeholder="Search music&hellip;" value="<?= htmlspecialchars($query, ENT_QUOTES); ?>"/><input type="submit" value="&#9658;"/>
			</form>
<?php if ($playlist) { ?>
			<h4><?= $query; ?></h4>
			<i class="fas fa-home"></i>
			<i class="fas fa-video"></i>
			<i class="fas fa-play"></i>
			<i class="fas fa-undo-alt"></i>
			<i class="fas fa-forward"></i>
			<div id="player"></div>
			<h5>Press <b>space</b> to play/pause, and press <b>enter</b> for next track.</h5>
		</main>
		<script src="https://www.youtube.com/iframe_api" async></script>
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.4.0/dist/jquery.min.js"></script>
		<script>
			var player, YTdeferred = $.Deferred();
			window.onYouTubeIframeAPIReady = function() {
				YTdeferred.resolve(window.YT);
			};
			$(function(){
				YTdeferred.done(function(YT) {
					player = new YT.Player('player', {
						host: 'https://www.youtube-nocookie.com',
						playerVars: {
							'iv_load_policy': 3,
							'modestbranding': 1,
							'rel': 0
						},
						events: {
							'onReady': function() {
								player.loadPlaylist({playlist: '<?= $playlist; ?>'});
							},
							'onStateChange': function(event) {
								if (event.data == 0) {
									if ($('.fa-history').length) player.previousVideo();
								}
								if (event.data == 1) {
									$('.fa-play').removeClass('fa-play').addClass('fa-pause');
									$(document).attr('title', player.getVideoData().title);
									$('h4').text(player.getVideoData().title);
									bg(player.getVideoData().video_id);
								}
								if (event.data == 2) {
									$('.fa-pause').removeClass('fa-pause').addClass('fa-play');
								}
							}
						}
					});
				});
				function bg(id) {
					var hd = 'https://i.ytimg.com/vi/' + id + '/maxresdefault.jpg',
						hq = 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
					$.ajax({
						url: 'https://images' + ~~(Math.random() * 33) + '-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url=' + encodeURIComponent(hd),
						type: 'HEAD',
						success: function(){
							$('body').css('background-image', 'url(' + hd + ')');
						},
						error: function(){
							$('body').css('background-image', 'url(' + hq + ')');
						}
					});
				}
				$(document).on('click', '.fa-home', function() {
					$(location).attr('href', '<?= strtok($_SERVER['REQUEST_URI'], '?'); ?>');
				});
				$(document).on('click', '.fa-video', function() {
					$(this).removeClass('fa-video').addClass('fa-video-slash');
					$('#player').addClass('see');
					$('h5').css('display', 'none');
				});
				$(document).on('click', '.fa-video-slash', function() {
					$(this).removeClass('fa-video-slash').addClass('fa-video');
					$('#player').removeClass('see');
					$('h5').css('display', 'block');
				});
				$(document).on('click', '.fa-pause', function() {
					$(this).removeClass('fa-pause').addClass('fa-play');
					player.pauseVideo();
				});
				$(document).on('click', '.fa-play', function() {
					$(this).removeClass('fa-play').addClass('fa-pause');
					player.playVideo();
				});
				$(document).on('click', '.fa-undo-alt', function() {
					$(this).removeClass('fa-undo-alt').addClass('fa-history');
				});
				$(document).on('click', '.fa-history', function() {
					$(this).removeClass('fa-history').addClass('fa-undo-alt');
				});
				$(document).on('click', '.fa-forward', function() {
					player.nextVideo();
				});
				$(document).on('keypress', function(event) {
					if (event.target.tagName != 'INPUT') {
						if (event.which == 32) {
							if (player.getPlayerState() == 1) player.pauseVideo();
							else player.playVideo();
						}
						if (event.which == 13) player.nextVideo();
					}
				});
			});
			if (/Mobi|Android/i.test(navigator.userAgent)) {
				document.querySelector('h5').innerHTML = '<b>Rotate</b> your device to turn off the screen, and <b>shake</b> it for next track.';
				var script = document.createElement('script');
				script.src = 'https://cdn.jsdelivr.net/npm/shake.js@1.2.2/shake.min.js';
				document.body.appendChild(script);
				script.onload = function() {
					var shakeEvent = new Shake({threshold: 17});
					shakeEvent.start();
					window.addEventListener('shake', function() {
						player.nextVideo();
					}, false);
				}
			}
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
