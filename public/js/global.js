$(document).ready(function() {
	var closeButton = $('.flash-close');

	$(closeButton).click(function(e){
		$(closeButton).parent().remove();
	});
});
