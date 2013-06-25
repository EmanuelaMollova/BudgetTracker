$(document).ready(function(){
    $('#month_from_date').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    });

    $('#month_to_date').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    });
    
    $('#chbx').on('click', function(){
        $("input[type='checkbox']").prop("checked", $('#chbx').prop("checked"))
    });
});