$(document).ready(function() {
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    var fecha;

    var calendar = $('#calendar').fullCalendar({
        editable: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },

        events: $('#url').val(),

        // Convert the allDay from string to boolean
        eventRender: function(event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        select: function(start, end, allDay) {
            fecha = start;
            $('#myModal').modal('show'); //prompt('Atividad:');

           // var title = prompt('actividad:');
           /* if (title) {

                var end = end.formatDate(end,"yyyy-MM-dd HH:mm:ss");
                $.ajax({
                    url: 'http://localhost:8888/fullcalendar/add_events.php',
                    data: 'title='+ title+'&start='+ start +'&end='+ end +'&url='+ url ,
                    type: "POST",
                    success: function(json) {
                        alert('Added Successfully');
                    }
                });
                calendar.fullCalendar('renderEvent',
                    {
                        title: title,
                        start: start,
                        end: end,
                        allDay: allDay
                    },
                    true // make the event "stick"
                );
            }*/

            calendar.fullCalendar('unselect');
        },

        editable: true,
        eventDrop: function(event, delta) {
           /* var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
            $.ajax({
                url: 'http://localhost:8888/fullcalendar/update_events.php',
                data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
                type: "POST",
                success: function(json) {
                    alert("Updated Successfully");
                }
            });*/
        },
        eventResize: function(event) {
           /* var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
            $.ajax({
                url: 'http://localhost:8888/fullcalendar/update_events.php',
                data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
                type: "POST",
                success: function(json) {
                    alert("Updated Successfully");
                }
            });*/

        }

    });

    $('#myform1').submit(function(e){
        var url = $(this).attr('action');
        var extra_task = $('#extra_task').val();
        var activity = $('#activity').val();
        var hora = $('#hora').val();
        var sfecha = fecha;
        var data = {'actividad':activity,'fecha':sfecha,'hora':hora};
        if((activity == "")&& (extra_task ==""))
        {
            alert("Ingrese una tarea a realizar.");
            e.preventDefault();
            return;
        }
         else {
         if((activity == "")&& (extra_task !=""))
         {
         data = {'actividad':extra_task,'fecha':date,'hora':hora};

         }
         $.ajax({
         url: url,
         // headers:{'X-CSRF-TOKEN':csrf_test_name},
         type:('POST'),
         data:data,
         dataType:'json',
         success : function(response) {
         $('#myModal').modal('hide');
         $.post(base_url,function(data){
         window.location = current_url;
         });
         },
         error: function(response){
         //si hay un error mostramos un mensaje

         alert(response);
         }
         });
         }
    });

});