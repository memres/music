var callback;
$(function() {
	$('input[type="search"]').autocomplete({
		appendTo: 'article',
		autoFocus: true,
		source: function(request, response) {
			$.getJSON('https://www.google.com/complete/search?callback=?', {
				'hl': 'en',
				'ds': 'yt',
				'client': 'youtube',
				'jsonp': 'callback',
				'q': request.term
			});
			callback = function(data) {
				var suggestions = [];
				$.each(data[1], function(key, val) {
					suggestions.push({
						'value': val[0]
					});
				});
				suggestions.length = 5;
				response(suggestions);
			};
		},
		select: function(event, ui) {
			rotate();
			location.href = '?q=' + encodeURIComponent(ui.item.value).replace(/%20/g, '+');
		}
	});
	$('form').on('submit', rotate);
	function rotate() {
		$('button[type="submit"] i').css('animation', 'rotation 2s infinite linear');
	}
});
