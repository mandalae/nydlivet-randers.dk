$(document).ready(function(){
	$(".imagedb").bind('click', function(e){
		OpenImageDB(this);
		e.preventDefault();
	});
	
	$(".thumbnails li").bind('click', function(e){
		var image_id = $(this).attr('id').substring(4);
		$.get('/getimage', {'id':image_id, 'width':434, 'height':330, 'type':'src'}, function(html){
			if (html != ''){
				$("#image img").attr('src', html);
			}
		});
	});
	
});

function OpenImageDB(link){
	var width = 1005;
	var height = 700;

	var topvar = (screen.height / 2) - (height / 2);
	var leftvar = (screen.width / 2) - (width / 2);

	try
	{
		var imageDBSelector = window.open(link.href, "imageDBSelector", "width=" + width + ",height=" + height + ",top=" + topvar + ",left=" + leftvar + ",scrollbars,status,resizable");
		imageDBSelector.focus();
	}
	catch (exception)
	{
		alert("Der skete en fejl, da popupvinduet til billededatabase skulle genereres. Tjek din browsers indstillinger.");
	}
}

function select_image(id, name, path){
	$("[name='"+name+"']").val(id);
	$("#img_"+name).attr('src', path);
}