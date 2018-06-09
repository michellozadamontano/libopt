$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $( "#datepicker" ).datepicker($.datepicker.regional[ "es" ]);
    $( "#date_plan" ).datepicker($.datepicker.regional[ "es" ] );

    $('#my_multi_select').multiSelect({
        selectableHeader: "<div class='custom-header'>Subordinados</div>",
        selectionHeader: "<div class='custom-header'>Seleccionados</div>"

    });
    $('input#search-multi').quicksearch('select#my_multi_select');


    $('#select-all').click(function(){
        $('#my_multi_select').multiSelect('select_all');
        return false;
    });
    $('#deselect-all').click(function(){
        $('#my_multi_select').multiSelect('deselect_all');
        return false;
    });
    $('#list_select').multiSelect({
        selectableHeader: "<div class='custom-header'>Tareas</div>",
        selectionHeader: "<div class='custom-header'>Incumplidas</div>",
        selectableOptgroup: true,
        minWidth:300
    });
    $('#list_task').select2({
        placeholder: "Seleciona tarea",
        allowClear: true
    });
    setTimeout(function() {
        $("#opinion_div").fadeOut(1500);
    },3000);
    var date;
    $('.effects').on('click',(function(e){
        e.preventDefault();
        date = $(this).attr('href');
        $('#date_task').empty();
        $('#date_task').append(date);
        $('#myModal').modal('show');
    }));
    function timeToSeconds(time) {
        time = time.split(/:/);
        return time[0] * 3600 + time[1] * 60;
    }
    function analiseSeconds(time) {
        time = time.split(/:/);
        var result = false;
        if((time[0]*1<0)||(time[0]*1>23)||(time[1]*1<0)||(time[1]*1>59)){
            result = true;
        }
        return result;
    }

    $('#myform').submit(function(e){
        var url = $(this).attr('action');
        var extra_task = $('#extra_task').val();
        var activity = $('#activity').val();
        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        var data = {'actividad':activity,'fecha':date,'hora':hora};
        if((activity == "")&& (extra_task ==""))
        {
            alert("Ingrese una tarea a realizar.");
            e.preventDefault();
            return;
        }
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }
       /* else {
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
        }*/
    });
    //aqui voy a hacer algunos efectos visuales para no enredar tanto al usuario
    $('#row_rango').hide();
    $('#checkbox6').on('click',function (e) {
        if($('#checkbox6').prop('checked'))
        {
            $('#row_rango').fadeIn('slow');
        }
        else {
            $('#row_rango').fadeOut('slow');
        }
    });

    $('#flujo_form').submit(function(e){

        var activity = $('#actividad').val();
        var grupoFlujo = $('#grupo_participantes').val();
        var hora = $('#hora_inicio').val();
        var horaf = $('#hora_fin').val();
        var flujo_desde = $('#flujo_desde').val();
        var flujo_hasta = $('#flujo_hasta').val();
        var flujo_fecha = $('#fecha_flujo').val();

        if(activity == "")
        {
            alert("Ingrese una tarea a realizar.");
            e.preventDefault();
            return;
        }
        if(grupoFlujo == null)
        {
            alert("Debe existir al menos un Grupo de participantes.");
            e.preventDefault();
            return;
        }

        if($('#checkbox6').prop('checked'))
        {
            if(flujo_desde == "" || flujo_hasta == "")
            {
                alert("Seleccione un rango de fecha por favor.");
                e.preventDefault();
                return;
            }
            if(fechaCorrecta(flujo_desde,flujo_hasta))
            {
                alert("La fecha desde no puede ser mayor que la fecha hasta.");
                e.preventDefault();
                return;
            }
        }
        if(flujo_fecha == "" &&(!$('#checkbox6').prop('checked')))
        {
            alert('Seleccione al menos una fecha o un rango de fecha');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds(horaf);
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }
    });

    $("#date_plan").on('change',function(){
        var selected_date = $(this).val();
        var data = {'fecha':selected_date};
        var url = $('#url').val();
        $.ajax({
            url: url,
            // headers:{'X-CSRF-TOKEN':csrf_test_name},
            type:('POST'),
            data:data,
            dataType:'json',
            success : function(response) {
             //   var res = JSON.parse(response);
                $('#view').html(response.page);
            },
            error: function(response){
                //si hay un error mostramos un mensaje

                alert(response);
            }
        });
    });
    $('#form_report').submit(function(e) {

        $('#error').empty();
        if($('#task').val() == "")
        {
            $('#error').html('Inserte las tareas principales');
            e.preventDefault();
        }
    });

    $("#grupo_id").change(function() {
        $("#grupo_id option:selected").each(function() {
            var grupo_id = $('#grupo_id').val();
            var url = $('#dir_activity').val();

                $.post(url, {
                    grupo_id : grupo_id
                }, function(data) {
                    $("#activity").html(data);
                });
        });
    })
    //animaciones
    $('#hora').attr('disable');
    $('#td').change(function(){
        if($('#td').prop('checked') )
        {

            $('#hora').prop('disable',true);
        }
        else
        {
            $('#hora').prop('disable',false);
        }

    });

    function fechaCorrecta(fecha1, fecha2){

        //Split de las fechas recibidas para separarlas
        var x = fecha1.split('/');
        var z = fecha2.split('/');

        //Cambiamos el orden al formato americano, de esto dd/mm/yyyy a esto mm/dd/yyyy
        fecha1 = x[2] + '-' + x[1] + '-' + x[0];
        fecha2 = z[2] + '-' + z[1] + '-' + z[0];

        //Comparamos las fechas
        return Date.parse(fecha1) > Date.parse(fecha2);
    }
    $('#vacaciones').submit(function(e){
        var result = fechaCorrecta($('#desde').val(),$('#hasta').val())
        if(result){
            alert('La fecha desde no puede ser mayor que hasta');
            e.preventDefault();
        }

    });
    $('#updPreDiaFecha').submit(function(e){

        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }

        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }

    });
    $('#updPre').submit(function(e){

        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }

        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }

    });
    $('#updPreFecha').submit(function(e){

        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }

        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }

    });
    $('#preDiaFecha').submit(function(e){

        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }

        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }

    });
    $('#PreFecha').submit(function(e){

        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }

        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }

    });
    $('#predefinida_form').submit(function(e){

        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }

        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }

    });
    $('#dateform').submit(function(e){

        var hora = $('#hora').val();
        var horaf = $('#horaf').val();
        if(analiseSeconds(hora))
        {
            alert('La hora inicial está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }
        if(analiseSeconds(horaf))
        {
            alert('La hora final está en formato incorrecto, debe estar entre las 00:00 y 23:00');
            e.preventDefault();
            return;
        }

        var startTime = timeToSeconds(hora);
        var endTime = timeToSeconds($("#horaf").val());
        if(startTime > endTime){
            alert("La hora inicial no puede ser mayor que la hora final.");
            e.preventDefault();
            return;
        }

    });

});
