$(document).ready(function(){
	
		var inpVal = '';
		
		$('#right_content').masonry({
		  itemSelector: '.box',
		  animate:true
		});
		
		$(window).bind("resize", function(){ $('#right_content').masonry().reload(); });
		
		$('.text_box').focus(function(){
		
			inpVal = $(this).val();
			$(this).val('');
			$(this).removeClass('em');
		
		});
		$('.text_box').blur(function(){
		
			if($(this).val() == ''){
				$(this).val(inpVal);
				$(this).addClass('em');
			}
		
		});
		
		$('#change_language_btn').click(function(){
			$.get('ajax/language.php', function(data) {
				  $.modal(data);
				// alert('Load was performed.');
			});
		
		});
		
	});