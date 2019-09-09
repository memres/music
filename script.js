$(function() {
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		$('h1').html('Shake your device for next track.');
		var script = document.createElement('script');
		script.src = 'https://cdn.jsdelivr.net/npm/shake.js@1.2.2/shake.min.js';
		document.head.appendChild(script);
		script.onload = function() {
			var shakeEvent = new Shake({threshold: 23});
			shakeEvent.start();
			window.addEventListener('shake', next, false);
		}
	}
	var folder = window.location.pathname.split('/').slice(0, -1).join('/'),
	audio = $('audio')[0],
	track = Cookies.get('shuffle') ? randomize() : 0,
	trax = [track],
	timeout;
	play(track);
	$('select').on('change', function() {
		Cookies.set($(this).attr('name'), this.value, {expires: 365, path: folder});
		location.reload();
	});
	$('.prev').on('click', prev);
	$('.next').on('click', next);
	$('.play').on('click', function() {
		audio.paused ? audio.play() : audio.pause();
	});
	$(document).on('click', '.elli', function() {
		$(this).toggleClass('psis');
	});
	$('.loop, .shuffle').on('click', function() {
		$(this).toggleClass('on');
	});
	$('.shuffle').on('click', function() {
		$(this).hasClass('on') ? Cookies.set('shuffle', 1, {expires: 365, path: folder}) : Cookies.remove('shuffle', {path: folder});
	});
	$('.queue').on('click', function() {
		let h1 = $('li.on').text();
		if ($(this).hasClass('on')) {
			$(this).removeClass('on');
			$('h1').text(h1);
			$('ul').slideUp();
		}
		else {
			$(this).addClass('on');
			$('h1').text('QUEUE');
			$('ul').slideDown();
			$('.elli').addClass('psis');
		}
	});
	$('li').on('click', function() {
		if ($(this).hasClass('on')) {
			audio.currentTime = 0;
			audio.play();
		}
		else {
			track = $(this).index();
			play(track);
			trax.push(track);
		}
	});
	$('figure').slider({
		value: audio.currentTime,
		slide: function(event, ui) {
			audio.currentTime = (audio.duration / 100) * ui.value;
		}
	});
	$('.ui-slider-handle').off('keydown');
	$(window).on('keydown', function(event) {
		if ($(event.target).is('INPUT')) return;
		if (event.which == 37) audio.currentTime = audio.currentTime - 3;
		if (event.which == 39) audio.currentTime = audio.currentTime + 3;
		if (event.which == 38 && audio.volume < 1) audio.volume = (Math.round(audio.volume * 100) / 100) + 0.05;
		if (event.which == 40 && audio.volume > 0) audio.volume = (Math.round(audio.volume * 100) / 100) - 0.05;
	});
	$(window).on('keyup', function(event) {
		if ($(event.target).is('INPUT')) return;
		if (event.which == 32) audio.paused ? audio.play() : audio.pause();
		if (event.which == 13) next();
		if (event.which == 80) prev();
		if (event.which == 76) $('.loop').toggleClass('on');
		if (event.which == 83) {
			$('.shuffle').toggleClass('on');
			$('.shuffle').hasClass('on') ? Cookies.set('shuffle', 1, {expires: 365, path: folder}) : Cookies.remove('shuffle', {path: folder});
		}
		if (event.which == 81) {
			let h1 = $('li.on').text();
			if ($('.queue').hasClass('on')) {
				$('.queue').removeClass('on');
				$('h1').html(h1);
				$('ul').slideUp();
			}
			else {
				$('.queue').addClass('on');
				$('h1').html('Queue');
				$('ul').slideDown();
				$('.elli').addClass('psis');
			}
		}
	});
	$('audio').on('volumechange', function() {
		clearTimeout(timeout);
		$('header').stop(true, true).show().find('i').text(audio.volume == 0 ? 'volume_off' : (audio.volume <= 0.5 ? 'volume_down' : 'volume_up')).next().text(Math.round(audio.volume * 100));
		timeout = setTimeout(function() {
			$('header').fadeOut();
		}, 1000);
	});
	$('audio').on('error', function() {
		var id = $('li.on').attr('id');
		if ($('audio[src*="de.invidious"]').length) {
			audio.src = 'https://fi.invidious.snopyta.org/latest_version?local=true&itag=140&id=' + id;
			audio.pause();
			audio.load();
			audio.play();
		}
		else if ($('audio[src*="fi.invidious"]').length) {
			audio.src = 'https://invidious.13ad.de/latest_version?local=true&itag=251&id=' + id;
			audio.pause();
			audio.load();
			audio.play();
		}
		else {
			$('li.on').remove();
			if ($('.shuffle').hasClass('on')) track = randomize();
			if (track == $('li:last-child').index()) track = 0;
			play(track);
		}
	});
	$('audio').on('loadedmetadata', function() {
		$('.total').text(calc(audio.duration));
		if ($('ul').is(':visible')) $('ul').animate({scrollTop: $('li.on').position().top - $('li').first().position().top});
		else $('ul').slideDown().animate({scrollTop: $('li.on').position().top - $('li').first().position().top}).slideUp();
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
		$('.progress').css('width', ((audio.currentTime / audio.duration) * 100) + '%');
	});
	$('audio').on('play', function() {
		$('.play').text('pause');
	});
	$('audio').on('pause', function() {
		$('.play').text('play_arrow');
	});
	$('audio').on('ended', function() {
		if ($('.loop').hasClass('on')) audio.play();
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
		var li = $('li:eq(' + n + ')'),
		title = li.text(),
		id = li.attr('id'),
		ytimg = 'https://i.ytimg.com/vi/' + id;
		//
		$('h1.elli').removeClass('elli psis');
		$('h1').text(title);
		if ($('h1').height() > 39) $('h1').addClass('elli psis');
		$(document).attr('title', title);
		//
		$('li.on').removeClass('on');
		li.addClass('ok on');
		//
		$.ajax({
			url: 'https://images' + ~~(Math.random() * 33) + '-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url=' + encodeURIComponent(ytimg + '/maxresdefault.jpg'),
			type: 'HEAD',
			success: function() {
				$('body').css('background-image', 'url(' + ytimg + '/maxresdefault.jpg)');
			},
			error: function() {
				$('body').css('background-image', 'url(' + ytimg + '/hqdefault.jpg)');
			}
		});
		//
		audio.src = 'https://de.invidious.snopyta.org/latest_version?local=true&itag=251&id=' + id;
		audio.pause();
		audio.load();
		audio.play();
	}
	function next() {
		if ($('.shuffle').hasClass('on')) track = randomize();
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
		if ($('li:eq(' + num + ')').hasClass('ok')) {
			if ($('li').length == $('li.ok').length) {
				if (num == track) return randomize();
				else {
					$('li').removeClass('ok');
					return num;
				}
			}
			else return randomize();
		}
		else return num;
	}
});
