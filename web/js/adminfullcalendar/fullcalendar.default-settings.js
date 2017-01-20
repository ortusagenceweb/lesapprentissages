$(function () {
    $('#calendar-holder').fullCalendar({
        header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listWeek'
		},
		navLinks: true, // can click day/week names to navigate views
		editable: false,
		eventLimit: true, // allow "more" link when too many events
		events: {
			url: fullPath + '/calendar',
			/*url: '/admin/calendar',*/
			type: 'POST',
			error: function() {
				alert('Il y a eu une erreur lors de la récupération des évènements du calendrier !');
			},
			overlap: false,
		},
		timeFormat: 'H(:mm)',
		eventStartEditable: false,
		now: Date.now(),
		eventAfterAllRender: function(view) {
			$("#overlay").hide();
			$("#overlay1").hide();
		},
		loading: function (isLoading, view) {
			if(isLoading) {
				$("#overlay").show();
				$("#overlay1").show();
			} else {
				$("#overlay").hide();
				$("#overlay1").hide();
			}
		}
    });
});
