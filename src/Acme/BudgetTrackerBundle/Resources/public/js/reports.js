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

    $('u').on('mouseover', function(){
       $(this).css('cursor', 'pointer'); 
    });
    
    $('u').on('click', function(ev){
        ev.preventDefault();
        var elem = $(this).text();
        $('.'+elem).fadeToggle();
    });
    
    $('.toggle_expenses').on('click', function(){
       $('.products').fadeToggle();  
    });
    
    $('#chbx').on('click', function(){
        $("input[type='checkbox']").prop("checked", $('#chbx').prop("checked"))
    });
});