var suggestCallBack;
$(function() {
	$('input').autocomplete({
		source: function(request, response) {
			$.getJSON('https://suggestqueries.google.com/complete/search?callback=?', {
				'ds': 'yt',
				'client': 'youtube',
				'jsonp': 'suggestCallBack',
				'q': request.term
			});
			suggestCallBack = function(data) {
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
	});
	$('button[type="button"]').on('click', function() {
		$(this).css('pointer-events', 'none').find('i').css('animation', 'rotation 2s infinite linear');
		$(location).attr('href', $(this).attr('value'));
	});
	$('form').on('submit', function() {
		$(this).css('pointer-events', 'none').find('i').css('animation', 'rotation 2s infinite linear');
	});
});
