			$(function(){
				var track = $('audio').attr('data-track'), audio = $('audio')[0];
				play(track);
				function next() {
					$.ajax({
						data: 'r=' + track,
						dataType: 'json',
						beforeSend: function() {
							$('body').css('background-image', 'url(https://cdn.dribbble.com/users/563824/screenshots/3633228/untitled-5.gif)');
							$('.fa-forward').removeClass('fa-forward').addClass('fa-spinner fa-spin');
						},
						success: function(data) {
							$('audio').attr('data-track', data.track);
							$('h3').text(data.title);
							$(document).attr('title', data.title);
							play(data.track);
							$('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-forward');
						}
					});
				}
				function play(id) {
					$('audio').attr('src', 'https://invidio.us/latest_version?itag=251&local=true&id=' + id);
					audio.play();
					bg(id);
				}
				function bg(id) {
					var hd = 'https://i.ytimg.com/vi/' + id + '/maxresdefault.jpg',
						hq = 'https://i.ytimg.com/vi/' + id + '/hqdefault.jpg';
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
					if (!audio.playing) audio.play();
				}
				$('audio').on('ended', function() {
					if ($('.fa-history').length) {
						audio.play();
						$('.fa-history').removeClass('fa-history').addClass('fa-sync-alt fa-spin');
						setTimeout(function(){$('.fa-sync-alt').removeClass('fa-sync-alt fa-spin').addClass('fa-history');}, 500);
					}
					else next();
				});
				$('audio').on('play', function() {
					$('.fa-play').removeClass('fa-play').addClass('fa-pause');
				});
				$('audio').on('pause', function() {
					$('.fa-pause').removeClass('fa-pause').addClass('fa-play');
				});
				$(document).on('click', '.fa-pause', function() {
					$(this).removeClass('fa-pause').addClass('fa-play');
					audio.pause();
				});
				$(document).on('click', '.fa-play', function() {
					$(this).removeClass('fa-play').addClass('fa-pause');
					audio.play();
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
