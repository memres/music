$(function() {
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		$('h1').text('Shake your device for next track.');
		var script = document.createElement('script');
		script.onload = function() {
			var shakeEvent = new Shake({threshold: 30});
			shakeEvent.start();
			window.addEventListener('shake', next, false);
		}
		script.src = 'https://cdn.jsdelivr.net/npm/shake.js@1.2.2/shake.min.js';
		document.head.appendChild(script);
	}
	$('[name="chart"]').val(Cookies.get('chart'));
	$('[name="country"]').val(Cookies.get('country'));
	if (Cookies.get('shuffle')) $('.shuffle').addClass('on');
	var folder = window.location.pathname.split('/').slice(0, -1).join('/'),
	itag = /iPhone|iPad|iPod/i.test(navigator.userAgent) ? 140 : 251,
	audio = $('audio')[0],
	track = Cookies.get('shuffle') ? randomize() : 0,
	trax = [track],
	timeout;
	if (itag == 140) $('.download').remove();
	play(track);
	$('.play').on('click', playpause);
	$('.next').on('click', next);
	$('.prev').on('click', prev);
	$('.queue').on('click', queue);
	$('.shuffle').on('click', shuffle);
	$('.download').on('click', download);
	$('select').on('change', function() {
		Cookies.set($(this).attr('name'), this.value, {expires: 365, path: folder});
		location.reload();
	});
	$(document).on('click', '.elli', function() {
		$(this).toggleClass('psis');
	});
	$('.loop').on('click', function() {
		$(this).toggleClass('on');
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
	$('audio').on('volumechange', function() {
		clearTimeout(timeout);
		$('header').stop(true, true).show().find('i').text(audio.volume == 0 ? 'volume_off' : (audio.volume <= 0.5 ? 'volume_down' : 'volume_up')).next().text(Math.round(audio.volume * 100));
		timeout = setTimeout(function() {
			$('header').fadeOut();
		}, 1000);
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
	$('audio').on('error', function() {
		if ($('audio[src*="invidio.us"]').length) {
			audio.src = 'https://invidious.snopyta.org/latest_version?local=true&itag=' + itag + '&id=' + $('li.on').attr('id');
			audio.pause();
			audio.load();
			audio.play();
		}
		else {
			$('li.on').remove();
			if ($('.shuffle').hasClass('on')) track = randomize();
			else if (track == $('li:last-child').index()) track = 0;
			play(track);
		}
	});
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
		audio.src = 'https://invidio.us/latest_version?local=true&itag=' + itag + '&id=' + id;
		audio.pause();
		audio.load();
		audio.play();
	}
	function calc(d, c) {
		var hour = parseInt(d / 3600),
		min = parseInt((d / 60) % 60),
		sec = parseInt(d - ((hour * 3600) + (min * 60)));
		if (c) sec = parseInt(d % 60);
		return (hour ? hour + ':' : '') + (hour && min < 10 ? '0' + min : min) + ':' + (sec < 10 ? '0' + sec : sec);
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
	function playpause() {
		audio.paused ? audio.play() : audio.pause();
	}
	function shuffle() {
		$('.shuffle').toggleClass('on');
		$('.shuffle').hasClass('on') ? Cookies.set('shuffle', 1, {expires: 365, path: folder}) : Cookies.remove('shuffle', {path: folder});
	}
	function download() {
		window.location.href = 'https://alltube.drycat.fr/download?audio=on&url=http%3A%2F%2Fyoutu.be%2F' + $('li.on').attr('id');
	}
	function queue() {
		let h1 = $('li.on').text();
		if ($('.queue').hasClass('on')) {
			$('.queue').removeClass('on');
			$('h1').text(h1);
			$('ul').slideUp();
		}
		else {
			$('.queue').addClass('on');
			$('h1').text('QUEUE');
			$('ul').slideDown();
			$('.elli').addClass('psis');
		}
	}
	$('ul').sortable({
		handle: 'span',
		update: function() {
			if (Cookies.get('shuffle')) shuffle();
			track = $('li.on').index();
			trax = [track];
		}
	});
	$('ul').disableSelection();
	$('figure').slider({
		step: .01,
		value: audio.currentTime,
		slide: function(event, ui) {
			audio.currentTime = (audio.duration / 100) * ui.value;
		}
	});
	$('.ui-slider-handle').off('keydown');
	$(window).on('keydown', function(event) {
		if (event.which == 37) audio.currentTime = audio.currentTime - 3;
		if (event.which == 39) audio.currentTime = audio.currentTime + 3;
		if (event.which == 38 && audio.volume < 1) audio.volume = (Math.round(audio.volume * 100) / 100) + 0.05;
		if (event.which == 40 && audio.volume > 0) audio.volume = (Math.round(audio.volume * 100) / 100) - 0.05;
	});
	$(window).on('keyup', function(event) {
		if (event.which == 32) playpause();
		if (event.which == 13) next();
		if (event.which == 80) prev();
		if (event.which == 76) $('.loop').toggleClass('on');
		if (event.which == 83) shuffle();
		if (event.which == 81) queue();
		if (event.which == 68 && itag == 251) download();
	});
});
