jQuery.fn.visibilityToggle = function() {
	return this.css('visibility', function(i, visibility) {
		return (visibility == 'visible') ? 'hidden' : 'visible';
	});
};

jQuery.fn.opacityToggle = function() {
	return this.css('opacity', function(i, opacity) {
		return (opacity > 0) ? '0' : '1';
	});
};

$(document).ready(function(){
	var hall = $("#hall-of-fame"); //Add button ID

	$(hall).click(function (e) {
		$(".chalkboard").visibilityToggle();
		$(".chalkboard").opacityToggle();
// 		$(".chalkboard").toggle();
	});

});
