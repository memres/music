<?php
$title = 'Music';
$motto = 'Listen to music of the world.';
$playlist = !empty($_COOKIE['playlist']) ? $_COOKIE['playlist'] : 'PL0KrRV0rbVN_2obINsiQO7xAa8x1vA1TS';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://music.youtube.com/youtubei/v1/browse?alt=json&key=AIzaSyC9XL3ZjWddXya6X74dJoCTL-WEYFDNX30');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"context":{"client":{"clientName":"WEB_REMIX","clientVersion":"0.1","hl":"en","gl":"US","experimentIds":[],"experimentsToken":"","browserName":"Chrome","browserVersion":"86.0.4240.75","osName":"Windows","osVersion":"10.0","utcOffsetMinutes":180,"locationInfo":{"locationPermissionAuthorizationStatus":"LOCATION_PERMISSION_AUTHORIZATION_STATUS_UNSUPPORTED"},"musicAppInfo":{"musicActivityMasterSwitch":"MUSIC_ACTIVITY_MASTER_SWITCH_INDETERMINATE","musicLocationMasterSwitch":"MUSIC_LOCATION_MASTER_SWITCH_INDETERMINATE","pwaInstallabilityStatus":"PWA_INSTALLABILITY_STATUS_UNKNOWN"}},"capabilities":{},"request":{"internalExperimentFlags":[{"key":"force_route_music_library_subscriptions_to_outertube","value":"true"},{"key":"force_music_enable_outertube_home_browse","value":"true"},{"key":"force_route_music_library_track_artists_to_outertube","value":"true"},{"key":"force_music_enable_outertube_tastebuilder_browse","value":"true"},{"key":"force_music_enable_outertube_playlist_detail_browse","value":"true"},{"key":"force_route_music_library_playlists_to_outertube","value":"true"},{"key":"force_music_enable_outertube_music_queue","value":"true"},{"key":"force_route_music_library_songs_to_outertube","value":"true"},{"key":"force_route_music_library_albums_to_outertube","value":"true"},{"key":"force_music_enable_outertube_album_detail_browse","value":"true"},{"key":"force_music_enable_outertube_search_suggestions","value":"true"}],"sessionIndex":{}},"clickTracking":{"clickTrackingParams":null},"activePlayers":{},"user":{"enableSafetyMode":false}},"browseId":"VL'.$playlist.'","browseEndpointContextSupportedConfigs":{"browseEndpointContextMusicConfig":{"pageType":"MUSIC_PAGE_TYPE_PLAYLIST"}}}');
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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="utf-8"/>
		<meta name="robots" content="noindex"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
		<title><?= $title; ?></title>
		<meta name="description" content="<?= $motto; ?>"/>
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
			<optgroup label="—————————"></optgroup>
			<optgroup label="🗺 Countries"></optgroup>
			<optgroup label="—————————"></optgroup>
			<option value="PL4fGSI1pDJn6puJdseH2Rt9sMvt9E2M4i">Worldwide</option>
			<option value="PL4fGSI1pDJn4Kd7YEG9LbUqvt64PLs9Fo">Argentina</option>
			<option value="PL4fGSI1pDJn7xvYy-bP6UFeG5tITQgScd">Australia</option>
			<option value="PL4fGSI1pDJn6fFTVP30alDfSDAkEtHaNr">Austria</option>
			<option value="PL4fGSI1pDJn64Up8Ds5BXizLBFZ922jHj">Belgium</option>
			<option value="PL4fGSI1pDJn5Vi4RJX33LnETbjMhmPc9V">Bolivia</option>
			<option value="PL4fGSI1pDJn7rGBE8kEC0CqTa1nMh9AKB">Brazil</option>
			<option value="PL4fGSI1pDJn57Q7WbODbmXjyjgXi0BTyD">Canada</option>
			<option value="PL4fGSI1pDJn777t00zYu_BKjXHUdhkXH9">Chile</option>
			<option value="PL4fGSI1pDJn6CW97F1vSZOkoU7k7VsYk9">Colombia</option>
			<option value="PL4fGSI1pDJn6U9fUfBkfy3uyXE7Rtvo4b">Costa Rica</option>
			<option value="PL4fGSI1pDJn5wV1AgglmIN_8okwTkz9WT">Czechia</option>
			<option value="PL4fGSI1pDJn51jFsgXEIR7WdKBychJiMU">Denmark</option>
			<option value="PL4fGSI1pDJn4C36SQoHh9fII-EXde2i3k">Dominican Republic</option>
			<option value="PL4fGSI1pDJn7K4bdLZJ5GppzLDAihF58q">Ecuador</option>
			<option value="PL4fGSI1pDJn6ALv-WRypOl0nGaLgtW6nC">El Salvador</option>
			<option value="PL4fGSI1pDJn7uCBUO9GemJda1xfqmvV7_">Estonia</option>
			<option value="PL4fGSI1pDJn4T5TECl_90hfJsPUu1yi2y">Finland</option>
			<option value="PL4fGSI1pDJn7bK3y1Hx-qpHBqfr6cesNs">France</option>
			<option value="PL4fGSI1pDJn6KpOXlp0MH8qA9tngXaUJ-">Germany</option>
			<option value="PL4fGSI1pDJn7NCQ_U0nwlhidgZ8E3uBQw">Guatemala</option>
			<option value="PL4fGSI1pDJn5ZVtAKP9-OKnn09CJ-Znpt">Honduras</option>
			<option value="PL4fGSI1pDJn6K3QY1nHyhOGQqNCBGbMKi">Hungary</option>
			<option value="PL4fGSI1pDJn6pwJw_mb31TUqc9C_gpskG">Iceland</option>
			<option value="PL4fGSI1pDJn4pTWyM3t61lOyZ6_4jcNOw">India</option>
			<option value="PL4fGSI1pDJn5S_UFt83P-RlBC4CR3JYuo">Ireland</option>
			<option value="PL4fGSI1pDJn4ECcNLNscMAPND-Degbd5N">Israel</option>
			<option value="PL4fGSI1pDJn5JiDypHxveEplQrd7XQMlX">Italy</option>
			<option value="PL4fGSI1pDJn4-UIb6RKHdxam-oAUULIGB">Japan</option>
			<option value="PL4fGSI1pDJn7z-3xqv1Ujjobcy2pjpZAA">Kenya</option>
			<option value="PL4fGSI1pDJn4ie_xg2ndQYSEeZrFYvkQf">Luxembourg</option>
			<option value="PL4fGSI1pDJn6fko1AmNa_pdGPZr5ROFvd">Mexico</option>
			<option value="PL4fGSI1pDJn7CXu1B1U0lYQ0qfPB9TVfa">Netherlands</option>
			<option value="PL4fGSI1pDJn6SZ8psSiS6j-QgUACJK4gC">New Zealand</option>
			<option value="PL4fGSI1pDJn7eCAxG3AuCuottnW_D5C5w">Nicaragua</option>
			<option value="PL4fGSI1pDJn6Au0oeuQPsd1iFyiU8Br9I">Nigeria</option>
			<option value="PL4fGSI1pDJn7ywehQhyuuPWo3ayrdSOHn">Norway</option>
			<option value="PL4fGSI1pDJn4G4B-V4UTrxD7l5mE9cPS-">Panama</option>
			<option value="PL4fGSI1pDJn5G0B8V2PSgs7O9EA4gF5m_">Paraguay</option>
			<option value="PL4fGSI1pDJn4k5jOJjYpq8pluME-gNAnh">Peru</option>
			<option value="PL4fGSI1pDJn68fmsRw9f6g-NzU5UA45v1">Poland</option>
			<option value="PL4fGSI1pDJn7H0X0bZN4C-I6YeldOvPku">Portugal</option>
			<option value="PL4fGSI1pDJn5G2T6hrqwSS7ajUA7y4S5l">Romania</option>
			<option value="PL4fGSI1pDJn5C8dBiYt0BTREyCHbZ47qc">Russia</option>
			<option value="PL4fGSI1pDJn79dpGvfySMY9w43BluD4lI">Serbia</option>
			<option value="PL4fGSI1pDJn7xvqMZR_9OgljLcMQpuKXN">South Africa</option>
			<option value="PL4fGSI1pDJn6jXS_Tv_N9B8Z0HTRVJE0m">South Korea</option>
			<option value="PL4fGSI1pDJn6sMPCoD7PdSlEgyUylgxuT">Spain</option>
			<option value="PL4fGSI1pDJn7S_JFSuBHol2RH9WphaqzS">Sweden</option>
			<option value="PL4fGSI1pDJn6Nhmcqn4xr769wwoMmS3DI">Switzerland</option>
			<option value="PL4fGSI1pDJn4CI0qH2JZYs2qGXo1itpCG">Tanzania</option>
			<option value="PL4fGSI1pDJn5tdVDtIAZArERm_vv4uFCR">Turkey</option>
			<option value="PL4fGSI1pDJn74qLP73Smcy0gAbGWi71Gr">Uganda</option>
			<option value="PL4fGSI1pDJn4E_HoW5HB-w5vFPkYfo3dB">Ukraine</option>
			<option value="PL4fGSI1pDJn6_f5P3MnzXg9l3GDfnSlXa">United Kingdom</option>
			<option value="PL4fGSI1pDJn6O1LS0XSdF3RyO0Rq_LDeI">United States</option>
			<option value="PL4fGSI1pDJn5caN5mlO8NWCPSyuHkQANg">Uruguay</option>
			<option value="PL4fGSI1pDJn7JsFoxkKlW3Zoi_snioCcs">Zimbabwe</option>
			<optgroup label="—————————"></optgroup>
			<optgroup label="♫ Individual"></optgroup>
			<optgroup label="—————————"></optgroup>
			<option value="PL0KrRV0rbVN_2obINsiQO7xAa8x1vA1TS">Goldies</option>
			<option value="PL0KrRV0rbVN-21saESr1SBcKls4kZy09W">Global</option>
			<option value="PL0KrRV0rbVN-Rnulv8HWqd2v-IWQ7gMUs">Hebrew</option>
			<option value="PL0KrRV0rbVN_xUARb6NIifWlMcLsJSmDo">Turkish</option>
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
<?php if (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) { ?>
		<script>var sc_invisible=1,sc_project=9421647,sc_security="cfd47c32";window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)},ga.l=+new Date,ga("create","UA-8993524-2","auto"),ga("send","pageview");</script>
		<script src="https://statcounter.com/counter/counter.js"></script>
		<script src="https://www.google-analytics.com/analytics.js" async></script>
<?php } ?>
	</body>
</html>
