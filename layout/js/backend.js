$(function(){
	
	'use strict';



	//Hide Placeholder On FROM Focus
	$('[placeholder]').focus( function (){
			//it take the placeholder and copy inside data-text
			$(this).attr('data-text', $(this).attr('placeholder'));
			$(this).attr('placeholder', ''); //it take the placeholder and return empty
	
	}).blur(function(){
				//it will put in  attribute place holder what is in the data-text
			$(this).attr('placeholder', $(this).attr('data-text'));

	});


	//Add Asterik[*] On Required Field
	$('input, textarea').each(function () {
			if ($(this).attr('required') === 'required') {
				$(this).after('<span class="asterisk">*</span>');
			}
	});


	//Confirmation Message on Hfref
	$('.confirm').click( function(){

			return confirm('Are You Sure you?');
	});
	

	//Activate Plugin The Selectboxit
	$('select').selectBoxIt();




	//switch Login | signUP
	$('.login-page h1 span').click( function(){

			$(this).addClass('selected').siblings().removeClass('selected');

			$('.login-page form').hide();
			$('.' + $(this).data('class')).fadeIn(100); //get the data-class of the one i click
	});	



	//Live Prev for create new items, 
	$('.live').keyup(function(){

			//this equal data-class put in text get value
		$($(this).data('class')).text($(this).val());
	});







});