$(document).ready(function() {

    $('#desde').mask('99/99/9999');
    $('#datepicker').mask('99/99/9999');
    $("#hora").mask('99:99');
    $("#horaf").mask('99:99');
    $("#hora_inicio").mask('99:99');
    $("#hora_fin").mask('99:99');
    //$('#fecha_flujo').mask('99/99/9999');
   // var dateArray= ["09/03/2017", "11/03/2017"]
    var fechas = $('#fechas').val();
    if (fechas == "" || fechas == undefined) {
        $("#fecha_flujo").multiDatesPicker();
    }
    else {

        var days = fechas.split(',');

        $("#fecha_flujo").multiDatesPicker({
            addDates: days
        });
    }
    $.datepicker._selectDateOverload = $.datepicker._selectDate;
    $.datepicker._selectDate = function (id, dateStr) {
        var target = $(id);
        var inst = this._getInst(target[0]);
        if (target[0].multiDatesPicker != null) {
            inst.inline = true;
            this._selectDateOverload(id, dateStr);
            inst.inline = false;
            target[0].multiDatesPicker.changed = false;
        } else {
            this._selectDateOverload(id, dateStr);
            //target[0].multiDatesPicker.changed = false;
        }
        this._updateDatepicker(inst);
    };


    var grupoFlujo = $('#grupoFlujo').val();
    if((grupoFlujo != "") && (grupoFlujo != undefined))
    {
        var grupo = grupoFlujo.split(',');
        $('#grupo_participantes').val(grupo).trigger('chosen:updated');
    }


    var pickerOpts = {

        defaultDate: $('#datepicker').val(),
        dateFormat: 'dd/mm/yy'

    };
    $.datepicker.setDefaults($.datepicker.regional["es"]);
    $("#datepicker" ).datepicker(pickerOpts);
    $('#desde').datepicker({
        defaultDate: "+1w",
       // changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
       // minDate: '+3',
        onSelect: function (selectedDate) {
                dateMin = $('#desde').datepicker("getDate");
                var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 1);
                var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(), dateMin.getDate() + 1);
                $('#hasta').val($.datepicker.formatDate('dd/mm/yy', new Date(rMax)));
                var d2 = $(this).datepicker("getDate");
                dateMin.setDate(dateMin.getDate() + 1);
                d2.setDate(d2.getDate() + 30);
                $("#hasta").datepicker("option", "minDate", dateMin);
                $("#hasta").datepicker("option", "maxDate", d2);


        }
    });

    $('#hasta').mask('99/99/9999');

    $('#hasta').datepicker({
        dateFormat: 'dd/mm/yy'
    });
    $('#fecha').datepicker({
        dateFormat: 'dd/mm/yy',

    });
    $('#fecha').mask('99/99/9999');
    $('#fecha_task_subor').mask('99/99/9999');
    $('#fecha_task_subor').datepicker({
        dateFormat: 'dd-mm-yy'
    });
    $('#flujo_desde').mask('99-99-9999');
    $('#flujo_desde').datepicker({
        dateFormat: 'dd-mm-yy'
    });
    $('#flujo_hasta').mask('99-99-9999');
    $('#flujo_hasta').datepicker({
        dateFormat: 'dd-mm-yy'
    });


    //validacion de formulario
    $('#vacaciones').validate();
    $('#calendar-form').validate();
    $('#predefinida_form').validate();
    $('#updPre').validate();
    $('#preDiaFecha').validate();
    $('#updPreDiaFecha').validate();
    $('#PreFecha').validate();
    $('#updPreFecha').validate();
    $('#myform').validate();
    $('#updPlan').validate();
    $('#dateform').validate();
    $('#flujo_form').validate();

    //experimentos mios
    var day = $('#day').val();
    var month = $('#month').val();
    var fecha = new Date();
    var ano = fecha.getFullYear();
    var lastday = daysInMonth(month,ano);
    $("#range_date").ionRangeSlider({
        min:day,
        max:lastday,
        type: 'single',
        step: 1,
        prefix:"dia ",
        prettify: false,
        hasGrid: true

    });
    function daysInMonth(humanMonth, year) {
        return new Date(year || new Date().getFullYear(), humanMonth, 0).getDate();
    }

});
