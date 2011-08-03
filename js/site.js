$(document).ready(function(){
	
	if ($("#calendar").length > 0){
	
		$("#calendar").fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: true,
			buttonText: {
				prev: '&nbsp;&#9668;&nbsp;',
				next: '&nbsp;&#9658;&nbsp;',
				prevYear: '&nbsp;&lt;&lt;&nbsp;',
				nextYear: '&nbsp;&gt;&gt;&nbsp;',
				today: 'I dag',
				month: 'Måned',
				week: 'Uge',
				day: 'Dag'
			},
			firstDay: 1,
			monthNames: ['Januar','Februar','Marts','April','Maj','Juni','Juli','August','September','Oktober','November','December'],
			monthNamesShort: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Okt','Nov','Dec'],
			dayNames: ['Søndag','Mandag','Tirsdag','Onsdag','Torsdag','Fredag','Lørdag'],
			dayNamesShort: ['Søn','Man','Tir','Ons','Tor','Fre','Lør'],
			timeFormat: {
				'': 'HH:MM',
				agenda: 'HH:MM{ - HH:MM}'
			},
			eventMouseover : function(event, jsEvent, view){
				// Put the overlay in the right position to begin with
				$(".cal_mo").css({
					top:jsEvent.pageY-(parseInt($(".cal_mo").height())+15),
					left:jsEvent.pageX-(parseInt($(".cal_mo").width())/2)
				});
				// Make sure the element data moves as the mouse does
				$(jsEvent.target).mousemove(function(e){
					$(".cal_mo").css({
						top:e.pageY-(parseInt($(".cal_mo").height())+15),
						left:e.pageX-(parseInt($(".cal_mo").width())/2)
					});
				});
				// Show correct data overlay
				$(".cal_mo").html('<strong>Kursus:</strong> ' + event.title + '<br /><strong>Underviser:</strong> ' + event.teacher + '<br /><strong>Varighed:</strong> ' + event.totalClasses + ' uger' + '<br /><strong>Niveau: </strong>' + event.class_level).fadeIn('fast');
			},
			eventMouseout  : function(event, jsEvent, view){
				// Hide overlay
				$(".cal_mo").fadeOut('fast');
			},
			eventClick: function(event, jsEvent, view){
				window.location.href = window.location.href + '/detaljer/' + event.id;
			}
		});

		if (typeof calendar_data != 'undefined'){
			for (var i in calendar_data){
				var start_class = parseInt(calendar_data[i].first_class);
				var end_class = 0;
				for (var x = 0; x < parseInt(calendar_data[i].total_classes); x++){
					if (x > 0){
						start_class = (3600*24*7)+parseInt(start_class);
					}
					end_class = start_class+parseInt(calendar_data[i].class_length);
					var event = {
						id		: calendar_data[i].id,
						title	: calendar_data[i].name,
						start	: start_class,
						end 	: end_class,
						editable: false,
						allDay	: false,
						
						totalClasses : calendar_data[i].total_classes,
						teacher	: calendar_data[i].teacher,
						class_level  : calendar_data[i].class_level
					};	
					$("#calendar").fullCalendar('renderEvent', event, true);
				}
			}
		}
	}
	
});