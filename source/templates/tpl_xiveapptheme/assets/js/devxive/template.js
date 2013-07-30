// Fix Dropdown input problem
jQuery(function() {
	$(".dropdown-menu input, .dropdown-menu label").click(function(e) {
		e.stopPropagation();
	});
});
