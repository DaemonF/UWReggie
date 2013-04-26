var actionToNum = {'login': 0, 'signup': 1, 'lostPass': 2};

function validate(textBox, regex){
	if(textBox.inputIsEmpty() || !regex.test(textBox.val())){
		textBox.addClass("error");
		return false;
	}
	return true;
}

function showForm(action){
	switch(actionToNum[action]){
		case 0:
			$('#pass').show(200);
			$('#signUp').hide(200);
			$('#signUpLink').show();
			$('#signUpLink2').hide();
			$('#logInLink').hide();
			$('#lostPassLink').show();
			$('#submit').html("Log In");
			break;
		case 1:
			$('#pass').show(200);
			$('#signUp').show(200);
			$('#signUpLink').hide();
			$('#signUpLink2').hide();
			$('#logInLink').show();
			$('#lostPassLink').show();
			$('#submit').html("Sign Up");
			break;
		case 2:
			$('#pass').hide(200);
			$('#signUp').hide(200);
			$('#signUpLink').hide();
			$('#signUpLink2').show();
			$('#logInLink').show();
			$('#lostPassLink').hide();
			$('#submit').html("Retrieve Password");
			break;
	}
	$('[name="action"]').attr("value",action);
	$('.error').removeClass("error");
	$('#errorText').html("");
}

$(function() {	
	$('[name="phone"]').mask("(999) 999-9999", {placeholder:"  "});
	
	$(':text,:password').focus(function(){
		$(this).removeClass("error");
	});

	/*$('.alertCancel').click(function(){
		return confirm("Are you sure you want to delete this alert?");
	});*/

	$('.checkAll').click(function () {
		$(this).closest('form').find(':checkbox').attr('checked', this.checked);
	});

	$('#loginForm').submit(function(){
		var action = $('[name="action"]');
		var email = $('[name="email"]');
		var pass = $('[name="password"]');
		var pass2 = $('[name="passwordAgain"]');
		//var phone = $('[name="phone"]');
		$('.error').removeClass("error");
		var error = "";
		
		switch(actionToNum[action.val()]){
			case 1:
				//error = (validate(phone, /^\([0-9]{3}\) [0-9]{3}\-[0-9]{4}$/) ? "" : "Invalid phone number. ") + error;
				var pa2 = false;
				pass.val() == pass2.val() ? pa2 = true :	pass2.addClass("error");
				error = (pa2 ? "" : "Passwords dont match. ") + error;
			case 0:
				error = (validate(pass, /^.{6,}$/) ? "" : "Password must be more than 6 characters. ") + error;
			case 2:
				error = (validate(email, /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/) ? "" : "Invalid email address. ") + error;
		}
		$('#errorText').html(error);
		if(error != "")
			return false;
		return true;
	});
});
