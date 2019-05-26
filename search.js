var callback, rotate = 'rotation 2s infinite linear';
$(function() {
	$('input[type="search"]').autocomplete({
		appendTo: 'article',
		autoFocus: true,
		source: function(request, response) {
			$.getJSON('https://suggestqueries.google.com/complete/search?callback=?', {
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
			$('button[type="submit"] i').css('animation', rotate);
			location.href = '?q=' + encodeURI(ui.item.value).replace(/%20/g, '+');
		}
	});
	$('form').on('submit', function() {
		$('button[type="submit"] i').css('animation', rotate);
	});
});
