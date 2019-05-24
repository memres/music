$(function() {
	$('main ul').slideUp();
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
	$('main li').on('click', function() {
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
	$('figure').on('click', function(event) {
		audio.currentTime = (event.offsetX / this.offsetWidth) * audio.duration;
	});
	$('.queue').on('click', function() {
		if ($(this).hasClass('on')) {
			$(this).removeClass('on');
			$('h5').html(h5);
			$('main ul').slideUp();
		}
		else {
			$(this).addClass('on');
			$('h5').html('<b>Queue</b>');
			$('main ul').slideDown();
		}
	});
	$('.loop, .shuffle').on('click', function() {
		$(this).toggleClass('on');
	});
	$('.play').on('click', function() {
		if (!audio.paused) audio.pause();
		else audio.play();
	});
	$('.prev').on('click', prev);
	$('.next').on('click', next);
	$(document).on('click', '.help', function() {
		var text = $(this).text();
		$(this).text(text == 'help' ? 'help_outline' : 'help');
		$('h5 span').slideToggle(200);
	});
	$(document).on('keyup', function(event) {
		if ($(event.target).is('input, main ul')) return;
		if (event.which == 32) {
			if (!audio.paused) audio.pause();
			else audio.play();
		}
		if (event.which == 13) next();
		if (event.which == 80) prev();
		if (event.which == 83) $('.shuffle').toggleClass('on');
		if (event.which == 76) $('.loop').toggleClass('on');
		if (event.which == 81) {
			if ($('.queue').hasClass('on')) {
				$('.queue').removeClass('on');
				$('h5').html(h5);
				$('main ul').slideUp();
			}
			else {
				$('.queue').addClass('on');
				$('h5').html('<b>Queue</b>');
				$('main ul').slideDown();
			}
		}
		if (event.which == 72) {
			var text = $('.help').text();
			$('.help').text(text == 'help' ? 'help_outline' : 'help');
			$('h5 span').slideToggle(200);
		}
	});
	$(document).on('keydown', function(event) {
		if ($(event.target).is('input, main ul')) return;
		if (event.which == 37) audio.currentTime = audio.currentTime - 3;
		if (event.which == 39) audio.currentTime = audio.currentTime + 3;
		if (event.which == 38) if (audio.volume < 1) audio.volume = (Math.round(audio.volume * 100) / 100) + 0.05;
		if (event.which == 40) if (audio.volume > 0) audio.volume = (Math.round(audio.volume * 100) / 100) - 0.05;
	});
	$('audio').on('volumechange', function() {
		clearTimeout(timeout);
		$('header').stop(true, true).show().find('i').text(audio.volume == 0 ? 'volume_off' : (audio.volume <= 0.5 ? 'volume_down' : 'volume_up')).next().text(Math.round(audio.volume * 100));
		timeout = setTimeout(function() {
			$('header').fadeOut();
		}, 1000);
	});
	$('audio').on('error', function() {
		var request = '/latest_version?local=true&itag=140&id=' + $('main li.active').attr('id');
		if ($('audio[src*="itag=251"]').length) {
			audio.src = 'https://vid.wxzm.sx' + request;
			audio.load();
			audio.oncanplay = audio.play();
		}
		else if ($('audio[src*="wxzm.sx"]').length) {
			audio.src = 'https://invidious.enkirton.net' + request;
			audio.load();
			audio.oncanplay = audio.play();
		}
		else {
			$('main li.active').remove();
			if ($('.shuffle').hasClass('on')) track = randomize();
			if (track == $('main li:last-child').index()) track = 0;
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
		$('.current').text(calc(audio.currentTime, true));
		$('.seek').css('width', ((audio.currentTime / audio.duration) * 100) + '%');
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
		$('.seek').css('width', '0%');
		$('.current, .total').text('0:00');
		var li = $('main li:eq(' + n + ')'),
		id = li.attr('id'),
		title = li.text(),
		hd = 'https://i.ytimg.com/vi/' + id + '/maxresdefault.jpg',
		hq = 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
		$('h3').text(title);
		$(document).attr('title', title);
		$('main li.active').removeClass('active');
		li.addClass('active played');
		if ($('main ul').is(':visible')) $('main ul').animate({scrollTop: $('main li.active').position().top - $('main li').first().position().top});
		else $('main ul').slideDown().animate({scrollTop: $('main li.active').position().top - $('main li').first().position().top}).slideUp();
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
		audio.src = 'https://vid.wxzm.sx/latest_version?local=true&itag=251&id=' + id;
		audio.load();
		audio.oncanplay = audio.play();
	}
	function next() {
		if ($('.shuffle').hasClass('on')) track = randomize();
		else if (track == $('main li:last-child').index()) track = 0;
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
		var num = ~~(Math.random() * $('main li').length);
		if ($('main li:eq(' + num + ')').hasClass('played')) {
			if ($('main li').length == $('main li.played').length) {
				if (num == track) return randomize();
				else {
					$('main li').removeClass('played');
					return num;
				}
			}
			else return randomize();
		}
		else return num;
	}
});
