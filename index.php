<?php
if (isset($_COOKIE['heroku']) || $_POST['password'] == 'mesmusic') { if (!isset($_COOKIE['heroku'])) setcookie('heroku', true);
$title = 'Music';
$motto = 'Listen to hit music of the world.';
$playlist = !empty($_COOKIE['playlist']) ? $_COOKIE['playlist'] : country();
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
function country() {
	$gl = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? $_SERVER['HTTP_CF_IPCOUNTRY'] : 'WW';
	if (!in_array($gl, ['WW', 'AR', 'AU', 'AT', 'BE', 'BO', 'BR', 'CA', 'CL', 'CO', 'CR', 'CZ', 'DK', 'DO', 'EC', 'SV', 'EE', 'FI', 'FR', 'DE', 'GT', 'HN', 'HU', 'IS', 'IE', 'IL', 'IT', 'JP', 'KE', 'LU', 'MX', 'NL', 'NZ', 'NI', 'NG', 'NO', 'PA', 'PY', 'PE', 'PL', 'PT', 'RO', 'RU', 'RS', 'ZA', 'KR', 'ES', 'SE', 'CH', 'TZ', 'TR', 'UG', 'UA', 'GB', 'US', 'UY', 'ZW'])) $gl = 'WW';
	if ($gl == 'WW') $pl = 'PL4fGSI1pDJn6puJdseH2Rt9sMvt9E2M4i';
	if ($gl == 'AR') $pl = 'PL4fGSI1pDJn4Kd7YEG9LbUqvt64PLs9Fo';
	if ($gl == 'AU') $pl = 'PL4fGSI1pDJn7xvYy-bP6UFeG5tITQgScd';
	if ($gl == 'AT') $pl = 'PL4fGSI1pDJn6fFTVP30alDfSDAkEtHaNr';
	if ($gl == 'BE') $pl = 'PL4fGSI1pDJn64Up8Ds5BXizLBFZ922jHj';
	if ($gl == 'BO') $pl = 'PL4fGSI1pDJn5Vi4RJX33LnETbjMhmPc9V';
	if ($gl == 'BR') $pl = 'PL4fGSI1pDJn7rGBE8kEC0CqTa1nMh9AKB';
	if ($gl == 'CA') $pl = 'PL4fGSI1pDJn57Q7WbODbmXjyjgXi0BTyD';
	if ($gl == 'CL') $pl = 'PL4fGSI1pDJn777t00zYu_BKjXHUdhkXH9';
	if ($gl == 'CO') $pl = 'PL4fGSI1pDJn6CW97F1vSZOkoU7k7VsYk9';
	if ($gl == 'CR') $pl = 'PL4fGSI1pDJn6U9fUfBkfy3uyXE7Rtvo4b';
	if ($gl == 'CZ') $pl = 'PL4fGSI1pDJn5wV1AgglmIN_8okwTkz9WT';
	if ($gl == 'DK') $pl = 'PL4fGSI1pDJn51jFsgXEIR7WdKBychJiMU';
	if ($gl == 'DO') $pl = 'PL4fGSI1pDJn4C36SQoHh9fII-EXde2i3k';
	if ($gl == 'EC') $pl = 'PL4fGSI1pDJn7K4bdLZJ5GppzLDAihF58q';
	if ($gl == 'SV') $pl = 'PL4fGSI1pDJn6ALv-WRypOl0nGaLgtW6nC';
	if ($gl == 'EE') $pl = 'PL4fGSI1pDJn7uCBUO9GemJda1xfqmvV7_';
	if ($gl == 'FI') $pl = 'PL4fGSI1pDJn4T5TECl_90hfJsPUu1yi2y';
	if ($gl == 'FR') $pl = 'PL4fGSI1pDJn7bK3y1Hx-qpHBqfr6cesNs';
	if ($gl == 'DE') $pl = 'PL4fGSI1pDJn6KpOXlp0MH8qA9tngXaUJ-';
	if ($gl == 'GT') $pl = 'PL4fGSI1pDJn7NCQ_U0nwlhidgZ8E3uBQw';
	if ($gl == 'HN') $pl = 'PL4fGSI1pDJn5ZVtAKP9-OKnn09CJ-Znpt';
	if ($gl == 'HU') $pl = 'PL4fGSI1pDJn6K3QY1nHyhOGQqNCBGbMKi';
	if ($gl == 'IS') $pl = 'PL4fGSI1pDJn6pwJw_mb31TUqc9C_gpskG';
	if ($gl == 'IN') $pl = 'PL4fGSI1pDJn4pTWyM3t61lOyZ6_4jcNOw';
	if ($gl == 'IE') $pl = 'PL4fGSI1pDJn5S_UFt83P-RlBC4CR3JYuo';
	if ($gl == 'IL') $pl = 'PL4fGSI1pDJn4ECcNLNscMAPND-Degbd5N';
	if ($gl == 'IT') $pl = 'PL4fGSI1pDJn5JiDypHxveEplQrd7XQMlX';
	if ($gl == 'JP') $pl = 'PL4fGSI1pDJn4-UIb6RKHdxam-oAUULIGB';
	if ($gl == 'KE') $pl = 'PL4fGSI1pDJn7z-3xqv1Ujjobcy2pjpZAA';
	if ($gl == 'LU') $pl = 'PL4fGSI1pDJn4ie_xg2ndQYSEeZrFYvkQf';
	if ($gl == 'MX') $pl = 'PL4fGSI1pDJn6fko1AmNa_pdGPZr5ROFvd';
	if ($gl == 'NL') $pl = 'PL4fGSI1pDJn7CXu1B1U0lYQ0qfPB9TVfa';
	if ($gl == 'NZ') $pl = 'PL4fGSI1pDJn6SZ8psSiS6j-QgUACJK4gC';
	if ($gl == 'NI') $pl = 'PL4fGSI1pDJn7eCAxG3AuCuottnW_D5C5w';
	if ($gl == 'NG') $pl = 'PL4fGSI1pDJn6Au0oeuQPsd1iFyiU8Br9I';
	if ($gl == 'NO') $pl = 'PL4fGSI1pDJn7ywehQhyuuPWo3ayrdSOHn';
	if ($gl == 'PA') $pl = 'PL4fGSI1pDJn4G4B-V4UTrxD7l5mE9cPS-';
	if ($gl == 'PY') $pl = 'PL4fGSI1pDJn5G0B8V2PSgs7O9EA4gF5m_';
	if ($gl == 'PE') $pl = 'PL4fGSI1pDJn4k5jOJjYpq8pluME-gNAnh';
	if ($gl == 'PL') $pl = 'PL4fGSI1pDJn68fmsRw9f6g-NzU5UA45v1';
	if ($gl == 'PT') $pl = 'PL4fGSI1pDJn7H0X0bZN4C-I6YeldOvPku';
	if ($gl == 'RO') $pl = 'PL4fGSI1pDJn5G2T6hrqwSS7ajUA7y4S5l';
	if ($gl == 'RU') $pl = 'PL4fGSI1pDJn5C8dBiYt0BTREyCHbZ47qc';
	if ($gl == 'RS') $pl = 'PL4fGSI1pDJn79dpGvfySMY9w43BluD4lI';
	if ($gl == 'ZA') $pl = 'PL4fGSI1pDJn7xvqMZR_9OgljLcMQpuKXN';
	if ($gl == 'KR') $pl = 'PL4fGSI1pDJn6jXS_Tv_N9B8Z0HTRVJE0m';
	if ($gl == 'ES') $pl = 'PL4fGSI1pDJn6sMPCoD7PdSlEgyUylgxuT';
	if ($gl == 'SE') $pl = 'PL4fGSI1pDJn7S_JFSuBHol2RH9WphaqzS';
	if ($gl == 'CH') $pl = 'PL4fGSI1pDJn6Nhmcqn4xr769wwoMmS3DI';
	if ($gl == 'TZ') $pl = 'PL4fGSI1pDJn4CI0qH2JZYs2qGXo1itpCG';
	if ($gl == 'TR') $pl = 'PL4fGSI1pDJn5tdVDtIAZArERm_vv4uFCR';
	if ($gl == 'UG') $pl = 'PL4fGSI1pDJn74qLP73Smcy0gAbGWi71Gr';
	if ($gl == 'UA') $pl = 'PL4fGSI1pDJn4E_HoW5HB-w5vFPkYfo3dB';
	if ($gl == 'GB') $pl = 'PL4fGSI1pDJn6_f5P3MnzXg9l3GDfnSlXa';
	if ($gl == 'US') $pl = 'PL4fGSI1pDJn6O1LS0XSdF3RyO0Rq_LDeI';
	if ($gl == 'UY') $pl = 'PL4fGSI1pDJn5caN5mlO8NWCPSyuHkQANg';
	if ($gl == 'ZW') $pl = 'PL4fGSI1pDJn7JsFoxkKlW3Zoi_snioCcs';
	setcookie('playlist', $pl, time() + 86400 * 365);
	return $pl;
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
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎼 Chill"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_lahi7LHgueceOxW0fJx3R6RotTOHwKTVk">Afterhours Indie Electronica</option>
			<option value="RDCLAK5uy_kluqSMlnog1GDp3fK-kzf787f9DMW8LB0">All-Time Easy Acoustics</option>
			<option value="RDCLAK5uy_mSTNM0PGbSQ3yzasEOXCX-FbWYcWWXXcs">Autumnal Piano</option>
			<option value="RDCLAK5uy_lnhNPbvX4k_G_zu22RmzmIGBbVecbdGSo">Chilled Electronica</option>
			<option value="RDCLAK5uy_m6G7SIpoC-9YgB58sG5_NOIbKEEeJQ4rA">Chillhop</option>
			<option value="RDCLAK5uy_mPolD_J22gS1SKxufARWcTZd1UrAH_0ZI">Deepchill</option>
			<option value="RDCLAK5uy_k1VhMCqnxKBUtnyLY2-ukw7d7LEW4_By4">Delicate French Songs</option>
			<option value="RDCLAK5uy_njvsGKIUycy_a4h7zTS8upbKhHcMVzHFM">Easy-Going Pop &amp; Rock</option>
			<option value="RDCLAK5uy_mV3tlvfxdTTo6xi3NgL7xX06eCxmmp7sY">Ethereal Electronica</option>
			<option value="RDCLAK5uy_licpPo1LW9tFDgRx6aAvjwrUpG2cXeD64">Folktronica Morning Dew</option>
			<option value="RDCLAK5uy_m0X_CFV7sbR3EKDoP_FvqyK5faV7xorUY">Freshly Chilled</option>
			<option value="RDCLAK5uy_nd7Z5lw4pxvPsojHWM49SQtxW4eoFtC2A">Hits Unplugged</option>
			<option value="RDCLAK5uy_mA8k_XEacwfSzV9XYufCw2GHpnlghRQEk">Jazz Piano in a Mellow Mood</option>
			<option value="RDCLAK5uy_kb7EBi6y3GrtJri4_ZH56Ms786DFEimbM">Lofi Loft</option>
			<option value="RDCLAK5uy_mY3u-PtdCos-uud8AM0rSQujovS5H09Tw">Mantras for Your Wellbeing</option>
			<option value="RDCLAK5uy_nqHoq8CFYwOb_JdlZUn9mbV7wiSjRC3w4">Peaceful Singer-Songwriter</option>
			<option value="RDCLAK5uy_luHLSctqQNSMVQwt1456I_-2Ui0xVY5NY">Reflections of a Singer-Songwriter</option>
			<option value="RDCLAK5uy_nvUj4T_56VOP_kqa2NSnKe4QgnxezO0r4">Relaxed Indie Electronica</option>
			<option value="RDCLAK5uy_mTrr21OCLdb2dJgl_BJ-6MdJDXWqB-1gc">Yoga &amp; Meditation</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎸 Enery Boosters"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_m0Nsi5Jnn_g6qbvc7fywPRhEv1qN0PcMM">Conditions Underground</option>
			<option value="RDCLAK5uy_lzfaAvnOgcjLBHrY52TpE8aHTS3JPFA2I">Don't Stop Me Now</option>
			<option value="RDCLAK5uy_mh_rifcs511XG-CeTlhoXBZ6HmiKk7_B4">Electro Motivation</option>
			<option value="RDCLAK5uy_m_DSYej4lgADdQpVjMhYSkQGOsBsOlWHE">Epic Trance</option>
			<option value="RDCLAK5uy_n9ZfdafqMK3znPy_3zDgor-Qlg1YjSMHw">Female Electro-Pop</option>
			<option value="RDCLAK5uy_k59GXAlPBRGnPK4iQupnDUUI1AIz1t6QM">Golden Gods of Metal</option>
			<option value="RDCLAK5uy_kLwgLlrxA4-_EchctXgTyHR4rwRaRv1wk">I Feel House</option>
			<option value="RDCLAK5uy_k4dNm_2PYzIBXgim5cMwHG1QgDmLQZbdY">Indie Pop Power</option>
			<option value="RDCLAK5uy_lVm93MPGWY2gMYbeUHn2Y5HnRNRzMbQfM">Lose Yourself</option>
			<option value="RDCLAK5uy_k5vcGRXixxemtzK1eKDS7BeHys7mvYOdk">Maximum Decibels</option>
			<option value="RDCLAK5uy_mZlRRhMWQbAAp9pAFHOp5k0q3K3q18Nw8">Morning Euphoria</option>
			<option value="RDCLAK5uy_mT4SbKRyky_D9g1up6uptiHdDg8fXQulM">Pop-Punk &amp; Hardcore</option>
			<option value="RDCLAK5uy_kztWUiOd42stlpXD8mCA1cch2CRQABk2c">Rock All Day Roll All Night</option>
			<option value="RDCLAK5uy_lb6CVU6S4uVugLVNTU9WhqfaomWAgnho4">Shout-Out</option>
			<option value="RDCLAK5uy_ms_Pk5Iu__IPm8uiJU0H1tiZGh5JNEWLQ">Shut Up &amp; Drive</option>
			<option value="RDCLAK5uy_khFJzdpmtk0_MaUuWNgqYbNamuGV623kk">Techno Room</option>
			<option value="RDCLAK5uy_lmPdI8kX7_u_cpZV_m9X5gpVei1qRq-tw">Unstoppable Pop</option>
			<option value="RDCLAK5uy_kAO_XOt7kQyxWrfyALgKmKvAPK0y-QhGk">Uplifting EDM</option>
			<option value="RDCLAK5uy_lsi81CkzVbf_iX-BFMu0YRL8w31CulbFE">Woke Music</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎶 Feel Good"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_lBhJGJYtUsZjFHe5US4EXaHcZltRaIzvM">Bright &amp; Breezy Soul</option>
			<option value="RDCLAK5uy_krSIym5-Zz3SKT9MMkXbmXjXLQfjm8lYQ">Everybody Loves the Sunshine</option>
			<option value="RDCLAK5uy_lWy02cQBnTVTlwuRauaGKeUDH3L6PXNxI">Feel-Good Classic Rock</option>
			<option value="RDCLAK5uy_m8UYTpmaUGhIlXpWiyukIgw4Ug_POVPNw">Feel-Good Dance Hits</option>
			<option value="RDCLAK5uy_mSpTnu0yGFc7ogsk3y6tzNnLPonqB17AY">Feel-Good Floor Fillers</option>
			<option value="RDCLAK5uy_lc8w_0y80HszG3uA16WJ7g3iBg1kqa4pc">Feel-Good Funk &amp; Soul</option>
			<option value="RDCLAK5uy_kioUKViUUSHIhedumyU0tgoB96TdrgczI">Feel-Good Indie Folk</option>
			<option value="RDCLAK5uy_m0wlRoNn5iCTTgBedfoOQ19Jq9P3XTLIA">Feel-Good Pop &amp; Rock</option>
			<option value="RDCLAK5uy_lOprH1vCx0efMJQDYichTFzifw_BkJgbg">Feel-Good R&amp;B Pop</option>
			<option value="RDCLAK5uy_luMytwNDUtZw5OJ-TeitwSXbSolqmEVuc">Fun &amp; Funky</option>
			<option value="RDCLAK5uy_nUzDYmqMzeuOz5H0h_3jRjK3-c6_cNn4s">Good Vibe Rap</option>
			<option value="RDCLAK5uy_lw21MuKaqFsDBIPZSbRmZcoDrHmV_c6uY">Happy Latin Pop</option>
			<option value="RDCLAK5uy_mfdqvCAl8wodlx2P2_Ai2gNkiRDAufkkI">Happy Pop Hits</option>
			<option value="RDCLAK5uy_kQFX0MBt9DZEGKgdyfLgPAXpRqR9xuy80">Sing-Along Indie</option>
			<option value="RDCLAK5uy_nGDY-5g7jyCekbxTiefop3LlmXZ4pUPlo">Sing-Along Soul</option>
			<option value="RDCLAK5uy_m2xlpGtaecJNSNQUH4upH_SCbRqSAUnb0">Sing-Along Vintage Hits</option>
			<option value="RDCLAK5uy_ktv79aJ_zAi049KdFWFaAZEfgnm5jNZpk">Sunshine Indie</option>
			<option value="RDCLAK5uy_mdqA2Fb_DHIMPutUozLjAX8O1WkBD2I8Y">Upbeat Indie Pop</option>
			<option value="RDCLAK5uy_nbjTRkNC2i9SrQ0nIceM1P4Y3a4NPveIk">Walking on Sunshine</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎹 Focus"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_lM8FfMwe3UXqNeUHIMquyuSmzQnzxYQ60">Ambient Bass Music</option>
			<option value="RDCLAK5uy_n9hGvSNdO2TpX8jJuiThvnfrfIi1qNRnY">Classical Focus</option>
			<option value="RDCLAK5uy_kk9Tes94U0LHlttI2bfPQ1Ifm_pdVlBXQ">Classical for Studying</option>
			<option value="RDCLAK5uy_l4mpFrYS2_7EbvFlgA8gWVw0nUC_MresY">Classical Spanish Guitar</option>
			<option value="RDCLAK5uy_n-m1rlUMIY05Fv9G-tzedVYeQOrGL7L8U">Dynamic Solo Cello</option>
			<option value="RDCLAK5uy_nIQ-vZjrOsAAHK2SNZZO7mJ0e1yak6baE">Electronic Mind Focus</option>
			<option value="RDCLAK5uy_n7hJdfgnhIUKMgZ8afb77pS4ZF1zCAI7c">Jazz Focus</option>
			<option value="RDCLAK5uy_kw3wCUMtn1vTrRh2xZsScyjHczOUp5yLU">Jazz for Reading</option>
			<option value="RDCLAK5uy_mkkN7gS94hrEREZWEdf7L-MY8Q6UlBi6c">Head Down Techno</option>
			<option value="RDCLAK5uy_l8RTfCN0RdWbBBOznrMP7kI8AXAr0B1pA">Instrumental Hard Rock</option>
			<option value="RDCLAK5uy_kp8MscJDNmmxJYrv2m6kfUQ1-i8N8Pkv0">Minimal Beats, Endless Style</option>
			<option value="RDCLAK5uy_nnZGCEPxzc5FASdbQVMufD25OfYBJlHqY">Modern Classical Music</option>
			<option value="RDCLAK5uy_ndildEhFGrzzF3GyPvr13NuTR6Jkmch1g">Motorik Jazz</option>
			<option value="RDCLAK5uy_mm0E-ZbUzzmLwLI3BdxlDXr-1KSZZmCwg">Mozart Piano Allegros</option>
			<option value="RDCLAK5uy_kdPvQ6IExq4ZUi0fYvQlXwLWkOPCuTpng">Oriental Trap</option>
			<option value="RDCLAK5uy_kRA21YG7FopO1hRvDdpjlDxWy6fB5Ym5U">Piano Sonatas for Working</option>
			<option value="RDCLAK5uy_nrtPJ8fc1sQkHJ_fT0qZlrJ0t8IoDfP0Q">Psychedelic Ambient Techno</option>
			<option value="RDCLAK5uy_kaBTIn7SlHfzvu4nCvUZemSzFpBpfZGfw">Trip Hop Instrumentals</option>
			<option value="RDCLAK5uy_ny_3fjH20gPvg3ZFCc4ITAoiJHRCF1F_8">Working and Focusing</option>
			<option value="RDCLAK5uy_kvqalDvWx98Jy-ZwveXauB1ozHwmdQSJg">Working to a Beat</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🥁 Party"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_kDFM16SsiM1Wk2Des0hz9DSw9C-DvLRkU">Belly Dancing Anthems</option>
			<option value="RDCLAK5uy_nq6odxgBbj3KUXGBslWi3nKngVBt_iL2g">Big Room Party</option>
			<option value="RDCLAK5uy_ljghXQ9bHNpyxDxcXOagYeZYIITqGj7eU">Brazilian Funk Party</option>
			<option value="RDCLAK5uy_loc3QkpRI5iaLbS_laJf0_OwzvPeAYrYc">Classic Pop Party</option>
			<option value="RDCLAK5uy_lWJBduNuKqFcJUJvhmY9powZ7uVecwTTc">Classic Rock Party</option>
			<option value="RDCLAK5uy_nfs_t4FUu00E5ED6lveEBBX1VMYe1mFjk">Dance-Pop Bangers</option>
			<option value="RDCLAK5uy_l9AjmSK6hYyhyiHN8gl4PbDDL968ag9rs">Disco Fever</option>
			<option value="RDCLAK5uy_l_A3-cqAc4YdpTHxLCpPV9YewV6y3_Ww8">Disco Party at Studio 54</option>
			<option value="RDCLAK5uy_mS-EPMRbILV7IK3GZA-IF2h24ZUrdaIW0">Disco Strings &amp; Floorfillers</option>
			<option value="RDCLAK5uy_lwP-V7kSIhX3CJEV7zRd9P8Nj0F1l5RbY">Drum &amp; Bass Room</option>
			<option value="RDCLAK5uy_m5-Ur8LO4mxLbawH6xdL4htYBw9mq-1RI">Euphoric Dance Classics</option>
			<option value="RDCLAK5uy_nvsLJSrvEhiD_w4BwMjXyruHsJroVjC8g">Funky Pop Party</option>
			<option value="RDCLAK5uy_kWiJXUNLZM9EyS3GBGznl1ku8_cOos97U">Get Up &amp; Dance!</option>
			<option value="RDCLAK5uy_lBGRuQnsG37Akr1CY4SxL0VWFbPrbO4gs">Hip-Hop on Everything</option>
			<option value="RDCLAK5uy_koL8UrtDrQNjkNtDzivGIhdRDKC_4Gr9M">Hot Funk &amp; Soul</option>
			<option value="RDCLAK5uy_l7K78k4EkjcFojhd1617rmUjY-aet6-t0">K-Pop Party Hits</option>
			<option value="RDCLAK5uy_najK8H3a0uHHPccD3U1Rygo_DqD3EkYlc">Nu Disco Boogie</option>
			<option value="RDCLAK5uy_npkgrjOge0uceDgFNjxp5ymEDMdUUHQ_4">Party-Starting Latin Hits</option>
			<option value="RDCLAK5uy_lBNUteBRencHzKelu5iDHwLF6mYqjL-JU">Pop Certified</option>
			<option value="RDCLAK5uy_ktBjabhs1wIZYNOOFX44MOC1nq8QFASH4">Pop Party</option>
			<option value="RDCLAK5uy_kijGNzEJh9L_T8tbNp9dPRLoP_CpR2aZE">Progressive House Party</option>
			<option value="RDCLAK5uy_mRKEF5_7LI3lEOB2OyvG_zYWTXguqBAfc">Right Here, Right Now</option>
			<option value="RDCLAK5uy_kVRnJPeKUO-68eZiQwF8cf9v-rPjnUM4A">Rocking Party Anthems</option>
			<option value="RDCLAK5uy_k1vMt2fo8e-Jy78tTSddZxMBjGSmQfwmU">Sing-Along Rock</option>
			<option value="RDCLAK5uy_nQ6dBbqysNTNkc-uHW7l1jVfq5GFsC4Wc">Straight Outta Balkans</option>
			<option value="RDCLAK5uy_lGiQTUU4XxGV7l9L5YJ3sV0zomDirmZTM">Tech House Party</option>
			<option value="RDCLAK5uy_lNSUHrUfeEfPa7fC-Ns9AXpYRYJq5Xf_8">'00s Indie Dance Party</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎻 Romance"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_m9tEEtXykaa1D5nj2dvvGdr5pTuDQrkMA">A Fine Romance</option>
			<option value="RDCLAK5uy_km8j1Msn0n8FTLRIw0krI9k2sf-t1Z4hg">Acoustic Covers of Love Songs</option>
			<option value="RDCLAK5uy_m6XXtQxtjnB5lr_FhgBANwp7mHKY5Yoik">Acoustic Singer-Songwriters</option>
			<option value="RDCLAK5uy_nHSqCJjDrW9HBhCNdF6tWPdnOMngOv0wA">All-Time Laid-Back Hits</option>
			<option value="RDCLAK5uy_kYQ7Qm5u4UNVG3761sGKaBH5XTLH14iY4">Autumn is for Romantics</option>
			<option value="RDCLAK5uy_kGd5Uflt0qZ87d7AQca88S8VVWTiFm3NM">Female Jazz Vocalists</option>
			<option value="RDCLAK5uy_mgQRfX663uk0M5CQQrk3T5EY3XOEvf40c">Forever Alone</option>
			<option value="RDCLAK5uy_klZswePrKb8XZQSmOz8kbgDb4PgzFvRQk">Jazz for the Lonely</option>
			<option value="RDCLAK5uy_nFJs2geRNXWweqaI-DKBsj9ZmejLScNWU">Jazz Pop Serenades</option>
			<option value="RDCLAK5uy_lpcenrGYN3AxC3juoc-ZJIBeCX6EgC7dc">Indie Lovers Mixtape</option>
			<option value="RDCLAK5uy_lsdV5oBPFEC87QS1yTL1UFbqzVmKNAisE">Lonely Hearts Club</option>
			<option value="RDCLAK5uy_kgcJfFLUn0Tf438rlfoD5RpgjW3bXny4M">Love Themes from the Movies</option>
			<option value="RDCLAK5uy_nDL8KeBrUagwyISwNmyEiSfYgz1gVCesg">Mellow Pop Classics</option>
			<option value="RDCLAK5uy_kHEZSjQcLKHMtZV6DlEKF8PSuucZtfLJg">November Rain</option>
			<option value="RDCLAK5uy_lzsHknmo26P5nkGiogVYHmdZPwreFtwwg">Power Ballads Forever</option>
			<option value="RDCLAK5uy_nyKVppE-RpLkeCcwLct4rvN9e8AAsS_qw">Soft Rock Ballads</option>
			<option value="RDCLAK5uy_nixo3rhrzllo_TPCR0bOGV_Sw-dZdkuHU">Soundtrack Hits</option>
			<option value="RDCLAK5uy_kEr0flXHYLeFC8vz4_7E5rwLxvu1AhH9Q">The Quiet Storm</option>
			<option value="RDCLAK5uy_k_nVgsjkPzM50opxbtIq9DnQP9v1-1vWc">The Way You Look Tonight</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎷 Sleep"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_mtwas9HiKhixA3a5XlISxW3OghgEX4Rk8">Bedroom Chillout</option>
			<option value="RDCLAK5uy_lWpivTL71XRxOpopcMR26TK35Swhkgl7A">Bedtime Singer-Songwriters</option>
			<option value="RDCLAK5uy_mbxFCbi78aFuRwKZf0iN8ftIlmwjGLDxU">Blues Instrumentals</option>
			<option value="RDCLAK5uy_k83Q0BJ_yGLX2nTNKxOXb3BP3Pb7uMIN0">Classical for Sleeping</option>
			<option value="RDCLAK5uy_lbQ87tFQihzaDxmmfDoHbw5xQwBWUrMDk">Conducted</option>
			<option value="RDCLAK5uy_l2OjbOL4oVkkHE86UT6oQCNufuv8d0luQ">Deep Sleep Meditation</option>
			<option value="RDCLAK5uy_k8GvsGbBT2D5lvGqOc6gAitzrcWVvecYI">Downtempo Instrumentals</option>
			<option value="RDCLAK5uy_la7VOc9Dl-iCWLk1Yfg1vN9DTSbv_iyro">Dreamtime Sounds</option>
			<option value="RDCLAK5uy_k2csipDOuQwX6-GGZd4ys5MM5bueEqkrE">Fall Asleep</option>
			<option value="RDCLAK5uy_lsl0WiIp96sJajCIcHkUnOWtsU29tDSNg">Gentle Acoustic Instrumentals</option>
			<option value="RDCLAK5uy_n9dTMg3y6pJDvrR6iVfqFwvb6WHGWSzlw">Hush Hush Jazz</option>
			<option value="RDCLAK5uy_n20y3xLQ-tJWGUDbJu03n2osH1izHdpuI">Icelandic Indie Dreams</option>
			<option value="RDCLAK5uy_lbcexX19-ShfMgzXpxayVFh_9l4Dml-kg">Mellow Solo Piano</option>
			<option value="RDCLAK5uy_kL57PLcOmExjhzqGfGhvA82ZWe4fPH2c4">Muted Jazz</option>
			<option value="RDCLAK5uy_n7J5BsxPobjq4uHzDsf1wEbatggwKjeuY">Sleepy Indie Synths</option>
			<option value="RDCLAK5uy_n7sTcaLpQClN41GSmP1jFn2pZs2uPeSw4">Soothing Classical Harp</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎧 Workout"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_lfHrWBAL48ByDno7Wbnr5PMRFOvRZpOfU">Aggro Rock &amp; Rap for Lifting</option>
			<option value="RDCLAK5uy_lHCkw5gWSHBLzu_ZGfxx-6EZPcq_5C28k">Alternative Metal Workout</option>
			<option value="RDCLAK5uy_kZd0S0zMWrfnYqsjDgjKrIA2_jWm1kYD0">Drum &amp; Bass Workout</option>
			<option value="RDCLAK5uy_mVuA6l3MZFkCMtL1kWMnvBw6c3YaTCytU">Euphoric Running Tracks</option>
			<option value="RDCLAK5uy_mWaI5hOt3Zc_WU4cPCDyhdGt-mPe4mHlw">Hard EDM Workout</option>
			<option value="RDCLAK5uy_mP3cXpsPxDWkZAGQWsJzRel1LJMiVtG2k">Harder Better Faster Stronger</option>
			<option value="RDCLAK5uy_nKbdRGm-ovEaiFG7smcm4hgTi2_w01ZLc">Hip-Hop Workout</option>
			<option value="RDCLAK5uy_n_xjd7ai1z51Df3XTZZoVLMn0d-IJQ6vU">Indie-Rock Workout</option>
			<option value="RDCLAK5uy_kFKbgZ9IT12btLVHpZmyME0Tlv5d0rtsk">New Club and EDM</option>
			<option value="RDCLAK5uy_mHzpUzeIBmQ2pR-iS8xi7CO8CfPdfUM3s">Running Around</option>
			<option value="RDCLAK5uy_mzjlwoE7o230P6pFaamCx1II0XckFiuQo">Running Rock</option>
			<option value="RDCLAK5uy_lysW1jKAEassYxVOmLnd_nVwY5M8ObX0Y">Running to Metal</option>
			<option value="RDCLAK5uy_m2DbXGr69B7FSL6LqEHjdEFsxTmghY4Qw">Running Tracks</option>
			<option value="RDCLAK5uy_m-YJyz6cquN8dHWwbuwWJpoY7gC2dSaVo">Sweaty EDM Workout</option>
			<option value="RDCLAK5uy_kbi6Z_4Nf2I05PQjOGGZMltCe4NidkZbY">Trap Workout</option>
			<option value="RDCLAK5uy_nzrBgAO5csfi06EQwzKRh3BvJUTxf8umI">Working Out in the '00s</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🎵 Local"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="RDCLAK5uy_kODrJiB2r2jrx_oITwxntKLvofERV0NSE">50 Years of Romantic Pop</option>
			<option value="RDCLAK5uy_nIS4kZng78XMjia0cDnzYy8EHwPrHIDX0">Acoustic Wave</option>
			<option value="RDCLAK5uy_kaRoBBGlE8Xh1enKL7w9DqNyCkBaZJjOI">Alternative Lovers</option>
			<option value="RDCLAK5uy_nWAfTMiZb3q5Dh3fXQe3T3A6vzDMJk6G8">Autumn Melancholy</option>
			<option value="RDCLAK5uy_nOUUn0uPng2213dSoIyfl-LCgC_IuGjbM">Calm Instrumental</option>
			<option value="RDCLAK5uy_kjQJh9d85PwdTFi8V03RleRqWdE6_17vo">Chill Classics</option>
			<option value="RDCLAK5uy_nyWocYSe0j8kcLWxzE7KlyO8ZMI9LLCK4">Chill Hotlist</option>
			<option value="RDCLAK5uy_lKXN_dz1MWKldqPyT6K-G79Sw4kC0XOsQ">Club Anthems</option>
			<option value="RDCLAK5uy_nwi2pOvC9hy9_gxyh87wd7Urt6sgdVhEg">Club Hotlist</option>
			<option value="RDCLAK5uy_nHy8cw6a0rx7FosldlxqBf7pnYTiNTQtc">Easy EDM</option>
			<option value="RDCLAK5uy_kB1PM1GFKy0KLYd3C5oZ59KHN-q2JcRRg">Electronic Beats</option>
			<option value="RDCLAK5uy_kFwwkJSpVd6kDTto-rhFDGE6oSY-RmS_w">Fitness</option>
			<option value="RDCLAK5uy_kiLhvcwQ77iHCtSSBSJd9b9qO3pqabOpk">Fresh Coffee</option>
			<option value="RDCLAK5uy_miEDbTEB2wkTklDaoV0_srC3Jqez-pTwA">Fun Songs</option>
			<option value="RDCLAK5uy_lFry8ihXQb97wQH4abCdMtu-s1ttH6Gms">Girl Power</option>
			<option value="RDCLAK5uy_k01s16yKt3DF85A_c7ZyiJ042HgbLqMdI">Have a Happy Journey</option>
			<option value="RDCLAK5uy_kKzUODEz2Q7W1zlUPGAhi6IPFjwGZ5jTI">Hip-Hop Romance</option>
			<option value="RDCLAK5uy_lZmVTh-cPRYcIAQ3KKIDZyA98oFJUH3EI">Indie Love</option>
			<option value="RDCLAK5uy_mbO8rK39lM8U6sBbSggQKBHiBLrJ7jS2M">Indie Party</option>
			<option value="RDCLAK5uy_lk-XEQ33hlGUYXZ2A-e6H-mM0cBhcGv5A">Latin Pop</option>
			<option value="RDCLAK5uy_knwVWlJewj6DVqayFFz7Q4niFA7uh70t8">Love Ache</option>
			<option value="RDCLAK5uy_kODrJiB2r2jrx_oITwxntKLvofERV0NSE">Love Classics</option>
			<option value="RDCLAK5uy_lE1mKJYjSGAi2wWJ_289TZ1VDItr7wjEE">Love Songs</option>
			<option value="RDCLAK5uy_nNPmsza6Q2zki5UwVYYbfRCxDOasGHqcQ">Masters of Melancholy</option>
			<option value="RDCLAK5uy_nqmfME1gwNMEd-b-9rSttKi306K2ogh7Y">Mellow Pop</option>
			<option value="RDCLAK5uy_n15Q2ov0q8ay0rc_0OCO8qNhKIBl-i1Dk">New Acoustic</option>
			<option value="RDCLAK5uy_lcqGMRa1AnE-FviLROb7BClCr3_14yp6A">Pop Floor Fillers</option>
			<option value="RDCLAK5uy_nX6UmhaEyaWe200N_vATudKrScwKqiawI">Pop Party</option>
			<option value="RDCLAK5uy_ktbw9FjYGjKZ_f_Emsq5ZeC5hFKbwkERQ">Rainy Days</option>
			<option value="RDCLAK5uy_kcM9gyTOl7X_O5HqWLj-c9Y6ciUlE1m_U">Rock Ballads</option>
			<option value="RDCLAK5uy_mo6Yju-l1p19WsGcI9OjTMgyCZarfyEIk">Sing-Along Pop</option>
			<option value="RDCLAK5uy_lD3-8XG2-GhwLbOW610CVEKYTC0mmMwus">Singles Party</option>
			<option value="RDCLAK5uy_l63FFr2xGnXGVPfOWTNtAirWzsmZW_fBU">Trap Party</option>
			<option value="RDCLAK5uy_n-msvjTNXhvt7TZEF49VVZtGMjP3GHQ30">Upbeat Indie Pop</option>
			<option value="RDCLAK5uy_n1pCtKr6ApgDMdzsgxfxN6TpW6mdAPy-U">Wedding Classics</option>
			<optgroup label="————————————————"></optgroup>
			<optgroup label="🗺 Countries"></optgroup>
			<optgroup label="————————————————"></optgroup>
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
			<optgroup label="————————————————"></optgroup>
			<optgroup label="♫ Specialists"></optgroup>
			<optgroup label="————————————————"></optgroup>
			<option value="PL0KrRV0rbVN-21saESr1SBcKls4kZy09W">Global Trax</option>
			<option value="PL0KrRV0rbVN-Rnulv8HWqd2v-IWQ7gMUs">Hebrew Songs</option>
			<option value="PL0KrRV0rbVN_xUARb6NIifWlMcLsJSmDo">Turkish Songs</option>
			<option value="PL0KrRV0rbVN_2obINsiQO7xAa8x1vA1TS">Oldies but Goldies</option>
		</select>
		<main>
			<nav>
				<i class="material-icons prev">skip_previous</i>
				<i class="material-icons play">pause</i>
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

<?php } else echo '<form method="post"><input type="password" name="password"/></form>'; ?>
