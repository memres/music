$(function(){
	var id = $('body').attr('id'),
	audio = document.querySelector('audio'),
	loader = 'url(https://cdn.dribbble.com/users/563824/screenshots/3633228/untitled-5.gif)';
	play(id);
	function next() {
		$.ajax({
			data: 'r=' + id,
			dataType: 'json',
			beforeSend: function() {
				$('body').css('background-image', loader);
			},
			success: function(data) {
				$('body').attr('id', data.id);
				$('h4').text(data.title);
				$(document).attr('title', data.title);
				play(data.id);
			}
		});
	}
	function play(i) {
		$('audio').attr('src', 'https://invidio.us/latest_version?itag=251&local=true&id=' + i);
		audio.play();
		bg(i);
	}
	function bg(i) {
		var hd = 'https://i.ytimg.com/vi/' + i + '/maxresdefault.jpg',
		hq = 'https://i.ytimg.com/vi/' + i + '/hqdefault.jpg';
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
	}
	$('audio').on('ended', function() {
		if ($('.fa-history').length) audio.play();
		else next();
	});
	$(document).on('click', '.fa-home', function() {
		$(location).attr('href', '//' + window.location.hostname + window.location.pathname);
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
