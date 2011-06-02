$(document).ready(function(){	
	////////////////////////////////////////////
	//Tweets Rotator
	
	$('#twitter_update_list').show();
	
	var tweets = $('#twitter_update_list');
	
	tweets.children('li:not(:first)').hide();
				
	setInterval(function() {
	    tweets.children('li:visible').fadeOut(200, function() {
	    	$(this).index() === $(this).parent().children().length - 1
	    	? $(this).parent().children('li').eq(0).fadeIn(600)
	    	: $(this).next().fadeIn(600);
	    });
	}, 4500);
				
	////////////////////////////////////////////
	
	
	////////////////////////////////////////////
	//Input Placeholder
	$("#email").evoPlaceholder("type your email address");
	
	////////////////////////////////////////////
	
	////////////////////////////////////////////
	// Ajax Subscription
	$('#subscribe_form').submit(function(){
	
		var action = $(this).attr('action');
		
		$('#subscribe_btn').attr('disabled','disabled');
		$('#subscribe_form img.loader').show();		
		
		$.post(action, {
			email: $('#email').val()
		},
			function(data){
				$('#email').hide();
				$('#email').val(data);
				$('#email').fadeIn(800);
				$('#subscribe_form img.loader').fadeOut('fast',function(){$(this).hide()});
				$('#subscribe_form #subscribe_btn').attr('disabled',''); 
				
			});
		
		return false; 
	
	});	

});
//EvoGraphics Placeholder Plugin
//Copyright - All right reserved
$.fn.evoPlaceholder = function(placeholder){
	var element = this.eq(0);
	
	element.focus(function(){
		if(this.value == placeholder) this.value='';
	}).blur(function(){
		if(this.value.length == 0) this.value= placeholder;
		return(false);
	});
	
	return element.blur();
}