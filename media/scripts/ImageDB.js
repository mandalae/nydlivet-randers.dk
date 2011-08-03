$(document).ready(function(){
	$(".close_imagedb").bind('click', function(e){
		window.close();
		e.preventDefault();
	});
	
	$(".gallery_choose").bind('click', function(e){
		var options = {path:'/', expires:1};
		if($(this).is(':checked')){
			$.cookie('pb_gallery', $(this).val(), options);
		} else {
			$.cookie('pb_gallery', null, options);
		}
	});
	
	$(".commission_choose").bind('click', function(e){
		var options = {path:'/', expires:1};
		if($(this).is(':checked')){
			$.cookie('pb_commission', $(this).val(), options);
		} else {
			$.cookie('pb_commission', null, options);
		}
	});
	
	$(".gal_choose").bind('click', function(e){
		var image_id = $(this).attr('id').substring(4);
		if ($(this).hasClass('chosen')){
			if (typeof commission != 'undefined' && commission > 0){
				$.get('/imagedb/commission/removeimage', {'id':image_id, 'gallery':commission});
			} else {
				$.get('/imagedb/imagegroup/removeimage', {'id':image_id, 'gallery':gallery});
			}
			$(this).removeClass('chosen');
		} else {
			var sortorder = $(".chosen").size();
			if (typeof commission != 'undefined' && commission > 0){
				$.get('/imagedb/commission/addimage', {'id':image_id, 'gallery':commission, 'sortorder':sortorder});
			} else {
				$.get('/imagedb/imagegroup/addimage', {'id':image_id, 'gallery':gallery, 'sortorder':sortorder});
			}
			$(this).addClass('chosen');
		}
		e.preventDefault();
	});
	
	$(".sortable_images").sortable({
					'cursor':'pointer',
					'update':function(e, ui){
						var str = '';
						var x = 0;
						$(".sortable_images .image").each(function(){
							str += $(this).attr('id').substring(4) + ':' + x + ';';
							x++; 
						});
						$.get('/imagedb/imagegroup/sortimages', {'gallery':gallery, 'order':str});
					}
	});
	
	$(".gallery_active").bind('click', function(){
		var $checkbox = $(this);
		var gallery = $checkbox.val();
		$.get('/imagedb/imagegroup/activate', {'gallery':gallery}, function(){
			if (!$checkbox.is(':checked')){
				$checkbox.removeAttr('checked');
			} else {
				$checkbox.attr('checked', 'checked');
			}
		});
	});
	
	$(".commission_active").bind('click', function(){
		var $checkbox = $(this);
		var gallery = $checkbox.val();
		$.get('/imagedb/commission/activate', {'gallery':gallery}, function(){
			if (!$checkbox.is(':checked')){
				$checkbox.removeAttr('checked');
			} else {
				$checkbox.attr('checked', 'checked');
			}
		});
	});
});

function choose_image(id, name, path){
	window.opener.select_image(id, name, path);
	window.close();
}