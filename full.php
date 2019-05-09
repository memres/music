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
	/* Removed due to slow page loading...
	$featured .= implode($artists)."			<h3>Hit Tracks</h3>\n";
	$api = 'http://ws.audioscrobbler.com/2.0/?method=geo.getTopTracks&api_key=&format=json&limit=20&page='.mt_rand(1, 28).'&country='.urlencode($country);
	$json = json_decode(@file_get_contents($api), true);
	foreach ($json['tracks']['track'] as $item) $tracks[] = '			<a href="?q='.urlencode($item['artist']['name'].' '.$item['name']).'">'.$item['name']."</a>\n";
	shuffle($tracks);
	$featured .= implode($tracks)."			<h3>Featured Tags</h3>\n";
	*/
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
		<style>
			::selection {
				color: #9c27b0;
			}
			* {
				box-sizing: border-box;
			}
			body {
				margin: 0;
				min-height: 100vh;
				display: flex;
				align-items: center;
				justify-content: center;
				background-color: #fff;
				background-size: cover;
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-position: center center;
			}
			main {
				width: 90%;
				margin: 20px 0;
				padding: 20px;
				user-select: none;
				border-radius: 20px;
				box-shadow: 2px 2px 10px #246756;
				background-image: radial-gradient(farthest-side at bottom left, rgba(255, 0, 255, 0.5), #246756), radial-gradient(farthest-corner at bottom right, rgba(255, 50, 50, 0.5), #246756 400px);
				opacity: 0.95;
			}
			main, input {
				color: #fff;
				text-align: center;
				font-family: 'Open Sans', sans-serif;
			}
			input, button {
				display: inline-block;
				float: left;
				border: 0;
				outline: 0;
				margin: 0;
				color: #fff;
				line-height: 30px;
			}
			input {
				width: 68%;
				padding: 10px;
				font-size: 15px;
				background-color: #201c29;
				border-radius: 0; /* safari */
			}
			button {
				width: 16%;
				padding: 10px 0;
				font-size: 20px;
				cursor: pointer;
				text-shadow: 2px 2px 4px #201c29;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			button[type=button] {
				border-top-left-radius: 20px;
				border-bottom-left-radius: 20px;
			}
			button[type=submit] {
				border-top-right-radius: 20px;
				border-bottom-right-radius: 20px;
			}
			form:after, nav:after, aside:after {
				content: '';
				display: table;
				clear: both;
			}
			h3, h4, h5 {
				margin: 15px 0;
				text-shadow: 2px 2px 4px #201c29;
			}
			h3, h4 {
				font-size: 18px;
				line-height: 24px;
			}
			h5 {
				font-size: 12px;
				line-height: 16px;
				font-weight: normal;
				letter-spacing: 1px;
			}
			h5 span {
				display: none;
			}
			b {
				color: #ff8a00;
				text-transform: uppercase;
			}
			nav i {
				display: inline-block;
				float: left;
				width: 32%;
				margin: 0 1%;
				padding: 15px;
				font-size: 20px;
				cursor: pointer;
				text-shadow: 2px 2px 4px #201c29;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			nav i:first-child {
				margin-left: 0;
				border-top-left-radius: 20px;
				border-bottom-left-radius: 20px;
			}
			nav i:last-child {
				margin-right: 0;
				border-top-right-radius: 20px;
				border-bottom-right-radius: 20px;
			}
			a {
				display: inline-block;
				margin: 5px 2px;
				padding: 10px;
				line-height: 20px;
				font-size: 15px;
				color: #fff;
				outline: 0;
				border-radius: 15px;
				text-decoration: none;
				text-shadow: 2px 2px 4px #201c29;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			a:active, button:active, nav i:active {
				background-image: linear-gradient(to right, #da1b60, #ff8a00);
			}
			ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: auto;
				max-height: 200px;
			}
			li {
				display: block;
				margin: 0;
				padding: 10px;
				line-height: 20px;
				font-size: 15px;
				color: #201c29;
				background-color: #f1f1f1;
				cursor: pointer;
				overflow-wrap: break-word;
				word-wrap: break-word;
				word-break: break-word;
				hyphens: auto;
			}
			li:nth-child(even) {
				background-color: #c1c1c1;
			}
			li.active {
				color: #fff;
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			figure {
				margin: 0;
				z-index: 10;
				position: relative;
				width: 100%;
				height: 40px;
				cursor: col-resize;
				background-color: #f1f1f1;
			}
			.buffer, .seek {
				position: absolute;
				top: 0;
				left: 0;
				height: 40px;
			}
			.buffer {
				background-color: #c1c1c1;
			}
			.seek {
				background-image: linear-gradient(to right, #ff8a00, #da1b60);
			}
			aside {
				margin-top: 15px;
				text-shadow: 2px 2px 4px #201c29;
			}
			aside * {
				float: left;
				width: 20%;
			}
			aside i {
				font-size: 20px;
			}
			aside i:before, h5 i {
				cursor: pointer;
			}
			aside i.on {
				color: #ff8a00;
			}
			time {
				font-size: 15px;
				line-height: 20px;
				letter-spacing: 1px;
			}
			.current {
				text-align: left;
			}
			.total {
				text-align: right;
			}
			@media(min-width: 1280px) {
				main {
					width: 80%;
				}
			}
			@media(min-width: 1920px) {
				input, a, li, time {
					font-size: 30px;
				}
				button, nav i, aside i {
					font-size: 40px;
				}
				h3, h4 {
					font-size: 36px;
					line-height: 48px;
				}
				h5 {
					font-size: 24px;
					line-height: 32px;
				}
				button, input {
					padding: 20px;
					line-height: 60px;
				}
				nav i {
					padding: 30px;
				}
				a, li, time {
					line-height: 40px;
				}
				a {
					margin: 8px 4px;
					padding: 10 20px;
				}
				ul {
					max-height: 360px;
				}
				figure, .buffer, .seek {
					height: 60px;
				}
			}
		</style>
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
		<script>
			$(function(){
				$('ul').slideUp();
				var audio = $('audio')[0], track = 0, trax = [0];
				play(track);
				$(document).on('click', 'li', function() {
					if ($(this).hasClass('active')) {
						audio.currentTime = 0;
						audio.play();
					}
					else {
						track = $(this).index();
						play(track);
						trax.push(track);
					}
				});
				$(document).on('click', 'figure', function(event) {
					var percent = event.offsetX / this.offsetWidth;
					audio.currentTime = percent * audio.duration;
					$('.seek').css('width', percent + '%');
				});
				$(document).on('click', '.fa-question-circle', function() {
					$('h5 span').fadeToggle();
					$(this).toggleClass('far fas');
				});
				$(document).on('click', '.fa-bars', function() {
					if ($(this).hasClass('on')) {
						$('h5').html(h5);
						$('ul').slideUp();
						$(this).removeClass('on');
					}
					else {
						$('h5').html('<b>Playlist</b>');
						$('ul').slideDown();
						$(this).addClass('on');
					}
				});
				$(document).on('click', '.fa-random, .fa-undo-alt', function() {
					if ($(this).hasClass('on')) $(this).removeClass('on');
					else $(this).addClass('on');
				});
				$(document).on('click', '.fa-pause', function() {
					$(this).removeClass('fa-pause').addClass('fa-play');
					audio.pause();
				});
				$(document).on('click', '.fa-play', function() {
					$(this).removeClass('fa-play').addClass('fa-pause');
					audio.play();
				});
				$(document).on('click', '.fa-step-backward', function() {
					prev();
				});
				$(document).on('click', '.fa-step-forward', function() {
					next();
				});
				$(document).on('keyup', function(event) {
					if (event.target.tagName != 'INPUT') {
						if (event.which == 32) {
							if (!audio.paused) audio.pause();
							else audio.play();
						}
						if (event.which == 13) next();
						if (event.which == 66) prev();
						if (event.which == 82) {
							if ($('.fa-random').hasClass('on')) $('.fa-random').removeClass('on');
							else $('.fa-random').addClass('on');
						}
						if (event.which == 76) {
							if ($('.fa-undo-alt').hasClass('on')) $('.fa-undo-alt').removeClass('on');
							else $('.fa-undo-alt').addClass('on');
						}
						if (event.which == 80) {
							if ($('.fa-bars').hasClass('on')) {
								$('h5').html(h5);
								$('ul').slideUp();
								$('.fa-bars').removeClass('on');
							}
							else {
								$('h5').html('<b>Playlist</b>');
								$('ul').slideDown();
								$('.fa-bars').addClass('on');
							}
						}
						if (event.which == 72) {
							$('h5 span').fadeToggle();
							$('.fa-question-circle').toggleClass('far fas');
						}
					}
				});
				$(document).on('keydown', function(event) {
					if (event.target.tagName != 'INPUT') {
						if (event.which == 37) {
							audio.currentTime = audio.currentTime - 3;
						}
						if (event.which == 39) {
							audio.currentTime = audio.currentTime + 3;
						}
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
				const h5 = $('h5').html();
				$('audio').on('error', function() {
					if ($('audio[src*="get.php"]').length) {
						$('audio').attr('src', 'https://invidio.us/latest_version?local=true&itag=140&id=' + $('li.active').attr('id'));
						audio.pause();
						audio.load();
						audio.oncanplaythrough = audio.play();
					}
					else {
						$('li.active').addClass('live');
						next();
						$('li.live').remove();
					}
				});
				$('audio').on('loadedmetadata', function() {
					$('.total').text(calc(audio.duration));
				});
				$('audio').on('progress', function() {
					if (audio.duration > 0) {
						for (var i = 0; i < audio.buffered.length; i++) {
							if (audio.buffered.start(audio.buffered.length - 1 - i) < audio.currentTime) {
								$('.buffer').css('width', (audio.buffered.end(audio.buffered.length - 1 - i) / audio.duration) * 100 + '%');
								break;
							}
						}
					}
				});
				$('audio').on('timeupdate', function() {
					$('.current').text(calc(audio.currentTime, true));
					if (audio.duration > 0) $('.seek').css('width', ((audio.currentTime / audio.duration) * 100) + '%');
				});
				$('audio').on('play', function() {
					$('.fa-play').removeClass('fa-play').addClass('fa-pause');
				});
				$('audio').on('pause', function() {
					$('.fa-pause').removeClass('fa-pause').addClass('fa-play');
				});
				$('audio').on('ended', function() {
					if ($('.fa-undo-alt').hasClass('on')) audio.play();
					else next();
				});
				function calc(d, c) {
					var hour = parseInt(d / 3600),
					min = parseInt((d / 60) % 60),
					sec = parseInt(d - ((hour * 3600) + (min * 60)));
					if (c) sec = parseInt(d % 60);
					return (hour ? hour + ':' : '') + (hour && min < 10 ? '0' + min : min) + ':' + (sec < 10 ? '0' + sec : sec);
				}
				function play(n) {
					$('.buffer, .seek').css('width', '0%');
					$('.current, .total').text('0:00');
					var li = $('li:eq(' + n + ')'),
					id = li.attr('id'),
					title = li.text(),
					hd = 'https://i.ytimg.com/vi/' + id + '/maxresdefault.jpg',
					hq = 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
					$('h3').text(title);
					$(document).attr('title', title);
					$('li').removeClass('active');
					li.addClass('active played');
					if ($('ul').is(':visible')) $('ul').animate({scrollTop: $('li.active').position().top - $('li').first().position().top});
					else $('ul').slideDown().animate({scrollTop: $('li.active').position().top - $('li').first().position().top}).delay().slideUp();
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
					$('audio').attr('src', 'get.php?v=' + id);
					audio.pause();
					audio.load();
					audio.oncanplaythrough = audio.play();
				}
				function next() {
					if ($('.fa-random').hasClass('on')) track = randomize();
					else if (track == $('li:last-child').index()) track = 0;
					else track++;
					play(track);
					trax.push(track);
				}
				function prev() {
					if (trax.length > 1) {
						trax.pop();
						track = trax[trax.length - 1];
						play(track);
					}
				}
				function randomize() {
					var num = ~~(Math.random() * $('li').length);
					if ($('li:eq(' + num + ')').hasClass('played')) {
						if ($('li').length == $('li.played').length) {
							if (num == track) return randomize();
							else {
								$('li').removeClass('played');
								return num;
							}
						}
						else return randomize();
					}
					else return num;
				}
			});
		</script>
<?php } else echo $featured; ?>
		<script>var sc_invisible = 1, sc_project = 5408945, sc_security = 'f75ba4c3';window.ga = window.ga || function(){(ga.q = ga.q || []).push(arguments)};ga.l = +new Date;ga('create', 'UA-28085788-1', 'auto');ga('send', 'pageview');</script>
		<script src="https://statcounter.com/counter/counter.js"></script>
		<script src="https://www.google-analytics.com/analytics.js" async></script>
	</body>
</html>
