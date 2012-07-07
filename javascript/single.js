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
				  $('#language_change').html(data);
					$('#language_change').show();
				// alert('Load was performed.');
			});
		
		});
		
		$('.learn_btn').click(function(){
		
			var word_id = $(this).attr('id').substring(4);
			$.post("ajax/learn_word.php",{id:word_id},function(result){
				$("span").html(result);
			  });
		
		});
	});