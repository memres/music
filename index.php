<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://music.youtube.com/youtubei/v1/browse?alt=json&key=AIzaSyC9XL3ZjWddXya6X74dJoCTL-WEYFDNX30');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"context":{"client":{"clientName":"WEB_REMIX","clientVersion":"0.1","hl":"en","gl":"US","experimentIds":[],"experimentsToken":"","browserName":"Chrome","browserVersion":"86.0.4240.75","osName":"Windows","osVersion":"10.0","utcOffsetMinutes":180,"locationInfo":{"locationPermissionAuthorizationStatus":"LOCATION_PERMISSION_AUTHORIZATION_STATUS_UNSUPPORTED"},"musicAppInfo":{"musicActivityMasterSwitch":"MUSIC_ACTIVITY_MASTER_SWITCH_INDETERMINATE","musicLocationMasterSwitch":"MUSIC_LOCATION_MASTER_SWITCH_INDETERMINATE","pwaInstallabilityStatus":"PWA_INSTALLABILITY_STATUS_UNKNOWN"}},"capabilities":{},"request":{"internalExperimentFlags":[{"key":"force_route_music_library_subscriptions_to_outertube","value":"true"},{"key":"force_music_enable_outertube_home_browse","value":"true"},{"key":"force_route_music_library_track_artists_to_outertube","value":"true"},{"key":"force_music_enable_outertube_tastebuilder_browse","value":"true"},{"key":"force_music_enable_outertube_playlist_detail_browse","value":"true"},{"key":"force_route_music_library_playlists_to_outertube","value":"true"},{"key":"force_music_enable_outertube_music_queue","value":"true"},{"key":"force_route_music_library_songs_to_outertube","value":"true"},{"key":"force_route_music_library_albums_to_outertube","value":"true"},{"key":"force_music_enable_outertube_album_detail_browse","value":"true"},{"key":"force_music_enable_outertube_search_suggestions","value":"true"}],"sessionIndex":{}},"clickTracking":{"clickTrackingParams":null},"activePlayers":{},"user":{"enableSafetyMode":false}},"browseId":"VL'.(!empty($_COOKIE['playlist']) ? $_COOKIE['playlist'] : 'PL0KrRV0rbVN-21saESr1SBcKls4kZy09W').'","browseEndpointContextSupportedConfigs":{"browseEndpointContextMusicConfig":{"pageType":"MUSIC_PAGE_TYPE_PLAYLIST"}}}');
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'X-Referer: https://music.youtube.com']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$cr = curl_exec($ch);
curl_close($ch);
$json = json_decode($cr, true);
foreach ($json['contents']['singleColumnBrowseResultsRenderer']['tabs'][0]['tabRenderer']['content']['sectionListRenderer']['contents'][0]['musicPlaylistShelfRenderer']['contents'] as $i) {
	$id = $i['musicResponsiveListItemRenderer']['flexColumns'][0]['musicResponsiveListItemFlexColumnRenderer']['text']['runs'][0]['navigationEndpoint']['watchEndpoint']['videoId'] ?? null;
	if (!$id) continue;
	$duration = $i['musicResponsiveListItemRenderer']['fixedColumns'][0]['musicResponsiveListItemFixedColumnRenderer']['text']['runs'][0]['text'];
	$track = $i['musicResponsiveListItemRenderer']['flexColumns'][0]['musicResponsiveListItemFlexColumnRenderer']['text']['runs'][0]['text'];
	$artist = '';
	foreach ($i['musicResponsiveListItemRenderer']['flexColumns'][1]['musicResponsiveListItemFlexColumnRenderer']['text']['runs'] as $e) {
		$a = $e['text'];
		$artist .= $a;
		if (stripos($track, $a) !== false) $track = str_ireplace($a, '', $track);
	}
	$queue[] = '<li id="'.$id.'"><img src="https://i.ytimg.com/vi/'.$id.'/default.jpg" alt="'.$duration.'"/><bdi>'.$artist.'</bdi> - <bdo>'.trim(trim(trim(str_ireplace(['Official Music Video', 'Official Video', ' ()', ' []'], '', $track)), '-')).'</bdo></li>';
}
$ch = curl_init('https://www.googleapis.com/youtube/v3/playlists?part=snippet&maxResults=50&channelId=UCWKr4i1-fPtckUpqHeMZWIA&key=AIzaSyA-dlBUjVQeuc4a6ZN4RkNUYDFddrVLxrA&fields=nextPageToken,items(id,snippet(title,thumbnails(medium(url))))');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1)');
$result = curl_exec($ch);
curl_close($ch);
$json = json_decode($result, true);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8"/>
		<meta name="robots" content="noindex"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		<title><?= $title = 'Music'; ?></title>
		<meta name="description" content="<?= $motto = 'Listen to music of the world.'; ?>"/>
		<meta property="og:title" content="<?= $title; ?>"/>
		<meta property="og:description" content="<?= $motto; ?>"/>
		<meta property="og:image" content="https://lh3.googleusercontent.com/-Clcvno4d4KM/Xg2Mq73HVfI/AAAAAAAACa8/GLg1y5VsfjYyhJIWH6j8EJ2ZsHhmmsxYwCDMYAw/image.png"/>
		<meta property="og:url" content="<?= $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>"/>
		<meta name="twitter:card" content="summary"/>
		<meta name="theme-color" content="#246756"/>
		<link rel="icon" href="favicon.ico"/>
		<link rel="manifest" href="manifest.json"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext"/>
		<link rel="stylesheet" href="style.css"/>
	</head>
	<body>
		<audio></audio>
		<div><img/></div>
		<header></header>
		<select name="playlist">
<?php foreach ($json['items'] as $i) { ?>
			<option value="<?= $i['id']; ?>"><?= $i['snippet']['title']; ?></option>
<?php } ?>
		</select>
		<main>
			<nav>
				<i class="material-icons prev">skip_previous</i>
				<i class="material-icons play">play_arrow</i>
				<i class="material-icons next">skip_next</i>
			</nav>
			<h1></h1>
			<ul>
				<?= implode("\n				", $queue)."\n"; ?>
			</ul>
			<figure>
				<figcaption class="buffer"></figcaption>
				<figcaption class="progress"></figcaption>
			</figure>
			<aside>
				<time class="current"></time>
				<time class="total"></time>
				<i class="material-icons queue">queue_music</i>
				<i class="material-icons shuffle">shuffle</i>
				<i class="material-icons loop">loop</i>
				<i class="material-icons launch">launch</i>
			</aside>
		</main>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
		<script src="script.js"></script>
	</body>
</html>