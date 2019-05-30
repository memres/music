$(function() {
	$('.playlist').slideUp();
	if ((typeof window.orientation !== 'undefined') || (navigator.userAgent.indexOf('IEMobile') !== -1)) {
		$('h5').html('<b>Shake</b> your device for next track.');
		var script = document.createElement('script');
		script.src = 'https://cdn.jsdelivr.net/npm/shake.js@1.2.2/shake.min.js';
		document.head.appendChild(script);
		script.onload = function() {
			var shakeEvent = new Shake({threshold: 17});
			shakeEvent.start();
			window.addEventListener('shake', next, false);
		}
	}
	const h5 = $('h5').html();
	var audio = $('audio')[0], track = 0, trax = [0], timeout;
	play(track);
	$(document).on('click', '.elli', function() {
		$(this).toggleClass('psis');
	});
	$(document).on('click', '.help', function() {
		var help = $(this).text();
		$(this).text(help == 'help' ? 'help_outline' : 'help');
		$('h5 span').slideToggle(200);
	});
	$('.prev').on('click', prev);
	$('.next').on('click', next);
	$('.play').on('click', function() {
		audio.paused ? audio.play() : audio.pause();
	});
	$('.loop, .shuffle').on('click', function() {
		$(this).toggleClass('on');
	});
	$('.queue').on('click', function() {
		if ($(this).hasClass('on')) {
			$(this).removeClass('on');
			$('h5').html(h5);
			$('.playlist').slideUp();
		}
		else {
			$(this).addClass('on');
			$('h5').html('<b>Queue</b>');
			$('.playlist').slideDown();
		}
	});
	$('.playlist li').on('click', function() {
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
	$('figure').on('click', function(event) {
		audio.currentTime = (event.offsetX / this.offsetWidth) * audio.duration;
	});
	$('figure').slider({
		value: audio.currentTime,
		slide: function(event, ui) {
			audio.currentTime = (audio.duration / 100) * ui.value;
		}
	});
	$('.ui-slider-handle').off('keydown');
	$(document).on('keydown', function(event) {
		if ($(event.target).is('INPUT')) return;
		if (event.which == 37) audio.currentTime = audio.currentTime - 3;
		if (event.which == 39) audio.currentTime = audio.currentTime + 3;
		if (event.which == 38) if (audio.volume < 1) audio.volume = (Math.round(audio.volume * 100) / 100) + 0.05;
		if (event.which == 40) if (audio.volume > 0) audio.volume = (Math.round(audio.volume * 100) / 100) - 0.05;
	});
	$(document).on('keyup', function(event) {
		if ($(event.target).is('INPUT')) return;
		if (event.which == 32) audio.paused ? audio.play() : audio.pause();
		if (event.which == 13) next();
		if (event.which == 80) prev();
		if (event.which == 83) $('.shuffle').toggleClass('on');
		if (event.which == 76) $('.loop').toggleClass('on');
		if (event.which == 81) {
			if ($('.queue').hasClass('on')) {
				$('.queue').removeClass('on');
				$('h5').html(h5);
				$('.playlist').slideUp();
			}
			else {
				$('.queue').addClass('on');
				$('h5').html('<b>Queue</b>');
				$('.playlist').slideDown();
			}
		}
		if (event.which == 72) {
			var help = $('.help').text();
			$('.help').text(help == 'help' ? 'help_outline' : 'help');
			$('h5 span').slideToggle(200);
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
		var request = '/latest_version?local=true&itag=140&id=' + $('.playlist li.on').attr('id');
		if ($('audio[src*="itag=251"]').length) {
			audio.src = 'https://de.invidious.snopyta.org' + request;
			audio.pause();
			audio.load();
			audio.play();
		}
		else if ($('audio[src*="de."]').length) {
			audio.src = 'https://fi.invidious.snopyta.org' + request;
			audio.pause();
			audio.load();
			audio.play();
		}
		else {
			$('.playlist li.on').remove();
			if ($('.shuffle').hasClass('on')) track = randomize();
			if (track == $('.playlist li:last-child').index()) track = 0;
			play(track);
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
		$('figure').slider('value', ~~((100 / audio.duration) * audio.currentTime));
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
		$('.progress').css('width', '0%');
		$('.current, .total').text('0:00');
		var li = $('.playlist li:eq(' + n + ')'),
		id = li.attr('id'),
		title = li.text(),
		hd = 'https://i.ytimg.com/vi/' + id + '/maxresdefault.jpg',
		hq = 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
		$('h3.elli').removeClass('elli psis');
		$('h3').text(title);
		if ($('h3').height() > 40) $('h3').addClass('elli psis');
		$(document).attr('title', title);
		$('.playlist li.on').removeClass('on');
		li.addClass('ok on');
		if ($('.playlist').is(':visible')) $('.playlist').animate({scrollTop: $('.playlist li.on').position().top - $('.playlist li').first().position().top});
		else $('.playlist').slideDown().animate({scrollTop: $('.playlist li.on').position().top - $('.playlist li').first().position().top}).slideUp();
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
		audio.src = 'https://de.invidious.snopyta.org/latest_version?local=true&itag=251&id=' + id;
		audio.pause();
		audio.load();
		audio.play();
	}
	function next() {
		if ($('.shuffle').hasClass('on')) track = randomize();
		else if (track == $('.playlist li:last-child').index()) track = 0;
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
		var num = ~~(Math.random() * $('.playlist li').length);
		if ($('.playlist li:eq(' + num + ')').hasClass('ok')) {
			if ($('.playlist li').length == $('.playlist li.ok').length) {
				if (num == track) return randomize();
				else {
					$('.playlist li').removeClass('ok');
					return num;
				}
			}
			else return randomize();
		}
		else return num;
	}
});
