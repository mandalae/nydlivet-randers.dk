var page = 1;
$(document).ready(function(){
	UpdatePortfolio()
	
	if (typeof(offset) != 'undefined'){
		getNextRow();
	}
	
	$(".arrow_right").bind('click', function(e){
		getNext();
		e.preventDefault();
	});
	
	$(".arrow_left").bind('click', function(e){
		getPrev();
		e.preventDefault();
	});
	
});

function UpdatePortfolio(){
	$(".portfolio_thumbs .image").unbind('click').bind('click', function(e){
		var image_id = $(this).attr('id').substring(4);
		if ($("#big_" + image_id)){
			$(".portfolio .current").hide();
			$(".portfolio .current").removeClass('current');
			$("#big_" + image_id).fadeIn('fast', function(){
				$(this).addClass('current'); 
			});
			$(".portfolio_thumbs .current").removeClass('current');
			$("#img_" + image_id).addClass('current');
		} else {
			$.get('/getimage', {'id':image_id, 'width':500, 'height':394, 'type':'src'}, function(html){
				if (html != ''){
					$(".portfolio .current").attr('src', html);
				}
			});
		}
		e.preventDefault();
	});
	
	$("#portfoliotop .image").unbind('click').bind('click', function(e){
		selectNextSibling();
	});
}

function getNextRow(){
	offset = offset+9;
	$.get('/commissions/getimages', {'gallery':gallery, 'offset':offset}, function(xml){
		if ($("response image", xml).size() > 0){
			expandSlider();
			$("response image", xml).each(function(){
				$(".portfolio_thumbs .thumb_slider").append('<a href="/commissions/'+seo+'/'+$(this).attr('id')+'" class="image" id="img_'+$(this).attr('id')+'"><img src="'+$(this).attr('thumb')+'" width="75" height="50" /></a>');
				$("#portfoliotop").append('<div class="imageContainer"><div id="big_'+$(this).attr('id')+'" class="image"><img src="'+$(this).text()+'" height="394" /></div></div>');
				UpdatePortfolio();
			});
		} else {
			offset = offset-9;
		}
	}, 'xml');
}

function expandSlider(){
	var current_width = parseInt($(".portfolio_thumbs .thumb_slider").css('width').substring(-2));
	$(".portfolio_thumbs .thumb_slider").css('width', (current_width+800));
}

function getNext(){
	$(".portfolio_thumbs").scrollTo({left:'+=800'}, 800, {axis:'x'});
	getNextRow();
	page++;
}

function getPrev(){
	$(".portfolio_thumbs").scrollTo({left:'-=800'}, 800, {axis:'x'});
	page--;
}

function reset(){
	$(".portfolio_thumbs").scrollTo({left:0}, 800, {axis:'x'});
	page = 1;
}

function selectNextSibling(){
	var $next = $($(".portfolio .current").parent('.imageContainer').next()[0]).find('.image');
	$(".portfolio .current").hide().removeClass('current');
	var image_id = $($next[0]).attr('id').substring(4);
	$("#portfoliotop .image").unbind('click');
	$($next[0]).fadeIn('fast', function(){
		$(this).addClass('current');
		UpdatePortfolio();
	});
	$(".portfolio_thumbs .current").removeClass('current');
	$("#img_" + image_id).addClass('current');
	var index = $('.imageContainer').index($($next).parent());
	if (parseInt(index)+1 > (parseInt(page)*9)){
		getNext();
	}
	if ((parseInt(index)+1 < ((parseInt(page)-1)*9)) && page > 1){
		reset();
	}
}