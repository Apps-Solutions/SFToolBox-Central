
function check_type(){
	var type = $('#inp_contact_type').val();
	var user = $('#inp_co_us_id_user').val();
	
	if ( type == 2 ){
		$('#inp_co_us_id_user').attr('disabled', 'disabled'); 
		$('#inp_co_us_id_user').val(0);
	} else if ( user > 0 ) { 
		$('#inp_contact_type').val(1);
		$('#inp_contact_type').attr('disabled', 'disabled'); 
		$('#inp_co_us_id_user').removeAttr('disabled', 'disabled'); 
	} else {
		$('#inp_contact_type').removeAttr('disabled', 'disabled'); 
		$('#inp_co_us_id_user').removeAttr('disabled', 'disabled');  
	}
	
}
