$(document).ready(function(){

	var MaxInputs       = 8; //maximum input boxes allowed
	var InputsWrapper   = $("#aliases"); //Input boxes wrapper ID
	var AddButton       = $("#addAlias"); //Add button ID
	var SubmitButton       = $("#storePlace"); //Add button ID

	$(SubmitButton).click(function (e) {
		if(!$('#place_name').val()) {
			e.preventDefault();
			$('#mainContent').append('<div id="flash_error" class="alert alert-danger">You have to add at least a name.</div>');
		}
	});


	var x = InputsWrapper.length; //initlal text box count
	var FieldCount = 0; //to keep track of text box added

	$(AddButton).click(function (e)  //on add input button click
	{
		e.preventDefault();
		if(x <= MaxInputs) //max input box allowed
		{
			FieldCount++; //text box added increment
			//add input box
			$(InputsWrapper).append('<span class="alias"><input type="text" name="aliases[]" id="field_'+ FieldCount +'" value="Text '+ FieldCount +'"/><a href="#" class="removeclass">&times;</a></span>');
			x++; //text box increment
		}
		return false;
	});

// 	$("body").on("click",".removeclass", function(e){ //user click on remove text
// 		if( x > 1 ) {
// 			$(this).parent('div').remove(); //remove text box
// 			x--; //decrement textbox
// 		}
// 		return false;
// 	})



});
