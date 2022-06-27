$(document).ready(function(){
	enquire.register("screen and (max-width: 700px)", {
		match : function() {
		},  
		unmatch : function() {
			$(document).off('click', '.logo a');
		}
	});
});