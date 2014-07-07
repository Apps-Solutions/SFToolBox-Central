
function edit_contact( id_user ){
	if ( id_user > 0 ){
		window.location = 'index.php?command=' + cmd_frm_contact + "&u=" + id_user + '&cb=' + command;
	}
}

function edit_user( which ){
	if (which > 0){
		$.ajax({
			url: "ajax.php",
			type: "POST",
			async: false,
			data: {
		  		resource: 	'user',
		  		action: 	'get_user_info',
	    		id_user: 	which
			},
		  	dataType: "json",
		 	success: function(data) {
				if (data.success == true )  {
					var user = data.info;
					$('#inp_id_user').val( user.id_user ); 
					$('#inp_user').val( user.user ); 
					$('#inp_profile').val( user.id_profile );
					
					$('#opc_password').hide();
					
					$('#mdl_frm_user').modal('show');
				}
				else {  
					show_error( data.error );
					return false;
				}
			}
		}); 
	} else {
		clean_form();
		$('#inp_profile').val( id_profile );
		$('#opc_password').show();
	} 
}

function detail_user( which ){
	if (which > 0){
		if ( which != $('#inp_detail_id_user').val() ){
			$.ajax({
				url: "ajax.php",
				type: "POST",
				async: false,
				data: {
			  		resource: 	'user',
			  		action: 	'get_user_info_html',
		    		id_user: 	which
				},
			  	dataType: "json",
			 	success: function(data) {
					if (data.success == true )  {
						var html = data.html;
						$('#inp_detail_id_user').val( which );
						$('#detail_user_content').html( html );
						
						$('#mdl_detail_user').modal('show');
					}
					else {  
						show_error( data.error );
						return false;
					}
				}
			}); 
		} else {
			$('#mdl_detail_user').modal('show');
		}
	} 
}
 
function change_password_option( option ){
	if ( option == 'pwd_manual'){
		$('#div_password').show('slide', '{ direction: "down" }');
	} else { 
		$('#inp_password, #inp_password_match').val(''); 
		$('#div_password').hide('slide'); 
	}
}

function clean_form(){
	$('#inp_id_user').val(0);
	$('#inp_user').val('');
	$('#inp_profile').val(0);
	
	$('#inp_pwd_option_manual').removeAttr('checked');
	$('#inp_pwd_option_email').removeAttr('checked'); 
	$('#frm_user').get(0).reset();
}

function set_instance_option(){
	if ( $('#inp_instance_option').prop('checked') ){
		$('#div_instance').show('slide', '{ direction: "down" }'); 
	} else {
		$('#div_instance').hide('slide'); 
	}
}

function is_unique( user ){
	$.ajax({
		url: "ajax.php",
		type: "POST",
		async: false,
		data: {
	  		resource: 	'user',
	  		action: 	'is_unique_user',
    		id_user: 	$('#inp_id_user').val(), 
    		user: 		user
		},
	  	dataType: "json",
	 	success: function(data) {
			if (data.success == true )  {
				unique = data.unique;  
				return data.unique;
			}
			else {  
				return false;
			}
		}
	}); 
}

var unique;

$(document).ready(function() {
	$.formUtils.addValidator({
		name : 'password-match',
		validatorFunction : function(value, $el, config, language, $form) {
			var source = $el[0].id;
			var target = $('#' + source).attr( 'data-validation-target' );
			var compare =  $('#' + target).val();
			return ( value == compare );
		},
		errorMessage : 'Las contraseñas deben coincidir ',
		errorMessageKey: 'badPasswordMatch'
	});
	
	$.formUtils.addValidator({
		name : 'unique-user',
		validatorFunction : function(value, $el, config, language, $form) {
			if (value.length > 5){
				is_unique( value );
				return unique;
			} else 
				return true;
		},
		errorMessage : 'El usuario ya existe ',
		errorMessageKey: 'badUserUnique'
	});
	
	$.validate({
		form : '#frm_user',
		language : validate_language
	}); 
});