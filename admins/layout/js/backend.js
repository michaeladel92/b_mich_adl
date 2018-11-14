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


	//Convert password field to text field on Havar
	var passField = $('.password');
	$('.show-pass').hover(function(){
			passField.attr('type','text');
	},function(){
			passField.attr('type','password');
		});



	//Confirmation Message on Hfref
	$('.confirm').click( function(){

			return confirm('Are You Sure you?');
	});


	//Category Show Option Span
	$('.cat h3').click(function(){

			$(this).next('.full-view').fadeToggle(200);
	});

	$('.option span').click(function(){

			$(this).addClass('active').siblings('span').removeClass('active');	
			
			if ($(this).data('view') == 'full') {

						$('.cat .full-view').fadeIn(200);
			}else{

						$('.cat .full-view').fadeOut(200);

			}
	});

	

	//Activate Plugin The Selectboxit
	$('select').selectBoxIt();


	//Dashboard selector User [add selected class, go parent ,next of parent, fade]
	$('.toggle_info').click(function(){

			$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

			if ($(this).hasClass('selected')) {

				$(this).html('<i class="fa fa-minus fa-lg"></i>');
			}else{
				$(this).html('<i class="fa fa-plus fa-lg"></i>');

			}
	});



		//show delete child chategory
		$('.child_category').hover(function(){

					 $(this).find('.show-delete').fadeIn();

		}, function(){

					$(this).find('.show-delete').fadeOut();
		});


});