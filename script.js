			$(function(){
				$('ul').slideUp();
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
					$('h5 span').slideToggle(200);
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
							$('h5 span').slideToggle(200);
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
				$('audio').on('error', function() {
					var request = '/latest_version?local=true&itag=251&id=' + $('li.active').attr('id');
					if ($('audio[src*="wxzm.sx"]').length) {
						$('audio').attr('src', 'https://invidious.enkirton.net' + request);
						audio.pause();
						audio.load();
						audio.oncanplaythrough = audio.play();
					}
					else if ($('audio[src*="enkirton.net"]').length) {
						$('audio').attr('src', 'https://tube.poal.co' + request);
						audio.pause();
						audio.load();
						audio.oncanplaythrough = audio.play();
					}
					else if ($('audio[src*="poal.co"]').length) {
						$('audio').attr('src', 'https://invidiou.sh' + request);
						audio.pause();
						audio.load();
						audio.oncanplaythrough = audio.play();
					}
					else if ($('audio[src*="invidiou.sh"]').length) {
						$('audio').attr('src', 'https://invidious.snopyta.org' + request);
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
					$('audio').attr('src', 'https://vid.wxzm.sx/latest_version?local=true&itag=251&id=' + id);
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
