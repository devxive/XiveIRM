$('#go').keyup(function() {
	var search_query = $(this).val();
	
	$.post('search.php', {search_query : search_query}, function(searchq) {
		$('#search_query').html(searchq);
	});
});