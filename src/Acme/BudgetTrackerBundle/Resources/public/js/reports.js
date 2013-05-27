(function(){
    
    $('#all').on('click', function (e) {
        e.preventDefault();
        $("input[type='checkbox']").attr('checked', 'checked');
    });
    
    $('#none').on('click', function (e) {
        e.preventDefault();
        $("input[type='checkbox']").removeAttr('checked');
    });
    
    $('#month_start_date').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    });
    
    $('#month_end_date').datepicker({
        format: 'dd-mm-yyyy',
        weekStart: 1,
        autoclose: true
    });
    
}) ();