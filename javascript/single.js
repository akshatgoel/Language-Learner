$(document).ready(function(){
	
		var inpVal = '';
		var is_in_popup = false;
		
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
		
		$('#language_change, #help').hover(function(){
			is_in_popup = true;
		}, function(){
			is_in_popup = false;
		});
		
		$('#change_language_btn').click(function(){
			$('.popup').hide();
			$('#overlay').show();
			$.get('ajax/language.php', function(data) {
				  $('#language_change').html(data);
					$('#overlay').hide();
					$('#language_change').show();
			});
			$(window).bind('click', function(){
				if(is_in_popup == false){ $('#language_change').hide(); }
			});
		});
		
		$('#help_btn').click(function(){
			$('.popup').hide();
			$('#help_div').show();
			$(window).bind('click', function(){
				if(is_in_popup == false){ $('#help_div').hide(); }
			});
		});
		
		$('.learn_btn').click(function(){
			var word_id = $(this).attr('id').substring(4);
			var btn = $(this);
			$('#overlay').show();
			$.post("ajax/learn_word.php",{id:word_id},function(result){  
			$('#overlay').hide();});
			btn.html('Unlearn');
			btn.removeClass('learn_btn');
			btn.addClass('unlearn_btn');
		});
		$('.unlearn_btn').click(function(){
			var word_id = $(this).attr('id').substring(4);
			var btn = $(this);
			$('#overlay').show();
			$.post("ajax/unlearn_word.php",{id:word_id},function(result){
			$('#overlay').hide();});
			btn.html('Learn');
			btn.removeClass('unlearn_btn');
			btn.addClass('learn_btn');
		});
		
		$('.content-area').jScrollPane({
			horizontalGutter:5,
			verticalGutter:5,
			'showArrows': false
		});
	
		$('.jspScrollable').mouseenter(function(){
			$('.jspDrag').stop(true, true).fadeIn('slow');
		});
		$('.jspScrollable').mouseleave(function(){
			$('.jspDrag').stop(true, true).fadeOut('slow');
		});
		
		
		
		$('.lesson_link').live('click',function(){
			var lesson = $(this).attr('title');
			$('#overlay').show();
			$.post("ajax/load_lesson.php",{lessonName: lesson},function(result){
				$('#right_content').html(result);$('#overlay').hide();
				$('#right_content').masonry().reload();
				
			});
		});
		$('.ajax_link').click(function(){
			$('#overlay').show();
			var page = $(this).attr('title');
			$.get("ajax/"+page+".php",function(result){
				$('#right_content').html(result);$('#overlay').hide();
				$('#right_content').masonry().reload();
			});
		});

	});