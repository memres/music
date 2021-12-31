$(function() {
	$('[name="playlist"]').val(Cookies.get('playlist'));
	if (Cookies.get('shuffle')) $('.shuffle').addClass('on');
	var folder = window.location.pathname.split('/').slice(0, -1).join('/'),
	audio = $('audio')[0],
	track = Cookies.get('shuffle') ? randomize() : 0,
	trax = [track],
	timeout;
	play(track);
	$('.play').on('click', playpause);
	$('.next').on('click', next);
	$('.prev').on('click', prev);
	$('.queue').on('click', queue);
	$('.shuffle').on('click', shuffle);
	$('.launch').on('click', launch);
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
		$('header').stop(true, true).show().html('<i class="material-icons">'+(audio.volume == 0 || audio.muted ? 'volume_off' : (audio.volume <= 0.5 ? 'volume_down' : 'volume_up'))+'</i> '+(audio.muted ? '0' : Math.round(audio.volume * 100)));
		timeout = setTimeout(function() {
			$('header').fadeOut();
		}, 1000);
	});
	$('audio').on('loadedmetadata', function() {
		$('.total').text(calc(audio.duration));
	});
	$('audio').on('progress', function() {
		if (audio.duration > 0) {
			for (let i = 0; i < audio.buffered.length; i++) {
				if (audio.buffered.start(audio.buffered.length - 1 - i) < audio.currentTime) {
					$('.buffer').css('width', (audio.buffered.end(audio.buffered.length - 1 - i) / audio.duration) * 100+'%');
					break;
				}
			}
		}
	});
	$('audio').on('timeupdate', function() {
		$('.current').text(calc(audio.currentTime, true));
		$('.progress').css('width', ((audio.currentTime / audio.duration) * 100)+'%');
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
	$('ul').sortable({
		handle: 'img',
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
		slide: function(i, e) {
			audio.currentTime = (audio.duration / 100) * e.value;
		}
	});
	$('.ui-slider-handle').off('keydown');
	$(window).on('keydown', function(e) {
		if (e.which == 32) return false;
		if (e.which == 37) backward();
		if (e.which == 39) forward();
		if (e.which == 38 && audio.volume < 1) audio.volume = (Math.round(audio.volume * 100) + 5) / 100;
		if (e.which == 40 && audio.volume > 0) audio.volume = (Math.round(audio.volume * 100) - 5) / 100;
	});
	$(window).on('keyup', function(e) {
		if (e.which == 32) playpause();
		if (e.which == 13 || e.which == 78) next();
		if (e.which == 80) prev();
		if (e.which == 76) $('.loop').toggleClass('on');
		if (e.which == 83) shuffle();
		if (e.which == 81) queue();
		if (e.which == 79) launch();
		if (e.which == 77) audio.muted = !audio.muted;
		if (e.which == 48 || e.which == 96) audio.currentTime = 0;
		if (e.which == 49 || e.which == 97) audio.currentTime = .1 * audio.duration;
		if (e.which == 50 || e.which == 98) audio.currentTime = .2 * audio.duration;
		if (e.which == 51 || e.which == 99) audio.currentTime = .3 * audio.duration;
		if (e.which == 52 || e.which == 100) audio.currentTime = .4 * audio.duration;
		if (e.which == 53 || e.which == 101) audio.currentTime = .5 * audio.duration;
		if (e.which == 54 || e.which == 102) audio.currentTime = .6 * audio.duration;
		if (e.which == 55 || e.which == 103) audio.currentTime = .7 * audio.duration;
		if (e.which == 56 || e.which == 104) audio.currentTime = .8 * audio.duration;
		if (e.which == 57 || e.which == 105) audio.currentTime = .9 * audio.duration;
	});
	function playpause() {
		audio.paused ? audio.play() : audio.pause();
	}
	function forward() {
		audio.currentTime = Math.min(audio.currentTime + 3, audio.duration);
	}
	function backward() {
		audio.currentTime = Math.max(audio.currentTime - 3, 0);
	}
	function next() {
		if ($('li').not('.ok').length) {
			if ($('.shuffle').hasClass('on')) track = randomize();
			else if (track == $('li').last().index()) track = $('li').not('.ok').first().index();
			else track++;
			play(track);
			trax.push(track);
		}
	}
	function prev() {
		if (trax.length > 1) {
			trax.pop();
			track = trax[trax.length - 1];
			play(track);
		}
	}
	function shuffle() {
		$('.shuffle').toggleClass('on');
		$('.shuffle').hasClass('on') ? Cookies.set('shuffle', 1, {expires: 365, path: folder}) : Cookies.remove('shuffle', {path: folder});
	}
	function launch() {
		if (!audio.paused) audio.pause();
		window.open('https://invidious.snopyta.org/latest_version?local=true&itag=251&id='+$('li.on').attr('id'), '_blank');	
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
	function randomize() {
		let num = ~~(Math.random() * $('li').length);
		if ($('li').eq(num).hasClass('ok')) return randomize();
		else return num;
	}
	function calc(d, c) {
		let s = ~~(c ? d % 60 : d % 3600 % 60);
		return ~~(d % 3600 / 60)+':'+(s < 10 ? '0'+s : s);
	}
	function play(n) {
		let li = $('li').eq(n),
		tt = li.text(),
		id = li.attr('id'),
		im = 'https://i.ytimg.com/vi/'+id,
		hd = im+'/maxresdefault.jpg',
		hq = im+'/hqdefault.jpg';
		//
		if ($('li').length == $('li.ok').length) $('li').removeClass('ok');
		$('li.on').removeClass('on');
		li.addClass('ok on');
		//
		$('h1.elli').removeClass('elli psis');
		$('h1').text(tt);
		if ($('h1').height() > 39) $('h1').addClass('elli psis');
		$(document).attr('title', tt);
		//
		$('time').text('0:00');
		$('figcaption').css('width', '0');
		$('.total').text(li.children('img').attr('alt'));
		//
		if ($('ul').is(':visible')) $('ul').animate({scrollTop: li.position().top - $('li').first().position().top});
		else $('ul').slideDown().animate({scrollTop: li.position().top - $('li').first().position().top}).slideUp();
		//
		audio.pause();
		audio.currentTime = 0;
		$.get('api.php?v='+id, function(e) {
			if (e) {
				$('audio').html(e);
				audio.load();
				audio.play();
			}
			else next();
		});
		//
		if ('mediaSession' in navigator) {
			navigator.mediaSession.metadata = new MediaMetadata({
				title: li.children('bdo').text(),
				artist: li.children('bdi').text(),
				artwork: [{
					src: im+'/mqdefault.jpg',
					sizes: '320x180',
					type: 'image/jpg'
				}]
			});
			navigator.mediaSession.setActionHandler('nexttrack', next);
			navigator.mediaSession.setActionHandler('previoustrack', prev);
			navigator.mediaSession.setActionHandler('seekforward', forward);
			navigator.mediaSession.setActionHandler('seekbackward', backward);
		}
		//
		$.ajax({
			url: 'https://images'+~~(Math.random() * 33)+'-focus-opensocial.googleusercontent.com/gadgets/proxy?container=none&url='+encodeURIComponent(hd),
			type: 'HEAD',
			success: function() {
				$('div img').attr('src', hd).on('load', function() {
					$(this).parent().css('background-image', 'url('+hd+')');
				});
			},
			error: function() {
				$('div img').attr('src', hq).on('load', function() {
					$(this).parent().css('background-image', 'url('+hq+')');
				});
			}
		});
	}
});
